export function initPlantFormPopup() {
  let plantFormPopup = document.getElementById("plant-form-popup");
  let openPlantDetailsLink = document.getElementById("open-plant-details");
  let cancelPlantButton = document.getElementById("cancel-plant-button");

  if (openPlantDetailsLink) {
    openPlantDetailsLink.addEventListener("click", function (e) {
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

console.log("plantFormPopup.js loaded");
