<?php
// Geralmente $tabela é definida para o contexto do módulo.
$tabela = 'alertas_duplicidade';
require_once("../../../conexao.php"); // Garante que $pdo está disponível

// --- Parâmetros de Busca e Paginação ---
$registrosPorPagina = 10; // Defina quantos registros quer por página
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($paginaAtual < 1) {
    $paginaAtual = 1;
}
$offset = ($paginaAtual - 1) * $registrosPorPagina;

$termoBusca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$whereClause = " WHERE a.resolvido = FALSE"; // Padrão: buscar apenas alertas não resolvidos
$params = [];

if (!empty($termoBusca)) {
    // Adiciona o termo de busca à cláusula WHERE, procurando em tipo, valor e nome do cliente
    $whereClause .= " AND (a.tipo_alerta LIKE :busca OR a.valor_duplicado LIKE :busca OR c.nome LIKE :busca)";
    $params[':busca'] = '%' . $termoBusca . '%';
}

// --- Lógica para Marcar Alerta como Resolvido (POST) ---
if (isset($_POST['resolver_alerta_id'])) {
    $alerta_id = $_POST['resolver_alerta_id'];
    $stmt_resolver = $pdo->prepare("UPDATE alertas_duplicidade SET resolvido = TRUE WHERE id = :id");
    $stmt_resolver->bindValue(":id", $alerta_id, PDO::PARAM_INT); // Usar PDO::PARAM_INT para IDs
    $stmt_resolver->execute();

    // Redireciona para a URL atual para evitar reenvio do formulário
    $current_page_url = $_SERVER['PHP_SELF'];
    if (!empty($_SERVER['QUERY_STRING'])) {
        $current_page_url .= '?' . $_SERVER['QUERY_STRING'];
    }
    header("Location: " . $current_page_url);
    exit();
}

// --- 1. Contar o Total de Registros (para paginação com busca) ---
$stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM {$tabela} a JOIN clientes c ON a.id_cliente_cadastrado = c.id" . $whereClause);
$stmtTotal->execute($params);
$totalRegistros = $stmtTotal->fetchColumn();
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// --- 2. Obter os Dados da Tabela com Paginação, Busca e JOIN de Cliente ---
$sql = "SELECT
            a.*,
            c.nome as nome_cliente,
            c.cpf as cpf_cliente,
            c.status_cliente as status_cliente_full,
            c.foto as foto_cliente,
            c.comprovante_rg as foto_cnh_cliente,
            c.comprovante_endereco,       -- NOVO: Comprovante de Endereço
            c.endereco,
            c.cidade,
            c.valor_desejado as valor_solicitado,
            a.verificado_nome_cnh,
            a.verificado_endereco_cnh,
            a.verificado_cidade_cnh,
            a.verificado_foto_cnh,        -- NOVO
            a.verificado_validade_cnh,    -- NOVO
            a.verificado_nome_comp,       -- NOVO
            a.verificado_endereco_comp,   -- NOVO
            a.verificado_cidade_comp,     -- NOVO
            a.detalhes_alerta_text,
            c.referencia_contato
        FROM
            {$tabela} a
        JOIN
            clientes c ON a.id_cliente_cadastrado = c.id
        " . $whereClause . "
        ORDER BY a.data_alerta DESC
        LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':limit', $registrosPorPagina, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

foreach ($params as $key => &$val) {
    $stmt->bindParam($key, $val);
}
$stmt->execute();
$alertas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$linhas = count($alertas); // Agora $alertas já vem paginado e filtrado

?>
<div class="mb-4">
    </div>

<?php if ($linhas > 0): ?>
<small>
    <table class="table table-hover" id="tabela-alertas">
        <thead>
            <tr>
                <th>Tipo Alerta</th>
                <th class="esc">Valor Duplicado</th>
                <th class="esc">Cliente Cadastrado</th>
                <th class="esc">Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alertas as $alerta): ?>
                <tr>
                    <td><?= htmlspecialchars($alerta['tipo_alerta']) ?></td>
                    <td class="esc"><?= htmlspecialchars($alerta['valor_duplicado']) ?></td>
                    <td class="esc">
                        <?php if (!empty($alerta['id_cliente_cadastrado'])): ?>
                            <a href="editar_cliente.php?id=<?= htmlspecialchars($alerta['id_cliente_cadastrado']) ?>" title="Ver Cliente">
                                <?= htmlspecialchars($alerta['nome_cliente']) ?> (ID: <?= htmlspecialchars($alerta['id_cliente_cadastrado']) ?>)
                            </a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td class="esc"><?= date('d/m/Y H:i:s', strtotime($alerta['data_alerta'])) ?></td>
                    <td>
                        <a href="#" onclick="mostrar(
                                '<?= htmlspecialchars($alerta['id']) ?>',
                                '<?= htmlspecialchars($alerta['tipo_alerta']) ?>',
                                '<?= htmlspecialchars($alerta['valor_duplicado']) ?>',
                                '<?= date('d/m/Y H:i:s', strtotime($alerta['data_alerta'])) ?>',
                                '<?= htmlspecialchars($alerta['resolvido']) ?>',
                                '<?= htmlspecialchars($alerta['nome_cliente'] ?? 'N/A') ?>',
                                '<?= htmlspecialchars($alerta['cpf_cliente'] ?? 'N/A') ?>',
                                '<?= htmlspecialchars($alerta['id_cliente_cadastrado'] ?? 'N/A') ?>',
                                '<?= htmlspecialchars($alerta['status_cliente_full'] ?? 'N/A') ?>',
                                '<?= htmlspecialchars($alerta['foto_cliente'] ?? 'sem-foto.png') ?>',
                                '<?= htmlspecialchars($alerta['foto_cnh_cliente'] ?? 'sem-cnh.png') ?>',
                                '<?= htmlspecialchars($alerta['comprovante_endereco'] ?? 'sem-comprovante.png') ?>', /* NOVO: Comprovante Endereço */
                                '<?= htmlspecialchars($alerta['endereco'] ?? 'N/A') ?>',
                                '<?= htmlspecialchars($alerta['cidade'] ?? 'N/A') ?>',
                                '<?= htmlspecialchars($alerta['detalhes_alerta_text'] ?? 'Nenhum detalhe adicional.') ?>',
                                '<?= htmlspecialchars(number_format($alerta['valor_solicitado'] ?? 0, 2, '.', '')) ?>',
                                '<?= htmlspecialchars($alerta['parcelamento'] ?? 'N/A') ?>',
                                '<?= htmlspecialchars($alerta['verificado_nome_cnh'] ?? 0) ?>',
                                '<?= htmlspecialchars($alerta['verificado_endereco_cnh'] ?? 0) ?>',
                                '<?= htmlspecialchars($alerta['verificado_cidade_cnh'] ?? 0) ?>',
                                '<?= htmlspecialchars($alerta['verificado_foto_cnh'] ?? 0) ?>',        /* NOVO: Verificado Foto CNH */
                                '<?= htmlspecialchars($alerta['verificado_validade_cnh'] ?? 0) ?>',    /* NOVO: Verificado Validade CNH */
                                '<?= htmlspecialchars($alerta['verificado_nome_comp'] ?? 0) ?>',       /* NOVO: Verificado Nome Comprovante */
                                '<?= htmlspecialchars($alerta['verificado_endereco_comp'] ?? 0) ?>',   /* NOVO: Verificado Endereço Comprovante */
                                '<?= htmlspecialchars($alerta['verificado_cidade_comp'] ?? 0) ?>',     /* NOVO: Verificado Cidade Comprovante */
                                '<?= htmlspecialchars($alerta['telefone_referencia'] ?? 'N/A') ?>'
                        )" title="Ver Detalhes" class="btn btn-sm btn-info ml-1">
                            <i class="fa fa-info-circle"></i> Detalhes
                        </a>

                        </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <small><div align="center" id="mensagem-excluir"></div></small>
    </table>
</small>

<?php if ($totalPaginas > 1): ?>
    <nav aria-label="Page navigation example" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php
            $urlBusca = !empty($termoBusca) ? '&busca=' . urlencode($termoBusca) : '';
            for ($i = 1; $i <= $totalPaginas; $i++):
                $activeClass = ($i == $paginaAtual) ? 'active' : '';
            ?>
                <li class="page-item <?= $activeClass ?>">
                    <a class="page-link" href="?pagina=<?= $i . $urlBusca ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

<?php else: ?>
    <small>Nenhum Alerta de Duplicidade Pendente Encontrado!</small>
<?php endif; ?>

<script type="text/javascript">
    $(document).ready( function () {
        $('#tabela-alertas').DataTable({
            "language" : {
                "url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
            },
            "ordering": false,
            "paging": false,
            "info": false,
            "searching": false
        });
    });
</script>

<script type="text/javascript">
    // A função mostrar() foi modificada no painel_alertas.php para aceitar mais parâmetros.
    // Esta função aqui no listar.php é redundante e deve ser removida ou adaptada
    // Se você tiver um cenário onde a função 'mostrar' precisa ser definida aqui e não em painel_alertas.php,
    // por favor, me informe. No entanto, o ideal é ter uma única definição em painel_alertas.php.

    function limparCampos(){
        $('#id').val('');
        $('#tipo_alerta').val('');
        $('#valor_duplicado').val('');
        $('#ids').val('');
        $('#btn-deletar').hide();
        // Não é necessário limpar as checkboxes de verificação aqui, pois elas são populadas
        // pela função mostrar() no painel_alertas.php ao abrir o modal.
    }

    function selecionar(id){
        var ids = $('#ids').val();
        if($('#seletor-'+id).is(":checked") == true){
            var novo_id = ids + id + '-';
            $('#ids').val(novo_id);
        }else{
            var retirar = ids.replace(id + '-', '');
            $('#ids').val(retirar);
        }
        var ids_final = $('#ids').val();
        if(ids_final == ""){
            $('#btn-deletar').hide();
        }else{
            $('#btn-deletar').show();
        }
    }

    function deletarSel(){
        var ids = $('#ids').val();
        var id_array = ids.split("-");
        if(confirm('Tem certeza que deseja excluir os registros selecionados?')){
            for(i=0; i<id_array.length-1; i++){
                excluir(id_array[i]);
            }
            limparCampos();
        }
    }

    function excluir(id){
        $.ajax({
            url: 'paginas/<?= $pag ?>/excluir.php',
            method: 'POST',
            data: {id},
            dataType: "html",
            success:function(result){
                listar();
                $('#mensagem-excluir').text(result);
            }
        });
    }

    // A função editar() também foi movida e adaptada no painel_alertas.php.
    // Esta definição aqui pode ser redundante, a menos que haja um caso de uso específico para ela aqui.
    function editar(id, tipo_alerta, valor_duplicado, data_alerta){
        $('#mensagem').text('');
        $('#titulo_inserir').text('Editar Registro');
        $('#id').val(id);
        $('#tipo_alerta').val(tipo_alerta);
        $('#valor_duplicado').val(valor_duplicado);
        $('#data_alerta').val(data_alerta);
        $('#modalForm').modal('show');
    }
</script>