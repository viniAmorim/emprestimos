<?php
require_once('../../../conexao.php');

$id = $_POST['id'] ?? '';

$query = $pdo->prepare("DELETE FROM produtos_emprestimos WHERE id = :id");
$query->bindValue(":id", $id);
$query->execute();

echo 'Excluído com Sucesso';
?>