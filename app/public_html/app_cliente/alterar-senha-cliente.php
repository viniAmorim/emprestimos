<?php
// Arquivo: alterar-senha-cliente.php (DEVE ESTAR NA MESMA PASTA QUE 'recuperar-senha-cliente.php')

@session_start();
require_once("../conexao.php");

$senha = @$_POST['senha'];
$re_senha = @$_POST['re_senha'];
$email = @$_POST['email'];
$token = @$_POST['token']; // Recebe o token enviado do formulário

// 1. VALIDAÇÃO BÁSICA
if(empty($email) || empty($token)){
    echo 'Erro de segurança: parâmetros ausentes.';
    exit();
}

if($senha != $re_senha){
    echo 'As senhas são diferentes!!';
    exit();
}

if(strlen($senha) < 6){ // Adicionar validação de tamanho mínimo
    echo 'A senha deve ter pelo menos 6 caracteres.';
    exit();
}

// 2. VERIFICAÇÃO DE SEGURANÇA (Email e Token DEVEM CORRESPONDER)
$query = $pdo->prepare("SELECT id FROM clientes WHERE email = :email AND token = :token");
$query->bindValue(":email", $email);
$query->bindValue(":token", $token);
$query->execute();
$total_reg = $query->rowCount();

if($total_reg == 0){
    // Se o token foi alterado/limpo ou o link é inválido, não permite a troca.
    echo 'Erro de segurança: Link de recuperação inválido ou expirado!';
    exit();
}

// 3. ATUALIZAÇÃO E LIMPEZA DO TOKEN
$senha_crip = password_hash($senha, PASSWORD_DEFAULT);

// ATUALIZA: senha_crip, LIMPA: token (torna o link obsoleto)
$query = $pdo->prepare("UPDATE clientes SET senha_crip = :senha_crip, token = NULL WHERE email = :email AND token = :token");

$query->bindValue(":senha_crip", $senha_crip);
$query->bindValue(":email", $email);
$query->bindValue(":token", $token); // Usa o token para garantir que está alterando o cliente certo

if($query->execute()){
    // Limpa a sessão (se ela foi usada para armazenar temporariamente)
    unset($_SESSION['temp_reset_email']); 
    unset($_SESSION['temp_reset_token']); 
    echo 'Senha alterada com Sucesso';
} else {
    echo 'Erro ao atualizar a senha no banco de dados.';
}

// Opcional: Garanta que não há saída extra com exit()
exit();
?>