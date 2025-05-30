<?php 
$tabela = 'solicitar_emprestimo';
require_once("../../../conexao.php");
@session_start();
$id_usuario = @$_SESSION['id'];

$valor = $_POST['valor'];
$valor = str_replace('.', '', $valor);
$valor = str_replace(',', '.', $valor);
$parcelas = $_POST['parcelas'];
$data = $_POST['data'];
$garantia = $_POST['garantia'];
$obs = $_POST['obs'];
$cliente = $_POST['cliente'];
$id = $_POST['id'];



if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET parcelas = :parcelas, valor = :valor, data = '$data', status = 'Pendente', cliente = '$cliente', obs = :obs, garantia = :garantia ");
	
}else{
$query = $pdo->prepare("UPDATE $tabela SET parcelas = :parcelas, valor = :valor, data = '$data', status = 'Pendente', cliente = '$cliente', obs = :obs, garantia = :garantia where id = '$id'");
}
$query->bindValue(":parcelas", "$parcelas");
$query->bindValue(":valor", "$valor");
$query->bindValue(":obs", "$obs");
$query->bindValue(":garantia", "$garantia");
$query->execute();

echo 'Salvo com Sucesso';


$dataF = implode('/', array_reverse(@explode('-', $data)));
	
$valorF = number_format($valor, 2, ',', '.');

$query2 = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome = @$res2[0]['nome'];
$telefone = @$res2[0]['telefone'];

$tel_cliente = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
$telefone_envio = $tel_cliente;

if($token != "" and $instancia != ""){
	$mensagem = '*'.$nome_sistema.'* %0A';
	$mensagem .= '_Nova solicitação de Empréstimo_ %0A';
	$mensagem .= 'Cliente: *'.$nome.'* %0A';
	$mensagem .= 'Data: *'.$dataF.'* %0A';
	$mensagem .= 'Valor: *'.$valorF.'* %0A';
	$mensagem .= 'Parcelas: *'.$parcelas.'* %0A';
	$mensagem .= 'Garantia: *'.$garantia.'* %0A';
	$mensagem .= 'Obs: *'.$obs.'* %0A';
	require('../../../painel/apis/texto.php');
}

 ?>