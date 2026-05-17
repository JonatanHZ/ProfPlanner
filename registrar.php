<?php
session_start();
include 'conexao.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cadastro.php");
    exit();
}

$nome            = trim($_POST['nome']);
$email           = trim($_POST['email']);
$senha           = $_POST['senha'];
$confirmar_senha = $_POST['confirmar_senha'];

// Validações no servidor

if (!$nome || !$email || !$senha || !$confirmar_senha) {
    header("Location: cadastro.php?erro=campos&nome=" . urlencode($nome) . "&email=" . urlencode($email));
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: cadastro.php?erro=invalido&nome=" . urlencode($nome));
    exit();
}

if (strlen($senha) < 6) {
    header("Location: cadastro.php?erro=curta&nome=" . urlencode($nome) . "&email=" . urlencode($email));
    exit();
}

if ($senha !== $confirmar_senha) {
    header("Location: cadastro.php?erro=senha&nome=" . urlencode($nome) . "&email=" . urlencode($email));
    exit();
}

//  Verifica se o email já está cadastrado 
$check = $conn->prepare("SELECT usuario_id FROM tbUsuarios WHERE login = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    // Email já existe no banco
    header("Location: cadastro.php?erro=email&nome=" . urlencode($nome));
    exit();
}
$check->close(); 

$hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    INSERT INTO tbUsuarios (nome, login, senha, atualizado_em)
    VALUES (?, ?, ?, NOW())
");
$stmt->bind_param("sss", $nome, $email, $hash);

if ($stmt->execute()) {
    // Redireciona para o login com mensagem de sucesso
    header("Location: login.php?sucesso=1");
} else {
    echo "Erro ao cadastrar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
