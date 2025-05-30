<?php 
$tabela = 'clientes';
require_once("../../../conexao.php");

$nome = @$_POST['nome'];
$email = @$_POST['email'];
$telefone = @$_POST['telefone'];
$data_nasc = @$_POST['data_nasc'];
$data_nasc = implode('-', array_reverse(explode('/', $data_nasc)));
$endereco = @$_POST['endereco'];
$obs = @$_POST['obs'];
$cpf = @$_POST['cpf'];
$pix = @$_POST['pix'];
$indicacao = @$_POST['indicacao'];
$bairro = @$_POST['bairro'];
$cidade = @$_POST['cidade'];
$estado = @$_POST['estado'];
$cep = @$_POST['cep'];
$id = @$_POST['id'];
$pessoa = @$_POST['pessoa'];
$status = @$_POST['status'];

$nome_sec = @$_POST['nome_sec'];
$telefone_sec = @$_POST['telefone_sec'];
$endereco_sec = @$_POST['endereco_sec'];
$grupo = @$_POST['grupo'];
$dados_emprestimo = @$_POST['dados_emprestimo'];
$cliente_cadastro = @$_POST['cliente_cadastro'];
$telefone2 = @$_POST['telefone2'];
$status_cliente = @$_POST['status_cliente'];

$senha = @$_POST['senha'];
$conf_senha = @$_POST['conf_senha'];

if($cliente_cadastro == "Sim"){
	if($senha != $conf_senha){
		echo 'As senhas não são iguais!';
		exit();
	}
}else{
	$senha = '123';
}
$senha_crip = password_hash($senha, PASSWORD_DEFAULT);

if($cpf != ""){
	//validacao cpf
$query = $pdo->query("SELECT * from $tabela where cpf = '$cpf'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id != $id_reg){
	echo 'CPF já Cadastrado!';
	exit();
}
}


//validacao telefone
$query = $pdo->query("SELECT * from $tabela where telefone = '$telefone'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id != $id_reg){
	echo 'Telefone já Cadastrado!';
	exit();
}





//validar troca da foto
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {
	$comprovante_endereco = $res[0]['comprovante_endereco'];
	$comprovante_rg = $res[0]['comprovante_rg'];
	$foto = $res[0]['foto'];
} else {
	$comprovante_endereco = 'sem-foto.png';
	$comprovante_rg = 'sem-foto.png';
	$foto = 'sem-foto.jpg';
	
}


// SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['comprovante_endereco']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);

$caminho = '../../images/comprovantes/' . $nome_img;

$imagem_temp = @$_FILES['comprovante_endereco']['tmp_name'];

if (@$_FILES['comprovante_endereco']['name'] != "") {
	$ext = strtolower(pathinfo($nome_img, PATHINFO_EXTENSION)); // Converte a extensão para minúsculas
	$extensoes_permitidas = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'rar', 'zip', 'doc', 'docx', 'webp', 'xlsx', 'xlsm', 'xls', 'xml'];

	if (in_array($ext, $extensoes_permitidas)) {

		// EXCLUO A FOTO ANTERIOR
		if ($comprovante_endereco != "sem-foto.png") {
			@unlink('../../images/comprovantes/' . $comprovante_endereco);
		}

		$comprovante_endereco = $nome_img;

		// pegar o tamanho da imagem
		list($largura, $altura) = getimagesize($imagem_temp);

		// Redimensionar a imagem se a largura for maior que 1400
		if ($largura > 1400) {
			// Calcular a nova altura mantendo a proporção
			$nova_largura = 1400;
			$nova_altura = ($altura / $largura) * $nova_largura;

			// Criar uma nova imagem em branco
			$image = imagecreatetruecolor($nova_largura, $nova_altura);

			// Criar a imagem a partir do arquivo original
			if ($ext == 'png') {
				$imagem_original = imagecreatefrompng($imagem_temp);
				imagealphablending($image, false);
				imagesavealpha($image, true);
			} else if ($ext == 'jpeg' || $ext == 'jpg') {
				$imagem_original = imagecreatefromjpeg($imagem_temp);
			} else if ($ext == 'gif') {
				$imagem_original = imagecreatefromgif($imagem_temp);
			} else {
				die("Formato de imagem não suportado.");
			}

			// Redimensionar a imagem
			imagecopyresampled($image, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);

			// Salvar a imagem com qualidade de 20%
			imagejpeg($image, $caminho, 20);
			imagedestroy($imagem_original);
			imagedestroy($image);
		} else {
			// Se a largura não for maior que 1400, apenas move o arquivo
			move_uploaded_file($imagem_temp, $caminho);
		}
	} else {
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}




// SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['comprovante_rg']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);

$caminho = '../../images/comprovantes/' . $nome_img;

$imagem_temp = @$_FILES['comprovante_rg']['tmp_name'];

if (@$_FILES['comprovante_rg']['name'] != "") {
	$ext = strtolower(pathinfo($nome_img, PATHINFO_EXTENSION)); // Converte a extensão para minúsculas
	$extensoes_permitidas = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'rar', 'zip', 'doc', 'docx', 'webp', 'xlsx', 'xlsm', 'xls', 'xml'];

	if (in_array($ext, $extensoes_permitidas)) {

		// EXCLUO A FOTO ANTERIOR
		if ($comprovante_rg != "sem-foto.png") {
			@unlink('../../images/comprovantes/' . $comprovante_rg);
		}

		$comprovante_rg = $nome_img;

		// pegar o tamanho da imagem
		list($largura, $altura) = getimagesize($imagem_temp);

		// Redimensionar a imagem se a largura for maior que 1400
		if ($largura > 1400) {
			// Calcular a nova altura mantendo a proporção
			$nova_largura = 1400;
			$nova_altura = ($altura / $largura) * $nova_largura;

			// Criar uma nova imagem em branco
			$image = imagecreatetruecolor($nova_largura, $nova_altura);

			// Criar a imagem a partir do arquivo original
			if ($ext == 'png') {
				$imagem_original = imagecreatefrompng($imagem_temp);
				imagealphablending($image, false);
				imagesavealpha($image, true);
			} else if ($ext == 'jpeg' || $ext == 'jpg') {
				$imagem_original = imagecreatefromjpeg($imagem_temp);
			} else if ($ext == 'gif') {
				$imagem_original = imagecreatefromgif($imagem_temp);
			} else {
				die("Formato de imagem não suportado.");
			}

			// Redimensionar a imagem
			imagecopyresampled($image, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);

			// Salvar a imagem com qualidade de 20%
			imagejpeg($image, $caminho, 20);
			imagedestroy($imagem_original);
			imagedestroy($image);
		} else {
			// Se a largura não for maior que 1400, apenas move o arquivo
			move_uploaded_file($imagem_temp, $caminho);
		}
	} else {
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}






// SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['foto']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);

$caminho = '../../images/clientes/' . $nome_img;

$imagem_temp = @$_FILES['foto']['tmp_name'];

if (@$_FILES['foto']['name'] != "") {
	$ext = strtolower(pathinfo($nome_img, PATHINFO_EXTENSION)); // Converte a extensão para minúsculas
	$extensoes_permitidas = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'webp'];

	if (in_array($ext, $extensoes_permitidas)) {

		// EXCLUO A FOTO ANTERIOR
		if ($foto != "sem-foto.jpg") {
			@unlink('../../images/clientes/' . $foto);
		}

		$foto = $nome_img;

		// pegar o tamanho da imagem
		list($largura, $altura) = getimagesize($imagem_temp);

		// Redimensionar a imagem se a largura for maior que 1400
		if ($largura > 1400) {
			// Calcular a nova altura mantendo a proporção
			$nova_largura = 1400;
			$nova_altura = ($altura / $largura) * $nova_largura;

			// Criar uma nova imagem em branco
			$image = imagecreatetruecolor($nova_largura, $nova_altura);

			// Criar a imagem a partir do arquivo original
			if ($ext == 'png') {
				$imagem_original = imagecreatefrompng($imagem_temp);
				imagealphablending($image, false);
				imagesavealpha($image, true);
			} else if ($ext == 'jpeg' || $ext == 'jpg') {
				$imagem_original = imagecreatefromjpeg($imagem_temp);
			} else if ($ext == 'gif') {
				$imagem_original = imagecreatefromgif($imagem_temp);
			} else {
				die("Formato de imagem não suportado.");
			}

			// Redimensionar a imagem
			imagecopyresampled($image, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);

			// Salvar a imagem com qualidade de 20%
			imagejpeg($image, $caminho, 20);
			imagedestroy($imagem_original);
			imagedestroy($image);
		} else {
			// Se a largura não for maior que 1400, apenas move o arquivo
			move_uploaded_file($imagem_temp, $caminho);
		}
	} else {
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, data_cad = curDate(), endereco = :endereco, data_nasc = '$data_nasc', obs = :obs, pix = :pix, indicacao = :indicacao, bairro = :bairro, estado = :estado, cidade = :cidade, cep = :cep, pessoa = :pessoa, nome_sec = :nome_sec, telefone_sec = :telefone_sec, endereco_sec = :endereco_sec, grupo = :grupo, status = :status, comprovante_endereco = '$comprovante_endereco', comprovante_rg = '$comprovante_rg', dados_emprestimo = :dados_emprestimo, telefone2 = :telefone2, foto = '$foto', status_cliente = '$status_cliente', senha_crip = '$senha_crip' ");

$query->bindValue(":dados_emprestimo", "$dados_emprestimo");
	
}else{
$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, endereco = :endereco, data_nasc = '$data_nasc', obs = :obs, pix = :pix, indicacao = :indicacao, bairro = :bairro, estado = :estado, cidade = :cidade, cep = :cep, pessoa = :pessoa, nome_sec = :nome_sec, telefone_sec = :telefone_sec, endereco_sec = :endereco_sec, grupo = :grupo, status = :status, comprovante_endereco = '$comprovante_endereco', comprovante_rg = '$comprovante_rg', telefone2 = :telefone2, foto = '$foto', status_cliente = '$status_cliente' where id = '$id'");
}
$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":cpf", "$cpf");
$query->bindValue(":obs", "$obs");
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
$query->execute();

echo 'Salvo com Sucesso';



$tel_cliente = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_sistema);
$telefone_envio = $tel_cliente;

if($cliente_cadastro == 'Sim' and $token != "" and $instancia != ""){
	$mensagem = '*'.$nome_sistema.'* %0A';
	$mensagem .= '_Novo Cliente Cadastrado_ %0A';
	$mensagem .= 'Cliente: *'.$nome.'* %0A';
	$mensagem .= 'Telefone: *'.$telefone.'* %0A%0A';
	$mensagem .= $dados_emprestimo;
	require('../../apis/texto.php');
}



$tel_cliente = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
$telefone_envio = $tel_cliente;

if($token != "" and $instancia != ""){
	$mensagem = '*'.$nome_sistema.'* %0A';
	$mensagem .= '_Você foi cadastrado no Sistema_ %0A';
	$mensagem .= 'Cliente: *'.$nome.'* %0A';
	$mensagem .= '_Acesse seu Painel_ %0A%0A';

	if($cliente_cadastro == 'Sim'){
		$sua_senha = ' sua senha de cadastro!';
	}else{
		$sua_senha = ' a senha 123';
	}

	$mensagem .= 'Use seu CPF e '.$sua_senha.' %0A';
	$mensagem .= $url_sistema.'acesso';

	require('../../apis/texto.php');
}


 ?>