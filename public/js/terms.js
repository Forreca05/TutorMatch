const modal = document.getElementById("terms-modal");
const openLink = document.getElementById("terms-link");
const closeBtn = document.querySelector(".close-modal");

openLink.onclick = function (e) {
  e.preventDefault();
  modal.style.display = "block";
};

closeBtn.onclick = function () {
  modal.style.display = "none";
};

window.onclick = function (event) {
  if (event.target === modal) {
    modal.style.display = "none";
  }
};
