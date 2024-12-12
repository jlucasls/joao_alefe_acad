<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        session_start();

                        // Inclui o arquivo de conexão com o banco de dados
                        require 'conexao.php';

                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            // Obtém os dados do formulário
                            $email = $_POST['email'];
                            $senha = $_POST['senha'];

                            // Verifica se é o admin
                            if ($email == 'admin@gmail.com' && $senha == 'admin123') {
                                $_SESSION['email'] = $email;  // Salva o email do admin na sessão
                                header("Location: editar.php"); // Redireciona para a página de editar
                                exit;
                            }

                            // Consulta no banco de dados para verificar o e-mail
                            $sql = "SELECT id, nome, email, senha, plano FROM clientes WHERE email = :email";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':email', $email);
                            $stmt->execute();

                            // Verifica se o cliente existe
                            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($cliente && password_verify($senha, $cliente['senha'])) {
                                // Senha correta, cria sessão e redireciona
                                $_SESSION['id'] = $cliente['id'];
                                $_SESSION['nome'] = $cliente['nome'];
                                $_SESSION['email'] = $cliente['email'];
                                $_SESSION['plano'] = $cliente['plano'];

                                header("Location: painel.php"); // Redireciona para o painel
                                exit;
                            } else {
                                // E-mail ou senha inválidos
                                echo '<div class="alert alert-danger">E-mail ou senha inválidos.</div>';
                            }
                        }
                        ?>

                        <!-- Formulário de login -->
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" name="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" name="senha" class="form-control" id="senha" required>
                            </div>
                            <p>Não tem uma conta? <a href="cadastrarCliente.php">Cadastre-se agora</a></p>
                            <button type="submit" class="btn btn-primary w-100">Entrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
