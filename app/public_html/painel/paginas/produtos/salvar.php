<?php
require_once('../../../conexao.php');

$id = $_POST['id'] ?? '';
$titulo = $_POST['titulo'] ?? '';
$valor = $_POST['valor'] ?? '';
$descricao = $_POST['descricao'] ?? '';

// Limpa e formata o valor
$valor = str_replace(',', '.', str_replace('.', '', $valor)); // Remove pontos e troca vírgula por ponto para o DB

if ($titulo == '') {
    echo 'O título é obrigatório!';
    exit();
}

// Verifica se o título já existe (exceto para o próprio item que está sendo editado)
$query_verificar = $pdo->prepare("SELECT * FROM produtos_emprestimos WHERE titulo = :titulo AND id != :id");
$query_verificar->bindValue(":titulo", $titulo);
$query_verificar->bindValue(":id", $id);
$query_verificar->execute();
$res_verificar = $query_verificar->fetchAll(PDO::FETCH_ASSOC);

if (@count($res_verificar) > 0) {
    echo 'Título já cadastrado!';
    exit();
}

if ($id == '') {
    // Inserir novo produto
    $query = $pdo->prepare("INSERT INTO produtos_emprestimos (titulo, valor, descricao) VALUES (:titulo, :valor, :descricao)");
    $query->bindValue(":titulo", $titulo);
    $query->bindValue(":valor", $valor);
    $query->bindValue(":descricao", $descricao);
    $query->execute();
    echo 'Salvo com Sucesso';
} else {
    // Atualizar produto existente
    $query = $pdo->prepare("UPDATE produtos_emprestimos SET titulo = :titulo, valor = :valor, descricao = :descricao WHERE id = :id");
    $query->bindValue(":titulo", $titulo);
    $query->bindValue(":valor", $valor);
    $query->bindValue(":descricao", $descricao);
    $query->bindValue(":id", $id);
    $query->execute();
    echo 'Salvo com Sucesso';
}
?>