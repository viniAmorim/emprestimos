<?php 
$tabela = 'clientes';
require_once("../../../conexao.php");

$id = $_POST['id'];

$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
$comprovante_rg = $res[0]['comprovante_rg'];
$comprovante_endereco = $res[0]['comprovante_endereco'];
$foto = $res[0]['foto'];

if($comprovante_rg != "sem-foto.png"){
	@unlink('../../images/comprovantes/'.$comprovante_rg);
}

if($comprovante_endereco != "sem-foto.png"){
	@unlink('../../images/comprovantes/'.$comprovante_endereco);
}

if($foto != "sem-foto.jpg"){
	@unlink('../../images/clientes/'.$foto);
}

$pdo->query("DELETE FROM $tabela WHERE id = '$id' ");
echo 'Excluído com Sucesso';
?>