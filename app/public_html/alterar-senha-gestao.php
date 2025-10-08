<?php
session_start();

require_once("conexao.php"); 

$nova_senha = @$_POST['senha'];
$re_senha = @$_POST['re_senha'];
$email = @$_POST['email'];
$token = @$_POST['token']; 

if (empty($nova_senha) || $nova_senha != $re_senha) {
    die("As senhas digitadas não coincidem ou estão vazias!");
}

$statement = $pdo->prepare("SELECT * FROM usuarios WHERE email=? AND token=?");
$statement->execute([$email, $token]);
$tot = $statement->rowCount();

if ($tot == 0) {
  die("Falha de segurança: Link de redefinição inválido ou já utilizado.");
}

$senha_criptografada = password_hash($nova_senha, PASSWORD_DEFAULT);

$update = $pdo->prepare("UPDATE usuarios SET senha_crip=?, token=NULL WHERE email=?");

$update->execute([$senha_criptografada, $email]); 

echo "Senha alterada com Sucesso";

unset($_SESSION['temp_reset_email']);
unset($_SESSION['temp_reset_token']);
?>