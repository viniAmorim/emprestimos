<?php 
@session_start();
unset($_SESSION['usuario_logado_pagina']);
$_SESSION['usuario_logado_pagina'] = true;
require_once("conexao.php");
$query = $pdo->query("SELECT * from usuarios where nivel = 'Administrador'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
$senha = '123';
$senha_crip = password_hash($senha, PASSWORD_DEFAULT);
if($linhas == 0){
	$pdo->query("INSERT INTO usuarios SET nome = '$nome_sistema', email = '$email_sistema', senha = '', senha_crip = '$senha_crip', nivel = 'Administrador', ativo = 'Sim', foto = 'sem-foto.jpg', telefone = '$telefone_sistema', data = curDate() ");
}

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
			<a href="./"><img src="img/logo.png" class="imagem" title="Ver Site"></a>
			<form method="post" action="autenticar.php" >
				<input type="email" name="usuario" placeholder="Seu Email" required value="<?php if($modo_teste == 'Sim'){ echo $email_sistema; } ?>">
				<input type="password" name="senha" placeholder="Senha" value="<?php if($modo_teste == 'Sim'){ echo '123'; } ?>" required>
			
<div class="form-group" style="display: flex; align-items: center; margin-bottom: 10px">
  <input 
    type="checkbox" 
    value="Sim" 
    name="salvar" 
    id="salvar_acesso"
    style="margin: 0 4px 0 0; padding: 0; width: auto; height: auto;"
  />
  <label for="salvar_acesso" class="control-label" style="margin: 0; color:#474747; font-size: 12px">Salvar Senha</label>
</div>



				<button>Login</button>
			</form>	
						
		</div>
	</div>
</body>
</html>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.querySelector('input[name="usuario"]');
    const senhaInput = document.querySelector('input[name="senha"]');
    const checkbox = document.querySelector('#salvar_acesso');

    // Preencher os campos se dados existirem no localStorage
    const savedEmail = localStorage.getItem('email_salvo');
    const savedSenha = localStorage.getItem('senha_salva');

    if (savedEmail) emailInput.value = savedEmail;
    if (savedSenha) senhaInput.value = savedSenha;
    if (savedEmail || savedSenha) checkbox.checked = true;

    // Ao submeter o formulário, salvar ou limpar os dados
    const form = emailInput.closest('form');
    form.addEventListener('submit', function () {
      if (checkbox.checked) {
        localStorage.setItem('email_salvo', emailInput.value);
        localStorage.setItem('senha_salva', senhaInput.value);
      } else {
        localStorage.removeItem('email_salvo');
        localStorage.removeItem('senha_salva');
      }
    });
  });
</script>
