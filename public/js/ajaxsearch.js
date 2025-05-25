const searchInput = document.getElementById("search-input2");
const resultsContainer = document.querySelector(".results");
searchInput.addEventListener("input", async () => {
  const q = searchInput.value.trim();
  const params = new URLSearchParams({ q });

  const response = await fetch(`../actions/search_ajax.php?${params.toString()}`);
  const data = await response.json();
  resultsContainer.innerHTML = "";
  if (data.length > 0) {
    data.forEach(service => {
      const serviceDiv = document.createElement("div");
      serviceDiv.classList.add("service");
      serviceDiv.innerHTML = `
            <h3>${service.title}</h3>
            <p>${service.description}</p>
            <p><strong>Preço:</strong> €${parseFloat(service.price).toFixed(2)}</p>
            <a href="/pages/view_service.php?id=${service.id}">Ver mais »</a>
          `;
      resultsContainer.appendChild(serviceDiv);
    });
  } else {
    resultsContainer.innerHTML = "<p>Nenhum serviço encontrado com os filtros aplicados.</p>";
  }
});