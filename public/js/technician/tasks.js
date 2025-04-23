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

document.addEventListener("DOMContentLoaded", function () {
  const overlay = document.getElementById("overlay");

  overlay.addEventListener("click", function (e) {
    if (e.target === overlay) {
      closePopup("requestFormPopup");
    }
  });
});

function openPopup(popupId) {
  document.getElementById(popupId).classList.add("open-popup");
  document.getElementById("overlay").style.visibility = "visible";
  document.getElementById("overlay").style.opacity = "1";
}

function closePopup(popupId) {
  document.getElementById(popupId).classList.remove("open-popup");
  document.getElementById("overlay").style.visibility = "hidden";
  document.getElementById("overlay").style.opacity = "0";
}

function autoResize(textarea) {
  textarea.style.height = "auto";
  textarea.style.height = "${textarea.scrollHeight}px";
  // textarea.style.height = `${textarea.scrollHeight}px`;
}

function toggleSidebar() {
  document.getElementById("sidebar").classList.toggle("active");
}

document.addEventListener("click", function (event) {
  if (event.target.classList.contains("overlay")) {
    const currentSelection = document.getElementById("statusSelect").value;
    if (currentSelection === originalStatus) {
      closePopup("statusPopup");
    }
  }
});

let currentTaskId = null;
let originalStatus = null;
function openStatusPopup(taskId, currentStatus) {
  currentTaskId = taskId;

  const formattedStatus = currentStatus
    .split("_")
    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
    .join(" ");

  document.getElementById("currentStatus").textContent = formattedStatus;
  document.getElementById("statusSelect").value = currentStatus;

  originalStatus = currentStatus;
  document.getElementById("statusPopup").classList.add("open-popup");
  document.getElementById("overlay").style.visibility = "visible";
  document.getElementById("overlay").style.opacity = "1";
}

function updateStatus() {
  const status = document.getElementById("statusSelect").value;
  updateTaskStatus(currentTaskId, status);
  document.getElementById("statusPopup").classList.remove("open-popup");
  document.getElementById("overlay").style.visibility = "hidden";
  document.getElementById("overlay").style.opacity = "0";
}

function updateTaskStatus(taskId, status) {
  fetch(`${URLROOT}/technician/tasks`, {
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
  fetch(`${URLROOT}/technician/details/${taskId}`, {
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
  fetch(`${URLROOT}/technician/details/${taskId}`, {
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

