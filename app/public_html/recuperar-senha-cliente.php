<?php
// Arquivo: recuperar-senha-cliente.php
// Versão FINAL (Corrigida a busca, a saída AJAX e incluído diagnóstico de e-mail)

// ----------------------------------------------------------------------
// 1. INÍCIO DO BUFFER DE SAÍDA (CRÍTICO PARA AJAX)
// ----------------------------------------------------------------------
ob_start(); 

// Carrega a conexão com o banco de dados
require_once("conexao.php");

// ----------------------------------------------------------------------
// BLOCO DE CONFIGURAÇÃO E DEBUG DE CHAVE API
// ----------------------------------------------------------------------
$debug_key_path = "config_sendgrid.php"; 
@require_once($debug_key_path); 

// Variáveis de Configuração do Sistema (Mantenha suas variáveis)
$url_sistema = 'https://app.ucredcredito.com/'; 
$nome_sistema = 'Ucred';
$email_sistema = 'noreply@ucredcredito.com';
$token = 'seu_token_aqui_para_wa'; 
$instancia = 'sua_instancia_aqui_para_wa'; 

if (empty($sendgrid_api_key)) {
    // Retorno de erro no caso da chave SendGrid estar ausente
    $resposta_final = "Erro no servidor. Tente novamente mais tarde.";
    ob_get_clean();
    echo $resposta_final;
    exit();
}

// ----------------------------------------------------------------------
// 2. RECEBE E PREPARA A VARIÁVEL DE BUSCA
// ----------------------------------------------------------------------

// ATUALIZADO: Usando FILTER_SANITIZE_FULL_SPECIAL_CHARS (substitui o deprecated FILTER_SANITIZE_STRING)
$usuario = filter_var(@$_POST['usuario'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 

// Limpa o CPF/CNPJ para a busca no banco (ex: 78802671079)
$usuario_limpo = preg_replace('/[^0-9]/', '', $usuario); 

// ----------------------------------------------------------------------
// 3. BUSCA NO BANCO DE DADOS - CORRIGIDO PARA MÁSCARA NA COLUNA 'cpf'
// ----------------------------------------------------------------------

// A função REPLACE no SQL garante que o CPF seja encontrado, mesmo com pontos e traços na coluna 'cpf'.
$query = $pdo->prepare("SELECT * from clientes 
                         WHERE email = :email OR 
                               REPLACE(REPLACE(REPLACE(cpf, '.', ''), '-', ''), '/', '') = :cpf_limpo");

$query->bindValue(":email", $usuario); 
$query->bindValue(":cpf_limpo", $usuario_limpo); 

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
    $q = $pdo->prepare("UPDATE clientes SET token=? WHERE email=?");
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

        // Prepara o payload JSON, usando $email_destino
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

        // Configura e executa a requisição cURL
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
        
        // --- BLOCO DE DIAGNÓSTICO TEMPORÁRIO (USANDO DIE) ---
        // Se o código NÃO for 202 (Sucesso), o script morre e mostra o erro do SendGrid
        if ($http_code != 202) { 
            // Limpa o buffer antes de mostrar o erro
            ob_get_clean(); 
            
            // Mantenha o error_log para o caso de o Docker capturá-lo
            error_log("FALHA SENDGRID. HTTP Code: {$http_code}. cURL Error: {$error}. Response: {$response}. Tentativa para: {$email_destino}"); 

            // CRÍTICO: Mata o script e exibe o erro completo para você na tela.
            die("ERRO SENDGRID: " . $response . " (HTTP Code: " . $http_code . ")");
        } 
        // --- FIM DO BLOCO DE DIAGNÓSTICO TEMPORÁRIO ---

        // (Seu log original)
        if ($http_code < 200 || $http_code >= 300) {
            error_log("Erro SendGrid cURL (HTTP: $http_code): " . ($error ?: $response));
        }
    } 
    
    // ------------------------------------------
    // LÓGICA DE ENVIO DO WHATSAPP
    // ------------------------------------------
    if($token != "" and $instancia != ""){
        $telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
        $mensagem_wa = '*'.$nome_sistema.'*%0A%0A';
        $mensagem_wa .= '🤩 _Link para Recuperação de Senha_ %0A%0A';
        $mensagem_wa .= $reset_link; 
        
        $mensagem = $mensagem_wa; 
        require('painel/apis/texto.php'); 
    }

    // Define a resposta de SUCESSO
    $resposta_final = 'Recuperado com Sucesso';
}else{
    // Define a resposta de ERRO (segura)
    $resposta_final = 'O usuário informado não foi encontrado.';
}

// ----------------------------------------------------------------------
// 5. ENCERRAMENTO DO BUFFER E RESPOSTA AJAX
// ----------------------------------------------------------------------

// Limpa todo o buffer de saída acumulado (qualquer echo ou warning dos requires)
ob_get_clean(); 

// Garante que APENAS a string desejada seja enviada ao AJAX
echo $resposta_final;

// O script termina aqui
?>