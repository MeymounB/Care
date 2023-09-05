import {
  initIsPresential,
  initDatetimeValidation,
} from "./components/appointmentForm";

import {initShowModal, initDeleteForm} from "./components/adviceForm";
import {initDeleteAccountPopup} from "./components/deleteAccountPopup";
import {
  initPlantEditFormPopup,
  initPlantAddFormPopup,
} from "./components/plantFormPopup";

/*
import {initImageSelection, deleteImage} from "./modules/imageSelection";
import {initProfileSettingsButton} from "./modules/profileSettings";
import {initAppointmentRequest} from "./modules/appointmentRequest";
import {initHorizontalScroll} from "./modules/horizontalScroll";
import {initDropdown} from "./modules/dropdown";
import {initSearch} from "./modules/search";
*/

/* COMPONENTS */
initPlantEditFormPopup();
initPlantAddFormPopup();
initShowModal();
initDeleteForm();
initIsPresential();
initDatetimeValidation();
initDeleteAccountPopup();

/* MODULES */
// initImageSelection();
// initProfileSettingsButton();
// initAppointmentRequest();
// initHorizontalScroll();
// initDropdown();
// initSearch();
