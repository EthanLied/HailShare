<?php
header("Content-type: application/javascript");
?>

// Flag to test if current display is mobile or not
let isMobile;

// Cords of selected dropoff and pickup locations
let toLong = 0
let toLat = 0
let fromLong = 0
let fromLat = 0

document.addEventListener('DOMContentLoaded', async () => {

    isMobile = window.matchMedia("(max-width: 768px)").matches;

    // Close both dropdowns at the start
    toggleAddressDropdown('close', 'from', 'load')
    toggleAddressDropdown('close', 'to', 'load')

})

// Applys pagination when page loads
function paginationLoad() {

    let rideItems
    let rideItemsPerPage 

    if (isMobile) {
        rideItemsPerPage = 5;
        rideItems = document.querySelectorAll('.rideItemMobile')
    }
    else {
        rideItemsPerPage = 10;
        rideItems = document.querySelectorAll('#tableContainer > table tr:not(.tableHeaderRow)');
    }

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

    if (isMobile) {
        rideItemsPerPage = 5;
        rideItems = document.querySelectorAll('.rideItemMobile')
    }
    else {
        rideItemsPerPage = 10;
        rideItems = document.querySelectorAll('#tableContainer > table tr:not(.tableHeaderRow)');
    }

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
        if (isMobile) {
            rideItemsArray.style.display = 'flex';
        }
        else{
            rideItemsArray.style.display = 'table-row';
        }
    });

}

// Update pagination from button inputs
function updatePagination(offset) {

    if (isMobile) {
        rideItemsPerPage = 5;
        rideItems = document.querySelectorAll('.rideItemMobile')
    }
    else {
        rideItemsPerPage = 10;
        rideItems = document.querySelectorAll('#tableContainer > table tr:not(.tableHeaderRow)');
    }
    
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

    processPagination(selectedPage);
}

function showPopup(buttonElement){

    // Early return if not mobile
    if (!isMobile){
        return
    }

    // Import required html elements to transition
    const popupMenu = document.querySelector('#popupMenu')
    const darkenOverlay = document.querySelector('#darkenOverlay')

    // Apply transitions
    popupMenu.style.transform = `translateY(2%)`
    darkenOverlay.style.backgroundColor = `rgba(0, 0, 0, 0.6)`
    darkenOverlay.style.pointerEvents = `all`

    // Imports the parent div of the button pressed
    const rideContainer = buttonElement.closest('.rideItemMobile');

    // Allows read / writing of data attributes
    const rideData = rideContainer.dataset;

    // Updates pickup and dropoff addresses
    document.getElementById('popupPickupSpecific').textContent = rideData.pickupSpecific;
    document.getElementById('popupPickupGeneral').textContent = rideData.pickupGeneral;
    document.getElementById('popupDropoffSpecific').textContent = rideData.dropoffSpecific;
    document.getElementById('popupDropoffGeneral').textContent = rideData.dropoffGeneral;

    // Updates pickup and dropoff distance
    document.getElementById('popupPickupDistance').textContent = rideData.pickupDistance;
    document.getElementById('popupDropoffDistance').textContent = rideData.dropoffDistance;

    // Updates time
    const formattedTime = rideData.time.replace(',', ' ');
    document.getElementById('popupTime').textContent = formattedTime;

    // Updates seats and price
    document.getElementById('popupSeats').textContent = `${rideData.seats}`;
    document.getElementById('popupNormalPrice').textContent = rideData.price
    document.getElementById('popupDiscountedPrice').textContent = parseFloat(rideData.price) / (parseInt(rideData.seats) + 1);
}

function closePopup(){

    // Reverts back transitions
    const popupMenu = document.querySelector('#popupMenu')
    const darkenOverlay = document.querySelector('#darkenOverlay')

    popupMenu.style.transform = `translateY(100%)`
    darkenOverlay.style.backgroundColor = `rgba(0, 0, 0, 0.0)`
    darkenOverlay.style.pointerEvents = `none`
}

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

async function loadRecords() {

    const records = await readDB('rides');

    const table = document.getElementById('desktopTable');
    const tableContainer = document.getElementById('tableContainer');

    // Completes any rides to be completed
    await queryDB(`UPDATE rides SET completed_at = DATE(created_at) + INTERVAL 1 DAY WHERE pickup_time <= NOW() - INTERVAL 1 DAY AND completed_at IS NULL`);

    // Clear existing records
    while (table.rows.length > 1) {
        table.deleteRow(1);
    }

    tableContainer.querySelectorAll('.rideItemMobile').forEach(el => el.remove());

    for (const record of records) {

        const peopleInside = await queryDB(`SELECT COUNT(*) FROM ride_participants WHERE ride_id = ${record.ride_id}`);
        
        if (peopleInside[0]['COUNT(*)'] == record.available_seats || isBeforeNow(record.pickup_time)){
            continue; // skip this record, dont return (that stops the whole loop)
        }

        const count = parseInt(peopleInside[0]['COUNT(*)']);
        const splitPrice = (record.price / (count + 1)).toFixed(2);

        if (isMobile) {
            const pickupParts = record.pickup_location.split(',');
            const pickupSpecific = pickupParts[0].trim();
            const pickupGeneral = pickupParts.slice(1).join(',').trim();

            const dropoffParts = record.dropoff_location.split(',');
            const dropoffSpecific = dropoffParts[0].trim();
            const dropoffGeneral = dropoffParts.slice(1).join(',').trim();

            const item = document.createElement('div');
            item.className = 'rideItemMobile mobileComponent';

            // Aria Labels
            item.dataset.pickupSpecific = pickupSpecific;
            item.dataset.pickupGeneral = pickupGeneral;
            item.dataset.dropoffSpecific = dropoffSpecific;
            item.dataset.dropoffGeneral = dropoffGeneral;
            item.dataset.pickupDistance = getDistance(fromLat, fromLong, record.pickup_lat, record.pickup_long);
            item.dataset.dropoffDistance = getDistance(toLat, toLong, record.dropoff_lat, record.dropoff_long);
            item.dataset.time = formatTime(record.pickup_time);
            item.dataset.seats = `${count} / ${record.available_seats}`;
            item.dataset.price = record.price;
            item.innerHTML = `
                <div class="rideItemMobileRow1">
                    <div class="rideItemMobileRowLeft">
                        <p><span class="rideItemMobileLabel">From: </span>${record.pickup_location}</p>
                    </div>
                    <div class="rideItemMobileRowRight">
                        <p><span class="rideItemMobileLabel">To: </span>${record.dropoff_location}</p>
                    </div>
                </div>
                <div class="rideItemMobileRow2">
                    <p><span class="material-symbols-outlined">schedule</span><span>&ensp;</span>${formatTime(record.pickup_time)}</p>
                    <p><span class="material-symbols-outlined">attach_money</span>RM${record.price}</p>
                </div>
                <button class="btnStrong rideItemMobileBtn" onClick="showPopup(this)">View More</button>
            `;
            tableContainer.appendChild(item);

        } else {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><p>${record.pickup_location}</p></td>
                <td><p>${record.dropoff_location}</p></td>
                <td>${getDistance(fromLat, fromLong, record.pickup_lat, record.pickup_long)}</td>
                <td>${getDistance(toLat, toLong, record.dropoff_lat, record.dropoff_long)}</td>
                <td>${formatTime(record.pickup_time)}</td>
                <td>${count} / ${record.available_seats}</td>
                <td>RM <s>${parseFloat(record.price).toFixed(0)}</s> ${splitPrice}</td>
                <td>
                    <a href="../myRides/chatRoom/index.html">
                        <span class="material-symbols-outlined">chat</span>
                    </a>
                </td>
                <td>
                    <a href="../myRides/index.html">
                        <span class="material-symbols-outlined">directions_car</span>
                    </a>
                </td>
            `;
            table.appendChild(row);
        }
    }
}

function formatTime(datetimeStr) {
    const date = new Date(datetimeStr);
    return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
}

function isBeforeNow(datetimeStr) {
    const pickupTime = new Date(datetimeStr);
    const now = new Date();
    return pickupTime < now;
}

function getDistance(lat1, lon1, lat2, lon2) {
    const R = 6371000; // Earth's radius in meters
    const toRad = deg => deg * (Math.PI / 180);

    const dLat = toRad(lat2 - lat1);
    const dLon = toRad(lon2 - lon1);

    const a = Math.sin(dLat / 2) ** 2 +
              Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
              Math.sin(dLon / 2) ** 2;

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    distanceInMeters = R * c;

    if (distanceInMeters >= 1000) {
        return `${(distanceInMeters / 1000).toFixed(1)}km`;
    }
    return `${Math.round(distanceInMeters)}m`;
}

// Address Input Funcs
// Store queries addresses from dropdown
let returnedLocations = [];

// Used to handle dropdown selection not registering
let closeTimer = null;

// Only activate address resolveAddress after set time, this var holds that time
let debounceTimer = null;

// Used to cancel API requests if double sent
let activeController = null;


async function resolveAddress(addressInputType, addressQuery) {

    // Clears previous addresses
    returnedLocations = []

    // Reset based on input type
    if (addressInputType === "to"){
        toLat = 0
        toLong = 0
    }
    else{
        fromLat = 0
        fromLong = 0
    }

    // Reset timer to cancel API request
    clearTimeout(debounceTimer);

    // Cancel any ongoing API requests
    if (activeController) activeController.abort();

    // Dont bother query if too short, just edit display 
    if (addressQuery.length < 3) {
        
        displaySuggestedLocations(addressInputType)
        return;
    }

    // Only activate after 500ms
    debounceTimer = setTimeout(async () => {
        try {
            // Controller obj to cancel API requests
            activeController = new AbortController();

            // Uses photon as external api
            const res = await fetch(
                `https://photon.komoot.io/api/?q=${encodeURIComponent(addressQuery)}&bbox=99.6418,0.8538,119.2758,7.3634`,
                { signal: activeController.signal } // Makes activeController manage ongoing API requests
            );

            // Grabs data back
            const data = await res.json();

            console.log(data);
            captureQueryData(data)
            console.log(returnedLocations)

            displaySuggestedLocations(addressInputType)


        } catch (err) {
            console.error(err);
        }
    }, 500);
}

function captureQueryData(data) {

    const fields = ["name", "street", "district", "city", "postcode", "country"];

    for (locationSuggestionNum = 0; locationSuggestionNum < Math.min(data.features.length, 5); locationSuggestionNum++) {

        const long = data.features[locationSuggestionNum].geometry.coordinates[0]
        const lat = data.features[locationSuggestionNum].geometry.coordinates[1]
        const currentsuggestionData = data.features[locationSuggestionNum].properties

        const address = fields
            // Loops over each iteam and add to string + conditional if value is blank
            .map(field => currentsuggestionData[field] ? `${currentsuggestionData[field]}, ` : "")
            .join("")
            .slice(0, -2); // removes trailing ", 

        returnedLocations.push({ "address": address, "long": long, "lat": lat });
    }

    return returnedLocations
}

function displaySuggestedLocations(addessInputType) {

    // Shows dropdown
    toggleAddressDropdown('open', addessInputType, 'query');

    // Show Dropdown func here
    const suggestedDropdowns = document.getElementById(addessInputType === "from" ? "fromAddressDropdown" : "toAddressDropdown")

    // For each dropdown item, show the address if available
    Array.from(suggestedDropdowns.children).forEach((child, index) => {
        const dropdownAddress = returnedLocations[index]?.address ?? "-----------------------------------------------------------------------------------------------------------";
        child.innerText = returnedLocations.length === 0 ? "Type to Search!" : dropdownAddress // If nothing, show nothing
    });

}

function selectDropdownLocation(idx, type, selectedAddress) {

    if (type === 'from') {
        fromLong = returnedLocations[idx].long ?? 0;
    } else {
        toLong = returnedLocations[idx].long ?? 0;
    }

    if (type === 'from') {
        fromLat = returnedLocations[idx].lat ?? 0;
    } else {
        toLat = returnedLocations[idx].lat ?? 0;
    }

    const inputElement = document.getElementById(type === 'from' ? "fromInput" : "toInput")
    inputElement.value = selectedAddress

    toggleAddressDropdown('close', type, 'select')
}

function toggleAddressDropdown(mode, type, trigger) {
    console.log(trigger)
    const addressDropdown = type === "from" ? document.getElementById("fromAddressDropdown") : document.getElementById("toAddressDropdown");

    if (trigger === 'offFocus') {
        // Wait 50ms in case 'select' comes in later
        closeTimer = setTimeout(() => {
            addressDropdown.style.display = mode === 'close' ? 'none' : 'flex';
        }, 100);

    } else {
        // Cancel the delay and run immediately
        clearTimeout(closeTimer);
        addressDropdown.style.display = mode === 'close' ? 'none' : 'flex';
    }

}

async function searchRides(){

    const alert = document.getElementById("alert");

    if (fromLat == 0 || fromLong == 0 || toLat == 0 || toLong == 0) {
        
        alert.innerText = 'Please ensure both "From" and "To" locations are set!';
    }
    else{
        // Fetch records from DB
        await loadRecords();

        // update pagination
        paginationLoad();

        alert.innerText = ''
    }

    
}