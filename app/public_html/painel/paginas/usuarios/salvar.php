<?php 
$tabela = 'usuarios';
require_once("../../../conexao.php");

if($modo_teste == 'Sim'){
	echo 'Em modo de teste esse recurso fica desabilitado!';
	exit();
}

$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$nivel = $_POST['nivel'];
$endereco = $_POST['endereco'];
$senha = '123';
$senha_crip = password_hash($senha, PASSWORD_DEFAULT);
$id = $_POST['id'];
$visualizar = $_POST['visualizar'];
$comissao = $_POST['comissao'];
$pagamento = $_POST['pagamento'];

$comissao = str_replace('.', ',', $comissao);
$comissao = str_replace('%', '', $comissao);

if($comissao == ""){
	$comissao = 0;
}


//validacao email
$query = $pdo->query("SELECT * from $tabela where email = '$email'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id != $id_reg){
	echo 'Email já Cadastrado!';
	exit();
}

//validacao telefone
$query = $pdo->query("SELECT * from $tabela where telefone = '$telefone'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id != $id_reg){
	echo 'Telefone já Cadastrado!';
	exit();
}

if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, email = :email, senha = '', senha_crip = '$senha_crip', nivel = '$nivel', ativo = 'Sim', foto = 'sem-foto.jpg', telefone = :telefone, data = curDate(), endereco = :endereco, visualizar = :visualizar, comissao = :comissao, pagamento = :pagamento ");
	
}else{
$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, nivel = '$nivel', telefone = :telefone, endereco = :endereco, visualizar = :visualizar, comissao = :comissao, pagamento = :pagamento where id = '$id'");
}
$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":visualizar", "$visualizar");
$query->bindValue(":comissao", "$comissao");
$query->bindValue(":pagamento", "$pagamento");
$query->execute();

echo 'Salvo com Sucesso';
 ?>