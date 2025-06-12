<?php
require_once("../../../conexao.php");

$id = $_POST['id'];

$pdo->query("UPDATE clientes SET validado = 1 WHERE id = '$id'");

echo 'Validado com sucesso!';
