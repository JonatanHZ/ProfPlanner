<?php
session_start();

// Se já está logado, não precisa ver o login
if (isset($_SESSION['usuario_id'])) {
    header("Location: principal.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/login.css"/>
    <link rel="shortcut icon" href="img/ProfPlanner.ico" type="image/x-icon">
    <title>Login — ProfPlanner</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  </head>
  <body>

    <div class="container" id="container">
      <div class="form-container entrar">
        <form action="autenticar.php" method="POST">         
          <h1>Entrar</h1>

          <?php if (isset($_GET['sucesso'])): ?>
            <p class="sucesso-msg">
              <i class="bi bi-check-circle"></i>
              Conta criada com sucesso! Faça login.
            </p>
          <?php endif; ?>

          <?php if (isset($_GET['erro'])): ?>
            <p class="erro-msg">
              <i class="bi bi-exclamation-circle"></i>
              Email ou senha incorretos.
            </p>
          <?php endif; ?>

          <input type="email" name="email" placeholder="Email" required />
          <input type="password" name="senha" placeholder="Senha" required />
          <a href="#" id="esquecer-senha">Esqueceu sua senha?</a>
          <button type="submit">Entrar</button>

          <!-- Link para cadastro -->
          <a href="cadastro.php" style="font-size:13px; color:#333; margin-top:10px; text-decoration:none;">
            Não tem conta? <strong>Cadastre-se</strong>
          </a>
        </form>
      </div>
    </div>
  </body>
</html>
