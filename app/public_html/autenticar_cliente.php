<?php 
@session_set_cookie_params(['httponly' => true]);
@session_start();
@session_regenerate_id(true);
require_once("conexao.php");

$cpf = filter_var(@$_POST['cpf'], @FILTER_SANITIZE_STRING);
$senha = filter_var(@$_POST['senha'], @FILTER_SANITIZE_STRING);

$query = $pdo->prepare("SELECT * from clientes where cpf = :cpf order by id desc");
$query->bindValue(":cpf", "$cpf");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);

if($linhas > 0){
    // Verifica a senha e, se correta, configura a sessão
    if(password_verify($senha, $res[0]['senha_crip'])){
        $_SESSION['nome'] = $res[0]['nome'];
        $_SESSION['id'] = $res[0]['id']; 
        $_SESSION['token_IFDSFDSFFSAS'] = 'IODIIIJFDFDSS';
        
        // Retorna sucesso e a URL de redirecionamento
        $resposta = ['status' => 'sucesso', 'mensagem' => 'Login bem-sucedido!', 'redirecionar' => 'painel_cliente'];
    } else {
        // Retorna erro para senha incorreta
        $resposta = ['status' => 'erro', 'mensagem' => 'Dados de acesso incorretos.'];
    }
} else {
    // Retorna erro para CPF não encontrado
    $resposta = ['status' => 'erro', 'mensagem' => 'Dados de acesso incorretos.'];
}

// Configura o cabeçalho para JSON e retorna a resposta
header('Content-Type: application/json');
echo json_encode($resposta);
exit();
?>