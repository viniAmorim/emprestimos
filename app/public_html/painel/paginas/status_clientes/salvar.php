<?php 
$tabela = 'status_clientes';
require_once("../../../conexao.php");

$nome = $_POST['nome'];
$id = $_POST['id'];
$cor = $_POST['cor'];

//validacao nome
$query = $pdo->query("SELECT * from $tabela where nome = '$nome'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id != $id_reg){
	echo 'Nome do status já Cadastrado!';
	exit();
}


if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, cor = :cor ");
	
}else{
$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, cor = :cor where id = '$id'");
}
$query->bindValue(":nome", "$nome");
$query->bindValue(":cor", "$cor");
$query->execute();

echo 'Salvo com Sucesso';
 ?>