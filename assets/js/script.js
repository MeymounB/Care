// DropDown pour demande de conseil
function showDropdown() {
	var dropdown = document.getElementById("myDropdown");
	if (dropdown.style.display === "none") {
		dropdown.style.display = "block";
	} else {
		dropdown.style.display = "none";
	}
}

window.onload = function () {
	var checkboxes = document.querySelectorAll("#myDropdown input[type='checkbox']");
	var input = document.getElementById("myInput");

	for (var i = 0; i < checkboxes.length; i++) {
		checkboxes[i].addEventListener('change', function () {
			var selectedItems = [];
			for (var j = 0; j < checkboxes.length; j++) {
				if (checkboxes[j].checked) {
					selectedItems.push(checkboxes[j].value);
				}
			}
			input.value = selectedItems.join(", "); // Afficher les éléments sélectionnés dans l'input
		});
	}
};

// Script pour fichier dans ajout plante
// JavaScript code to handle file selection
var fileInput = document.getElementById("image");

fileInput.addEventListener("change", function (event) {
	// Get the selected file(s)
	var files = event.target.files;
	if (files && files.length > 0) {
		// Process the selected file(s) as needed
		for (var i = 0; i < files.length; i++) {
			var file = files[i];
			console.log("Selected file:", file.name);
			// You can perform further actions with the file, such as reading its content or uploading it to a server.
		}
	}
});

// script pour preview img
// JavaScript code to handle file selection
function openFileInput(event) {
	event.preventDefault(); // évite de lancer deux fois la demande de photos
	document.getElementById("image").click();
}

function handleFileSelect(event) {
	var previewImage = document.getElementById("previewImage");
	var imageLabel = document.getElementById("imageLabel");
	var deleteImageBtn = imageLabel.querySelector(".delete-image");
	var imageInput = document.getElementById("image");

	// Get the selected files
	var files = event.target.files;
	var numImagesSelected = files.length;

	if (numImagesSelected === 0) {
		// Show an alert if no image is selected
		alert("Veuillez sélectionner au moins une image.");
		return;
	}

	if (numImagesSelected > 3) {
		// Show an alert if more than 3 images are selected
		alert("Vous ne pouvez sélectionner que 3 images maximum.");
		return;
	}

	// Clear previous preview images
	var previewImages = imageLabel.querySelectorAll("img");
	for (var i = 0; i < previewImages.length; i++) {
		imageLabel.removeChild(previewImages[i]);
	}

	// Loop through each selected file and create a preview image for it (up to 3 images)
	for (var i = 0; i < numImagesSelected; i++) {
		var file = files[i];
		var reader = new FileReader();
		reader.onload = function (e) {
			var newPreviewImage = document.createElement("img");
			newPreviewImage.src = e.target.result;
			imageLabel.insertBefore(newPreviewImage, imageLabel.querySelector("span"));
		};
		reader.readAsDataURL(file);
	}

	// Show the delete image button if at least one image is selected
	deleteImageBtn.style.display = numImagesSelected > 0 ? "block" : "none";

	// Hide the "ajouter une image" text if at least one image is selected
	imageLabel.querySelector("span").style.display = numImagesSelected > 0 ? "none" : "block";

	// Hide the preview image if no image is selected
	previewImage.style.display = numImagesSelected > 0 ? "block" : "none";
}

function deleteImage() {
	var imageLabel = document.getElementById("imageLabel");
	var deleteImageBtn = imageLabel.querySelector(".delete-image");
	var imageInput = document.getElementById("image");
	var previewImages = imageLabel.querySelectorAll("img");

	// Clear all preview images
	for (var i = 0; i < previewImages.length; i++) {
		imageLabel.removeChild(previewImages[i]);
	}

	// Hide the delete image button
	deleteImageBtn.style.display = "none";

	// Show the "ajouter une image" text
	imageLabel.querySelector("span").style.display = "block";

	// Hide the preview image
	previewImage.style.display = "none";

	// Clear the input value
	imageInput.value = null;
}

// QUI NE FONCTIONNE PAS
// script pour bouton settings page de profil
document.addEventListener("DOMContentLoaded", function () {
	const bouton = document.getElementById('monBouton');
	const elementCache = document.getElementById('monElementCache');

	bouton.addEventListener('click', () => {
		elementCache.style.display = 'block !important'; // Pour afficher l'élément
		// Ou
		// elementCache.style.display = 'none'; // Pour masquer l'élément
	});
});

// Search-bar et filtre : Forum 
$(document).ready(function () {
	$("#searchForm").submit(function (event) {
		event.preventDefault(); // Empêche le formulaire de se soumettre

		// Récupérer la valeur de la barre de recherche
		var searchTerm = $("#searchInput").val();

		// Récupérer la valeur du bouton radio sélectionné (public ou privé)
		var filterOption = $("input[name='filter']:checked").val();

		// Exécutez votre logique de recherche ici avec searchTerm et filterOption
		// Dans cet exemple, nous affichons simplement les valeurs sélectionnées dans la console.
		console.log("Recherche:", searchTerm);
		console.log("Filtre:", filterOption);

		// Vous pouvez effectuer des actions supplémentaires, telles que charger les résultats de recherche dans la section "results".
		// Par exemple, vous pouvez utiliser une requête AJAX pour récupérer les résultats de recherche à partir d'un serveur.
	});
});


// Scroll horizontal 
// const content = document.getElementsByClassName('rdvs');

// content.addEventListener('mousemove', (e) => {
// 	const delta = e.clientX - window.innerWidth / 2;
// 	content.scrollLeft += delta * 0.05; // Ajustez le facteur de vitesse selon vos préférences
// });

const rdvsContainer = document.querySelector('.rdvs');

if (rdvsContainer) {
	let isMouseDown = false;
	let startX;
	let scrollLeft;

	rdvsContainer.addEventListener('mousedown', (e) => {
		isMouseDown = true;
		startX = e.pageX - rdvsContainer.offsetLeft;
		scrollLeft = rdvsContainer.scrollLeft;
	});

	rdvsContainer.addEventListener('mouseleave', () => {
		isMouseDown = false;
	});

	rdvsContainer.addEventListener('mouseup', () => {
		isMouseDown = false;
	});

	rdvsContainer.addEventListener('mousemove', (e) => {
		if (!isMouseDown) return;
		e.preventDefault();
		const x = e.pageX - rdvsContainer.offsetLeft;
		const walk = (x - startX) * 3; // Ajustez la vitesse de défilement
		rdvsContainer.scrollLeft = scrollLeft - walk;
	});
}


// Demande de rendez-vous
const datepicker = document.getElementById('datepicker');
const hourSelect = document.getElementById('hour');
const minuteSelect = document.getElementById('minute');

// Obtenir la date actuelle et calculer la date dans 3 jours
const now = new Date();
const threeDaysLater = new Date(now);
threeDaysLater.setDate(now.getDate() + 2);

// Calculer la date dans 3 jours + 5 ans
const threeDaysLaterFiveYears = new Date(threeDaysLater);
threeDaysLaterFiveYears.setFullYear(threeDaysLater.getFullYear() + 5);

// Configurer les valeurs min et max pour le datepicker
datepicker.min = threeDaysLater.toISOString().split('T')[0];
datepicker.max = threeDaysLaterFiveYears.toISOString().split('T')[0];

// Générer les options pour les heures (8-20)
for (let hour = 8; hour <= 20; hour++) {
	const option = document.createElement('option');
	option.value = hour;
	option.textContent = hour.toString().padStart(2, '0');
	hourSelect.appendChild(option);
}

// Générer les options pour les minutes (0-55 par tranche de 5)
for (let minute = 0; minute <= 55; minute += 5) {
	const option = document.createElement('option');
	option.value = minute;
	option.textContent = minute.toString().padStart(2, '0');
	minuteSelect.appendChild(option);
}