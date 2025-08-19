<?php
// Inclui a conexão com o banco de dados.
require_once("../conexao.php");

// Verifica se o ID do cliente foi fornecido na URL.
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID do cliente não fornecido.");
}

// Obtém o ID do cliente da URL.
$id_cliente = $_GET['id'];

// Prepara e executa a consulta para buscar todos os dados do cliente.
$query = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
$query->bindValue(":id", $id_cliente);
$query->execute();
$cliente = $query->fetch(PDO::FETCH_ASSOC);

// Se nenhum cliente for encontrado, exibe uma mensagem de erro.
if (!$cliente) {
    die("Cliente não encontrado.");
}

// Prepara e executa a consulta para buscar todos os alertas de duplicidade.
$query_alertas = $pdo->prepare("SELECT * FROM alertas_duplicidade WHERE id_cliente_cadastrado = :id_cliente ORDER BY data_alerta DESC");
$query_alertas->bindValue(":id_cliente", $id_cliente);
$query_alertas->execute();
$alertas_duplicidade = $query_alertas->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Análise do Cliente: <?= htmlspecialchars($cliente['nome'] ?? '') ?></title>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .section-title {
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .info-block {
            margin-bottom: 20px;
        }
        .info-block h6 {
            font-weight: bold;
            color: #555;
        }

        /* Sombreamento nas bordas das imagens */
        .img-fluid.rounded-circle.shadow,
        .img-fluid.rounded.shadow-sm {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
            transition: transform 0.3s ease-in-out;
        }

        .img-fluid.rounded-circle.shadow:hover,
        .img-fluid.rounded.shadow-sm:hover {
            transform: scale(1.02);
        }
       

        .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #007bff;
            border-color: #007bff;
        }

        .custom-control-label::before {
            top: .25rem;
            left: 0;
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 0.25rem;
            background-color: #e9f5ff;
            border: 1px solid #b3d7ff;
        }

        .custom-control-label::after {
            top: .25rem;
            left: 0;
            width: 1.5rem;
            height: 1.5rem;
            line-height: 1.5rem;
        }

        /* Alertas de duplicidade com fundo levemente vermelho */
        .alert-duplicidade {
            background-color: #ffebe6;
            color: #8b0000;
            border: 1px solid #ffcccb;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(255, 0, 0, 0.1);
        }

        /* Estilo para as informações dentro do alerta de duplicidade */
        .alert-duplicidade p {
            margin-bottom: 8px;
        }

        .alert-duplicidade strong {
            color: #dc3545;
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

        /* Estilo para os títulos de seção de imagem */
        .section-title {
            font-size: 1.6rem;
            font-weight: 600;
            color: #34495e;
            margin-bottom: 1.5rem;
            text-align: center;
            position: relative;
            padding-bottom: 8px;
        }

        /* Adiciona uma linha sutil abaixo dos títulos */
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: #007bff;
            border-radius: 2px;
        }

        /* Estilo para a seção de Alertas de Duplicidade */
        .alert-duplicidade-card {
            background-color: #fef2f2;
            border: 1px solid #fbdada;
            border-radius: 10px;
            padding: 20px 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(255, 0, 0, 0.08);
        }

        /* Título dentro do cartão de alerta */
        .alert-duplicidade-card .section-title-alt {
            font-size: 1.5rem;
            font-weight: 700;
            color: #c0392b;
            margin-bottom: 15px;
            position: relative;
            padding-bottom: 5px;
            text-align: left;
        }

        /* Linha sutil abaixo do título do alerta */
        .alert-duplicidade-card .section-title-alt::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: #e74c3c;
            border-radius: 2px;
        }

        /* Parágrafos de informações dentro do alerta */
        .alert-duplicidade-card p {
            margin-bottom: 8px !important;
            display: flex;
            align-items: baseline;
            font-size: 1.05em;
            line-height: 1.4;
        }

        /* Rótulos em negrito dentro do alerta */
        .alert-duplicidade-card p strong {
            color: #8b0000;
            min-width: 120px;
            flex-shrink: 0;
            margin-right: 10px;
        }

        /* Valores dentro do alerta */
        .alert-duplicidade-card p span.form-control-plaintext {
            border-bottom: none !important;
            padding: 0 !important;
            color: #333;
            font-weight: 500;
            flex-grow: 1;
        }

        /* Estilo para a lista de alertas de referência */
        #alertas_referencia_lista {
            margin-top: 10px !important;
            margin-left: 20px !important;
            list-style: disc;
            color: #444;
        }

        #alertas_referencia_lista li {
            padding: 4px 0;
            border-bottom: 1px dashed #f0c0c0;
            font-size: 0.95em;
        }

        #alertas_referencia_lista li:last-child {
            border-bottom: none;
        }

        /* Estilo para a seção de Detalhes da Solicitação */
        .solicitacao-card {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        /* Título dentro do cartão de solicitação */
        .solicitacao-card .section-title-alt {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
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
            background-color: #3498db;
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
            min-width: 120px;
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
        /* Estilo da marca de verificação (o ::after) */
        .custom-control-label::after {
            content: '';
            display: block;
            position: absolute;
            top: 50%;
            left: 0.2rem;
            transform: translateY(-50%) rotate(45deg);
            width: 0.6rem;
            height: 1.2rem;
            border: solid white;
            border-width: 0 4px 4px 0;
            opacity: 0;
            transition: opacity 0.2s ease-in-out;
        }

       

        .custom-control-input:checked ~ .custom-control-label::after {
            opacity: 1;
        }

        /* Estilo para o foco (acessibilidade com teclado) */
        .custom-control-input:focus ~ .custom-control-label::before {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        /* --- Estilo Moderno para o Título Principal da Página --- */
        .main-page-title {
            font-family: 'Inter', sans-serif;
            font-size: 2.8em;
            font-weight: 700;
            color: #2c3e50;
            letter-spacing: -0.03em;
            margin-bottom: 40px;
            position: relative;
            padding-bottom: 15px;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Linha decorativa abaixo do título */
        .main-page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 5px;
            background: linear-gradient(to right, #007bff, #28a745);
            border-radius: 5px;
        }
        .btn-resolvido {
            transition: all 0.3s ease;
        }

        .btn-resolvido:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        /* Estilo para a seção de Status e Estágio */
.status-card {
    background-color: #f0f8ff; /* Fundo azul claro */
    border: 1px solid #cceeff; /* Borda azul clara */
    border-radius: 12px; /* Cantos arredondados */
    padding: 20px; /* Espaçamento interno */
    margin-bottom: 25px; /* Margem inferior */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); /* Sombra suave */
    display: flex;
    justify-content: space-between; /* Espaço entre os itens */
    align-items: center;
}

.status-card h6 {
    font-size: 1.1em;
    font-weight: 600;
    color: #555;
    margin-bottom: 0;
}

.status-card p {
    font-size: 1.3em;
    font-weight: 700;
    color: #007bff; /* Cor azul para os valores */
    margin-bottom: 0;
}
/* Estilo para a seção de Valores de Empréstimos */
.loan-values-card {
    background-color: #f8f9fa; /* Fundo cinza claro */
    border: 1px solid #e9ecef; /* Borda cinza suave */
    border-radius: 8px; /* Cantos arredondados */
    padding: 20px; /* Espaçamento interno */
    margin-bottom: 20px; /* Margem inferior */
    box-shadow: 0 4px 10px rgba(0,0,0,0.05); /* Sombra leve */
}

.loan-values-card p {
    margin: 0 0 10px 0; /* Espaçamento entre os parágrafos */
    font-size: 1em;
    color: #495057;
}

.loan-values-card strong {
    color: #212529; /* Cor mais escura para os rótulos */
    font-weight: 600;
}

.loan-value {
    color: #007bff; /* Cor azul para os valores */
    font-weight: bold;
    font-size: 1.1em;
    display: inline-block;
    margin-left: 5px;
}

/* Estilo para a seção de dados pessoais e endereço */
.card p {
    font-size: 0.9em;
    color: #495057;
    margin-bottom: 5px;
}
.card p strong {
    color: #212529;
}
.card h6 {
    font-size: 1.05em;
    font-weight: 600;
}

/* Estilo para as caixas de validação */
.validation-card {
    background-color: #f8f9fa; /* Fundo cinza claro, neutro e suave */
    border: 1px solid #e9ecef; /* Borda cinza clara */
    border-radius: 8px; /* Cantos arredondados */
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05); /* Sombra suave */
}

.validation-card .form-group {
    display: block;
    margin-bottom: 0.25rem; /* Reduzindo a margem inferior para diminuir o espaçamento */
}



/* Estilo para os títulos das seções */
.validation-card h6 {
    font-size: 1.05em;
    font-weight: 600;
    color: #333; /* Cor escura para o texto */
    margin-top: 0;
}

/* Estilo para os dados pessoais e endereço */
.text-start {
    text-align: left !important;
}

.text-start p {
    font-size: 0.95em;
    line-height: 1.4;
    margin-top: 0;
    margin-bottom: 5px;
}

.text-start strong {
    color: #555;
    font-weight: 600;
}
/* Container para os grupos de checkbox, garantindo um agrupamento visual */
.checkbox-group-container {
    background-color: #f0f8ff;
    border: 1px solid #cceeff;
    border-radius: 10px;
    padding: 20px 25px;
    margin-top: 25px;
    box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.08);
}

/* Estilo para cada item individual de checkbox */
.custom-control.custom-checkbox {
    padding-left: 2.5rem;
    margin-bottom: 1rem;
    cursor: pointer;
    position: relative;
    user-select: none;
    display: flex;
    align-items: center;
    padding: 8px 0;
    transition: background-color 0.2s ease-in-out, transform 0.1s ease-out;
    border-radius: 6px;
}

/* Efeito de hover e active para cada item de checkbox */
.custom-control.custom-checkbox:hover {
    background-color: #e6f7ff;
    transform: translateY(-2px);
}

.custom-control.custom-checkbox:active {
    transform: translateY(0);
}

/* Estilo do label (texto) do checkbox */
.custom-control-label {
    padding-left: 45px;
    font-size: 1.05em;
    color: #495057;
    line-height: 1.6rem;
    margin-left: 0.5rem;
    padding-top: 2px;
    flex-grow: 1;
}

/* Estilo do quadrado do checkbox (o ::before) */
.custom-control-label::before {
    content: '';
    display: block;
    position: absolute;
    top: 50%;
    left: 0;
    transform: translateY(-50%);
    width: 1.8rem;
    height: 1.8rem;
    border-radius: 0.4rem;
    background-color: #e9f5ff;
    border: 2px solid #a8d6ff;
    box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.08);
    transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
}

/* Estilo da marca de verificação (o ::after) */
.custom-control-label::after {
    content: '';
    display: block;
    position: absolute;
    top: 50%;
    left: 0.2rem;
    transform: translateY(-50%) rotate(45deg);
    width: 0.6rem;
    height: 1.2rem;
    border: solid white;
    border-width: 0 4px 4px 0;
    opacity: 0;
    transition: opacity 0.2s ease-in-out;
}

/* Estilo do checkbox quando marcado */
.custom-control-input:checked ~ .custom-control-label::before {
    background-color: #007bff;
    border-color: #007bff;
    box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.2), 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.custom-control-input:checked ~ .custom-control-label::after {
    opacity: 1;
}

/* Estilo para o foco (acessibilidade com teclado) */
.custom-control-input:focus ~ .custom-control-label::before {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
/* Estilo para a seção de dados pessoais e endereço */
.data-card {
    background-color: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.data-card p {
    font-size: 1.0em;
    color: #495057;
    margin-bottom: 8px;
    display: flex;
    align-items: baseline;
}

.data-card p strong {
    color: #2c3e50;
    font-weight: 600;
    min-width: 120px; /* Alinha os rótulos */
    flex-shrink: 0;
}
/* Estilo para o container do formulário */
.form-card {
    background-color: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

/* Estilo para os campos de formulário */
.form-card .form-control,
.form-card .form-select {
    border-radius: 6px;
    border: 1px solid #ced4da;
    transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.form-card .form-control:focus,
.form-card .form-select:focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
}

/* Estilo para o botão de ação */
.form-card .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    font-weight: 600;
    border-radius: 6px;
    transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
}

.form-card .btn-primary:hover {
    background-color: #0056b3;
    border-color: #004a99;
}

    </style>
</head>
<body>
    <div class="container">
        <h2 class="main-page-title text-center mb-4"><?= htmlspecialchars($cliente['nome'] ?? '')  ?></h2>

        <div class="status-card">
          <div class="status-item">
              <h6>Status:</h6>
              <p>Novo</p>
          </div>
          <div class="status-item">
              <h6>Estágio:</h6>
              <p><?= htmlspecialchars($cliente['estagio_cliente'] ?? '') ?></p>
          </div>
        </div>

        <hr>
        
        <hr>

        <h4 class="section-title">Alertas de Duplicidade</h4>
        <div class="alert-duplicidade-card">
            <?php 
            $has_unresolved_alerts = false;
            if (count($alertas_duplicidade) > 0): ?>
                <?php foreach ($alertas_duplicidade as $alerta): 
                    if ($alerta['resolvido'] == 1) continue;
                    $has_unresolved_alerts = true;

                    $data_alerta_formatada = date('d/m/Y', strtotime($alerta['data_alerta']));
                    $nome_cliente_duplicado = "Não encontrado";

                    // Tenta buscar o nome do cliente duplicado se o valor_duplicado não for N/A
                    if ($alerta['valor_duplicado'] !== 'N/A' && !empty($alerta['valor_duplicado'])) {
                        if ($alerta['tipo_alerta'] === 'Nome Duplicado') {
                            $query_cliente_dup = $pdo->prepare("SELECT nome FROM clientes WHERE nome = :valor AND id != :id_original LIMIT 1");
                            $query_cliente_dup->bindValue(":valor", $alerta['valor_duplicado']);
                            $query_cliente_dup->bindValue(":id_original", $id_cliente);
                        } else if ($alerta['tipo_alerta'] === 'Telefone Duplicado' || $alerta['tipo_alerta'] === 'CPF Duplicado' || $alerta['tipo_alerta'] === 'Email Duplicado') {
                            // Assumindo que 'valor_duplicado' contém o telefone/CPF/email duplicado
                            $campo_busca = '';
                            if ($alerta['tipo_alerta'] === 'Telefone Duplicado') $campo_busca = 'telefone';
                            else if ($alerta['tipo_alerta'] === 'CPF Duplicado') $campo_busca = 'cpf';
                            else if ($alerta['tipo_alerta'] === 'Email Duplicado') $campo_busca = 'email';

                            if (!empty($campo_busca)) {
                                $query_cliente_dup = $pdo->prepare("SELECT nome FROM clientes WHERE {$campo_busca} = :valor AND id != :id_original LIMIT 1");
                                $query_cliente_dup->bindValue(":valor", $alerta['valor_duplicado']);
                                $query_cliente_dup->bindValue(":id_original", $id_cliente);
                            }
                        }

                        if (isset($query_cliente_dup)) {
                            $query_cliente_dup->execute();
                            $res_dup = $query_cliente_dup->fetch(PDO::FETCH_ASSOC);
                            if ($res_dup) {
                                $nome_cliente_duplicado = $res_dup['nome'];
                            }
                        }
                    }
                ?>
                    <div id="alerta-<?= htmlspecialchars($alerta['id'] ?? '') ?>">
                        <h5 class="section-title-alt">Alerta: <?= htmlspecialchars($alerta['tipo_alerta']) ?></h5>
                        <p class="mb-2"><strong>Valor Duplicado:</strong> <span class="form-control-plaintext py-1"><?= htmlspecialchars($alerta['valor_duplicado'] ?? '') ?></span></p>
                        <p class="mb-2"><strong>Cliente Duplicado:</strong> <span class="form-control-plaintext py-1"><?= htmlspecialchars($nome_cliente_duplicado  ?? '') ?></span></p>
                        <p class="mb-2"><strong>Data do Alerta:</strong> <span class="form-control-plaintext py-1"><?= $data_alerta_formatada ?></span></p>
                        
                        <button class="btn btn-sm btn-success btn-resolvido" data-id="<?= htmlspecialchars($alerta['id']  ?? '') ?>">
                            <i class="fas fa-check"></i> Ignorar
                        </button>
                        
                        <hr class="my-3">
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (!$has_unresolved_alerts): ?>
                <p>Nenhum alerta de duplicidade pendente para este cliente.</p>
            <?php endif; ?>
        </div>

        <h4 class="section-title">Comprovantes</h4>
<div class="row mb-4">
    <div class="col-md-6 text-center">
        <h5 class="section-title">Comprovante de RG:</h5>
        <?php if (!empty($cliente['comprovante_rg']) && $cliente['comprovante_rg'] !== 'sem-foto.png'): ?>
            <img src="/painel/images/comprovantes/<?= htmlspecialchars($cliente['comprovante_rg'] ?? '') ?>" alt="Comprovante de RG" class="img-fluid rounded shadow-sm" style="max-width: 500px; border: 2px solid #ddd; display: block; margin: 0 auto;">
        <?php else: ?>
            <p>Não enviado.</p>
        <?php endif; ?>
    </div>

    <div class="col-md-6">
      <div class="data-card mt-4">
          <h6 class="section-title text-start">Dados Pessoais:</h6>
          <p>
              <strong>Nome Completo:</strong> <span><?= htmlspecialchars($cliente['nome']  ?? '') ?></span>
          </p>
          <p>
              <strong>CPF:</strong> <span><?= htmlspecialchars($cliente['cpf']  ?? '') ?></span>
          </p>
          <p>
              <strong>RG:</strong> <span><?= htmlspecialchars($cliente['rg']  ?? '') ?></span>
          </p>
          <p>
              <strong>Celular:</strong> <span><?= htmlspecialchars($cliente['telefone']  ?? '')  ?></span>
          </p>
      </div>
      <div class="validation-card">
        <h6 class="section-title text-start mb-3">Validações:</h6>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
            <input type="checkbox" class="custom-control-input" id="check_validade_cnh">
            <label class="custom-control-label" for="check_validade_cnh">Validade da CNH</label>
        </div>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
            <input type="checkbox" class="custom-control-input" id="check_nome_documento">
            <label class="custom-control-label" for="check_nome_documento">Confere com Documento</label>
        </div>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
            <input type="checkbox" class="custom-control-input" id="check_nome_whatsapp">
            <label class="custom-control-label" for="check_nome_whatsapp">Busca em Whatsapp</label>
        </div>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
            <input type="checkbox" class="custom-control-input" id="check_nome_consulta">
            <label class="custom-control-label" for="check_nome_consulta">Consulta</label>
        </div>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
            <input type="checkbox" class="custom-control-input" id="check_cpf_confere_documento">
            <label class="custom-control-label" for="check_cpf_confere_documento">Confere CPF com Documento</label>
        </div>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
            <input type="checkbox" class="custom-control-input" id="check_rg_confere_documento">
            <label class="custom-control-label" for="check_rg_confere_documento">Confere RG com Documento</label>
        </div>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
            <input type="checkbox" class="custom-control-input" id="check_foto_usuario_confere">
            <label class="custom-control-label" for="check_foto_usuario_confere">Foto do usuário confere com Documento</label>
        </div>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
            <input type="checkbox" class="custom-control-input" id="check_celular_confere">
            <label class="custom-control-label" for="check_celular_confere">Celular confere</label>
        </div>
  </div>


    </div>
</div>

<hr>

<div class="row info-block">
    <div class="col-md-6">
        <h5 class="section-title">Comprovante de Endereço:</h5>
        <?php if (!empty($cliente['comprovante_endereco']) && $cliente['comprovante_endereco'] !== 'sem-foto.png'): ?>
            <img src="/painel/images/comprovantes/<?= htmlspecialchars($cliente['comprovante_endereco'] ?? '') ?>" alt="Comprovante de Endereço" class="img-fluid rounded shadow-sm" style="max-width: 500px; border: 2px solid #ddd; display: block; margin: 0 auto;">
        <?php else: ?>
            <p>Não enviado.</p>
        <?php endif; ?>
    </div>
    <div class="col-md-6">
        <div class="validation-card">
            <h6 class="section-title text-start mb-3">Validação do Endereço:</h6>
            <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                <input type="checkbox" class="custom-control-input" id="check_titular_aceito">
                <label class="custom-control-label" for="check_titular_aceito">Titular Aceito</label>
            </div>
            <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                <input type="checkbox" class="custom-control-input" id="check_cidade_atendemos">
                <label class="custom-control-label" for="check_cidade_atendemos">Cidade que atendemos</label>
            </div>
            <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                <input type="checkbox" class="custom-control-input" id="check_emissao_prazo">
                <label class="custom-control-label" for="check_emissao_prazo">Emissão dentro do prazo</label>
            </div>
            <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                <input type="checkbox" class="custom-control-input" id="check_endereco_confere_comprovante">
                <label class="custom-control-label" for="check_endereco_confere_comprovante">Endereço confere com comprovante</label>
            </div>
        </div>        

        <div class="data-card mt-4">
          <h6 class="section-title text-start">Endereço Registrado:</h6>
          <p>
              <strong>Endereço:</strong> <span><?= htmlspecialchars($cliente['endereco'] ?? '') ?>, Nº <?= htmlspecialchars($cliente['numero']?? '') ?></span>
          </p>
          <p>
              <strong>Quadra:</strong> <span><?= htmlspecialchars($cliente['quadra']?? '') ?></span>
          </p>
          <p>
              <strong>Lote:</strong> <span><?= htmlspecialchars($cliente['lote']?? '') ?></span>
          </p>
          <p>
              <strong>Bairro:</strong> <span><?= htmlspecialchars($cliente['bairro']?? '') ?></span>
          </p>
          <p>
              <strong>Cidade/Estado:</strong> <span><?= htmlspecialchars($cliente['cidade']?? '') ?> - <?= htmlspecialchars($cliente['estado']?? '') ?></span>
          </p>
          <p>
              <strong>CEP:</strong> <span><?= htmlspecialchars($cliente['cep']?? '') ?></span>
          </p>
          <?php if (!empty($cliente['complemento'])): ?>
              <p>
                  <strong>Complemento:</strong> <span><?= htmlspecialchars($cliente['complemento'] ?? '') ?></span>
              </p>
          <?php endif; ?>
      </div>
    </div>
</div>
        <hr>

        <h4 class="section-title">Referências</h4>
        <div class="row info-block">
            <div class="col-md-6">
                <div class="data-card">
                    <h6 class="section-title text-start">Dados da Referência:</h6>
                    <p>
                        <strong>Nome Completo:</strong> <span><?= htmlspecialchars($cliente['referencia_nome']?? '') ?></span>
                    </p>
                    <p>
                        <strong>Celular (WhatsApp):</strong> <span><?= htmlspecialchars($cliente['referencia_contato'] ?? '') ?></span>
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="validation-card">
                    <h6 class="section-title text-start mb-3">Validações:</h6>
                    <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                        <input type="checkbox" class="custom-control-input" id="check_sobrenome_confere">
                        <label class="custom-control-label" for="check_sobrenome_confere">Sobrenome confere</label>
                    </div>
                    <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                        <input type="checkbox" class="custom-control-input" id="check_num_com_whatsapp">
                        <label class="custom-control-label" for="check_num_com_whatsapp">Número com WhatsApp</label>
                    </div>
                    <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                        <input type="checkbox" class="custom-control-input" id="check_cliente_bom">
                        <label class="custom-control-label" for="check_cliente_bom">Cliente bom?</label>
                    </div>
                </div>
            </div>
        </div>
        <h4 class="section-title">Ramo de Atuação</h4>

<?php
$motorista = !empty($cliente['print_perfil_app']) && $cliente['print_perfil_app'] !== 'sem-foto.png' ||
             !empty($cliente['print_veiculo_app']) && $cliente['print_veiculo_app'] !== 'sem-foto.png' ||
             !empty($cliente['print_ganhos_hoje']) && $cliente['print_ganhos_hoje'] !== 'sem-foto.png' ||
             !empty($cliente['print_ganhos_30dias']) && $cliente['print_ganhos_30dias'] !== 'sem-foto.png';

$autonomo = !empty($cliente['extrato_90dias']) && $cliente['extrato_90dias'] !== 'sem-foto.png';
$assalariado = !empty($cliente['contracheque']) && $cliente['contracheque'] !== 'sem-foto.png';
?>
<div class="row">
    <div class="col-md-6">
        <div class="validation-card">
            <h6>Comprovantes Genéricos</h6>
            <hr>
            <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                <input type="checkbox" class="custom-control-input" id="checkNome" value="1">
                <label class="custom-control-label" for="checkNome">Nome</label>
            </div>
            <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                <input type="checkbox" class="custom-control-input" id="checkData" value="1">
                <label class="custom-control-label" for="checkData">Data</label>
            </div>
            <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                <input type="checkbox" class="custom-control-input" id="checkGanhos" value="1">
                <label class="custom-control-label" for="checkGanhos">Ganhos</label>
            </div>
        </div>
    </div>

    <?php if ($motorista): ?>
        <div class="col-md-6">
            <div class="validation-card">
                <h6>Validação de Motorista</h6>
                <hr>
                <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                    <input type="checkbox" class="custom-control-input" id="checkTaxaAceitacao" value="1">
                    <label class="custom-control-label" for="checkTaxaAceitacao">Taxa de Aceitação</label>
                </div>
                <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                    <input type="checkbox" class="custom-control-input" id="checkConfere" value="1">
                    <label class="custom-control-label" for="checkConfere">Confere</label>
                </div>
                <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                    <input type="checkbox" class="custom-control-input" id="checkAtivo" value="1">
                    <label class="custom-control-label" for="checkAtivo">Está ativo</label>
                </div>
                <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                    <input type="checkbox" class="custom-control-input" id="checkCabecalhos" value="1">
                    <label class="custom-control-label" for="checkCabecalhos">Cabeçalhos/Horários prints</label>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if ($motorista): ?>
    <div class="row mb-4">
        <div class="col-md-4 text-center">
            <h5 class="section-title">Print Perfil App:</h5>
            <?php if (!empty($cliente['print_perfil_app']) && $cliente['print_perfil_app'] !== 'sem-foto.png'): ?>
                <img src="/painel/images/comprovantes/<?= htmlspecialchars($cliente['print_perfil_app'] ?? '') ?>" alt="Print Perfil App" class="img-fluid rounded shadow-sm" style="max-width: 350px; border: 2px solid #ddd; display: block; margin: 0 auto;">
            <?php else: ?>
                <p>Não enviado.</p>
            <?php endif; ?>
        </div>
        <div class="col-md-4 text-center">
            <h5 class="section-title">Print Veículo App:</h5>
            <?php if (!empty($cliente['print_veiculo_app']) && $cliente['print_veiculo_app'] !== 'sem-foto.png'): ?>
                <img src="/painel/images/comprovantes/<?= htmlspecialchars($cliente['print_veiculo_app'] ?? '') ?>" alt="Print Veículo App" class="img-fluid rounded shadow-sm" style="max-width: 350px; border: 2px solid #ddd; display: block; margin: 0 auto;">
            <?php else: ?>
                <p>Não enviado.</p>
            <?php endif; ?>
        </div>
        <div class="col-md-4 text-center">
            <h5 class="section-title">Print Ganhos Hoje:</h5>
            <?php if (!empty($cliente['print_ganhos_hoje']) && $cliente['print_ganhos_hoje'] !== 'sem-foto.png'): ?>
                <img src="/painel/images/comprovantes/<?= htmlspecialchars($cliente['print_ganhos_hoje'] ?? '') ?>" alt="Print Ganhos Hoje" class="img-fluid rounded shadow-sm" style="max-width: 350px; border: 2px solid #ddd; display: block; margin: 0 auto;">
            <?php else: ?>
                <p>Não enviado.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4 text-center">
            <h5 class="section-title">Print Ganhos 30 Dias:</h5>
            <?php if (!empty($cliente['print_ganhos_30dias']) && $cliente['print_ganhos_30dias'] !== 'sem-foto.png'): ?>
                <img src="/painel/images/comprovantes/<?= htmlspecialchars($cliente['print_ganhos_30dias'] ?? '') ?>" alt="Print Ganhos 30 Dias" class="img-fluid rounded shadow-sm" style="max-width: 350px; border: 2px solid #ddd; display: block; margin: 0 auto;">
            <?php else: ?>
                <p>Não enviado.</p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($autonomo): ?>
    <div class="row mb-4">
        <div class="col-md-4 text-center">
            <h5 class="section-title">Extrato 90 Dias:</h5>
            <?php if (!empty($cliente['extrato_90dias']) && $cliente['extrato_90dias'] !== 'sem-foto.png'): ?>
                <img src="/painel/images/comprovantes/<?= htmlspecialchars($cliente['extrato_90dias'] ?? '') ?>" alt="Extrato 90 Dias" class="img-fluid rounded shadow-sm" style="max-width: 350px; border: 2px solid #ddd; display: block; margin: 0 auto;">
            <?php else: ?>
                <p>Não enviado.</p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($assalariado): ?>
    <div class="row mb-4">
        <div class="col-md-4 text-center">
            <h5 class="section-title">Contracheque:</h5>
            <?php if (!empty($cliente['contracheque']) && $cliente['contracheque'] !== 'sem-foto.png'): ?>
                <img src="/painel/images/comprovantes/<?= htmlspecialchars($cliente['contracheque'] ?? '') ?>" alt="Contracheque" class="img-fluid rounded shadow-sm" style="max-width: 350px; border: 2px solid #ddd; display: block; margin: 0 auto;">
            <?php else: ?>
                <p>Não enviado.</p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
        <hr>

        <h4 class="section-title">Valores de Empréstimos</h4>
        <?php if (!empty($cliente) && (!empty($cliente['valor_desejado']) || !empty($cliente['valor_parcela_desejada']))): ?>
            <div class="card loan-values-card">
                <p>
                    <strong>Valor Desejado:</strong> 
                    <span class="loan-value">R$ <?= number_format($cliente['valor_desejado'], 2, ',', '.') ?></span>
                </p>
                <p>
                    <strong>Valor da Parcela Desejada:</strong> 
                    <span class="loan-value">R$ <?= number_format($cliente['valor_parcela_desejada'], 2, ',', '.') ?></span>
                </p>
            </div>
        <?php else: ?>
            <p>Nenhum valor de empréstimo desejado registrado.</p>
        <?php endif; ?>

      <hr>

      <h4 class="section-title">Finalizar Análise</h4>
      <div class="row justify-content-center">
          <div class="col-md-12">
            <div class="form-card mt-4">
              <form action="finalizar_analise.php" method="POST">
                  <input type="hidden" name="id_cliente" value="<?= htmlspecialchars($id_cliente ?? '') ?>">

                  <div class="form-group mb-3">
                      <label for="status_final" class="form-label">Selecione o status final:</label>
                      <select class="form-select form-control" id="status_final" name="status_final" required>
                          <option value="" selected disabled>Escolha uma opção</option>
                          <option value="Aprovado">Aprovar</option>
                          <option value="Reprovado">Reprovar</option>
                          <option value="Pendente">Pendência</option>
                      </select>
                  </div>

                  <div class="form-group mb-3">
                      <label for="observacoes" class="form-label">Descrição e Observações:</label>
                      <textarea class="form-control" id="observacoes" name="observacoes" rows="4" placeholder="Insira aqui as observações, motivos ou pendências..."></textarea>
                  </div>

                  <div class="d-grid gap-2">
                      <button type="submit" class="btn btn-primary btn-lg mt-3">Finalizar Análise</button>
                  </div>
              </form>
          </div>
          </div>
      </div>

      

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Escuta o clique em qualquer botão com a classe 'btn-resolvido'
        $(document).on('click', '.btn-resolvido', function() {
            var alertaId = $(this).data('id');
            var alertaElemento = $('#alerta-' + alertaId);
            
            // Confirmação via SweetAlert
            Swal.fire({
                title: 'Tem certeza?',
                text: "Você irá marcar este alerta como resolvido e ele não será mais exibido.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, resolver!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'marcar_alerta_resolvido.php',
                        type: 'POST',
                        data: {
                            id: alertaId
                        },
                        success: function(response) {
                            if (response.trim() === 'sucesso') {
                                alertaElemento.fadeOut('slow', function() {
                                    $(this).remove();
                                    // Verifica se ainda existem alertas visíveis
                                    if ($('.alert-duplicidade-card').children('div').length === 0) {
                                        $('.alert-duplicidade-card').html('<p>Nenhum alerta de duplicidade pendente para este cliente.</p>');
                                    }
                                    Swal.fire(
                                        'Resolvido!',
                                        'O alerta foi marcado como resolvido.',
                                        'success'
                                    );
                                });
                            } else {
                                Swal.fire(
                                    'Erro!',
                                    'Não foi possível resolver o alerta. Tente novamente.',
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Erro!',
                                'Ocorreu um erro na requisição. Verifique sua conexão.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
    </script>
</body>
</html>