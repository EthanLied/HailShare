<?php
header("Content-type: application/javascript");
?>
<?php require '../../Database/DBfunctions.php'; ?>

document.addEventListener('DOMContentLoaded', () => {

    // Calls functions when page loads
    populateDates()

    // Styles dropdowns for mobile
    const isMobile = window.matchMedia("(max-width: 768px)").matches;
    if (isMobile){
        styleDropdown()
    }
    
    // Close both dropdowns at the start
    toggleAddressDropdown('close', 'from', 'load')
    toggleAddressDropdown('close', 'to', 'load')

})

// Closes and opens navbar
function toggleNavbar() {

    // Grabs navbar component 
    const navbar = document.getElementById("navbar")

    // Grabs content div
    const content = document.getElementById("content")

    // Triggers the "expand" attribute of the Navbar
    navbar.classList.toggle("expand")

    // Grabs all navbar item components
    const navbarItems = document.querySelectorAll(".navbarItem");

    // Triggers the "expand" attribute of ALL classes matching "navbarItems" 
    navbarItems.forEach(navbarItems => {
        navbarItems.classList.toggle("expand");
    });

    // Triggers the "expand" attribute of the content
    content.classList.toggle("expand")
}

function populateDates(){

    const dateSelector = document.getElementById("dateDropdown")

    // Grabs date object
    let date = new Date();

    // Loop for the next 30 days
    for (let i = 0; i < 30; i++) {

        // Options struct to tell the date object the format to return the date
        const options = { weekday: 'short', month: 'short', day: 'numeric' };

        // Returns the date data as an english string with pre-defined options
        const dateString = date.toLocaleDateString('en-US', options);
        
        // Splits at 'T' to cut off at hour "YYYY-MM-DDTHH:mm:ss.sssZ"
        // Grabs index 0 to get left-hand date string
        const isoValue = date.toISOString().split('T')[0];

        // Creates new option component
        const dateOption = document.createElement("option");

        // Appends value
        dateOption.value = isoValue;

        // If today, give special formatting
        dateOption.innerHTML = i === 0 ? `Today (${dateString})` : dateString;
        
        // Append option
        dateSelector.appendChild(dateOption);

        // Increments date
        date.setDate(date.getDate() + 1);
    }

}

function styleDropdown(){
    document.querySelectorAll("select").forEach(select => {
        
        // Hides native select styles
        select.style.display = "none";

        // Wraps a div for all our select elements
        const container = document.createElement("div");
        container.className = "custom-select-container";
        select.parentNode.insertBefore(container, select);
        container.appendChild(select); // Move native select inside

        // Create the clickable trigger box
        const trigger = document.createElement("div");
        trigger.className = "custom-select-trigger";
        trigger.innerText = select.options[select.selectedIndex]?.text || "";
        container.appendChild(trigger);

        // Popup container
        const popup = document.createElement("div");
        popup.className = "custom-popup";
        
        // Populate the popup with existing <option> data
        Array.from(select.options).forEach(opt => {
            const item = document.createElement("div");
            item.className = "custom-popup-item";

            // Sets text from <select> data
            item.innerText = opt.text;
            
            // If click option
            item.addEventListener("click", () => {
                select.value = opt.value;     // Update the hidden native select
                trigger.innerText = opt.text; // Update the visual UI
                popup.style.display = "none"; // Close popup
                
            });

            // Append option to popup menu
            popup.appendChild(item);
        });

        // Adds to container with custom dropdown
        container.appendChild(popup);

        // Toggle popup open/close on click
        trigger.addEventListener("click", (e) => {

            // Limits click event from influencing parrents
            e.stopPropagation();

            // Close all other open popups first (singleton)
            document.querySelectorAll(".custom-popup").forEach(p => {
                if (p !== popup) p.style.display = "none";
            });

            // Toggle the current one only
            popup.style.display = popup.style.display === "block" ? "none" : "block";
        });
    });

    // Close any open popups if the user clicks somewhere else on the page
    document.addEventListener("click", () => {
        document.querySelectorAll(".custom-popup").forEach(p => p.style.display = "none");
    });

}

let returnedLocations = [];
let fromLong = 0;
let fromLat = 0;
let toLong = 0;
let toLat = 0;
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

    // Reset 
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


async function submitRide() {

    const from = document.getElementById("fromInput").value;
    const to = document.getElementById("toInput").value;
    const date = document.getElementById("dateDropdown").value;
    const hour = document.getElementById("hourInput").value;
    const minute = document.getElementById("minuteInput").value;
    const datetime = `${date} ${parseTo24h(hour)}:${minute}:00`;
    const capacity = document.getElementById("capacityInput").value;
    const price = document.getElementById("priceInput").value;
    const alert = document.getElementById("alert")

    if (fromLat == 0 || fromLong == 0 || toLat == 0 || toLong == 0) {
        alert.innerText = "Please click on an address presented!"
        alert.style.color = "red"
    }
    else if (isDateTimeInPast(date, hour, minute)) {
        alert.innerText = "Selected time must be after now!"
        alert.style.color = "red"
    }
    else {
        alert.innerText = ""
        alert.style.color = "white"
        const cookies = document.cookie.split('; ');
        const userID = cookies.find(c => c.startsWith('user_id' + '=')).split('=')[1];
        const addToRide = `INSERT INTO \`rides\` (\`user_id\`, \`pickup_location\`, \`pickup_lat\`, \`pickup_long\`, \`dropoff_location\`, \`dropoff_lat\`, \`dropoff_long\`, \`price\`, \`pickup_time\`, \`available_seats\`, \`status\`, \`completed_at\`, \`created_at\`, \`updated_at\`) ` +
        `VALUES ('${userID}', '${from}', '${fromLat}', '${fromLong}', '${to}', '${toLat}', '${toLong}', '${price}', '${datetime}', '${capacity}', 'active', DATE('${datetime}') + INTERVAL 1 DAY, NOW(), NULL)`;
        await queryDB(addToRide);
        console.log("Sent DB Query: " + addToRide)

        const rideIdResult = await queryDB(`SELECT ride_id FROM rides ORDER BY ride_id DESC LIMIT 1`);
        const rideId = rideIdResult[0].ride_id;

        const addToRideParticipants = `INSERT INTO ride_participants (ride_id, user_id) VALUES ('${rideId}', '${userID}')`;
        await queryDB(addToRideParticipants);
        console.log("Sent DB Query: " + addToRideParticipants)

        window.location.href = '../myRides/index.php';
    }

}

// Convert "12AM", "1PM" etc into 24h number
function parseTo24h(hourStr) {
    const isPM = hourStr.includes("PM");
    const isAM = hourStr.includes("AM");
    let h = parseInt(hourStr); // strips AM/PM, grabs number

    if (isAM && h === 12) return 0;  // 12AM = midnight = 0
    if (isPM && h !== 12) return h + 12; // 1PM = 13, 2PM = 14 etc
    return h; // 12PM = 12, 1AM = 1 etc
}

function isDateTimeInPast(date, hour, minute) {

    const hour24 = parseTo24h(hour);

    // Create a date object from the selected values
    const selectedDateTime = new Date(`${date}T${String(hour24).padStart(2, '0')}:${minute}:00`);

    // Compare against now
    const now = new Date();

    return selectedDateTime < now;
}