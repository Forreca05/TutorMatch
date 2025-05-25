const toggle = document.getElementById('darkModeSwitch');
const rootEl = document.documentElement; // the <html> element

// 1) on load, read saved preference and apply
const saved = localStorage.getItem('theme');
if (saved === 'dark') {
  rootEl.setAttribute('data-theme', 'dark');
  toggle.checked = true;
}

// 2) when user flips the switch…
toggle.addEventListener('change', () => {
  if (toggle.checked) {
    rootEl.setAttribute('data-theme', 'dark');
    localStorage.setItem('theme', 'dark');
  }
  else {
    rootEl.removeAttribute('data-theme');
    localStorage.setItem('theme', 'light');
  }
});