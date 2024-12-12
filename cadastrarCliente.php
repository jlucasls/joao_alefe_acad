<?php
    // Inclui o arquivo de conexão com o banco de dados, que contém a configuração do PDO
    include 'conexao.php';

    // Verifica se o formulário foi submetido (verifica se o método de envio foi POST)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Obtém os dados do formulário (nome, email, senha e plano) enviados pelo método POST
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografa a senha antes de salvar no banco
        $plano = $_POST['plano'];

        // Comando SQL para inserir os dados no banco de dados na tabela 'clientes'
        $sql = "INSERT INTO clientes (nome, email, senha, plano) VALUES (:nome, :email, :senha, :plano)";

        // Prepara a consulta SQL para execução
        $stmt = $pdo->prepare($sql);

        // Liga os parâmetros da consulta SQL aos valores obtidos no formulário
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':plano', $plano);

        // Executa a consulta e verifica se a operação foi bem-sucedida
        if ($stmt->execute()) {
            // Se a execução for bem-sucedida, exibe uma mensagem de sucesso
            echo '<div class="alert alert-success">Cliente cadastrado com sucesso!</div>';
        } else {
            // Se ocorrer um erro na execução, exibe uma mensagem de erro
            echo '<div class="alert alert-danger">Erro ao cadastrar cliente.</div>';
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Clientes</title>
    <!-- Inclusão do CSS do Bootstrap para estilizar o formulário -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h4>Cadastro de Cliente</h4>
                    </div>
                    <div class="card-body">
                        <!-- Formulário de cadastro que envia os dados para a própria página -->
                        <form action="cadastrarCliente.php" method="post">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome:</label>
                                <!-- Campo para o nome do cliente -->
                                <input type="text" id="nome" name="nome" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail:</label>
                                <!-- Campo para o email do cliente -->
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha:</label>
                                <!-- Campo para a senha do cliente -->
                                <input type="password" id="senha" name="senha" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="plano" class="form-label">Plano:</label>
                                <!-- Campo de seleção de plano do cliente -->
                                <select id="plano" name="plano" class="form-control" required>
                                    <option value="mensal">Mensal</option>
                                    <option value="diario">Diário</option>
                                    <option value="anual">Anual</option>
                                </select>
                            </div>
                            <!-- Botão para submeter o formulário -->
                            <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                        </form>
                        <div class="mt-3 text-center">
                            <!-- Link para a página de login caso o usuário já tenha uma conta -->
                            <p>Já tem uma conta? <a href="login.php">Faça login agora</a></p>
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
