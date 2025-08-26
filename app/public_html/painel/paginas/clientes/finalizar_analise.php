<?php
// Inclui a conexão com o banco de dados.
require_once("../../../conexao.php");

// Verifica se a requisição foi feita via POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Coleta e valida os dados do formulário.
    $id_cliente = $_POST['id_cliente'] ?? null;
    $status_final = $_POST['status_final'] ?? null;

    // Garante que o ID do cliente e o status foram fornecidos.
    if (!$id_cliente || !$status_final) {
        die("Erro: ID do cliente ou status final não fornecido.");
    }

    // Lógica para coletar a observação correta com base no status.
    $observacoes = null;
    if ($status_final === 'Reprovado') {
        $observacoes = $_POST['observacoes'] ?? null;
    } elseif ($status_final === 'Pendente') {
        $observacoes = $_POST['observacoes_pendente_desc'] ?? null;
    } else {
        $observacoes = $_POST['observacoes_genericas_desc'] ?? null;
    }

    // 2. Prepara e executa a atualização dos dados do cliente.
    // O uso de prepared statements é crucial para a segurança.
    $sql_update_cliente = "UPDATE clientes SET status = :status_final, observacoes = :observacoes WHERE id = :id_cliente";
    $query_update_cliente = $pdo->prepare($sql_update_cliente);

    $query_update_cliente->bindValue(":status_final", $status_final);
    $query_update_cliente->bindValue(":observacoes", $observacoes);
    $query_update_cliente->bindValue(":id_cliente", $id_cliente);
    $query_update_cliente->execute();

    // 3. Processa e atualiza o estado de cada checkbox.
    // Usa uma lista segura (whitelist) para evitar injeção de SQL.
    $campos_validacao = [
        'check_foto_perfil',
        'check_validade_cnh',
        'check_nome_documento',
        'check_nome_whatsapp',
        'check_nome_consulta',
        'check_cpf_confere_documento',
        'check_rg_confere_documento',
        'check_foto_usuario_confere',
        'check_celular_confere',
        'check_titular_aceito',
        'check_cidade_atendemos',
        'check_emissao_prazo',
        'check_endereco_confere_comprovante',
        'check_sobrenome_confere',
        'check_num_com_whatsapp',
        'check_cliente_bom',
        'check_nome',
        'check_data',
        'check_ganhos',
        'check_taxa_aceitacao',
        'check_confere',
        'check_ativo',
        'check_cabecalhos'
    ];
    
    // Inicia a construção da string SQL para os updates das checkboxes.
    $sql_checkboxes = "UPDATE clientes SET ";
    $params = [];
    $updates = [];

    // Itera sobre a lista segura de campos de validação.
    foreach ($campos_validacao as $campo) {
        // Se a checkbox foi marcada no formulário, o valor é 1.
        // Se não, o valor é 0.
        $valor = isset($_POST[$campo]) ? 1 : 0;
        
        $updates[] = "`{$campo}` = :{$campo}";
        $params[":{$campo}"] = $valor;
    }

    // Junta as partes da query e adiciona a cláusula WHERE.
    $sql_checkboxes .= implode(', ', $updates);
    $sql_checkboxes .= " WHERE id = :id_cliente";
    $params[':id_cliente'] = $id_cliente;

    // Prepara e executa a query de atualização das checkboxes.
    $query_checkboxes = $pdo->prepare($sql_checkboxes);
    $query_checkboxes->execute($params);

    // 4. Atualiza o estágio do cliente para refletir o status da análise
    $sql_update_estagio = "UPDATE clientes SET estagio_cliente = :estagio WHERE id = :id_cliente";
    $query_update_estagio = $pdo->prepare($sql_update_estagio);
    $query_update_estagio->bindValue(":estagio", $status_final);
    $query_update_estagio->bindValue(":id_cliente", $id_cliente);
    $query_update_estagio->execute();

    // Exibe o array de parâmetros que será usado na query das checkboxes
    echo "<pre>Parâmetros da Query de Checkboxes:\n";
    var_dump($params);
    echo "</pre>";

    // Exibe a query SQL que está sendo construída
    echo "<pre>Query SQL das Checkboxes:\n";
    var_dump($sql_checkboxes);
    echo "</pre>";

    // Exibe o resultado da execução da query
    echo "<pre>Resultado da Execução:\n";
    var_dump($query_checkboxes->rowCount()); // Isso mostra o número de linhas afetadas
    echo "</pre>";

    // 5. Redireciona o usuário.
    // header("Location: ../../index.php?pagina=clientes&status=sucesso&mensagem=Análise finalizada com sucesso!");
    // exit();

} else {
    // Se a requisição não for POST, redireciona de volta com uma mensagem de erro.
    header("Location: clientes.php?status=erro&mensagem=Método de requisição inválido.");
    exit();
}
?>