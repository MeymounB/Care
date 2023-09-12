export function initUpdateAccountPopup() {
  document.addEventListener("DOMContentLoaded", () => {
    const editAccountLink = document.getElementById("edit-account-form");
    console.log(editAccountLink);
    let accountModal = document.getElementById("accountModal");
    console.log(accountModal);
    let closeModalButton = document.getElementById("closeModalButton");
    console.log(closeModalButton);
    function closeModal() {
      accountModal.style.display = "none";
    }

    // Open the modal and fetch the form
    editAccountLink.addEventListener("click", (e) => {
      e.preventDefault();

      fetch("/mon-profil/edit-form", {
        method: "GET",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.json();
        })
        .then((data) => {
          const modalBody = accountModal.querySelector(".modal-body");
          modalBody.innerHTML = data.form;

          accountModal.style.display = "block";

          let accountForm = accountModal.querySelector("#account-form");

          accountForm.addEventListener("submit", (event) => {
            event.preventDefault();

            let formData = new FormData(accountForm);
            fetch("/mon-profil/edit", {
              method: "POST",
              body: formData,
              headers: {
                "X-Requested-With": "XMLHttpRequest",
              },
            })
              .then((response) => response.json())
              .then((data) => {
                if (data.message === "Success!") {
                  closeModal();
                  location.reload();
                } else {
                  console.error("Error updating account:", data.errors);
                }
              })
              .catch((error) => {
                console.error("Error submitting account form:", error);
              });
          });
        })
        .catch((error) => {
          console.error("Error fetching the form:", error);
        });
    });

    closeModalButton.addEventListener("click", closeModal);
  });
}
