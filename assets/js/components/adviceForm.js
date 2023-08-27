export function initShowModal() {
  // Get the modal container
  document.addEventListener("DOMContentLoaded", (event) => {
    let modalContainer = document.getElementById("edit-comment-modal");

    // Listen for the click event on each "Edit" button
    document.querySelectorAll(".edit-comment").forEach(function (element) {
      element.addEventListener("click", function (e) {
        e.preventDefault();

        let commentId = this.getAttribute("data-id");

        fetch("/comment/" + commentId + "/edit-form", {
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
            // Set the inner HTML of the modal
            modalContainer.innerHTML = data.form;

            // Show the modal
            modalContainer.classList.remove("hidden");

            let form = modalContainer.querySelector("form");
            console.log(form);
            let button = form.querySelector('button[type="submit"]');
            console.log(button);

            button.addEventListener("click", function (e) {
              e.preventDefault();

              let formData = new FormData(form);

              fetch("/comment/" + commentId + "/edit", {
                method: "POST",
                body: formData,
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
                .then(() => {
                  modalContainer.classList.add("hidden");
                  location.reload();
                })
                .catch((error) => {
                  console.log(
                    "There was a problem with the fetch operation: " +
                      error.message
                  );
                });
            });

            // hide the modal when clicking on the close button
            let modalClose = modalContainer.querySelector("#modal-close");

            if (!modalClose) {
              return;
            }

            modalClose.addEventListener("click", function () {
              modalContainer.classList.add("hidden");
            });
          });
      });
    });
  });
}

export function initDeleteForm() {
  document.addEventListener("DOMContentLoaded", function () {
    let deleteButton = document.getElementById("delete-button");
    if (deleteButton) {
      deleteButton.addEventListener("click", function (e) {
        e.preventDefault();

        let deleteForm = document.getElementById("delete-form");
        fetch(deleteForm.action, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
          },
          body: new URLSearchParams(new FormData(deleteForm)).toString(),
        })
            .then(response => {
              if (!response.ok) { throw response }
              // Redirect to the index page
              window.location.href = "/advice";
            })
            .catch(error => {
              error.text().then(errorMessage => {
                console.log(
                    "Une erreur est survenue lors de la suppression de l'élément",
                    errorMessage
                );
              });
            });
      });
    }
  });
}

console.log("adviceForm.js loaded");
