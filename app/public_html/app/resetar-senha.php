<?php
@session_start();
// Caminho para 'conexao.php' (um nível acima de onde 'index.php' de gestão está, se index estiver em /app)
require_once("../conexao.php");

// 1. VERIFICAÇÃO INICIAL DE SEGURANÇA
// Assumindo que $url_sistema é definida em conexao.php ou config.
$url_sistema = 'index.php'; // Redireciona para o login de gestão

if (!isset($_REQUEST['email']) || !isset($_REQUEST['token'])) {
    header('location: ' . $url_sistema);
    exit;
}

// 2. VERIFICAÇÃO DE TOKEN NA TABELA 'USUARIOS'
$email_req = @$_REQUEST['email'];
$token_req = @$_REQUEST['token'];

$statement = $pdo->prepare("SELECT id FROM usuarios WHERE email=? AND token=?");
$statement->execute([$email_req, $token_req]);
$tot = $statement->rowCount();

if ($tot == 0) {
    // Token inválido, expirado, ou usuário não existe
    header('location: ' . $url_sistema);
    exit;
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
    <title>Redefinir Senha - <?php echo $nome_sistema ?></title>
    
    <link rel="stylesheet" type="text/css" href="painel/styles/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="painel/fonts/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="painel/styles/style.css">
    <link rel="stylesheet" href="painel/css/swiper.css">
    
    <link rel="icon" type="image/png" href="../img/icone.png" sizes="32x32"> 
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
                            <h3 class="text-center">Definir Nova Senha de Gestão</h3>

                            <form method="post" id="form-recuperar">
                                <div class="form-custom form-label form-icon mb-3">
                                    <i class="bi bi-lock-fill font-12"></i>
                                    <input type="password" name="senha" value="" id="senha" class="form-control rounded-xs" 
                                        placeholder="Digite uma Nova Senha" required />
                                    <label class="color-theme">Nova Senha</label>
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
    
    <script src="painel/scripts/bootstrap.min.js"></script>
    <script src="painel/scripts/custom.js"></script>
    <script src="painel/js/jquery-3.3.1.min.js"></script>
    <script src="painel/js/jquery.validate.min.js"></script>
    <script src="painel/js/swiper.min.js"></script>
    <script src="painel/js/jquery.custom.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script src="_service-worker.js"></script>
    <script src="painel/js/base.js"></script>
    
    <?php require_once("alertas.php"); ?>

    <script type="text/javascript">
    $("#form-recuperar").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            // Endpoint para alterar a senha de USUÁRIO (GESTÃO)
            url: "alterar-senha.php", 
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
                    }, 1500); 
                } else {
                    // Exibe a mensagem de erro que vem do PHP
                    alert(mensagem.trim()); 
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