<?php header("Content-type: application/javascript");?>

// Global Vars
let currentTab = "hosted"
let currentSubTab = "ongoing"
let isMobile
let rideItemsPerPage
let rideItems

document.addEventListener('DOMContentLoaded', async () => {

    isMobile = window.matchMedia("(max-width: 768px)").matches;

    // Load from DB
    await loadRides()

    // Refresh tabs
    switchTab('hosted')
    switchSubtab('ongoing')

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
function switchTab(selectedTab) {

    // Updates global var
    currentTab = selectedTab

    // Grabs 2 tabs
    const hostedRidesTab = document.getElementById("hostedRidesTab")
    const joinedRidesTab = document.getElementById("joinedRidesTab")

    // Changes tab contrast
    hostedRidesTab.style.backgroundColor = (selectedTab === 'hosted') ? "rgb(255, 255, 255)" : "transparent"
    joinedRidesTab.style.backgroundColor = (selectedTab === 'joined') ? "rgb(255, 255, 255)" : "transparent"

    // Changes each element item either to visible or invisible depending which tab group they belong
    document.querySelectorAll('.hosted').forEach(el => {
        el.style.display = (selectedTab === 'hosted' && el.classList.contains(currentSubTab)) ? "flex" : "none";
    });

    document.querySelectorAll('.joined').forEach(el => {
        el.style.display = (selectedTab === 'joined' && el.classList.contains(currentSubTab)) ? "flex" : "none";
    });

    // Updates pagination
    paginationLoad()
    const paginationInput = document.querySelector("#paginationSelector")
    paginationInput.value = "1"
}

// Switches subtab on click
function switchSubtab(selectedSubTab) {

    // Updates glbal var
    currentSubTab = selectedSubTab

    // Changes tab opacitiy depening on which is clicked
    const ongoingRidesTab = document.getElementById("ongoingRidesTab")
    const pastRidesTab = document.getElementById("pastRidesTab")
    const ongoingIndicatorBar = document.getElementById("ongoingIndicatorBar")
    const pastIndicatorBar = document.getElementById("pastIndicatorBar")

    ongoingRidesTab.style.opacity = (selectedSubTab === 'ongoing') ? 1 : 0.5
    pastRidesTab.style.opacity = (selectedSubTab === 'past') ? 1 : 0.5
    ongoingIndicatorBar.style.opacity = (selectedSubTab === 'ongoing') ? 1 : 0.3
    pastIndicatorBar.style.opacity = (selectedSubTab === 'past') ? 1 : 0.3

    // Changes each element item either to visiblt or invisible depending which tab group they belong
    document.querySelectorAll('.ongoing').forEach(el => {
        el.style.display = (selectedSubTab === 'ongoing' && el.classList.contains(currentTab)) ? "flex" : "none";
    });

    document.querySelectorAll('.past').forEach(el => {
        el.style.display = (selectedSubTab === 'past' && el.classList.contains(currentTab)) ? "flex" : "none";
    });

    // UPdates pagination
    paginationLoad()
    const paginationInput = document.querySelector("#paginationSelector")
    paginationInput.value = "1"
}

// Applys pagination when page loads
function paginationLoad() {

    rideItems = document.querySelectorAll(`.${currentTab}.${currentSubTab}`)
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

    rideItems = document.querySelectorAll(`.${currentTab}.${currentSubTab}`)

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

    rideItems = document.querySelectorAll(`.${currentTab}.${currentSubTab}`)

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

async function loadRides(){
    
    // Grabs userId cookie
    const userId = document.cookie.split('; ').find(cookie => cookie.startsWith('user_id='))?.split('=')[1];
    
    // Dont do anything if cookie not found
    if (!userId) return;

    const container = document.getElementById('rideRecordContainer');
    container.innerHTML = '';

    //
    // Hosted Rides
    //

    // Query hosted ride data
    const rides = await queryDB(`SELECT * FROM rides WHERE user_id = ${userId}`);

    // Seperate ongoing from past
    const now = new Date();
    const ongoingRides = (rides ?? []).filter(ride => ride.status === 'active');
    const pastRides = (rides ?? []).filter(ride => ride.status !== 'active');

    // Create each ongoing joined ride
    if (ongoingRides.length > 0) {
        for (const record of ongoingRides) {
            
            // Find participant count
            const participants = await queryDB(`SELECT COUNT(*) FROM ride_participants WHERE ride_id = ${record.ride_id}`);
            const count = parseInt(participants[0]['COUNT(*)']);

            // Create Item
            const div = document.createElement('div');
            div.className = 'ongoingRidesItem hosted ongoing';

            // Store data arias
            div.dataset.rideId = record.ride_id;
            div.dataset.userId = record.user_id;

            div.innerHTML = `
                <div class="leftSideItems">
                    <p class="rideIDLabel">Ride ID: <span>#${record.ride_id}</span></p>
                    <p>From: <span class="address">${record.pickup_location}</span></p>
                    <p>To: <span>${record.dropoff_location}</span></p>
                    <p>Time: <span>${formatTime(record.pickup_time)}</span></p>
                    <p>Capacity: <span>${count} / ${record.available_seats}</span></p>
                    <p>Price: <span>RM ${parseFloat(record.price).toFixed(2)}</span></p>
                </div>
                <div class="rightSideItems">
                    <a>
                        <button class="btnNormal importantBtn" onclick="closeRide('${record.ride_id}')">Close Ride <span class="material-symbols-outlined">close</span></button>
                    </a>
                    <a href="editRide/index.php">
                        <button class="btnNormal" onclick="document.cookie='ride_id=${record.ride_id}; max-age=2592000; path=/ '">Edit Ride <span class="material-symbols-outlined">edit</span></button>
                    </a>
                    <a href="chatRoom/index.php">
                        <button class="btnNormal" onclick="document.cookie='ride_id=${record.ride_id}; max-age=2592000; path=/'">Chatroom <span class="material-symbols-outlined">chat</span></button>
                    </a>
                </div>
            `;
            container.appendChild(div);
        }
    } else {
        const div = document.createElement('div');
        div.className = 'ongoingRidesItem hosted ongoing';
        div.innerHTML = `<p>Your rides will appear here!</p>`;
        container.appendChild(div);
    }

    // For each past hosted ride
    if (pastRides.length > 0) {
        for (const record of pastRides) {

            // Find participant count
            const participants = await queryDB(`SELECT COUNT(*) FROM ride_participants WHERE ride_id = ${record.ride_id}`);
            const count = parseInt(participants[0]['COUNT(*)']);

            // Find the time completed for each ride
            let timeEnded = formatTime(record.completed_at);
           
            // Create Item
            const div = document.createElement('div');
            div.className = 'rideHistoryItem hosted past';

            // Store data arias
            div.dataset.rideId = record.ride_id;
            div.dataset.userId = record.user_id;

            div.innerHTML = `
                <div class="leftSideItems">
                    <p class="rideIDLabel">Ride ID: <span>#${record.ride_id}</span></p>
                    <p>Capacity: <span>${count} / ${record.available_seats}</span></p>
                    <p>Price: <span>RM ${parseFloat(record.price).toFixed(2)}</span></p>
                    <p>From: <span class="address">${record.pickup_location}</span></p>
                </div>
                <div class="rightSideItems">
                    <a href="chatRoom/index.php">
                        <button class="btnNormal" onclick="document.cookie='ride_id=${record.ride_id}; max-age=2592000; path=/'">Chatroom <span class="material-symbols-outlined">chat</span></button>
                    </a>
                    <p>Time Hosted: <span>${formatTime(record.pickup_time)}</span></p>
                    <p>Time Ended: <span>${timeEnded}</span></p>
                    <p>To: <span>${record.dropoff_location}</span></p>
                </div>
            `;
            container.appendChild(div);
        }
    } else {
        const div = document.createElement('div');
        div.className = 'rideHistoryItem hosted past';
        div.innerHTML = `<p>Your rides will appear here!</p>`;
        container.appendChild(div);
    }
    

    //
    // Joined Rides
    //

    // Query ride participants
    const participantRows = await queryDB(`SELECT * FROM ride_participants WHERE user_id = ${userId}`);
    if (!participantRows || participantRows.length === 0) return;

    // Query joined rides with the ride participants returned above
    const joinedRideIds = participantRows.map(participant => participant.ride_id).join(',');
    const joinedRides = await queryDB(`SELECT * FROM rides WHERE ride_id IN (${joinedRideIds})`);

    // If no rides, dont do anything
    if (!joinedRides || joinedRides.length === 0) return;

    // Attach participant status to each ride
    // Adds a new key 'participantStatus' to the existing JSON
    const joinedRidesWithStatus = await joinedRides.map(ride => ({
        ...ride,
        participantStatus: participantRows.find(participantRows => participantRows.ride_id === ride.ride_id)?.status
    }));

    // Seperate ongoing from rest
    // Ignore self-hosted rides as they cannot be considered joined
    // Ongoing only counts if the ride is active, and the ride participant is also active
    const ongoingJoined = joinedRidesWithStatus.filter(ride => ride.status === 'active' && ride.participantStatus === 'active' && ride.user_id !== userId);
    const pastJoined = joinedRidesWithStatus.filter(ride => ride.status !== 'active' || ride.participantStatus !== 'active' && ride.user_id !== userId);

    // Create each ongoing joined ride
    if (ongoingJoined.length > 0) {
        for (const record of ongoingJoined) {

            // Find participant count
            const participants = await queryDB(`SELECT COUNT(*) FROM ride_participants WHERE ride_id = ${record.ride_id}`);
            const count = parseInt(participants[0]['COUNT(*)']);

            // Create ride item
            const div = document.createElement('div');
            div.className = 'ongoingRidesItem joined ongoing';

            // Store data arias
            div.dataset.rideId = record.ride_id;
            div.dataset.userId = record.user_id;

            div.innerHTML = `
                <div class="leftSideItems">
                    <p class="rideIDLabel">Ride ID: <span>#${record.ride_id}</span></p>
                    <p>Time: <span>${formatTime(record.pickup_time)}</span></p>
                    <p>Capacity: <span>${count} / ${record.available_seats}</span></p>
                    <p>Price: <span>RM ${parseFloat(record.price).toFixed(2)}</span></p>
                    <p>From: <span class="address">${record.pickup_location}</span></p>
                </div>
                <div class="rightSideItems">
                    <a>
                        <button class="btnNormal importantBtn" onclick="leaveRide(${record.ride_id}, ${userId})">Leave Ride <span class="material-symbols-outlined">logout</span></button>
                    </a>
                    <a href="chatRoom/index.php">
                        <button class="btnNormal" onclick="document.cookie='ride_id=${record.ride_id}; max-age=2592000; path=/'">Chatroom <span class="material-symbols-outlined">chat</span></button>
                    </a>
                    <p>To: <span>${record.dropoff_location}</span></p>
                </div>
            `;
            container.appendChild(div);
        }
    } else {
        const div = document.createElement('div');
        div.className = 'ongoingRidesItem joined ongoing';
        div.innerHTML = `<p>Your rides will appear here!</p>`;
        container.appendChild(div);
    }

    // Create each past joined ride
    if (pastJoined.length > 0) {
        for (const record of pastJoined) {

            // Find participant count
            const participants = await queryDB(`SELECT COUNT(*) FROM ride_participants WHERE ride_id = ${record.ride_id}`);
            const count = parseInt(participants[0]['COUNT(*)']);

            // Find the time completed for each ride
            let timeEnded = formatTime(record.completed_at);

            // Create ride item
            const div = document.createElement('div');
            div.className = 'rideHistoryItem joined past';

            // Store data arias
            div.dataset.rideId = record.ride_id;
            div.dataset.userId = record.user_id;

            div.innerHTML = `
                <div class="leftSideItems">
                    <p class="rideIDLabel">Ride ID: <span>#${record.ride_id}</span></p>
                    <p>Capacity: <span>${count} / ${record.available_seats}</span></p>
                    <p>Price: <span>RM ${parseFloat(record.price).toFixed(2)}</span></p>
                    <p>From: <span class="address">${record.pickup_location}</span></p>
                </div>
                <div class="rightSideItems">
                    <a href="chatRoom/index.php">
                        <button class="btnNormal" onclick="document.cookie='ride_id=${record.ride_id}; max-age=2592000; path=/'">Chatroom <span class="material-symbols-outlined">chat</span></button>
                    </a>
                    <a href="giveRating/index.php">
                        <button class="btnNormal" onclick="document.cookie='ride_owner_id=${record.user_id}; max-age=2592000; path=/'">Give Rating <span class="material-symbols-outlined">star</span></button>
                    </a>
                    <p>Time Hosted: <span>${formatTime(record.pickup_time)}</span></p>
                    <p>Time Ended: <span>${timeEnded}</span></p>
                    <p>To: <span>${record.dropoff_location}</span></p>
                </div>
            `;
            container.appendChild(div);
        }
    } else {
        const div = document.createElement('div');
        div.className = 'rideHistoryItem joined past';
        div.innerHTML = `<p>Your rides will appear here!</p>`;
        container.appendChild(div);
    }

    
}

function formatTime(datetimeStr) {
    const date = new Date(datetimeStr);
    return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
}

async function closeRide(rideId){
    
    // Query all ride participants for intended ride to close
    const participantRows = await queryDB(`SELECT * FROM ride_participants WHERE ride_id = ${rideId}`);

    // If participants, dont do anything
    if (!participantRows || participantRows.length === 0) return;
    
    // More than 1 participant, reject cancelation
    if (participantRows.length > 1){
        alert("Sorry! You can't close a ride if someone else has joined in that ride.")
        return;
    }

    // Else accept and close ride
    alert(`Ride #${rideId} closed.`)

    await queryDB(`UPDATE rides SET status = 'closed' WHERE ride_id = ${rideId}`)

    // Changes ride participant status also
    await queryDB(`UPDATE ride_participants SET status = 'completed' WHERE ride_id = ${rideId}`);
    location.reload();
}

async function leaveRide(rideId, userId){

    await queryDB(`UPDATE ride_participants SET status = 'left' WHERE ride_id = ${rideId} AND user_id = ${userId}`);

    location.reload();

}

