<!doctype html>
<html>

<head>
    <!--Imports-->
    <link rel="stylesheet" href="../shadCNTemplate.php">
    <link rel="stylesheet" href="desktopCSS.php">
    <link rel="stylesheet" href="tableCSS.php">
    <link rel="stylesheet" href="navbarCSS.php">
    <link rel="stylesheet" href="mobileCSS.php">
    <script src="script.php" defer></script>
    <script src="../../Database/DBfunctions.php" defer></script>
    <title>Ride List</title>

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

    <div id="popupMenu" class="mobileComponent">
        <div id="popupMenuLocations">
            <div id="pickupDropoffLogoContainer">
                <span class="material-symbols-outlined">
                    directions_car
                </span>
                <div id="pickupDropoffLogoLine"></div>
                <span class="material-symbols-outlined">
                    location_on
                </span>
            </div>
            <div id="pickupCard">
                <p>PICKUP</p>
                <p class="specificLocationName" id="popupPickupSpecific">Petronas Twin Tower</p>
                <p id="popupPickupGeneral">KLCC, 50088 KL</p>
            </div>
            <div id="dropoffCard">
                <p>DROPOFF</p>
                <p class="specificLocationName" id="popupDropoffSpecific">Jalan Punchak</p>
                <p id="popupDropoffGeneral">Off Jln P. Ramlee, 50250 KL</p>
            </div>
        </div>
        <div id="popupMenuDetailsContainer">
            <div class="popupMenuDetails">
                <p>Walk to Pickup</p>
                <p><span class="menuDetailsImportant" id="popupPickupDistance">63</span>&ensp;</p>
            </div>
            <div class="popupMenuDetails">
                <p>Pickup Time</p>
                <p><span class="menuDetailsImportant" id="popupTime">10:42</span>&ensp;</p>
            </div>
            <div class="popupMenuDetails">
                <p>Drop-off Walk</p>
                <p><span class="menuDetailsImportant" id="popupDropoffDistance">23</span>&ensp;</p>
            </div>
            <div class="popupMenuDetails">
                <p>Seats Taken</p>
                <p><span class="menuDetailsImportant" id="popupSeats"></span>&ensp;seats</p>
            </div>
        </div>
        <div id="popupMenuPriceAndSeats">
            <div id="yourFareContainer">
                <p>Price</p>
                <p><span id="fareDetailsImportant">RM <s class="negativePrice" id="popupNormalPrice">12</s> <span class="positivePrice" id="popupDiscountedPrice">6.00</span></span></p>
            </div>
            <div id="seatsAvailable"></div>
        </div>
        <div id="popupMenuBtns">
            <button class="btnNormal" onclick="closePopup()"><span class="material-symbols-outlined">
                    close
                </span>&ensp;Close
            </button>
            
            <button class="btnNormal" onclick="window.location.href='../myRides/chatRoom/index.php'"><span class="material-symbols-outlined">
                        chat
                </span>&ensp;Chat
            </button>

            <button class="btnStrong" id="joinRideBtn" onclick="window.location.href='../myRides/index.php'"><span class="material-symbols-outlined">
                    directions_car
                </span>&ensp;Join Ride
            </button>

        </div>
    </div>
    <div id="darkenOverlay" onclick="closePopup()"></div>

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
        <a>
            <div class="navbarItem">
                <span class="material-symbols-outlined">list_alt</span>
                <p>Ride List</p>
            </div>
        </a>

        <!--Create Ride Icon-->
        <a href="../createRide/index.php">
            <div class="navbarItem">
                <span class="material-symbols-outlined"> add_circle</span>
                <p>Create Ride</p>
            </div>
        </a>

        <!--My Rides Icon-->
        <a href="../myRides/index.php">
            <div class="navbarItem">
                <span class="material-symbols-outlined"> event_available</span>
                <p>My Rides</p>
            </div>
        </a>

        <!--Customer Support Icon-->
        <a href="../customerSupport/index.php">
            <div class="navbarItem">
                <span class="material-symbols-outlined">support_agent</span>
                <p>Customer Support</p>
            </div>
        </a>

        <!--My Profile Icon-->
        <a href="../myProfile/index.php">
            <div class="navbarItem">
                <span class="material-symbols-outlined"> account_circle</span>
                <p>My Profile</p>
            </div>
        </a>
    </div>

    <div id="content">
        <div id="addressInputs">
            <h1 id="rideListTitle">Ride List</h1>
            <p>From:</p>
            <div class="addressDropdownWrapper">
                <input name="fromAddress" id="fromInput" onfocus="resolveAddress('from', this.value)" oninput="resolveAddress('from', this.value)" onblur="toggleAddressDropdown('close', 'from', 'offFocus')">
                <div class="addressDropdown" id="fromAddressDropdown">
                    <div class = "addressDropdownItem" onclick="selectDropdownLocation('0', 'from', this.innerText)">
                    </div>
                    <div class = "addressDropdownItem" onclick="selectDropdownLocation('1', 'from', this.innerText)">
                    </div>
                    <div class = "addressDropdownItem" onclick="selectDropdownLocation('2', 'from', this.innerText)">
                    </div>
                    <div class = "addressDropdownItem" onclick="selectDropdownLocation('3', 'from', this.innerText)">
                    </div>
                    <div class = "addressDropdownItem" onclick="selectDropdownLocation('4', 'from', this.innerText)">
                    </div>
                </div>
            </div>
            <p>To:</p>
            <div class="addressDropdownWrapper">
                <input name="toAddress" id="toInput" onfocus="resolveAddress('to', this.value)" oninput="resolveAddress('to', this.value)" onblur="toggleAddressDropdown('close', 'to', 'offFocus')">
                <div class="addressDropdown" id="toAddressDropdown">
                    <div class = "addressDropdownItem" onclick="selectDropdownLocation('0', 'to', this.innerText)">
                    </div>
                    <div class = "addressDropdownItem" onclick="selectDropdownLocation('1', 'to', this.innerText)">
                    </div>
                    <div class = "addressDropdownItem" onclick="selectDropdownLocation('2', 'to', this.innerText)">
                    </div>
                    <div class = "addressDropdownItem" onclick="selectDropdownLocation('3', 'to', this.innerText)">
                    </div>
                    <div class = "addressDropdownItem" onclick="selectDropdownLocation('4', 'to', this.innerText)">
                    </div>
                </div>
            </div>
        </div>
        <button class="btnStrong" id="searchBtn" onclick="searchRides()">Search</button>
        <p id="alert"></p>
        <h2 id="ridesTitle">Rides</h2>
        <h4 class="mobileComponent">Sort By:</h4>
        <select id="sortByDropdown">
            <option>Time (Eariler)</option>
            <option>Time (Later)</option>
            <option>Pickup Distance (Ascending)</option>
            <option>Pickup Distance (Decending)</option>
            <option>Dropoff Distance (Decending)</option>
            <option>Dropoff Distance (Decending)</option>
            <option>Price (Cheaper)</option>
        </select>
        <div id="tableContainer">
            <div class="rideItemMobile mobileComponent" 
            data-pickup-specific="Petronas Twin Tower" data-pickup-general="KLCC, 50088 KL"
            data-dropoff-specific="Jalan Puncak" data-dropoff-general="Off Jln P. Ramlee, 50250 KL"
            data-pickup-distance="63" data-dropoff-distance="23"
            data-time="10:42,PM" data-seats="1"
            data-price="12">
                <p>Search results will appear here!</p>
            </div>
            <table class="desktopComponent" id="desktopTable">
                <tr class="tableHeaderRow">
                    <th>From</th>
                    <th>To</th>
                    <th>Distance to Pickup</th>
                    <th>Distance From Dropoff</th>
                    <th>Pickup Time</th>
                    <th>Capacity</th>
                    <th>Price</th>
                    <th>Chat</th>
                    <th>Join</th>
                </tr>
                <tr id="emptyState">
                    <td colspan="9">Search results will appear here!</td>
                </tr>
            </table>
        </div>

        <div id="pagination">
            <p id="paginationLabel">Page:&nbsp;</p>

            <button class="btnNormal paginationBtn" onclick="updatePagination(-1)">
                <span class="material-symbols-outlined">chevron_backward</span>
                <p>Previous&nbsp;&nbsp;</p>
            </button>

            <input id="paginationSelector" value="1" oninput="processPagination(this.value)">

            <p> &nbsp;/&nbsp;
            <p id="paginationTotal">1</p>
            </p>

            <button class="btnNormal paginationBtn" onclick="updatePagination(1)">
                <p>&nbsp;&nbsp;Next</p>
                <span class="material-symbols-outlined">chevron_forward</span>
            </button>
        </div>

    </div>

</body>

</html>