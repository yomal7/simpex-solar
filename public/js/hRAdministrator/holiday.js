const URLROOT = "http://localhost/simpex-solar";

function addComment(recordId) {
  const comment = document.getElementById("newComment").value.trim();
  if (!comment) return;

  // Send the request as x-www-form-urlencoded with recordId and comment
  fetch(`${URLROOT}/hRAdministrator/details/${recordId}`, {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `id=${recordId}&comment=${encodeURIComponent(comment)}`, // Correct body format
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

function deleteComment(recordId) {
  if (!confirm("Are you sure you want to delete this comment?")) return;

  // Send the request as x-www-form-urlencoded for deleting the comment
  fetch(`${URLROOT}/hRAdministrator/details/${recordId}`, {
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

document.addEventListener("DOMContentLoaded", function () {
  const forms = document.querySelectorAll(".approval form");

  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      const status = this.querySelector('input[name="status"]').value;
      if (
        !confirm(
          `Are you sure you want to ${status.toLowerCase()} this leave request?`
        )
      ) {
        e.preventDefault();
      }
    });
  });
});
