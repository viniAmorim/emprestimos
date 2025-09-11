<?php
@session_start();
require_once("../conexao.php");
$mensagem_sessao = @$_SESSION['msg'];


$query = $pdo->query("SELECT * from usuarios where nivel = 'Administrador'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
$senha = '123';
$senha_crip = password_hash($senha, PASSWORD_DEFAULT);
if ($linhas == 0) {
	$pdo->query("INSERT INTO usuarios SET nome = '$nome_sistema', email = '$email_sistema', senha_crip = '$senha_crip', nivel = 'Administrador', ativo = 'Sim', foto = 'sem-foto.jpg', telefone = '$telefone_sistema', data = curDate() ");
}

?>
<!DOCTYPE HTML>
<html lang="pt-BR">

<head>

	<meta charset="utf-8">
	<link rel="icon" type="image/png" href="images/favicon.png" sizes="32x32">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, minimal-ui">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name="viewport"
		content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
	<title><?php echo $nome_sistema ?></title>
	<link rel="stylesheet" type="text/css" href="painel/styles/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="painel/fonts/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="painel/styles/style.css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link
		href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700;800&family=Roboto:wght@400;500;700&display=swap"
		rel="stylesheet">
	<link rel="manifest" href="_manifest.json">
	<meta id="theme-check" name="theme-color" content="#FFFFFF">
	<link rel="apple-touch-icon" sizes="180x180" href="app/icons/icon-192x192.png">
	<link rel="stylesheet" href="painel/css/swiper.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,900" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

	<link rel="icon" type="image/png" href="../img/icone.png" sizes="32x32">

</head>


<body class="theme-light">

	<div id="preloader">
		<div class="spinner-border color-highlight" role="status"></div>
	</div>


<!-- MENU DE INSTALAÇÃO DO APP-->
<div class="offcanvas offcanvas-bottom rounded-m offcanvas-detached" id="menu-install-pwa-ios">
  <div class="content">
    <img src="app/icons/icon-128x128.png" alt="img" width="80" class="rounded-l mx-auto my-4">
    <h1 class="text-center font-800 font-20">Adicionar à tela inicial</h1>
    <p class="boxed-text-xl">
      Instale o Sistema na sua tela inicial e acesse-o como um aplicativo comum. Abra o menu do seu navegador e toque em
      "Adicionar à
      Tela inicial".
    </p>
    <a href="#"
      class="pwa-dismiss close-menu gradient-blue shadow-bg shadow-bg-s btn btn-s btn-full text-uppercase font-700  mt-n2"
      data-bs-dismiss="offcanvas">Mais tarde</a>
  </div>
</div>

<div class="offcanvas offcanvas-bottom rounded-m offcanvas-detached" id="menu-install-pwa-android">
  <div class="content">
    <img src="app/icons/icon-128x128.png" alt="img" width="80" class="rounded-m mx-auto my-4">
    <h1 class="text-center font-700 font-20">Instalar</h1>
    <p class="boxed-text-l">
      Instale o Sistema na sua tela inicial para desfrutar de uma experiência única e nativa.
    </p>
    <a href="#"
      class="pwa-install btn btn-m rounded-s text-uppercase font-900 gradient-highlight shadow-bg shadow-bg-s btn-full">Adicionar
      na Tela Inicical</a><br>
    <a href="#" data-bs-dismiss="offcanvas"
      class="pwa-dismiss close-menu color-theme text-uppercase font-900 opacity-50 font-11 text-center d-block mt-n1">Mais
      tarde</a>
  </div>
</div>


	<div id="page">



		<!-- MODAL RESETAR SENHA-->
		<div class="page-content pb-0 mb-0">
			<div class="card card-style m-0 bg-transparent shadow-0 bg-10 rounded-0" data-card-height="cover">
				<div class="card-center">
					<div class="card card-style">
						<div class="content">
							<h2 class="text-center font-800 font-30 mb-2"><br><img src="../img/logo_ucred.png" width="50%"></h2><br>
							<form method="post" action="autenticar.php">
								<div class="form-custom form-label form-icon mb-3">
									<i class="bi bi-person-circle font-14"></i>
									<input type="text" class="form-control rounded-xs" value="" placeholder="CPF / CNPJ"
										id="email_usu" name="usuario" value="" required onkeyup="verMasc()" />
									<label class="color-theme">CPF</label>
								</div>
								<div class="form-custom form-label form-icon mb-3">
									<i class="bi bi-lock-fill font-12"></i>
									<input type="password" type="text" name="senha" value="" class="form-control rounded-xs" id="c2"
										placeholder="Senha (123) se você não se cadastrou" required />
									<label class="color-theme">Senha</label>
								</div>
								<button class="btn btn-full gradient-green rounded-xs text-uppercase font-700 w-100 btn-s mt-4 mb-2"
									type="submit">Logar
                </button>
                
                <div class="d-flex justify-content-end w-100 mb-1">
                  <a href="../../app/painel/paginas/cadastro.php"
                    class="btn btn-full gradient-blue rounded-xs text-uppercase font-600 w-100 btn-s mt-0 mb-1">
                      Cadastre-se
                  </a>
                </div>
								<a href="#" class="d-flex pb-2" data-trigger-switch="salvar_acesso">
									<div class="align-self-center">
										<h6 class="mb-0 font-12">Salvar Acesso</h6>
									</div>
                
									<div class="ms-auto align-self-center">
										<div class="form-switch android-switch switch-green switch-m">
											<input type="checkbox" value="Sim" class="android-input" id="salvar_acesso" name="salvar">
											<label class="custom-control-label"></label>
										</div>
									</div>
								</a>
                
							</form>

              <div class="d-flex">
								<div>
									<a class="color-theme opacity-30 font-12" href="../app/index.php" data-bs-toggle="offcanvas"
										data-bs-target="#menu-forgot" class="list-group-item">
										<div>Login Gestão</div>
									</a>
								</div>
							</div>
						</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>



		<!-- End of Page Content-->

	</div>
	<!--End of Page ID-->



	<form action="autenticar.php" method="post" style="display:none">
		<input type="text" name="id" id="id_usua">
		<input type="text" name="pagina" id="pagina_salva">
		<button type="submit" id="btn_auto"></button>
	</form>



	<script src="painel/scripts/bootstrap.min.js"></script>
	<script src="painel/scripts/custom.js"></script>
	<script src="painel/js/jquery-3.3.1.min.js"></script>
	<script src="painel/js/jquery.validate.min.js"></script>
	<script src="painel/js/swiper.min.js"></script>
	<script src="painel/js/jquery.custom.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>



<!-- Base Js File -->
<script src="_service-worker.js"></script>
<script src="painel/js/base.js"></script>

</body>

<?php require_once("alertas.php"); ?>

<script type="text/javascript">
	$(document).ready(function () {
		var email_usuario = localStorage.email_usu;
		var senha_usuario = localStorage.senha_usu;
		var id_usuario = localStorage.id_usu;
		var pagina = localStorage.pagina;
		if (id_usuario != "" && id_usuario != undefined) {
			$('#pagina').hide();
			//$('#splash_imagem').show();
			$('#id_usua').val(id_usuario);
			$('#pagina_salva').val(pagina);
			$('#btn_auto').click();
		} else {
			$('#pagina').show();
			//$('#splash_imagem').hide();
			var mensagem_sessao = "<?= $mensagem_sessao ?>";
			if (mensagem_sessao == "Dados Incorretos!") {
				$('#btn_erro').click();
			} else if (mensagem_sessao == "Seu Acesso foi desativado!") {
				$('#btn_alert').click();
			}
		}
		if (email_usuario != "" && email_usuario != undefined) {
			$('#salvar_acesso').prop('checked', true);
		} else {
			$('#salvar_acesso').prop('checked', false);
		}
		$('#usuario').val(email_usuario);
		$('#password-field').val(senha_usuario);
	});
</script>



<script type="text/javascript">
	$("#form-recuperar").submit(function () {
		//$('#mensagem-recuperar').text('Enviando!!');
		$('#submitforgot').hide();
		event.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			url: "recuperar-senha.php",
			type: 'POST',
			data: formData,
			success: function (mensagem) {
				//$('#mensagem-recuperar').text('');
				//$('#mensagem-recuperar').removeClass()
				if (mensagem.trim() == "Recuperado com Sucesso") {
					$('#btn-fechar-rec').click();
					//$('#menu-forgot').modal('hide');
					$('#btn_sucess_sen_env').click();
					$('#email-recuperar').val('');
					//$('#mensagem-recuperar').addClass('text-success')
					//toast('Link para troca de senha no Email ou no seu whatsapp!', 'verde');
				} else {
					$('#btn_email_n_cad').click();
					//$('#mensagem-recuperar').addClass('text-danger')
					//toast(mensagem, 'vermelha');
				}
				$('#submitforgot').show();
			},
			cache: false,
			contentType: false,
			processData: false,
		});
	});
</script>

	<!-- Mascaras JS -->
<script type="text/javascript" src="../painel/js/mascaras.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script> 

<script type="text/javascript">
	$(document).ready( function () {
		verMasc();
	});
	function verMasc(){
		var cpf = $('#email_usu').val();
		if(cpf.length >= 15){
			$('#email_usu').mask('00.000.000/0000-00');
		}else{
			$('#email_usu').mask('000.000.000-000');
		}
	}

  
	
</script>