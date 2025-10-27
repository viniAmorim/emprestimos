<?php 
@session_start();
$id_usuario = @$_SESSION['id'];
$tabela = 'receber';
require_once("../../../conexao.php");

$data_atual = date('Y-m-d');

$id = $_POST['id'];
$obs_amortizar = $_POST['obs_lancar'];
$valor = $_POST['valor_lancar'];
$data_pgto = $_POST['data_lancar'];
$id_cliente = $_POST['id_cliente'];
$forma_pgto = $_POST['forma_pgto_lancar'];

$valor = str_replace('.', '', $valor);
$valor = str_replace(',', '.', $valor);

$query2 = $pdo->query("SELECT * from emprestimos where id = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$cliente = @$res2[0]['cliente'];
$valor_emp = @$res2[0]['valor'];

$query2 = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = @$res2[0]['nome'];

$descricao_emp = 'Empréstimo - '.$nome_cliente;


$valor_emp = $valor_emp + $valor;

$pdo->query("UPDATE emprestimos SET valor = '$valor_emp' where id = '$id'");

$pdo->query("INSERT INTO pagar SET referencia = 'Empréstimo', id_ref = '$id', valor = '$valor',  usuario_lanc = '$id_usuario', data = curDate(), data_venc = curDate(), data_pgto = '$data_pgto', pago = 'Sim', descricao = '$descricao_emp', obs = '$obs_amortizar', usuario_pgto = '$id_usuario', forma_pgto = '$forma_pgto' ");

echo 'Salvo com Sucesso';
?>