document.addEventListener('DOMContentLoaded', () => {

    // Calls functions when page loads
    switchTab(1);
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
function switchTab(selectedTabNum){

    // Grabs 2 tabs
    const hostedRidesTab = document.getElementById("hostedRidesTab")
    const joinedRidesTab = document.getElementById("joinedRidesTab")

    // Changes tab contrast
    hostedRidesTab.style.backgroundColor = (selectedTabNum === 1 ) ? "rgb(216, 216, 216)" : "transparent"
    joinedRidesTab.style.backgroundColor = (selectedTabNum === 2 ) ? "rgb(216, 216, 216)" : "transparent"

    // Changes each element item either to visiblt or invisible depending which tab group they belong
    document.querySelectorAll('.tab1').forEach(el => {
        el.style.display = (selectedTabNum === 1 ) ? "flex" : "none";
    });

    document.querySelectorAll('.tab2').forEach(el => {
        el.style.display = (selectedTabNum === 2 ) ? "flex" : "none";
    });
}