<?php
session_start();
include("conexao.php");
include("admin_verifica.php");
include("conexao.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$avatar_letra = strtoupper(mb_substr($_SESSION['nome'], 0, 1));
$nome_usuario = $_SESSION['nome'];
$sql = "SELECT usuario_id, nome, login 
        FROM tbUsuarios 
        ORDER BY nome ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="img/ProfPlanner.ico" type="image/x-icon">
    <title>ProfPlanner</title>
    <link rel="stylesheet" href="css/usuario_listar.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
</head>

<body>
    <!-- HEADER -->
    <header>
        <div class="header-inner">

            <div class="logo">
                <div class="logo-icon">
                    <i class="bi bi-book-half"></i>
                </div>

                <span class="logo-text">
                    Prof<span class="logo-accent">Planner</span>
                </span>
            </div>

            <!-- BOTÃO HAMBURGUER -->
            <button class="menu-toggle" id="menu-toggle">
                <i class="bi bi-list"></i>
            </button>

            <!-- NAVBAR -->
            <nav class="nav" id="nav">

                <a href="principal.php" class="nav-link">
                    Início
                </a>

                <a href="agenda-aula.php" class="nav-link">
                    Aulas
                </a>

                <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'admin') { ?>

                    <a href="usuario_listar.php" class="nav-link-active">
                        Usuários
                    </a>

                <?php } ?>

                <a href="sair.php" class="nav-link">
                    Sair
                </a>

            </nav>

            <!-- AÇÕES -->
            <div class="header-actions">

                <button class="btn-icon notif-btn">
                    <i class="bi bi-bell"></i>
                    <span class="notif-dot"></span>
                </button>

                <div class="avatar" title="<?= htmlspecialchars($nome_usuario)?>">
                    <?= $avatar_letra ?>
                </div>

            </div>

        </div>
    </header>

    <div class="container">
        <h1>Usuários Cadastrados</h1>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Login</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    if ($result->num_rows > 0) {

                        while ($usuario = $result->fetch_assoc()) {
                            echo "
                    <tr>
                        <td>{$usuario['usuario_id']}</td>
                        <td>{$usuario['nome']}</td>
                        <td>{$usuario['login']}</td>
                    </tr>
                    ";
                        }
                    } else {
                        echo "
                <tr>
                    <td colspan='4'>Nenhum usuário cadastrado.</td>
                </tr>
                ";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-inner">
            <span class="footer-brand">ProfPlanner</span>
            <span class="footer-copy">© 2025 · Todos os direitos reservados</span>
        </div>
    </footer>
</body>
    <script src="js/menu_responsivo.js"></script>
</html>