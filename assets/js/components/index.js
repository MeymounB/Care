import {initDeleteForm, initShowModal} from "./advice/adviceForm";
import {initDatetimeValidation, initIsPresential} from "./appt/appointmentForm";
import {initDeleteAccountPopup} from "./profil/deleteAccountPopup";
import {
  initPlantAddFormPopup,
  initPlantEditFormPopup,
} from "./profil/plantFormPopup";
import {initAddressFormPopup} from "./profil/addressFormPopup";

export function init() {
  initDatetimeValidation();
  initDeleteAccountPopup();
  initDeleteForm();
  initIsPresential();
  initPlantAddFormPopup();
  initPlantEditFormPopup();
  initShowModal();
  initAddressFormPopup();
}
