<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: agenda-aula.php");
    exit();
}

$id = (int) $_GET['id'];
$avatar_letra = strtoupper(mb_substr($_SESSION['nome'], 0, 1));

$stmt = $conn->prepare("SELECT * FROM tbAgenda WHERE agenda_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$comp = $stmt->get_result()->fetch_assoc();

if (!$comp) {
    header("Location: agenda-aula.php");
    exit();
}
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
    <title>ProfPlanner — Editar</title>
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
        <h2>Editar Compromisso</h2>

        <form action="atualizar.php" method="POST" onsubmit="return validarForm()">
            <input type="hidden" name="agenda_id" value="<?= $comp['agenda_id'] ?>">

            <select name="professor_id" id="professor_id" required>
                <option value="" disabled>Selecione um professor</option>
                <?php
                    $profs = $conn->query("SELECT pessoa_id, nome FROM tbPessoas ORDER BY nome ASC");
                    while ($p = $profs->fetch_assoc()):
                        $sel = $p['pessoa_id'] == $comp['professor_id'] ? 'selected' : '';
                ?>
                    <option value="<?= $p['pessoa_id'] ?>" <?= $sel ?>><?= htmlspecialchars($p['nome']) ?></option>
                <?php endwhile; ?>
            </select>

            <select name="agenda_tipo_id" id="agenda_tipo_id" required>
                <option value="" disabled>Selecione o Tipo</option>
                <?php
                    $tipos = $conn->query("SELECT agenda_tipo_id, nome FROM tbAgendaTipo ORDER BY nome ASC");
                    while ($t = $tipos->fetch_assoc()):
                        $sel = $t['agenda_tipo_id'] == $comp['agenda_tipo_id'] ? 'selected' : '';
                ?>
                    <option value="<?= $t['agenda_tipo_id'] ?>" <?= $sel ?>><?= htmlspecialchars($t['nome']) ?></option>
                <?php endwhile; ?>
            </select>

            <label for="data">Data</label>
            <input type="date" name="data" id="data" value="<?= $comp['data'] ?>" required>

            <label for="hora">Horário (opcional)</label>
            <input type="time" name="hora" id="hora" value="<?= $comp['hora'] ?? '' ?>">

            <label for="observacao">Descrição</label>
            <input type="text" name="observacao" id="observacao"
                   value="<?= htmlspecialchars($comp['observacao'] ?? '') ?>"
                   placeholder="Ex: Matemática - 8º Ano">

            <button type="submit">Salvar alterações</button>
            <a href="agenda-aula.php"
               style="display:block; text-align:center; margin-top:10px; font-size:13px; color:#666; text-decoration:none;">
                Cancelar
            </a>
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