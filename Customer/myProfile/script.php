<?php header("Content-type: application/javascript");?>


document.addEventListener('DOMContentLoaded', async () => {
    loadInfo()
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

async function loadInfo(){

    // Grabs userId cookie
    const userId = document.cookie.split('; ').find(cookie => cookie.startsWith('user_id='))?.split('=')[1];

    // Query userdata
    const userData = await queryDB(`SELECT * FROM users WHERE user_id = ${userId}`);

    // Populate name fields
    document.querySelector('#firstName input').value = userData[0].first_name;
    document.querySelector('#lastName input').value = userData[0].last_name;

    // Populate contact fields
    document.querySelector('#emailInput').value = userData[0].email;
    document.querySelector('#phoneNumberInput').value = userData[0].phone_number;

    // Parse and populate date of birth dropdowns
    const [year, month, day] = userData[0].date_of_birth.split('-').map(Number);

    document.querySelector('select[name="day"]').value = day;
    document.querySelector('select[name="month"]').value = month;
    document.querySelector('select[name="year"]').value = year;
}