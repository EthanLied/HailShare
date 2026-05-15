<?php header("Content-type: application/javascript");?>

document.addEventListener('DOMContentLoaded', async () => {

    checkIfCanRate()
    
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

// Imports all stars
const stars = document.querySelectorAll('#ratingContainer > span');

// Counter to retain which stars to keep filled
let selected = 0;

// Fill funct to toggle .filled 
const fill = (n) => stars.forEach((s, i) => s.classList.toggle('filled', i < n));

// For each star
stars.forEach((star, i) => {

    // If hover, Fills each star and previous stars
    star.addEventListener('mouseenter', () => fill(i + 1));

    // If click, Fills/Unfill each star and previous stars by toggling their current states
    star.addEventListener('click', () => { selected = selected === i + 1 ? 0 : i + 1; });
});

// Applies fill funct when user moves out of the container + fills in num stars retained with "selected" var
document.querySelector('#ratingContainer').addEventListener('mouseleave', () => fill(selected));


async function submitRating(){

    const rideId = await grabCookie('ride_id')
    const userId = await grabCookie('user_id')

    const rideHostResult = await queryDB(`
        SELECT user_id from rides
        WHERE ride_id = ${rideId}
    `)

    rideHost = rideHostResult[0].user_id

    await queryDB(`
        INSERT INTO ratings (ride_id, rater_user_id, rated_user_id, rating_score)
        VALUES (${rideId}, ${userId}, ${rideHost}, ${selected})
    `)

    window.location.href = "../index.php";
}

async function checkIfCanRate(){

    const rideId = await grabCookie('ride_id')
    const userId = await grabCookie('user_id')

    const result = await queryDB(`
        SELECT * FROM ratings
        WHERE rater_user_id = ${userId} AND ride_id = ${rideId}
    `)

    if (result.length === 1){
        const content = document.getElementById('content');

        // Remove last 3 child elements
        for (let i = 0; i < 3; i++) {
            content.removeChild(content.lastElementChild);
        }

        // Add notice
        const notice = document.createElement('p');
        notice.textContent = "You have already given a rating!";
        notice.id = "notice";
        content.appendChild(notice);
    }

}