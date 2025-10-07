<?php
// Carrega a conexão com o banco de dados
require_once("conexao.php");

// 1. Inclui o autoloader do Composer para carregar a biblioteca SendGrid
// Certifique-se de que o caminho para o autoloader está correto!
require 'vendor/autoload.php';

// Importa as classes do SendGrid
use SendGrid\Mail\Mail;

$email = filter_var(@$_POST['email'], @FILTER_SANITIZE_STRING);

// 2. Variável de Configuração do SendGrid (Adicione ao seu arquivo de conexão ou configuração)
// ** ATENÇÃO: SUBSTITUA PELA SUA CHAVE DE API REAL DO SENDGRID **
$sendgrid_api_key = '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'; 


$query = $pdo->prepare("SELECT * from usuarios where email = :email");
$query->bindValue(":email", "$email");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if($total_reg > 0){ 
    $telefone = $res[0]['telefone'];
    
    $token_usuario = hash('sha256',time());
    
    $q = $pdo->prepare("UPDATE usuarios SET token=? WHERE email=?");
    $q->execute([$token_usuario,$email]);
    
    // O link de recuperação permanece o mesmo
    $reset_link = $url_sistema.'app/resetar-senha.php'.'?email='.$email.'&token='.$token_usuario;

    
    // ------------------------------------------
    // 3. LÓGICA DE ENVIO DO EMAIL COM SENDGRID
    // ------------------------------------------
    
    // Configura o e-mail
    $email_sg = new Mail();
    
    // Remetente: Usamos o email_sistema como o endereço de envio
    $email_sg->setFrom($email_sistema, $nome_sistema);
    
    // Destinatário
    $email_sg->addTo($email, $nome_sistema); 
    
    // Assunto (Não precisamos mais do mb_convert_encoding, o SendGrid lida com UTF-8)
    $assunto = $nome_sistema . ' - Recuperação de Senha';
    $email_sg->setSubject($assunto);
    
    // Corpo da Mensagem (Recomenda-se enviar em HTML e Texto Simples)
    $mensagem_texto = 'Clique no Link abaixo para atualizar sua senha: ' . $reset_link;
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
    
    $email_sg->addContent("text/plain", $mensagem_texto);
    $email_sg->addContent("text/html", $mensagem_html);

    // Tenta enviar usando o SendGrid
    try {
        $sendgrid = new \SendGrid($sendgrid_api_key);
        $response = $sendgrid->send($email_sg);
        
        // O SendGrid retorna códigos HTTP. 200 ou 202 indica sucesso.
        // if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
        //    // E-mail enviado com sucesso!
        // }
        
    } catch (Exception $e) {
        // Se houver erro de API, registra ou trata.
        // file_put_contents('sendgrid_error.log', 'Erro SendGrid: ' . $e->getMessage() . "\n", FILE_APPEND);
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
        
        // Alterei a variável $mensagem para $mensagem_wa para evitar conflito com o SendGrid
        $mensagem = $mensagem_wa; // Se o arquivo 'texto.php' usar a variável $mensagem
        require('painel/apis/texto.php'); 
        
    }

    echo 'Recuperado com Sucesso';
}else{
    echo 'Esse email não está Cadastrado!';
}

?>