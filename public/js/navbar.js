document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('menu-toggle');
  const menu = document.getElementById('dropdown-menu');
  let backdrop = document.getElementById('dropdown-backdrop');

  // Create backdrop if not present
  if (!backdrop) {
    backdrop = document.createElement('div');
    backdrop.id = 'dropdown-backdrop';
    document.body.appendChild(backdrop);
  }

  toggle.addEventListener('click', (e) => {
    e.stopPropagation();
    menu.classList.toggle('open');
    backdrop.classList.toggle('active');
  });

  backdrop.addEventListener('click', () => {
    menu.classList.remove('open');
    backdrop.classList.remove('active');
  });

  // Optional: close if click outside menu and toggle
  window.addEventListener('click', (e) => {
    if (!menu.contains(e.target) && !toggle.contains(e.target)) {
      menu.classList.remove('open');
      backdrop.classList.remove('active');
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
            div.classList.add('suggestion-item');
            div.addEventListener('click', () => {
              window.location.href = `/pages/view_service.php?id=${item.id}`;
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
