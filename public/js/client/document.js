document.addEventListener("DOMContentLoaded", function () {
  const dropArea = document.getElementById("dropArea");
  const fileInput = document.getElementById("fileInput");
  const uploadUI = document.getElementById("uploadUI");
  const previewContainer = document.getElementById("previewContainer");
  const dropAreaPreview = document.getElementById("dropAreaPreview");
  const fileName = document.getElementById("fileName");
  const fileSize = document.getElementById("fileSize");
  const removeFileBtn = document.getElementById("removeFile");
  const submitButton = document.getElementById("submitButton");
  const projectId = document.getElementById("projectId").value;

  // Prevent default drag behaviors
  ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
    dropArea.addEventListener(eventName, preventDefaults, false);
    document.body.addEventListener(eventName, preventDefaults, false);
  });

  function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
  }

  // Highlight drop area when item is dragged over it
  ["dragenter", "dragover"].forEach((eventName) => {
    dropArea.addEventListener(eventName, highlight, false);
  });

  // Remove highlight when item is dragged out or dropped
  ["dragleave", "drop"].forEach((eventName) => {
    dropArea.addEventListener(eventName, unhighlight, false);
  });

  function highlight() {
    dropArea.classList.add("dragover");
  }

  function unhighlight() {
    dropArea.classList.remove("dragover");
  }

  // Handle dropped files
  dropArea.addEventListener("drop", handleDrop, false);
  fileInput.addEventListener("change", handleChange, false);
  removeFileBtn.addEventListener("click", removeFile, false);

  function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    handleFiles(files);
  }

  function handleChange() {
    const files = this.files;
    handleFiles(files);
  }

  function handleFiles(files) {
    if (files.length) {
      const file = files[0];
      if (validateFile(file)) {
        showPreview(file);
      }
    }
  }

  function validateFile(file) {
    // Check file type - ONLY allow PDF files
    const validTypes = ["application/pdf"];
    if (!validTypes.includes(file.type)) {
      alert("Only PDF files are allowed");
      return false;
    }

    // Check file size (max 5MB)
    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
      alert("File size must be less than 5MB");
      return false;
    }

    return true;
  }

  function showPreview(file) {
    // For PDF files, show the PDF icon
    dropAreaPreview.src = `${URLROOT}/public/assets/pdf-icon.png`;
    dropAreaPreview.style.display = "block";

    // Update file info
    fileName.textContent = file.name;
    fileSize.textContent = formatFileSize(file.size);

    // Show preview container, hide upload UI
    uploadUI.style.display = "none";
    previewContainer.classList.add("show");

    // Enable submit button
    submitButton.disabled = false;
  }

  function formatFileSize(bytes) {
    if (bytes < 1024) return bytes + " bytes";
    else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + " KB";
    else return (bytes / 1048576).toFixed(1) + " MB";
  }

  function removeFile() {
    fileInput.value = "";
    dropAreaPreview.src = "";
    uploadUI.style.display = "flex";
    previewContainer.classList.remove("show");
    submitButton.disabled = true;
  }

  // Handle form submission
  submitButton.addEventListener("click", async function () {
    const file = fileInput.files[0];
    if (!file) {
      alert("Please select a file first");
      return;
    }

    // Create FormData object
    const formData = new FormData();
    formData.append("document", file);
    formData.append("project_id", projectId);

    try {
      submitButton.disabled = true;
      submitButton.textContent = "Uploading...";

      const response = await fetch(`${URLROOT}/client/submitDocument`, {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.success) {
        alert("Document submitted successfully!");
        window.location.reload();
      } else {
        throw new Error(data.message || "Error submitting document");
      }
    } catch (error) {
      alert(
        error.message || "An error occurred while submitting your document"
      );
    } finally {
      submitButton.disabled = false;
      submitButton.textContent = "Submit Document";
    }
  });
});
