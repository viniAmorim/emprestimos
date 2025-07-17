<?php
$tabela = 'clientes';
require_once("../../../conexao.php"); // Inclui o arquivo de conexão com o banco de dados

// Configuração de resposta JSON
header('Content-Type: application/json');

// --- 1. Coleta e sanitiza os dados do POST ---
$nome = htmlspecialchars(trim($_POST['nome'] ?? ''));
$email = htmlspecialchars(trim($_POST['email'] ?? ''));
$telefone = trim($_POST['telefone'] ?? '');
$data_nasc = $_POST['data_nasc'] ?? '';
$data_nasc = implode('-', array_reverse(explode('/', $data_nasc)));
$endereco = htmlspecialchars(trim($_POST['endereco'] ?? ''));
$cpf = trim($_POST['cpf'] ?? '');
$pix = htmlspecialchars(trim($_POST['pix'] ?? ''));
$indicacao = htmlspecialchars(trim($_POST['indicacao'] ?? ''));
$indicacao_contato = htmlspecialchars(trim($_POST['indicacao_contato'] ?? ''));
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

// Novas variáveis para campos condicionais - Inicializadas como nulas ou vazias
$modelo_veiculo = null;
$status_veiculo = null;
$valor_aluguel = null;

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

// NOVO CAMPO: Inicializa a variável para o comprovante extra do autônomo
$comprovante_extra_autonomo = 'sem-foto.png';
$comprovante_extra_assalariado = 'sem-foto.png';


$nome_sec = htmlspecialchars(trim($_POST['nome_sec'] ?? ''));
$telefone_sec = trim($_POST['telefone_sec'] ?? '');
$endereco_sec = htmlspecialchars(trim($_POST['endereco_sec'] ?? ''));
$grupo = htmlspecialchars(trim($_POST['grupo'] ?? ''));
$cliente_cadastro = $_POST['cliente_cadastro'] ?? '';
$telefone2 = trim($_POST['telefone2'] ?? '');
$status_cliente = htmlspecialchars(trim($_POST['status_cliente'] ?? ''));

$senha = $_POST['senha'] ?? '';
$conf_senha = $_POST['conf_senha'] ?? '';

// Formata valores monetários, removendo R$, pontos e substituindo vírgula por ponto
$valor_desejado = isset($_POST['valor_desejado']) ? str_replace(',', '.', str_replace(['R$', '.', ' '], '', $_POST['valor_desejado'])) : 0;
$valor_parcela_desejada = isset($_POST['parcela_desejada']) ? str_replace(',', '.', str_replace(['R$', '.', ' '], '', $_POST['parcela_desejada'])) : 0;

// Validação de senhas para novo cadastro de cliente
if($cliente_cadastro == "Sim"){
    if($senha != $conf_senha){
        echo json_encode(['success' => false, 'message' => 'As senhas não são iguais!']);
        exit();
    }
}else{
    $senha = '123'; // Senha padrão se não for um cadastro de cliente
}
$senha_crip = password_hash($senha, PASSWORD_DEFAULT); // Criptografa a senha

// Validação de CPF duplicado (ainda impede o cadastro)
if($cpf != ""){
    $query = $pdo->prepare("SELECT id FROM $tabela WHERE cpf = :cpf");
    $query->bindValue(":cpf", $cpf);
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $id_reg = @$res[0]['id'];
    if(@count($res) > 0 && $id != $id_reg){ // Se encontrou e não é o próprio registro sendo editado
        echo json_encode(['success' => false, 'message' => 'CPF já Cadastrado!']);
        error_log("ALERTA ADMINISTRADOR: Tentativa de cadastro/edição com CPF duplicado: " . $cpf . " para o ID: " . $id);
        exit(); // Impede o cadastro
    }
}

// Flag para saber se um alerta de nome duplicado foi gerado
$alerta_nome_duplicado = false;

// Validação de Nome Completo duplicado (AGORA PERMITE O CADASTRO, APENAS LOGA NO BANCO E NO ARQUIVO)
if($nome != ""){
    $query = $pdo->prepare("SELECT id FROM $tabela WHERE nome = :nome");
    $query->bindValue(":nome", $nome);
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $id_reg = @$res[0]['id'];
    if(@count($res) > 0 && $id != $id_reg){ // Se encontrou e não é o próprio registro sendo editado
        // Define a flag para inserir o alerta após o cadastro/edição do cliente
        $alerta_nome_duplicado = true;
        // Log para o arquivo de erro do servidor (mantido para logs imediatos)
        error_log("ALERTA ADMINISTRADOR: Nome Completo duplicado detectado, mas cadastro permitido: " . $nome . " para o ID: " . $id);
    }
}

// Validação de Telefone duplicado (ainda impede o cadastro)
if($telefone != ""){
    $query = $pdo->prepare("SELECT id FROM $tabela WHERE telefone = :telefone");
    $query->bindValue(":telefone", $telefone);
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $id_reg = @$res[0]['id'];
    if(@count($res) > 0 && $id != $id_reg){ // Se encontrou e não é o próprio registro sendo editado
        echo json_encode(['success' => false, 'message' => 'Telefone já Cadastrado!']);
        error_log("ALERTA ADMINISTRADOR: Tentativa de cadastro/edição com Telefone duplicado: " . $telefone . " para o ID: " . $id);
        exit(); // Impede o cadastro
    }
}

// --- Inicializa variáveis de imagem e busca existentes para edição ---
// As variáveis $comprovante_endereco, $comprovante_rg, $foto, etc., já foram inicializadas acima.

// Busca os nomes de arquivo existentes no banco de dados se for uma edição
if ($id != "") {
    $query = $pdo->prepare("SELECT
        comprovante_endereco, comprovante_rg, foto,
        print_perfil_app, print_veiculo_app, print_ganhos_hoje, print_ganhos_30dias,
        extrato_90dias, contracheque,
        comprovante_extra_autonomo,
        comprovante_extra_assalariado
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
        // NOVO: Recupera o nome do arquivo existente para o novo campo
        $comprovante_extra_autonomo = $res[0]['comprovante_extra_autonomo'];
        $comprovante_extra_assalariado = $res[0]['comprovante_extra_assalariado'];
    }
}

// --- Diretórios de Upload (agora todos os documentos vão para 'comprovantes/') ---
$base_images_dir = '../../images/';
$comprovantes_dir = $base_images_dir . 'comprovantes/';
$clientes_dir = $base_images_dir . 'clientes/'; // Mantido para a foto principal do cliente

// Cria os diretórios se não existirem (com permissões mais seguras para produção)
foreach ([$comprovantes_dir, $clientes_dir] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true); // Permissões mais seguras: 0755
    }
}

// Função auxiliar para processar uploads de imagens/documentos (SEM MUDANÇAS AQUI)
function processUpload($file_input_name, &$db_field_variable, $target_dir, $prefix, $allowed_extensions, $quality = 20) {
    global $pdo; // Acesso ao objeto PDO para logging (se necessário)

    if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['name'] != "") {
        $nome_img = date('d-m-Y-H-i-s') . '-' . $prefix . '-' . preg_replace('/[ :]+/', '-', $_FILES[$file_input_name]['name']);
        $caminho = $target_dir . $nome_img;
        $imagem_temp = $_FILES[$file_input_name]['tmp_name'];
        $ext = strtolower(pathinfo($nome_img, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed_extensions)) {
            // Exclui o arquivo antigo se existir e não for o default
            if ($db_field_variable != "sem-foto.png" && file_exists($target_dir . $db_field_variable)) {
                @unlink($target_dir . $db_field_variable);
            }
            $db_field_variable = $nome_img; // Atualiza a variável para o novo nome do arquivo

            // Tipos de arquivo que não são imagens e devem ser apenas movidos
            $document_extensions = ['pdf', 'rar', 'zip', 'doc', 'docx', 'xlsx', 'xlsm', 'xls', 'xml'];

            if (in_array($ext, $document_extensions)) {
                move_uploaded_file($imagem_temp, $caminho);
            } else { // É uma imagem, tenta otimizar
                list($largura, $altura) = getimagesize($imagem_temp);
                if ($largura > 1400) {
                    $nova_largura = 1400;
                    $nova_altura = (int)floor(($altura / $largura) * $nova_largura);
                    $image = imagecreatetruecolor($nova_largura, $nova_altura);

                    $imagem_original = null;
                    switch ($ext) {
                        case 'png':
                            $imagem_original = imagecreatefrompng($imagem_temp);
                            imagealphablending($image, false);
                            imagesavealpha($image, true);
                            break;
                        case 'jpeg':
                        case 'jpg':
                            $imagem_original = imagecreatefromjpeg($imagem_temp);
                            break;
                        case 'gif':
                            $imagem_original = imagecreatefromgif($imagem_temp);
                            break;
                        case 'webp':
                            $imagem_original = imagecreatefromwebp($imagem_temp);
                            break;
                        default:
                            // Não deveria chegar aqui se a extensão foi validada
                            move_uploaded_file($imagem_temp, $caminho);
                            return; // Sai da função se o tipo de imagem não for suportado pela GD
                    }

                    if ($imagem_original) {
                        imagecopyresampled($image, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);
                        imagejpeg($image, $caminho, $quality); // Usa a qualidade definida
                        imagedestroy($imagem_original);
                        imagedestroy($image);
                    }
                } else {
                    move_uploaded_file($imagem_temp, $caminho); // Move sem redimensionar se for pequeno
                }
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Extensão de arquivo para ' . $prefix . ' não permitida!']);
            exit();
        }
    }
}

// Extensões de arquivo comuns para comprovantes (podem incluir documentos)
$extensoes_comprovantes = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'rar', 'zip', 'doc', 'docx', 'webp', 'xlsx', 'xlsm', 'xls', 'xml'];
// Extensões para fotos de perfil e prints de app (geralmente só imagens + PDF para prints)
$extensoes_imagens_pdf = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'webp'];
$extensoes_imagens_apenas = ['png', 'jpg', 'jpeg', 'gif', 'webp'];


// --- CHAMADAS PARA PROCESSAR TODOS OS UPLOADS ---
processUpload('comprovante_endereco', $comprovante_endereco, $comprovantes_dir, 'endereco', $extensoes_comprovantes);
processUpload('comprovante_rg', $comprovante_rg, $comprovantes_dir, 'rg', $extensoes_comprovantes);
processUpload('print_perfil_app', $print_perfil_app, $comprovantes_dir, 'perfil-app', $extensoes_imagens_pdf);
processUpload('print_veiculo_app', $print_veiculo_app, $comprovantes_dir, 'veiculo-app', $extensoes_imagens_pdf);
processUpload('print_ganhos_hoje', $print_ganhos_hoje, $comprovantes_dir, 'ganhos-hoje', $extensoes_imagens_pdf);
processUpload('print_ganhos_30dias', $print_ganhos_30dias, $comprovantes_dir, 'ganhos-30dias', $extensoes_imagens_pdf);
processUpload('extrato_90dias', $extrato_90dias, $comprovantes_dir, 'extrato-90dias', $extensoes_imagens_pdf);
processUpload('contracheque', $contracheque, $comprovantes_dir, 'contracheque', $extensoes_imagens_pdf);

// NOVO: Chamada para processar o upload de 'comprovante_extra_autonomo'
processUpload('comprovante_extra_autonomo', $comprovante_extra_autonomo, $comprovantes_dir, 'extra-autonomo', $extensoes_comprovantes);
processUpload('comprovante_extra_assalariado', $comprovante_extra_assalariado, $comprovantes_dir, 'extra-assalariado', $extensoes_comprovantes);


// --- NOVA LÓGICA PARA PROCESSAR A FOTO DO USUÁRIO ENVIADA VIA BASE64 (CÂMERA) ---
// Verifica se o campo 'foto_usuario' foi enviado via POST (contém a string Base64)
if (isset($_POST['foto_usuario']) && !empty($_POST['foto_usuario'])) {
    $imageData = $_POST['foto_usuario'];

    // Verifica se a string Base64 tem o prefixo de dados
    if (strpos($imageData, 'data:image/') === 0) {
        // Extrai o tipo de imagem (ex: 'png', 'jpeg') e os dados Base64 puros
        list($type, $imageData) = explode(';', $imageData);
        list(, $imageData) = explode(',', $imageData);
        
        $ext_foto = str_replace('data:image/', '', $type); // Ex: 'png', 'jpeg'
        // Garante que a extensão seja 'jpeg' se for 'jpg' (para padronização)
        if ($ext_foto == 'jpg') $ext_foto = 'jpeg';

        if (!in_array($ext_foto, ['png', 'jpeg', 'gif', 'webp'])) { // Apenas imagens para foto de perfil
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

        // Exclui a foto antiga se existir e não for a padrão
        if ($foto != 'sem-foto.jpg' && file_exists($clientes_dir . $foto)) {
            @unlink($clientes_dir . $foto);
        }
        $foto = $nome_img_foto; // Atualiza a variável $foto com o novo nome

        // Salva a imagem decodificada no caminho de destino
        $salvo = file_put_contents($caminho_foto, $decodedImage);

        if ($salvo === false) {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar a imagem da câmera no servidor.']);
            exit();
        }

        // Tenta otimizar a imagem após salvar
        // Abre a imagem recém-salva para otimização, se necessário
        list($largura, $altura) = getimagesize($caminho_foto);

        if ($largura > 1400) {
            $nova_largura = 1400;
            $nova_altura = intval(($altura / $largura) * $nova_largura);
            $image_resampled = imagecreatetruecolor($nova_largura, $nova_altura);

            $imagem_original_from_file = null;
            switch ($ext_foto) {
                case 'png':
                    $imagem_original_from_file = imagecreatefrompng($caminho_foto);
                    imagealphablending($image_resampled, false);
                    imagesavealpha($image_resampled, true);
                    break;
                case 'jpeg':
                    $imagem_original_from_file = imagecreatefromjpeg($caminho_foto);
                    break;
                case 'gif':
                    $imagem_original_from_file = imagecreatefromgif($caminho_foto);
                    break;
                case 'webp':
                    $imagem_original_from_file = imagecreatefromwebp($caminho_foto);
                    break;
            }

            if ($imagem_original_from_file) {
                imagecopyresampled($image_resampled, $imagem_original_from_file, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);
                imagejpeg($image_resampled, $caminho_foto, 80); // Qualidade 80 para fotos de perfil
                imagedestroy($imagem_original_from_file);
                imagedestroy($image_resampled);
            }
        }
    } else {
        // Se a string não tiver o formato esperado de Base64, pode ser um erro ou dados inválidos
        // Opcional: logar para investigação
        error_log("ALERTA: foto_usuario enviada, mas não é uma string Base64 válida: " . substr($imageData, 0, 50) . "...");
    }
}

// --- Lógica Condicional para Dados do Ramo ---
// Coleta e atribui os valores dos campos específicos do ramo
if ($ramo === 'uber') {
    $modelo_veiculo = htmlspecialchars(trim(@$_POST['modelo_veiculo']));
    $status_veiculo = htmlspecialchars(trim(@$_POST['status_veiculo']));
    $valor_aluguel = isset($_POST['valor_aluguel']) ? str_replace(',', '.', str_replace(['R$', '.', ' '], '', $_POST['valor_aluguel'])) : 0;
} else if ($ramo === 'autonomo') {
    $funcao_autonomo = htmlspecialchars(trim(@$_POST['funcao_autonomo']));
    $empresa_autonomo = htmlspecialchars(trim(@$_POST['empresa_autonomo']));
    // NOVO: Verifica se o comprovante extra foi enviado para autônomo.
    // A variável $comprovante_extra_autonomo já foi preenchida pela função processUpload.
} else if ($ramo === 'assalariado') {
    $funcao_assalariado = htmlspecialchars(trim(@$_POST['funcao_assalariado']));
    $empresa_assalariado = htmlspecialchars(trim(@$_POST['empresa_assalariado']));
}


// Prepara a query de INSERT ou UPDATE
if($id == ""){
    // Query de INSERT para um novo registro
    $query = $pdo->prepare("INSERT INTO $tabela
        SET nome = :nome,
        email = :email,
        cpf = :cpf,
        telefone = :telefone,
        data_cad = curDate(),
        endereco = :endereco,
        data_nasc = :data_nasc,
        pix = :pix,
        indicacao = :indicacao,
        indicacao_contato = :indicacao_contato,
        bairro = :bairro,
        estado = :estado,
        cidade = :cidade,
        cep = :cep,
        pessoa = :pessoa,
        nome_sec = :nome_sec,
        telefone_sec = :telefone_sec,
        endereco_sec = :endereco_sec,
        grupo = :grupo,
        status = :status,
        comprovante_endereco = :comprovante_endereco,
        comprovante_rg = :comprovante_rg,
        telefone2 = :telefone2,
        foto = :foto,
        status_cliente = :status_cliente,
        senha_crip = :senha_crip,
        rg = :rg,
        ramo = :ramo,
        quadra = :quadra,
        lote = :lote,
        numero = :numero,
        complemento = :complemento,
        referencia_nome = :referencia_nome,
        referencia_contato = :referencia_contato,
        referencia_parentesco = :referencia_parentesco,
        modelo_veiculo = :modelo_veiculo,
        status_veiculo = :status_veiculo,
        placa = :placa,
        valor_aluguel = :valor_aluguel,
        print_perfil_app = :print_perfil_app,
        print_veiculo_app = :print_veiculo_app,
        print_ganhos_hoje = :print_ganhos_hoje,
        print_ganhos_30dias = :print_ganhos_30dias,
        funcao_autonomo = :funcao_autonomo,
        empresa_autonomo = :empresa_autonomo,
        extrato_90dias = :extrato_90dias,
        funcao_assalariado = :funcao_assalariado,
        empresa_assalariado = :empresa_assalariado,
        contracheque = :contracheque,
        valor_desejado = :valor_desejado,
        valor_parcela_desejada = :valor_parcela_desejada,
        comprovante_extra_autonomo = :comprovante_extra_autonomo,
        comprovante_extra_assalariado = :comprovante_extra_assalariado
    ");

}else{
    // Query de UPDATE para um registro existente
    $query = $pdo->prepare("
        UPDATE $tabela SET
        nome = :nome,
        email = :email,
        cpf = :cpf,
        telefone = :telefone,
        endereco = :endereco,
        data_nasc = :data_nasc,
        pix = :pix,
        indicacao = :indicacao,
        indicacao_contato = :indicacao_contato,
        bairro = :bairro,
        estado = :estado,
        cidade = :cidade,
        cep = :cep,
        pessoa = :pessoa,
        nome_sec = :nome_sec,
        telefone_sec = :telefone_sec,
        endereco_sec = :endereco_sec,
        grupo = :grupo,
        status = :status,
        comprovante_endereco = :comprovante_endereco,
        comprovante_rg = :comprovante_rg,
        telefone2 = :telefone2,
        foto = :foto,
        status_cliente = :status_cliente,
        rg = :rg,
        ramo = :ramo,
        quadra = :quadra,
        lote = :lote,
        numero = :numero,
        complemento = :complemento,
        referencia_nome = :referencia_nome,
        referencia_contato = :referencia_contato,
        referencia_parentesco = :referencia_parentesco,
        modelo_veiculo = :modelo_veiculo,
        status_veiculo = :status_veiculo,
        placa = :placa,
        valor_aluguel = :valor_aluguel,
        print_perfil_app = :print_perfil_app,
        print_veiculo_app = :print_veiculo_app,
        print_ganhos_hoje = :print_ganhos_hoje,
        print_ganhos_30dias = :print_ganhos_30dias,
        funcao_autonomo = :funcao_autonomo,
        empresa_autonomo = :empresa_autonomo,
        extrato_90dias = :extrato_90dias,
        funcao_assalariado = :funcao_assalariado,
        empresa_assalariado = :empresa_assalariado,
        contracheque = :contracheque,
        valor_desejado = :valor_desejado,
        valor_parcela_desejada = :valor_parcela_desejada,
        comprovante_extra_autonomo = :comprovante_extra_autonomo,
        comprovante_extra_assalariado = :comprovante_extra_assalariado
        where id = :id
    ");
}

// --- Binda os parâmetros para a query ---
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

// Bind de campos específicos do ramo
$query->bindValue(":modelo_veiculo", $modelo_veiculo);
$query->bindValue(":status_veiculo", $status_veiculo);
$query->bindValue(":placa", $placa);

// Binda valores monetários
$query->bindValue(":valor_aluguel", $valor_aluguel);
$query->bindValue(":valor_desejado", $valor_desejado);
$query->bindValue(":valor_parcela_desejada", $valor_parcela_desejada);

// Bind dos novos campos de ramo (autônomo e assalariado)
$query->bindValue(":funcao_autonomo", $funcao_autonomo);
$query->bindValue(":empresa_autonomo", $empresa_autonomo);
$query->bindValue(":extrato_90dias", $extrato_90dias); // Nome do arquivo

$query->bindValue(":funcao_assalariado", $funcao_assalariado);
$query->bindValue(":empresa_assalariado", $empresa_assalariado);
$query->bindValue(":contracheque", $contracheque); // Nome do arquivo

// Bind dos novos campos de prints de apps
$query->bindValue(":print_perfil_app", $print_perfil_app);
$query->bindValue(":print_veiculo_app", $print_veiculo_app);
$query->bindValue(":print_ganhos_hoje", $print_ganhos_hoje);
$query->bindValue(":print_ganhos_30dias", $print_ganhos_30dias);

// NOVO: Binda o valor do comprovante extra do autônomo
$query->bindValue(":comprovante_extra_autonomo", $comprovante_extra_autonomo);

$query->bindValue(":comprovante_extra_assalariado", $comprovante_extra_assalariado);

// Bind de campos que eram interpolados
$query->bindValue(":data_nasc", $data_nasc);
$query->bindValue(":foto", $foto);
$query->bindValue(":comprovante_endereco", $comprovante_endereco);
$query->bindValue(":comprovante_rg", $comprovante_rg);
$query->bindValue(":status_cliente", $status_cliente);
$query->bindValue(":senha_crip", $senha_crip);


// Se for uma edição, binda o ID
if ($id != "") {
    $query->bindValue(":id", $id);
}

// --- Executa a query com tratamento de erros ---
try {
    $query->execute();

    // Após a execução do INSERT/UPDATE, obtenha o ID do cliente
    if ($id == "") {
        // Se foi um INSERT, pega o ID do último registro inserido
        $novo_cliente_id = $pdo->lastInsertId();
    } else {
        // Se foi um UPDATE, o ID já é conhecido
        $novo_cliente_id = $id;
    }

    // Se um alerta de nome duplicado foi detectado, insira na tabela de alertas
    if ($alerta_nome_duplicado === true) {
        $stmt_alerta = $pdo->prepare("INSERT INTO alertas_duplicidade (tipo_alerta, valor_duplicado, id_cliente_cadastrado) VALUES (:tipo, :valor, :cliente_id)");
        $stmt_alerta->bindValue(":tipo", "Nome Duplicado");
        $stmt_alerta->bindValue(":valor", $nome);
        $stmt_alerta->bindValue(":cliente_id", $novo_cliente_id);
        $stmt_alerta->execute();
    }

    echo json_encode(['success' => true, 'message' => 'Salvo com Sucesso']); // Mensagem de sucesso em JSON

} catch (PDOException $e) {
    // Log do erro completo para o servidor (para depuração)
    error_log("Erro de PDO ao salvar cliente: " . $e->getMessage());

    // Mensagem genérica para o frontend, para não expor detalhes internos
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar os dados. Por favor, tente novamente mais tarde.']);
}

// --- Envio de mensagem para o administrador (se cliente_cadastro for 'Sim' e variáveis de API existirem) ---
// Certifique-se de que $telefone_sistema, $token, $instancia, $nome_sistema, $url_sistema
// estejam definidos em seu arquivo 'conexao.php' ou similar.
$tel_cliente_admin = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_sistema);
$telefone_envio_admin = $tel_cliente_admin;

if(isset($cliente_cadastro) && $cliente_cadastro == 'Sim' && isset($token) && $token != "" && isset($instancia) && $instancia != ""){
    $mensagem_admin = '*'.$nome_sistema.'* %0A';
    $mensagem_admin .= '_Novo Cliente Cadastrado_ %0A';
    $mensagem_admin .= 'Cliente: *'.$nome.'* %0A';
    $mensagem_admin .= 'Telefone: *'.$telefone.'* %0A%0A';
    // DESCOMENTE A LINHA ABAIXO QUANDO O ARQUIVO 'apis/texto.php' ESTIVER PRONTO E CONFIGURADO
    // require('../../apis/texto.php');
}

// --- Envio de mensagem para o cliente (se variáveis de API existirem) ---
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
        $sua_senha = ' a senha 123';
    }

    $mensagem_user .= 'Use seu CPF e '.$sua_senha.' %0A';
    $mensagem_user .= $url_sistema.'acesso';

    // DESCOMENTE A LINHA ABAIXO QUANDO O ARQUIVO 'apis/texto.php' ESTIVER PRONTO E CONFIGURADO
    // require('../../apis/texto.php');
}
?>