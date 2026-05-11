<!doctype html>
<html>
  <head>
    <!--Imports-->
    <link rel="stylesheet" href="../shadCNTemplate.php" />
    <link rel="stylesheet" href="navbar.php" />
    <link rel="stylesheet" href="desktop.php" />
    <link rel="stylesheet" href="mobile.php" />
    <script src="script.php" defer></script>
    <script src="../../Database/DBfunctions.php" defer></script>
    <script src="../cookieJSInterface.php" defer></script>
    <title>My Rides - Chatroom</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!--Google SVG Imports-->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    />
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
        <span
          class="material-symbols-outlined"
          id="hamburgerMenuNavbarIcon"
          onclick="toggleNavbar()"
        >
          menu
        </span>
        <!-- "Onclick" triggers menu expansion / shrinking-->
        <a href="">
          <h3>Hailshare</h3>
        </a>
      </div>

      <!--Ride List Icon-->
      <a href="../../Customer/rideList/index.php">
        <div class="navbarItem" href="">
          <span class="material-symbols-outlined">list_alt</span>
          <p>Ride List</p>
        </div>
      </a>

      <!--Create Ride Icon-->
      <a href="../../Customer/createRide/index.php">
        <div class="navbarItem">
          <span class="material-symbols-outlined"> add_circle</span>
          <p>Create Ride</p>
        </div>
      </a>

      <!--My Rides Icon-->
      <a href="../../Customer/myRides/index.php">
        <div class="navbarItem">
          <span class="material-symbols-outlined"> event_available</span>
          <p>My Rides</p>
        </div>
      </a>

      <!--Customer Support Icon-->
      <a href="../../Customer/customerSupport/index.php">
        <div class="navbarItem">
          <span class="material-symbols-outlined">support_agent</span>
          <p>Customer Support</p>
        </div>
      </a>

      <!--My Profile Icon-->
      <a href="../../Customer/myProfile/index.php">
        <div class="navbarItem">
          <span class="material-symbols-outlined"> account_circle</span>
          <p>My Profile</p>
        </div>
      </a>
    </div>

    <div id="content">
      <h1>Chatroom ID: #<span id="chatroomId"></span></h1>
      <div id="actionBtns">
        <a >
          <button class="btnNormal" id="goBackBtn" onclick="goBack()">
            <span class="material-symbols-outlined"> chevron_left </span>Go Back
          </button>
        </a>
        <a>
          <button class="btnNormal staffChat" id="endConversationBtn" onclick="closeRequest()">
            <span class="material-symbols-outlined"> close </span>End
            Conversation
          </button>
        </a>
      </div>

      <div id="messageBox">
        <div id="staffAssigned" class="staffChat">
          <p>Current Staff Assigned: <span id="staffAssignedName"></span></p>
        </div>
        <div id="scrollableContent">
          <div id="messageContent">
            
          </div>
        </div>
        <div id="sendMessageContainer">
          <div id="textarea-wrapper">
            <textarea
              placeholder="Type a message..."
              onInput="this.style.height = 'auto'; this.style.height = this.scrollHeight + 'px'"
              id="textBox"
            ></textarea>
          </div>
          <button class="btnNormal" id="sendBtn" onclick="sendMessage()">
            <span class="material-symbols-outlined"> send </span>
          </button>
        </div>
      </div>
    </div>
  </body>
</html>
