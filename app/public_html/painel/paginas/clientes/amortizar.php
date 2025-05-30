<?php 
@session_start();
$id_usuario = @$_SESSION['id'];
$tabela = 'receber';
require_once("../../../conexao.php");

$data_atual = date('Y-m-d');

$id = $_POST['id'];
$obs_amortizar = $_POST['obs_amortizar'];
$valor = $_POST['valor_amortizar'];
$data_pgto = $_POST['data_amortizar'];
$id_cliente = $_POST['id_cliente'];

$query2 = $pdo->query("SELECT * from emprestimos where id = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$cliente = @$res2[0]['cliente'];
$valor_emp = @$res2[0]['valor'];

$valor_emp = $valor_emp - $valor;

$pdo->query("UPDATE emprestimos SET valor = '$valor_emp' where id = '$id'");

$pdo->query("INSERT INTO receber SET cliente = '$cliente', referencia = 'Conta', id_ref = '$id', valor = '$valor',  usuario_lanc = '$id_usuario', data = curDate(), data_venc = curDate(), data_pgto = '$data_pgto', pago = 'Sim', descricao = 'Amortização Empréstimo', frequencia = '0', recorrencia = '', obs = '$obs_amortizar' ");

echo 'Salvo com Sucesso';
?>