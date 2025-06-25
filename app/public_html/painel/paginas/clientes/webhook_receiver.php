<?php
require_once("../../../conexao.php");

$input = file_get_contents('php://input');
$data = json_decode($input, true);


if (!$data) {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados JSON inválidos']);
    exit;
}

$nome = $data['nome'] ?? '';
$telefone = $data['telefone'] ?? '';
$cpf = $data['cpf'] ?? '';
$email = $data['email'] ?? '';
$referencia_nome = $data['referencia_nome'] ?? '';
$referencia_contato = $data['referencia_contato'] ?? '';
$referencia_parentesco = $data['referencia_parentesco'] ?? '';
$comprovante_endereco = $data['comprovante_endereco'] ?? '';
$comprovante_rg = $data['cnh'] ?? ''; 
$foto = $data['foto_perfil'] ?? '';
$modelo_veiculo = $data['modelo_veiculo'] ?? '';
$placa = $data['placa'] ?? '';
$status_veiculo = $data['status_veiculo'] ?? '';
$valor_desejado = $data['ganhos_30dias'] ?? 0;
$valor_parcela_desejada = $data['ganhos_semana'] ?? 0;

$data_cad = date('Y-m-d');

$query = $pdo->prepare("INSERT INTO clientes SET 
  nome = :nome,
  telefone = :telefone,
  cpf = :cpf,
  email = :email,
  data_cad = :data_cad,
  referencia_nome = :referencia_nome,
  referencia_contato = :referencia_contato,
  referencia_parentesco = :referencia_parentesco,
  comprovante_endereco = :comprovante_endereco,
  comprovante_rg = :comprovante_rg,
  foto = :foto,
  modelo_veiculo = :modelo_veiculo,
  placa = :placa,
  status_veiculo = :status_veiculo,
  valor_desejado = :valor_desejado,
  valor_parcela_desejada = :valor_parcela_desejada
");

$query->execute([
  ':nome' => $nome,
  ':telefone' => $telefone,
  ':cpf' => $cpf,
  ':email' => $email,
  ':data_cad' => $data_cad,
  ':referencia_nome' => $referencia_nome,
  ':referencia_contato' => $referencia_contato,
  ':referencia_parentesco' => $referencia_parentesco,
  ':comprovante_endereco' => $comprovante_endereco,
  ':comprovante_rg' => $comprovante_rg,
  ':foto' => $foto,
  ':modelo_veiculo' => $modelo_veiculo,
  ':placa' => $placa,
  ':status_veiculo' => $status_veiculo,
  ':valor_desejado' => $valor_desejado,
  ':valor_parcela_desejada' => $valor_parcela_desejada,
]);

echo json_encode(['status' => 'sucesso']);
