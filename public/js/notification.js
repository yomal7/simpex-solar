/**
 * Global notification handler
 * Handles WebSocket connection for real-time notification updates across all pages
 */
(function () {
  // Get user data from the body
  const USER_ID = document.body.getAttribute("data-user-id");
  const USER_ROLE = document.body.getAttribute("data-user-role");
  const URLROOT = document.body.getAttribute("data-urlroot");

  // Skip initialization if we're on the chat page (let chat.js handle it)
  const isChatPage = window.location.href.includes("/chat");

  // WebSocket connection
  let socket = null;

  /**
   * Connect to WebSocket server
   */
  function connectWebSocket() {
    // Create WebSocket connection
    socket = new WebSocket("ws://localhost:8080");

    socket.onopen = function () {
      console.log("Notification WebSocket connected");

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

      // When receiving a message, update the notification dot
      if (data.type === "message") {
        // Only handle if it's not on the chat page
        if (!isChatPage) {
          updateNotificationDot(true);
        }
      }
    };

    socket.onclose = function () {
      console.log("Notification WebSocket connection closed");
      // Try to reconnect after a delay
      setTimeout(connectWebSocket, 5000);
    };

    socket.onerror = function (error) {
      console.error("WebSocket error:", error);
    };
  }

  function updateNotificationDot(show) {
    const notificationDot = document.querySelector(
      '.sidebar a[href*="/chat"] .notification-dot'
    );
    if (notificationDot) {
      notificationDot.style.display = show ? "block" : "none";
    }
  }

  /**
   * Check if there are any unread messages on page load
   */
  function checkInitialUnreadStatus() {
    // Special case for customer role - use client URL
    const endpointRole = USER_ROLE === "customer" ? "client" : USER_ROLE;

    // Otherwise, fetch unread status from server
    fetch(`${URLROOT}/${endpointRole}/getUnreadStatus`)
      .then((response) => response.json())
      .then((data) => {
        updateNotificationDot(data.hasUnread);
      })
      .catch((error) => {
        console.error("Error checking unread status:", error);
      });
  }

  // Initialize connection when DOM is loaded
  document.addEventListener("DOMContentLoaded", function () {
    // Skip if we're on the chat page (chat.js will handle it)
    if (isChatPage) {
      return;
    }

    if ("WebSocket" in window && USER_ID) {
      connectWebSocket();
      checkInitialUnreadStatus();
    }
  });
})();
