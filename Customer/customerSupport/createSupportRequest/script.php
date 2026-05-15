<?php header("Content-type: application/javascript");?>
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

async function submitSupportRequest(){
    
    const userId = await grabCookie('user_id')
    const issueType = document.getElementById("issueDropdown").value
    const additionalNotes = document.getElementById("additionalNotesTextarea").value

    await queryDB(`
        INSERT INTO support_chat_rooms (customer_user_id, issue_type, additional_notes)
        VALUES ('${userId}', '${issueType}', '${additionalNotes}')
    `)

    window.location.href = '../index.php'

}