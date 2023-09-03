import {
  initIsPresential,
  initDatetimeValidation,
} from "./components/appointmentForm";

import {initShowModal, initDeleteForm} from "./components/adviceForm";
import {initDeleteAccountPopup} from "./components/delete_account_popup";

initIsPresential();
initDatetimeValidation();
initDeleteAccountPopup();
initShowModal();
initDeleteForm();
