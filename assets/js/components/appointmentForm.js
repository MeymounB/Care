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

    // Initial setup based on the value of isPresential
    if (isPresentialInputs[0].checked) {
      addressDiv.classList.remove("hidden");
      linkDiv.classList.add("hidden");
      addressInput.required = true;
      linkInput.required = false;
    } else {
      linkDiv.classList.remove("hidden");
      addressDiv.classList.add("hidden");
      linkInput.required = true;
      addressInput.required = false;
    }

    isPresentialInputs.forEach((input) =>
      input.addEventListener("change", function () {
        if (this.value === "1") {
          addressDiv.classList.remove("hidden");
          linkDiv.classList.add("hidden");
          addressInput.required = true;
          linkInput.required = false;
        } else {
          linkDiv.classList.remove("hidden");
          addressDiv.classList.add("hidden");
          linkInput.required = true;
          addressInput.required = false;
        }
      })
    );
  });
}
