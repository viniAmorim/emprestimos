<?php
@session_set_cookie_params(['httponly' => true]);
@session_start();
@session_regenerate_id(true);
require_once("../conexao.php");

$id_usu = filter_var(@$_POST['id'], @FILTER_SANITIZE_STRING);
$pagina = filter_var(@$_POST['pagina'], @FILTER_SANITIZE_STRING);

// Autenticação automática por localStorage (se o usuário marcou "Salvar Acesso")
if ($id_usu != "") {
    $query = $pdo->prepare("SELECT * from clientes where id = :id");
    $query->bindValue(":id", "$id_usu");
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);

    if (count($res) > 0) {
        $_SESSION['nome'] = $res[0]['nome'];
        $_SESSION['id'] = $res[0]['id'];
        $_SESSION['nivel'] = 'Cliente';
        $_SESSION['aut_token_portalapp'] = 'portalapp2024';

        if ($pagina == "") {
            echo '<script>window.location="painel"</script>';
        } else {
            echo '<script>window.location="painel/' . $pagina . '"</script>';
        }
    } else {
        echo "<script>localStorage.setItem('id_usu', '')</script>";
        echo '<script>window.location="index.php"</script>';
    }
    exit(); // Encerra o script aqui
}

// Autenticação manual por CPF/CNPJ e Senha
$usuario = filter_var(@$_POST['usuario'], @FILTER_SANITIZE_STRING);
$senha = filter_var(@$_POST['senha'], @FILTER_SANITIZE_STRING);
$salvar = filter_var(@$_POST['salvar'], @FILTER_SANITIZE_STRING);

// Verifica na tabela 'clientes' primeiro
$query = $pdo->prepare("SELECT * from clientes where cpf = :cpf order by id asc limit 1");
$query->bindValue(":cpf", "$usuario");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);

// Se não encontrou cliente, verifica na tabela 'usuarios' (administradores)
if (count($res) == 0) {
    $query = $pdo->prepare("SELECT * from usuarios where email = :email");
    $query->bindValue(":email", "$usuario");
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);

    if (count($res) > 0) {
        $nivel = $res[0]['nivel'];
        $senha_crip_db = $res[0]['senha_crip'];

        if (password_verify($senha, $senha_crip_db)) {
            $_SESSION['nome'] = $res[0]['nome'];
            $_SESSION['id'] = $res[0]['id'];
            $_SESSION['nivel'] = $nivel;
            $_SESSION['aut_token_portalapp'] = 'portalapp2024';

            if ($salvar == 'Sim') {
                echo "<script>localStorage.setItem('email_usu', '$usuario')</script>";
                echo "<script>localStorage.setItem('senha_usu', '$senha')</script>";
                echo "<script>localStorage.setItem('id_usu', '{$res[0]['id']}')</script>";
            } else {
                echo "<script>localStorage.setItem('email_usu', '')</script>";
                echo "<script>localStorage.setItem('senha_usu', '')</script>";
                echo "<script>localStorage.setItem('id_usu', '')</script>";
            }

            echo '<script>window.location="painel"</script>';
            exit();
        }
    }
} else { // Se encontrou cliente, continua com a verificação
    $senha_crip_db = $res[0]['senha_crip'];
    if (password_verify($senha, $senha_crip_db)) {
        $_SESSION['nome'] = $res[0]['nome'];
        $_SESSION['id'] = $res[0]['id'];
        $_SESSION['nivel'] = 'Cliente';
        $_SESSION['aut_token_portalapp'] = 'portalapp2024';
        $id = $res[0]['id'];

        if ($salvar == 'Sim') {
            echo "<script>localStorage.setItem('email_usu', '$usuario')</script>";
            echo "<script>localStorage.setItem('senha_usu', '$senha')</script>";
            echo "<script>localStorage.setItem('id_usu', '$id')</script>";
        } else {
            echo "<script>localStorage.setItem('email_usu', '')</script>";
            echo "<script>localStorage.setItem('senha_usu', '')</script>";
            echo "<script>localStorage.setItem('id_usu', '')</script>";
        }

        echo '<script>window.location="painel"</script>';
        exit();
    }
}

// Se chegou aqui, as credenciais são inválidas
$_SESSION['msg'] = 'Dados Incorretos!';
echo '<script>window.location="index.php"</script>';
exit();
?>