<?php 
$tabela = 'solicitar_emprestimo';
require_once("../../../conexao.php");

$id = $_POST['id'];
$acao = $_POST['acao'];

$pdo->query("UPDATE $tabela SET status = '$acao' WHERE id = '$id' ");
echo 'Alterado com Sucesso';
?>