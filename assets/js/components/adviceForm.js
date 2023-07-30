export function initShowModal() {
  // Get the modal container
  document.addEventListener("DOMContentLoaded", (event) => {
    var modalContainer = document.getElementById("edit-comment-modal");

    // Listen for the click event on each "Edit" button
    document.querySelectorAll(".edit-comment").forEach(function (element) {
      element.addEventListener("click", function (e) {
        e.preventDefault();

        var commentId = this.getAttribute("data-id");

        fetch("/comment/" + commentId + "/edit", {
          method: "GET",
          headers: {
            "X-Requested-With": "XMLHttpRequest",
          },
        })
          .then((response) => response.text())
          .then((data) => {
            // Set the inner HTML of the modal
            modalContainer.innerHTML = data;

            // Show the modal
            modalContainer.classList.remove("hidden");

            modalContainer
              .querySelector("#modal-close")
              .addEventListener("click", function () {
                modalContainer.classList.add("hidden");
              });
          });
      });
    });
  });
}

console.log("adviceForm.js loaded");
