<?php 
$tabela = 'solicitar_emprestimo';
require_once("../../../conexao.php");
@session_start();
$id_usuario = @$_SESSION['id'];

function limpar_valor($valor) {
  return str_replace(',', '.', str_replace(['R$', '.'], '', $valor));
}

$valor = limpar_valor($_POST['valor']);
$valor_parcela = limpar_valor($_POST['valor_parcela']);
$tipo_vencimento = $_POST['tipo_vencimento'];
$data = $_POST['data'];
$garantia = $_POST['garantia'];
$obs = $_POST['obs'];
$cliente = $_POST['cliente'];
$id = $_POST['id'];


if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET valor_parcela = :valor_parcela, valor = :valor, data = '$data', status = 'Pendente', cliente = '$cliente', obs = :obs, garantia = :garantia, tipo_vencimento = :tipo_vencimento ");
	
}else{
$query = $pdo->prepare("UPDATE $tabela SET valor_parcela = :valor_parcela, valor = :valor, data = '$data', status = 'Pendente', cliente = '$cliente', obs = :obs, garantia = :garantia, tipo_vencimento = :tipo_vencimento where id = '$id'");
}
$query->bindValue(":valor_parcela", "$valor_parcela");
$query->bindValue(":valor", "$valor");
$query->bindValue(":obs", "$obs");
$query->bindValue(":garantia", "$garantia");
$query->bindValue(":tipo_vencimento", $tipo_vencimento);
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
	$mensagem .= 'valor_parcela: *'.$valor_parcela.'* %0A';
	$mensagem .= 'Garantia: *'.$garantia.'* %0A';
	$mensagem .= 'Obs: *'.$obs.'* %0A';
	require('../../../painel/apis/texto.php');
}

 ?>