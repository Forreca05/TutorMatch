const modal = document.getElementById("order-modal");
const openLink = document.getElementById("order-link");
const closeBtn = document.querySelector(".modal-close");

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
