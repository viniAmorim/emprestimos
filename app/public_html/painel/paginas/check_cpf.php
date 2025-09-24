<?php
require_once("../../conexao.php");

header('Content-Type: application/json');

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

$response = ['success' => true, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_array($input) && isset($input['cpf'])) {
    $cpf = $input['cpf'];

    $cpf_limpo = preg_replace('/[^0-9]/', '', $cpf);

    if (empty($cpf_limpo) || strlen($cpf_limpo) != 11) {
        $response = ['success' => false, 'message' => 'CPF inválido.'];
    } else {
        try {
            $query = $pdo->prepare("SELECT id FROM clientes WHERE REPLACE(REPLACE(REPLACE(cpf, '.', ''), '-', ''), ' ', '') = :cpf LIMIT 1");
            $query->bindValue(":cpf", $cpf_limpo);
            $query->execute();

            $result = $query->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $response = ['success' => false, 'message' => 'CPF já cadastrado.'];
            }
        } catch (PDOException $e) {
            $response = ['success' => false, 'message' => 'Erro ao verificar o CPF. Tente novamente mais tarde.'];
        }
    }
} else {
    $response = ['success' => false, 'message' => 'Requisição inválida.'];
}

echo json_encode($response);