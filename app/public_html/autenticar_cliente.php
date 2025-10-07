<?php 

@session_set_cookie_params(['httponly' => true]);

@session_start();

@session_regenerate_id(true);

require_once("conexao.php");

$cpf_digitado = @$_POST['cpf']; 
$senha = @$_POST['senha']; 

$cpf_apenas_numeros = preg_replace('/[^0-9]/', '', $cpf_digitado);

if (strlen($cpf_apenas_numeros) == 11) {
    $cpf = substr($cpf_apenas_numeros, 0, 3) . '.' .
           substr($cpf_apenas_numeros, 3, 3) . '.' .
           substr($cpf_apenas_numeros, 6, 3) . '-' .
           substr($cpf_apenas_numeros, 9, 2);
} else {
    $cpf = $cpf_digitado; 
}

$query = $pdo->prepare("SELECT * from clientes where cpf = :cpf order by id desc");
$query->bindValue(":cpf", $cpf); 
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);

if($linhas > 0){
    if(password_verify($senha, $res[0]['senha_crip'])){
      $_SESSION['nome'] = $res[0]['nome'];
      $_SESSION['id'] = $res[0]['id']; 
      $_SESSION['token_IFDSFDSFFSAS'] = 'IODIIIJFDFDSS';
    
      $resposta = ['status' => 'sucesso', 'mensagem' => 'Login bem-sucedido!', 'redirecionar' => 'painel_cliente'];
    } else {
        $resposta = ['status' => 'erro', 'mensagem' => 'Dados de acesso incorretos.'];
    }
} else {
    $resposta = ['status' => 'erro', 'mensagem' => 'Dados de acesso incorretos.'];
}

header('Content-Type: application/json');
echo json_encode($resposta);
exit();
?>