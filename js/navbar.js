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

document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('search-input');
  const suggestionsBox = document.getElementById('suggestions-box');

  searchInput.addEventListener('input', () => {
    const query = searchInput.value.trim();

    if (query.length < 2) {
      suggestionsBox.style.display = 'none';
      return;
    }

    fetch(`/actions/search_suggestions.php?q=${encodeURIComponent(query)}`)
      .then(res => res.json())
      .then(data => {
        suggestionsBox.innerHTML = '';
        if (data.length > 0) {
          data.forEach(item => {
            const div = document.createElement('div');
            div.textContent = item.title;
            div.addEventListener('click', () => {
              searchInput.value = item.title;
              document.getElementById('search-form').submit();
            });
            suggestionsBox.appendChild(div);
          });
          suggestionsBox.style.display = 'block';
        } else {
          suggestionsBox.style.display = 'none';
        }
      });
  });

  document.addEventListener('click', (e) => {
    if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
      suggestionsBox.style.display = 'none';
    }
  });
});
