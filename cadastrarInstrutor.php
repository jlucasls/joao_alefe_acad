<?php
// Inclui o arquivo de conexão com o banco de dados
include 'conexao.php';

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados enviados pelo formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografa a senha

    // Comando SQL para inserir os dados na tabela 'instrutor'
    $sql = "INSERT INTO instrutor (nome, email, senha) VALUES (:nome, :email, :senha)";

    // Prepara a consulta SQL
    $stmt = $pdo->prepare($sql);

    // Liga os parâmetros da consulta SQL aos valores obtidos no formulário
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);

    // Executa a consulta e exibe mensagem de sucesso ou erro
    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Instrutor cadastrado com sucesso!</div>';
    } else {
        echo '<div class="alert alert-danger">Erro ao cadastrar instrutor.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Instrutor</title>
    <!-- Inclusão do CSS do Bootstrap para estilizar o formulário -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h4>Cadastro de Instrutor</h4>
                    </div>
                    <div class="card-body">
                        <!-- Formulário de cadastro que envia os dados para a própria página -->
                        <form action="cadastrarInstrutor.php" method="post">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome:</label>
                                <!-- Campo para o nome do instrutor -->
                                <input type="text" id="nome" name="nome" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail:</label>
                                <!-- Campo para o email do instrutor -->
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha:</label>
                                <!-- Campo para a senha do instrutor -->
                                <input type="password" id="senha" name="senha" class="form-control" required>
                            </div>
                            <!-- Botão para submeter o formulário -->
                            <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                        </form>
                        <div class="mt-3 text-center">
                            <!-- Link para a página de login caso o instrutor já tenha uma conta -->
                            <p>Já tem uma conta? <a href="login2.php">Faça login agora</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inclusão dos scripts do Bootstrap para funcionalidades como botões e modais -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
