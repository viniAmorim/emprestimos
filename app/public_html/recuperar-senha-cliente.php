<?php
// Carrega a conexão com o banco de dados
require_once("conexao.php");

// ----------------------------------------------------------------------
// IMPORTAÇÃO DA CHAVE SECRETA:
// O arquivo config_sendgrid.php NÃO DEVE SER VERSIONADO NO GIT (.gitignore)
// ----------------------------------------------------------------------
require_once("config_sendgrid.php"); 
// A variável $sendgrid_api_key está definida e disponível aqui.
// ----------------------------------------------------------------------


// Defina suas variáveis de configuração aqui (ou no config.php)
$url_sistema = 'https://localhost/'; 
$nome_sistema = 'Ucred';
$email_sistema = 'noreply@ucredcredito.com';

// A chave $sendgrid_api_key foi carregada de config_sendgrid.php.

$token = 'seu_token_aqui_para_wa';
$instancia = 'sua_instancia_aqui_para_wa';

$email = filter_var(@$_POST['email'], FILTER_SANITIZE_EMAIL); 

$query = $pdo->prepare("SELECT * from clientes where email = :email");
$query->bindValue(":email", $email); 
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if($total_reg > 0){ 
    $telefone = $res[0]['telefone'];
    
    $token_usuario = bin2hex(random_bytes(32)); 
    $q = $pdo->prepare("UPDATE clientes SET token=? WHERE email=?");
    $q->execute([$token_usuario,$email]);
    
    $reset_link = $url_sistema.'resetar-senha.php'.'?email='.urlencode($email).'&token='.$token_usuario;

    
    // ------------------------------------------
    // LÓGICA DE ENVIO DO EMAIL COM cURL
    // ------------------------------------------
    
    // O envio ocorrerá desde que $sendgrid_api_key tenha sido definida no config_sendgrid.php
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

        // 1. Prepara o payload JSON para a API do SendGrid
        $payload = json_encode([
            'personalizations' => [
                [
                    'to' => [
                        ['email' => $email]
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

        // 2. Configura a requisição cURL
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/v3/mail/send');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $sendgrid_api_key, 
            'Content-Type: application/json'
        ]);

        // 3. Executa a requisição
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // 4. Trata a resposta (200, 201 ou 202 indica sucesso)
        if ($http_code < 200 || $http_code >= 300) {
            error_log("Erro SendGrid cURL (HTTP: $http_code): " . ($error ?: $response));
        }
    } else {
         error_log("Aviso: Chave SENDGRID_API_KEY não foi definida no config_sendgrid.php. Email não enviado.");
    }
    
    // ------------------------------------------
    // FIM DA LÓGICA DE ENVIO DO EMAIL
    // ------------------------------------------

    // disparar para o telefone do cliente a recuperação
    if($token != "" and $instancia != ""){
        $telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
        $mensagem_wa = '*'.$nome_sistema.'*%0A%0A';
        $mensagem_wa .= '🤩 _Link para Recuperação de Senha_ %0A%0A';
        $mensagem_wa .= $reset_link; 
        
        $mensagem = $mensagem_wa; 
        require('painel/apis/texto.php'); 
    }

    echo 'Recuperado com Sucesso';
}else{
    echo 'Esse email não está Cadastrado!';
}

?>