<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit;
}

$nome_usuario = $_SESSION['nome']; // Pega o nome do usuário da sessão
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h3>Bem-vindo, <?php echo htmlspecialchars($nome_usuario); ?>!</h3>

        <a href="login.php" class="btn btn-danger">Sair</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
