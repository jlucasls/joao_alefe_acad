<?php
session_start();
include 'conexao.php'; // Inclui a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Usa a variável $pdo já definida em 'conexao.php'
    $sql = "DELETE FROM clientes WHERE id = :id";
    $stmt = $pdo->prepare($sql); // Usando a variável $pdo
    $stmt->bindParam(':id', $id);

    try {
        $stmt->execute();
        $_SESSION['mensagem'] = "Cliente excluído com sucesso!";
    } catch (PDOException $e) {
        $_SESSION['mensagem'] = "Erro ao excluir cliente: " . $e->getMessage();
    }

    header("Location: cadastrarCliente.php"); // Redireciona após a exclusão
    exit;
}
?>
