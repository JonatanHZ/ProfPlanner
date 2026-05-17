<?php
session_start();

// Se já está logado, não precisa cadastrar
if (isset($_SESSION['usuario_id'])) {
  header("Location: principal.php");
  exit();
}

// Pega o erro da URL se vier algum
$erro = $_GET['erro'] ?? '';
$mensagens = [
  'campos'   => 'Preencha todos os campos obrigatórios.',
  'senha'    => 'As senhas não coincidem.',
  'curta'    => 'A senha deve ter pelo menos 6 caracteres.',
  'email'    => 'Esse email já está cadastrado.',
  'invalido' => 'Email inválido.',
];

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="img/ProfPlanner.ico" type="image/x-icon">
  <link rel="stylesheet" href="css/login.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <title>Cadastro — ProfPlanner</title>
</head>

<body>

  <div class="container" id="container">
    <div class="form-container entrar">
      <form action="registrar.php" method="POST" onsubmit="return validarCadastro()">
        <h1>Criar conta</h1>

        <?php if ($erro && isset($mensagens[$erro])): ?>
          <p class="erro-msg">
            <i class="bi bi-exclamation-circle"></i>
            <?= $mensagens[$erro] ?>
          </p>
        <?php endif; ?>

        <input
          type="text"
          name="nome"
          id="nome"
          placeholder="Nome completo"
          value="<?= htmlspecialchars($_GET['nome'] ?? '') ?>"
          required />

        <input
          type="email"
          name="email"
          id="email"
          placeholder="Email (será seu login)"
          value="<?= htmlspecialchars($_GET['email'] ?? '') ?>"
          required />

        <input
          type="password"
          name="senha"
          id="senha"
          placeholder="Senha (mínimo 6 caracteres)"
          required
          oninput="verificarForca(this.value)"/>

        <!-- Barra de força da senha -->
        <div class="senha-forca-wrap">
          <div class="senha-forca-barra">
            <div class="senha-forca-fill" id="forca-fill"></div>
          </div>
          <p class="senha-forca-label" id="forca-label"></p>
        </div>

        <input
          type="password"
          name="confirmar_senha"
          id="confirmar_senha"
          placeholder="Confirmar senha"
          required/>

        <button type="submit">Criar conta</button>

        <a href="login.php" style="font-size:13px; color:#333; margin-top:10px; text-decoration:none;">
          Já tem conta? <strong>Entrar</strong>
        </a>
      </form>
    </div>
  </div>
  <script src="js/cadastro.js"></script>
</body>

</html>