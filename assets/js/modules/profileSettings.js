export function initProfileSettingsButton() {
  let bouton = document.getElementById("monBouton");
  let elementCache = document.getElementById("monElementCache");

  bouton.addEventListener("click", () => {
    elementCache.style.display = "block !important";
    // elementCache.style.display = 'none'; // Pour masquer l'élément
  });
}

console.log("profileSettings.js chargé");
