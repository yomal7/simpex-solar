<?php require APPROOT . '/views/client/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chat.css">

</head>

<body data-user-role="customer" data-user-id="<?php echo $_SESSION['user_id']; ?>" data-urlroot="<?php echo URLROOT; ?>">

    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- <a href="#" class="logo">
            <i class='bx bx-code-alt'></i>
            <div class="logo-name"><span>Asmr</span>Prog</div>
        </a> -->
        <ul class="side-menu">
            <li><a href="<?php echo URLROOT; ?>/client/dashboard"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li><a href="<?php echo URLROOT; ?>/client/operationDashboard"><i class='bx bx-analyse'></i>Quotations and Projects</a></li>
            <li><a href="<?php echo URLROOT; ?>/client/shop"><i class='bx bx-store-alt'></i>Shop</a></li>
            <li class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'chat') !== false) ? 'active' : ''; ?>">
                <a href="<?php echo URLROOT; ?>/client/chat">
                    <i class='bx bx-message-square-dots'></i>Chat
                    <span class="notification-dot" style="display: <?php echo (isset($_SESSION['total_unread_count']) && $_SESSION['total_unread_count'] > 0) ? 'block' : 'none'; ?>;"></span>
                </a>
            </li>
            <!-- <li><a href="#"><i class='bx bx-group'></i>Users</a></li> -->
            <li><a href="<?php echo URLROOT; ?>/client/settings"><i class='bx bx-cog'></i>Settings</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href=<?php echo URLROOT; ?>/users/logout class="logout">
                    <i class='bx bx-log-out-circle'></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
        </nav>

        <!-- End of Navbar -->

        <div class="chat-container">
            <div class="chat-sidebar">
                <h3>Contacts</h3>
                <div class="chat-contacts">
                    <div class="contact-item" data-role="operations" data-id="<?php echo $data['coordinators']['operations']->user_id; ?>">
                        <div class="contact-info">
                            <h4>Operations Coordinator</h4>
                            <p class="role-description">For project inquiries and installation updates</p>
                        </div>
                        <?php if (isset($data['unread_counts']['operations']) && $data['unread_counts']['operations'] > 0): ?>
                            <span class="unread-badge" data-coordinator="operations"><?php echo $data['unread_counts']['operations']; ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="contact-item" data-role="supplier" data-id="<?php echo $data['coordinators']['supplier']->user_id; ?>">
                        <div class="contact-info">
                            <h4>Supplier Coordinator</h4>
                            <p class="role-description">For orders and product inquiries</p>
                        </div>
                        <?php if (isset($data['unread_counts']['supplier']) && $data['unread_counts']['supplier'] > 0): ?>
                            <span class="unread-badge" data-coordinator="supplier"><?php echo $data['unread_counts']['supplier']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="chat-main">
                <div class="chat-header">
                    <h3 id="currentChat">Select a contact</h3>
                </div>
                <div class="chat-messages" id="chatMessages">
                    <div class="empty-chat-message">Select a coordinator to start chatting</div>
                </div>
                <div class="chat-input">
                    <input type="text" id="messageInput" placeholder="Type a message..." disabled>
                    <button id="sendButton" disabled>Send</button>
                </div>
            </div>
        </div>

        <script>
            // Global variables for chat
            const USER_ID = '<?php echo $_SESSION['user_id']; ?>';
            const USER_ROLE = 'customer'; // Using 'customer' as role
            const URLROOT = '<?php echo URLROOT; ?>';

            // Define variables to track current chat
            let currentRecipientId = null;
            let currentRecipientType = null;

            // Track unread counts
            let unreadCounts = {
                operations: <?php echo $data['unread_counts']['operations'] ?? 0; ?>,
                supplier: <?php echo $data['unread_counts']['supplier'] ?? 0; ?>,
                hr: <?php echo $data['unread_counts']['hr'] ?? 0; ?>
            };

            function formatMessageTime(timestamp) {
                const date = new Date(timestamp);

                // Add 5 hours and 30 minutes
                date.setHours(date.getHours() + 5);
                date.setMinutes(date.getMinutes() + 30);

                // Format as HH:MM
                return date.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            // Add formatMessageDate function after formatMessageTime
            function formatMessageDate(timestamp) {
                const date = new Date(timestamp);

                // Add the same time zone adjustment as formatMessageTime
                date.setHours(date.getHours() + 5);
                date.setMinutes(date.getMinutes() + 30);

                // Get today and yesterday with consistent time parts
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                const yesterday = new Date(today);
                yesterday.setDate(yesterday.getDate() - 1);

                // Reset time parts of the message date for comparison
                const compareDate = new Date(date);
                compareDate.setHours(0, 0, 0, 0);

                // Format date based on when it was sent
                if (compareDate.getTime() === today.getTime()) {
                    return "Today";
                } else if (compareDate.getTime() === yesterday.getTime()) {
                    return "Yesterday";
                } else {
                    // Format as full date: January 15, 2025
                    return date.toLocaleDateString("en-US", {
                        month: "long",
                        day: "numeric",
                        year: "numeric",
                    });
                }
            }

            // New function to update sidebar notification dot
            function updateSidebarNotificationDot(show) {
                const notificationDot = document.querySelector('.sidebar a[href*="/chat"] .notification-dot');
                if (notificationDot) {
                    notificationDot.style.display = show ? 'block' : 'none';
                }
            }

            // Function to check total unread messages and update notification dot
            function updateNotificationStatus() {
                let totalUnread = 0;
                Object.values(unreadCounts).forEach(count => {
                    totalUnread += count;
                });

                console.log("Total unread messages:", totalUnread);
                updateSidebarNotificationDot(totalUnread > 0);
                return totalUnread;
            }

            // Add click event listeners to contact items
            document.addEventListener('DOMContentLoaded', function() {

                const contactItems = document.querySelectorAll('.contact-item');

                contactItems.forEach(item => {
                    item.addEventListener('click', function() {
                        // Get coordinator information
                        const coordinatorId = this.getAttribute('data-id');
                        const coordinatorRole = this.getAttribute('data-role');
                        const coordinatorTitle = this.querySelector('h4').textContent;

                        // Update UI
                        contactItems.forEach(c => c.classList.remove('active'));
                        this.classList.add('active');

                        document.getElementById('currentChat').textContent = coordinatorTitle;
                        document.getElementById('messageInput').disabled = false;
                        document.getElementById('sendButton').disabled = false;

                        // Set current recipient for sending messages
                        currentRecipientId = coordinatorId;
                        currentRecipientType = coordinatorRole;

                        // Load chat history
                        loadChatHistory(coordinatorRole);

                        // Mark messages as read
                        markMessagesAsRead(coordinatorRole);
                    });
                });

                // Set up message sending
                const messageInput = document.getElementById('messageInput');
                const sendButton = document.getElementById('sendButton');

                // Send message when button is clicked
                sendButton.addEventListener('click', sendMessage);

                // Send message when Enter key is pressed
                messageInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        sendMessage();
                    }
                });

                // Toggle sidebar on mobile
                const toggleSidebar = document.querySelector('.bx-menu');
                const sidebar = document.querySelector('.sidebar');
                const content = document.querySelector('.content');

                if (toggleSidebar) {
                    toggleSidebar.addEventListener('click', function() {
                        sidebar.classList.toggle('hide');
                        content.classList.toggle('expand');
                    });
                }
            });

            function loadChatHistory(coordinatorType) {
                const chatMessages = document.getElementById('chatMessages');
                chatMessages.innerHTML = '<div class="loading-message">Loading messages...</div>';

                fetch(`${URLROOT}/client/getChatHistory/${coordinatorType}`)
                    .then(response => response.json())
                    .then(data => {
                        chatMessages.innerHTML = '';

                        if (data && data.length > 0) {
                            // Sort messages by timestamp to ensure chronological order
                            data.sort((a, b) => new Date(a.timestamp) - new Date(b.timestamp));

                            let currentDateStr = null;

                            data.forEach(message => {
                                // Parse the timestamp and apply timezone adjustment
                                const messageDate = new Date(message.timestamp);
                                messageDate.setHours(messageDate.getHours() + 5);
                                messageDate.setMinutes(messageDate.getMinutes() + 30);

                                // Format date for comparison (YYYY-MM-DD)
                                const year = messageDate.getFullYear();
                                const month = String(messageDate.getMonth() + 1).padStart(2, '0');
                                const day = String(messageDate.getDate()).padStart(2, '0');
                                const dateStr = `${year}-${month}-${day}`;

                                // Add date separator if this is a new date
                                if (currentDateStr !== dateStr) {
                                    currentDateStr = dateStr;
                                    const dateDiv = document.createElement('div');
                                    dateDiv.className = 'date-separator';
                                    dateDiv.innerHTML = `<span>${formatMessageDate(message.timestamp)}</span>`;
                                    chatMessages.appendChild(dateDiv);
                                }

                                // Create message element
                                const isFromMe = message.sender_id == USER_ID;
                                const messageDiv = document.createElement('div');
                                messageDiv.className = isFromMe ? 'message sent' : 'message received';

                                const formattedTime = formatMessageTime(message.timestamp);

                                messageDiv.innerHTML = `
                            <div class="message-content">${message.message}</div>
                            <div class="message-time">${formattedTime}</div>
                        `;

                                chatMessages.appendChild(messageDiv);
                            });
                        } else {
                            chatMessages.innerHTML = '<div class="empty-chat-message">No messages yet. Start a conversation!</div>';
                        }

                        // Scroll to the bottom
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    })
                    .catch(error => {
                        console.error('Error loading messages:', error);
                        chatMessages.innerHTML = '<div class="error-message">Failed to load messages. Please try again.</div>';
                    });
            }

            // Function to mark messages as read
            function markMessagesAsRead(coordinatorType) {
                // Remove unread badge visually
                const badge = document.querySelector(`.unread-badge[data-coordinator="${coordinatorType}"]`);
                if (badge) {
                    // Instead of just hiding, remove it completely from the DOM
                    badge.parentNode.removeChild(badge);

                    // Update our tracking of unread counts
                    unreadCounts[coordinatorType] = 0;

                    // Check if all messages are read now and update dot
                    updateNotificationStatus();
                }

                    // Send request to mark messages as read in database
                    fetch(`${URLROOT}/client/markMessagesAsRead`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            coordinator_type: coordinatorType
                        })
                    });
                }

                // Function to send a message
                function sendMessage() {
                    const messageInput = document.getElementById('messageInput');
                    const message = messageInput.value.trim();

                    if (!message || !currentRecipientId) {
                        return;
                    }

                    // Clear input field
                    messageInput.value = '';

                    // Add message to chat immediately (optimistic UI)
                    const chatMessages = document.getElementById('chatMessages');

                    // Check if we need to add a date separator first
                    const lastDateSeparator = chatMessages.querySelector('.date-separator:last-child span');
                    const needsDateSeparator = !lastDateSeparator || lastDateSeparator.textContent !== 'Today';

                    if (needsDateSeparator) {
                        const dateDiv = document.createElement('div');
                        dateDiv.className = 'date-separator';
                        dateDiv.innerHTML = '<span>Today</span>';
                        chatMessages.appendChild(dateDiv);
                    }

                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'message sent';

                    const formattedTime = new Date().toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    messageDiv.innerHTML = `
            <div class="message-content">${message}</div>
            <div class="message-time">${formattedTime}</div>
        `;

                    chatMessages.appendChild(messageDiv);

                    // Scroll to the bottom
                    chatMessages.scrollTop = chatMessages.scrollHeight;



                    // Send message to server
                    fetch(`${URLROOT}/client/saveMessage`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                to_user_id: currentRecipientId,
                                message: message,
                                recipient_type: currentRecipientType
                            })
                        })
                        .then(response => response.json())
                        .catch(error => {
                            console.error('Error sending message:', error);
                        });

                    // Also send through WebSocket for real-time updates
                    if (socket && socket.readyState === WebSocket.OPEN) {
                        socket.send(JSON.stringify({
                            type: 'message',
                            from_user_id: USER_ID,
                            from_role: USER_ROLE,
                            to_user_id: currentRecipientId,
                            to_role: currentRecipientType + 'Coordinator',
                            message: message
                        }));
                    }
                }

                // WebSocket connection
                let socket = null;

                function connectWebSocket() {
                    // Create WebSocket connection
                    socket = new WebSocket('ws://localhost:8080');

                    socket.onopen = function() {
                        console.log('WebSocket connection established');

                        // Register with the WebSocket server
                        socket.send(JSON.stringify({
                            type: 'register',
                            user_id: USER_ID,
                            user_role: USER_ROLE
                        }));
                    };

                    socket.onmessage = function(event) {
                        const data = JSON.parse(event.data);
                        console.log('WebSocket message received:', data);

                        if (data.type === 'message') {
                            // Figure out which coordinator this is from
                            let coordinatorType = null;
                            const contactItems = document.querySelectorAll('.contact-item');

                            contactItems.forEach(item => {
                                if (item.getAttribute('data-id') === data.from_user_id) {
                                    coordinatorType = item.getAttribute('data-role');
                                }
                            });

                            // Only display the message if it's from the current chat
                            if (currentRecipientId && data.from_user_id == currentRecipientId) {
                                // Add message to chat
                                const chatMessages = document.getElementById('chatMessages');

                                // Check if we need to add a date separator
                                const lastDateSeparator = chatMessages.querySelector('.date-separator:last-child span');
                                const needsDateSeparator = !lastDateSeparator || lastDateSeparator.textContent !== 'Today';

                                if (needsDateSeparator) {
                                    const dateDiv = document.createElement('div');
                                    dateDiv.className = 'date-separator';
                                    dateDiv.innerHTML = '<span>Today</span>';
                                    chatMessages.appendChild(dateDiv);
                                }

                                const messageDiv = document.createElement('div');
                                messageDiv.className = 'message received';

                                const formattedTime = new Date().toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });

                                messageDiv.innerHTML = `
                        <div class="message-content">${data.message}</div>
                        <div class="message-time">${formattedTime}</div>
                    `;

                                chatMessages.appendChild(messageDiv);

                                // Scroll to the bottom
                                chatMessages.scrollTop = chatMessages.scrollHeight;

                                // Mark this message as read since we're viewing it
                                markMessagesAsRead(currentRecipientType);
                            } else if (coordinatorType) {
                                // Show notification for message from someone else
                                updateUnreadCount(coordinatorType, data.from_user_id);
                                updateSidebarNotificationDot(true); // Show notification dot in sidebar
                            }
                        }
                    };

                    socket.onclose = function() {
                        console.log('WebSocket connection closed');
                        // Try to reconnect after a delay
                        setTimeout(connectWebSocket, 5000);
                    };

                    socket.onerror = function(error) {
                        console.error('WebSocket error:', error);
                    };
                }

                // Update unread count badge
                function updateUnreadCount(coordinatorType) {
                    // Find the contact that matches this coordinator type
                    const contactItem = document.querySelector(`.contact-item[data-role="${coordinatorType}"]`);

                    if (contactItem) {
                        let badge = contactItem.querySelector(`.unread-badge[data-coordinator="${coordinatorType}"]`);

                        if (badge) {
                            // Update existing badge
                            const count = parseInt(badge.textContent || '0');
                            badge.textContent = count + 1;
                            unreadCounts[coordinatorType] = count + 1;

                        } else {
                            // Create new badge
                            badge = document.createElement('span');
                            badge.className = 'unread-badge';
                            badge.setAttribute('data-coordinator', coordinatorType);
                            badge.textContent = '1';
                            contactItem.appendChild(badge);
                            unreadCounts[coordinatorType] = 1;
                        }

                        // Update notification dot visiblr
                        updateSidebarNotificationDot(true); // Show notification dot in sidebar
                    }
                }

                // Connect to WebSocket when page loads
                document.addEventListener('DOMContentLoaded', function() {
                    if ('WebSocket' in window) {
                        connectWebSocket();
                    } else {
                        console.log('WebSockets are not supported in this browser.');
                        // Fall back to polling for messages every few seconds
                        setInterval(function() {
                            if (currentRecipientType) {
                                loadChatHistory(currentRecipientType);
                            }
                        }, 5000); // Poll every 5 seconds
                    }
                
                    updateSidebarNotificationDot(totalUnread > 0);
                });
        </script>

    </div> <!-- End of content div -->

    <?php require APPROOT . '/views/client/footer.php'; ?>