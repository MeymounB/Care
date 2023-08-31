import flatpickr from "flatpickr";

export function initIsPresential() {
  document.addEventListener("DOMContentLoaded", function () {
    let isPresentialInputs = document.querySelector(
      '#appointment_isPresential'
    );
    let addressInput = document.querySelector(
      '#appointment_address'
    );
    let addressParent = addressInput.parentElement;
    let linkInput = document.querySelector('input[name="appointment[link]"]');
    let addressDiv = document.querySelector("#addressDiv");
    let linkDiv = document.querySelector("#linkDiv");

    console.log(isPresentialInputs.value, addressInput);

    if (Boolean(Number(isPresentialInputs.value)) === false) {
      addressInput.parentElement.style.display = "none";
    }  

    isPresentialInputs.addEventListener("change", (e) => {
      const isPresential = Boolean(Number(e.target.value)) === true;
      console.log(isPresential);

      if (isPresential) {
        addressParent.style.display = "block";
      } else {
        addressParent.style.display = "none";
      }
    });

    if (
      !isPresentialInputs.length ||
      !addressInput ||
      !linkInput ||
      !addressDiv ||
      !linkDiv
    ) {
      return;
    }

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

export function initDatetimeValidation() {
  // @TODO: should be great if the placeholder was percistant
  let previousValue = "";

  // prevent undifined error
  // let element = document.getElementById("appointment_plannedAt");

  // if (!element) {
  //   return;
  // }

  // element.addEventListener("input", function (e) {
  //   // check if the input is a delete
  //   if (
  //     e.inputType === "deleteContentBackward" &&
  //     e.target.value.length < previousValue.length
  //   ) {
  //     previousValue = e.target.value;
  //     return; // do nothing
  //   }

  //   // delete all non-digit characters
  //   let datetime = e.target.value.replace(/\D/g, "");

  //   // YYYY-MM-DD HH:MM
  //   if (datetime.length >= 4) {
  //     datetime = datetime.slice(0, 4) + "-" + datetime.slice(4);
  //   }
  //   if (datetime.length >= 7) {
  //     datetime = datetime.slice(0, 7) + "-" + datetime.slice(7);
  //   }
  //   if (datetime.length >= 10) {
  //     datetime = datetime.slice(0, 10) + " " + datetime.slice(10);
  //   }
  //   if (datetime.length >= 13) {
  //     datetime = datetime.slice(0, 13) + ":" + datetime.slice(13);
  //   }
  //   // if (datetime.length >= 16) {
  //   //   datetime = datetime.slice(0, 16) + ":" + datetime.slice(16);
  //   // }
  //   // remove extra characters
  //   datetime = datetime.slice(0, 16);

  //   // update the input value
  //   e.target.value = datetime;

  //   // update actual value
  //   previousValue = datetime;
  // });

  // @TODO: should be great to use flatpickr with it but it's not working + should be great to format the date like this : 16-04-2021 14:00 (dd-mm-yyyy hh:mm) : DONE
  // @TODO: library is working, but config does not change. check why.
  // https://flatpickr.js.org/examples/

  let datetimeInput = document.getElementById("appointment_plannedAt");
  flatpickr("#appointment_plannedAt", {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    minTime: "09:00",
    maxTime: "20:00",
    minDate: "today",
    defaultHour: 9,
    defaultMinute: 0,
    enableSeconds: false,
    minuteIncrement: 5,
    onOpen: () => {
      console.log("flatpickr opened");
    }
  });
}

console.log("Hello from appointmentForm.js");
