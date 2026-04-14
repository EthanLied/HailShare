


document.addEventListener('DOMContentLoaded', () => {

    // Calls functions when page loads
    paginationLoad();
})

// Applys pagination when page loads
function paginationLoad() {

    // Grabs all rows
    const tableRows = document.querySelectorAll('#tableContainer > table tr')

    // Converts multiple rows to array
    const tableRowArray = Array.from(tableRows)

    // Selects everything except the first 10
    tableRowArray.slice(11).forEach(tableRowArray => {

        // Hides the rest of them
        tableRowArray.style.display = 'none';
    });

    // Grabs the pagination total value
    const paginationTotal = document.getElementById("paginationTotal");

    // Adjusts it accordingly from number of rows detected
    paginationTotal.innerText = `${Math.ceil(Array.from(tableRows).length / 10)}`

}

function processPagination(selectedPage){
    
    // Grabs all rows
    const tableRows = document.querySelectorAll('#tableContainer > table tr:not(.tableHeaderRow)');

    // Converts multiple rows to array
    const tableRowArray = Array.from(tableRows)

    // Early return and no modification for the following conditions:
    // If input value > available pages
    // If input is empty
    // If input < 1
    // If input is not a num
    if (selectedPage > Math.ceil(tableRowArray.length / 10) || selectedPage === "" || selectedPage < 1 || Number.isNaN(Number(selectedPage))){
        return;
    }

    // Selects everything 
    tableRowArray.forEach(tableRowArray => {

        // Hides all of them
        tableRowArray.style.display = 'none';
    });

    // Selects only those defined by pagination selection
    const sliceStart = 10 * (selectedPage - 1);
    const sliceEnd = sliceStart + 10;

    tableRowArray.slice(sliceStart, sliceEnd).forEach(tableRowArray => {

        // Reveals them
        tableRowArray.style.display = 'table-row';
    });

}

// Update pagination from button inputs
function updatePagination(offset) {

    // Grabs all rows
    const tableRows = document.querySelectorAll('#tableContainer > table tr:not(.tableHeaderRow)');

    // Grabs the current pagination value
    const input = document.getElementById('paginationSelector');

    // Converts multiple rows to array
    const tableRowArray = Array.from(tableRows)

    // Finds total possible pagination pages
    const totalPages = Math.ceil(tableRowArray.length / 10);
    
    // Get the current pagination value and apply the offset
    let selectedPage = parseInt(input.value) + offset;

    // If the previous value inputed is not valid
    if ( selectedPage > totalPages ||  selectedPage < 1 ||  isNaN(selectedPage) ||  input.value === "") {

        // Defaults to 1
        selectedPage = 1;
    }

    // Display the updated value into input box
    input.value = selectedPage;

    processPagination(selectedPage);
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