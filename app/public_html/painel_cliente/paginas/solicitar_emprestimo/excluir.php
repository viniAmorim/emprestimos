<?php 
$tabela = 'solicitar_emprestimo';
require_once("../../../conexao.php");

$id = $_POST['id'];

$pdo->query("DELETE FROM $tabela WHERE id = '$id' ");
echo 'Excluído com Sucesso';
?>