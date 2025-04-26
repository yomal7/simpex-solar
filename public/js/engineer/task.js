const URLROOT = "http://localhost/simpex-solar";

document.addEventListener("click", function (event) {
  const sidebar = document.getElementById("sidebar");
  const menuToggle = document.querySelector(".menu-toggle");
  if (
    window.innerWidth <= 768 &&
    sidebar.classList.contains("active") &&
    !sidebar.contains(event.target) &&
    event.target !== menuToggle
  ) {
    sidebar.classList.remove("active");
  }
});

function autoResize(textarea) {
  textarea.style.height = "auto";
  textarea.style.height = "${textarea.scrollHeight}px";
  // textarea.style.height = `${textarea.scrollHeight}px`;
}

function toggleSidebar() {
  document.getElementById("sidebar").classList.toggle("active");
}

// Updated updateStatus function to change the color immediately
function updateStatus(taskId, status) {
  // Update the dropdown's class immediately for visual feedback
  const dropdown = document.querySelector('.status-dropdown');
  if (dropdown) {
      // Remove all status classes
      dropdown.classList.remove('incomplete', 'in_progress', 'completed');
      // Add the new status class
      dropdown.classList.add(status);
  }
  
  // Then call the existing function to update the server
  updateTaskStatus(taskId, status);
}

function updateTaskStatus(taskId, status) {
  fetch(`${URLROOT}/engineer/tasks`, {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `id=${taskId}&status=${status}`,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const statusButton = document.querySelector(
          `button.status-button[onclick*="${taskId}"]`
        );
        if (statusButton) {
          const formattedStatus = status
            .split("_")
            .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
            .join(" ");
          statusButton.textContent = formattedStatus;

          statusButton.classList.remove(
            "incomplete",
            "in_progress",
            "completed"
          );

          statusButton.classList.add(status.toLowerCase());

          statusButton.setAttribute(
            "onclick",
            `openStatusPopup(${taskId}, '${status}')`
          );
        }
      } else {
        alert("Failed to update status");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("An error occurred while updating status");
    });
}

function addComment(taskId) {
  const comment = document.getElementById("newComment").value.trim();
  if (!comment) return;

  // Send the request as x-www-form-urlencoded with taskId and comment
  fetch(`${URLROOT}/engineer/details/${taskId}`, {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `id=${taskId}&comment=${encodeURIComponent(comment)}`, // Correct body format
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        location.reload(); // Reload to reflect the added comment
      } else {
        alert(data.message || "Failed to add comment");
      }
    })
    .catch((error) => console.error("Error:", error));
}

function deleteComment(taskId) {
  if (!confirm("Are you sure you want to delete this comment?")) return;

  // Send the request as x-www-form-urlencoded for deleting the comment
  fetch(`${URLROOT}/engineer/details/${taskId}`, {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `action=delete_comment`, // Pass only the action for deletion
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        location.reload(); // Reload to reflect the deletion
      } else {
        alert("Failed to delete comment");
      }
    })
    .catch((error) => console.error("Error:", error));
}

