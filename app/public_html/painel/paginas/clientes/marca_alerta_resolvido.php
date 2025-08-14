<?php
require_once("../../../conexao.php");

// Verifica se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Validação básica para garantir que o ID é um número
    if (filter_var($id, FILTER_VALIDATE_INT)) {
        try {
            // Prepara e executa a query de atualização
            $pdo->beginTransaction();
            $query = $pdo->prepare("UPDATE alertas_duplicidade SET resolvido = 1 WHERE id = :id");
            $query->bindValue(":id", $id, PDO::PARAM_INT);
            $query->execute();
            $pdo->commit();

            echo "sucesso";

        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "erro: " . $e->getMessage();
        }
    } else {
        echo "erro: ID inválido.";
    }
} else {
    echo "erro: Requisição inválida.";
}
?>