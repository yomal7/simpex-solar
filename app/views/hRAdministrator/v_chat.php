<?php require APPROOT . '/views/hRAdministrator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chat.css">
</head>

<body data-user-role="hRAdministrator" data-user-id="<?php echo $_SESSION['user_id']; ?>" data-urlroot="<?php echo URLROOT; ?>">

    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- ************ -->
        <!-- Sidebar -->
        <!-- ************ -->

        <div class="sidebar" id="sidebar">
            <img

                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture" />
            <a href="<?php echo URLROOT ?>/hRAdministrator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/employees">
                <span class="material-icons-sharp">group</span>
                <h3>Employees</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/attendance">
                <span class="material-icons-sharp">checklist_rtl</span>
                <h3>Attendance</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/holiday">
                <span class="material-icons-sharp">date_range</span>
                <h3>Holiday</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/chat" class="active">
                <span class="material-icons-sharp">chat</span>
                <h3>Chat</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">
            <div class="chat-container">
                <div class="chat-sidebar">
                    <div class="chat-search">
                        <input type="text" id="searchClients" placeholder="Search clients...">
                    </div>
                    <div class="chat-list" id="clientList">
                        <?php if (!empty($data['clients'])): ?>
                            <?php foreach ($data['clients'] as $client): ?>
                                <div class="contact-item"
                                    data-id="<?php echo $client->user_id; ?>"
                                    data-name="<?php echo $client->name; ?>">
                                    <img src="<?php echo URLROOT; ?>/public/assets/profile.png" alt="Client">
                                    <div class="contact-info">
                                        <h4><?php echo $client->name; ?></h4>
                                        <p class="last-message"><?php echo !empty($client->last_message) ?
                                                                    (strlen($client->last_message) > 30 ?
                                                                        substr($client->last_message, 0, 30) . '...' :
                                                                        $client->last_message) : 'No messages'; ?></p>
                                    </div>
                                    <?php if (!empty($client->unread_count) && $client->unread_count > 0): ?>
                                        <span class="unread-count"><?php echo $client->unread_count; ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="no-chats">No chat history found</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="chat-main">
                    <div class="chat-header">
                        <h3 id="currentClient">Select a client to start chatting</h3>
                    </div>
                    <div class="chat-messages" id="chatMessages">
                        <div class="empty-chat-message">Select a client to start chatting</div>
                    </div>
                    <div class="chat-input">
                        <input type="text" id="messageInput" placeholder="Type a message..." disabled>
                        <button id="sendButton" disabled>Send</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="<?php echo URLROOT; ?>/js/chat.js"></script>

        <?php require APPROOT . '/views/hRAdministrator/footer.php'; ?>