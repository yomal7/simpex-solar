const dropArea = document.getElementById("dropArea");
const fileInput = document.getElementById("fileInput");
const previewArea = document.getElementById("previewArea");
const fileName = document.getElementById("fileName");
const removeFileBtn = document.getElementById("removeFile");
const submitButton = document.getElementById("submitButton");

["dragenter", "dragover", "dragleave", "drop"].forEach((event) => {
  dropArea.addEventListener(event, preventDefaults, false);
  document.body.addEventListener(event, preventDefaults, false);
});

function preventDefaults(e) {
  e.preventDefault();
  e.stopPropagation();
}

["dragenter", "dragover"].forEach((event) => {
  dropArea.addEventListener(event, () => {
    dropArea.classList.add("dragover");
  });
});

["dragleave", "drop"].forEach((event) => {
  dropArea.addEventListener(event, () => {
    dropArea.classList.remove("dragover");
  });
});

dropArea.addEventListener("drop", handleDrop);
fileInput.addEventListener("change", handleFileSelect);
removeFileBtn.addEventListener("click", removeFile);

function handleDrop(e) {
  const files = e.dataTransfer.files;
  handleFiles(files);
}

function handleFileSelect(e) {
  const files = e.target.files;
  handleFiles(files);
}

function handleFiles(files) {
  if (files.length) {
    const file = files[0];
    if (validateFile(file)) {
      showPreview(file);
      submitButton.disabled = false;
    }
  }
}

function validateFile(file) {
  const validTypes = ["image/jpeg", "image/png"];
  const maxSize = 5 * 1024 * 1024; // 5MB

  if (!validTypes.includes(file.type)) {
    alert("Please upload a valid image file (PNG or JPG)");
    return false;
  }

  if (file.size > maxSize) {
    alert("File size must be less than 5MB");
    return false;
  }

  return true;
}

function showPreview(file) {
  const previewImage = document.getElementById("previewImage");
  fileName.textContent = file.name;

  // Create FileReader to read the image
  const reader = new FileReader();

  reader.onload = function (e) {
    previewImage.src = e.target.result;
  };

  reader.readAsDataURL(file);
  previewArea.classList.add("show");
}

function removeFile() {
  fileInput.value = "";
  previewArea.classList.remove("show");
  submitButton.disabled = true;

  // Clear the image source
  const previewImage = document.getElementById("previewImage");
  previewImage.src = "";
}

// submitButton.addEventListener("click", function () {
//   alert("Deposit slip submitted successfully!");
// });

submitButton.addEventListener("click", async function () {
  const fileInput = document.getElementById("fileInput");
  const file = fileInput.files[0];
  const orderId = document.getElementById("orderId").value;

  if (!file) {
    alert("Please select a file first");
    return;
  }

  try {
    submitButton.disabled = true; // Disable button while processing

    const formData = new FormData();
    formData.append("slip", file);
    formData.append("orderId", orderId);

    const response = await fetch(`${URLROOT}/client/processBankDeposit`, {
      method: "POST",
      body: formData,
    });

    const data = await response.json();

    if (data.success) {
      alert("Bank slip uploaded successfully!");
      window.location.href = `${URLROOT}/client/shop`;
    } else {
      throw new Error(data.message || "Failed to process payment");
    }
  } catch (error) {
    alert(error.message || "Something went wrong");
  } finally {
    submitButton.disabled = false; // Re-enable button after processing
  }
});

const uploadUI = document.getElementById("uploadUI");
const previewContainer = document.getElementById("previewContainer");
const dropAreaPreview = document.getElementById("dropAreaPreview");

function showPreview(file) {
  const reader = new FileReader();

  reader.onload = function (e) {
    dropAreaPreview.src = e.target.result;
    uploadUI.style.display = "none";
    previewContainer.classList.add("show");
    submitButton.disabled = false;
  };

  reader.readAsDataURL(file);
}

function removeFile() {
  fileInput.value = "";
  uploadUI.style.display = "flex";
  previewContainer.classList.remove("show");
  dropAreaPreview.src = "";
  submitButton.disabled = true;
}

// Update clientBankDeposit.js
submitButton.addEventListener("click", async function () {
  const fileInput = document.getElementById("fileInput");
  const file = fileInput.files[0];

  if (!file) {
    alert("Please select a file first");
    return;
  }

  const formData = new FormData();
  formData.append("slip", file);
  formData.append("orderId", orderId); // Add orderId as hidden input in view

  try {
    const response = await fetch(`${URLROOT}/client/processBankDeposit`, {
      method: "POST",
      body: formData,
    });

    const data = await response.json();

    if (data.success) {
      alert(data.message);
      window.location.href = `${URLROOT}/client/shop`;
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    alert(error.message || "Something went wrong");
  }
});
