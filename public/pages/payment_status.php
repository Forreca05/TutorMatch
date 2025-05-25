<?php
session_start();
$result = $_GET['result'] ?? 'error';           // success | error
$redirect = '../pages/orders.php';
?>
<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="utf-8">
  <title>Processamento de Pagamento</title>
  <link rel="stylesheet" href="../css/payment_status.css">
</head>

<body>
  <div class="payment-overlay">
    <div class="payment-box">
      <div id="spinner" class="spinner"></div>
      <h3 id="status-text">A processar o seu pagamento…</h3>

      <div id="result-msg" class="hidden
         <?= $result === 'success' ? 'success' : 'error' ?>">
        <?= $result === 'success'
          ? 'Pagamento concluído com sucesso!'
          : 'O pagamento falhou. Tente novamente.' ?>
      </div>
    </div>
  </div>

  <script>
    /* mostra “concluído” após 5 s e redirecciona após 3 s */
    setTimeout(() => {
      document.getElementById('spinner').style.display = 'none';
      document.getElementById('status-text').style.display = 'none';
      document.getElementById('result-msg').classList.remove('hidden');
      setTimeout(() => {
        window.location.href = '<?= $redirect ?>';
      }, 3000);
    }, 5000);
  </script>
</body>

</html>