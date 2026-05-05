<!doctype html>
<html>

<head>
    <!--Imports-->
    <link rel="stylesheet" href="../shadCNTemplate.php">
    <link rel="stylesheet" href="desktop.php">
    <link rel="stylesheet" href="navbar.php">
    <link rel="stylesheet" href="mobile.php">
    <script src="script.php" defer></script>
    <script src="../../Database/DBfunctions.php" defer></script>
    <title>My Rides</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--Google SVG Imports-->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>

    <div id="header" class="mobileComponent">
        <a>
            <h2>Hailshare</h2>
        </a>
    </div>
    
    <!--Navbar-->
    <div id="navbar">

        <!--Hamburger menu and Logo-->
        <div class="navbarItem">
            <span class="material-symbols-outlined" id="hamburgerMenuNavbarIcon" onclick="toggleNavbar()"> menu
            </span> <!-- "Onclick" triggers menu expansion / shrinking-->
            <a href="">
                <h3>Hailshare</h3>
            </a>
        </div>

        <!--Ride List Icon-->
        <a href="../rideList/index.html">
            <div class="navbarItem" href="">
                <span class="material-symbols-outlined">list_alt</span>
                <p>Ride List</p>
            </div>
        </a>

        <!--Create Ride Icon-->
        <a href="../createRide/index.html">
            <div class="navbarItem">
                <span class="material-symbols-outlined"> add_circle</span>
                <p>Create Ride</p>
            </div>
        </a>

        <!--My Rides Icon-->
        <a>
            <div class="navbarItem">
                <span class="material-symbols-outlined"> event_available</span>
                <p>My Rides</p>
            </div>
        </a>

        <!--Customer Support Icon-->
        <a href="../customerSupport/index.html">
            <div class="navbarItem">
                <span class="material-symbols-outlined">support_agent</span>
                <p>Customer Support</p>
            </div>
        </a>

        <!--My Profile Icon-->
        <a href="../myProfile/index.html">
            <div class="navbarItem">
                <span class="material-symbols-outlined"> account_circle</span>
                <p>My Profile</p>
            </div>
        </a>
    </div>

    <div id="content">
        <h1>My Rides</h1>

        <div id="tabList">
            <a id="hostedRidesTab" onclick="switchTab('hosted')">Hosted Rides</a>
            <a id="joinedRidesTab" onclick="switchTab('joined')">Joined Rides</a>
        </div>

        <div id="ongoingPastSelectorContainer">
            <a id="ongoingRidesTab" onclick="switchSubtab('ongoing')">
                <p>Ongoing Rides</p>
                <div id="ongoingIndicatorBar"></div>
            </a>
            <a id="pastRidesTab" onclick="switchSubtab('past')">
                <p>Past Rides</p>
                <div id="pastIndicatorBar"></div>
            </a>
        </div>

        <div id="rideRecordContainer">
        </div>

        <div id="pagination">
            <p id="paginationLabel">Page:&nbsp;</p>

            <button class="btnNormal paginationBtn" onclick="updatePagination(-1)">
                <span class="material-symbols-outlined">chevron_backward</span>
                <p>Previous&nbsp;&nbsp;</p>
            </button>

            <input id="paginationSelector" value="1" oninput="processPagination(this.value)">

            <p> &nbsp;/&nbsp;
            <p id="paginationTotal">20</p>
            </p>

            <button class="btnNormal paginationBtn" onclick="updatePagination(1)">
                <p>&nbsp;&nbsp;Next</p>
                <span class="material-symbols-outlined">chevron_forward</span>
            </button>
        </div>

</body>

</html>