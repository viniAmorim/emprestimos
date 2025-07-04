<?php
$pag = 'produtos';

if(@$produtos == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Produtos</title>

    <!-- As inclusões de jQuery, Bootstrap e Font Awesome devem estar no seu index.php principal,
         conforme discutido anteriormente, para evitar duplicação e conflitos.
         Se você ainda não fez essa mudança no index.php, faça-a. -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script> -->
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->

    <link rel="stylesheet" href="css/produtos.css">
    <script src="js/ajax.js"></script>

</head>
<body>

<div class="main-page margin-mobile container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="#" onclick="abrirModalCadastrar()" class="btn btn-primary">
            Novo Produto
        </a>

        <div class="dropdown">
            <a href="#" class="btn btn-danger dropdown-toggle" id="btn-deletar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display:none;">
                <i class="fas fa-trash-alt"></i> Deletar Selecionados
            </a>
            <div class="dropdown-menu" aria-labelledby="btn-deletar">
                <a class="dropdown-item text-danger" href="#" onclick="deletarSel()">Confirmar Exclusão</a>
            </div>
        </div>
    </div>

    <div id="listar" class="product-grid">
        </div>
</div>

<input type="hidden" id="ids">

<!-- Modal de Detalhes (modalDados) -->
<div class="modal fade" id="modalDados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_modal_dados">Detalhes do Produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Título:</strong> <span id="titulo_dados"></span></p>
                <p><strong>Valor:</strong> <span id="valor_dados"></span></p>
                <!-- NOVOS CAMPOS PARA DETALHES -->
                <p><strong>Taxa de Juros:</strong> <span id="taxa_juros_dados"></span>%</p>
                <p><strong>Tipo de Vencimento:</strong> <span id="tipo_vencimento_dados"></span></p>
                <p><strong>Descrição:</strong> <span id="descricao_dados"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Formulário (modalForm) -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_modal_form">Novo Produto de Empréstimo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_produtos_emprestimos">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="titulo_produto">Título *</label>
                        <input type="text" class="form-control" id="titulo_produto" name="titulo" placeholder="Título do Produto de Empréstimo" required>
                    </div>

                    <div class="form-group">
                        <label for="valor_produto">Valor</label>
                        <input type="text" class="form-control" id="valor_produto" name="valor" placeholder="Valor Sugerido (Ex: R$ 5.000,00)" onkeyup="mascara_valor('valor_produto')">
                    </div>

                    <!-- NOVO CAMPO: Taxa de Juros -->
                    <div class="form-group">
                        <label for="taxa_juros_produto">Taxa de Juros (%)</label>
                        <input type="number" step="0.01" class="form-control" id="taxa_juros_produto" name="taxa_juros" placeholder="Ex: 5.25">
                    </div>

                    <!-- NOVO CAMPO: Tipo de Vencimento -->
                    <div class="form-group">
                        <label for="tipo_vencimento_produto">Tipo de Vencimento</label>
                        <select class="form-control" id="tipo_vencimento_produto" name="tipo_vencimento">
                            <option value="">Selecione</option>
                            <option value="Diário">Diário</option>
                            <option value="Semanal">Semanal</option>
                            <option value="Quinzenal">Quinzenal</option>
                            <option value="Mensal">Mensal</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="descricao_produto">Descrição</label>
                        <textarea class="form-control" id="descricao_produto" name="descricao" placeholder="Descrição detalhada do produto de empréstimo"></textarea>
                    </div>

                    <input type="hidden" id="id_produto" name="id">

                    <div id="mensagem_form" class="text-danger text-center mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    var pag = "<?=$pag?>";

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
        $('#modalForm').modal('show'); // Usa o método 'modal' do Bootstrap
    }

    // Função para abrir o modal de edição (AGORA COM TAXA_JUROS E TIPO_VENCIMENTO)
    function editar(id, titulo, valor, descricao, taxa_juros, tipo_vencimento) {
        $('#titulo_modal_form').text('Editar Produto de Empréstimo');
        $('#id_produto').val(id);
        $('#titulo_produto').val(titulo);
        $('#valor_produto').val(valor);
        $('#taxa_juros_produto').val(taxa_juros); // Preenche a taxa de juros
        $('#tipo_vencimento_produto').val(tipo_vencimento); // Preenche o tipo de vencimento
        $('#descricao_produto').val(descricao);
        $('#mensagem_form').text('');
        $('#modalForm').modal('show'); // Usa o método 'modal' do Bootstrap
    }

    // Função para abrir o modal de detalhes (AGORA COM TAXA_JUROS E TIPO_VENCIMENTO)
    function detalhes(titulo, valor, descricao, taxa_juros, tipo_vencimento) {
        $('#titulo_modal_dados').text('Detalhes do Produto');
        $('#titulo_dados').text(titulo);
        $('#valor_dados').text(valor);
        $('#taxa_juros_dados').text(taxa_juros); // Exibe a taxa de juros
        $('#tipo_vencimento_dados').text(tipo_vencimento); // Exibe o tipo de vencimento
        $('#descricao_dados').text(descricao);
        $('#modalDados').modal('show'); // Usa o método 'modal' do Bootstrap
    }

    function deletar(id) {
        Swal.fire({
            title: 'Tem certeza?',
            text: 'Deseja realmente excluir este produto?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'paginas/' + pag + "/excluir.php",
                    method: 'POST',
                    data: {id: id},
                    dataType: "html",
                    success: function(mensagem) {
                        if (mensagem.trim() == "Excluído com Sucesso") {
                            Swal.fire(
                                'Excluído!',
                                'O produto foi excluído com sucesso.',
                                'success'
                            );
                            listarProdutos(0); // Recarrega a lista de produtos
                        } else {
                            Swal.fire(
                                'Erro!',
                                mensagem,
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire(
                            'Erro de Conexão!',
                            'Não foi possível conectar ao servidor para excluir o produto.',
                            'error'
                        );
                        console.error("Erro na requisição AJAX:", status, error);
                    }
                });
            }
        });
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
                url: 'paginas/' + pag + "/excluir_selecionados.php",
                method: 'POST',
                data: {ids: ids},
                dataType: "html",
                success: function(mensagem) {
                    if (mensagem.trim() == "Excluído com Sucesso") {
                        listarProdutos(0);
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
            url: 'paginas/' + pag + "/salvar.php",
            type: 'POST',
            data: formData,
            success: function (mensagem) {
                $('#mensagem_form').text('');
                $('#mensagem_form').removeClass();
                if (mensagem.trim() == "Salvo com Sucesso") {
                    $('#modalForm').modal('hide'); // Esconde o modal usando o método 'hide' do Bootstrap
                    listarProdutos(0);
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

    // Função para máscara de valor
    function mascara_valor(id) {
        var campo = $('#' + id);
        campo.val(campo.val().replace(/\D/g, '').replace(/(\d{1,2})$/, ',$1').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.'));
    }
</script>

</body>
</html>
