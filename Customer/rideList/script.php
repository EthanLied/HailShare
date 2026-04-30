<?php
header("Content-type: application/javascript");
?>

// Flag to test if current display is mobile or not
let isMobile;

document.addEventListener('DOMContentLoaded', () => {

    isMobile = window.matchMedia("(max-width: 768px)").matches;

    // Calls functions when page loads
    paginationLoad();

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
    document.getElementById('popupSeats').textContent = `${rideData.seats} / 4`;
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
