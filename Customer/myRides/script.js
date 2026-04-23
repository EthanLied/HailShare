
// Global Vars
let currentTab = "hosted"
let currentSubTab = "ongoing"
let isMobile
let rideItemsPerPage
let rideItems

document.addEventListener('DOMContentLoaded', () => {

    isMobile = window.matchMedia("(max-width: 768px)").matches;

    // Calls functions when page loads
    switchTab('hosted');

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
function switchTab(selectedTab){

    // Updates global var
    currentTab = selectedTab

    // Grabs 2 tabs
    const hostedRidesTab = document.getElementById("hostedRidesTab")
    const joinedRidesTab = document.getElementById("joinedRidesTab")

    // Changes tab contrast
    hostedRidesTab.style.backgroundColor = (selectedTab === 'hosted' ) ? "rgb(255, 255, 255)" : "transparent"
    joinedRidesTab.style.backgroundColor = (selectedTab === 'joined' ) ? "rgb(255, 255, 255)" : "transparent"

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
function switchSubtab(selectedSubTab){
    
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