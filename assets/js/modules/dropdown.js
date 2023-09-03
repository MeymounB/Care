export function initDropdown() {
  let dropdown = document.getElementById("myDropdown");

  function toggleDropdown() {
    dropdown.style.display =
      dropdown.style.display === "none" ? "block" : "none";
  }

  dropdown.addEventListener("click", toggleDropdown);
}

console.log("dropdown.js chargé");
