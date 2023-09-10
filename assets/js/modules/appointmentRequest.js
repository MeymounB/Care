export function initAppointmentRequest() {
  let datepicker = document.getElementById("datepicker");
  let hourSelect = document.getElementById("hour");
  let minuteSelect = document.getElementById("minute");

  // Obtenez la date actuelle et calculez la date dans 3 jours
  let now = new Date();
  let threeDaysLater = new Date(now);
  threeDaysLater.setDate(now.getDate() + 2);

  // Calculez la date dans 3 jours + 5 ans
  let threeDaysLaterFiveYears = new Date(threeDaysLater);
  threeDaysLaterFiveYears.setFullYear(threeDaysLater.getFullYear() + 5);

  // Configurez les valeurs min et max pour le datepicker
  datepicker.min = threeDaysLater.toISOString().split("T")[0];
  datepicker.max = threeDaysLaterFiveYears.toISOString().split("T")[0];

  // Générez les options pour les heures (8-20)
  for (let hour = 8; hour <= 20; hour++) {
    let option = document.createElement("option");
    option.value = hour;
    option.textContent = hour.toString().padStart(2, "0");
    hourSelect.appendChild(option);
  }

  // Générez les options pour les minutes (0-55 par tranche de 5)
  for (let minute = 0; minute <= 55; minute += 5) {
    let option = document.createElement("option");
    option.value = minute;
    option.textContent = minute.toString().padStart(2, "0");
    minuteSelect.appendChild(option);
  }
}
