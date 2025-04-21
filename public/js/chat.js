// These constants will be used by the script
const USER_ROLE = document.body.getAttribute("data-user-role");
const USER_ID = document.body.getAttribute("data-user-id");
const URLROOT = document.body.getAttribute("data-urlroot");

// Initialize current recipient variables
let currentRecipientId = null;

function formatMessageTime(timestamp) {
  const date = new Date(timestamp);

  // Add 5 hours and 30 minutes
  date.setHours(date.getHours() + 5);
  date.setMinutes(date.getMinutes() + 30);

  // Format as HH:MM
  return date.toLocaleTimeString([], {
    hour: "2-digit",
    minute: "2-digit",
  });
}

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

// Document ready function
document.addEventListener("DOMContentLoaded", function () {
  // Set up client search functionality
  const searchInput = document.getElementById("searchClients");
  if (searchInput) {
    searchInput.addEventListener("input", function () {
      const searchTerm = this.value.toLowerCase();
      const clientItems = document.querySelectorAll(".contact-item");

      clientItems.forEach((item) => {
        const clientName = item.querySelector("h4").textContent.toLowerCase();

        if (clientName.includes(searchTerm)) {
          item.style.display = "flex";
        } else {
          item.style.display = "none";
        }
      });
    });
  }

  // Set up click event for client items
  const clientItems = document.querySelectorAll(".contact-item");
  clientItems.forEach((item) => {
    item.addEventListener("click", function () {
      // Update UI
      clientItems.forEach((c) => c.classList.remove("active"));
      this.classList.add("active");

      const clientId = this.getAttribute("data-id");
      const clientName = this.getAttribute("data-name");

      document.getElementById("currentClient").textContent = clientName;
      document.getElementById("messageInput").disabled = false;
      document.getElementById("sendButton").disabled = false;

      // Set current recipient
      currentRecipientId = clientId;

      // Load chat history
      loadChatHistory(clientId);

      // Remove unread count badge
      const unreadBadge = this.querySelector(".unread-count");
      if (unreadBadge) {
        unreadBadge.remove();
      }
    });
  });

  // Set up message sending
  const messageInput = document.getElementById("messageInput");
  const sendButton = document.getElementById("sendButton");

  // Send message when button is clicked
  sendButton.addEventListener("click", sendMessage);

  // Send message when Enter key is pressed
  messageInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      sendMessage();
    }
  });
});

function loadChatHistory(clientId) {
    const chatMessages = document.getElementById("chatMessages");
    chatMessages.innerHTML = '<div class="loading-message">Loading messages...</div>';
  
    fetch(`${URLROOT}/${USER_ROLE}/getClientChats?client_id=${clientId}`)
      .then((response) => response.json())
      .then((data) => {
        chatMessages.innerHTML = "";
  
        if (data && data.length > 0) {
          // Sort messages by timestamp to ensure chronological order
          data.sort((a, b) => new Date(a.timestamp) - new Date(b.timestamp));
  
          let currentDateStr = null;
  
          data.forEach((message) => {
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
              const dateDiv = document.createElement("div");
              dateDiv.className = "date-separator";
              dateDiv.innerHTML = `<span>${formatMessageDate(message.timestamp)}</span>`;
              chatMessages.appendChild(dateDiv);
            }
  
            // Create message element
            const isFromMe = message.sender_id == USER_ID;
            const messageDiv = document.createElement("div");
            messageDiv.className = isFromMe ? "message sent" : "message received";
            
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
      .catch((error) => {
        console.error("Error loading messages:", error);
        chatMessages.innerHTML = '<div class="error-message">Failed to load messages. Please try again.</div>';
      });
  
    // Mark messages as read
    markMessagesAsRead(clientId);
  }

// Function to mark messages as read
function markMessagesAsRead(clientId) {
  fetch(`${URLROOT}/${USER_ROLE}/markMessagesAsRead`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      client_id: clientId,
    }),
  });
}

// Function to send a message
function sendMessage() {
  const messageInput = document.getElementById("messageInput");
  const message = messageInput.value.trim();

  if (!message || !currentRecipientId) {
    return;
  }

  // Clear input field
  messageInput.value = "";

  // Add message to chat immediately (optimistic UI)
  const chatMessages = document.getElementById("chatMessages");

  // Check if we need to add a date separator first
  const today = new Date().toISOString().split("T")[0];
  const lastDateSeparator = chatMessages.querySelector(".date-separator:last-child span");
  const needsDateSeparator = !lastDateSeparator || lastDateSeparator.textContent !== "Today";
  
  if (needsDateSeparator) {
    const dateDiv = document.createElement("div");
    dateDiv.className = "date-separator";
    dateDiv.innerHTML = "<span>Today</span>";
    chatMessages.appendChild(dateDiv);
  }

  const messageDiv = document.createElement("div");
  messageDiv.className = "message sent";

  const formattedTime = new Date().toLocaleTimeString([], {
    hour: "2-digit",
    minute: "2-digit",
  });

  messageDiv.innerHTML = `
<div class="message-content">${message}</div>
<div class="message-time">${formattedTime}</div>
`;

  chatMessages.appendChild(messageDiv);

  // Scroll to the bottom
  chatMessages.scrollTop = chatMessages.scrollHeight;

  // Send message to server
  fetch(`${URLROOT}/${USER_ROLE}/saveMessage`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      to_user_id: currentRecipientId,
      message: message,
    }),
  })
    .then((response) => response.json())
    .catch((error) => {
      console.error("Error sending message:", error);
    });

  // Also send through WebSocket for real-time updates
  if (socket && socket.readyState === WebSocket.OPEN) {
    socket.send(
      JSON.stringify({
        type: "message",
        from_user_id: USER_ID,
        from_role: USER_ROLE,
        to_user_id: currentRecipientId,
        to_role: "customer",
        message: message,
      })
    );
  }
}

// WebSocket connection
let socket = null;

function connectWebSocket() {
  // Create WebSocket connection
  socket = new WebSocket("ws://localhost:8080");

  socket.onopen = function () {
    console.log("WebSocket connection established");

    // Register with the WebSocket server
    socket.send(
      JSON.stringify({
        type: "register",
        user_id: USER_ID,
        user_role: USER_ROLE,
      })
    );
  };

  socket.onmessage = function (event) {
    const data = JSON.parse(event.data);
    console.log("WebSocket message received:", data);

    if (data.type === "message") {
      // Check if this message is part of the current chat
      if (currentRecipientId && data.from_user_id == currentRecipientId) {
        // Add message to chat
        const chatMessages = document.getElementById("chatMessages");

        const lastDateSeparator = chatMessages.querySelector(
          ".date-separator:last-child span"
        );
        const needsDateSeparator =
          !lastDateSeparator || lastDateSeparator.textContent !== "Today";

        if (needsDateSeparator) {
          const dateDiv = document.createElement("div");
          dateDiv.className = "date-separator";
          dateDiv.innerHTML = "<span>Today</span>";
          chatMessages.appendChild(dateDiv);
        }

        const messageDiv = document.createElement("div");
        messageDiv.className = "message received";

        const formattedTime = new Date().toLocaleTimeString([], {
          hour: "2-digit",
          minute: "2-digit",
        });

        messageDiv.innerHTML = `
            <div class="message-content">${data.message}</div>
            <div class="message-time">${formattedTime}</div>
        `;

        chatMessages.appendChild(messageDiv);

        // Scroll to the bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // Mark message as read
        markMessagesAsRead(data.from_user_id);
      } else {
        // Update unread count for this client
        updateUnreadCount(data.from_user_id);
      }
    }
  };

  socket.onclose = function () {
    console.log("WebSocket connection closed");
    // Try to reconnect after a delay
    setTimeout(connectWebSocket, 5000);
  };

  socket.onerror = function (error) {
    console.error("WebSocket error:", error);
  };
}

// Update unread count badge
function updateUnreadCount(userId) {
  const contactItem = document.querySelector(
    `.contact-item[data-id="${userId}"]`
  );

  if (contactItem) {
    let unreadBadge = contactItem.querySelector(".unread-count");

    if (unreadBadge) {
      // Update existing badge
      const count = parseInt(unreadBadge.textContent || "0");
      unreadBadge.textContent = count + 1;
    } else {
      // Create new badge
      unreadBadge = document.createElement("span");
      unreadBadge.className = "unread-count";
      unreadBadge.textContent = "1";
      contactItem.appendChild(unreadBadge);
    }

    // If this client is not already in the list, reload the client list
    if (!contactItem.classList.contains("active")) {
      // Highlight the contact item
      contactItem.style.backgroundColor = "#f0f8ff";
      // Move the client to the top of the list
      const clientList = document.getElementById("clientList");
      clientList.insertBefore(contactItem, clientList.firstChild);
    }
  } else {
    // This is a new client, reload the entire client list
    refreshClientList();
  }
}

// Function to refresh the client list
function refreshClientList() {
  fetch(`${URLROOT}/${USER_ROLE}/getAllClientChats`)
    .then((response) => response.json())
    .then((clients) => {
      const clientList = document.getElementById("clientList");
      clientList.innerHTML = "";

      if (clients && clients.length > 0) {
        clients.forEach((client) => {
          const item = document.createElement("div");
          item.className = "contact-item";
          item.setAttribute("data-id", client.user_id);
          item.setAttribute("data-name", client.name);

          // If this is the current selected client, add active class
          if (currentRecipientId && client.user_id == currentRecipientId) {
            item.classList.add("active");
          }

          item.innerHTML = `
                <img src="${URLROOT}/public/assets/profile.png" alt="Client">
                <div class="contact-info">
                    <h4>${client.name}</h4>
                </div>
                ${
                  client.unread_count > 0
                    ? `<span class="unread-count">${client.unread_count}</span>`
                    : ""
                }
            `;

          // Add click event listener
          item.addEventListener("click", function () {
            // Update UI
            document
              .querySelectorAll(".contact-item")
              .forEach((c) => c.classList.remove("active"));
            this.classList.add("active");

            const clientId = this.getAttribute("data-id");
            const clientName = this.getAttribute("data-name");

            document.getElementById("currentClient").textContent = clientName;
            document.getElementById("messageInput").disabled = false;
            document.getElementById("sendButton").disabled = false;

            // Set current recipient
            currentRecipientId = clientId;

            // Load chat history
            loadChatHistory(clientId);

            // Remove unread count badge
            const unreadBadge = this.querySelector(".unread-count");
            if (unreadBadge) {
              unreadBadge.remove();
            }
          });

          clientList.appendChild(item);
        });
      } else {
        clientList.innerHTML = '<p class="no-chats">No chat history found</p>';
      }
    })
    .catch((error) => {
      console.error("Error refreshing client list:", error);
    });
}

// Connect to WebSocket when page loads
document.addEventListener("DOMContentLoaded", function () {
  if ("WebSocket" in window) {
    connectWebSocket();
  } else {
    console.log("WebSockets are not supported in this browser.");
    // Fall back to polling for updates every few seconds
    setInterval(function () {
      if (currentRecipientId) {
        loadChatHistory(currentRecipientId);
      }
      refreshClientList();
    }, 5000); // Poll every 5 seconds
  }
});
