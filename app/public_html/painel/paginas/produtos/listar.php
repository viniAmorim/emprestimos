<?php
require_once('../../../conexao.php'); // Certifique-se de que o caminho para sua conexão esteja correto

$pagina = $_POST['pagina'] ?? 0;
$limite = 9; // Quantidade de itens por página (ajuste para múltiplos de 3 ou 4 para a grade)
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
    echo '<div class="row product-grid-container">'; // Contêiner para os cards

    foreach ($res as $item) {
        $id = $item['id'];
        $titulo = htmlspecialchars($item['titulo']);
        $valor = number_format($item['valor'], 2, ',', '.'); // Formata o valor
        $descricao = htmlspecialchars($item['descricao']);

        // Limita a descrição para exibir apenas um trecho no card
        $descricao_curta = (strlen($descricao) > 100) ? substr($descricao, 0, 100) . '...' : $descricao;

        echo '<div class="col-md-4 col-sm-6 mb-4">'; // Coluna para cada card (3 por linha em desktop, 2 em tablet)
        echo '      <div class="card product-card h-100">'; // Card do Bootstrap
        echo '          <div class="card-body d-flex flex-column">';
        echo '              <h5 class="card-title product-title">' . $titulo . '</h5>';
        echo '              <p class="card-text product-description flex-grow-1">' . $descricao_curta . '</p>';
        echo '              <p class="card-text product-price">R$ ' . $valor . '</p>';
        echo '              <div class="product-actions mt-auto d-flex flex-column">'; // Adicionado d-flex flex-column para empilhar e controlar o tamanho
        // Botão Detalhes (descomentado se quiser exibi-lo)
        // echo '              <button onclick="detalhes(\'' . $titulo . '\', \'' . $valor . '\', \'' . str_replace(["\n", "\r"], "", $descricao) . '\')" class="btn btn-info btn-sm mb-1 w-100"><i class="fa fa-info-circle"></i> Detalhes</button>';
        
        // Botão Editar com a nova classe 'btn-editar-claro'
        echo '                  <button onclick="editar(' . $id . ', \'' . $titulo . '\', \'' . $item['valor'] . '\', \'' . str_replace(["\n", "\r"], "", $descricao) . '\')" class="btn btn-editar-claro btn-sm mb-1">Editar</button>';
        
        // Botão Excluir com a nova classe 'btn-excluir-claro' e ícone correto (trash-alt)
        echo '                  <button onclick="deletar(' . $id . ')" class="btn btn-excluir-claro btn-sm mb-1"><i class="fa fa-trash-alt"></i> Excluir</button>';
        echo '              </div>';
        echo '          </div>';
        echo '      </div>';
        echo '</div>';
    }
    echo '</div>'; // Fecha product-grid-container

    // Paginação
    echo '<nav aria-label="Page navigation" class="mt-4">';
    echo '<ul class="pagination justify-content-center">';
    for ($i = 0; $i < $total_paginas; $i++) {
        $classe_ativa = ($i == $pagina) ? 'active' : '';
        echo '<li class="page-item ' . $classe_ativa . '"><a class="page-link" href="#" onclick="listarProdutos(' . $i . ')">' . ($i + 1) . '</a></li>';
    }
    echo '</ul>';
    echo '</nav>';

} else {
    echo '<p class="text-center text-muted">Nenhum produto de empréstimo cadastrado.</p>';
}
?>