<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Live Chat – HailShare Staff</title>
    <link rel="stylesheet" href="../live-chat-staff/shadCNTemplate.php" />
    <link rel="stylesheet" href="../live-chat-staff/mobile.php" />
    <link rel="stylesheet" href="../live-chat-staff/style.php" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="../../Database/DBfunctions.php" defer></script>
    <script src="../cookieJSInterface.php" defer></script>
    <script src="../live-chat-staff/script.php" defer></script>
</head>

<body>
    <div id="navbar">
        <div class="navbarItem">
            <span class="material-symbols-outlined" id="hamburgerMenuNavbarIcon" onclick="toggleNavbar()">menu</span>
            <a href="">
                <h3>HailShare</h3>
            </a>
        </div>
        <a href="../ride-list-staff/index.php">
            <div class="navbarItem"><span class="material-symbols-outlined">list_alt</span>
                <p>Ride List</p>
            </div>
        </a>
        <a href="../business-analytics-staff/index.php">
            <div class="navbarItem"><span class="material-symbols-outlined">bar_chart</span>
                <p>Analytics</p>
            </div>
        </a>
        <a href="../live-chat-staff/index.php">
            <div class="navbarItem"><span class="material-symbols-outlined">support_agent</span>
                <p>Live Chat</p>
            </div>
        </a>
        <a href="../profile-staff/index.php">
            <div class="navbarItem"><span class="material-symbols-outlined">account_circle</span>
                <p>Profile</p>
            </div>
        </a>
    </div>
    <div id="content">
        <div class="content-wrapper">
            <!-- INBOX -->
            <div id="inboxView">
                <h1 class="page-title">Live Chat Inbox</h1>
                <div class="controls-bar">
                    <div class="control-group">
                        <label for="sortChat">Sort By:</label>
                        <select id="sortChat">
                            <option value="">— select —</option>
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="status">Status</option>
                        </select>
                    </div>
                    <div class="control-group">
                        <label for="filterChat">Filter By:</label>
                        <select id="filterChat">
                            <option value="">All</option>
                            <option value="active">Active</option>
                            <option value="waiting">Waiting</option>
                            <option value="closed">Closed</option>
                            <option value="timeout">Timeout</option>
                        </select>
                    </div>
                </div>
                <div id="chatList">
                    <p style="text-align:center;">Loading…</p>
                </div>
            </div>
            <!-- CHATROOM -->
            <div id="chatroomView" class="hidden">
                <div class="chatroom-header">
                    <button class="btnNormal btn-back" id="backToInbox">&#8249; Back to Inbox</button>
                    <h1 class="page-title" id="chatroomChatId">Chat Room</h1>
                    <div class="chatroom-actions">
                        <button class="btnNormal" id="takeOverBtn">Take Over Chat</button>
                        <button class="btnStrong btn-end" id="endConvoBtn">End Conversation</button>
                    </div>
                </div>
                <div class="assigned-bar">
                    Current Staff Assigned: <strong id="assignedStaff">—</strong>
                </div>
                <div id="messagesArea" class="messages-area"></div>
                <div class="message-input-row">
                    <input type="text" id="msgInput" placeholder="Type a message…" autocomplete="off"
                        aria-label="Type a message" />
                    <button class="btnStrong btn-send" id="sendBtn" title="Send" aria-label="Send message">
                        <span class="material-symbols-outlined">send</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="toast" id="toast" role="status" aria-live="polite"></div>
</body>

</html>