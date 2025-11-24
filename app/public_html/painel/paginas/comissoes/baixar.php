<?php 

require_once("../../../conexao.php");

$tabela = 'pagar';

@session_start();

$id_usuario = $_SESSION['id'];

$id = $_POST['id-baixar'];
$valor = $_POST['valor-baixar'];
$valor = str_replace(',', '.', $valor);
$saida = $_POST['saida-baixar'];
$data_baixar = $_POST['data-baixar'];



$pdo->query("UPDATE $tabela SET pago = 'Sim', usuario_pgto = '$id_usuario', forma_pgto = '$saida', data_pgto = '$data_baixar', valor = '$valor' where id = '$id'");



echo 'Baixado com Sucesso';

 ?>