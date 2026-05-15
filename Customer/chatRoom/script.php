<?php header("Content-type: application/javascript");?>

let userId
let senderName
let lastUpdateTimeStamp = new Date().toLocaleString('sv-SE');

document.addEventListener('DOMContentLoaded', async () => {

    loadMessages()

    setInterval(async () => {await refreshMessages();}, 2000);
})

// Closes and opens navbar
function toggleNavbar() {

    // Grabs navbar component 
    const navbar = document.getElementById("navbar")

    // Grabs all navbar item components
    const navbarItems = document.querySelectorAll(".navbarItem");

    // Grabs content div
    const content = document.getElementById("content")

    // Triggers the "expand" attribute of the Navbar
    navbar.classList.toggle("expand")

    // Triggers the "expand" attribute of ALL classes matching "navbarItems" 
    navbarItems.forEach(navbarItems => {
        navbarItems.classList.toggle("expand");
    });

    // Triggers the "expand" attribute of the content
    content.classList.toggle("expand")
}

let chatroomType
let chatroomTableName
let chatroomIncrementingIdValue
let chatroomIdValue

async function loadMessages(){

    // Grabs cookies
    userId = await grabCookie('user_id')
    chatRoomCookieId = await grabCookie('chat_room_id')
    supportChatRoomCookieId = await grabCookie('support_chat_room_id')

    // Seperate between normal ride chatrooms and support request chatrooms
    chatroomType = await grabCookie('chatroom_type')

    if (chatroomType === 'ride'){
        chatroomTableName = 'ride_chat_rooms'
        chatroomMessagesTableName = 'ride_chat_messages'
        chatroomOwnerName = 'guest_user_id'
        chatroomFkName = 'ride_chat_id'
        chatroomId = 'ride_id'
        chatroomIdValue = chatRoomCookieId

        const incrementingIdResult = await queryDB(`
            SELECT ride_chat_id FROM ride_chat_rooms
            WHERE ride_id = ${chatroomIdValue}
        `)

        chatroomIncrementingIdValue = incrementingIdResult[0].ride_chat_id
    }
    else{
        chatroomTableName = 'support_chat_rooms'
        chatroomMessagesTableName = 'support_chat_messages'
        chatroomOwnerName = 'customer_user_id'
        chatroomFkName = 'support_chat_id'
        chatroomId = 'support_chat_id'
        chatroomIdValue = supportChatRoomCookieId
        chatroomIncrementingIdValue = await grabCookie('support_chat_room_id')
    }

    // Displays chatroom ID
    document.getElementById("chatroomId").innerText = `${chatroomIdValue}`

    // Hides staff components if needed
    document.querySelectorAll('.staffChat').forEach(el => el.style.display = (chatroomType === 'ride') ? 'none' : 'flex');
    chatroomExistence = await queryDB(`SELECT * FROM ${chatroomTableName} WHERE ${chatroomId} = ${chatroomIdValue}`)

    // Lock chatroom if closed
    if (chatroomExistence[0].status !== 'active'){
        document.getElementById("sendMessageContainer").style.display = 'none'
        document.getElementById("scrollableContent").style.opacity = 0.5
        document.getElementById("chatroomId").innerText = `` 
        document.getElementById("chatroomId").innerHTML = `${chatroomIdValue} <strong>(CLOSED)</strong>` 
    }

    // Set staff assigned
    if (chatroomType != 'ride'){
    
        staffQueryName = await queryDB(`
            SELECT first_name from users
            WHERE user_id = ${chatroomExistence[0].staff_user_id}
        `)

        let staffName = "Waiting for Support Agent";

        if (staffQueryName && staffQueryName.length > 0) {
            staffName = staffQueryName[0].first_name || "Waiting for Support Agent";
        }
    
        document.getElementById("staffAssignedName").innerText = staffName
    }

    // Grabs all messages of a chatroom
    const allMessages = await queryDB(
        `SELECT * FROM ${chatroomMessagesTableName}
        WHERE ${chatroomFkName} = '${chatroomIncrementingIdValue}' 
        ORDER BY sent_at ASC`
    );

    const outgoingName = await queryDB(`SELECT first_name from users WHERE user_id = '${userId}'`)
    senderName = outgoingName[0].first_name;
    const messageContainer = document.getElementById("messageContent")

    for (const message of allMessages){

        // If it's outoging message
        if (message.sender_user_id === userId){
            appendMessages(message.message_content, senderName, "outgoing")
        }

        // If it's incoming message
        else{
            const incomingName = await queryDB(`SELECT first_name from users WHERE user_id = '${message.sender_user_id}'`)
            appendMessages(message.message_content, incomingName[0].first_name, "incoming")
        }

    }

}

async function sendMessage(){

    // Grab text content
    const textbox = document.getElementById("textBox")
    const message = textbox.value

    // Clear textarea
    document.getElementById("textBox").value = ""

    // Dont do anything if length of message is empty
    if (message.length < 1){
        return;
    }

    // Write to DB
    await queryDB(`
        INSERT INTO ${chatroomMessagesTableName} (${chatroomFkName}, sender_user_id, message_content) 
        VALUES ('${chatroomIncrementingIdValue}', '${userId}', '${message}')
    `)

    appendMessages(message, senderName, "outgoing")

    // Scroll to the bottom
    const el = document.getElementById("scrollableContent");
    el.scrollTop = el.scrollHeight;

}

async function refreshMessages(){

    const newMessages = await queryDB(`SELECT * FROM ${chatroomMessagesTableName} WHERE sent_at > '${lastUpdateTimeStamp}' AND sender_user_id <> ${userId}`)

    lastUpdateTimeStamp = new Date().toLocaleString('sv-SE');

    for (const message of newMessages){
        const nameRecord = await queryDB(`SELECT first_name FROM users WHERE user_id = '${message.sender_user_id}'`)
        const incomingName = nameRecord[0].first_name
        appendMessages(message.message_content, incomingName, "incoming")
    }

}

function appendMessages(textContent, name, type){
    
    // Create message box
    const messageContainer = document.getElementById("messageContent")

    const msgDiv = document.createElement("div");
    const msgText = document.createElement("p");
    const msgSender = document.createElement("p");

    // Message Content
    msgText.textContent = textContent;

    // Sender name
    msgSender.classList.add("sentBy");
    msgSender.textContent = name

    // Type of msg
    msgDiv.classList.add(type === "outgoing" ? "outgoingMsg" : "incomingMsg");

    // Append to containers 
    msgDiv.appendChild(msgText);
    msgDiv.appendChild(msgSender);
    messageContainer.appendChild(msgDiv);
}

function goBack(){

    if ((chatroomType === 'ride')){
        window.location.href = '../myRides/index.php'
    }
    else{
        window.location.href = '../customerSupport/index.php'
    }
}

async function closeRequest(){

    await queryDB(`
        UPDATE support_chat_rooms
        SET status = 'closed', ended_at = NOW()
        WHERE support_chat_id = ${chatroomIdValue}
    `)

    window.location.href = "../customerSupport/index.php"
}