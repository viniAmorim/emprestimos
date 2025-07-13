<?php
$tabela = 'clientes';
require_once("../../../conexao.php"); // Inclui o arquivo de conexão com o banco de dados

// Configuração de resposta JSON (adicionado para melhor comunicação com o frontend)
header('Content-Type: application/json');

// --- 1. Coleta e sanitiza os dados do POST ---
$nome = @$_POST['nome'];
$email = @$_POST['email'];
$telefone = @$_POST['telefone'];
$data_nasc = @$_POST['data_nasc'];
$data_nasc = implode('-', array_reverse(explode('/', $data_nasc))); // Formata a data para YYYY-MM-DD
$endereco = @$_POST['endereco'];
$cpf = @$_POST['cpf'];
$pix = @$_POST['pix'];
$indicacao = @$_POST['indicacao'];
$bairro = @$_POST['bairro'];
$cidade = @$_POST['cidade'];
$estado = @$_POST['estado'];
$cep = @$_POST['cep'];
$id = @$_POST['id']; // ID do registro se for uma edição
$pessoa = @$_POST['pessoa'];
$status = @$_POST['status'];

$rg = @$_POST['rg'];
$ramo = @$_POST['ramo'];
$quadra = @$_POST['quadra'];
$lote = @$_POST['lote'];
$numero = @$_POST['numero'];
$complemento = @$_POST['complemento'];
$referencia_nome = @$_POST['referencia_nome'];
$referencia_contato = @$_POST['referencia_contato'];
$referencia_parentesco = @$_POST['referencia_parentesco'];

// Mapeamento correto: 'placa_veiculo' do form para 'placa' no DB
$placa = @$_POST['placa_veiculo'];

// Novas variáveis para campos condicionais - Inicializadas como nulas ou vazias
$modelo_veiculo = null;
$status_veiculo = null;
$valor_aluguel = null; // Será tratado abaixo

$funcao_autonomo = null;
$empresa_autonomo = null;
$extrato_90dias_file = null; // Nome do arquivo do extrato

$funcao_assalariado = null;
$empresa_assalariado = null;
$contracheque_file = null; // Nome do arquivo do contracheque

$print_perfil_app_file = null; // Nome do arquivo
$print_veiculo_app_file = null; // Nome do arquivo
$print_ganhos_hoje_file = null; // Nome do arquivo
$print_ganhos_30dias_file = null; // Nome do arquivo

$nome_sec = @$_POST['nome_sec'];
$telefone_sec = @$_POST['telefone_sec'];
$endereco_sec = @$_POST['endereco_sec'];
$grupo = @$_POST['grupo'];
$cliente_cadastro = @$_POST['cliente_cadastro'];
$telefone2 = @$_POST['telefone2'];
$status_cliente = @$_POST['status_cliente'];

$senha = @$_POST['senha'];
$conf_senha = @$_POST['conf_senha'];

// Formata valores monetários, removendo R$, pontos e substituindo vírgula por ponto
$valor_desejado = isset($_POST['valor_desejado']) ? str_replace(',', '.', str_replace(['R$', '.', ' '], '', $_POST['valor_desejado'])) : 0;
$valor_parcela_desejada = isset($_POST['parcela_desejada']) ? str_replace(',', '.', str_replace(['R$', '.', ' '], '', $_POST['parcela_desejada'])) : 0; // Corrigido para 'parcela_desejada' do form

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
    $query = $pdo->query("SELECT * from $tabela where cpf = '$cpf'");
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
    $query = $pdo->query("SELECT * from $tabela where nome = '$nome'");
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
    $query = $pdo->query("SELECT * from $tabela where telefone = '$telefone'");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $id_reg = @$res[0]['id'];
    if(@count($res) > 0 && $id != $id_reg){ // Se encontrou e não é o próprio registro sendo editado
        echo json_encode(['success' => false, 'message' => 'Telefone já Cadastrado!']);
        error_log("ALERTA ADMINISTRADOR: Tentativa de cadastro/edição com Telefone duplicado: " . $telefone . " para o ID: " . $id);
        exit(); // Impede o cadastro
    }
}

// --- Inicializa variáveis de imagem e busca existentes para edição ---
$comprovante_endereco = 'sem-foto.png';
$comprovante_rg = 'sem-foto.png';
$foto = 'sem-foto.jpg';

// Novas variáveis para arquivos condicionais, inicializadas com valor padrão
$print_perfil_app = 'sem-foto.png';
$print_veiculo_app = 'sem-foto.png';
$print_ganhos_hoje = 'sem-foto.png';
$print_ganhos_30dias = 'sem-foto.png';
$extrato_90dias = 'sem-foto.png';
$contracheque = 'sem-foto.png';


// Busca os nomes de arquivo existentes no banco de dados se for uma edição
if ($id != "") {
    $query = $pdo->query("SELECT
        comprovante_endereco, comprovante_rg, foto,
        print_perfil_app, print_veiculo_app, print_ganhos_hoje, print_ganhos_30dias,
        extrato_90dias, contracheque
        FROM $tabela where id = '$id'");
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
    }
}

// --- Diretórios de Upload (ajustados para sua estrutura) ---
$base_images_dir = '../../images/';
$comprovantes_dir = $base_images_dir . 'comprovantes/';
$clientes_dir = $base_images_dir . 'clientes/';
$documentos_renda_dir = $base_images_dir . 'documentos_renda/'; // Novo diretório
$prints_apps_dir = $base_images_dir . 'prints_apps/';       // Novo diretório

// !!! ESTE BLOCO FOI REMOVIDO PERMANENTEMENTE. A CRIAÇÃO DE DIRETÓRIOS É FEITA NO entrypoint.sh !!!
/*
foreach ([$comprovantes_dir, $clientes_dir, $documentos_renda_dir, $prints_apps_dir] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}
*/

// --- SCRIPT PARA SUBIR COMPROVANTE DE ENDEREÇO ---
if (isset($_FILES['comprovante_endereco']) && $_FILES['comprovante_endereco']['name'] != "") {
    $nome_img_endereco = date('d-m-Y-H-i-s') . '-' . preg_replace('/[ :]+/', '-', $_FILES['comprovante_endereco']['name']);
    $caminho_endereco = $comprovantes_dir . $nome_img_endereco;
    $imagem_temp_endereco = $_FILES['comprovante_endereco']['tmp_name'];
    $ext_endereco = strtolower(pathinfo($nome_img_endereco, PATHINFO_EXTENSION));
    $extensoes_permitidas = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'rar', 'zip', 'doc', 'docx', 'webp', 'xlsx', 'xlsm', 'xls', 'xml'];

    error_log("DIAGNOSTICO: Tentando upload de comprovante_endereco.");
    error_log("DIAGNOSTICO: Temp file: " . $imagem_temp_endereco . " (exists: " . (file_exists($imagem_temp_endereco) ? 'true' : 'false') . ", is_uploaded_file: " . (is_uploaded_file($imagem_temp_endereco) ? 'true' : 'false') . ")");
    error_log("DIAGNOSTICO: Dest path: " . $caminho_endereco . " (is_writable: " . (is_writable(dirname($caminho_endereco)) ? 'true' : 'false') . ")");


    if (in_array($ext_endereco, $extensoes_permitidas)) {
        if ($comprovante_endereco != "sem-foto.png" && file_exists($comprovantes_dir . $comprovante_endereco)) {
            @unlink($comprovantes_dir . $comprovante_endereco);
        }
        $comprovante_endereco = $nome_img_endereco;
        if (in_array($ext_endereco, ['pdf', 'rar', 'zip', 'doc', 'docx', 'xlsx', 'xlsm', 'xls', 'xml'])) {
            if (!move_uploaded_file($imagem_temp_endereco, $caminho_endereco)) {
                error_log("ERRO: Falha ao mover arquivo de comprovante_endereco (PDF/DOC/etc). Origem: " . $imagem_temp_endereco . ", Destino: " . $caminho_endereco);
            }
        } else {
            list($largura, $altura) = getimagesize($imagem_temp_endereco);
            if ($largura > 1400) {
                $nova_largura = 1400;
                $nova_altura = ($altura / $largura) * $nova_largura;
                $image = imagecreatetruecolor($nova_largura, $nova_altura);
                if ($ext_endereco == 'png') {
                    $imagem_original = imagecreatefrompng($imagem_temp_endereco);
                    imagealphablending($image, false);
                    imagesavealpha($image, true);
                } else if ($ext_endereco == 'jpeg' || $ext_endereco == 'jpg') {
                    $imagem_original = imagecreatefromjpeg($imagem_temp_endereco);
                } else if ($ext_endereco == 'gif') {
                    $imagem_original = imagecreatefromgif($imagem_temp_endereco);
                } else if ($ext_endereco == 'webp') {
                    $imagem_original = imagecreatefromwebp($imagem_temp_endereco);
                } else { // Fallback for unsupported image types
                    if (!move_uploaded_file($imagem_temp_endereco, $caminho_endereco)) {
                        error_log("ERRO: Falha ao mover arquivo de comprovante_endereco (fallback). Origem: " . $imagem_temp_endereco . ", Destino: " . $caminho_endereco);
                    }
                    $imagem_original = null;
                }
                if ($imagem_original) {
                    imagecopyresampled($image, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);
                    if ($ext_endereco == 'png') { imagepng($image, $caminho_endereco, 9); } // PNG quality 0-9
                    else if ($ext_endereco == 'gif') { imagegif($image, $caminho_endereco); }
                    else if ($ext_endereco == 'webp') { imagewebp($image, $caminho_endereco, 80); }
                    else { imagejpeg($image, $caminho_endereco, 80); } // JPEG quality 0-100
                    imagedestroy($imagem_original);
                    imagedestroy($image);
                }
            } else {
                if (!move_uploaded_file($imagem_temp_endereco, $caminho_endereco)) {
                    error_log("ERRO: Falha ao mover arquivo de comprovante_endereco (sem redimensionamento). Origem: " . $imagem_temp_endereco . ", Destino: " . $caminho_endereco);
                }
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Extensão de arquivo de comprovante de endereço não permitida!']);
        exit();
    }
}

// --- SCRIPT PARA SUBIR COMPROVANTE DE RG/CNH ---
if (isset($_FILES['comprovante_rg']) && $_FILES['comprovante_rg']['name'] != "") {
    $nome_img_rg = date('d-m-Y-H-i-s') . '-' . preg_replace('/[ :]+/', '-', $_FILES['comprovante_rg']['name']);
    $caminho_rg = $comprovantes_dir . $nome_img_rg;
    $imagem_temp_rg = $_FILES['comprovante_rg']['tmp_name'];
    $ext_rg = strtolower(pathinfo($nome_img_rg, PATHINFO_EXTENSION));
    $extensoes_permitidas = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'rar', 'zip', 'doc', 'docx', 'webp', 'xlsx', 'xlsm', 'xls', 'xml'];

    error_log("DIAGNOSTICO: Tentando upload de comprovante_rg.");
    error_log("DIAGNOSTICO: Temp file: " . $imagem_temp_rg . " (exists: " . (file_exists($imagem_temp_rg) ? 'true' : 'false') . ", is_uploaded_file: " . (is_uploaded_file($imagem_temp_rg) ? 'true' : 'false') . ")");
    error_log("DIAGNOSTICO: Dest path: " . $caminho_rg . " (is_writable: " . (is_writable(dirname($caminho_rg)) ? 'true' : 'false') . ")");

    if (in_array($ext_rg, $extensoes_permitidas)) {
        if ($comprovante_rg != "sem-foto.png" && file_exists($comprovantes_dir . $comprovante_rg)) {
            @unlink($comprovantes_dir . $comprovante_rg);
        }
        $comprovante_rg = $nome_img_rg;
        if (in_array($ext_rg, ['pdf', 'rar', 'zip', 'doc', 'docx', 'xlsx', 'xlsm', 'xls', 'xml'])) {
            if (!move_uploaded_file($imagem_temp_rg, $caminho_rg)) {
                error_log("ERRO: Falha ao mover arquivo de comprovante_rg (PDF/DOC/etc). Origem: " . $imagem_temp_rg . ", Destino: " . $caminho_rg);
            }
        } else {
            list($largura, $altura) = getimagesize($imagem_temp_rg);
            if ($largura > 1400) {
                $nova_largura = 1400;
                $nova_altura = ($altura / $largura) * $nova_largura;
                $image = imagecreatetruecolor($nova_largura, $nova_altura);
                if ($ext_rg == 'png') {
                    $imagem_original = imagecreatefrompng($imagem_temp_rg);
                    imagealphablending($image, false);
                    imagesavealpha($image, true);
                } else if ($ext_rg == 'jpeg' || $ext_rg == 'jpg') {
                    $imagem_original = imagecreatefromjpeg($imagem_temp_rg);
                } else if ($ext_rg == 'gif') {
                    $imagem_original = imagecreatefromgif($imagem_temp_rg);
                } else if ($ext_rg == 'webp') {
                    $imagem_original = imagecreatefromwebp($imagem_temp_rg);
                } else {
                    if (!move_uploaded_file($imagem_temp_rg, $caminho_rg)) {
                        error_log("ERRO: Falha ao mover arquivo de comprovante_rg (fallback). Origem: " . $imagem_temp_rg . ", Destino: " . $caminho_rg);
                    }
                    $imagem_original = null;
                }
                if ($imagem_original) {
                    imagecopyresampled($image, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);
                    if ($ext_rg == 'png') { imagepng($image, $caminho_rg, 9); }
                    else if ($ext_rg == 'gif') { imagegif($image, $caminho_rg); }
                    else if ($ext_rg == 'webp') { imagewebp($image, $caminho_rg, 80); }
                    else { imagejpeg($image, $caminho_rg, 80); }
                    imagedestroy($imagem_original);
                    imagedestroy($image);
                }
            } else {
                if (!move_uploaded_file($imagem_temp_rg, $caminho_rg)) {
                    error_log("ERRO: Falha ao mover arquivo de comprovante_rg (sem redimensionamento). Origem: " . $imagem_temp_rg . ", Destino: " . $caminho_rg);
                }
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Extensão de arquivo de comprovante RG/CNH não permitida!']);
        exit();
    }
}

// --- SCRIPT PARA SUBIR FOTO DO USUÁRIO ---
if (isset($_FILES['foto_usuario']) && $_FILES['foto_usuario']['tmp_name'] != "") { // Corrigido para 'foto_usuario'
    $nome_original_foto = $_FILES['foto_usuario']['name'];
    $ext_foto = strtolower(pathinfo($nome_original_foto, PATHINFO_EXTENSION));
    $extensoes_permitidas_foto = ['png', 'jpg', 'jpeg', 'gif', 'webp'];

    error_log("DIAGNOSTICO: Tentando upload de foto_usuario.");
    error_log("DIAGNOSTICO: Temp file: " . $_FILES['foto_usuario']['tmp_name'] . " (exists: " . (file_exists($_FILES['foto_usuario']['tmp_name']) ? 'true' : 'false') . ", is_uploaded_file: " . (is_uploaded_file($_FILES['foto_usuario']['tmp_name']) ? 'true' : 'false') . ")");
    error_log("DIAGNOSTICO: Dest path: " . $clientes_dir . $nome_img_foto . " (is_writable: " . (is_writable(dirname($clientes_dir . $nome_img_foto)) ? 'true' : 'false') . ")");

    if (!in_array($ext_foto, $extensoes_permitidas_foto)) {
        echo json_encode(['success' => false, 'message' => 'Extensão de imagem de usuário não permitida!']);
        exit();
    }

    $nome_img_foto = date('Y-m-d_H-i-s') . '.' . $ext_foto;
    $caminho_foto = $clientes_dir . $nome_img_foto;
    $imagem_temp_foto = $_FILES['foto_usuario']['tmp_name'];

    if ($foto != 'sem-foto.jpg' && file_exists($clientes_dir . $foto)) {
        @unlink($clientes_dir . $foto);
    }
    $foto = $nome_img_foto;

    list($largura, $altura) = getimagesize($imagem_temp_foto);

    if ($largura > 1400) {
        $nova_largura = 1400;
        $nova_altura = intval(($altura / $largura) * $nova_largura);
        $image = imagecreatetruecolor($nova_largura, $nova_altura);

        switch ($ext_foto) {
            case 'png':
                $imagem_original = imagecreatefrompng($imagem_temp_foto);
                imagealphablending($image, false);
                imagesavealpha($image, true);
                break;
            case 'jpg':
            case 'jpeg':
                $imagem_original = imagecreatefromjpeg($imagem_temp_foto);
                break;
            case 'gif':
            case 'webp':
                $imagem_original = imagecreatefromgif($imagem_temp_foto);
                break;
            default:
                echo json_encode(['success' => false, 'message' => "Formato de imagem de usuário não suportado."]);
                exit();
        }
        imagecopyresampled($image, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);
        if ($ext_foto == 'png') { imagepng($image, $caminho_foto, 9); }
        else if ($ext_foto == 'gif') { imagegif($image, $caminho_foto); }
        else if ($ext_foto == 'webp') { imagewebp($image, $caminho_foto, 80); }
        else { imagejpeg($image, $caminho_foto, 80); }
        imagedestroy($imagem_original);
        imagedestroy($image);
    } else {
        if (!move_uploaded_file($imagem_temp_foto, $caminho_foto)) {
            error_log("ERRO: Falha ao mover arquivo de foto_usuario (sem redimensionamento). Origem: " . $imagem_temp_foto . ", Destino: " . $caminho_foto);
        }
    }
}

// --- NOVOS SCRIPTS PARA UPLOAD DE IMAGENS CONDICIONAIS ---

// SCRIPT PARA SUBIR PRINT PERFIL APP
if (isset($_FILES['print_perfil_app']) && $_FILES['print_perfil_app']['name'] != "") {
    $nome_img = date('d-m-Y-H-i-s') . '-perfil-' . preg_replace('/[ :]+/', '-', $_FILES['print_perfil_app']['name']);
    $caminho = $prints_apps_dir . $nome_img;
    $imagem_temp = $_FILES['print_perfil_app']['tmp_name'];
    $ext = strtolower(pathinfo($nome_img, PATHINFO_EXTENSION));
    $extensoes_permitidas = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'webp'];

    error_log("DIAGNOSTICO: Tentando upload de print_perfil_app.");
    error_log("DIAGNOSTICO: Temp file: " . $imagem_temp . " (exists: " . (file_exists($imagem_temp) ? 'true' : 'false') . ", is_uploaded_file: " . (is_uploaded_file($imagem_temp) ? 'true' : 'false') . ")");
    error_log("DIAGNOSTICO: Dest path: " . $caminho . " (is_writable: " . (is_writable(dirname($caminho)) ? 'true' : 'false') . ")");

    if (in_array($ext, $extensoes_permitidas)) {
        if ($print_perfil_app != "sem-foto.png" && file_exists($prints_apps_dir . $print_perfil_app)) {
            @unlink($prints_apps_dir . $print_perfil_app);
        }
        $print_perfil_app = $nome_img;
        if ($ext == 'pdf') { // PDFs não são redimensionados
            if (!move_uploaded_file($imagem_temp, $caminho)) {
                error_log("ERRO: Falha ao mover arquivo de print_perfil_app (PDF). Origem: " . $imagem_temp . ", Destino: " . $caminho);
            }
        } else {
            list($largura, $altura) = getimagesize($imagem_temp);
            if ($largura > 1400) {
                $nova_largura = 1400;
                $nova_altura = ($altura / $largura) * $nova_largura;
                $image = imagecreatetruecolor($nova_largura, $nova_altura);
                if ($ext == 'png') { $imagem_original = imagecreatefrompng($imagem_temp); imagealphablending($image, false); imagesavealpha($image, true); }
                else if ($ext == 'jpeg' || $ext == 'jpg') { $imagem_original = imagecreatefromjpeg($imagem_temp); }
                else if ($ext == 'gif') { $imagem_original = imagecreatefromgif($imagem_temp); }
                else if ($ext == 'webp') { $imagem_original = imagecreatefromwebp($imagem_temp); }
                if ($imagem_original) {
                    imagecopyresampled($image, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);
                    if ($ext == 'png') { imagepng($image, $caminho, 9); }
                    else if ($ext == 'gif') { imagegif($image, $caminho); }
                    else if ($ext == 'webp') { imagewebp($image, $caminho, 80); }
                    else { imagejpeg($image, $caminho, 80); }
                    imagedestroy($imagem_original);
                    imagedestroy($image);
                }
            } else {
                if (!move_uploaded_file($imagem_temp, $caminho)) {
                    error_log("ERRO: Falha ao mover arquivo de print_perfil_app (sem redimensionamento). Origem: " . $imagem_temp . ", Destino: " . $caminho);
                }
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Extensão de arquivo de Print Perfil App não permitida!']);
        exit();
    }
}

// SCRIPT PARA SUBIR PRINT VEICULO APP
if (isset($_FILES['print_veiculo_app']) && $_FILES['print_veiculo_app']['name'] != "") {
    $nome_img = date('d-m-Y-H-i-s') . '-veiculo-' . preg_replace('/[ :]+/', '-', $_FILES['print_veiculo_app']['name']);
    $caminho = $prints_apps_dir . $nome_img;
    $imagem_temp = $_FILES['print_veiculo_app']['tmp_name'];
    $ext = strtolower(pathinfo($nome_img, PATHINFO_EXTENSION));
    $extensoes_permitidas = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'webp'];

    error_log("DIAGNOSTICO: Tentando upload de print_veiculo_app.");
    error_log("DIAGNOSTICO: Temp file: " . $imagem_temp . " (exists: " . (file_exists($imagem_temp) ? 'true' : 'false') . ", is_uploaded_file: " . (is_uploaded_file($imagem_temp) ? 'true' : 'false') . ")");
    error_log("DIAGNOSTICO: Dest path: " . $caminho . " (is_writable: " . (is_writable(dirname($caminho)) ? 'true' : 'false') . ")");

    if (in_array($ext, $extensoes_permitidas)) {
        if ($print_veiculo_app != "sem-foto.png" && file_exists($prints_apps_dir . $print_veiculo_app)) {
            @unlink($prints_apps_dir . $print_veiculo_app);
        }
        $print_veiculo_app = $nome_img;
        if ($ext == 'pdf') {
            if (!move_uploaded_file($imagem_temp, $caminho)) {
                error_log("ERRO: Falha ao mover arquivo de print_veiculo_app (PDF). Origem: " . $imagem_temp . ", Destino: " . $caminho);
            }
        } else {
            list($largura, $altura) = getimagesize($imagem_temp);
            if ($largura > 1400) {
                $nova_largura = 1400;
                $nova_altura = ($altura / $largura) * $nova_largura;
                $image = imagecreatetruecolor($nova_largura, $nova_altura);
                if ($ext == 'png') { $imagem_original = imagecreatefrompng($imagem_temp); imagealphablending($image, false); imagesavealpha($image, true); }
                else if ($ext == 'jpeg' || $ext == 'jpg') { $imagem_original = imagecreatefromjpeg($imagem_temp); }
                else if ($ext == 'gif') { $imagem_original = imagecreatefromgif($imagem_temp); }
                else if ($ext == 'webp') { $imagem_original = imagecreatefromwebp($imagem_temp); }
                if ($imagem_original) {
                    imagecopyresampled($image, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);
                    if ($ext == 'png') { imagepng($image, $caminho, 9); }
                    else if ($ext == 'gif') { imagegif($image, $caminho); }
                    else if ($ext == 'webp') { imagewebp($image, $caminho, 80); }
                    else { imagejpeg($image, $caminho, 80); }
                    imagedestroy($imagem_original);
                    imagedestroy($image);
                }
            } else {
                if (!move_uploaded_file($imagem_temp, $caminho)) {
                    error_log("ERRO: Falha ao mover arquivo de print_veiculo_app (sem redimensionamento). Origem: " . $imagem_temp . ", Destino: " . $caminho);
                }
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Extensão de arquivo de Print Veículo App não permitida!']);
        exit();
    }
}

// SCRIPT PARA SUBIR PRINT GANHOS HOJE
if (isset($_FILES['print_ganhos_hoje']) && $_FILES['print_ganhos_hoje']['name'] != "") {
    $nome_img = date('d-m-Y-H-i-s') . '-ganhos-hoje-' . preg_replace('/[ :]+/', '-', $_FILES['print_ganhos_hoje']['name']);
    $caminho = $prints_apps_dir . $nome_img;
    $imagem_temp = $_FILES['print_ganhos_hoje']['tmp_name'];
    $ext = strtolower(pathinfo($nome_img, PATHINFO_EXTENSION));
    $extensoes_permitidas = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'webp'];

    error_log("DIAGNOSTICO: Tentando upload de print_ganhos_hoje.");
    error_log("DIAGNOSTICO: Temp file: " . $imagem_temp . " (exists: " . (file_exists($imagem_temp) ? 'true' : 'false') . ", is_uploaded_file: " . (is_uploaded_file($imagem_temp) ? 'true' : 'false') . ")");
    error_log("DIAGNOSTICO: Dest path: " . $caminho . " (is_writable: " . (is_writable(dirname($caminho)) ? 'true' : 'false') . ")");

    if (in_array($ext, $extensoes_permitidas)) {
        if ($print_ganhos_hoje != "sem-foto.png" && file_exists($prints_apps_dir . $print_ganhos_hoje)) {
            @unlink($prints_apps_dir . $print_ganhos_hoje);
        }
        $print_ganhos_hoje = $nome_img;
        if ($ext == 'pdf') {
            if (!move_uploaded_file($imagem_temp, $caminho)) {
                error_log("ERRO: Falha ao mover arquivo de print_ganhos_hoje (PDF). Origem: " . $imagem_temp . ", Destino: " . $caminho);
            }
        } else {
            list($largura, $altura) = getimagesize($imagem_temp);
            if ($largura > 1400) {
                $nova_largura = 1400;
                $nova_altura = ($altura / $largura) * $nova_largura;
                $image = imagecreatetruecolor($nova_largura, $nova_altura);
                if ($ext == 'png') { $imagem_original = imagecreatefrompng($imagem_temp); imagealphablending($image, false); imagesavealpha($image, true); }
                else if ($ext == 'jpeg' || $ext == 'jpg') { $imagem_original = imagecreatefromjpeg($imagem_temp); }
                else if ($ext == 'gif') { $imagem_original = imagecreatefromgif($imagem_temp); }
                else if ($ext == 'webp') { $imagem_original = imagecreatefromwebp($imagem_temp); }
                if ($imagem_original) {
                    imagecopyresampled($image, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);
                    if ($ext == 'png') { imagepng($image, $caminho, 9); }
                    else if ($ext == 'gif') { imagegif($image, $caminho); }
                    else if ($ext == 'webp') { imagewebp($image, $caminho, 80); }
                    else { imagejpeg($image, $caminho, 80); }
                    imagedestroy($imagem_original);
                    imagedestroy($image);
                }
            } else {
                if (!move_uploaded_file($imagem_temp, $caminho)) {
                    error_log("ERRO: Falha ao mover arquivo de print_ganhos_hoje (sem redimensionamento). Origem: " . $imagem_temp . ", Destino: " . $caminho);
                }
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Extensão de arquivo de Print Ganhos Hoje não permitida!']);
        exit();
    }
}

// SCRIPT PARA SUBIR PRINT GANHOS 30 DIAS
if (isset($_FILES['print_ganhos_30dias']) && $_FILES['print_ganhos_30dias']['name'] != "") {
    $nome_img = date('d-m-Y-H-i-s') . '-ganhos-30dias-' . preg_replace('/[ :]+/', '-', $_FILES['print_ganhos_30dias']['name']);
    $caminho = $prints_apps_dir . $nome_img;
    $imagem_temp = $_FILES['print_ganhos_30dias']['tmp_name'];
    $ext = strtolower(pathinfo($nome_img, PATHINFO_EXTENSION));
    $extensoes_permitidas = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'webp'];

    error_log("DIAGNOSTICO: Tentando upload de print_ganhos_30dias.");
    error_log("DIAGNOSTICO: Temp file: " . $imagem_temp . " (exists: " . (file_exists($imagem_temp) ? 'true' : 'false') . ", is_uploaded_file: " . (is_uploaded_file($imagem_temp) ? 'true' : 'false') . ")");
    error_log("DIAGNOSTICO: Dest path: " . $caminho . " (is_writable: " . (is_writable(dirname($caminho)) ? 'true' : 'false') . ")");

    if (in_array($ext, $extensoes_permitidas)) {
        if ($print_ganhos_30dias != "sem-foto.png" && file_exists($prints_apps_dir . $print_ganhos_30dias)) {
            @unlink($prints_apps_dir . $print_ganhos_30dias);
        }
        $print_ganhos_30dias = $nome_img;
        if ($ext == 'pdf') {
            if (!move_uploaded_file($imagem_temp, $caminho)) {
                error_log("ERRO: Falha ao mover arquivo de print_ganhos_30dias (PDF). Origem: " . $imagem_temp . ", Destino: " . $caminho);
            }
        } else {
            list($largura, $altura) = getimagesize($imagem_temp);
            if ($largura > 1400) {
                $nova_largura = 1400;
                $nova_altura = ($altura / $largura) * $nova_largura;
                $image = imagecreatetruecolor($nova_largura, $nova_altura);
                if ($ext == 'png') { $imagem_original = imagecreatefrompng($imagem_temp); imagealphablending($image, false); imagesavealpha($image, true); }
                else if ($ext == 'jpeg' || $ext == 'jpg') { $imagem_original = imagecreatefromjpeg($imagem_temp); }
                else if ($ext == 'gif') { $imagem_original = imagecreatefromgif($imagem_temp); }
                else if ($ext == 'webp') { $imagem_original = imagecreatefromwebp($imagem_temp); }
                if ($imagem_original) {
                    imagecopyresampled($image, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);
                    if ($ext == 'png') { imagepng($image, $caminho, 9); }
                    else if ($ext == 'gif') { imagegif($image, $caminho); }
                    else if ($ext == 'webp') { imagewebp($image, $caminho, 80); }
                    else { imagejpeg($image, $caminho, 80); }
                    imagedestroy($imagem_original);
                    imagedestroy($image);
                }
            } else {
                if (!move_uploaded_file($imagem_temp, $caminho)) {
                    error_log("ERRO: Falha ao mover arquivo de print_ganhos_30dias (sem redimensionamento). Origem: " . $imagem_temp . ", Destino: " . $caminho);
                }
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Extensão de arquivo de Print Ganhos 30 Dias não permitida!']);
        exit();
    }
}

// SCRIPT PARA SUBIR EXTRATO 90 DIAS (AUTÔNOMO)
if (isset($_FILES['extrato_90dias']) && $_FILES['extrato_90dias']['name'] != "") {
    $nome_img = date('d-m-Y-H-i-s') . '-extrato-' . preg_replace('/[ :]+/', '-', $_FILES['extrato_90dias']['name']);
    $caminho = $documentos_renda_dir . $nome_img;
    $imagem_temp = $_FILES['extrato_90dias']['tmp_name'];
    $ext = strtolower(pathinfo($nome_img, PATHINFO_EXTENSION));
    $extensoes_permitidas = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'webp'];

    error_log("DIAGNOSTICO: Tentando upload de extrato_90dias.");
    error_log("DIAGNOSTICO: Temp file: " . $imagem_temp . " (exists: " . (file_exists($imagem_temp) ? 'true' : 'false') . ", is_uploaded_file: " . (is_uploaded_file($imagem_temp) ? 'true' : 'false') . ")");
    error_log("DIAGNOSTICO: Dest path: " . $caminho . " (is_writable: " . (is_writable(dirname($caminho)) ? 'true' : 'false') . ")");

    if (in_array($ext, $extensoes_permitidas)) {
        if ($extrato_90dias != "sem-foto.png" && file_exists($documentos_renda_dir . $extrato_90dias)) {
            @unlink($documentos_renda_dir . $extrato_90dias);
        }
        $extrato_90dias = $nome_img;
        if ($ext == 'pdf') {
            if (!move_uploaded_file($imagem_temp, $caminho)) {
                error_log("ERRO: Falha ao mover arquivo de extrato_90dias (PDF). Origem: " . $imagem_temp . ", Destino: " . $caminho);
            }
        } else {
            list($largura, $altura) = getimagesize($imagem_temp);
            if ($largura > 1400) {
                $nova_largura = 1400;
                $nova_altura = ($altura / $largura) * $nova_largura;
                $image = imagecreatetruecolor($nova_largura, $nova_altura);
                if ($ext == 'png') { $imagem_original = imagecreatefrompng($imagem_temp); imagealphablending($image, false); imagesavealpha($image, true); }
                else if ($ext == 'jpeg' || $ext == 'jpg') { $imagem_original = imagecreatefromjpeg($imagem_temp); }
                else if ($ext == 'gif') { $imagem_original = imagecreatefromgif($imagem_temp); }
                else if ($ext == 'webp') { $imagem_original = imagecreatefromwebp($imagem_temp); }
                if ($imagem_original) {
                    imagecopyresampled($image, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);
                    if ($ext == 'png') { imagepng($image, $caminho, 9); }
                    else if ($ext == 'gif') { imagegif($image, $caminho); }
                    else if ($ext == 'webp') { imagewebp($image, $caminho, 80); }
                    else { imagejpeg($image, $caminho, 80); }
                    imagedestroy($imagem_original);
                    imagedestroy($image);
                }
            } else {
                if (!move_uploaded_file($imagem_temp, $caminho)) {
                    error_log("ERRO: Falha ao mover arquivo de extrato_90dias (sem redimensionamento). Origem: " . $imagem_temp . ", Destino: " . $caminho);
                }
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Extensão de arquivo de Extrato 90 Dias não permitida!']);
        exit();
    }
}

// SCRIPT PARA SUBIR CONTRACHEQUE (ASSALARIADO)
if (isset($_FILES['contracheque']) && $_FILES['contracheque']['name'] != "") {
    $nome_img = date('d-m-Y-H-i-s') . '-contracheque-' . preg_replace('/[ :]+/', '-', $_FILES['contracheque']['name']);
    $caminho = $documentos_renda_dir . $nome_img;
    $imagem_temp = $_FILES['contracheque']['tmp_name'];
    $ext = strtolower(pathinfo($nome_img, PATHINFO_EXTENSION));
    $extensoes_permitidas = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'webp'];

    error_log("DIAGNOSTICO: Tentando upload de contracheque.");
    error_log("DIAGNOSTICO: Temp file: " . $imagem_temp . " (exists: " . (file_exists($imagem_temp) ? 'true' : 'false') . ", is_uploaded_file: " . (is_uploaded_file($imagem_temp) ? 'true' : 'false') . ")");
    error_log("DIAGNOSTICO: Dest path: " . $caminho . " (is_writable: " . (is_writable(dirname($caminho)) ? 'true' : 'false') . ")");

    if (in_array($ext, $extensoes_permitidas)) {
        if ($contracheque != "sem-foto.png" && file_exists($documentos_renda_dir . $contracheque)) {
            @unlink($documentos_renda_dir . $contracheque);
        }
        $contracheque = $nome_img;
        if ($ext == 'pdf') {
            if (!move_uploaded_file($imagem_temp, $caminho)) {
                error_log("ERRO: Falha ao mover arquivo de contracheque (PDF). Origem: " . $imagem_temp . ", Destino: " . $caminho);
            }
        } else {
            list($largura, $altura) = getimagesize($imagem_temp);
            if ($largura > 1400) {
                $nova_largura = 1400;
                $nova_altura = ($altura / $largura) * $nova_largura;
                $image = imagecreatetruecolor($nova_largura, $nova_altura);
                if ($ext == 'png') { $imagem_original = imagecreatefrompng($imagem_temp); imagealphablending($image, false); imagesavealpha($image, true); }
                else if ($ext == 'jpeg' || $ext == 'jpg') { $imagem_original = imagecreatefromjpeg($imagem_temp); }
                else if ($ext == 'gif') { $imagem_original = imagecreatefromgif($imagem_temp); }
                else if ($ext == 'webp') { $imagem_original = imagecreatefromwebp($imagem_temp); }
                if ($imagem_original) {
                    imagecopyresampled($image, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);
                    if ($ext == 'png') { imagepng($image, $caminho, 9); }
                    else if ($ext == 'gif') { imagegif($image, $caminho); }
                    else if ($ext == 'webp') { imagewebp($image, $caminho, 80); }
                    else { imagejpeg($image, $caminho, 80); }
                    imagedestroy($imagem_original);
                    imagedestroy($image);
                }
            } else {
                if (!move_uploaded_file($imagem_temp, $caminho)) {
                    error_log("ERRO: Falha ao mover arquivo de contracheque (sem redimensionamento). Origem: " . $imagem_temp . ", Destino: " . $caminho);
                }
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Extensão de arquivo de Contracheque não permitida!']);
        exit();
    }
}


// --- Lógica Condicional para Dados do Ramo ---
// Coleta e atribui os valores dos campos específicos do ramo
if ($ramo === 'uber') {
    $modelo_veiculo = @$_POST['modelo_veiculo'];
    $status_veiculo = @$_POST['status_veiculo'];
    // Formata valor_aluguel
    $valor_aluguel = isset($_POST['valor_aluguel']) ? str_replace(',', '.', str_replace(['R$', '.', ' '], '', $_POST['valor_aluguel'])) : 0;
} else if ($ramo === 'autonomo') {
    $funcao_autonomo = @$_POST['funcao_autonomo'];
    $empresa_autonomo = @$_POST['empresa_autonomo'];
} else if ($ramo === 'assalariado') {
    $funcao_assalariado = @$_POST['funcao_assalariado'];
    $empresa_assalariado = @$_POST['empresa_assalariado'];
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
        data_nasc = '$data_nasc',
        pix = :pix,
        indicacao = :indicacao,
        bairro = :bairro,
        estado = :estado,
        cidade = :cidade,
        cep = :cep,
        pessoa = :pessoa,
        nome_sec = :nome_sec,
        telefone_sec = :telefone_sec,
        endereco_sec = :endereco_sec,
        grupo = :grupo, status = :status,
        comprovante_endereco = '$comprovante_endereco',
        comprovante_rg = '$comprovante_rg',
        telefone2 = :telefone2,
        foto = '$foto',
        status_cliente = '$status_cliente',
        senha_crip = '$senha_crip',
        rg = :rg, ramo = :ramo,
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
        print_perfil_app = '$print_perfil_app',
        print_veiculo_app = '$print_veiculo_app',
        print_ganhos_hoje = '$print_ganhos_hoje',
        print_ganhos_30dias = '$print_ganhos_30dias',
        funcao_autonomo = :funcao_autonomo,
        empresa_autonomo = :empresa_autonomo,
        extrato_90dias = '$extrato_90dias',
        funcao_assalariado = :funcao_assalariado,
        empresa_assalariado = :empresa_assalariado,
        contracheque = '$contracheque',
        valor_desejado = :valor_desejado,
        valor_parcela_desejada = :valor_parcela_desejada
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
        data_nasc = '$data_nasc',
        pix = :pix,
        indicacao = :indicacao,
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
        comprovante_endereco = '$comprovante_endereco',
        comprovante_rg = '$comprovante_rg',
        telefone2 = :telefone2,
        foto = '$foto',
        status_cliente = '$status_cliente',
        rg = :rg, ramo = :ramo,
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
        print_perfil_app = '$print_perfil_app',
        print_veiculo_app = '$print_veiculo_app',
        print_ganhos_hoje = '$print_ganhos_hoje',
        print_ganhos_30dias = '$print_ganhos_30dias',
        funcao_autonomo = :funcao_autonomo,
        empresa_autonomo = :empresa_autonomo,
        extrato_90dias = '$extrato_90dias',
        funcao_assalariado = :funcao_assalariado,
        empresa_assalariado = :empresa_assalariado,
        contracheque = '$contracheque',
        valor_desejado = :valor_desejado,
        valor_parcela_desejada = :valor_parcela_desejada
        where id = '$id'
    ");
}

// Binda os parâmetros para a query
$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":cpf", "$cpf");
$query->bindValue(":pix", "$pix");
$query->bindValue(":indicacao", "$indicacao");
$query->bindValue(":bairro", "$bairro");
$query->bindValue(":cidade", "$cidade");
$query->bindValue(":estado", "$estado");
$query->bindValue(":cep", "$cep");
$query->bindValue(":pessoa", "$pessoa");

$query->bindValue(":nome_sec", "$nome_sec");
$query->bindValue(":telefone_sec", "$telefone_sec");
$query->bindValue(":endereco_sec", "$endereco_sec");
$query->bindValue(":grupo", "$grupo");
$query->bindValue(":status", "$status");
$query->bindValue(":telefone2", "$telefone2");

$query->bindValue(":rg", "$rg");
$query->bindValue(":ramo", "$ramo");
$query->bindValue(":quadra", "$quadra");
$query->bindValue(":lote", "$lote");
$query->bindValue(":numero", "$numero");
$query->bindValue(":complemento", "$complemento");
$query->bindValue(":referencia_nome", "$referencia_nome");
$query->bindValue(":referencia_contato", "$referencia_contato");
$query->bindValue(":referencia_parentesco", "$referencia_parentesco");

// Bind de campos específicos do ramo
$query->bindValue(":modelo_veiculo", $modelo_veiculo);
$query->bindValue(":status_veiculo", $status_veiculo);
$query->bindValue(":placa", $placa); // Já mapeado de placa_veiculo

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

$query->execute(); // Executa a query

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
    $stmt_alerta->bindValue(":valor", "$nome");
    $stmt_alerta->bindValue(":cliente_id", $novo_cliente_id);
    $stmt_alerta->execute();
}

echo json_encode(['success' => true, 'message' => 'Salvo com Sucesso']); // Mensagem de sucesso em JSON

// Envio de mensagem para o administrador (se cliente_cadastro for 'Sim' e variáveis de API existirem)
// Certifique-se de que $telefone_sistema, $token, $instancia, $nome_sistema, $url_sistema
// estejam definidos em seu arquivo 'conexao.php' ou similar.
$tel_cliente_admin = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_sistema);
$telefone_envio_admin = $tel_cliente_admin;

if(isset($cliente_cadastro) && $cliente_cadastro == 'Sim' && isset($token) && $token != "" && isset($instancia) && $instancia != ""){
    $mensagem_admin = '*'.$nome_sistema.'* %0A';
    $mensagem_admin .= '_Novo Cliente Cadastrado_ %0A';
    $mensagem_admin .= 'Cliente: *'.$nome.'* %0A';
    $mensagem_admin .= 'Telefone: *'.$telefone.'* %0A%0A';
    // Descomente a linha abaixo quando o arquivo 'apis/texto.php' estiver pronto e configurado
    // require('../../apis/texto.php');
}

// Envio de mensagem para o cliente (se variáveis de API existirem)
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

    // Descomente a linha abaixo quando o arquivo 'apis/texto.php' estiver pronto e configurado
    // require('../../apis/texto.php');
}
?>
