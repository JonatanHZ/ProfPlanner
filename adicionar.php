<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$avatar_letra = strtoupper(mb_substr($_SESSION['nome'], 0, 1));
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="img/ProfPlanner.ico" type="image/x-icon">
  <link rel="stylesheet" href="css/Adicionar.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <title>ProfPlanner — Adicionar</title>
</head>

<body>
  <!-- HEADER -->
  <header>
    <div class="header-inner">
      <div class="logo">
        <div class="logo-icon"><i class="bi bi-book-half"></i></div>
        <span class="logo-text">Prof<span class="logo-accent">Planner</span></span>
      </div>

      <!-- BOTÃO HAMBURGUER -->
      <button class="menu-toggle" id="menu-toggle">
        <i class="bi bi-list"></i>
      </button>

      <nav class="nav" id="nav">
        <a href="principal.php" class="nav-link">Início</a>
        <a href="agenda-aula.php" class="nav-link-active">Aulas</a>
       
        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'admin') { ?>
        <a href="usuario_listar.php" class="nav-link"> Usuários </a>
        <?php } ?>
                
        <a href="#" class="nav-link">Turmas</a>
        <a href="sair.php" class="nav-link">Sair</a>
      </nav>

      <div class="header-actions">
        <button class="btn-icon notif-btn">
          <i class="bi bi-bell"></i>
          <span class="notif-dot"></span>
        </button>
        <div class="avatar"><?= $avatar_letra ?></div>
      </div>
    </div>
  </header>

  <div class="container">
    <h2>Novo Compromisso</h2>
    <form action="salvar.php" method="POST" onsubmit="return validarForm()">

      <select name="professor_id" id="professor_id" required>
        <option value="" disabled selected>Selecione um professor</option>
        <?php
          $resultado = $conn->query("SELECT pessoa_id, nome FROM tbPessoas ORDER BY nome ASC");
          while ($linha = $resultado->fetch_assoc()) {
            echo "<option value='{$linha['pessoa_id']}'>{$linha['nome']}</option>";
          }
        ?>
      </select>

      <select name="agenda_tipo_id" id="agenda_tipo_id" required>
        <option value="" disabled selected>Selecione o Tipo</option>
        <?php
          $tipos = $conn->query("SELECT agenda_tipo_id, nome FROM tbAgendaTipo ORDER BY nome ASC");
          while ($linha = $tipos->fetch_assoc()) {
            echo "<option value='{$linha['agenda_tipo_id']}'>{$linha['nome']}</option>";
          }
        ?>
      </select>

      <label for="data">Data</label>
      <input type="date" name="data" id="data" required>

      <label for="hora">Horário (opcional)</label>
      <input type="time" name="hora" id="hora">

      <label for="observacao">Descrição</label>
      <input type="text" name="observacao" id="observacao" placeholder="Ex: Matemática - 8º Ano">

      <button type="submit">Salvar</button>
    </form>
  </div>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-inner">
      <span class="footer-brand">ProfPlanner</span>
      <span class="footer-copy">© 2025 · Todos os direitos reservados</span>
    </div>
  </footer>

  <script src="js/adicionar.js"></script>
  <script src="js/menu_responsivo.js"></script>
</body>
</html>