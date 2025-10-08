<?php
@session_start(); // Garanta que a sessão está ativa
require_once("../conexao.php");

// 1. VERIFICAÇÃO INICIAL DE SEGURANÇA
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
 header('location: ' . $url_sistema);
 exit;
}

// Se o token for válido, os valores da URL serão passados para o formulário.
?>
<!DOCTYPE HTML>
<html lang="en">

<head>
 </head>

<body class="theme-light">

 <div id="page">
  <div class="page-content pb-0 mb-0">
   <div class="card card-style m-0 bg-transparent shadow-0 bg-10 rounded-0" data-card-height="cover">
    <div class="card-center">
     <div class="card card-style">
      <div class="content">
       <h2 class="text-center font-800 font-30 mb-2"><br><img src="../img/logo.png" width="70%"></h2><br>


       <form method="post" id="form-recuperar">
        <div class="form-custom form-label form-icon mb-3">
         <i class="bi bi-lock font-12"></i>
         <input type="password" name="senha" value="" id="senha" class="form-control rounded-xs" 
          placeholder="Digite uma Nova Senha" required />
         <label class="color-theme">Nova Senha</label>
         <span>(Obrigatório)</span>
        </div>

        <div class="form-custom form-label form-icon mb-3">
         <i class="bi bi-lock font-12"></i>
         <input type="password" name="re_senha" value="" id="re_senha" class="form-control rounded-xs" 
          placeholder="Repita Sua Nova Senha" required />
         <label class="color-theme">Repitir Senha</label>
         <span>(Obrigatório)</span>
        </div>


                <input type="hidden" name="token" id="token" value="<?php echo htmlspecialchars($token_req) ?>">

        <input type="hidden" name="email" id="email" value="<?php echo htmlspecialchars($email_req) ?>">



        <button class="btn btn-full gradient-blue rounded-xs text-uppercase font-700 w-100 btn-s mt-4 mb-2"
         type="submit">ALTERAR SENHA</button><br>

       </form>

      </div>
     </div>
    </div>
   </div>

  </div>
 </div>
 
 
  <script type="text/javascript">
  $("#form-recuperar").submit(function (e) {
  e.preventDefault();
  var formData = new FormData(this);

  $.ajax({
   url: "alterar-senha-cliente.php",
   type: 'POST',
   data: formData,
   success: function (mensagem) {
    if (mensagem.trim() == "Senha alterada com Sucesso") {
     $('#senha').val('');
     $('#re_senha').val('');
     $('#btn_sucess_rec_senha').click();
     // Redireciona para o index após sucesso
     setTimeout(function(){
      window.location.href = "index.php"; 
     }, 1500); // Dá tempo para o alerta aparecer
    } else {
     // Exibe a mensagem de erro que vem do PHP
     alert(mensagem.trim()); // Exibe a mensagem de erro real
     //$('#btn_senha_incorreta').click(); // Você pode usar um modal aqui
    }
   },
   cache: false,
   contentType: false,
   processData: false,
  });
  });
 </script>

</body>
</html>