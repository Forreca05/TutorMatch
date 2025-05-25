async function currencyAPI() {
  const precoSpan = document.getElementById('preco');
  const targetSelect = document.getElementById('currency');
  const baseEurAmount = parseFloat(precoSpan.dataset.eur);
  const targetCurrency = targetSelect.value;

  if (isNaN(baseEurAmount)) {
    precoSpan.textContent = 'Preço inválido';
    return;
  }

  const apiUrl = "https://api.freecurrencyapi.com/v1/latest?apikey=fca_live_4hPrrPbeQw6KDaTEBHHeg2FuYuPCrsWJPUIbUxUF";

  try {
    const res = await fetch(apiUrl);
    if (!res.ok) throw new Error('Falha na rede');
    const { data: rates } = await res.json();

    if (!rates.EUR || !rates[targetCurrency]) {
      throw new Error(`Taxa ${targetCurrency} indisponível`);
    }

    // EUR → USD
    const usd = baseEurAmount / rates.EUR;

    // USD → target
    const converted = usd * rates[targetCurrency];

    const formatter = new Intl.NumberFormat(undefined, {
      style: 'currency',
      currency: targetCurrency,
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });

    precoSpan.textContent = formatter.format(converted);

  } catch (err) {
    console.error(err);
    precoSpan.textContent = 'Erro na conversão';
  }
}

window.addEventListener('DOMContentLoaded', () => {
  document.getElementById('currency').addEventListener('change', currencyAPI);
  currencyAPI();
});