<?php
$pag = 'painel_alertas'; // Nome da página atual, usado nos scripts AJAX

// Esta parte verifica a permissão do usuário. Mantenha se for necessário no seu sistema.
if (@$usuarios == 'ocultar') {
    echo "<script>window.location='../index.php'</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Alertas</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css"> 

    <style>
        /* Estilo base para custom-control-input:hidden */
        .custom-control-input:disabled ~ .custom-control-label::before {
            background-color: #e9ecef; /* Cor de fundo para desabilitado */
            border-color: #ced4da; /* Cor da borda para desabilitado */
            cursor: not-allowed;
        }

        /* Estilo para o checkmark quando o checkbox está disabled e checked */
        .custom-control-input:checked:disabled ~ .custom-control-label::before {
            background-color: #28a745; /* Cor de fundo para "checked" e "disabled" (verde de sucesso) */
            border-color: #28a745;
            opacity: 0.8; /* Um pouco de opacidade para indicar desabilitado */
        }

        .custom-control-input:checked:disabled ~ .custom-control-label::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23fff' d='M6.564.753l-3.59 3.617-1.481-1.47a.75.75 0 00-1.061 1.06l2.032 2.028a.75.75 0 001.06-.006l4.141-4.182a.75.75 0 10-1.06-1.06z'/%3e%3c/svg%3e"); /* Ícone de check branco */
        }
    </style>
</head>
<body>

    <div class="main-page margin-mobile">
        <li class="dropdown head-dpdn2" style="display: inline-block;">
            <a href="#" data-toggle="dropdown" class="btn btn-danger dropdown-toggle" id="btn-deletar" style="display:none">
                <span class="fa fa-trash-o"></span> Deletar Selecionados
            </a>
            <ul class="dropdown-menu">
                <li>
                    <div class="notification_desc2">
                        <p>Excluir Selecionados? <a href="#" onclick="deletarSel()"><span class="text-danger">Sim</span></a></p>
                    </div>
                </li>
            </ul>
        </li>

        <div class="bs-example widget-shadow" style="padding:15px" id="listar">
        </div>
    </div>

    <input type="hidden" id="ids">

    <div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalFormLabel"><span id="titulo_inserir"></span></h4>
                    <button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Tipo de Alerta</label>
                                <input type="text" class="form-control" id="tipo_alerta" name="tipo_alerta" placeholder="Ex: CPF, Email, Telefone" required>
                            </div>
                            <div class="col-md-6">
                                <label>Valor Duplicado</label>
                                <input type="text" class="form-control" id="valor_duplicado" name="valor_duplicado" placeholder="Valor que gerou o alerta" required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Data do Alerta</label>
                                <input type="text" class="form-control" id="data_alerta" name="data_alerta" placeholder="YYYY-MM-DD HH:MM:SS" required>
                            </div>
                            <div class="col-md-6">
                                <label>ID Cliente Cadastrado</label>
                                <input type="number" class="form-control" id="id_cliente_cadastrado" name="id_cliente_cadastrado" placeholder="ID do Cliente" readonly>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="resolvido" name="resolvido">
                                    <label class="form-check-label" for="resolvido">
                                        Marcar como Resolvido
                                    </label>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" class="form-control" id="id" name="id">

                        <br>
                        <small><div id="mensagem" align="center"></div></small>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDados" tabindex="-1" aria-labelledby="modalDadosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title display-5 font-weight-bold" id="modalDadosLabel">Detalhes do Alerta e Cliente</h2>
                    <button id="btn-fechar-dados" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_alerta_modal_dados" value=""> 

                    <div class="row mb-4 align-items-center">
                        <div class="col-md-3 text-left">
                            <img src="" id="foto_cliente_dados" alt="Foto do Cliente" class="img-fluid rounded-circle shadow" style="max-width: 120px; max-height: 120px; object-fit: cover; border: 3px solid #eee;">
                        </div>
                        <div class="col-md-7">
                            <p class="mb-2"><strong>Nome:</strong> <span id="nome_cliente_completo_dados" class="form-control-plaintext border-bottom py-1"></span></p>
                            <p class="mb-2"><strong>CPF:</strong> <span id="cpf_cliente_dados" class="form-control-plaintext border-bottom py-1"></span></p>
                            <p class="mb-0"><strong>Cliente ID:</strong> <span id="id_cliente_cadastrado_dados" class="form-control-plaintext border-bottom py-1"></span></p>
                        </div>
                        <div class="col-md-2 text-right">
                            <p class="mb-0"><strong>Status:</strong> <br><span id="status_cliente_dados" class="badge badge-primary py-2 px-3 mt-1" style="font-size: 0.9em;"></span></p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row mb-4">
                        <div class="col-md-6 text-center">
                            <p class="font-weight-bold mb-2">Foto CNH:</p>
                            <img src="" id="foto_cnh_dados" alt="Foto CNH" class="img-fluid rounded shadow-sm" style="max-width: 350px; border: 2px solid #ddd; display: block; margin: 0 auto;">
                            <div class="d-flex flex-column align-items-start mt-2">
                                <div class="form-group custom-control custom-checkbox my-1">
                                    <input type="checkbox" class="custom-control-input" id="check_cnh_nome">
                                    <label class="custom-control-label" for="check_cnh_nome">Nome CNH confere</label>
                                </div>
                                <div class="form-group custom-control custom-checkbox my-1">
                                    <input type="checkbox" class="custom-control-input" id="check_cnh_foto">
                                    <label class="custom-control-label" for="check_cnh_foto">Foto CNH confere</label>
                                </div>
                                <div class="form-group custom-control custom-checkbox my-1">
                                    <input type="checkbox" class="custom-control-input" id="check_cnh_validade">
                                    <label class="custom-control-label" for="check_cnh_validade">Validade CNH</label>
                                </div>
                                </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <p class="font-weight-bold mb-2">Comprovante de Endereço:</p>
                            <img src="" id="foto_comprovante_endereco_dados" alt="Comprovante de Endereço" class="img-fluid rounded shadow-sm" style="max-width: 350px; border: 2px solid #ddd; display: block; margin: 0 auto;">
                            <div class="d-flex flex-column align-items-start mt-2">
                                <div class="form-group custom-control custom-checkbox my-1">
                                    <input type="checkbox" class="custom-control-input" id="check_comp_nome">
                                    <label class="custom-control-label" for="check_comp_nome">Nome no comprovante</label>
                                </div>
                                <div class="form-group custom-control custom-checkbox my-1">
                                    <input type="checkbox" class="custom-control-input" id="check_comp_endereco">
                                    <label class="custom-control-label" for="check_comp_endereco">Endereço Completo</label>
                                </div>
                                <div class="form-group custom-control custom-checkbox my-1">
                                    <input type="checkbox" class="custom-control-input" id="check_comp_cidade">
                                    <label class="custom-control-label" for="check_comp_cidade">Cidade que atendemos</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <small><div id="mensagem-verificacao" align="center" class="mt-2 text-success"></div></small>

                    <h5 class="font-weight-bold mb-3">Alertas de Duplicidade:</h5>
                    <p class="mb-2"><strong>Tipo de Alerta:</strong> <span id="tipo_alerta_dados" class="form-control-plaintext border-bottom py-1"></span></p>
                    <p class="mb-2"><strong>Valor Duplicado:</strong> <span id="valor_duplicado_dados" class="form-control-plaintext border-bottom py-1"></span></p>
                    <p class="mb-2"><strong>Data do Alerta:</strong> <span id="data_alerta_dados" class="form-control-plaintext border-bottom py-1"></span></p>
                    <p class="mt-3">
                        <strong>Detalhes do Alerta:</strong> <span id="detalhes_alerta_text_dados" class="form-control-plaintext border-bottom py-1"></span>
                        <ul id="alertas_referencia_lista" class="list-unstyled mt-2 ml-3">
                        </ul>
                    </p>

                    <hr class="my-4">

                    <h5 class="font-weight-bold mb-3">Detalhes da Solicitação:</h5>
                    <p class="mb-2"><strong>Valor Solicitado:</strong> R$ <span id="valor_solicitado_dados" class="form-control-plaintext border-bottom py-1"></span></p>
                    <p class="mb-0"><strong>Parcelamento:</strong> <span id="parcelamento_dados" class="form-control-plaintext border-bottom py-1"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="btn_salvar_verificacao">Salvar Verificações</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

<script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script> <script type="text/javascript" src="../js/bootstrap.min.js"></script> <script type="text/javascript">
    var pag = "<?=$pag?>"; // Variável 'pag' definida para uso em AJAX.
    var alertaAtualId = null; // Variável global para armazenar o ID do alerta atual

    $(document).ready(function() {
        listar(); // Chama a função listar para carregar o conteúdo da tabela

        // Listener para o botão Salvar Verificações no modalDados
        $('#btn_salvar_verificacao').click(function() {
            if (alertaAtualId) {
                // Checkboxes da CNH
                var cnhNomeChecked = $('#check_cnh_nome').is(':checked') ? 1 : 0;
                var cnhFotoChecked = $('#check_cnh_foto').is(':checked') ? 1 : 0;
                var cnhValidadeChecked = $('#check_cnh_validade').is(':checked') ? 1 : 0;
                // Os campos verificado_endereco_cnh e verificado_cidade_cnh NÃO são enviados via AJAX
                // porque não há checkboxes correspondentes no HTML.
                // Isso significa que seu salvar_verificacao.php deve ser ajustado para não esperar esses campos,
                // OU você deve garantir que eles tenham um valor padrão no PHP (ex: 0).

                // Checkboxes do Comprovante de Endereço
                var compNomeChecked = $('#check_comp_nome').is(':checked') ? 1 : 0;
                var compEnderecoChecked = $('#check_comp_endereco').is(':checked') ? 1 : 0;
                var compCidadeChecked = $('#check_comp_cidade').is(':checked') ? 1 : 0;

                $.ajax({
                    url: 'paginas/' + pag + '/salvar_verificacao.php',
                    method: 'POST',
                    data: {
                        id: alertaAtualId,
                        verificado_nome_cnh: cnhNomeChecked,
                        // verificado_endereco_cnh e verificado_cidade_cnh REMOVIDOS AQUI
                        verificado_foto_cnh: cnhFotoChecked,
                        verificado_validade_cnh: cnhValidadeChecked,
                        verificado_nome_comp: compNomeChecked,
                        verificado_endereco_comp: compEnderecoChecked,
                        verificado_cidade_comp: compCidadeChecked
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#mensagem-verificacao').removeClass('text-danger text-success').text('');
                        if (response.success) {
                            $('#mensagem-verificacao').addClass('text-success').text(response.message);
                            console.log('Verificações salvas com sucesso!');
                            listar(); // Recarrega a lista para mostrar o estado atualizado
                        } else {
                            $('#mensagem-verificacao').addClass('text-danger').text(response.message);
                            console.error('Erro ao salvar verificações:', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#mensagem-verificacao').removeClass('text-danger text-success').text('');
                        $('#mensagem-verificacao').addClass('text-danger').text('Erro de comunicação com o servidor.');
                        console.error('Erro na requisição AJAX:', status, error);
                        console.log(xhr.responseText); // Para depuração, mostra a resposta completa do servidor
                    }
                });
            } else {
                console.warn('ID do alerta não definido para salvar as verificações.');
            }
        });
    });

    // Função para chamar o listar.php e carregar o conteúdo na div #listar
    function listar() {
        $.ajax({
            url: 'paginas/' + pag + '/listar.php',
            method: 'POST',
            data: {},
            dataType: "html",
            success: function(result) {
                $("#listar").html(result);
            }
        });
    }

    // Função para exibir detalhes do alerta, cliente e empréstimo no modalDados
    // ATENÇÃO: verificado_endereco_cnh e verificado_cidade_cnh ainda precisam ser passados
    // para esta função `mostrar` se o banco de dados ainda os armazena.
    // O JavaScript simplesmente não os usará para marcar checkboxes, mas o PHP pode precisar.
    function mostrar(
        id_alerta, 
        tipo_alerta, valor_duplicado, data_alerta, resolvido,
        nome_cliente, cpf_cliente, id_cliente_cadastrado, status_cliente, foto_cliente,
        foto_cnh, comprovante_endereco_cliente, endereco_cliente, cidade_cliente, 
        detalhes_alerta_text, valor_solicitado, parcelamento,
        verificado_nome_cnh, verificado_endereco_cnh, verificado_cidade_cnh, // MANTENHA AQUI se eles vêm do BD
        verificado_foto_cnh, verificado_validade_cnh, 
        verificado_nome_comp, verificado_endereco_comp, verificado_cidade_comp, 
        telefone_referencia 
    ) {
        // Armazena o ID do alerta na variável global e no input hidden do modal
        alertaAtualId = id_alerta;
        $('#id_alerta_modal_dados').val(id_alerta);

        // Limpa a mensagem de feedback anterior ao abrir o modal
        $('#mensagem-verificacao').text('');

        // Preenchendo a Seção Superior do Cliente
        $('#nome_cliente_completo_dados').text(nome_cliente);
        $('#cpf_cliente_dados').text(cpf_cliente);
        $('#id_cliente_cadastrado_dados').text(id_cliente_cadastrado);

        // Ajusta o status e a classe do badge
        $('#status_cliente_dados').text(status_cliente);
        $('#status_cliente_dados').removeClass('badge-info badge-success badge-warning badge-danger badge-secondary')
                                  .addClass(getBadgeClass(status_cliente));

        // Define as imagens (ajuste o caminho base com '../' para subir de nível)
        // O caminho './' ou 'images/' fará com que procure em 'painel/paginas/painel_alertas/images/...'
        // O caminho '../../../images/' fará com que procure em 'painel/images/...' que é o mais provável.
        $('#foto_cliente_dados').attr('src', foto_cliente && foto_cliente !== 'sem-foto.png' ? '/painel/images/clientes/' + foto_cliente : '/painel/images/perfil/sem-foto.png');
        $('#foto_cnh_dados').attr('src', foto_cnh && foto_cnh !== 'sem-cnh.png' ? '/painel/images/comprovantes/' + foto_cnh : '/painel/images/comprovantes/sem-cnh.png');
        $('#foto_comprovante_endereco_dados').attr('src', comprovante_endereco_cliente && comprovante_endereco_cliente !== 'sem-comprovante.png' ? '/painel/images/comprovantes/' + comprovante_endereco_cliente : '/painel/images/comprovantes/sem-comprovante.png');


        // Popular as checkboxes com os valores do banco de dados (0 ou 1)
        // Checkboxes CNH
        $('#check_cnh_nome').prop('checked', (verificado_nome_cnh == 1));
        $('#check_cnh_foto').prop('checked', (verificado_foto_cnh == 1));
        $('#check_cnh_validade').prop('checked', (verificado_validade_cnh == 1));
        // Checkboxes de endereço e cidade CNH REMOVIDAS daqui
       
        // Checkboxes Comprovante de Endereço
        $('#check_comp_nome').prop('checked', (verificado_nome_comp == 1));
        $('#check_comp_endereco').prop('checked', (verificado_endereco_comp == 1));
        $('#check_comp_cidade').prop('checked', (verificado_cidade_comp == 1));

        // Preenchendo a Seção de Alertas Específicos
        $('#tipo_alerta_dados').text(tipo_alerta);
        $('#valor_duplicado_dados').text(valor_duplicado);
        $('#data_alerta_dados').text(data_alerta);
        
        // Preenchendo os detalhes do alerta (se for um texto único)
        $('#detalhes_alerta_text_dados').text(detalhes_alerta_text);

        // Lógica para alertas adicionais (como telefone de referência)
        let alertasAdicionaisHtml = '';
        if (telefone_referencia !== 'N/A' && telefone_referencia === cpf_cliente) { 
             alertasAdicionaisHtml += `<li>- Telefone de referência informado (${telefone_referencia}) é o mesmo do cliente (${nome_cliente})</li>`;
        }
        // Adicione outros alertas dinâmicos aqui, se houver
        // Ex: if (some_condition) { alertasAdicionaisHtml += `<li>- Algum outro alerta</li>`; }

        $('#alertas_referencia_lista').html(alertasAdicionaisHtml || '<li>Nenhum alerta específico.</li>');


        // Preenchendo a Seção de Solicitação de Valor
        $('#valor_solicitado_dados').text(parseFloat(valor_solicitado).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#parcelamento_dados').text(parcelamento);

        $('#modalDados').modal('show'); // Exibe o modal
    }

    // Função auxiliar para determinar a classe do badge de status
    function getBadgeClass(status) {
        switch (status.toLowerCase()) {
            case 'aprovado':
                return 'badge-success';
            case 'em análise':
                return 'badge-info';
            case 'pendência':
                return 'badge-warning';
            case 'reprovado':
                return 'badge-danger';
            default:
                return 'badge-secondary'; // Cor padrão para outros status
        }
    }

    // ... (restante das suas funções CRUD como inserir, editar, limparCampos, selecionar, deletarSel, excluir)
    // Se você tiver outras funções como 'deletarSel()', 'inserir()', etc., elas devem vir aqui.
</script>