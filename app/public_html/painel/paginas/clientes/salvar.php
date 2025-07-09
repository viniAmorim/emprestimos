<?php
$tabela = 'clientes';
require_once("../../../conexao.php"); // Inclui o arquivo de conexão com o banco de dados

// Coleta e sanitiza os dados do POST
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
$numero = @$_POST['numero']; // Adicionado @ para evitar notice se não vier no POST
$complemento = @$_POST['complemento'];
$referencia_nome = @$_POST['referencia_nome'];
$referencia_contato = @$_POST['referencia_contato'];
$referencia_parentesco = @$_POST['referencia_parentesco'];
$modelo_veiculo = @$_POST['modelo_veiculo'];
$status_veiculo = @$_POST['status_veiculo'];
$placa = @$_POST['placa'];

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
$valor_parcela_desejada = isset($_POST['valor_parcela_desejada']) ? str_replace(',', '.', str_replace(['R$', '.', ' '], '', $_POST['valor_parcela_desejada'])) : 0;

// Validação de senhas para novo cadastro de cliente
if($cliente_cadastro == "Sim"){
    if($senha != $conf_senha){
        echo 'As senhas não são iguais!';
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
        echo 'CPF já Cadastrado!';
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
        echo 'Telefone já Cadastrado!';
        error_log("ALERTA ADMINISTRADOR: Tentativa de cadastro/edição com Telefone duplicado: " . $telefone . " para o ID: " . $id);
        exit(); // Impede o cadastro
    }
}

// Inicializa variáveis de imagem para evitar erros se não houver upload ou se for um novo registro
$comprovante_endereco = 'sem-foto.png';
$comprovante_rg = 'sem-foto.png';
$foto = 'sem-foto.jpg';

// Busca os nomes de arquivo existentes no banco de dados se for uma edição
$query = $pdo->query("SELECT comprovante_endereco, comprovante_rg, foto FROM $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($res) > 0) {
    $comprovante_endereco = $res[0]['comprovante_endereco'];
    $comprovante_rg = $res[0]['comprovante_rg'];
    $foto = $res[0]['foto'];
}


// SCRIPT PARA SUBIR COMPROVANTE DE ENDEREÇO
if (isset($_FILES['comprovante_endereco']) && $_FILES['comprovante_endereco']['name'] != "") {
    $nome_img_endereco = date('d-m-Y-H-i-s') . '-' . preg_replace('/[ :]+/', '-', $_FILES['comprovante_endereco']['name']);
    $caminho_endereco = '../../images/comprovantes/' . $nome_img_endereco;
    $imagem_temp_endereco = $_FILES['comprovante_endereco']['tmp_name'];
    $ext_endereco = strtolower(pathinfo($nome_img_endereco, PATHINFO_EXTENSION));
    $extensoes_permitidas = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'rar', 'zip', 'doc', 'docx', 'webp', 'xlsx', 'xlsm', 'xls', 'xml'];

    if (in_array($ext_endereco, $extensoes_permitidas)) {
        // Exclui a foto anterior se não for a padrão
        if ($comprovante_endereco != "sem-foto.png" && file_exists('../../images/comprovantes/' . $comprovante_endereco)) {
            @unlink('../../images/comprovantes/' . $comprovante_endereco);
        }
        $comprovante_endereco = $nome_img_endereco; // Atualiza o nome do arquivo

        // Move o arquivo ou redimensiona a imagem
        if (in_array($ext_endereco, ['pdf', 'rar', 'zip', 'doc', 'docx', 'xlsx', 'xlsm', 'xls', 'xml'])) {
            move_uploaded_file($imagem_temp_endereco, $caminho_endereco);
        } else {
            list($largura, $altura) = getimagesize($imagem_temp_endereco);
            if ($largura > 1400) {
                $nova_largura = 1400;
                $nova_altura = ($altura / $largura) * $nova_largura;
                $image = imagecreatetruecolor($nova_largura, $nova_altura);

                // Cria a imagem a partir do tipo de arquivo
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
                } else {
                    move_uploaded_file($imagem_temp_endereco, $caminho_endereco);
                    $imagem_original = null;
                }

                if ($imagem_original) {
                    imagecopyresampled($image, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);
                    imagejpeg($image, $caminho_endereco, 20); // Salva com qualidade de 20%
                    imagedestroy($imagem_original);
                    imagedestroy($image);
                }
            } else {
                move_uploaded_file($imagem_temp_endereco, $caminho_endereco);
            }
        }
    } else {
        echo 'Extensão de arquivo de comprovante de endereço não permitida!';
        exit();
    }
}


// SCRIPT PARA SUBIR COMPROVANTE DE RG/CNH
if (isset($_FILES['comprovante_rg']) && $_FILES['comprovante_rg']['name'] != "") {
    $nome_img_rg = date('d-m-Y-H-i-s') . '-' . preg_replace('/[ :]+/', '-', $_FILES['comprovante_rg']['name']);
    $caminho_rg = '../../images/comprovantes/' . $nome_img_rg;
    $imagem_temp_rg = $_FILES['comprovante_rg']['tmp_name'];
    $ext_rg = strtolower(pathinfo($nome_img_rg, PATHINFO_EXTENSION));
    $extensoes_permitidas = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'rar', 'zip', 'doc', 'docx', 'webp', 'xlsx', 'xlsm', 'xls', 'xml'];

    if (in_array($ext_rg, $extensoes_permitidas)) {
        // Exclui a foto anterior se não for a padrão
        if ($comprovante_rg != "sem-foto.png" && file_exists('../../images/comprovantes/' . $comprovante_rg)) {
            @unlink('../../images/comprovantes/' . $comprovante_rg);
        }
        $comprovante_rg = $nome_img_rg; // Atualiza o nome do arquivo

        // Move o arquivo ou redimensiona a imagem
        if (in_array($ext_rg, ['pdf', 'rar', 'zip', 'doc', 'docx', 'xlsx', 'xlsm', 'xls', 'xml'])) {
            move_uploaded_file($imagem_temp_rg, $caminho_rg);
        } else {
            list($largura, $altura) = getimagesize($imagem_temp_rg);
            if ($largura > 1400) {
                $nova_largura = 1400;
                $nova_altura = ($altura / $largura) * $nova_largura;
                $image = imagecreatetruecolor($nova_largura, $nova_altura);

                // Cria a imagem a partir do tipo de arquivo
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
                    move_uploaded_file($imagem_temp_rg, $caminho_rg);
                    $imagem_original = null;
                }

                if ($imagem_original) {
                    imagecopyresampled($image, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);
                    imagejpeg($image, $caminho_rg, 20); // Salva com qualidade de 20%
                    imagedestroy($imagem_original);
                    imagedestroy($image);
                }
            } else {
                move_uploaded_file($imagem_temp_rg, $caminho_rg);
            }
        }
    } else {
        echo 'Extensão de arquivo de comprovante RG/CNH não permitida!';
        exit();
    }
}

// SCRIPT PARA SUBIR FOTO DO USUÁRIO
if (isset($_FILES['foto']) && $_FILES['foto']['tmp_name'] != "") {
    $nome_original_foto = $_FILES['foto']['name'];
    $ext_foto = strtolower(pathinfo($nome_original_foto, PATHINFO_EXTENSION));
    $extensoes_permitidas_foto = ['png', 'jpg', 'jpeg', 'gif', 'webp'];

    if (!in_array($ext_foto, $extensoes_permitidas_foto)) {
        echo 'Extensão de imagem de usuário não permitida!';
        exit();
    }

    $nome_img_foto = date('Y-m-d_H-i-s') . '.' . $ext_foto;
    $caminho_foto = '../../images/clientes/' . $nome_img_foto;
    $imagem_temp_foto = $_FILES['foto']['tmp_name'];

    // Exclui foto anterior se não for padrão
    if ($foto != 'sem-foto.jpg' && file_exists('../../images/clientes/' . $foto)) {
        @unlink('../../images/clientes/' . $foto);
    }
    $foto = $nome_img_foto; // Atualiza o nome do arquivo

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
                $imagem_original = imagecreatefromgif($imagem_temp_foto);
                break;
            case 'webp':
                $imagem_original = imagecreatefromwebp($imagem_temp_foto);
                break;
            default:
                echo "Formato de imagem não suportado.";
                exit();
        }
        imagecopyresampled($image, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);
        imagejpeg($image, $caminho_foto, 80); // Salva com qualidade de 80%
        imagedestroy($imagem_original);
        imagedestroy($image);
    } else {
        move_uploaded_file($imagem_temp_foto, $caminho_foto);
    }
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
$query->bindValue(":modelo_veiculo", "$modelo_veiculo");
$query->bindValue(":status_veiculo", "$status_veiculo");
$query->bindValue(":placa", "$placa");

$query->bindValue(":valor_desejado", "$valor_desejado");
$query->bindValue(":valor_parcela_desejada", "$valor_parcela_desejada");

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

echo 'Salvo com Sucesso'; // Mensagem de sucesso

// Envio de mensagem para o administrador (se cliente_cadastro for 'Sim' e variáveis de API existirem)
$tel_cliente_admin = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_sistema); // Assume $telefone_sistema é definido em conexao.php
$telefone_envio_admin = $tel_cliente_admin;

if($cliente_cadastro == 'Sim' && isset($token) && $token != "" && isset($instancia) && $instancia != ""){
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

    if($cliente_cadastro == 'Sim'){
        $sua_senha = ' sua senha de cadastro!';
    }else{
        $sua_senha = ' a senha 123';
    }

    $mensagem_user .= 'Use seu CPF e '.$sua_senha.' %0A';
    $mensagem_user .= $url_sistema.'acesso'; // Assume $url_sistema é definido em conexao.php

    // Descomente a linha abaixo quando o arquivo 'apis/texto.php' estiver pronto e configurado
    // require('../../apis/texto.php');
}
?>
