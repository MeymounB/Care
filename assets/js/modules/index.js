import {initAppointmentRequest} from "./appointmentRequest.js";
import {initImageSelection} from "./imageSelection.js";
import {initProfileSettingsButton} from "./profileSettings.js";
import {initHorizontalScroll} from "./horizontalScroll.js";
import {initDropdown} from "./dropdown.js";
import {initSearch} from "./search.js";

export function init() {
  initAppointmentRequest();
  initImageSelection();
  initProfileSettingsButton();
  initHorizontalScroll();
  initDropdown();
  initSearch();
}
