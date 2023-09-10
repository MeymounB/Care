export function initHandleFileSelect(evt) {
  const files = evt.target.files;
  const file = files[0];
  if (file && file.type.match("image.*")) {
    const reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById("previewImage").src = e.target.result;
    };
    reader.readAsDataURL(file);
  }
}
