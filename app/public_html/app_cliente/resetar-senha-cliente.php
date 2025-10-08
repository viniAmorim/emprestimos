<?php
@session_start(); // Garanta que a sessão está ativa
// Caminho para 'conexao.php' (um nível acima de 'app_cliente/')
require_once("../conexao.php");

// 1. VERIFICAÇÃO INICIAL DE SEGURANÇA
// Variáveis de Configuração (Necessárias para o título e redirecionamento)
// Assumindo que essas variáveis estão em ‘conexao.php’ ou em um arquivo de config incluído por ele.
$nome_sistema = 'Ucred'; // Exemplo, ajuste conforme sua variável real
$url_sistema = 'index.php'; // Redireciona para o login do cliente após falha/sucesso

if (!isset($_REQUEST['email']) || !isset($_REQUEST['token'])) {
// Redireciona se os parâmetros da URL estiverem faltando
header('location: ' . $url_sistema);
exit;
}

// 2. VERIFICAÇÃO DE TOKEN NA TABELA 'CLIENTES'
$email_req = @$_REQUEST['email'];
$token_req = @$_REQUEST['token'];

// ATENÇÃO: Verificando na tabela 'clientes'
$statement = $pdo->prepare("SELECT id FROM clientes WHERE email=? AND token=?");
$statement->execute([$email_req, $token_req]);
$tot = $statement->rowCount();

if ($tot == 0) {
// Token inválido, expirado, ou cliente não existe
header('location: ' . $url_sistema); // Redireciona para a página inicial
exit;
}

// Se o token for válido, a página continua a carregar o formulário.
?>
<!DOCTYPE HTML>
<html lang="pt-BR">

<head>
 <meta charset="utf-8">
 <link rel="icon" type="image/png" href="../img/icone.png" sizes="32x32">
 <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, minimal-ui">
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
 <meta name="viewport"
  content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
 <title><?php echo $nome_sistema ?> - Redefinir Senha</title>
 
  <link rel="stylesheet" type="text/css" href="../painel/styles/bootstrap.css">
 <link rel="stylesheet" type="text/css" href="../painel/fonts/bootstrap-icons.css">
 <link rel="stylesheet" type="text/css" href="../painel/styles/style.css">
 
 <link rel="preconnect" href="https://fonts.gstatic.com">
 <link
  href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700;800&family=Roboto:wght@400;500;700&display=swap"
  rel="stylesheet">
 <link rel="manifest" href="_manifest.json">
 <meta id="theme-check" name="theme-color" content="#FFFFFF">
 <link rel="apple-touch-icon" sizes="180x180" href="../app/icons/icon-192x192.png">
 <link rel="stylesheet" href="../painel/css/swiper.css">
 <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,900" rel="stylesheet">
 <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
 <link rel="icon" type="image/png" href="../img/icone.png" sizes="32x32">

 <style>
  /* Você pode manter o estilo show-password-toggle aqui, se for útil para o formulário de reset */
  .show-password-toggle {
  position: absolute;
  right: 15px; 
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  z-index: 10;
  }
 </style>
</head>


<body class="theme-light">

 <div id="preloader">
  <div class="spinner-border color-highlight" role="status"></div>
 </div>

  <div id="page">

  <div class="page-content pb-0 mb-0">
  <div class="card card-style m-0 bg-transparent shadow-0 bg-10 rounded-0" data-card-height="cover">
   <div class="card-center">
   <div class="card card-style">
    <div class="content">
        <h2 class="text-center font-800 font-30 mb-2"><br><img src="../img/logo_ucred.png" width="50%"></h2><br>
    <h3 class="text-center">Definir Nova Senha</h3>


    <form method="post" id="form-recuperar">
     <div class="form-custom form-label form-icon mb-3">
      <i class="bi bi-lock-fill font-12"></i>
      <input type="password" name="senha" value="" id="senha" class="form-control rounded-xs" 
       placeholder="Digite uma Nova Senha" required />
      <label class="color-theme">Nova Senha</label>
            <i class="bi bi-eye-slash-fill font-12 show-password-toggle" id="togglePassword"></i>
     </div>

     <div class="form-custom form-label form-icon mb-3">
      <i class="bi bi-lock-fill font-12"></i>
      <input type="password" name="re_senha" value="" id="re_senha" class="form-control rounded-xs" 
       placeholder="Repita Sua Nova Senha" required />
      <label class="color-theme">Repetir Senha</label>
     </div>


            <input type="hidden" name="token" id="token" value="<?php echo htmlspecialchars($token_req) ?>">
      <input type="hidden" name="email" id="email" value="<?php echo htmlspecialchars($email_req) ?>">


      <button class="btn btn-full gradient-green rounded-xs text-uppercase font-700 w-100 btn-s mt-4 mb-2"
      type="submit">ALTERAR SENHA
      </button>
      
      <div class="d-flex justify-content-center w-100 mb-1">
      <a href="index.php" class="color-theme opacity-30 font-12">
       <div>Voltar ao Login</div>
      </a>
      </div>
    </form>
    </div>
   </div>
   </div>
  </div>
  </div>
 </div>

   <script src="../painel/scripts/bootstrap.min.js"></script>
 <script src="../painel/scripts/custom.js"></script>
 <script src="../painel/js/jquery-3.3.1.min.js"></script>
 <script src="../painel/js/jquery.validate.min.js"></script>
 <script src="../painel/js/swiper.min.js"></script>
 <script src="../painel/js/jquery.custom.js"></script>
 <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

 <script src="_service-worker.js"></script>
 <script src="../painel/js/base.js"></script>
 <script type="text/javascript" src="../painel/js/mascaras.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script> 

 <?php require_once("alertas.php"); ?>


 <script type="text/javascript">
  $("#form-recuperar").submit(function (e) {
  e.preventDefault();
  var formData = new FormData(this);

  $.ajax({
   url: "alterar-senha-cliente.php",
   type: 'POST',
   data: formData,
   success: function (mensagem) {
    console.log("Retorno do PHP (Alterar Senha): [" + mensagem.trim() + "]"); 
    if (mensagem.trim() == "Senha alterada com Sucesso") {
     $('#senha').val('');
     $('#re_senha').val('');
     $('#btn_sucess_rec_senha').click(); // Alerta de sucesso
     // Redireciona para o index após sucesso
     setTimeout(function(){
      window.location.href = "index.php"; 
     }, 1500); // Dá tempo para o alerta aparecer
    } else {
     // Exibe a mensagem de erro que vem do PHP
     alert(mensagem.trim()); 
     // Se você tiver um botão de alerta de erro, use-o aqui
     // $('#btn_senha_incorreta').click();
    }
   },
   cache: false,
   contentType: false,
   processData: false,
  });
  });

  // Script de toggle de senha
  const togglePassword = document.querySelector('#togglePassword');
  const password = document.querySelector('#senha'); // Altere para o ID correto da nova senha

  togglePassword.addEventListener('click', function (e) {
  const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
  password.setAttribute('type', type);
  
  this.classList.toggle('bi-eye-fill');
  this.classList.toggle('bi-eye-slash-fill');
  });
 </script>


</body>
</html>