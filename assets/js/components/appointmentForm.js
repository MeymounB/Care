export function initIsPresential() {
  document.addEventListener("DOMContentLoaded", function () {
    let isPresentialInputs = document.querySelectorAll(
      'input[name="appointment[isPresential]"]'
    );
    let addressInput = document.querySelector(
      'input[name="appointment[address]"]'
    );
    let linkInput = document.querySelector('input[name="appointment[link]"]');
    let addressDiv = document.querySelector("#addressDiv");
    let linkDiv = document.querySelector("#linkDiv");

    isPresentialInputs.forEach((input) =>
      input.addEventListener("change", function () {
        if (this.value === "1") {
          addressDiv.classList.remove("hidden");
          linkDiv.classList.add("hidden");
          addressInput.required = true; // mark the address field as required
          linkInput.required = false; // mark the link field as not required
        } else {
          linkDiv.classList.remove("hidden");
          addressDiv.classList.add("hidden");
          linkInput.required = true; // mark the link field as required
          addressInput.required = false; // mark the address field as not required
        }
      })
    );
  });
}
