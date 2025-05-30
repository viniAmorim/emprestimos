<?php 
require_once("conexao.php");

 ?>
 <!DOCTYPE html>
<html>
<head>
	<title><?php echo $nome_sistema ?></title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/x-icon" href="img/icone.png">

</head>
	<?php 
if($fundo_login != "" and $fundo_login != "sem-foto.png"){ ?>
<body style="background: url('img/<?php echo $fundo_login ?>') no-repeat center center fixed; background-size: cover;">
<?php }else{ ?>
<body >
<?php } ?>
	<div class="login">		
		<div class="form">
			<img src="img/logo.png" class="imagem">
			<form method="post" action="autenticar_cliente.php">
				<input type="text" id="cpf" name="cpf" placeholder="Seu CPF" required>
				<input type="password" name="senha" placeholder="Senha (123) se você não se cadastrou" required>
				<button>Entrar</button>
			</form>	
			<p align="center"><a href="cadastro" ><button style="background:#858585">Cadastre-se</button></a></p>
		</div>
	</div>
</body>
</html>



<script src="painel/js/jquery-1.11.1.min.js"></script>
	<!-- Mascaras JS -->
<script type="text/javascript" src="painel/js/mascaras.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script> 
