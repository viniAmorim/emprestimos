<?php 
$tabela = 'config';
require_once("../conexao.php");

if($modo_teste == 'Sim'){
	echo 'Em modo de teste esse recurso fica desabilitado!';
	exit();
}

$nome = $_POST['nome_sistema'];
$email = $_POST['email_sistema'];
$telefone = $_POST['telefone_sistema'];
$endereco = $_POST['endereco_sistema'];
$juros = $_POST['juros_sistema'];
$juros = str_replace(',', '.', $juros);
$multa = $_POST['multa_sistema'];
$multa = str_replace(',', '.', $multa);
$juros_emp = $_POST['juros_emprestimo'];
$taxa_sistema = $_POST['taxa_sistema'];
$instancia = $_POST['instancia'];
$token = $_POST['token'];
$dias_aviso = $_POST['dias_aviso'];
$cnpj_sistema = $_POST['cnpj_sistema'];
$marca_dagua = $_POST['marca_dagua'];
$dias_criar_parcelas = $_POST['dias_criar_parcelas'];
$pix_sistema = $_POST['pix_sistema'];
$saldo_inicial = $_POST['saldo_inicial'];
$verificar_pagamentos = 'Não';
$seletor_api = $_POST['seletor_api'];
$recursos = $_POST['recursos'];
$cobrar_automatico = $_POST['cobrar_automatico'];
$public_key = $_POST['public_key'];
$access_token = $_POST['access_token'];
$entrada_sistema = $_POST['entrada_sistema'];

$query = $pdo->query("SELECT * from config");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$foto_assinatura = @$res[0]['assinatura'];

//foto logo
$caminho = '../img/logo.png';
$imagem_temp = @$_FILES['foto-logo']['tmp_name']; 

if(@$_FILES['foto-logo']['name'] != ""){
	$ext = pathinfo($_FILES['foto-logo']['name'], PATHINFO_EXTENSION);   
	if($ext == 'png'){ 	
				
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


//foto logo rel
$caminho = '../img/logo.jpg';
$imagem_temp = @$_FILES['foto-logo-rel']['tmp_name']; 

if(@$_FILES['foto-logo-rel']['name'] != ""){
	$ext = pathinfo(@$_FILES['foto-logo-rel']['name'], PATHINFO_EXTENSION);   
	if($ext == 'jpg'){ 	
			
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


//foto icone
$caminho = '../img/icone.png';
$imagem_temp = @$_FILES['foto-icone']['tmp_name']; 

if(@$_FILES['foto-icone']['name'] != ""){
	$ext = pathinfo(@$_FILES['foto-icone']['name'], PATHINFO_EXTENSION);   
	if($ext == 'png' || $ext == 'PNG'){ 	
			
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


//foto assinatura
$caminho = '../img/assinatura.jpg';
$imagem_temp = @$_FILES['foto-assinatura']['tmp_name']; 

if(@$_FILES['foto-assinatura']['name'] != ""){
	$ext = pathinfo(@$_FILES['foto-assinatura']['name'], PATHINFO_EXTENSION);   
	if($ext == 'jpg' || $ext == 'JPG'){ 	
		$foto_assinatura = 'assinatura.jpg';	
		move_uploaded_file($imagem_temp, $caminho);

	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}



//foto fundo login
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['fundo_login']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);
$caminho = '../img/'.$nome_img;
$imagem_temp = @$_FILES['fundo_login']['tmp_name']; 

if(@$_FILES['fundo_login']['name'] != ""){
	$ext = pathinfo(@$_FILES['fundo_login']['name'], PATHINFO_EXTENSION);   
	if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'JPG' || $ext == 'png' || $ext == 'PNG'|| $ext == 'gif' || $ext == 'GIF' || $ext == 'webp' || $ext == 'WEBP'){			
		move_uploaded_file($imagem_temp, $caminho);
		$fundo_login = $nome_img;

		$query = $pdo->query("SELECT * FROM config");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$fundo_login_antigo = @$res[0]['fundo_login'];

		if($fundo_login_antigo != "sem-foto.png"){
			@unlink('../img/'.$fundo_login_antigo);
		} 

	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}



//foto logo site
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['logo_site']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);
$caminho = '../img/'.$nome_img;
$imagem_temp = @$_FILES['logo_site']['tmp_name']; 

if(@$_FILES['logo_site']['name'] != ""){
	$ext = pathinfo(@$_FILES['logo_site']['name'], PATHINFO_EXTENSION);   
	if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'JPG' || $ext == 'png' || $ext == 'PNG'|| $ext == 'gif' || $ext == 'GIF' || $ext == 'webp' || $ext == 'WEBP'){			
		move_uploaded_file($imagem_temp, $caminho);
		$logo_site = $nome_img;

		$query = $pdo->query("SELECT * FROM config");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$logo_site_antigo = @$res[0]['logo_site'];

		if($logo_site_antigo != "sem-foto.png"){
			@unlink('../img/'.$logo_site_antigo);
		} 

	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco, juros = :juros_sistema, multa = :multa_sistema, juros_emp = :juros_emp, taxa_sistema = :taxa_sistema, instancia = :instancia, token = :token, dias_aviso = :dias_aviso, cnpj = :cnpj_sistema, marca_dagua = '$marca_dagua', dias_criar_parcelas = '$dias_criar_parcelas', pix_sistema = :pix_sistema, saldo_inicial = :saldo_inicial, verificar_pagamentos = :verificar_pagamentos, seletor_api = '$seletor_api', assinatura = '$foto_assinatura', recursos = :recursos, cobrar_automatico = :cobrar_automatico, public_key = :public_key, access_token = :access_token, entrada_sistema = :entrada_sistema, fundo_login = '$fundo_login', logo_site = '$logo_site' where id = 1");

$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":juros_sistema", "$juros");
$query->bindValue(":multa_sistema", "$multa");
$query->bindValue(":juros_emp", "$juros_emp");
$query->bindValue(":taxa_sistema", "$taxa_sistema");
$query->bindValue(":instancia", "$instancia");
$query->bindValue(":token", "$token");
$query->bindValue(":dias_aviso", "$dias_aviso");
$query->bindValue(":cnpj_sistema", "$cnpj_sistema");
$query->bindValue(":pix_sistema", "$pix_sistema");
$query->bindValue(":saldo_inicial", "$saldo_inicial");
$query->bindValue(":verificar_pagamentos", "$verificar_pagamentos");
$query->bindValue(":recursos", "$recursos");
$query->bindValue(":cobrar_automatico", "$cobrar_automatico");
$query->bindValue(":public_key", "$public_key");
$query->bindValue(":access_token", "$access_token");
$query->bindValue(":entrada_sistema", "$entrada_sistema");
$query->execute();

echo 'Editado com Sucesso';
 ?>