document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('menu-toggle');
  const menu = document.getElementById('dropdown-menu');

  toggle.addEventListener('click', (e) => {
    e.stopPropagation(); // impede o fecho imediato
    menu.classList.toggle('open');
  });

  // Fecha o menu se clicares fora
  window.addEventListener('click', (e) => {
    if (!menu.contains(e.target) && !toggle.contains(e.target)) {
      menu.classList.remove('open');
    }
  });
});
