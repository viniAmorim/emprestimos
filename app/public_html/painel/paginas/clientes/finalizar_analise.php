<?php
require_once("../../../conexao.php");

function processUpload($file_input_name, $db_column_name, &$db_field_variable, $target_dir, $prefix, $allowed_extensions, $quality = 20) {
    global $pdo, $id_cliente;

    if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['name'] != "") {
        $sql_old_file = "SELECT {$db_column_name} FROM clientes WHERE id = :id_cliente";
        $query_old_file = $pdo->prepare($sql_old_file);
        $query_old_file->bindValue(":id_cliente", $id_cliente);
        $query_old_file->execute();
        $old_file_name = $query_old_file->fetchColumn();

        $nome_img = date('d-m-Y-H-i-s') . '-' . $prefix . '-' . preg_replace('/[^a-zA-Z0-9.\-]+/', '-', $_FILES[$file_input_name]['name']);
        $caminho = $target_dir . $nome_img;
        $imagem_temp = $_FILES[$file_input_name]['tmp_name'];
        $ext = strtolower(pathinfo($nome_img, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed_extensions)) {
            if (!empty($old_file_name) && $old_file_name != "sem-foto.png" && $old_file_name != "sem-foto.jpg" && file_exists($target_dir . $old_file_name)) {
                @unlink($target_dir . $old_file_name);
            }
            $db_field_variable = $nome_img; 

            // Tipos de arquivo que não são imagens
            $document_extensions = ['pdf', 'rar', 'zip', 'doc', 'docx', 'xlsx', 'xlsm', 'xls', 'xml'];

            if (in_array($ext, $document_extensions)) {
                move_uploaded_file($imagem_temp, $caminho);
            } else { // É uma imagem, tenta otimizar
                if (!function_exists('gd_info')) {
                    error_log("GD Library não está instalada. Arquivo movido sem otimização: " . $caminho);
                    move_uploaded_file($imagem_temp, $caminho);
                    return;
                }

                list($largura, $altura) = getimagesize($imagem_temp);
                if ($largura === false) {
                    error_log("Não foi possível obter dimensões da imagem: " . $imagem_temp);
                    move_uploaded_file($imagem_temp, $caminho);
                    return;
                }

                if ($largura > 1400) {
                    $nova_largura = 1400;
                    $nova_altura = (int)floor(($altura / $largura) * $nova_largura);
                    $image = imagecreatetruecolor($nova_largura, $nova_altura);

                    $imagem_original = null;
                    switch ($ext) {
                        case 'png': $imagem_original = imagecreatefrompng($imagem_temp); break;
                        case 'jpeg':
                        case 'jpg': $imagem_original = imagecreatefromjpeg($imagem_temp); break;
                        case 'gif': $imagem_original = imagecreatefromgif($imagem_temp); break;
                        case 'webp': $imagem_original = imagecreatefromwebp($imagem_temp); break;
                        default:
                            error_log("Extensão de imagem não suportada pela GD em finalizar_analise.php: " . $ext);
                            move_uploaded_file($imagem_temp, $caminho);
                            return;
                    }

                    if ($imagem_original) {
                        imagecopyresampled($image, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);
                        
                        switch ($ext) {
                            case 'png': imagepng($image, $caminho, 9); break;
                            case 'jpeg':
                            case 'jpg': imagejpeg($image, $caminho, $quality); break;
                            case 'gif': imagegif($image, $caminho); break;
                            case 'webp': imagewebp($image, $caminho, $quality); break;
                        }
                        imagedestroy($imagem_original);
                        imagedestroy($image);
                    } else {
                        error_log("Falha ao criar imagem original a partir do temporário: " . $imagem_temp . " Extensão: " . $ext);
                        move_uploaded_file($imagem_temp, $caminho); 
                    }
                } else {
                    move_uploaded_file($imagem_temp, $caminho);
                }
            }
        } else {
            die("Extensão de arquivo para '{$prefix}' não permitida! (Permitidas: " . implode(', ', $allowed_extensions) . ')');
        }
    } else {
        $db_field_variable = $db_field_variable ?? 'sem-foto.png';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = $_POST['id_cliente'] ?? null;
    $status_final = $_POST['status_final'] ?? null;

    if (!$id_cliente || !$status_final) {
        die("Erro: ID do cliente ou status final não fornecido.");
    }

    $observacoes = null;
    if ($status_final === 'Reprovado') {
        $observacoes = $_POST['observacoes'] ?? null;
    } elseif ($status_final === 'Pendente') {
        $observacoes = $_POST['observacoes_pendente_desc'] ?? null;
    } else {
        $observacoes = $_POST['observacoes_genericas_desc'] ?? null;
    }

    $nome = $_POST['nome'] ?? null;
    $email = $_POST['email'] ?? null;
    $telefone = $_POST['telefone'] ?? null;
    $cpf = $_POST['cpf'] ?? null;
    $rg = $_POST['rg'] ?? null;
    $data_nasc = $_POST['data_nasc'] ?? null;
    $pix = $_POST['pix'] ?? null;
    $endereco = $_POST['endereco'] ?? null;
    $numero = $_POST['numero'] ?? null;
    $quadra = $_POST['quadra'] ?? null;
    $lote = $_POST['lote'] ?? null;
    $bairro = $_POST['bairro'] ?? null;
    $cidade = $_POST['cidade'] ?? null;
    $estado = $_POST['estado'] ?? null;
    $cep = $_POST['cep'] ?? null;
    $complemento = $_POST['complemento'] ?? null;
    $referencia_nome = $_POST['referencia_nome'] ?? null;
    $referencia_contato = $_POST['referencia_contato'] ?? null;
    $referencia_parentesco = $_POST['referencia_parentesco'] ?? null;
    $indicacao = $_POST['indicacao'] ?? null;
    $indicacao_contato = $_POST['indicacao_contato'] ?? null;
    $modelo_veiculo = $_POST['modelo_veiculo'] ?? null;
    $placa_veiculo = $_POST['placa_veiculo'] ?? null;
    $status_veiculo = $_POST['status_veiculo'] ?? null;
    $valor_aluguel = $_POST['valor_aluguel'] ?? null;
    $frequencia_aluguel = $_POST['frequencia_aluguel'] ?? null;
    $funcao_autonomo = $_POST['funcao_autonomo'] ?? null;
    $empresa_autonomo = $_POST['empresa_autonomo'] ?? null;
    $funcao_assalariado = $_POST['funcao_assalariado'] ?? null;
    $empresa_assalariado = $_POST['empresa_assalariado'] ?? null;
    $valor_desejado = $_POST['valor_desejado'] ?? null;
    $observacoes = trim($_POST['observacoes_reprovacao'] ?? '');

    // Obtém os nomes dos arquivos atuais para não sobrescrever se não houver novo upload
    $sql_get_files = "SELECT foto, comprovante_rg, comprovante_endereco, print_ganhos_hoje, extrato_90dias, contracheque FROM clientes WHERE id = :id";
    $query_get_files = $pdo->prepare($sql_get_files);
    $query_get_files->bindValue(":id", $id_cliente);
    $query_get_files->execute();
    $arquivos_atuais = $query_get_files->fetch(PDO::FETCH_ASSOC);

    // Variáveis para armazenar os novos nomes dos arquivos
    $foto = $arquivos_atuais['foto'];
    $comprovante_rg = $arquivos_atuais['comprovante_rg'];
    $comprovante_endereco = $arquivos_atuais['comprovante_endereco'];
    $print_ganhos_hoje = $arquivos_atuais['print_ganhos_hoje'];
    $extrato_90dias = $arquivos_atuais['extrato_90dias'];
    $contracheque = $arquivos_atuais['contracheque'];

    // Define diretórios e extensões permitidas
    $diretorio_clientes = $_SERVER['DOCUMENT_ROOT'] . '/painel/images/clientes/';
    $diretorio_comprovantes = $_SERVER['DOCUMENT_ROOT'] . '/painel/images/comprovantes/';
    $extensoes_comprovantes = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'rar', 'zip', 'doc', 'docx', 'xlsx', 'xlsm', 'xls', 'xml', 'webp'];
    $extensoes_imagens_apenas = ['png', 'jpg', 'jpeg', 'gif', 'webp'];

    // PROCESSA OS UPLOADS USANDO A FUNÇÃO CORRIGIDA
    processUpload('novo_comprovante_rg', 'comprovante_rg', $comprovante_rg, $diretorio_comprovantes, 'rg', $extensoes_comprovantes);
    processUpload('foto_usuario', 'foto', $foto, $diretorio_clientes, 'foto-perfil', $extensoes_imagens_apenas);
    processUpload('novo_comprovante_endereco', 'comprovante_endereco', $comprovante_endereco, $diretorio_comprovantes, 'endereco', $extensoes_comprovantes);
    processUpload('print_ganhos_hoje', 'print_ganhos_hoje', $print_ganhos_hoje, $diretorio_comprovantes, 'ganhos', $extensoes_comprovantes);
    processUpload('extrato_90dias', 'extrato_90dias', $extrato_90dias, $diretorio_comprovantes, 'extrato', $extensoes_comprovantes);
    processUpload('contracheque', 'contracheque', $contracheque, $diretorio_comprovantes, 'contracheque', $extensoes_comprovantes);

    // 2. Constrói a query de atualização com todos os campos editáveis.
    $sql_update_cliente = "UPDATE clientes SET 
    status = :status_final, 
    observacoes_reprovacao = :observacoes_reprovacao, 
    observacoes = :observacoes, 
    estagio_cliente = :estagio_cliente,
    nome = :nome, 
    email = :email, 
    telefone = :telefone,
    cpf = :cpf,
    rg = :rg,
    data_nasc = :data_nasc,
    pix = :pix,
    endereco = :endereco,
    numero = :numero,
    quadra = :quadra,
    lote = :lote,
    bairro = :bairro,
    cidade = :cidade,
    estado = :estado,
    cep = :cep,
    complemento = :complemento,
    referencia_nome = :referencia_nome,
    referencia_contato = :referencia_contato,
    referencia_parentesco = :referencia_parentesco,
    indicacao = :indicacao,
    indicacao_contato = :indicacao_contato,
    modelo_veiculo = :modelo_veiculo,
    placa_veiculo = :placa_veiculo,
    status_veiculo = :status_veiculo,
    valor_aluguel = :valor_aluguel,
    frequencia_aluguel = :frequencia_aluguel,
    funcao_autonomo = :funcao_autonomo,
    empresa_autonomo = :empresa_autonomo,
    funcao_assalariado = :funcao_assalariado,
    empresa_assalariado = :empresa_assalariado,
    valor_desejado = :valor_desejado,
    foto = :foto,
    comprovante_rg = :comprovante_rg,
    comprovante_endereco = :comprovante_endereco,
    print_ganhos_hoje = :print_ganhos_hoje,
    extrato_90dias = :extrato_90dias,
    contracheque = :contracheque";

    // 3. Processa e atualiza o estado de cada checkbox.
    $campos_validacao = [
        'check_validade_cnh', 'check_nome_documento', 'check_nome_whatsapp', 'check_nome_consulta',
        'check_cpf_confere_documento', 'check_rg_confere_documento', 'check_foto_usuario_confere',
        'check_celular_confere', 'check_titular_aceito', 'check_cidade_atendemos',
        'check_emissao_prazo', 'check_endereco_confere_comprovante', 'check_sobrenome_confere',
        'check_num_com_whatsapp', 'check_cliente_bom', 'check_nome', 'check_data',
        'check_ganhos', 'check_taxa_aceitacao', 'check_confere', 'check_ativo', 'check_cabecalhos'
    ];
    
    foreach ($campos_validacao as $campo) {
        $sql_update_cliente .= ", `{$campo}` = :{$campo}";
    }
    
    // Finaliza a query com a cláusula WHERE
    $sql_update_cliente .= " WHERE id = :id_cliente";

    // 4. Prepara e executa a query principal.
    $query_update_cliente = $pdo->prepare($sql_update_cliente);

    $query_update_cliente->bindValue(":status_final", $status_final);
    $query_update_cliente->bindValue(":observacoes_reprovacao", $observacoes);
    $query_update_cliente->bindValue(":observacoes", $observacoes);
    $query_update_cliente->bindValue(":estagio_cliente", $status_final);
    $query_update_cliente->bindValue(":nome", $nome);
    $query_update_cliente->bindValue(":email", $email);
    $query_update_cliente->bindValue(":telefone", $telefone);
    $query_update_cliente->bindValue(":cpf", $cpf);
    $query_update_cliente->bindValue(":rg", $rg);
    $query_update_cliente->bindValue(":data_nasc", $data_nasc);
    $query_update_cliente->bindValue(":pix", $pix);
    $query_update_cliente->bindValue(":endereco", $endereco);
    $query_update_cliente->bindValue(":numero", $numero);
    $query_update_cliente->bindValue(":quadra", $quadra);
    $query_update_cliente->bindValue(":lote", $lote);
    $query_update_cliente->bindValue(":bairro", $bairro);
    $query_update_cliente->bindValue(":cidade", $cidade);
    $query_update_cliente->bindValue(":estado", $estado);
    $query_update_cliente->bindValue(":cep", $cep);
    $query_update_cliente->bindValue(":complemento", $complemento);
    $query_update_cliente->bindValue(":referencia_nome", $referencia_nome);
    $query_update_cliente->bindValue(":referencia_contato", $referencia_contato);
    $query_update_cliente->bindValue(":referencia_parentesco", $referencia_parentesco);
    $query_update_cliente->bindValue(":indicacao", $indicacao);
    $query_update_cliente->bindValue(":indicacao_contato", $indicacao_contato);
    $query_update_cliente->bindValue(":modelo_veiculo", $modelo_veiculo);
    $query_update_cliente->bindValue(":placa_veiculo", $placa_veiculo);
    $query_update_cliente->bindValue(":status_veiculo", $status_veiculo);
    $query_update_cliente->bindValue(":valor_aluguel", $valor_aluguel);
    $query_update_cliente->bindValue(":frequencia_aluguel", $frequencia_aluguel);
    $query_update_cliente->bindValue(":funcao_autonomo", $funcao_autonomo);
    $query_update_cliente->bindValue(":empresa_autonomo", $empresa_autonomo);
    $query_update_cliente->bindValue(":funcao_assalariado", $funcao_assalariado);
    $query_update_cliente->bindValue(":empresa_assalariado", $empresa_assalariado);
    $query_update_cliente->bindValue(":valor_desejado", $valor_desejado);
    $query_update_cliente->bindValue(":foto", $foto);
    $query_update_cliente->bindValue(":comprovante_rg", $comprovante_rg);
    $query_update_cliente->bindValue(":comprovante_endereco", $comprovante_endereco);
    $query_update_cliente->bindValue(":print_ganhos_hoje", $print_ganhos_hoje);
    $query_update_cliente->bindValue(":extrato_90dias", $extrato_90dias);
    $query_update_cliente->bindValue(":contracheque", $contracheque);
    
    foreach ($campos_validacao as $campo) {
        $valor = isset($_POST[$campo]) ? 1 : 0;
        $query_update_cliente->bindValue(":{$campo}", $valor);
    }
    
    $query_update_cliente->bindValue(":id_cliente", $id_cliente);
    $query_update_cliente->execute();

    header("Location: ../../index.php?pagina=clientes&status=sucesso&mensagem=Análise finalizada com sucesso!");
    exit();

} else {
    header("Location: clientes.php?status=erro&mensagem=Método de requisição inválido.");
    exit();
}
?>