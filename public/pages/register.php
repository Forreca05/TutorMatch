<?php 
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../../private/utils/csrf.php');
drawHeader(); ?>
<link rel="stylesheet" href="../css/register.css">

<div class="container-sm text-center">
  <h2>Cria a tua Conta</h2>


  <form action="../actions/action_register.php" method="post" class="form">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    <input type="text" name="username" placeholder="Nome de utilizador" class="form-input" required>
    <input type="email" name="email" placeholder="Email" class="form-input" required>
    <input type="password" name="password" placeholder="Palavra-passe" class="form-input" 
       pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{7,}$" 
       title="A palavra-passe deve ter pelo menos 7 caracteres, incluindo uma letra e um número." 
       required>
    <label class="checkbox-label">
      <input type="checkbox" required>
      Aceito os <a href="#" id="terms-link">Termos e Serviços</a>
    </label>

    <button type="submit" class="btn btn-primary btn-full">Criar conta</button>
  </form>
</div>

<!-- Modal dos Termos -->
<div id="terms-modal" class="modal">
  <div class="modal-content">
    <span class="modal-close">&times;</span>
    <h3>Termos de Utilização e Política de Privacidade</h3>
    <p>
      Ao utilizar a nossa plataforma, concorda em respeitar os nossos termos de serviço, a política de privacidade e todas as leis aplicáveis.
      Compromete-se a:
    </p>
    <ul>
      <li>Utilizar a plataforma de forma ética e legal.</li>
      <li>Não partilhar a sua conta com terceiros.</li>
      <li>Não publicar conteúdos ofensivos, ilegais ou fraudulentos.</li>
      <li>Respeitar os outros utilizadores e as suas opiniões.</li>
    </ul>

    <h4>Pagamentos e Responsabilidade</h4>
    <p>
      Os pagamentos devem ser feitos apenas através da plataforma. Não somos responsáveis por transações realizadas fora do nosso sistema.
      Em caso de disputa entre utilizadores, a nossa equipa tentará intermediar, mas não garantimos resolução.
    </p>

    <h4>Privacidade e Proteção de Dados</h4>
    <p>
      Guardamos os seus dados com segurança e não os partilhamos com terceiros sem consentimento, exceto quando exigido por lei.
      Utilizamos cookies e tecnologias semelhantes para melhorar a sua experiência.
    </p>

    <h4>Suspensão de Conta</h4>
    <p>
      Reservamo-nos o direito de suspender ou eliminar contas que violem os nossos termos ou coloquem outros utilizadores em risco.
    </p>

    <p>
      Ao criar uma conta, confirma que leu e aceitou estes termos.
    </p>
  </div>
</div>

<script src="../js/terms.js"></script>

<?php drawFooter(); ?>