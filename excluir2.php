<?php
session_start();
include 'conexao.php'; // Inclui a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Usa a variável $pdo já definida em 'conexao.php'
    $sql = "DELETE FROM instrutor WHERE id = :id";
    $stmt = $pdo->prepare($sql); // Usando a variável $pdo
    $stmt->bindParam(':id', $id);

    try {
        $stmt->execute();
        $_SESSION['mensagem'] = "instrutor excluído com sucesso!";
    } catch (PDOException $e) {
        $_SESSION['mensagem'] = "Erro ao excluir instrutor: " . $e->getMessage();
    }

    header("Location: cadastrarInstrutor.php"); // Redireciona após a exclusão
    exit;
}
?>
