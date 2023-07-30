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
        let request = new XMLHttpRequest();
        request.open("DELETE", deleteForm.action, true);
        request.setRequestHeader(
          "Content-Type",
          "application/x-www-form-urlencoded; charset=UTF-8"
        );
        request.onload = function () {
          if (request.status >= 200 && request.status < 400) {
            // Redirect to the index page
            window.location.href = "/advice";
          } else {
            // reached our target server, but it returned an error
            console.log(
              "Une erreur est survenue lors de la suppression de l'élément",
              request.responseText
            );
          }
        };

        request.send(new URLSearchParams(new FormData(deleteForm)).toString());
      });
    }
  });
}

console.log("adviceForm.js loaded");
