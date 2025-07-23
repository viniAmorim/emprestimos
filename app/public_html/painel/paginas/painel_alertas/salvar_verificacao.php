<?php
// Inclua seu arquivo de conexão com o banco de dados
require_once('../../../conexao.php'); // Ajuste o caminho conforme sua estrutura de pastas

header('Content-Type: application/json'); // Garante que a resposta seja JSON

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_alerta = $_POST['id'] ?? null;

    // Campos da CNH (apenas os que você vai usar)
    $verificado_nome_cnh = $_POST['verificado_nome_cnh'] ?? null;
    $verificado_foto_cnh = $_POST['verificado_foto_cnh'] ?? null;
    $verificado_validade_cnh = $_POST['verificado_validade_cnh'] ?? null;

    // Campos para Comprovante de Endereço
    $verificado_nome_comp = $_POST['verificado_nome_comp'] ?? null;
    $verificado_endereco_comp = $_POST['verificado_endereco_comp'] ?? null;
    $verificado_cidade_comp = $_POST['verificado_cidade_comp'] ?? null;

    // Validação de dados (REMOVIDOS verificado_endereco_cnh e verificado_cidade_cnh)
    if ($id_alerta === null ||
        $verificado_nome_cnh === null || 
        $verificado_foto_cnh === null || $verificado_validade_cnh === null ||
        $verificado_nome_comp === null || $verificado_endereco_comp === null || $verificado_cidade_comp === null) {
        $response['message'] = 'Dados incompletos para salvar as verificações.';
        echo json_encode($response);
        exit();
    }

    // Garante que os valores sejam 0 ou 1 (inteiros)
    $verificado_nome_cnh = (int)$verificado_nome_cnh;
    $verificado_foto_cnh = (int)$verificado_foto_cnh;
    $verificado_validade_cnh = (int)$verificado_validade_cnh;

    $verificado_nome_comp = (int)$verificado_nome_comp;
    $verificado_endereco_comp = (int)$verificado_endereco_comp;
    $verificado_cidade_comp = (int)$verificado_cidade_comp;

    try {
        // Prepare a query SQL para atualizar os campos específicos, incluindo os novos
        // REMOVIDOS verificado_endereco_cnh e verificado_cidade_cnh da query
        $stmt = $pdo->prepare("UPDATE alertas_duplicidade SET
                                verificado_nome_cnh = :verificado_nome_cnh,
                                verificado_foto_cnh = :verificado_foto_cnh,
                                verificado_validade_cnh = :verificado_validade_cnh,
                                verificado_nome_comp = :verificado_nome_comp,
                                verificado_endereco_comp = :verificado_endereco_comp,
                                verificado_cidade_comp = :verificado_cidade_comp
                                WHERE id = :id_alerta");

        $stmt->bindParam(':verificado_nome_cnh', $verificado_nome_cnh, PDO::PARAM_INT);
        $stmt->bindParam(':verificado_foto_cnh', $verificado_foto_cnh, PDO::PARAM_INT);
        $stmt->bindParam(':verificado_validade_cnh', $verificado_validade_cnh, PDO::PARAM_INT);
        $stmt->bindParam(':verificado_nome_comp', $verificado_nome_comp, PDO::PARAM_INT);
        $stmt->bindParam(':verificado_endereco_comp', $verificado_endereco_comp, PDO::PARAM_INT);
        $stmt->bindParam(':verificado_cidade_comp', $verificado_cidade_comp, PDO::PARAM_INT);
        $stmt->bindParam(':id_alerta', $id_alerta, PDO::PARAM_INT);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = 'Verificações salvas com sucesso!';
            } else {
                $response['message'] = 'Nenhum registro foi atualizado (ID não encontrado ou valores já eram os mesmos).';
            }
        } else {
            $response['message'] = 'Erro ao executar a query de atualização.';
            // Para depuração, você pode adicionar: errorInfo()
            // $response['error_info'] = $stmt->errorInfo();
        }
    } catch (PDOException $e) {
        $response['message'] = 'Erro no banco de dados: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Método de requisição inválido.';
}

echo json_encode($response);
?>