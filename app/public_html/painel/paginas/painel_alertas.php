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
        /* Estilização Geral do Modal */
#modalDados .modal-content {
    border-radius: 12px; /* Bordas mais suaves */
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1); /* Sombra mais pronunciada para o modal */
    overflow: hidden; /* Garante que o conteúdo não vaze */
}

#modalDados .modal-header {
    background-color: #f8f9fa; /* Fundo claro para o cabeçalho */
    border-bottom: 1px solid #e9ecef;
    padding: 20px 30px;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

#modalDados .modal-title {
    color: #343a40;
    font-weight: 700;
    font-size: 2.2rem; /* Tamanho maior para o título */
}

#modalDados .close {
    font-size: 2.5rem; /* Botão de fechar maior */
    color: #6c757d;
    transition: color 0.2s ease-in-out;
    margin-top: -15px; /* Ajuste para centralizar o X */
}

#modalDados .close:hover {
    color: #dc3545;
}

#modalDados .modal-body {
    padding: 30px;
}

#modalDados .modal-footer {
    border-top: 1px solid #e9ecef;
    padding: 20px 30px;
    background-color: #f8f9fa;
    border-bottom-left-radius: 12px;
    border-bottom-right-radius: 12px;
}

/* Sombreamento nas bordas das imagens */
.img-fluid.rounded-circle.shadow,
.img-fluid.rounded.shadow-sm {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important; /* Sombra mais suave e proeminente */
    transition: transform 0.3s ease-in-out;
}

.img-fluid.rounded-circle.shadow:hover,
.img-fluid.rounded.shadow-sm:hover {
    transform: scale(1.02); /* Leve zoom ao passar o mouse */
}


/* Checkboxes alinhados à esquerda, maiores e com fundo levemente azul */
.custom-control.custom-checkbox {
    padding-left: 2.5rem; /* Ajusta o padding para o alinhamento */
    margin-bottom: 0.75rem; /* Espaçamento entre os checkboxes */
}

.custom-control-input:checked ~ .custom-control-label::before {
    background-color: #007bff; /* Cor primária do Bootstrap */
    border-color: #007bff;
}

.custom-control-label::before {
    top: .25rem; /* Alinha verticalmente */
    left: 0; /* Alinha à esquerda */
    width: 1.5rem; /* Checkbox maior */
    height: 1.5rem; /* Checkbox maior */
    border-radius: 0.25rem; /* Levemente arredondado */
    background-color: #e9f5ff; /* Fundo levemente azul */
    border: 1px solid #b3d7ff; /* Borda azul clara */
}

.custom-control-label::after {
    top: .25rem; /* Alinha verticalmente */
    left: 0; /* Alinha à esquerda */
    width: 1.5rem; /* Marca de check maior */
    height: 1.5rem; /* Marca de check maior */
    line-height: 1.5rem; /* Centraliza o ícone */
}

/* Alertas de duplicidade com fundo levemente vermelho */
.alert-duplicidade {
    background-color: #ffebe6; /* Vermelho muito claro, quase um pêssego */
    color: #8b0000; /* Cor de texto vermelho escuro */
    border: 1px solid #ffcccb; /* Borda sutil */
    border-radius: 8px;
    padding: 15px 20px;
    margin-bottom: 20px;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(255, 0, 0, 0.1); /* Sombra sutil vermelha */
}

/* Estilo para as informações dentro do alerta de duplicidade */
.alert-duplicidade p {
    margin-bottom: 8px;
}

.alert-duplicidade strong {
    color: #dc3545; /* Vermelho mais forte para os títulos */
}

/* Melhorias gerais para o texto */
.form-control-plaintext {
    font-size: 1.1em;
    padding: 0.25rem 0;
}

hr.my-4 {
    border-top: 1px solid #dee2e6;
    margin-top: 2rem !important;
    margin-bottom: 2rem !important;
}

.list-unstyled.mt-2.ml-3 li {
    padding: 5px 0;
    border-bottom: 1px dashed #e9ecef;
}

.list-unstyled.mt-2.ml-3 li:last-child {
    border-bottom: none;
}
/* Container para as informações do cliente */
.info-cliente-card {
    background-color: #f8f9fa; /* Um fundo suave */
    border-radius: 8px; /* Cantos levemente arredondados */
    padding: 20px 25px; /* Espaçamento interno */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); /* Sombra sutil para destacar como um "cartão" */
    margin-bottom: 20px; /* Espaço abaixo do cartão */
    display: flex; /* Para flexbox */
    flex-direction: column; /* Itens em coluna */
    gap: 10px; /* Espaço entre os parágrafos */
}

/* Estilo para os parágrafos de informação */
.info-cliente-card p {
    margin-bottom: 0 !important; /* Remove o margin padrão do Bootstrap */
    display: flex; /* Para alinhar o label e o valor */
    align-items: baseline; /* Alinha o texto na linha de base */
    font-size: 1.1em; /* Fonte um pouco maior */
}

/* Estilo para os rótulos (Nome, CPF, Cliente ID) */
.info-cliente-card p strong {
    color: #495057; /* Cor mais escura para os rótulos */
    min-width: 90px; /* Garante alinhamento dos rótulos */
    flex-shrink: 0; /* Impede que o strong encolha */
    margin-right: 10px; /* Espaço entre o rótulo e o valor */
}

/* Estilo para os valores (o span com o ID) */
.info-cliente-card p span.form-control-plaintext {
    border-bottom: none !important; /* Remove a borda inferior */
    padding: 0 !important; /* Remove padding desnecessário */
    color: #212529; /* Cor de texto padrão para o valor */
    font-weight: 500; /* Levemente mais encorpado */
    flex-grow: 1; /* Permite que o span ocupe o espaço restante */
}

/* Estilo para os títulos de seção de imagem */
.section-title {
    font-size: 1.6rem; /* Tamanho da fonte maior */
    font-weight: 600; /* Levemente menos negrito que o padrão `bold` */
    color: #34495e; /* Uma cor mais profunda e moderna para o título */
    margin-bottom: 1.5rem; /* Mais espaço abaixo do título */
    text-align: center; /* Centraliza o título */
    position: relative; /* Para o pseudo-elemento */
    padding-bottom: 8px; /* Espaço para a linha abaixo */
}

/* Adiciona uma linha sutil abaixo dos títulos */
.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 60px; /* Largura da linha */
    height: 3px; /* Espessura da linha */
    background-color: #007bff; /* Cor da linha, pode ser um azul do tema */
    border-radius: 2px; /* Cantos arredondados para a linha */
}

/* Estilo para a seção de Alertas de Duplicidade */
.alert-duplicidade-card {
    background-color: #fef2f2; /* Um vermelho pastel bem suave */
    border: 1px solid #fbdada; /* Borda sutil para definir o "cartão" */
    border-radius: 10px; /* Cantos arredondados */
    padding: 20px 25px; /* Espaçamento interno */
    margin-bottom: 30px; /* Espaço para a próxima seção */
    box-shadow: 0 4px 15px rgba(255, 0, 0, 0.08); /* Sombra sutil com um toque avermelhado */
}

/* Título dentro do cartão de alerta */
.alert-duplicidade-card .section-title-alt {
    font-size: 1.5rem; /* Um pouco menor que os títulos de imagem, mas ainda proeminente */
    font-weight: 700; /* Mais negrito para chamar atenção */
    color: #c0392b; /* Um vermelho mais vibrante para o título do alerta */
    margin-bottom: 15px;
    position: relative;
    padding-bottom: 5px;
    text-align: left; /* Alinha o título à esquerda */
}

/* Linha sutil abaixo do título do alerta */
.alert-duplicidade-card .section-title-alt::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0; /* Alinha a linha à esquerda */
    width: 50px; /* Largura da linha */
    height: 3px;
    background-color: #e74c3c; /* Cor da linha */
    border-radius: 2px;
}

/* Parágrafos de informações dentro do alerta */
.alert-duplicidade-card p {
    margin-bottom: 8px !important; /* Espaço entre as informações */
    display: flex;
    align-items: baseline;
    font-size: 1.05em; /* Tamanho da fonte */
    line-height: 1.4;
}

/* Rótulos em negrito dentro do alerta */
.alert-duplicidade-card p strong {
    color: #8b0000; /* Vermelho escuro para os rótulos */
    min-width: 120px; /* Ajuda a alinhar os valores */
    flex-shrink: 0;
    margin-right: 10px;
}

/* Valores dentro do alerta */
.alert-duplicidade-card p span.form-control-plaintext {
    border-bottom: none !important; /* Remove a borda inferior */
    padding: 0 !important;
    color: #333; /* Cor de texto para o valor */
    font-weight: 500;
    flex-grow: 1;
}

/* Estilo para a lista de alertas de referência */
#alertas_referencia_lista {
    margin-top: 10px !important;
    margin-left: 20px !important; /* Indenta a lista */
    list-style: disc; /* Estilo de marcador de lista */
    color: #444;
}

#alertas_referencia_lista li {
    padding: 4px 0;
    border-bottom: 1px dashed #f0c0c0; /* Linha tracejada suave */
    font-size: 0.95em;
}

#alertas_referencia_lista li:last-child {
    border-bottom: none;
}

/* Estilo para a seção de Detalhes da Solicitação */
.solicitacao-card {
    background-color: #f8f9fa; /* Fundo suave, similar ao do cliente */
    border-radius: 10px;
    padding: 20px 25px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); /* Sombra sutil */
    margin-bottom: 20px;
}

/* Título dentro do cartão de solicitação */
.solicitacao-card .section-title-alt {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50; /* Azul escuro/cinza para o título */
    margin-bottom: 15px;
    position: relative;
    padding-bottom: 5px;
    text-align: left;
}

/* Linha sutil abaixo do título da solicitação */
.solicitacao-card .section-title-alt::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 3px;
    background-color: #3498db; /* Azul do tema */
    border-radius: 2px;
}

/* Parágrafos de informações dentro da solicitação */
.solicitacao-card p {
    margin-bottom: 8px !important;
    display: flex;
    align-items: baseline;
    font-size: 1.05em;
    line-height: 1.4;
}

/* Rótulos em negrito dentro da solicitação */
.solicitacao-card p strong {
    color: #495057;
    min-width: 120px; /* Ajuda a alinhar os valores */
    flex-shrink: 0;
    margin-right: 10px;
}

/* Valores dentro da solicitação */
.solicitacao-card p span.form-control-plaintext {
    border-bottom: none !important;
    padding: 0 !important;
    color: #212529;
    font-weight: 500;
    flex-grow: 1;
}
/* Container para os grupos de checkbox, garantindo um agrupamento visual */
.checkbox-group-container {
    background-color: #f0f8ff; /* Fundo muito levemente azul para as opções */
    border: 1px solid #cceeff; /* Borda sutil */
    border-radius: 10px; /* Cantos arredondados, ligeiramente mais que antes */
    padding: 20px 25px; /* Espaçamento interno mais generoso */
    margin-top: 25px; /* Espaço entre a imagem e as checkboxes */
    box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.08); /* Sombra interna sutil para profundidade */
}

/* Estilo para cada item individual de checkbox */
.custom-control.custom-checkbox {
    padding-left: 2.5rem; /* Espaçamento para o checkbox */
    margin-bottom: 1rem; /* Mais espaçamento entre cada checkbox */
    cursor: pointer; /* Indica que é clicável */
    position: relative; /* Necessário para posicionar o checkbox */
    user-select: none; /* Impede seleção de texto ao clicar */
    display: flex; /* Para alinhar o input e o label */
    align-items: center; /* Centraliza verticalmente o checkbox com o texto */
    padding: 8px 0; /* Aumenta a área de clique */
    transition: background-color 0.2s ease-in-out, transform 0.1s ease-out; /* Transições suaves */
    border-radius: 6px; /* Bordas arredondadas para a área de hover */
}

/* Efeito de hover e active para cada item de checkbox */
.custom-control.custom-checkbox:hover {
    background-color: #e6f7ff; /* Fundo suave ao passar o mouse */
    transform: translateY(-2px); /* Leve levantamento no hover */
}

.custom-control.custom-checkbox:active {
    transform: translateY(0); /* Retorna ao normal no clique */
}

/* Estilo do label (texto) do checkbox */
.custom-control-label {
    font-size: 1.05em; /* Fonte um pouco maior para o texto */
    color: #495057; /* Cor do texto do label */
    line-height: 1.6rem; /* Ajusta a altura da linha para alinhar com o checkbox maior */
    margin-left: 0.5rem; /* Espaço entre o checkbox e o texto */
    padding-top: 2px; /* Ajuste fino para alinhamento vertical */
    flex-grow: 1; /* Permite que o label ocupe o espaço restante */
}

/* Estilo do quadrado do checkbox (o ::before) */
.custom-control-label::before {
    content: ''; /* Essencial para pseudo-elementos */
    display: block; /* Garante que se comporta como um bloco */
    position: absolute; /* Posiciona o checkbox */
    top: 50%; /* Centraliza verticalmente */
    left: 0; /* Alinha à esquerda */
    transform: translateY(-50%); /* Ajuste final para centralização vertical */
    width: 1.8rem; /* Checkbox MAIOR */
    height: 1.8rem; /* Checkbox MAIOR */
    border-radius: 0.4rem; /* Cantos mais arredondados */
    background-color: #e9f5ff; /* Fundo levemente azul para o checkbox desmarcado */
    border: 2px solid #a8d6ff; /* Borda azul clara e mais definida */
    box-shadow: inset 0 1px 4px rgba(0,0,0,0.08); /* Sombra interna sutil para profundidade */
    transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
}

/* Estilo da marca de verificação (o ::after) */
.custom-control-label::after {
    content: ''; /* Essencial para pseudo-elementos */
    display: block; /* Garante que se comporta como um bloco */
    position: absolute; /* Posiciona a marca de verificação */
    top: 50%; /* Centraliza verticalmente */
    left: 0.2rem; /* Ajusta a posição da marca de verificação */
    transform: translateY(-50%) rotate(45deg); /* Rotaciona para formar o checkmark */
    width: 0.6rem; /* Largura da marca de verificação */
    height: 1.2rem; /* Altura da marca de verificação */
    border: solid white; /* Cor branca para a marca */
    border-width: 0 4px 4px 0; /* Espessura e formato do "V" */
    opacity: 0; /* Inicialmente invisível */
    transition: opacity 0.2s ease-in-out;
}

/* Estilo do checkbox quando marcado */
.custom-control-input:checked ~ .custom-control-label::before {
    background-color: #007bff; /* Azul primário quando marcado */
    border-color: #007bff;
    box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.2), 0 0 0 0.2rem rgba(0, 123, 255, 0.25); /* Sombra interna e externa para destaque */
}

.custom-control-input:checked ~ .custom-control-label::after {
    opacity: 1; /* Torna a marca de verificação visível quando marcado */
}

/* Estilo para o foco (acessibilidade com teclado) */
.custom-control-input:focus ~ .custom-control-label::before {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25); /* Anel de foco azul */
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
                          <div class="info-cliente-card">
                              <p class="mb-2"><strong>Nome:</strong> <span id="nome_cliente_completo_dados" class="form-control-plaintext py-1"></span></p>
                              <p class="mb-2"><strong>CPF:</strong> <span id="cpf_cliente_dados" class="form-control-plaintext py-1"></span></p>
                              <p class="mb-0"><strong>Cliente ID:</strong> <span id="id_cliente_cadastrado_dados" class="form-control-plaintext py-1"></span></p>
                          </div>
                        </div>
                        <div class="col-md-2 text-right">
                            <p class="mb-0"><strong>Status:</strong> <br><span id="status_cliente_dados" class="badge badge-primary py-2 px-3 mt-1" style="font-size: 0.9em;"></span></p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row mb-4">
                        <div class="col-md-6 text-center">
                            <h5 class="section-title">Foto CNH:</h5>
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
                            <h5 class="section-title">Comprovante de Endereço:</h5>
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

                    <div class="alert-duplicidade-card">
                      <h5 class="section-title-alt">Alertas de Duplicidade:</h5>
                      <p class="mb-2"><strong>Tipo de Alerta:</strong> <span id="tipo_alerta_dados" class="form-control-plaintext py-1"></span></p>
                      <p class="mb-2"><strong>Valor Duplicado:</strong> <span id="valor_duplicado_dados" class="form-control-plaintext py-1"></span></p>
                      <p class="mb-2"><strong>Data do Alerta:</strong> <span id="data_alerta_dados" class="form-control-plaintext py-1"></span></p>
                      <p class="mt-3">
                          <strong>Detalhes do Alerta:</strong> <span id="detalhes_alerta_text_dados" class="form-control-plaintext py-1"></span>
                          <ul id="alertas_referencia_lista" class="list-unstyled mt-2 ml-3">
                        </ul>
                      </p>
                  </div>

                  <hr class="my-4">

                  <div class="solicitacao-card">
                      <h5 class="section-title-alt">Detalhes da Solicitação:</h5>
                      <p class="mb-2"><strong>Valor Solicitado:</strong> R$ <span id="valor_solicitado_dados" class="form-control-plaintext py-1"></span></p>
                      <p class="mb-0"><strong>Parcelamento:</strong> <span id="parcelamento_dados" class="form-control-plaintext py-1"></span></p>
                  </div>
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