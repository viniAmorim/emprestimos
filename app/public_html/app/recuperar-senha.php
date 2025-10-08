<?php
// Arquivo: recuperar-senha-cliente.php

// ----------------------------------------------------------------------
// 1. INÍCIO DO BUFFER DE SAÍDA (CRÍTICO PARA AJAX)
// ----------------------------------------------------------------------
ob_start(); 

// Carrega a conexão com o banco de dados (Assumindo que está um nível acima)
require_once("../conexao.php"); 

// ----------------------------------------------------------------------
// BLOCO DE CONFIGURAÇÃO E DEBUG DE CHAVE API (MANTIDO)
// ----------------------------------------------------------------------
$debug_key_path = "config_sendgrid.php"; 
@require_once($debug_key_path); 

// Variáveis de Configuração do Sistema (Mantenha suas variáveis)
$url_sistema = 'https://app.ucredcredito.com/app/'; 
$nome_sistema = 'Ucred';
$email_sistema = 'noreply@ucredcredito.com';
$token = 'seu_token_aqui_para_wa'; 
$instancia = 'sua_instancia_aqui_para_wa'; 

// Verifica a chave do SendGrid antes de continuar
if (empty($sendgrid_api_key)) {
$resposta_final = "Erro no servidor. Chave de Email ausente.";
ob_get_clean();
echo $resposta_final;
exit();
}

// ----------------------------------------------------------------------
// 2. RECEBE E PREPARA A VARIÁVEL DE BUSCA
// ----------------------------------------------------------------------

// Sanitiza a entrada
$usuario = filter_var(trim(@$_POST['usuario']), FILTER_SANITIZE_FULL_SPECIAL_CHARS); 

// Limpa o CPF/CNPJ para a busca no banco (mantém apenas dígitos)
$usuario_limpo = preg_replace('/[^0-9]/', '', $usuario); 

// ----------------------------------------------------------------------
// 3. BUSCA NO BANCO DE DADOS - LÓGICA REFORÇADA E CORRIGIDA
// ----------------------------------------------------------------------

// Buscamos o cliente se o email, o cpf com máscara OU o cpf sem máscara corresponderem
$query = $pdo->prepare("SELECT * from usuarios 
 WHERE email = :usuario_completo 
 OR cpf = :usuario_completo 
 OR TRIM(REPLACE(REPLACE(REPLACE(cpf, '.', ''), '-', ''), '/', '')) = :cpf_limpo"); 

// Binda os valores
$query->bindValue(":usuario_completo", $usuario); // Tenta buscar E-mail OU CPF com máscara (que está no banco)
$query->bindValue(":cpf_limpo", $usuario_limpo); // Tenta buscar pelo CPF/CNPJ SÓ com números

$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

// ----------------------------------------------------------------------
// 4. PROCESSA A RECUPERAÇÃO E EXECUTA ENVIOS
// ----------------------------------------------------------------------

if($total_reg > 0){ 
// Captura os dados do cliente
$email_destino = $res[0]['email']; 
$telefone = $res[0]['telefone'];

// Gera e salva o token
$token_usuario = bin2hex(random_bytes(32)); 
$q = $pdo->prepare("UPDATE usuarios SET token=? WHERE email=?");
$q->execute([$token_usuario, $email_destino]); 

$reset_link = $url_sistema.'resetar-senha.php'.'?email='.urlencode($email_destino).'&token='.$token_usuario;


// ------------------------------------------
// LÓGICA DE ENVIO DO EMAIL COM cURL (SendGrid)
// ------------------------------------------
if (!empty($sendgrid_api_key)) { 

$assunto = $nome_sistema . ' - Recuperação de Senha';

// Corpo da Mensagem em HTML 
$mensagem_html = "
<html>
<body>
<p>Olá,</p>
<p>Recebemos uma solicitação de recuperação de senha para sua conta.</p>
<p>Clique no link abaixo para criar uma nova senha:</p>
<p><a href=\"{$reset_link}\" style=\"display: inline-block; padding: 10px 20px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">
Redefinir Senha
</a></p>
<p>Se você não solicitou a redefinição, ignore este e-mail.</p>
<p>Atenciosamente, <br>{$nome_sistema}</p>
</body>
</html>
";

// Prepara e envia o payload JSON (código cURL omitido)
$payload = json_encode([
'personalizations' => [
[
'to' => [
['email' => $email_destino]
],
'subject' => $assunto
]
],
'from' => [
'email' => $email_sistema,
'name' => $nome_sistema
],
'content' => [
[
'type' => 'text/plain',
'value' => 'Clique no Link abaixo para atualizar sua senha: ' . $reset_link
],
[
'type' => 'text/html',
'value' => $mensagem_html
]
]
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/v3/mail/send');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
'Authorization: Bearer ' . $sendgrid_api_key, 
'Content-Type: application/json'
]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($http_code < 200 || $http_code >= 300) {
error_log("Erro SendGrid cURL (HTTP: $http_code): " . ($error ?: $response));
}
} 

// ------------------------------------------
// LÓGICA DE ENVIO DO WHATSAPP (MANTIDO)
// ------------------------------------------
// if($token != "" and $instancia != ""){
// $telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
// $mensagem_wa = '*'.$nome_sistema.'*%0A%0A';
// $mensagem_wa .= '🤩 _Link para Recuperação de Senha_ %0A%0A';
// $mensagem_wa .= $reset_link; 

// $mensagem = $mensagem_wa; 
// @require('painel/apis/texto.php'); 
// }

// Define a resposta de SUCESSO
$resposta_final = 'Recuperado com Sucesso';
}else{
// Define a resposta de ERRO
$resposta_final = 'O usuário informado não foi encontrado.';
}

// ----------------------------------------------------------------------
// 5. ENCERRAMENTO DO BUFFER E RESPOSTA AJAX
// ----------------------------------------------------------------------
ob_get_clean(); 
echo $resposta_final;
?>