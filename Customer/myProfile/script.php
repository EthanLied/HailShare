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
    const userId = await grabCookie('user_id')

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

async function saveNonSensitive(){

    // Grabs userId cookie
    const userId = await grabCookie('user_id')

    const firstName = document.querySelector('#firstName input').value;
    const lastName  = document.querySelector('#lastName input').value;
    const email     = document.querySelector('#emailInput').value;
    const phone     = document.querySelector('#phoneNumberInput').value;

    const day   = document.querySelector('#dobDropdowns select[name="day"]').value;
    const month = document.querySelector('#dobDropdowns select[name="month"]').value;
    const year  = document.querySelector('#dobDropdowns select[name="year"]').value;

    // Padstart to provide leading '0' for single digit month / day
    const dob = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`; 

    await queryDB(`
        UPDATE users
        SET first_name = '${firstName}', last_name = '${lastName}', email = '${email}', phone_number = '${phone}', date_of_birth = '${dob}'
        WHERE user_id = ${userId}
    `)

    alert('Profile Updated!')
}

async function saveSensitive(){

    // Grabs userId cookie
    const userId = await grabCookie('user_id')

    // Grabs stored password
    let storedHash = await queryDB(`
        SELECT password_hash from users
        WHERE user_id = ${userId}
    `)
    storedHash = storedHash[0].password_hash

    // Grabs user input
    const currentPasswordInput = document.getElementById("currentPasswordInput").value
    const newPasswordInput = document.getElementById("newPasswordInput").value
    const securityQuestionDropdown = document.getElementById("securityQuestionDropdown").value
    const securityQuestionInput = document.getElementById("securityAnswerInput").value

    // Crypto obj and validating password
    const bcrypt = dcodeIO.bcrypt; // Hash comparision obj
    const isMatch = await bcrypt.compare(currentPasswordInput, storedHash);
   
    if (!isMatch){
        alert("Password does not match saved password!")
        return
    }

    const newPasswordHash = await bcrypt.hash(newPasswordInput, 10)

    // Add fields dynamically if user inputed them
    const fields = [];

    // Stops single quotes from breaking strings
    const escape = str => str.replace(/'/g, "\\'");
    if (newPasswordHash)          fields.push(`password_hash = '${escape(newPasswordHash)}'`);
    if (securityQuestionDropdown) fields.push(`security_question = '${escape(securityQuestionDropdown)}'`);
    if (securityQuestionInput)    fields.push(`security_question_answer = '${escape(securityQuestionInput)}'`);

    if (fields.length === 0) return; // nothing to update

    await queryDB(`
        UPDATE users
        SET ${fields.join(', ')}
        WHERE user_id = ${userId}
    `)

    alert("Data Saved!")

}

function openDeletePrompt(){
    const deleteBtn = document.getElementById("deleteAccountBtn")
    
    deleteBtn.remove()

    const importantBtnsContainer = document.getElementById("importantBtns")

    importantBtnsContainer.innerHTML = `
        <button class="btnStrong" id="deleteAccountBtn" onclick="deleteAccount()">YES, DELETE!</button>
        <button class="btnStrong" id="logoutBtn" onclick="closeDeletePrompt()">NO, GO BACK!</button>
    `
}


function closeDeletePrompt(){

    const deleteBtn = document.getElementById("deleteAccountBtn")
    
    deleteBtn.remove()

    const importantBtnsContainer = document.getElementById("importantBtns")

    importantBtnsContainer.innerHTML = `
        <button class="btnStrong" id="logoutBtn" onclick="logout()">Logout</button>
          <button class="btnStrong" id="deleteAccountBtn" onclick="openDeletePrompt()">
            Delete Account
          </button>
    `
}

async function deleteAccount(){

    const userId = await grabCookie('user_id')

    console.log(`
        DELETE FROM users
        WHERE user_id = ${userId}
    `)

    await queryDB(`
        DELETE FROM users
        WHERE user_id = ${userId}
    `)


    window.location.href = '../../Admin/Homepage/index.php'

}

function logout(){
    window.location.href = '../../Admin/Homepage/index.php'
}

