<?php
require_once('../../conexao.php');

$ids = $_POST['ids'] ?? '';
$array_ids = explode(",", $ids);

foreach ($array_ids as $id) {
    if ($id != '') {
        $query = $pdo->prepare("DELETE FROM produtos_emprestimos WHERE id = :id");
        $query->bindValue(":id", $id);
        $query->execute();
    }
}

echo 'Excluído com Sucesso';
?>