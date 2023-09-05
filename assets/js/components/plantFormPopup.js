export function initPlantFormPopup() {
  let plantFormPopup = document.getElementById("plant-form-popup");
  let editPlantFormLink = document.getElementById("edit-plant-form-link");
  let cancelPlantButton = document.getElementById("cancel-plant-button");

  if (editPlantFormLink) {
    editPlantFormLink.addEventListener("click", function (e) {
      e.preventDefault();
      if (plantFormPopup) {
        plantFormPopup.style.display = "block";
      }
    });
  }

  if (cancelPlantButton) {
    cancelPlantButton.addEventListener("click", function (e) {
      e.preventDefault();
      if (plantFormPopup) {
        plantFormPopup.style.display = "none";
      }
    });
  }
}
