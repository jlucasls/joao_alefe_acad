<?php
session_start();
include 'conexao.php';

// Verifica se o usuário está autenticado como "admin@gmail.com"
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    // Se o e-mail não for "admin@gmail.com", redireciona para a página painel.php
    header("Location: painel.php");
    exit;
}

// Verifica se foi enviado um pedido de atualização de dados
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    // Recebe os dados do formulário de atualização
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $plano = $_POST['plano'];

    // Conecta ao banco de dados (usa a variável PDO previamente configurada)
    $conn = $pdo;
    $sql = "UPDATE clientes SET nome = :nome, email = :email, plano = :plano WHERE id = :id"; // SQL para atualizar os dados
    $stmt = $conn->prepare($sql);
    
    // Vincula os parâmetros do SQL com os dados recebidos
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':plano', $plano);
    $stmt->bindParam(':id', $id);

    try {
        // Executa a atualização no banco de dados
        $stmt->execute();
        $_SESSION['mensagem'] = "Cliente atualizado com sucesso!"; // Mensagem de sucesso
        header("Location: editar.php"); // Redireciona de volta para a página de edição
        exit;
    } catch (PDOException $e) {
        // Caso ocorra um erro, exibe a mensagem de erro
        $_SESSION['mensagem'] = "Erro ao atualizar cliente: " . $e->getMessage();
    }
}

// Carrega todos os clientes do banco para exibir na tabela
$sql = "SELECT * FROM clientes";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC); // Armazena os dados dos clientes
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Clientes</title>
    <!-- Inclusão do CSS do Bootstrap para estilização -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h4>Editar Clientes</h4>
        <!-- Botão adicional para acessar editar2.php -->
        <a href="editar2.php" class="btn btn-secondary w-100 mt-3">Ir para Gerenciar Instrutores</a>

        <!-- Exibe a mensagem de sucesso ou erro, se houver -->
        <?php
        if (isset($_SESSION['mensagem'])) {
            echo '<div class="alert alert-info">' . $_SESSION['mensagem'] . '</div>';
            unset($_SESSION['mensagem']);
        }
        ?>

        <!-- Tabela para exibir a lista de clientes -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Plano</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exibe cada cliente em uma linha da tabela -->
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?php echo $cliente['id']; ?></td>
                        <td><?php echo $cliente['nome']; ?></td>
                        <td><?php echo $cliente['email']; ?></td>
                        <td><?php echo $cliente['plano']; ?></td>
                        <td>
                            <!-- Link para editar o cliente -->
                            <a href="editar.php?id=<?php echo $cliente['id']; ?>" class="btn btn-warning">Editar</a>
                            <!-- Formulário para excluir o cliente -->
                            <form action="excluir.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">
                                <button type="submit" class="btn btn-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php
        // Verifica se foi passado um id na URL para carregar os dados de um cliente específico
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            // Carrega os dados do cliente a ser editado
            $sql = "SELECT * FROM clientes WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>

            <h4>Editar Cliente: <?php echo $cliente['nome']; ?></h4>
            <!-- Formulário para atualizar os dados do cliente -->
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($cliente['nome']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($cliente['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="plano">Plano:</label>
                    <select class="form-control" name="plano" required>
                        <!-- Define a opção selecionada com base no plano do cliente -->
                        <option <?php if ($cliente['plano'] == 'mensal') echo 'selected'; ?>>mensal</option>
                        <option <?php if ($cliente['plano'] == 'diario') echo 'selected'; ?>>diario</option>
                        <option <?php if ($cliente['plano'] == 'anual') echo 'selected'; ?>>anual</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Atualizar</button>
            </form>

        <?php
        }
        ?>

    </div>

    <!-- Inclusão dos scripts do Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
