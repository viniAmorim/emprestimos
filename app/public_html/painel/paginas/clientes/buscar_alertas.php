<?php
header('Content-Type: application/json');

try {
    require_once("../../../conexao.php");

    if (!isset($_POST['id_cliente'])) {
        throw new Exception("ID do cliente não fornecido.");
    }

    $id_cliente_original = $_POST['id_cliente'];

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

        if ($valor_duplicado !== 'N/A') {
            // Busca o nome do cliente duplicado usando o valor duplicado
            // e certificando-se de que não é o cliente original
            if ($tipo_alerta === 'Nome Duplicado') {
                $query_cliente_duplicado = $pdo->prepare("SELECT nome FROM clientes WHERE nome = :valor AND id != :id_cliente_original LIMIT 1");
                $query_cliente_duplicado->bindValue(":valor", $valor_duplicado);
                $query_cliente_duplicado->bindValue(":id_cliente_original", $id_cliente_original);
            } else if ($tipo_alerta === 'Telefone Duplicado') {
                $query_cliente_duplicado = $pdo->prepare("SELECT nome FROM clientes WHERE telefone = :valor AND id != :id_cliente_original LIMIT 1");
                $query_cliente_duplicado->bindValue(":valor", $valor_duplicado);
                $query_cliente_duplicado->bindValue(":id_cliente_original", $id_cliente_original);
            }

            if (isset($query_cliente_duplicado)) {
                $query_cliente_duplicado->execute();
                $res_cliente_duplicado = $query_cliente_duplicado->fetch(PDO::FETCH_ASSOC);

                if ($res_cliente_duplicado) {
                    $nome_cliente_duplicado = $res_cliente_duplicado['nome'];
                }
            }
        }

        $alertas[] = [
            'tipo_alerta' => $tipo_alerta,
            'valor_duplicado' => $valor_duplicado,
            'data_alerta' => $data_alerta,
            'nome_duplicado' => $nome_cliente_duplicado,
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