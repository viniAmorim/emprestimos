<?php 

require_once("../../../conexao.php");

$tabela = 'pagar';

@session_start();

$id_usuario = $_SESSION['id'];



$dataInicial = @$_POST['data_inicial'];

$dataFinal = @$_POST['data_final'];

$funcionario = @$_POST['id_funcionario'];
$saida = @$_POST['pgto'];


if ($funcionario == '') {

    $funcionario = 0;

}



$pdo->query("UPDATE pagar SET pago = 'Sim', usuario_pgto = '$id_usuario', data_pgto = curDate(), forma_pgto = '$saida' where data >= '$dataInicial' and data <= '$dataFinal' and pago != 'Sim' and funcionario = '$funcionario' and referencia like '%Comissao%'");



echo 'Baixado com Sucesso';

 ?>