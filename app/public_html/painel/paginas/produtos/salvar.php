<?php
require_once('../../../conexao.php'); // Certifique-se de que o caminho para sua conexão esteja correto

$id = $_POST['id'] ?? '';
$titulo = $_POST['titulo'] ?? '';
$valor = $_POST['valor'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$taxa_juros = $_POST['taxa_juros'] ?? null; // Nova coluna: taxa de juros
$tipo_vencimento = $_POST['tipo_vencimento'] ?? ''; // Nova coluna: tipo de vencimento

// Formata o valor para o formato de banco de dados (remove pontos, troca vírgula por ponto)
$valor = str_replace(',', '.', str_replace('.', '', $valor));

// Formata a taxa de juros para o formato de banco de dados (garante ponto como separador decimal)
if ($taxa_juros !== null) {
    $taxa_juros = str_replace(',', '.', $taxa_juros);
    // Converte para float para garantir que é um número válido antes de usar
    $taxa_juros = (float)$taxa_juros;
}

// Validação do título
if ($titulo == '') {
    echo 'O título é obrigatório!';
    exit();
}

// Verifica se o título já existe (exceto para o próprio produto em edição)
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
    $query = $pdo->prepare("INSERT INTO produtos_emprestimos (titulo, valor, descricao, taxa_juros, tipo_vencimento) VALUES (:titulo, :valor, :descricao, :taxa_juros, :tipo_vencimento)");
    $query->bindValue(":titulo", $titulo);
    $query->bindValue(":valor", $valor);
    $query->bindValue(":descricao", $descricao);
    $query->bindValue(":taxa_juros", $taxa_juros);
    $query->bindValue(":tipo_vencimento", $tipo_vencimento);
    $query->execute();
    echo 'Salvo com Sucesso';
} else {
    // Atualizar produto existente
    $query = $pdo->prepare("UPDATE produtos_emprestimos SET titulo = :titulo, valor = :valor, descricao = :descricao, taxa_juros = :taxa_juros, tipo_vencimento = :tipo_vencimento WHERE id = :id");
    $query->bindValue(":titulo", $titulo);
    $query->bindValue(":valor", $valor);
    $query->bindValue(":descricao", $descricao);
    $query->bindValue(":taxa_juros", $taxa_juros);
    $query->bindValue(":tipo_vencimento", $tipo_vencimento);
    $query->bindValue(":id", $id);
    $query->execute();
    echo 'Salvo com Sucesso';
}
?>
