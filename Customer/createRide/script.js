document.addEventListener('DOMContentLoaded', () => {

    // Calls functions when page loads
    populateDates()

    // Styles dropdowns for mobile
    const isMobile = window.matchMedia("(max-width: 768px)").matches;
    if (isMobile){
        styleDropdown()
    }
    

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