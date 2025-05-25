document.addEventListener('DOMContentLoaded', () => {
  const msg = document.querySelector('.flash-message');
  if (msg) {
    setTimeout(() => {
      msg.style.display = 'none';
    }, 3500);
  }
});
