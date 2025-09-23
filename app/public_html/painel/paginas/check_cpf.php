<?php
// Inclua o arquivo de conexão. O caminho pode variar.
require_once("../../conexao.php");

// Define o cabeçalho para a resposta JSON
header('Content-Type: application/json');

// Lê o corpo da requisição bruta e decodifica o JSON
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

$response = ['success' => true, 'message' => ''];

// Verifica se a requisição é POST e se a chave 'cpf' existe no array decodificado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_array($input) && isset($input['cpf'])) {
    $cpf = $input['cpf'];

    // Normaliza o CPF recebido para o formato de 11 dígitos
    $cpf_limpo = preg_replace('/[^0-9]/', '', $cpf);

    if (empty($cpf_limpo) || strlen($cpf_limpo) != 11) {
        $response = ['success' => false, 'message' => 'CPF inválido.'];
    } else {
        try {
            // A consulta SQL usa REPLACE para limpar o CPF no banco de dados antes de comparar.
            // Isso garante a verificação, mesmo se o CPF estiver salvo com pontos e traços.
            $query = $pdo->prepare("SELECT id FROM clientes WHERE REPLACE(REPLACE(REPLACE(cpf, '.', ''), '-', ''), ' ', '') = :cpf LIMIT 1");
            $query->bindValue(":cpf", $cpf_limpo);
            $query->execute();

            $result = $query->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Se encontrou um resultado, o CPF já existe
                $response = ['success' => false, 'message' => 'CPF já cadastrado.'];
            }
        } catch (PDOException $e) {
            // Em caso de erro no banco de dados, retorne uma mensagem genérica
            $response = ['success' => false, 'message' => 'Erro ao verificar o CPF. Tente novamente mais tarde.'];
            // Para depuração, você pode registrar o erro:
            // error_log('Erro PDO: ' . $e->getMessage());
        }
    }
} else {
    $response = ['success' => false, 'message' => 'Requisição inválida.'];
}

echo json_encode($response);