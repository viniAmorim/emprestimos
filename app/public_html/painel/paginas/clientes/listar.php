<?php
@session_start();
$visualizar_usuario = @$_SESSION['visualizar'];
$id_usuario = @$_SESSION['id'];

$tabela = 'clientes';
require_once("../../../conexao.php");

$data_atual = date('Y-m-d');

$status = @$_POST['p1'];
$estagio_cliente = @$_POST['p2'];
$alerta_busca = @$_POST['p3'];

// Sempre utilize o alias 'c' para a tabela principal
$tabela_com_alias = 'clientes c';
$join = "";
$filtros = [];

if($visualizar_usuario == 'Não'){
    // Esta condição deve ser tratada como um filtro, mas garantindo que o " and " seja o único
    $sql_visualizar = " and c.usuario = '$id_usuario' ";
}else{
    $sql_visualizar = " ";
}

// Lógica de filtro padrão para a primeira carga da página
if ($estagio_cliente == "") {
    // Se nenhum filtro de estágio for enviado, aplica o filtro padrão
    $filtros[] = "c.estagio_cliente IN ('Em análise', 'pendente', 'Nao validado')";
} else {
    // Se o filtro de estágio for enviado, use-o
    $filtros[] = "c.estagio_cliente = '$estagio_cliente'";
}

// Filtros adicionais (status e alerta)
if ($status != "") {
    $filtros[] = "c.status_cliente = '$status'";
}

if ($alerta_busca == "ComAlerta") {
    // Clientes que possuem ALERTA (PENDENTE OU IGNORADO)
    // Usamos um WHERE com subconsulta para evitar duplicação de linhas e manter a performance
    $filtros[] = "c.id IN (SELECT id_cliente_cadastrado FROM alertas_duplicidade)";
    
} else if ($alerta_busca == "Pendentes") {
    // Clientes que possuem pelo menos um ALERTA PENDENTE (resolvido = 0)
    $filtros[] = "c.id IN (SELECT id_cliente_cadastrado FROM alertas_duplicidade WHERE resolvido = 0)";
    
} else if ($alerta_busca == "ApenasIgnorados") {
    // Clientes que possuem alertas, mas TODOS estão RESOLVIDOS (resolvido = 1)
    // É a intersecção: clientes que estão na tabela de alertas, MAS não estão na lista de clientes com alertas PENDENTES.
    $filtros[] = "c.id IN (SELECT id_cliente_cadastrado FROM alertas_duplicidade)";
    $filtros[] = "c.id NOT IN (SELECT id_cliente_cadastrado FROM alertas_duplicidade WHERE resolvido = 0)";
    
} else if ($alerta_busca == "SemAlerta") {
    // Clientes que NÃO possuem alertas
    $filtros[] = "c.id NOT IN (SELECT id_cliente_cadastrado FROM alertas_duplicidade)";
}

// ----------------------------------------------------
// NOVO BLOCO DE MONTAGEM DA QUERY CORRIGIDO
// ----------------------------------------------------

// 1. Constrói a string de filtros (sem o "WHERE" inicial)
$sql_filtros = "";
if (count($filtros) > 0) {
    $sql_filtros = implode(" AND ", $filtros);
}

// Inicializa o array de condições que será usado no WHERE
$condicoes_finais = [];

// Adiciona a condição base (id > 0)
$condicoes_finais[] = "c.id > 0";

// Adiciona os filtros de busca/padrão, se existirem
if (!empty($sql_filtros)) {
    $condicoes_finais[] = $sql_filtros;
}

// Adiciona o filtro de visualização do usuário, se aplicável
if (!empty(trim($sql_visualizar))) {
    // Como $sql_visualizar inicia com " and ", removemos os primeiros 4 caracteres
    // para tratar a condição de forma limpa.
    $condicoes_finais[] = substr(trim($sql_visualizar), 4);
}

// Monta a cláusula WHERE final, unindo todas as condições com " AND "
$sql_where_final = "WHERE " . implode(" AND ", $condicoes_finais);


$query = $pdo->query("SELECT c.* FROM $tabela_com_alias $sql_where_final ORDER BY c.id DESC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);

if ($linhas > 0) {
    echo <<<HTML
    <small>
    <table class="table table-hover" id="tabela">
    <thead>
    <tr>
    <th>Nome</th>
    <th class="esc">CPF / CNPJ</th> 
    <th class="esc">Telefone</th>
    <th class="esc">Status</th>
    <th class="esc">Email</th>
    <th class="esc">Data Cadastro</th>
    <th class="esc">Grupo / Local</th>
    <th class="esc">Foto</th>
    <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    HTML;

    for ($i = 0; $i < $linhas; $i++) {
        $id = $res[$i]['id'];
        $nome = $res[$i]['nome'];
        $telefone = $res[$i]['telefone'];
        $email = $res[$i]['email'];
        $cpf = $res[$i]['cpf'];
        $endereco = $res[$i]['endereco'];
        $data_nasc = $res[$i]['data_nasc'];
        $data_cad = $res[$i]['data_cad'];
        $pix = $res[$i]['pix'];
        $indicacao = $res[$i]['indicacao'];
        $bairro = $res[$i]['bairro'];
        $cidade = $res[$i]['cidade'];
        $estado = $res[$i]['estado'];
        $cep = $res[$i]['cep'];
        $pessoa = $res[$i]['pessoa'];

        $cpf = $res[$i]['cpf'];
        $cpf_sem_mascara = preg_replace("/[^0-9]/", "", $cpf); 

        $nome_sec = @$res[$i]['nome_sec'];
        $telefone_sec = @$res[$i]['telefone_sec'];
        $endereco_sec = @$res[$i]['endereco_sec'];
        $grupo = @$res[$i]['grupo'];
        $status = @$res[$i]['status'];
        $comprovante_rg = @$res[$i]['comprovante_rg'];
        $comprovante_endereco = @$res[$i]['comprovante_endereco'];

        $telefone2 = @$res[$i]['telefone2'];
        $foto = @$res[$i]['foto'];
        $status_cliente = @$res[$i]['status_cliente'];
        $api_pgto = @$res[$i]['api_pgto'];

        $validado = $res[$i]['validado'];

        $data_nascF = implode('/', array_reverse(explode('-', $data_nasc ?? '')));
        $data_cadF = implode('/', array_reverse(explode('-', $data_cad ?? '')));

        $tel_whatsF = '55' . preg_replace('/[ ()-]+/', '', $telefone ?? '');

        // --- LÓGICA CORRIGIDA: MOVIDA PARA O INÍCIO DO LOOP ---
        $cor = '';
        $ocultar_cor = 'none';

        $query2 = $pdo->prepare("SELECT cor FROM status_clientes WHERE nome = :status_cliente");
        $query2->bindValue(":status_cliente", $status_cliente);
        $query2->execute();
        $res2 = $query2->fetch(PDO::FETCH_ASSOC);

        if ($res2 && $res2['cor'] != "") {
            $cor = $res2['cor'];
            $ocultar_cor = '';
        }

        // Verifica se existem alertas de duplicidade para este cliente
        $query_alerta = $pdo->prepare("SELECT COUNT(*) AS total_alertas FROM alertas_duplicidade WHERE id_cliente_cadastrado = :id AND resolvido = 0");
        $query_alerta->bindValue(":id", $id);
        $query_alerta->execute();
        $res_alerta = $query_alerta->fetch(PDO::FETCH_ASSOC);
        $total_alertas = $res_alerta['total_alertas'];

        $btn_alerta_class = ($total_alertas > 0) ? '' : 'ocultar';

        //verificar total de emprestimos do cliente
        $query2 = $pdo->query("SELECT * from emprestimos where cliente = '$id'");
        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        $total_emprestimos = @count($res2);

        $query2 = $pdo->query("SELECT * from cobrancas where cliente = '$id'");
        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        $total_cobrancas = @count($res2);

        $query2 = $pdo->query("SELECT * from receber where referencia = 'Conta' and cliente = '$id'");
        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        $total_contas = @count($res2);


        $ocultar_empre = '';
        if ($recursos == 'Cobranças') {
            $ocultar_empre = 'ocultar';
        }

        $ocultar_cobr = '';
        if ($recursos == 'Empréstimos') {
            $ocultar_cobr = 'ocultar';
        }

        // extensão do arquivo (comprovante de endereço)
        $ext = (isset($comprovante_endereco) && is_string($comprovante_endereco) && $comprovante_endereco !== '')
            ? pathinfo($comprovante_endereco, PATHINFO_EXTENSION)
            : '';

        if ($ext === 'pdf') {
            $tumb_comprovante_endereco = 'pdf.png';
        } else if ($ext === 'rar' || $ext === 'zip') {
            $tumb_comprovante_endereco = 'rar.png';
        } else {
            $tumb_comprovante_endereco = $comprovante_endereco ?: 'sem-foto.png';
        }

        // extensão do arquivo (comprovante de RG)
        $ext = (isset($comprovante_rg) && is_string($comprovante_rg) && $comprovante_rg !== '')
            ? pathinfo($comprovante_rg, PATHINFO_EXTENSION)
            : '';

        if ($ext === 'pdf') {
            $tumb_comprovante_rg = 'pdf.png';
        } else if ($ext === 'rar' || $ext === 'zip') {
            $tumb_comprovante_rg = 'rar.png';
        } else {
            $tumb_comprovante_rg = $comprovante_rg ?: 'sem-foto.png';
        }

        $enderecoF2 = rawurlencode($endereco ?? '');

        echo <<<HTML
        <tr style="">
        <td>
        <input type="checkbox" id="seletor-{$id}" class="form-check-input" onchange="selecionar('{$id}')">
        <i class="fa fa-square" style="color:{$cor}; display:{$ocultar_cor}"></i>
        {$nome}
        </td>
        <td class="esc" data-search="{$cpf_sem_mascara}">{$cpf}</td>
        <td class="esc">{$telefone}</td>
        <td class="esc"><span class="me-1 my-2 p-1" style="color:{$cor};">{$status_cliente}</span></td>
        <td class="esc">{$email}</td>
        <td class="esc">{$data_cadF}</td>
        <td class="esc">{$grupo}</td>
        <td class="esc"><img src="images/clientes/{$foto}" width="25px"></td>
        <td>
        <!-- <big><a href="#" onclick="editar('{$id}','{$nome}','{$telefone}','{$cpf}','{$email}','{$enderecoF2}','{$data_nascF}', '{$pix}', '{$indicacao}', '{$bairro}', '{$cidade}', '{$estado}', '{$cep}', '{$pessoa}', '{$nome_sec}', '{$telefone_sec}', '{$endereco_sec}', '{$grupo}', '{$tumb_comprovante_endereco}', '{$tumb_comprovante_rg}', '{$telefone2}', '{$foto}', '{$status_cliente},'{$api_pgto}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big> -->

        <li class="dropdown head-dpdn2" style="display: inline-block;">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>
            <ul class="dropdown-menu" style="margin-left:-230px;">
                <li>
                    <div class="notification_desc2">
                        <p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
                    </div>
                </li>
            </ul>
        </li>

        <big><a href="#" onclick="mostrar('{$id}', '{$nome}','{$telefone}','{$cpf}','{$email}','{$enderecoF2}','{$data_nascF}', '{$data_cadF}', '{$pix}', '{$indicacao}', '{$bairro}', '{$cidade}', '{$estado}', '{$cep}', '{$total_emprestimos}', '{$total_cobrancas}', '{$pessoa}', '{$total_contas}', '{$nome_sec}', '{$telefone_sec}', '{$endereco_sec}', '{$grupo}', '{$comprovante_endereco}', '{$comprovante_rg}', '{$tumb_comprovante_endereco}', '{$tumb_comprovante_rg}', '{$telefone2}', '{$foto}', '{$validado}' , '{$api_pgto}')" title="Mostrar Dados"><i class="fa fa-info-circle text-primary"></i></a></big>

        <big><a class="" href="http://api.whatsapp.com/send?1=pt_BR&phone={$tel_whatsF}" title="Whatsapp" target="_blank"><i class="fa fa-whatsapp " style="color:green"></i></a></big>

        <big><a href="index.php?pagina=analise_cliente&id={$id}" title="Análise do Cliente"><i class="fa fa-bar-chart text-danger"></i></a></big>

        <big><a href="#" onclick="arquivo('{$id}','{$nome}')" title="Inserir / Ver Arquivos"><i class="fa fa-file-archive-o" style="color:#3d1002"></i></a></big>

        <big><a class="{$ocultar_empre}" href="#" onclick="emprestimo('{$id}','{$nome}')" title="Novo Empréstimo"><i class="fa fa-usd" style="color:green"></i></a></big>

        <big><a class="{$ocultar_cobr}" href="#" onclick="cobranca('{$id}','{$nome}')" title="Cobrança Recorrente"><i class="fa fa-money" style="color:green"></i></a></big>

        </td>
        </tr>
        HTML;

    }

    echo <<<HTML
    </tbody>
    <small><div align="center" id="mensagem-excluir"></div></small>

    </table>
    <br>
    <div align="right">Total Clientes: {$linhas}</div>
    HTML;

} else {
    echo '<small>Nenhum Registro Encontrado!</small>';
}
?>

<style>
.btn-validar-cliente {
    padding: 12px 24px;
    font-size: 16px;
    border-radius: 6px;
    transition: background-color 0.3s, transform 0.2s;
}

.btn-validar-cliente:hover {
    background-color: #218838;
    transform: scale(1.03);
}

/* Estilo para ocultar o botão de alerta quando não houver alertas */
.ocultar {
    display: none !important;
}
/* Estilo para o título "ALERTAS:" */
.alertas-titulo {
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
    border-bottom: 2px solid #e53935;
    padding-bottom: 0.5rem;
    margin-bottom: 1.5rem;
}

/* Estilo de cada item de alerta */
.alerta-item {
    padding: 1rem 0;
    color: #555;
    line-height: 1.6;
    position: relative;
    padding-left: 2.5rem; /* Espaço para o ícone */
    border-bottom: 1px solid #eee;
}

/* Para o último item de alerta, remove a borda inferior */
.alerta-item:last-child {
    border-bottom: none;
}

/* Pseudo-elemento para o ícone de alerta */
.alerta-item::before {
    content: "!";
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background-color: #e53935;
    color: #fff;
    font-weight: 700;
    font-size: 0.9rem;
    width: 1.5rem;
    height: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

/* Estilo para os textos importantes em negrito */
.alerta-item strong {
    font-weight: 700; /* Negrito mais forte */
    color: #e53935; /* Usa o vermelho para destacar o texto */
}

/* Estilo para a mensagem de nenhum alerta encontrado */
.alerta-vazio {
    color: #888;
    font-style: italic;
    font-size: 1rem;
    margin: 1rem 0;
}
</style>

<div class="modal fade" id="modalAlertas" tabindex="-1" role="dialog" aria-labelledby="modalAlertasLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAlertasLabel">Alertas de Duplicidade (<span id="nome-cliente-alerta"></span>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="lista-alertas">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tabela').DataTable({
            "language": {
                //"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
            },
            "ordering": false,
            "stateSave": true
        });
    });

    function editar(id, nome, telefone, cpf, email, endereco, data_nasc, pix, indicacao, bairro, cidade, estado, cep, pessoa, nome_sec, telefone_sec, endereco_sec, grupo, comprovante_endereco, comprovante_rg, telefone2, foto, status_cliente, api_pgto) {
        $('#mensagem').text('');
        $('#titulo_inserir').text('Editar Registro');

        $('#id').val(id);
        $('#nome').val(nome);
        $('#email').val(email);
        $('#telefone').val(telefone);
        $('#endereco').val(decodeURIComponent(endereco));
        $('#cpf').val(cpf);
        $('#data_nasc').val(data_nasc);
        $('#pix').val(pix);
        $('#indicacao').val(indicacao);
        $('#bairro').val(bairro);
        $('#cidade').val(cidade);
        $('#estado').val(estado).change();
        $('#pessoa').val(pessoa).change();
        $('#cep').val(cep);

        $('#nome_sec').val(nome_sec);
        $('#telefone_sec').val(telefone_sec);
        $('#endereco_sec').val(endereco_sec);
        $('#grupo').val(grupo);
        $('#telefone2').val(telefone2);
        $('#status_cliente').val(status_cliente).change();

        $('#api_pgto').val(api_pgto).change();

        $('#target-comprovante-endereco').attr('src', 'images/comprovantes/' + comprovante_endereco);
        $('#target-comprovante-rg').attr('src', 'images/comprovantes/' + comprovante_rg);
        $('#target').attr('src', 'images/clientes/' + foto);

        $('#modalForm').modal('show');
    }

    function mostrar(id, nome, telefone, cpf, email, endereco, data_nasc, data_cad, pix, indicacao, bairro, cidade, estado, cep, total_emprestimos, total_cobrancas, pessoa, total_contas, nome_sec, telefone_sec, endereco_sec, grupo, comprovante_endereco, comprovante_rg, tumb_comprovante_endereco, tumb_comprovante_rg, telefone2, foto, validado,api_pgto ) {

        if (comprovante_endereco.trim() == "" || comprovante_endereco.trim() == "sem-foto.png") {
            $('#div_link_comprovante_endereco').hide();
        } else {
            $('#div_link_comprovante_endereco').show();
        }

        if (comprovante_rg.trim() == "" || comprovante_rg.trim() == "sem-foto.png") {
            $('#div_link_comprovante_rg').hide();
        } else {
            $('#div_link_comprovante_rg').show();
        }

        if (foto.trim() == "" || foto.trim() == "sem-foto.jpg") {
            $('#div_foto').hide();
        } else {
            $('#div_foto').show();
        }

        if (total_emprestimos > 0) {
            $('#dados_emp').show();
        } else {
            $('#dados_emp').hide();
        }

        if (total_cobrancas > 0) {
            $('#dados_cob').show();
        } else {
            $('#dados_cob').hide();
        }

        if (total_contas > 0) {
            $('#dados_deb').show();
        } else {
            $('#dados_deb').hide();
        }

        // if (validado === '0' || validado === 'false') {
        //     const btnValidar = `
        //         <button class="btn btn-success btn-validar-cliente" onclick="validarCliente('${id}', '${nome}')">
        //             <i class="fa fa-check-circle"></i> Validar Cliente
        //         </button>
        //     `;
        //     document.getElementById('div_botao_validar').innerHTML = btnValidar;
        // } else {
        //     document.getElementById('div_botao_validar').innerHTML = '';
        // }


        $('#titulo_dados').text(nome);
        $('#email_dados').text(email);
        $('#telefone_dados').text(telefone);
        $('#endereco_dados').text(decodeURIComponent(endereco));
        $('#cpf_dados').text(cpf);
        $('#data_nasc_dados').text(data_nasc);
        $('#data_cad_dados').text(data_cad);
        $('#pix_dados').text(pix);
        $('#indicacao_dados').text(indicacao);
        $('#bairro_dados').text(bairro);
        $('#cidade_dados').text(cidade);
        $('#estado_dados').text(estado);
        $('#cep_dados').text(cep);
        $('#pessoa_dados').text(pessoa);

        $('#nome_sec_dados').text(nome_sec);
        $('#telefone_sec_dados').text(telefone_sec);
        $('#endereco_sec_dados').text(endereco_sec);
        $('#grupo_dados').text(grupo);

        $('#telefone2_dados').text(telefone2);

        $('#cliente_baixar').val('');
        $('#status_cliente').val('').change();

        $('#api_pgto_dados').text(api_pgto);

        $('#id_cliente_mostrar').val(id);

        listarEmprestimos(id);
        listarCobrancas(id);
        listarDebitos(id);

        $('#link_comprovante_endereco').attr('href', 'images/comprovantes/' + comprovante_endereco);
        $('#target_mostrar_comprovante_endereco').attr('src', 'images/comprovantes/' + tumb_comprovante_endereco);

        $('#link_comprovante_rg').attr('href', 'images/comprovantes/' + comprovante_rg);
        $('#target_mostrar_comprovante_rg').attr('src', 'images/comprovantes/' + tumb_comprovante_rg);

        $('#target_mostrar_foto').attr('src', 'images/clientes/' + foto);

        //$('#modalDados').modal('show');
        $('#modalDados').appendTo('body').modal('show');
        

    }

    function mostrarAlertas(id, nome) {
    // Limpa o conteúdo do modal
    $('#lista-alertas').html('<p class="text-center">Carregando...</p>');
    $('#nome-cliente-alerta').text(nome);

    // Requisição AJAX para buscar os alertas
    $.ajax({
        url: '/painel/paginas/clientes/buscar_alertas.php',
        method: 'POST',
        data: {
            id_cliente: id
        },
        dataType: 'json',
        success: function(response) {
            if (response.success && response.alertas.length > 0) {
                let alertasHTML = '<h5 class="alertas-titulo">ALERTAS:</h5>';
                let nomeClienteAlerta = $('#nome-cliente-alerta').text();

                response.alertas.forEach(alerta => {
                    if (alerta.tipo_alerta === 'Nome Duplicado') {
                        alertasHTML += `
                            <div class="alerta-item">
                                <p>- O nome <strong>${alerta.valor_duplicado}</strong> já existe para o cliente <strong>${alerta.nome_duplicado}</strong>.</p>
                            </div>
                        `;
                    } else if (alerta.tipo_alerta === 'Telefone Duplicado') {
                        alertasHTML += `
                            <div class="alerta-item">
                                <p>- O telefone <strong>${alerta.valor_duplicado}</strong> já foi utilizado em outro cadastro do cliente <strong>${alerta.nome_duplicado}</strong>.</p>
                            </div>
                        `;
                    } else {
                        alertasHTML += `
                            <div class="alerta-item">
                                <p class="mb-0"><span class="alerta-tipo">${alerta.tipo_alerta}:</span> <span class="alerta-valor"><strong>${alerta.valor_duplicado}</strong></span></p>
                                <p class="alerta-data">Data do Alerta: ${alerta.data_alerta}</p>
                            </div>
                        `;
                    }
                });
                $('#lista-alertas').html(alertasHTML);
            } else {
                $('#lista-alertas').html('<p class="text-center alerta-vazio">Nenhum alerta de duplicidade encontrado para este cliente.</p>');
            }
        },
        error: function() {
            $('#lista-alertas').html('<p class="text-danger text-center">Erro ao carregar os alertas. Tente novamente.</p>');
        }
    });

    // Exibe o modal
    //$('#modalAlertas').modal('show');
    $('#modalAlertas').appendTo('body').modal('show');
}

    function limparCampos() {
        $('#id').val('');
        $('#nome').val('');
        $('#email').val('');
        $('#telefone').val('');
        $('#endereco').val('');
        $('#cpf').val('');
        $('#data_nasc').val('');
        $('#pix').val('');
        $('#indicacao').val('');
        $('#mensagem_whats').val('');

        $('#api_pgto').val('').change();

        $('#bairro').val('');
        $('#cidade').val('');
        $('#estado').val('').change();
        $('#cep').val('');
        $('#data_emp').val('<?= $data_atual ?>');
        $('#data_cob').val('<?= $data_atual ?>');

        $('#valor').val('');
        $('#parcelas').val('1');
        $('#juros').val('<?= $juros_sistema ?>');
        $('#multa').val('<?= $multa_sistema ?>');
        $('#juros_emp').val('<?= $juros_emprestimo ?>');
        $('#id_emp').val('');
        $('#frequencia').val('30').change();

        $('#telefone2').val('');

        $('#target-comprovante-endereco').attr('src', 'images/comprovantes/sem-foto.png');
        $('#target-comprovante-rg').attr('src', 'images/comprovantes/sem-foto.png');
        $('#target').attr('src', 'images/clientes/sem-foto.jpg');

        mascara_valor('juros')
        mascara_valor('multa')


        $('#data_venc_nova').val('<?= $data_atual ?>');
        $('#descricao_nova').val('');
        $('#obs_nova').val('');
        $('#valor_nova').val('');

        $('#valor_cob').val('');
        $('#parcelas_cob').val('');
        $('#id_cob').val('');
        $('#frequencia_cob').val('30').change();
        $('#pessoa').val('Física').change();
        $('#obs_cob').val('');

        $('#ids').val('');
        $('#btn-deletar').hide();
    }

    function selecionar(id) {

        var ids = $('#ids').val();

        if ($('#seletor-' + id).is(":checked") == true) {
            var novo_id = ids + id + '-';
            $('#ids').val(novo_id);
        } else {
            var retirar = ids.replace(id + '-', '');
            $('#ids').val(retirar);
        }

        var ids_final = $('#ids').val();
        if (ids_final == "") {
            $('#btn-deletar').hide();
            $('#btn-cobrar').hide();
            $('#mensagem_whats').hide();
        } else {
            $('#btn-deletar').show();
            $('#btn-cobrar').show();
            $('#mensagem_whats').show();
        }
    }

    function deletarSel() {
        var ids = $('#ids').val();
        var id = ids.split("-");

        for (i = 0; i < id.length - 1; i++) {
            excluir(id[i]);
        }

        limparCampos();
    }


    function cobrarSel() {
        var ids = $('#ids').val();
        var id = ids.split("-");
        var mensagem = $('#mensagem_whats').val();

        if (mensagem == "") {
            alert('Digite a mensagem na caixa ao lado');
            return;
        }

        for (i = 0; i < id.length - 1; i++) {
            mensagem_w(id[i], mensagem);
        }

        limparCampos();
    }



    function arquivo(id, nome) {
        $('#nome_arquivo').text(nome);
        $('#id_arquivo').val(id);
        $('#mensagem_arquivo').text('');

        listarArquivos();
        //$('#modalArquivos').modal('show');

        $('#modalArquivos').appendTo('body').modal('show');
        
    }

    function emprestimo(id, nome) {
        console.log('BOTÃO EMPRÉSTIMO');
        console.log('CHAMANDO FUNÇÃO EMPRÉSTIMO para ID:', id, 'NOME:', nome);
        $('#titulo_emp').text('Empréstimo: ' + nome);
        $('#id_emp').val(id);
        $('#mensagem_emp').text('');
        $('#frequencia').val('30').change();

        mascara_valor('juros');
        mascara_valor('multa');

        $('#modalEmprestimo').appendTo('body').modal('show');

        //$('#modalEmprestimo').modal('show');
    }

    function cobranca(id, nome) {
        $('#titulo_cob').text('Cobrança: ' + nome);
        $('#id_cob').val(id);
        $('#mensagem_cob').text('');
        $('#frequencia_cob').val('30').change();
        $('#descricao_cobranca').val('');

        //$('#modalCobranca').modal('show');
        $('#modalCobranca').appendTo('body').modal('show');
    }
</script>