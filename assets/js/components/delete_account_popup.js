export function initDeleteAccountPopup() {
  let popup = document.getElementById("delete-account-popup");
  let cancelButton = document.getElementById("cancel-button");
  let confirmButton = document.getElementById("confirm-button");
  let deleteAccountLink = document.getElementById("delete-account-link");

  if (deleteAccountLink) {
    deleteAccountLink.addEventListener("click", function (e) {
      e.preventDefault();
      if (popup) {
        popup.style.display = "block";
        setTimeout(function () {
          popup.style.opacity = "1";
        }, 10);
      }
    });
  }

  function hidePopup() {
    if (popup) {
      popup.style.opacity = "0";
      setTimeout(function () {
        popup.style.display = "none";
      }, 300);
    }
  }

  if (cancelButton) {
    cancelButton.addEventListener("click", hidePopup);
  }

  if (confirmButton) {
    let deleteForm = document.getElementById("delete-form");

    confirmButton.addEventListener("click", function () {
      fetch(deleteForm.action, {
        method: "DELETE",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        },
        body: new URLSearchParams(new FormData(deleteForm)).toString(),
      })
        .then((response) => {
          if (!response.ok) {
            throw response;
          }
          console.log("L'élément a bien été supprimé");
          window.location.href = "/";
        })
        .catch((error) => {
          error.text().then((errorMessage) => {
            console.log(
              "Une erreur est survenue lors de la suppression de l'élément",
              errorMessage
            );
          });
        });
      hidePopup();
    });
  }
}

console.log("delete account popup loaded");
