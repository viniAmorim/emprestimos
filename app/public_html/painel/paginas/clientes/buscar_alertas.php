<?php
header('Content-Type: application/json');

try {
 require_once("../../../conexao.php");

 if (!isset($_POST['id_cliente'])) {
 throw new Exception("ID do cliente não fornecido.");
 }

 $id_cliente_original = $_POST['id_cliente'];

 // 1. Busca todos os alertas para o cliente que está sendo analisado
 $query = $pdo->prepare("SELECT * FROM alertas_duplicidade WHERE id_cliente_cadastrado = :id_cliente");
 $query->bindValue(":id_cliente", $id_cliente_original);
 $query->execute();
 $res = $query->fetchAll(PDO::FETCH_ASSOC);

 $alertas = [];
 foreach ($res as $row) {
 $tipo_alerta = $row['tipo_alerta'] ?? 'Desconhecido';
 $valor_duplicado = $row['valor_duplicado'] ?? 'N/A';
 $data_alerta = $row['data_alerta'] ?? 'N/A';
 
 $nome_cliente_duplicado = "Não encontrado";
 $id_cliente_duplicado = null; // Inicializa o ID do cliente duplicado
 $query_cliente_duplicado = null; // Inicializa a query de busca do cliente

 if ($valor_duplicado !== 'N/A') {
 // 2. Monta a query para buscar o ID e o Nome do cliente duplicado
 // A busca deve ser feita pelo valor duplicado (nome, telefone, cpf, etc.)
 // e deve excluir o cliente que está sendo analisado (id != :id_cliente_original)
 
 // Busca por Nome Duplicado
 if ($tipo_alerta === 'Nome Duplicado') {
 $query_cliente_duplicado = $pdo->prepare("SELECT id, nome FROM clientes WHERE nome = :valor AND id != :id_cliente_original LIMIT 1");
 
 // Busca por Telefone Duplicado
 } else if ($tipo_alerta === 'Telefone Duplicado') {
 $query_cliente_duplicado = $pdo->prepare("SELECT id, nome FROM clientes WHERE telefone = :valor AND id != :id_cliente_original LIMIT 1");

 // Busca por CPF Duplicado (Adicionado para cobrir casos comuns)
 } else if ($tipo_alerta === 'CPF Duplicado') {
 $query_cliente_duplicado = $pdo->prepare("SELECT id, nome FROM clientes WHERE cpf = :valor AND id != :id_cliente_original LIMIT 1");

 // Busca por Email Duplicado (Adicionado para cobrir casos comuns)
 } else if ($tipo_alerta === 'Email Duplicado') {
 $query_cliente_duplicado = $pdo->prepare("SELECT id, nome FROM clientes WHERE email = :valor AND id != :id_cliente_original LIMIT 1");

 }
            
 // 3. Executa a query se ela foi montada
 if ($query_cliente_duplicado) {
 $query_cliente_duplicado->bindValue(":valor", $valor_duplicado);
 $query_cliente_duplicado->bindValue(":id_cliente_original", $id_cliente_original);
 $query_cliente_duplicado->execute();
 $res_cliente_duplicado = $query_cliente_duplicado->fetch(PDO::FETCH_ASSOC);

 if ($res_cliente_duplicado) {
 $nome_cliente_duplicado = $res_cliente_duplicado['nome'];
 $id_cliente_duplicado = $res_cliente_duplicado['id']; // Captura o ID
 }
 }
 }

 $alertas[] = [
 'id' => $row['id'] ?? null, // ID do alerta para a função 'Ignorar'
 'tipo_alerta' => $tipo_alerta,
 'valor_duplicado' => $valor_duplicado,
 'data_alerta' => $data_alerta,
 'nome_duplicado' => $nome_cliente_duplicado,
 'id_duplicado' => $id_cliente_duplicado, // NOVO CAMPO RETORNADO
 ];
 }

 $response = [
 'success' => true,
 'alertas' => $alertas,
 ];

 echo json_encode($response);

} catch (PDOException $e) {
 $response = [
 'success' => false,
 'message' => 'Erro no banco de dados: ' . $e->getMessage(),
 ];
 echo json_encode($response);
} catch (Exception $e) {
 $response = [
 'success' => false,
 'message' => 'Ocorreu um erro: ' . $e->getMessage(),
 ];
 echo json_encode($response);
}
?>