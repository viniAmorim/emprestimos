<?php
require_once('../../../conexao.php'); // Certifique-se de que o caminho para sua conexão esteja correto

$pagina = $_POST['pagina'] ?? 0;
$limite = 10; // Quantidade de itens por página
$offset = $pagina * $limite;

// Consulta para buscar os produtos de empréstimo
$query = $pdo->query("SELECT * FROM produtos_emprestimos ORDER BY id DESC LIMIT $limite OFFSET $offset");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_linhas = @count($res);

// Consulta para contar o total de produtos para a paginação
$query_total = $pdo->query("SELECT COUNT(*) as total FROM produtos_emprestimos");
$res_total = $query_total->fetch(PDO::FETCH_ASSOC);
$total_produtos = $res_total['total'];
$total_paginas = ceil($total_produtos / $limite);

if ($total_linhas > 0) {
    echo '<table class="table table-bordered table-striped" id="tabela">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Título</th>';
    echo '<th>Valor Sugerido</th>';
    echo '<th>Descrição</th>';
    echo '<th>Ações</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($res as $item) {
        $id = $item['id'];
        $titulo = htmlspecialchars($item['titulo']);
        $valor = number_format($item['valor'], 2, ',', '.'); // Formata o valor
        $descricao = htmlspecialchars($item['descricao']);

        // Limita a descrição para exibir apenas um trecho na listagem
        $descricao_curta = (strlen($descricao) > 70) ? substr($descricao, 0, 70) . '...' : $descricao;

        echo '<tr>';
        // Checkbox para exclusão múltipla (se você for implementar)
        // echo '<td><input type="checkbox" id="check-' . $id . '" name="selecionar" value="' . $id . '" onclick="selecionarItem()"></td>';
        echo '<td>' . $titulo . '</td>';
        echo '<td>R$ ' . $valor . '</td>';
        echo '<td>' . $descricao_curta . '</td>';
        echo '<td>';
        echo '<a href="#" onclick="detalhes(\'' . $titulo . '\', \'' . $valor . '\', \'' . str_replace(["\n", "\r"], "", $descricao) . '\')" class="btn btn-info btn-sm" title="Ver Detalhes"><i class="fa fa-info-circle"></i></a> ';
        echo '<a href="#" onclick="editar(' . $id . ', \'' . $titulo . '\', \'' . $item['valor'] . '\', \'' . str_replace(["\n", "\r"], "", $descricao) . '\')" class="btn btn-primary btn-sm" title="Editar Produto"><i class="fa fa-edit"></i></a> ';
        echo '<a href="#" onclick="deletar(' . $id . ')" class="btn btn-danger btn-sm" title="Excluir Produto"><i class="fa fa-trash"></i></a>';
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';

    // Paginação
    echo '<nav aria-label="Page navigation">';
    echo '<ul class="pagination">';
    for ($i = 0; $i < $total_paginas; $i++) {
        $classe_ativa = ($i == $pagina) ? 'active' : '';
        echo '<li class="page-item ' . $classe_ativa . '"><a class="page-link" href="#" onclick="listarProdutos(' . $i . ')">' . ($i + 1) . '</a></li>';
    }
    echo '</ul>';
    echo '</nav>';

} else {
    echo 'Nenhum produto de empréstimo cadastrado.';
}
?>