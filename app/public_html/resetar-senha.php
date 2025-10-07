<?php
session_start();

$url_sistema = 'https://localhost/'; 
$nome_sistema = 'Ucred'; 

// O arquivo conexao.php deve estar configurado para sua conexão com o banco de dados
require_once("conexao.php");

if (!isset($_REQUEST['email']) || !isset($_REQUEST['token'])) {
  header('location: ' . $url_sistema);
  exit;
}

$email = @$_REQUEST['email'];
$token = @$_REQUEST['token'];

// Prepara e executa a consulta para validar email e token
$statement = $pdo->prepare("SELECT * FROM clientes WHERE email=? AND token=?");
$statement->execute([$email, $token]);
$tot = $statement->rowCount();

if ($tot == 0) {
  header('location: ' . $url_sistema);
  exit;
}

$_SESSION['temp_reset_email'] = $email;
$_SESSION['temp_reset_token'] = $token;
?>

<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $nome_sistema ?> - Redefinir Senha</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'primary': '#c9dde7', // Exemplo de Primary Light Blue
            'primary-dark': '#1E90FF', // Exemplo de Primary Dark Blue
            'accent': '#FFD700', // Exemplo de Amarelo para destaque/botões
          },
          fontFamily: {
            poppins: ['Poppins', 'sans-serif'],
          }
        }
      }
    }
  </script>

  <style>
    .form-input {
      background-color: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
      padding: 0.75rem 1rem;
      border-radius: 0.5rem;
      color: white;
      transition: all 0.2s;
    }
    .form-input::placeholder {
      color: rgba(255, 255, 255, 0.7);
    }
    .form-input:focus {
      outline: none;
      border-color: #FFD700; /* accent color */
      box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.5);
      background-color: rgba(255, 255, 255, 0.15);
    }
    .btn-primary {
      background-color: #FFD700; /* accent color */
      color: #1a1a1a;
      font-weight: 700;
      padding: 0.75rem 1rem;
      border-radius: 0.5rem;
      transition: background-color 0.2s, transform 0.1s;
    }
    .btn-primary:hover {
      background-color: #E6C200;
      transform: translateY(-1px);
    }
  </style>

</head>

<body class="font-poppins bg-gradient-to-br from-primary-dark to-primary text-white min-h-screen flex items-center justify-center p-4">

  <div class="w-full max-w-sm">
    <div class="bg-white/10 backdrop-blur-md p-8 rounded-xl shadow-2xl space-y-6 border border-white/20">

      <div class="text-center">
        <img src="img/logo_ucred.png" alt="Logo Ucred" class="h-24 mx-auto mb-4" />
        <h2 class="text-2xl font-semibold text-white">Redefinir Senha</h2>
      </div>

      <form method="post" id="form-recuperar" class="space-y-4">
        
        <div class="relative">
          <input type="password" name="senha" id="senha" placeholder="Digite uma Nova Senha" class="form-input w-full" required />
          </div>

        <div class="relative">
          <input type="password" name="re_senha" id="re_senha" placeholder="Repita Sua Nova Senha" class="form-input w-full" required />
        </div>

        <input type="hidden" name="token" id="token" value="<?php echo htmlspecialchars($token); ?>">
        <input type="hidden" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>">

        <p id="mensagem-recuperar" class="text-center font-semibold"></p>

        <button class="btn-primary w-full" type="submit">ALTERAR SENHA</button>
        
        <p class="text-center pt-2">
            <a href="index.php" class="text-white hover:text-accent font-semibold text-sm hover:underline">
                Voltar para Login
            </a>
        </p>

      </form>
    </div>
  </div>

  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="text/javascript">
    $("#form-recuperar").submit(function (event) {
      
      event.preventDefault(); 
      $('#mensagem-recuperar').removeClass('text-danger text-success').text("Alterando..."); // Remove classes e define texto

      var formData = new FormData(this);

      $.ajax({
        url: "alterar-senha.php",
        type: 'POST',
        data: formData,
        
        success: function (mensagem) {
          
          $('#mensagem-recuperar').text(''); // Limpa o texto "Alterando..."
          var response = mensagem.trim();
          
          if (response == "Senha alterada com Sucesso") {
            
            $('#senha').val('');
            $('#re_senha').val('');
            
            // Exibe mensagem de sucesso
            $('#mensagem-recuperar').addClass('text-accent').text(response);
            
            // Redireciona após um pequeno delay para dar tempo do usuário ver a mensagem
            setTimeout(function() {
                window.location="index.php"; 
            }, 1500);

          } else {
            // Exibe mensagem de erro
            $('#mensagem-recuperar').addClass('text-red-400').text(response);
            
            // Ações de clique como 'btn_senha_incorreta' foram removidas, use a mensagem na tela.
          }
        },
        
        error: function() {
            $('#mensagem-recuperar').addClass('text-red-400').text('Erro de comunicação com o servidor.');
        },
        
        cache: false,
        contentType: false,
        processData: false,
      });
    });
  </script>
</body>
</html>