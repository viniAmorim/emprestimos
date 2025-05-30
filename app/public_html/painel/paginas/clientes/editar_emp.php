<?php 
$tabela = 'emprestimos';
require_once("../../../conexao.php");

$id = $_POST['id'];
$multa = $_POST['multa'];
$juros = $_POST['juros'];

$multa = str_replace('.', '', $multa);
$multa = str_replace(',', '.', $multa);

$juros = str_replace('.', '', $juros);
$juros = str_replace(',', '.', $juros);

$pdo->query("UPDATE $tabela SET multa = '$multa', juros = '$juros' WHERE id = '$id' ");
echo 'Editado com Sucesso';
?>