export function initPlantEditFormPopup() {
  let plantFormPopup = document.getElementById("plant-form-popup");
  let openPlantDetailsLink = document.getElementById("open-plant-details");
  let cancelPlantButton = document.getElementById("cancel-plant-button");

  if (openPlantDetailsLink) {
    openPlantDetailsLink.addEventListener("click", function (e) {
      e.preventDefault();
      if (plantFormPopup) {
        plantFormPopup.style.display = "block";
        setTimeout(function () {
          plantFormPopup.style.opacity = "1";
        }, 10);
      }
    });
  }

  if (cancelPlantButton) {
    cancelPlantButton.addEventListener("click", function (e) {
      e.preventDefault();
      if (plantFormPopup) {
        plantFormPopup.style.opacity = "0";
        setTimeout(function () {
          plantFormPopup.style.display = "none";
        }, 250);
      }
    });
  }
}

export function initPlantAddFormPopup() {
  document.addEventListener("DOMContentLoaded", function () {
    let openPlantFormLink = document.getElementById("open-plant-form");
    let plantModal = document.getElementById("plant-modal");

    openPlantFormLink.addEventListener("click", function (e) {
      e.preventDefault();
      // let commentId = this.getAttribute("data-id");

      fetch("/plant/new", {
        method: "POST",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.text();
        })
        .then((formHtml) => {
          plantModal.innerHTML = formHtml;
          plantModal.style.display = "block";
        })
        .catch((error) => {
          console.error("Error fetching form:", error);
        });
    });
  });
}
