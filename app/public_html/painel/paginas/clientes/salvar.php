<?php
@session_start();
$visualizar_usuario = @$_SESSION['visualizar'];
$id_usuario = @$_SESSION['id'];

$tabela = 'clientes';
require_once("../../../conexao.php"); 

header('Content-Type: application/json');

// ==============================================================================
// 1. COLETA E TRATAMENTO DE DADOS (PARTE 1)
// ==============================================================================

$nome = htmlspecialchars(trim($_POST['nome'] ?? ''));
$email = htmlspecialchars(trim($_POST['email'] ?? ''));
$telefone = trim($_POST['telefone'] ?? '');
$data_nasc = $_POST['data_nasc'] ?? '';
$data_nasc = implode('-', array_reverse(explode('/', $data_nasc))); // Formato SQL
$endereco = htmlspecialchars(trim($_POST['endereco'] ?? ''));
$cpf = trim($_POST['cpf'] ?? '');
$pix = htmlspecialchars(trim($_POST['pix'] ?? ''));
$indicacao = htmlspecialchars(trim($_POST['indicacao'] ?? ''));
$indicacao_contato = trim($_POST['indicacao_contato'] ?? ''); // Alterado para trim, pois é contato
$bairro = htmlspecialchars(trim($_POST['bairro'] ?? ''));
$cidade = htmlspecialchars(trim($_POST['cidade'] ?? ''));
$estado = htmlspecialchars(trim($_POST['estado'] ?? ''));
$cep = trim($_POST['cep'] ?? '');
$id = $_POST['id'] ?? '';
$pessoa = htmlspecialchars(trim($_POST['pessoa'] ?? ''));
$status = htmlspecialchars(trim($_POST['status'] ?? ''));

$rg = htmlspecialchars(trim($_POST['rg'] ?? ''));
$ramo = htmlspecialchars(trim($_POST['ramo'] ?? ''));
$quadra = htmlspecialchars(trim($_POST['quadra'] ?? ''));
$lote = htmlspecialchars(trim($_POST['lote'] ?? ''));
$numero = htmlspecialchars(trim($_POST['numero'] ?? ''));
$complemento = htmlspecialchars(trim($_POST['complemento'] ?? ''));
$referencia_nome = htmlspecialchars(trim($_POST['referencia_nome'] ?? ''));
$referencia_contato = trim($_POST['referencia_contato'] ?? '');
$referencia_parentesco = htmlspecialchars(trim($_POST['referencia_parentesco'] ?? ''));

$placa = htmlspecialchars(trim($_POST['placa_veiculo'] ?? ''));

// Variáveis para campos condicionais - Inicializadas como nulas ou vazias
$modelo_veiculo = null;
$status_veiculo = null;
$valor_aluguel = null;
$frequencia_aluguel = null;

$funcao_autonomo = null;
$empresa_autonomo = null;
$funcao_assalariado = null;
$empresa_assalariado = null;

// Variáveis para nomes de arquivo de imagens/documentos
$print_perfil_app = 'sem-foto.png';
$print_veiculo_app = 'sem-foto.png';
$print_ganhos_hoje = 'sem-foto.png';
$print_ganhos_30dias = 'sem-foto.png';
$extrato_90dias = 'sem-foto.png';
$contracheque = 'sem-foto.png';
$comprovante_endereco = 'sem-foto.png';
$comprovante_rg = 'sem-foto.png';
$foto = 'sem-foto.jpg'; // Foto principal do cliente
$comprovante_extra_autonomo = 'sem-foto.png';
$comprovante_extra_assalariado = 'sem-foto.png';


$nome_sec = htmlspecialchars(trim($_POST['nome_sec'] ?? ''));
$telefone_sec = trim($_POST['telefone_sec'] ?? '');
$endereco_sec = htmlspecialchars(trim($_POST['endereco_sec'] ?? ''));
$grupo = htmlspecialchars(trim($_POST['grupo'] ?? ''));
$cliente_cadastro = $_POST['cliente_cadastro'] ?? '';
$telefone2 = trim($_POST['telefone2'] ?? '');
$status_cliente = htmlspecialchars(trim($_POST['status_cliente'] ?? ''));
$api_pgto = @$_POST['api_pgto'];
$notificar_cadastro = @$_POST['notificar_cadastro'];

$senha = $_POST['senha'] ?? '';
$conf_senha = $_POST['conf_senha'] ?? '';

// Formata valores monetários para SQL
$valor_desejado = isset($_POST['valor_desejado']) ? str_replace(',', '.', str_replace(['R$', '.', ' '], '', $_POST['valor_desejado'])) : 0;

// 1. Validação de Senha
if($senha != $conf_senha){
    echo json_encode(['success' => false, 'message' => 'As senhas não são iguais!']);
    exit();
}
if($id == "" && empty($senha)){
    echo json_encode(['success' => false, 'message' => 'A senha é obrigatória para novos cadastros.']);
    exit();
}
if(!empty($senha)){
    $senha_crip = password_hash($senha, PASSWORD_DEFAULT); 
}

// 2. Validação de CPF (BLOQUEIO de Cadastro/Edição)
if($cpf != ""){
    $query = $pdo->prepare("SELECT id FROM $tabela WHERE cpf = :cpf");
    $query->bindValue(":cpf", $cpf);
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $id_reg = @$res[0]['id'];
    if(@count($res) > 0 && $id != $id_reg){ 
        echo json_encode(['success' => false, 'message' => 'CPF já Cadastrado!']);
        error_log("ALERTA ADMINISTRADOR: Tentativa de cadastro/edição com CPF duplicado: " . $cpf . " para o ID: " . $id);
        exit(); 
    }
}

// ==============================================================================
// 3. LÓGICA DE ALERTA DE DUPLICIDADE (NÃO BLOQUEANTE) - NOVO E APRIMORADO
// ==============================================================================
$alertas_para_inserir = [];

/**
 * Função Auxiliar para Checagem de Duplicidade Cruzada
 */
function checarDuplicidadeCruzada($pdo, $tabela, $id_atual, $valor, $tipo_alerta, $campos_db, &$alertas_para_inserir) {
    if (empty($valor)) {
        return;
    }

    // 1. Constrói a cláusula WHERE dinamicamente
    $where_clauses = [];
    foreach ($campos_db as $campo) {
        $where_clauses[] = "$campo = :valor";
    }
    $where_sql = implode(' OR ', $where_clauses);

    // 2. Prepara e Executa a Consulta
    $query_sql = "SELECT id FROM $tabela WHERE ($where_sql) AND id != :id_atual LIMIT 1";
    $query = $pdo->prepare($query_sql);
    
    $query->bindValue(":valor", $valor);
    $query->bindValue(":id_atual", $id_atual, PDO::PARAM_INT);
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);

    // 3. Registra o Alerta
    if (count($res) > 0) {
        $alertas_para_inserir[] = [
            'tipo' => $tipo_alerta,
            'valor' => $valor,
        ];
        // Log para o servidor, apenas se a checagem não for de CPF (que já loga com 'BLOQUEIO')
        if (!str_contains($tipo_alerta, 'CPF')) {
            error_log("ALERTA ADMINISTRADOR: $tipo_alerta detectado: " . $valor . " (Cliente ID: " . $id_atual . ")");
        }
    }
}


// --- VERIFICAÇÕES DE NOME (nome, referencia_nome, indicacao) ---
checarDuplicidadeCruzada($pdo, $tabela, $id, $nome, 'Nome Cliente Duplicado', ['nome', 'referencia_nome', 'indicacao'], $alertas_para_inserir);
checarDuplicidadeCruzada($pdo, $tabela, $id, $referencia_nome, 'Nome Referência Duplicado', ['referencia_nome', 'nome', 'indicacao'], $alertas_para_inserir);
checarDuplicidadeCruzada($pdo, $tabela, $id, $indicacao, 'Nome Indicador Duplicado', ['indicacao', 'referencia_nome', 'nome'], $alertas_para_inserir);

// --- VERIFICAÇÕES DE EMAIL E PIX (email, pix) ---
checarDuplicidadeCruzada($pdo, $tabela, $id, $email, 'Email Duplicado (Pix)', ['email', 'pix'], $alertas_para_inserir);
checarDuplicidadeCruzada($pdo, $tabela, $id, $pix, 'Chave Pix Duplicada (Email/Contato)', ['pix', 'email', 'telefone', 'referencia_contato', 'indicacao_contato'], $alertas_para_inserir);

// --- VERIFICAÇÕES DE TELEFONE/CONTATO (telefone, referencia_contato, indicacao_contato, pix) ---
// Note que o campo 'pix' pode ser um telefone, por isso está incluído.
checarDuplicidadeCruzada($pdo, $tabela, $id, $telefone, 'Telefone Cliente Duplicado', ['telefone', 'referencia_contato', 'indicacao_contato', 'pix'], $alertas_para_inserir);
checarDuplicidadeCruzada($pdo, $tabela, $id, $referencia_contato, 'Telefone Referência Duplicado', ['referencia_contato', 'indicacao_contato', 'telefone', 'pix'], $alertas_para_inserir);
checarDuplicidadeCruzada($pdo, $tabela, $id, $indicacao_contato, 'Telefone de Indicação Duplicado', ['indicacao_contato', 'referencia_contato', 'telefone', 'pix'], $alertas_para_inserir);

// ==============================================================================
// 4. PREPARAÇÃO E PROCESSAMENTO DE UPLOADS (PARTE 1 CONTINUAÇÃO)
// ==============================================================================

// Busca os nomes de arquivo existentes no banco de dados se for uma edição
if ($id != "") {
    $query = $pdo->prepare("SELECT
        comprovante_endereco, comprovante_rg, foto, senha_crip,
        print_perfil_app, print_veiculo_app, print_ganhos_hoje, print_ganhos_30dias,
        extrato_90dias, contracheque, comprovante_extra_autonomo, comprovante_extra_assalariado
        FROM $tabela where id = :id");
    $query->bindValue(":id", $id);
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    if (@count($res) > 0) {
        $comprovante_endereco = $res[0]['comprovante_endereco'];
        $comprovante_rg = $res[0]['comprovante_rg'];
        $foto = $res[0]['foto'];
        $print_perfil_app = $res[0]['print_perfil_app'];
        $print_veiculo_app = $res[0]['print_veiculo_app'];
        $print_ganhos_hoje = $res[0]['print_ganhos_hoje'];
        $print_ganhos_30dias = $res[0]['print_ganhos_30dias'];
        $extrato_90dias = $res[0]['extrato_90dias'];
        $contracheque = $res[0]['contracheque'];
        $comprovante_extra_autonomo = $res[0]['comprovante_extra_autonomo'];
        $comprovante_extra_assalariado = $res[0]['comprovante_extra_assalariado'];
        
        // Se a senha não foi fornecida no POST (edição), usa a existente para o bind
        if (empty($senha)) {
            $senha_crip = $res[0]['senha_crip'];
        }
    }
}

// --- Diretórios de Upload (Permissões 0755) ---
$base_images_dir = '../../images/';
$comprovantes_dir = $base_images_dir . 'comprovantes/';
$clientes_dir = $base_images_dir . 'clientes/'; 

foreach ([$comprovantes_dir, $clientes_dir] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true); 
    }
}

// Função auxiliar para processar uploads de imagens/documentos
function processUpload($file_input_name, &$db_field_variable, $target_dir, $prefix, $allowed_extensions, $quality = 20) {
    if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['name'] != "") {
        $nome_img = date('d-m-Y-H-i-s') . '-' . $prefix . '-' . preg_replace('/[ :]+/', '-', $_FILES[$file_input_name]['name']);
        $caminho = $target_dir . $nome_img;
        $imagem_temp = $_FILES[$file_input_name]['tmp_name'];
        $ext = strtolower(pathinfo($nome_img, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed_extensions)) {
            // Exclui o arquivo antigo se existir e não for o default
            if ($db_field_variable != "sem-foto.png" && $db_field_variable != "sem-foto.jpg" && file_exists($target_dir . $db_field_variable)) {
                @unlink($target_dir . $db_field_variable);
            }
            $db_field_variable = $nome_img; 

            $document_extensions = ['pdf', 'rar', 'zip', 'doc', 'docx', 'xlsx', 'xlsm', 'xls', 'xml'];

            if (in_array($ext, $document_extensions)) {
                move_uploaded_file($imagem_temp, $caminho);
            } else { // É uma imagem, tenta otimizar
                if (!function_exists('gd_info')) {
                    error_log("GD Library não está instalada ou habilitada. Arquivo movido sem otimização: " . $caminho);
                    move_uploaded_file($imagem_temp, $caminho); 
                    return;
                }

                list($largura, $altura) = @getimagesize($imagem_temp);
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
                        case 'png': $imagem_original = imagecreatefrompng($imagem_temp); imagealphablending($image, false); imagesavealpha($image, true); break;
                        case 'jpeg':
                        case 'jpg': $imagem_original = imagecreatefromjpeg($imagem_temp); break;
                        case 'gif': $imagem_original = imagecreatefromgif($imagem_temp); break;
                        case 'webp': $imagem_original = imagecreatefromwebp($imagem_temp); imagealphablending($image, false); imagesavealpha($image, true); break;
                        default: move_uploaded_file($imagem_temp, $caminho); return;
                    }

                    if ($imagem_original) {
                        imagecopyresampled($image, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);
                        
                        switch ($ext) {
                            case 'png': imagepng($image, $caminho, 9); break;
                            case 'jpeg':
                            case 'jpg': imagejpeg($image, $caminho, $quality); break;
                            case 'gif': imagegif($image, $caminho); break;
                            case 'webp': imagewebp($image, $caminho, $quality); break;
                            default: imagejpeg($image, $caminho, $quality);
                        }

                        imagedestroy($imagem_original);
                        imagedestroy($image);
                    } else {
                        move_uploaded_file($imagem_temp, $caminho); 
                    }
                } else {
                    move_uploaded_file($imagem_temp, $caminho); 
                }
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Extensão de arquivo para ' . $prefix . ' não permitida! (Permitidas: ' . implode(', ', $allowed_extensions) . ')']);
            exit();
        }
    }
}

// --- Definição de Extensões ---
$extensoes_comprovantes = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'rar', 'zip', 'doc', 'docx', 'webp', 'xlsx', 'xlsm', 'xls', 'xml'];
$extensoes_imagens_pdf = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'webp'];

// --- CHAMADAS PARA PROCESSAR TODOS OS UPLOADS ---
processUpload('comprovante_endereco', $comprovante_endereco, $comprovantes_dir, 'endereco', $extensoes_comprovantes);
processUpload('comprovante_rg', $comprovante_rg, $comprovantes_dir, 'rg', $extensoes_comprovantes);
processUpload('print_perfil_app', $print_perfil_app, $comprovantes_dir, 'perfil-app', $extensoes_imagens_pdf);
processUpload('print_veiculo_app', $print_veiculo_app, $comprovantes_dir, 'veiculo-app', $extensoes_imagens_pdf);
processUpload('print_ganhos_hoje', $print_ganhos_hoje, $comprovantes_dir, 'ganhos-hoje', $extensoes_imagens_pdf);
processUpload('print_ganhos_30dias', $print_ganhos_30dias, $comprovantes_dir, 'ganhos-30dias', $extensoes_imagens_pdf);
processUpload('extrato_90dias', $extrato_90dias, $comprovantes_dir, 'extrato-90dias', $extensoes_imagens_pdf);
processUpload('contracheque', $contracheque, $comprovantes_dir, 'contracheque', $extensoes_imagens_pdf);
processUpload('comprovante_extra_autonomo', $comprovante_extra_autonomo, $comprovantes_dir, 'extra-autonomo', $extensoes_comprovantes);
processUpload('comprovante_extra_assalariado', $comprovante_extra_assalariado, $comprovantes_dir, 'extra-assalariado', $extensoes_comprovantes);

// --- NOVA LÓGICA PARA PROCESSAR A FOTO DO USUÁRIO ENVIADA VIA BASE64 (CÂMERA) ---
if (isset($_POST['foto_usuario']) && !empty($_POST['foto_usuario'])) {
    $imageData = $_POST['foto_usuario'];
    if (strpos($imageData, 'data:image/') === 0) {
        list($type, $imageData) = explode(';', $imageData);
        list(, $imageData) = explode(',', $imageData);
        
        $ext_foto = str_replace('data:image/', '', $type); 
        if ($ext_foto == 'jpg') $ext_foto = 'jpeg';
        if (!in_array($ext_foto, ['png', 'jpeg', 'gif', 'webp'])) { 
            echo json_encode(['success' => false, 'message' => 'Formato de imagem de usuário não permitido para foto da câmera!']); 
            exit();
        }

        $decodedImage = base64_decode($imageData);
        if ($decodedImage === false) {
            echo json_encode(['success' => false, 'message' => 'Erro ao decodificar a imagem Base64.']);
            exit();
        }

        $nome_img_foto = date('Y-m-d_H-i-s') . '.' . $ext_foto;
        $caminho_foto = $clientes_dir . $nome_img_foto;

        if ($foto != 'sem-foto.jpg' && file_exists($clientes_dir . $foto)) {
            @unlink($clientes_dir . $foto);
        }
        $foto = $nome_img_foto; 

        $salvo = file_put_contents($caminho_foto, $decodedImage);

        if ($salvo === false) {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar a imagem da câmera no servidor.']);
            exit();
        }

        // Tenta otimizar/redimensionar a imagem
        list($largura, $altura) = @getimagesize($caminho_foto);

        if ($largura !== false && $largura > 1400) {
            $nova_largura = 1400;
            $nova_altura = intval(($altura / $largura) * $nova_largura);
            $image_resampled = imagecreatetruecolor($nova_largura, $nova_altura);

            $imagem_original_from_file = null;
            switch ($ext_foto) {
                case 'png': $imagem_original_from_file = imagecreatefrompng($caminho_foto); imagealphablending($image_resampled, false); imagesavealpha($image_resampled, true); break;
                case 'jpeg': $imagem_original_from_file = imagecreatefromjpeg($caminho_foto); break;
                case 'gif': $imagem_original_from_file = imagecreatefromgif($caminho_foto); break;
                case 'webp': $imagem_original_from_file = imagecreatefromwebp($caminho_foto); imagealphablending($image_resampled, false); imagesavealpha($image_resampled, true); break;
            }

            if ($imagem_original_from_file) {
                imagecopyresampled($image_resampled, $imagem_original_from_file, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);
                
                switch ($ext_foto) {
                    case 'png': imagepng($image_resampled, $caminho_foto, 9); break;
                    case 'jpeg': imagejpeg($image_resampled, $caminho_foto, 80); break;
                    case 'gif': imagegif($image_resampled, $caminho_foto); break;
                    case 'webp': imagewebp($image_resampled, $caminho_foto, 80); break;
                    default: imagejpeg($image_resampled, $caminho_foto, 80);
                }

                imagedestroy($imagem_original_from_file);
                imagedestroy($image_resampled);
            } else {
                error_log("Falha ao criar imagem original a partir do arquivo salvo: " . $caminho_foto);
            }
        }
    } else {
        error_log("ALERTA: foto_usuario enviada, mas não é uma string Base64 válida.");
    }
}

// --- Lógica Condicional para Dados do Ramo ---
if ($ramo === 'uber') {
    $modelo_veiculo = htmlspecialchars(trim(@$_POST['modelo_veiculo']));
    $status_veiculo = htmlspecialchars(trim(@$_POST['status_veiculo']));
    $valor_aluguel = isset($_POST['valor_aluguel']) ? str_replace(',', '.', str_replace(['R$', '.', ' '], '', $_POST['valor_aluguel'])) : 0;
    $frequencia_aluguel = htmlspecialchars(trim(@$_POST['frequencia_aluguel'] ?? ''));
} else if ($ramo === 'autonomo') {
    $funcao_autonomo = htmlspecialchars(trim(@$_POST['funcao_autonomo']));
    $empresa_autonomo = htmlspecialchars(trim(@$_POST['empresa_autonomo']));
} else if ($ramo === 'assalariado') {
    $funcao_assalariado = htmlspecialchars(trim(@$_POST['funcao_assalariado']));
    $empresa_assalariado = htmlspecialchars(trim(@$_POST['empresa_assalariado']));
}

// ==============================================================================
// 5. INSERÇÃO/ATUALIZAÇÃO NO BANCO DE DADOS (PARTE 2)
// ==============================================================================

// Prepara a query de INSERT ou UPDATE
if($id == ""){
    // Query de INSERT para um novo registro
    $query = $pdo->prepare("INSERT INTO $tabela
        SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone,
        data_cad = curDate(), endereco = :endereco, data_nasc = :data_nasc,
        pix = :pix, indicacao = :indicacao, indicacao_contato = :indicacao_contato,
        bairro = :bairro, estado = :estado, cidade = :cidade, cep = :cep,
        pessoa = :pessoa, nome_sec = :nome_sec, telefone_sec = :telefone_sec,
        endereco_sec = :endereco_sec, grupo = :grupo, status = :status,
        comprovante_endereco = :comprovante_endereco, comprovante_rg = :comprovante_rg,
        telefone2 = :telefone2, foto = :foto, status_cliente = :status_cliente,
        senha_crip = :senha_crip, api_pgto = '$api_pgto', rg = :rg, ramo = :ramo, quadra = :quadra,
        lote = :lote, numero = :numero, complemento = :complemento,
        referencia_nome = :referencia_nome, referencia_contato = :referencia_contato,
        referencia_parentesco = :referencia_parentesco, modelo_veiculo = :modelo_veiculo,
        status_veiculo = :status_veiculo, placa = :placa, valor_aluguel = :valor_aluguel,
        frequencia_aluguel = :frequencia_aluguel, print_perfil_app = :print_perfil_app,
        print_veiculo_app = :print_veiculo_app, print_ganhos_hoje = :print_ganhos_hoje,
        print_ganhos_30dias = :print_ganhos_30dias, funcao_autonomo = :funcao_autonomo,
        empresa_autonomo = :empresa_autonomo, extrato_90dias = :extrato_90dias,
        funcao_assalariado = :funcao_assalariado, empresa_assalariado = :empresa_assalariado,
        contracheque = :contracheque, valor_desejado = :valor_desejado,
        comprovante_extra_autonomo = :comprovante_extra_autonomo,
        comprovante_extra_assalariado = :comprovante_extra_assalariado, usuario = '$id_usuario'
    ");
}else{
    // Query de UPDATE para um registro existente
    $query = $pdo->prepare("
        UPDATE $tabela SET
        nome = :nome, email = :email, cpf = :cpf, telefone = :telefone,
        endereco = :endereco, data_nasc = :data_nasc, pix = :pix,
        indicacao = :indicacao, indicacao_contato = :indicacao_contato, bairro = :bairro,
        estado = :estado, cidade = :cidade, cep = :cep, pessoa = :pessoa,
        nome_sec = :nome_sec, telefone_sec = :telefone_sec, endereco_sec = :endereco_sec,
        grupo = :grupo, status = :status, comprovante_endereco = :comprovante_endereco,
        comprovante_rg = :comprovante_rg, telefone2 = :telefone2, foto = :foto,
        status_cliente = :status_cliente, rg = :rg, ramo = :ramo, quadra = :quadra,
        lote = :lote, numero = :numero, complemento = :complemento,
        referencia_nome = :referencia_nome, referencia_contato = :referencia_contato,
        referencia_parentesco = :referencia_parentesco, modelo_veiculo = :modelo_veiculo,
        status_veiculo = :status_veiculo, placa = :placa, valor_aluguel = :valor_aluguel,
        frequencia_aluguel = :frequencia_aluguel, print_perfil_app = :print_perfil_app,
        print_veiculo_app = :print_veiculo_app, print_ganhos_hoje = :print_ganhos_hoje,
        print_ganhos_30dias = :print_ganhos_30dias, funcao_autonomo = :funcao_autonomo,
        empresa_autonomo = :empresa_autonomo, extrato_90dias = :extrato_90dias,
        funcao_assalariado = :funcao_assalariado, empresa_assalariado = :empresa_assalariado,
        contracheque = :contracheque, valor_desejado = :valor_desejado,
        comprovante_extra_autonomo = :comprovante_extra_autonomo,
        comprovante_extra_assalariado = :comprovante_extra_assalariado,  api_pgto = :api_pgto
        where id = :id
    ");
}

// --- Binda os parâmetros para a query (Prepared Statements) ---
$query->bindValue(":nome", $nome);
$query->bindValue(":email", $email);
$query->bindValue(":telefone", $telefone);
$query->bindValue(":endereco", $endereco);
$query->bindValue(":cpf", $cpf);
$query->bindValue(":pix", $pix);
$query->bindValue(":indicacao", $indicacao);
$query->bindValue(":indicacao_contato", $indicacao_contato);
$query->bindValue(":bairro", $bairro);
$query->bindValue(":cidade", $cidade);
$query->bindValue(":estado", $estado);
$query->bindValue(":cep", $cep);
$query->bindValue(":pessoa", $pessoa);
$query->bindValue(":nome_sec", $nome_sec);
$query->bindValue(":telefone_sec", $telefone_sec);
$query->bindValue(":endereco_sec", $endereco_sec);
$query->bindValue(":grupo", $grupo);
$query->bindValue(":status", $status);
$query->bindValue(":telefone2", $telefone2);
$query->bindValue(":rg", $rg);
$query->bindValue(":ramo", $ramo);
$query->bindValue(":quadra", $quadra);
$query->bindValue(":lote", $lote);
$query->bindValue(":numero", $numero);
$query->bindValue(":complemento", $complemento);
$query->bindValue(":referencia_nome", $referencia_nome);
$query->bindValue(":referencia_contato", $referencia_contato);
$query->bindValue(":referencia_parentesco", $referencia_parentesco);
$query->bindValue(":modelo_veiculo", $modelo_veiculo);
$query->bindValue(":status_veiculo", $status_veiculo);
$query->bindValue(":placa", $placa);
$query->bindValue(":frequencia_aluguel", $frequencia_aluguel);
$query->bindValue(":valor_aluguel", $valor_aluguel);
$query->bindValue(":valor_desejado", $valor_desejado);
$query->bindValue(":funcao_autonomo", $funcao_autonomo);
$query->bindValue(":empresa_autonomo", $empresa_autonomo);
$query->bindValue(":extrato_90dias", $extrato_90dias);
$query->bindValue(":funcao_assalariado", $funcao_assalariado);
$query->bindValue(":empresa_assalariado", $empresa_assalariado);
$query->bindValue(":contracheque", $contracheque);
$query->bindValue(":print_perfil_app", $print_perfil_app);
$query->bindValue(":print_veiculo_app", $print_veiculo_app);
$query->bindValue(":print_ganhos_hoje", $print_ganhos_hoje);
$query->bindValue(":print_ganhos_30dias", $print_ganhos_30dias);
$query->bindValue(":comprovante_extra_autonomo", $comprovante_extra_autonomo);
$query->bindValue(":comprovante_extra_assalariado", $comprovante_extra_assalariado);
$query->bindValue(":data_nasc", $data_nasc);
$query->bindValue(":foto", $foto);
$query->bindValue(":comprovante_endereco", $comprovante_endereco);
$query->bindValue(":comprovante_rg", $comprovante_rg);
$query->bindValue(":status_cliente", $status_cliente);

// Lógica para Senha: usa a senha criptografada se houver, ou a existente (já buscada acima)
$query->bindValue(":senha_crip", $senha_crip);




// Se for uma edição, binda o ID
if ($id != "") {
    $query->bindValue(":id", $id);
}


// --- Executa a query com tratamento de erros ---
try {
    $query->execute();

    // Após a execução do INSERT/UPDATE, obtenha o ID do cliente
    $cliente_id_afetado = ($id == "") ? $pdo->lastInsertId() : $id;
    $mensagem_sucesso = 'Salvo com Sucesso!';

    // Insere Alertas de Duplicidade APÓS o cliente ter um ID
    if ($cliente_id_afetado !== null && !empty($alertas_para_inserir)) {
        foreach ($alertas_para_inserir as $alerta) {
            $stmt_alerta = $pdo->prepare("INSERT INTO alertas_duplicidade (tipo_alerta, valor_duplicado, id_cliente_cadastrado, data_alerta) VALUES (:tipo, :valor, :id_cliente, CURDATE())");
            $stmt_alerta->bindValue(":tipo", $alerta['tipo']);
            $stmt_alerta->bindValue(":valor", $alerta['valor']);
            $stmt_alerta->bindValue(":id_cliente", $cliente_id_afetado, PDO::PARAM_INT);
            $stmt_alerta->execute();
        }
        $mensagem_sucesso = 'Salvo com Sucesso!'; // Mantém a mensagem limpa
    }

    echo json_encode(['success' => true, 'message' => $mensagem_sucesso]); // Mensagem de sucesso em JSON

} catch (PDOException $e) {
    // Log do erro completo para o servidor (para depuração)
    error_log("Erro de PDO ao salvar cliente: " . $e->getMessage());

    // Mensagem genérica para o frontend
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar os dados. Por favor, tente novamente mais tarde.' . $e->getMessage()]);
}

// ==============================================================================
// 6. LÓGICA DE NOTIFICAÇÃO (WHATSAPP/SMS)
// ==============================================================================

// Certifique-se de que $telefone_sistema, $token, $instancia, $nome_sistema, $url_sistema
// estejam definidos (ex: em 'conexao.php').

// Envio para o Administrador
$tel_cliente_admin = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_sistema ?? '');
$telefone_envio_admin = $tel_cliente_admin;

if(isset($cliente_cadastro) && $cliente_cadastro == 'Sim' && isset($token) && $token != "" && isset($instancia) && $instancia != "" and $notificar_cadastro == 'Sim' and $id == ""){
    $mensagem_admin = '*'.$nome_sistema.'* %0A';
    $mensagem_admin .= '_Novo Cliente Cadastrado_ %0A';
    $mensagem_admin .= 'Cliente: *'.$nome.'* %0A';
    $mensagem_admin .= 'Telefone: *'.$telefone.'* %0A%0A';
    // require('../../apis/texto.php');
}

// Envio para o Cliente
$tel_cliente_user = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
$telefone_envio_user = $tel_cliente_user;

if(isset($token) && $token != "" && isset($instancia) && $instancia != ""){
    $mensagem_user = '*'.$nome_sistema.'* %0A';
    $mensagem_user .= '_Você foi cadastrado no Sistema_ %0A';
    $mensagem_user .= 'Cliente: *'.$nome.'* %0A';
    $mensagem_user .= '_Acesse seu Painel_ %0A%0A';

    if(isset($cliente_cadastro) && $cliente_cadastro == 'Sim'){
        $sua_senha = ' sua senha de cadastro!';
    }else{
        $sua_senha = ' a senha 123'; // Baseado na lógica do código original, caso a senha padrão seja '123'
    }

    $mensagem_user .= 'Use seu CPF e '.$sua_senha.' %0A';
    $mensagem_user .= $url_sistema.'acesso';

    // require('../../apis/texto.php');
}
?>