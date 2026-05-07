<?php header("Content-type: application/javascript");?>

let rideChatId
let userId
let senderName
let lastUpdateTimeStamp

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

async function loadMessages(){

    const rideId = document.cookie.split('; ').find(row => row.startsWith('ride_id='))?.split('=')[1];
    userId = document.cookie.split('; ').find(cookie => cookie.startsWith('user_id='))?.split('=')[1];

    // Displays rideId
    document.getElementById("rideId").innerText = `${rideId}`

    chatroomExistence = await queryDB(`SELECT * FROM ride_chat_rooms WHERE ride_id = ${rideId}`)

    // If this is NOT an existing chatroom, add to DB
    if (chatroomExistence.length != 1){
        await queryDB(`
        INSERT INTO ride_chat_rooms (ride_id, status) 
        VALUES (${rideId}, 'active')`)
        return;
    }

    // Grabs all messages of a chatroom
    rideChatId = chatroomExistence[0].ride_chat_id
    const allMessages = await queryDB(
        `SELECT * FROM ride_chat_messages 
        WHERE ride_chat_id = '${rideChatId}' 
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
    await queryDB(`INSERT INTO ride_chat_messages (ride_chat_id, sender_user_id, message_content) VALUES ('${rideChatId}', '${userId}', '${message}')`)

    appendMessages(message, senderName, "outgoing")

    // Scroll to the bottom
    const el = document.getElementById("scrollableContent");
    el.scrollTop = el.scrollHeight;

}

async function refreshMessages(){

    const newMessages = await queryDB(`SELECT * FROM ride_chat_messages WHERE sent_at > '${lastUpdateTimeStamp}' AND sender_user_id <> ${userId}`)

    for (const message of newMessages){
        const nameRecord = await queryDB(`SELECT first_name FROM users WHERE user_id = '${message.sender_user_id}'`)
        const incomingName = nameRecord[0].first_name
        appendMessages(message.message_content, incomingName, "incoming")
    }

    lastUpdateTimeStamp = new Date().toLocaleString('sv-SE');

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