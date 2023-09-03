export function initHorizontalScroll() {
  let rdvsContainer = document.querySelector(".rdvs");

  if (rdvsContainer) {
    let isMouseDown = false;
    let startX;
    let scrollLeft;

    rdvsContainer.addEventListener("mousedown", (e) => {
      isMouseDown = true;
      startX = e.pageX - rdvsContainer.offsetLeft;
      scrollLeft = rdvsContainer.scrollLeft;
    });

    rdvsContainer.addEventListener("mouseleave", () => {
      isMouseDown = false;
    });

    rdvsContainer.addEventListener("mouseup", () => {
      isMouseDown = false;
    });

    rdvsContainer.addEventListener("mousemove", (e) => {
      if (!isMouseDown) return;
      e.preventDefault();
      let x = e.pageX - rdvsContainer.offsetLeft;
      let walk = (x - startX) * 3; // Ajustez la vitesse de défilement selon vos préférences
      rdvsContainer.scrollLeft = scrollLeft - walk;
    });
  }
}

console.log("horizontalScroll.js chargé");
