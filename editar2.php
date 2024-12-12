<?php
session_start();
include 'conexao.php';

// Verifica se o usuário está autenticado como "admin@gmail.com"
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    header("Location: painel.php");
    exit;
}

// Verifica se foi enviado um pedido de atualização de dados
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    // Recebe os dados do formulário de atualização
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    // Conecta ao banco de dados e atualiza os dados
    $sql = "UPDATE instrutor SET nome = :nome, email = :email WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $id);

    try {
        $stmt->execute();
        $_SESSION['mensagem'] = "Instrutor atualizado com sucesso!";
        header("Location: editar2.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['mensagem'] = "Erro ao atualizar instrutor: " . $e->getMessage();
    }
}

// Carrega todos os instrutores para exibir na tabela
$sql = "SELECT * FROM instrutor";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$instrutores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Instrutores</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h4>Editar Instrutores</h4>

        <!-- Exibe a mensagem de sucesso ou erro -->
        <?php
        if (isset($_SESSION['mensagem'])) {
            echo '<div class="alert alert-info">' . $_SESSION['mensagem'] . '</div>';
            unset($_SESSION['mensagem']);
        }
        ?>

        <!-- Tabela para exibir a lista de instrutores -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Data de Cadastro</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exibe cada instrutor em uma linha da tabela -->
                <?php foreach ($instrutores as $instrutor): ?>
                    <tr>
                        <td><?php echo $instrutor['id']; ?></td>
                        <td><?php echo $instrutor['nome']; ?></td>
                        <td><?php echo $instrutor['email']; ?></td>
                        <td><?php echo $instrutor['data_cadastro']; ?></td>
                        <td>
                            <!-- Link para editar o instrutor -->
                            <a href="editar2.php?id=<?php echo $instrutor['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <!-- Formulário para excluir o instrutor -->
                            <form action="excluir2.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $instrutor['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php
        // Verifica se foi passado um id na URL para carregar os dados de um instrutor específico
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            // Carrega os dados do instrutor a ser editado
            $sql = "SELECT * FROM instrutor WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $instrutor = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>

            <h4>Editar Instrutor: <?php echo $instrutor['nome']; ?></h4>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $instrutor['id']; ?>">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($instrutor['nome']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($instrutor['email']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Atualizar</button>
            </form>

        <?php
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
