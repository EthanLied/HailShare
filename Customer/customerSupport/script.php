<?php header("Content-type: application/javascript");?>
let currentTab = 'ongoing'

document.addEventListener('DOMContentLoaded', async () => {

    isMobile = window.matchMedia("(max-width: 768px)").matches;

    await loadChats()

    // Calls functions when page loads
    switchTab('ongoing');

    // Loads pagination
    paginationLoad()
    
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

// Switches tab on click
function switchTab(selectedTab){

    // Updates global var
    currentTab = selectedTab

    // Grabs 2 tabs
    const ongoingChatsTab = document.getElementById("ongoingChatsTab")
    const pastChatsTab = document.getElementById("pastChatsTab")

    // Changes tab contrast
    ongoingChatsTab.style.backgroundColor = (selectedTab === 'ongoing' ) ? "rgb(255, 255, 255)" : "transparent"
    pastChatsTab.style.backgroundColor = (selectedTab === 'past' ) ? "rgb(255, 255, 255)" : "transparent"

    // Changes each element item either to visible or invisible depending which tab group they belong
    document.querySelectorAll('.ongoing').forEach(el => {
        el.style.display = (selectedTab === 'ongoing') ? "flex" : "none";
    });

    document.querySelectorAll('.past').forEach(el => {
        el.style.display = (selectedTab === 'past') ? "flex" : "none";
    });

    // Updates pagination
    paginationLoad()
    const paginationInput = document.querySelector("#paginationSelector")
    paginationInput.value = "1"
}

// Applys pagination when page loads
function paginationLoad() {

    rideItems = document.querySelectorAll(`.${currentTab}`)
    rideItemsPerPage = (isMobile) ? 5 : 10

    // Converts multiple rows to array
    const rideItemsArray = Array.from(rideItems)

    // Selects everything except the first 10
    rideItemsArray.slice(rideItemsPerPage + 1).forEach(rideItemsArray => {

        // Hides the rest of them
        rideItemsArray.style.display = 'none';
    });

    // Grabs the pagination total value
    const paginationTotal = document.getElementById("paginationTotal");
    
    // Adjusts it accordingly from number of rows detected
    paginationTotal.innerText = `${Math.ceil(Array.from(rideItems).length / rideItemsPerPage)}`

}


function processPagination(selectedPage) {

    rideItems = document.querySelectorAll(`.${currentTab}`)

    // Converts multiple rows to array
    const rideItemsArray = Array.from(rideItems)

    // Early return and no modification for the following conditions:
    // If input value > available pages
    // If input is empty
    // If input < 1
    // If input is not a num
    if (selectedPage > Math.ceil(rideItemsArray.length / rideItemsPerPage) || selectedPage === "" || selectedPage < 1 || Number.isNaN(Number(selectedPage))) {
        return;
    }

    // Selects everything 
    rideItemsArray.forEach(rideItemsArray => {

        // Hides all of them
        rideItemsArray.style.display = 'none';

    });

    // Selects only those defined by pagination selection
    const sliceStart = rideItemsPerPage * (selectedPage - 1);
    const sliceEnd = sliceStart + rideItemsPerPage;

    rideItemsArray.slice(sliceStart, sliceEnd).forEach(rideItemsArray => {

        // Reveals them
        rideItemsArray.style.display = 'flex';
    });

}

// Update pagination from button inputs
function updatePagination(offset) {

    rideItems = document.querySelectorAll(`.${currentTab}`)
    
    // Converts multiple rows to array
    const tableRowArray = Array.from(rideItems)

    // Grabs the current pagination value
    const input = document.getElementById('paginationSelector');

    // Finds total possible pagination pages
    const totalPages = Math.ceil(tableRowArray.length / rideItemsPerPage);

    // Get the current pagination value and apply the offset
    let selectedPage = parseInt(input.value) + offset;

    // If the previous value inputed is not valid
    if (selectedPage > totalPages || selectedPage < 1 || isNaN(selectedPage) || input.value === "") {

        // Defaults to 1
        selectedPage = 1;
    }

    // Display the updated value into input box
    input.value = selectedPage;

    // Updates paginaiton contents
    processPagination(selectedPage);
}

async function loadChats(){

    // Grabs userId cookie
    const userId = document.cookie.split('; ').find(cookie => cookie.startsWith('user_id='))?.split('=')[1];

    const supportChatRooms = await queryDB(`
        SELECT * FROM support_chat_rooms
        WHERE customer_user_id = ${userId}
    `)

    // Grabs container to append
    const container = document.getElementById('recordRowContainer');

    for (const supportChatRoom of supportChatRooms){
        const issueType = supportChatRoom.issue_type
        const timeOpened = supportChatRoom.started_at
        const timeClosed = supportChatRoom.ended_at
        const additionalNotes = supportChatRoom.additional_notes
        const agentNameQueried = await queryDB(`
            SELECT first_name FROM users 
            WHERE user_id = '${supportChatRoom.staff_user_id}'`)
        const agentName = agentNameQueried[0].first_name

        if (supportChatRoom.status === 'active'){
            container.innerHTML += `
                <div class="chatItem ongoing">
                    <div class="leftSideItems">
                        <p class="issueTypeLabel">Issue Type: <span>${issueType}</span></p>
                        <p>Time Opened: <span>${timeOpened}</span></p>
                        <p>Additional Notes: <span class="additionalNotes">${additionalNotes}</span></p>
                        <p>Agent assigned: <span>${agentName}</span></p>
                    </div>
                    <div class="rightSideItems">
                        <a href="chatroom/index.php">
                            <button class="btnNormal">Chatroom <span class="material-symbols-outlined">chat</span></button>
                        </a>
                        <a>
                            <button class="btnNormal closeChatBtn">Close Chat <span class="material-symbols-outlined">cancel</span></button>
                        </a>
                    </div>
                </div>`
        }
        else{
            const timeClosed = supportChatRoom.ended_at

            container.innerHTML += `
                <div class="chatItem past">
                    <div class="leftSideItems">
                        <p class="issueTypeLabel">Issue Type: <span>${issueType}</span></p>
                        <p>Time Closed: <span>${timeClosed}</span></p>
                        <p>Additional Notes: <span class="additionalNotes">${additionalNotes}</span></p>
                    </div>
                    <div class="rightSideItems">
                        <a href="chatroom/index.php">
                            <button class="btnNormal">Chatroom <span class="material-symbols-outlined">chat</span></button>
                        </a>
                        <p>Agent assigned: <span>${agentName}</span></p>
                    </div>
                </div>`
        }
    }
}