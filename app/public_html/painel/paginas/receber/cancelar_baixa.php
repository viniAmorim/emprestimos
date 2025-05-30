<?php 
$tabela = 'receber';
require_once("../../../conexao.php");

$id = $_POST['id'];

$pdo->query("UPDATE $tabela SET pago = 'Não', data_pgto = '', ref_pix = '', forma_pgto = '' WHERE id = '$id' ");
echo 'Excluído com Sucesso';
?>