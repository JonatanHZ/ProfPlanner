<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$nome_usuario = $_SESSION['nome'];
$avatar_letra = strtoupper(mb_substr($nome_usuario, 0, 1));

$stats = $conn->query("
    SELECT
        (SELECT COUNT(*) FROM tbAgenda
         JOIN tbAgendaTipo ON tbAgenda.agenda_tipo_id = tbAgendaTipo.agenda_tipo_id
         WHERE tbAgendaTipo.nome LIKE '%ula%'
         AND WEEK(tbAgenda.data) = WEEK(NOW())
         AND YEAR(tbAgenda.data) = YEAR(NOW())) AS aulas_semana,

        (SELECT COUNT(*) FROM tbAgenda) AS total_compromissos,

        (SELECT COUNT(*) FROM tbAgenda
         WHERE hora IS NOT NULL) AS horas_planejadas,

        (SELECT COUNT(DISTINCT professor_id) FROM tbAgenda) AS professores_ativos
")->fetch_assoc();

$eventos = $conn->query("
    SELECT tbAgenda.agenda_id, tbAgenda.data, tbAgenda.hora,
           tbAgenda.observacao, tbAgendaTipo.nome AS tipo
    FROM tbAgenda
    JOIN tbAgendaTipo ON tbAgenda.agenda_tipo_id = tbAgendaTipo.agenda_tipo_id
    WHERE tbAgenda.data >= CURDATE()
    ORDER BY tbAgenda.data ASC, tbAgenda.hora ASC
    LIMIT 5
");

$proxima_aula = $conn->query("
    SELECT tbAgenda.data, tbAgenda.hora, tbAgenda.observacao
    FROM tbAgenda
    JOIN tbAgendaTipo ON tbAgenda.agenda_tipo_id = tbAgendaTipo.agenda_tipo_id
    WHERE tbAgendaTipo.nome LIKE '%ula%'
    AND tbAgenda.data >= CURDATE()
    ORDER BY tbAgenda.data ASC, tbAgenda.hora ASC
    LIMIT 1
")->fetch_assoc();

$meses = [
    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
];

function formatar_data($data_str, $meses) {
    $ts = strtotime($data_str);
    return date('d', $ts) . ' de ' . $meses[(int)date('m', $ts)] . ' de ' . date('Y', $ts);
}

function badge_tipo($tipo) {
    $t = strtolower($tipo);
    if (str_contains($t, 'aula'))   return 'tag-blue';
    if (str_contains($t, 'reuni'))  return 'tag-green';
    if (str_contains($t, 'palest')) return 'tag-yellow';
    return 'tag-blue';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="img/ProfPlanner.ico" type="image/x-icon">
    <title>ProfPlanner</title>
    <link rel="stylesheet" href="css/principal.css" />
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
                <div class="logo-icon"><i class="bi bi-book-half"></i></div>
                <span class="logo-text">Prof<span class="logo-accent">Planner</span></span>
            </div>

            <!-- BOTÃO HAMBURGUER -->
            <button class="menu-toggle" id="menu-toggle">
                <i class="bi bi-list"></i>
            </button>

            <nav class="nav" id="nav">
                <a href="#" class="nav-link active">Início</a>
                <a href="agenda-aula.php" class="nav-link">Aulas</a>
                <a href="#" class="nav-link">Turmas</a>
                
                <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'admin') { ?>
                <a href="usuario_listar.php" class="nav-link"> Usuários </a>
                <?php } ?>
                
                <a href="sair.php" class="nav-link-sair">Sair</a>
            </nav>
            
            <div class="header-actions">
                <button class="btn-icon notif-btn">
                    <i class="bi bi-bell"></i>
                    <span class="notif-dot"></span>
                </button>
                <div class="avatar" title="<?= htmlspecialchars($nome_usuario) ?>">
                    <?= $avatar_letra ?>
                </div>
            </div>
        </div>
    </header>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-badge">
                <i class="bi bi-mortarboard"></i>
                Sistema de Agenda Docente
            </div>
            <h1 class="hero-title">
                Bem-vindo(a),
                <span class="hero-highlight"><?= htmlspecialchars($nome_usuario) ?>!</span>
            </h1>
            <p class="hero-sub">
                Gerencie suas aulas, eventos e reuniões de forma simples e organizada.
            </p>
            <div class="hero-btns">
                <a href="agenda-aula.php" class="btn btn-outline-hero">Ver Horários</a>
            </div>
        </div>
    </section>

    <!-- CARDS DE ESTATÍSTICAS -->
    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon icon-blue"><i class="bi bi-calendar3"></i></div>
                <div>
                    <p class="stat-value"><?= $stats['aulas_semana'] ?></p>
                    <p class="stat-label">Aulas Esta Semana</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-green"><i class="bi bi-people"></i></div>
                <div>
                    <p class="stat-value"><?= $stats['professores_ativos'] ?></p>
                    <p class="stat-label">Professores Ativos</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-yellow"><i class="bi bi-clock"></i></div>
                <div>
                    <p class="stat-value"><?= $stats['horas_planejadas'] ?>h</p>
                    <p class="stat-label">Horas Planejadas</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-red"><i class="bi bi-graph-up-arrow"></i></div>
                <div>
                    <p class="stat-value"><?= $stats['total_compromissos'] ?></p>
                    <p class="stat-label">Compromissos</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="main-content">
        <div class="content-grid">

            <section class="events-section">
                <div class="section-header">
                    <h2 class="section-title">Próximos Compromissos</h2>
                    <button class="btn btn-primary-sm">
                        <i class="bi bi-plus-lg"></i>
                        <a href="adicionar.php"> Adicionar</a>
                    </button>
                </div>
                <div class="events-card">
                    <div class="events-card-header">
                        <div class="events-card-title-group">
                            <div class="events-icon"><i class="bi bi-calendar-event"></i></div>
                            <div>
                                <h3 class="events-card-title">Agenda</h3>
                                <p class="events-card-sub">Compromissos a partir de hoje</p>
                            </div>
                        </div>
                    </div>

                    <ul class="event-list">
                        <?php if ($eventos->num_rows === 0): ?>
                            <li style="padding:24px; text-align:center; color:#888;">
                                Nenhum compromisso futuro cadastrado.
                                <a href="adicionar.php">Adicionar agora</a>
                            </li>
                        <?php else: ?>
                            <?php while ($ev = $eventos->fetch_assoc()): ?>
                                <?php
                                $data_fmt = formatar_data($ev['data'], $meses);
                                $hora_fmt = $ev['hora'] ? date('H:i', strtotime($ev['hora'])) : '';
                                $badge    = badge_tipo($ev['tipo']);
                                ?>
                                <li class="event-item" data-id="<?= $ev['agenda_id'] ?>">
                                    <div class="event-info">
                                        <div class="event-title-row">
                                            <span class="event-name">
                                                <?= htmlspecialchars($ev['observacao'] ?? $ev['tipo']) ?>
                                            </span>
                                            <span class="tag <?= $badge ?>">
                                                <?= htmlspecialchars($ev['tipo']) ?>
                                            </span>
                                        </div>
                                        <span class="event-date">
                                            <i class="bi bi-clock"></i>
                                            <?= $data_fmt ?>
                                            <?= $hora_fmt ? "às {$hora_fmt}" : '' ?>
                                        </span>
                                    </div>
                                    <div class="event-actions">
                                        <a href="editar.php?id=<?= $ev['agenda_id'] ?>"
                                            class="row-btn edit-row" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="row-btn delete-row"
                                            onclick="excluir(<?= $ev['agenda_id'] ?>)"
                                            title="Excluir">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </ul>

                    <div class="events-card-footer">
                        <span class="footer-info">Mostrando próximos 5 compromissos</span>
                        <a href="agenda-aula.php" class="btn-outline-sm" style="color: #0f1e3c;">Ver todos</a>
                    </div>
                </div>
            </section>

            <!-- SIDEBAR -->
            <aside class="sidebar">
                <div class="sidebar-card">
                    <h3 class="sidebar-card-title">Próxima Aula</h3>
                    <?php if ($proxima_aula): ?>
                        <p class="sidebar-card-sub">
                            <?= formatar_data($proxima_aula['data'], $meses) ?>
                            <?= $proxima_aula['hora'] ? ' às ' . date('H:i', strtotime($proxima_aula['hora'])) : '' ?>
                        </p>
                        <div class="next-class-box">
                            <i class="bi bi-calendar3 next-class-icon"></i>
                            <div>
                                <p class="next-class-name">
                                    <?= htmlspecialchars($proxima_aula['observacao'] ?? 'Aula') ?>
                                </p>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="sidebar-card-sub">Nenhuma aula agendada.</p>
                        <div class="next-class-box">
                            <i class="bi bi-calendar-x next-class-icon"></i>
                            <div>
                                <p class="next-class-name">Sem aulas próximas</p>
                                <p class="next-class-info"><a href="adicionar.php">Agendar agora</a></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-inner">
            <span class="footer-brand">ProfPlanner</span>
            <span class="footer-copy">© 2025 · Todos os direitos reservados</span>
        </div>
    </footer>

    <script src="js/menu_responsivo.js"></script>
    <script>
        function excluir(id) {
            if (confirm('Tem certeza que deseja excluir este compromisso?')) {
                window.location.href = 'excluir.php?id=' + id;
            }
        }
    </script>
</body>
</html>