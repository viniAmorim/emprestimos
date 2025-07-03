<?php
$pag = 'produtos'; // Nome da página alterado para refletir o conteúdo

if(@$produtos == 'ocultar'){ // Ajuste a variável de ocultar
    echo "<script>window.location='../index.php'</script>";
    exit();
}

// Lógicas de pagamento e afins que estavam na página de empréstimos não se aplicam diretamente aqui.
// Se precisar de algo específico, crie novas lógicas para produtos de empréstimo.
?>

<div class="main-page margin-mobile">

    <div class="row">
        <div class="col-md-2" style="padding:0">
            <a href="#" onclick="abrirModalCadastrar()" type="button" class="btn btn-primary"><span class="fa fa-plus"></span> Novo Produto</a>
        </div>
        </div>

    <li class="dropdown head-dpdn2" style="display: inline-block;">
        <a href="#" data-toggle="dropdown" class="btn btn-danger dropdown-toggle" id="btn-deletar" style="display:none"><span class="fa fa-trash-o"></span> Deletar</a>

        <ul class="dropdown-menu">
            <li>
                <div class="notification_desc2">
                    <p>Excluir Selecionados? <a href="#" onclick="deletarSel()"><span class="text-danger">Sim</span></a></p>
                </div>
            </li>
        </ul>
    </li>

    <div class="bs-example widget-shadow" style="padding:15px; margin-top:0px" id="listar">
        </div>

</div>

<input type="hidden" id="ids">


<div class="modal fade" id="modalDados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel"><span id="titulo_modal_dados">Detalhes do Produto</span></h4>
                <button id="btn-fechar-dados" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row" style="margin-top: 0px">
                    <div class="col-md-12" style="margin-bottom: 5px">
                        <span><b>Título: </b></span><span id="titulo_dados"></span>
                    </div>

                    <div class="col-md-12" style="margin-bottom: 5px">
                        <span><b>Valor (Sugestão): </b></span><span id="valor_dados"></span>
                    </div>

                    <div class="col-md-12" style="margin-bottom: 5px">
                        <span><b>Descrição: </b></span><span id="descricao_dados"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="titulo_modal_form">Novo Produto de Empréstimo</h4>
                <button id="btn-fechar-form" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_produtos_emprestimos">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="titulo">Título *</label>
                                <input type="text" class="form-control" id="titulo_produto" name="titulo" placeholder="Título do Produto de Empréstimo" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="valor">Valor (Sugestão)</label>
                                <input type="text" class="form-control" id="valor_produto" name="valor" placeholder="Valor Sugerido (Ex: R$ 5.000,00)" onkeyup="mascara_valor('valor_produto')">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <textarea class="form-control" id="descricao_produto" name="descricao" placeholder="Descrição detalhada do produto de empréstimo"></textarea>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" class="form-control" id="id_produto" name="id">

                    <br>
                    <small><div id="mensagem_form" align="center"></div></small>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script> <script type="text/javascript">
    $(document).ready(function () {
        listarProdutos(0); // Inicia a listagem na primeira página
    });

    // Função para listar produtos (com paginação)
    function listarProdutos(pagina) {
        $.ajax({
            url: 'paginas/' + pag + "/listar.php", // Arquivo PHP para listar os produtos
            method: 'POST',
            data: {pagina: pagina}, // Envia o número da página
            dataType: "html",
            success: function(result) {
                $("#listar").html(result);
            }
        });
    }

    // Função para abrir o modal de cadastro
    function abrirModalCadastrar() {
        $('#titulo_modal_form').text('Novo Produto de Empréstimo');
        $('#form_produtos_emprestimos')[0].reset(); // Limpa o formulário
        $('#id_produto').val(''); // Garante que o ID esteja vazio para cadastro
        $('#mensagem_form').text('');
        $('#modalForm').modal('show');
    }

    // Função para abrir o modal de edição
    function editar(id, titulo, valor, descricao) {
        $('#titulo_modal_form').text('Editar Produto de Empréstimo');
        $('#id_produto').val(id);
        $('#titulo_produto').val(titulo);
        $('#valor_produto').val(valor);
        $('#descricao_produto').val(descricao);
        $('#mensagem_form').text('');
        $('#modalForm').modal('show');
    }

    // Função para abrir o modal de detalhes
    function detalhes(titulo, valor, descricao) {
        $('#titulo_modal_dados').text('Detalhes do Produto');
        $('#titulo_dados').text(titulo);
        $('#valor_dados').text(valor);
        $('#descricao_dados').text(descricao);
        $('#modalDados').modal('show');
    }

    // Função para deletar um único produto
    function deletar(id) {
        if (confirm('Deseja realmente excluir este produto?')) {
            $.ajax({
                url: 'paginas/' + pag + "/excluir.php", // Arquivo PHP para excluir um produto
                method: 'POST',
                data: {id: id},
                dataType: "html",
                success: function(mensagem) {
                    if (mensagem.trim() == "Excluído com Sucesso") {
                        listarProdutos(0); // Recarrega a lista
                    } else {
                        alert(mensagem);
                    }
                }
            });
        }
    }

    // Função para deletar produtos selecionados (se você implementar checkboxes)
    function deletarSel() {
        var ids = $('#ids').val(); // Este ID viria de checkboxes selecionados, por exemplo
        if (ids === '') {
            alert('Nenhum item selecionado para exclusão.');
            return;
        }

        if (confirm('Deseja realmente excluir os produtos selecionados?')) {
            $.ajax({
                url: 'paginas/' + pag + "/excluir_selecionados.php", // Arquivo PHP para excluir múltiplos produtos
                method: 'POST',
                data: {ids: ids},
                dataType: "html",
                success: function(mensagem) {
                    if (mensagem.trim() == "Excluído com Sucesso") {
                        listarProdutos(0); // Recarrega a lista
                        $('#btn-deletar').hide(); // Esconde o botão de deletar selecionados
                        $('#ids').val(''); // Limpa os IDs
                    } else {
                        alert(mensagem);
                    }
                }
            });
        }
    }

    // Submit do formulário de cadastro/edição
    $("#form_produtos_emprestimos").submit(function () {
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: 'paginas/' + pag + "/salvar.php", // Arquivo PHP para salvar (inserir ou editar)
            type: 'POST',
            data: formData,
            success: function (mensagem) {
                $('#mensagem_form').text('');
                $('#mensagem_form').removeClass();
                if (mensagem.trim() == "Salvo com Sucesso") {
                    $('#btn-fechar-form').click();
                    listarProdutos(0); // Recarrega a lista na primeira página
                } else {
                    $('#mensagem_form').addClass('text-danger');
                    $('#mensagem_form').text(mensagem);
                }
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    });

    // Função para máscara de valor (se 'mascara_valor' estiver definida em outro JS, mantenha)
    function mascara_valor(id) {
        var campo = $('#' + id);
        campo.val(campo.val().replace(/\D/g, '').replace(/(\d{1,2})$/, ',$1').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.'));
    }
</script>