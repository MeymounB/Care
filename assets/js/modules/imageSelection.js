export function initImageSelection() {
  let fileInput = document.getElementById("image");
  let previewImage = document.getElementById("previewImage");
  let imageLabel = document.getElementById("imageLabel");

  function handleFileSelect(event) {
    // Get the selected file(s)
    let files = event.target.files;
    if (files && files.length > 0) {
      // Process the selected file(s) as needed
      for (let i = 0; i < files.length; i++) {
        let file = files[i];
        console.log("Selected file:", file.name);
        // You can perform further actions with the file, such as reading its content or uploading it to a server.
      }
    }
  }

  fileInput.addEventListener("change", handleFileSelect);
}

export function deleteImage() {
  let imageLabel = document.getElementById("imageLabel");
  let deleteImageBtn = imageLabel.querySelector(".delete-image");
  let imageInput = document.getElementById("image");
  let previewImages = imageLabel.querySelectorAll("img");

  for (let i = 0; i < previewImages.length; i++) {
    imageLabel.removeChild(previewImages[i]);
  }

  deleteImageBtn.style.display = "none";
  imageLabel.querySelector("span").style.display = "block";
  previewImage.style.display = "none";
  imageInput.value = null;
}
