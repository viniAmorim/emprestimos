<?php
require_once("../conexao.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID do cliente não fornecido.");
}

$id_cliente = $_GET['id'];

$query = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
$query->bindValue(":id", $id_cliente);
$query->execute();
$cliente = $query->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    die("Cliente não encontrado.");
}

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

/* Estiliza todos os campos de formulário padrão */
.form-control, .form-select {
    border-radius: 8px; /* Borda arredondada */
    border: 1px solid #ced4da; /* Cor da borda suave */
    padding: 8px 15px; /* Espaçamento interno */
    transition: all 0.3s ease-in-out; /* Transição suave para efeitos */
}

/* Efeito quando o campo está em foco (selecionado) */
.form-control:focus, .form-select:focus {
    border-color: #80bdff; /* Cor da borda em foco */
    box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25); /* Sombra suave */
    outline: none; /* Remove o contorno padrão */
}

/* Estilo para labels (rótulos) */
.form-group label {
    font-weight: 600;
    color: #495057;
}

.form-control[type="file"] {
    padding: 10px;
}

.alerta-resolvido {
    background-color: #e6ffe6; 
    border: 1px solid #00cc00; 
    padding: 15px;
    box-shadow: 0 0 5px rgba(0, 204, 0, 0.5); 
    color: #006600; 
}

/* Estilos base para todos os cards de alerta */
.alerta-card-item {
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    transition: background-color 0.3s, border-color 0.3s;
}

/* Estilo para Alertas PENDENTES (VERMELHO) */
.alerta-pendente {
    background-color: #ffeaea; /* Vermelho muito claro */
    border-left: 5px solid #d9534f; /* Borda lateral vermelha */
}

/* Estilo para Alertas RESOLVIDOS/IGNORADOS (VERDE) */
.alerta-resolvido {
    background-color: #e6ffe6; /* Verde muito claro */
    border-left: 5px solid #5cb85c; /* Borda lateral verde */
    opacity: 0.8; /* Leve opacidade para indicar que foi resolvido */
}

/* Opcional: Estilo para o badge de ignorado */
.alerta-resolvido .badge {
    font-size: 0.75rem;
    padding: 0.4em 0.8em;
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
            
            // Define a classe CSS e verifica o status
            $is_resolvido = ($alerta['resolvido'] == 1);
            $alerta_class = $is_resolvido ? 'alerta-resolvido' : 'alerta-pendente';
            
            // Define se há algum alerta pendente para a mensagem final
            if (!$is_resolvido) {
                $has_unresolved_alerts = true;
            }

            $data_alerta_formatada = date('d/m/Y', strtotime($alerta['data_alerta']));
            $nome_cliente_duplicado = "Não encontrado";

            // Lógica de busca pelo nome do cliente duplicado
            if ($alerta['valor_duplicado'] !== 'N/A' && !empty($alerta['valor_duplicado'])) {
                if ($alerta['tipo_alerta'] === 'Nome Duplicado') {
                    $query_cliente_dup = $pdo->prepare("SELECT nome FROM clientes WHERE nome = :valor AND id != :id_original LIMIT 1");
                    $query_cliente_dup->bindValue(":valor", $alerta['valor_duplicado']);
                    $query_cliente_dup->bindValue(":id_original", $id_cliente);
                } else if ($alerta['tipo_alerta'] === 'Telefone Duplicado' || $alerta['tipo_alerta'] === 'CPF Duplicado' || $alerta['tipo_alerta'] === 'Email Duplicado' || $alerta['tipo_alerta'] === 'Telefone de Referência Duplicado') {
                    
                    $campo_busca = '';
                    if ($alerta['tipo_alerta'] === 'Telefone Duplicado') $campo_busca = 'telefone';
                    else if ($alerta['tipo_alerta'] === 'CPF Duplicado') $campo_busca = 'cpf';
                    else if ($alerta['tipo_alerta'] === 'Email Duplicado') $campo_busca = 'email';
                    // **USAR O NOME EXATO DA COLUNA AQUI:**
                    else if ($alerta['tipo_alerta'] === 'Telefone de Referência Duplicado') $campo_busca = 'referencia_contato'; 
                
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
        
        <div id="alerta-<?= htmlspecialchars($alerta['id'] ?? '') ?>" class="alerta-card-item <?= $alerta_class ?>">
            <h5 class="section-title-alt">Alerta: <?= htmlspecialchars($alerta['tipo_alerta']) ?></h5>
            <p class="mb-2"><strong>Valor Duplicado:</strong> <span class="form-control-plaintext py-1"><?= htmlspecialchars($alerta['valor_duplicado'] ?? '') ?></span></p>
            <p class="mb-2"><strong>Cliente Duplicado:</strong> <span class="form-control-plaintext py-1"><?= htmlspecialchars($nome_cliente_duplicado ?? '') ?></span></p>
            <p class="mb-2"><strong>Data do Alerta:</strong> <span class="form-control-plaintext py-1"><?= $data_alerta_formatada ?></span></p>
            
            <?php if (!$is_resolvido): // Mostra o botão APENAS se não estiver resolvido ?>
                <button style="background-color: #8b0000; color: white" class="btn btn-sm btn-resolvido" data-id="<?= htmlspecialchars($alerta['id'] ?? '') ?>">
                    <i class="fas fa-check"></i> Ignorar
                </button>
            <?php else: // Mostra o status se estiver resolvido ?>
                <span class="badge bg-success text-white">IGNORADO</span>
            <?php endif; ?>

            <hr class="my-3">
        </div>
    <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if (!$has_unresolved_alerts): ?>
        <p>Nenhum alerta de duplicidade pendente para este cliente.</p>
    <?php endif; ?>
</div>

        <h4 class="section-title">Comprovantes</h4>
        <form id="form-analise" action="/painel/paginas/clientes/finalizar_analise.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id_cliente" value="<?= htmlspecialchars($cliente['id'] ?? '') ?>">
<div class="row mb-4">
    <div class="col-md-6 text-center">
        <h5 class="section-title">Comprovante de RG:</h5>
          <?php if (!empty($cliente['comprovante_rg']) && $cliente['comprovante_rg'] !== 'sem-foto.png'): ?>
                <img src="/painel/images/comprovantes/<?= htmlspecialchars($cliente['comprovante_rg'] ?? '') ?>" alt="Comprovante de RG" class="img-fluid rounded shadow-sm" style="max-width: 500px; border: 2px solid #ddd; display: block; margin: 0 auto;">
            <?php else: ?>
                <p>Não enviado.</p>
            <?php endif; ?>
            <div class="form-group mt-3">
                <label for="novo_comprovante_rg">Substituir Comprovante:</label>
                <input type="file" class="form-control" id="novo_comprovante_rg" name="novo_comprovante_rg">
          </div>

        <h5 class="section-title">Foto de perfil:</h5>
        <?php if (!empty($cliente['foto']) && $cliente['foto'] !== 'sem-foto.png'): ?>
                <img src="/painel/images/clientes/<?= htmlspecialchars($cliente['foto'] ?? '') ?>" alt="Foto de perfil" class="img-fluid rounded shadow-sm" style="max-width: 200px; border: 2px solid #ddd; display: block; margin: 0 auto;">
            <?php else: ?>
                <p>Não enviado.</p>
            <?php endif; ?>
            <div class="form-group mt-3">
                <label for="foto_usuario">Substituir Foto:</label>
                <input type="file" class="form-control" id="foto_usuario" name="foto_usuario">
            </div>
    </div>

    <div class="col-md-6">
      <div class="data-card mt-4">
          <h6 class="section-title text-start">Dados Pessoais:</h6>
          <div class="form-group mb-2">
            <label for="nome"><strong>Nome Completo:</strong></label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($cliente['nome'] ?? '') ?>">
        </div>
        
        <div class="form-group mb-2">
            <label for="email"><strong>Email:</strong></label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($cliente['email'] ?? '') ?>">
        </div>
        
        <div class="form-group mb-2">
            <label for="telefone"><strong>Celular:</strong></label>
            <input type="tel" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($cliente['telefone'] ?? '') ?>">
        </div>
        
        <div class="form-group mb-2">
            <label for="cpf"><strong>CPF:</strong></label>
            <input type="text" class="form-control" id="cpf" name="cpf" value="<?= htmlspecialchars($cliente['cpf'] ?? '') ?>">
        </div>
        
        <div class="form-group mb-2">
            <label for="rg"><strong>RG:</strong></label>
            <input type="text" class="form-control" id="rg" name="rg" value="<?= htmlspecialchars($cliente['rg'] ?? '') ?>">
        </div>
        
        <div class="form-group mb-2">
            <label for="data_nasc"><strong>Data Nascimento:</strong></label>
            <input type="date" class="form-control" id="data_nasc" name="data_nasc" value="<?= !empty($cliente['data_nasc']) ? htmlspecialchars((new DateTime($cliente['data_nasc']))->format('Y-m-d')) : '' ?>">
        </div>
        
        <div class="form-group mb-2">
            <label for="pix"><strong>Chave Pix:</strong></label>
            <input type="text" class="form-control" id="pix" name="pix" value="<?= htmlspecialchars($cliente['pix'] ?? '') ?>">
        </div>
      </div>
      <div class="validation-card">
        <h6 class="section-title text-start mb-3">Validações:</h6>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
            <input type="checkbox" class="custom-control-input" id="check_validade_cnh" name="check_validade_cnh" value="1" <?= $cliente['check_validade_cnh'] == 1 ? 'checked' : '' ?>>
            <label class="custom-control-label" for="check_validade_cnh">Validade da CNH</label>
        </div>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
            <input type="checkbox" class="custom-control-input" id="check_nome_documento" name="check_nome_documento" value="1" <?= $cliente['check_nome_documento'] == 1 ? 'checked' : '' ?>>
            <label class="custom-control-label" for="check_nome_documento">Nome preenchido confere com Documento</label>
        </div>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
            <input type="checkbox" class="custom-control-input" id="check_nome_whatsapp" name="check_nome_whatsapp" value="1" <?= $cliente['check_nome_whatsapp'] == 1 ? 'checked' : '' ?>>
            <label class="custom-control-label" for="check_nome_whatsapp">Busca em Whatsapp</label>
        </div>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
            <input type="checkbox" class="custom-control-input" id="check_nome_consulta" name="check_nome_consulta" value="1" <?= $cliente['check_nome_consulta'] == 1 ? 'checked' : '' ?>>
            <label class="custom-control-label" for="check_nome_consulta">Consulta</label>
        </div>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
            <input type="checkbox" class="custom-control-input" id="check_cpf_confere_documento" name="check_cpf_confere_documento" value="1" <?= $cliente['check_cpf_confere_documento'] == 1 ? 'checked' : '' ?>>
            <label class="custom-control-label" for="check_cpf_confere_documento">Confere CPF com Documento</label>
        </div>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
            <input type="checkbox" class="custom-control-input" id="check_rg_confere_documento" name="check_rg_confere_documento" value="1" <?= $cliente['check_rg_confere_documento'] == 1 ? 'checked' : '' ?>>
            <label class="custom-control-label" for="check_rg_confere_documento">Confere RG com Documento</label>
        </div>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
            <input type="checkbox" class="custom-control-input" id="check_foto_usuario_confere" name="check_foto_usuario_confere" value="1" <?= $cliente['check_foto_usuario_confere'] == 1 ? 'checked' : '' ?>>
            <label class="custom-control-label" for="check_foto_usuario_confere">Foto do usuário confere com Documento</label>
        </div>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
            <input type="checkbox" class="custom-control-input" id="check_celular_confere" name="check_celular_confere" value="1" <?= $cliente['check_celular_confere'] == 1 ? 'checked' : '' ?>>
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

      <div class="form-group mt-3">
          <label for="novo_comprovante_endereco">Substituir Comprovante:</label>
          <input type="file" class="form-control" id="novo_comprovante_endereco" name="novo_comprovante_endereco">
      </div>
    </div>
    <div class="col-md-6">
        <div class="validation-card">
            <h6 class="section-title text-start mb-3">Validação do Endereço:</h6>
            <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                <input type="checkbox" class="custom-control-input" id="check_titular_aceito" name="check_titular_aceito" value="1" <?= $cliente['check_titular_aceito'] == 1 ? 'checked' : '' ?>>
                <label class="custom-control-label" for="check_titular_aceito">Titular Aceito</label>
            </div>
            <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                <input type="checkbox" class="custom-control-input" id="check_cidade_atendemos" name="check_cidade_atendemos" value="1" <?= $cliente['check_cidade_atendemos'] == 1 ? 'checked' : '' ?>>
                <label class="custom-control-label" for="check_cidade_atendemos">Cidade que atendemos</label>
            </div>
            <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                <input type="checkbox" class="custom-control-input" id="check_emissao_prazo" name="check_emissao_prazo" value="1" <?= $cliente['check_emissao_prazo'] == 1 ? 'checked' : '' ?>>
                <label class="custom-control-label" for="check_emissao_prazo">Emissão dentro do prazo</label>
            </div>
            <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                <input type="checkbox" class="custom-control-input" id="check_endereco_confere_comprovante" name="check_endereco_confere_comprovante" value="1" <?= $cliente['check_endereco_confere_comprovante'] == 1 ? 'checked' : '' ?>>
                <label class="custom-control-label" for="check_endereco_confere_comprovante">Endereço confere com comprovante</label>
            </div>
        </div>        

        <div class="data-card mt-4">
          <h6 class="section-title text-start">Endereço Registrado:</h6>
          <div class="form-group mb-2">
        <label for="endereco"><strong>Endereço:</strong></label>
        <input type="text" class="form-control" id="endereco" name="endereco" value="<?= htmlspecialchars($cliente['endereco'] ?? '') ?>">
    </div>
    
    <div class="form-group mb-2">
        <label for="numero"><strong>Número:</strong></label>
        <input type="text" class="form-control" id="numero" name="numero" value="<?= htmlspecialchars($cliente['numero'] ?? '') ?>">
    </div>
    
    <div class="form-group mb-2">
        <label for="quadra"><strong>Quadra:</strong></label>
        <input type="text" class="form-control" id="quadra" name="quadra" value="<?= htmlspecialchars($cliente['quadra'] ?? '') ?>">
    </div>
    
    <div class="form-group mb-2">
        <label for="lote"><strong>Lote:</strong></label>
        <input type="text" class="form-control" id="lote" name="lote" value="<?= htmlspecialchars($cliente['lote'] ?? '') ?>">
    </div>
    
    <div class="form-group mb-2">
        <label for="bairro"><strong>Bairro:</strong></label>
        <input type="text" class="form-control" id="bairro" name="bairro" value="<?= htmlspecialchars($cliente['bairro'] ?? '') ?>">
    </div>
    
    <div class="form-group mb-2">
        <label for="cidade"><strong>Cidade:</strong></label>
        <input type="text" class="form-control" id="cidade" name="cidade" value="<?= htmlspecialchars($cliente['cidade'] ?? '') ?>">
    </div>
    
    <div class="form-group mb-2">
    <label for="estado"><strong>Estado:</strong></label>
    <select class="form-control" id="estado" name="estado">
        <option value="">Selecione um estado</option>
        <option value="AC" <?= ($cliente['estado'] ?? '') == 'AC' ? 'selected' : '' ?>>Acre</option>
        <option value="AL" <?= ($cliente['estado'] ?? '') == 'AL' ? 'selected' : '' ?>>Alagoas</option>
        <option value="AP" <?= ($cliente['estado'] ?? '') == 'AP' ? 'selected' : '' ?>>Amapá</option>
        <option value="AM" <?= ($cliente['estado'] ?? '') == 'AM' ? 'selected' : '' ?>>Amazonas</option>
        <option value="BA" <?= ($cliente['estado'] ?? '') == 'BA' ? 'selected' : '' ?>>Bahia</option>
        <option value="CE" <?= ($cliente['estado'] ?? '') == 'CE' ? 'selected' : '' ?>>Ceará</option>
        <option value="DF" <?= ($cliente['estado'] ?? '') == 'DF' ? 'selected' : '' ?>>Distrito Federal</option>
        <option value="ES" <?= ($cliente['estado'] ?? '') == 'ES' ? 'selected' : '' ?>>Espírito Santo</option>
        <option value="GO" <?= ($cliente['estado'] ?? '') == 'GO' ? 'selected' : '' ?>>Goiás</option>
        <option value="MA" <?= ($cliente['estado'] ?? '') == 'MA' ? 'selected' : '' ?>>Maranhão</option>
        <option value="MT" <?= ($cliente['estado'] ?? '') == 'MT' ? 'selected' : '' ?>>Mato Grosso</option>
        <option value="MS" <?= ($cliente['estado'] ?? '') == 'MS' ? 'selected' : '' ?>>Mato Grosso do Sul</option>
        <option value="MG" <?= ($cliente['estado'] ?? '') == 'MG' ? 'selected' : '' ?>>Minas Gerais</option>
        <option value="PA" <?= ($cliente['estado'] ?? '') == 'PA' ? 'selected' : '' ?>>Pará</option>
        <option value="PB" <?= ($cliente['estado'] ?? '') == 'PB' ? 'selected' : '' ?>>Paraíba</option>
        <option value="PR" <?= ($cliente['estado'] ?? '') == 'PR' ? 'selected' : '' ?>>Paraná</option>
        <option value="PE" <?= ($cliente['estado'] ?? '') == 'PE' ? 'selected' : '' ?>>Pernambuco</option>
        <option value="PI" <?= ($cliente['estado'] ?? '') == 'PI' ? 'selected' : '' ?>>Piauí</option>
        <option value="RJ" <?= ($cliente['estado'] ?? '') == 'RJ' ? 'selected' : '' ?>>Rio de Janeiro</option>
        <option value="RN" <?= ($cliente['estado'] ?? '') == 'RN' ? 'selected' : '' ?>>Rio Grande do Norte</option>
        <option value="RS" <?= ($cliente['estado'] ?? '') == 'RS' ? 'selected' : '' ?>>Rio Grande do Sul</option>
        <option value="RO" <?= ($cliente['estado'] ?? '') == 'RO' ? 'selected' : '' ?>>Rondônia</option>
        <option value="RR" <?= ($cliente['estado'] ?? '') == 'RR' ? 'selected' : '' ?>>Roraima</option>
        <option value="SC" <?= ($cliente['estado'] ?? '') == 'SC' ? 'selected' : '' ?>>Santa Catarina</option>
        <option value="SP" <?= ($cliente['estado'] ?? '') == 'SP' ? 'selected' : '' ?>>São Paulo</option>
        <option value="SE" <?= ($cliente['estado'] ?? '') == 'SE' ? 'selected' : '' ?>>Sergipe</option>
        <option value="TO" <?= ($cliente['estado'] ?? '') == 'TO' ? 'selected' : '' ?>>Tocantins</option>
    </select>
</div>
    
    <div class="form-group mb-2">
        <label for="cep"><strong>CEP:</strong></label>
        <input type="text" class="form-control" id="cep" name="cep" value="<?= htmlspecialchars($cliente['cep'] ?? '') ?>">
    </div>
    
    <div class="form-group mb-2">
        <label for="complemento"><strong>Complemento:</strong></label>
        <textarea class="form-control" id="complemento" name="complemento"><?= htmlspecialchars($cliente['complemento'] ?? '') ?></textarea>
    </div>
      </div>
    </div>
</div>
        <hr>

        <h4 class="section-title">Referências</h4>
        <div class="row info-block">
            <div class="col-md-6">
                <div class="data-card">
                    <h6 class="section-title text-start">Dados da Referência:</h6>
                    <div class="form-group mb-2">
                      <label for="referencia_nome"><strong>Nome Completo:</strong></label>
                      <input type="text" class="form-control" id="referencia_nome" name="referencia_nome" value="<?= htmlspecialchars($cliente['referencia_nome'] ?? '') ?>">
                    </div>

                    <div class="form-group mb-2">
                      <label for="referencia_contato"><strong>Celular (WhatsApp):</strong></label>
                      <input type="tel" class="form-control" id="referencia_contato" name="referencia_contato" value="<?= htmlspecialchars($cliente['referencia_contato'] ?? '') ?>">
                    </div>

               

                    <div class="form-group mb-2">
                        <label for="referencia_parentesco" class="block text-gray-700">Grau de parentesco</label>
                        <select id="referencia_parentesco" name="referencia_parentesco" class="form-control rounded-lg mt-1 w-full p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="" disabled selected>Selecione</option>
                            <option value="Pai" <?= ($cliente['referencia_parentesco'] ?? '') == 'Pai' ? 'selected' : '' ?>>Pai</option>
                            <option value="Mãe" <?= ($cliente['referencia_parentesco'] ?? '') == 'Mãe' ? 'selected' : '' ?>>Mãe</option>
                            <option value="Marido" <?= ($cliente['referencia_parentesco'] ?? '') == 'Marido' ? 'selected' : '' ?>>Marido</option>
                            <option value="Esposa" <?= ($cliente['referencia_parentesco'] ?? '') == 'Esposa' ? 'selected' : '' ?>>Esposa</option>
                            <option value="Filho" <?= ($cliente['referencia_parentesco'] ?? '') == 'Filho' ? 'selected' : '' ?>>Filho</option>
                            <option value="Filha" <?= ($cliente['referencia_parentesco'] ?? '') == 'Filha' ? 'selected' : '' ?>>Filha</option>
                            <option value="Irmão" <?= ($cliente['referencia_parentesco'] ?? '') == 'Irmão' ? 'selected' : '' ?>>Irmão</option>
                            <option value="Irmã" <?= ($cliente['referencia_parentesco'] ?? '') == 'Irmã' ? 'selected' : '' ?>>Irmã</option>
                            <option value="Tio" <?= ($cliente['referencia_parentesco'] ?? '') == 'Tio' ? 'selected' : '' ?>>Tio</option>
                            <option value="Tia" <?= ($cliente['referencia_parentesco'] ?? '') == 'Tia' ? 'selected' : '' ?>>Tia</option>
                            <option value="Avô" <?= ($cliente['referencia_parentesco'] ?? '') == 'Avô' ? 'selected' : '' ?>>Avô</option>
                            <option value="Avó" <?= ($cliente['referencia_parentesco'] ?? '') == 'Avó' ? 'selected' : '' ?>>Avó</option>
                            <option value="Primo" <?= ($cliente['referencia_parentesco'] ?? '') == 'Primo' ? 'selected' : '' ?>>Primo</option>
                            <option value="Prima" <?= ($cliente['referencia_parentesco'] ?? '') == 'Prima' ? 'selected' : '' ?>>Prima</option>
                            <option value="Sogro" <?= ($cliente['referencia_parentesco'] ?? '') == 'Sogro' ? 'selected' : '' ?>>Sogro</option>
                            <option value="Sogra" <?= ($cliente['referencia_parentesco'] ?? '') == 'Sogra' ? 'selected' : '' ?>>Sogra</option>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                      <label for="indicacao"><strong>Quem te indicou:</strong></label>
                      <input type="text" class="form-control" id="indicacao" name="indicacao" value="<?= htmlspecialchars($cliente['indicacao'] ?? '') ?>">
                    </div>

                    <div class="form-group mb-2">
                      <label for="indicacao_contato"><strong>Celular (Whatsapp):</strong></label>
                      <input type="tel" class="form-control" id="indicacao_contato" name="indicacao_contato" value="<?= htmlspecialchars($cliente['indicacao_contato'] ?? '') ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="validation-card">
                    <h6 class="section-title text-start mb-3">Validações:</h6>
                    <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                        <input type="checkbox" class="custom-control-input" id="check_sobrenome_confere" name="check_sobrenome_confere" value="1" <?= $cliente['check_sobrenome_confere'] == 1 ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="check_sobrenome_confere">Sobrenome confere</label>
                    </div>
                    <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                        <input type="checkbox" class="custom-control-input" id="check_num_com_whatsapp" name="check_num_com_whatsapp" value="1" <?= $cliente['check_num_com_whatsapp'] == 1 ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="check_num_com_whatsapp">Número com WhatsApp</label>
                    </div>
                    <div class="form-group custom-control custom-checkbox small-checkbox my-1">
                        <input type="checkbox" class="custom-control-input" id="check_cliente_bom" name="check_cliente_bom" value="1" <?= $cliente['check_cliente_bom'] == 1 ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="check_cliente_bom">Cliente bom?</label>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="section-title">Ramo de Atuação</h4>

<?php
$motorista = ($cliente['ramo'] === 'uber');
$autonomo = ($cliente['ramo'] === 'autonomo');
$assalariado = ($cliente['ramo'] === 'assalariado');
?>
<div class="row">
  <div class="col-md-6">
    <div class="validation-card">
      <h6>Comprovantes Genéricos</h6>
      <hr>
      <div class="form-group custom-control custom-checkbox small-checkbox my-1">
        <input type="checkbox" class="custom-control-input" id="check_nome" name="check_nome" value="1" <?= $cliente['check_nome'] == 1 ? 'checked' : '' ?>>
        <label class="custom-control-label" for="check_nome">Nome</label>
      </div>
      <div class="form-group custom-control custom-checkbox small-checkbox my-1">
        <input type="checkbox" class="custom-control-input" id="check_data" name="check_data" value="1" <?= $cliente['check_data'] == 1 ? 'checked' : '' ?>>
        <label class="custom-control-label" for="check_data">Data</label>
      </div>
      <div class="form-group custom-control custom-checkbox small-checkbox my-1">
        <input type="checkbox" class="custom-control-input" id="check_ganhos" name="check_ganhos" value="1" <?= $cliente['check_ganhos'] == 1 ? 'checked' : '' ?>>
        <label class="custom-control-label" for="check_ganhos">Ganhos</label>
      </div>
    </div>
  </div>

  <?php if ($motorista): ?>
    <div class="col-md-6">
      <div class="validation-card">
        <h6>Validação de Motorista</h6>
        <hr>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
          <input type="checkbox" class="custom-control-input" id="check_taxa_aceitacao" name="check_taxa_aceitacao" value="1" <?= $cliente['check_taxa_aceitacao'] == 1 ? 'checked' : '' ?>>
          <label class="custom-control-label" for="check_taxa_aceitacao">Taxa de Aceitação</label>
        </div>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
          <input type="checkbox" class="custom-control-input" id="check_confere" name="check_confere" value="1" <?= $cliente['check_confere'] == 1 ? 'checked' : '' ?>>
          <label class="custom-control-label" for="check_confere">Confere</label>
        </div>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
          <input type="checkbox" class="custom-control-input" id="check_ativo" name="check_ativo" value="1" <?= $cliente['check_ativo'] == 1 ? 'checked' : '' ?>>
          <label class="custom-control-label" for="check_ativo">Está ativo</label>
        </div>
        <div class="form-group custom-control custom-checkbox small-checkbox my-1">
          <input type="checkbox" class="custom-control-input" id="check_cabecalhos" name="check_cabecalhos" value="1" <?= $cliente['check_cabecalhos'] == 1 ? 'checked' : '' ?>>
          <label class="custom-control-label" for="check_cabecalhos">Cabeçalhos/Horários prints</label>
        </div>
        <div class="data-card">
          <h6 class="section-title text-start">Dados:</h6>
          <div class="form-group mb-2">
            <label for="modelo_veiculo"><strong>Modelo do veículo:</strong></label>
            <input type="text" class="form-control" id="modelo_veiculo" name="modelo_veiculo" value="<?= htmlspecialchars($cliente['modelo_veiculo'] ?? '') ?>">
          </div>
          <div class="form-group mb-2">
            <label for="placa_veiculo"><strong>Placa:</strong></label>
            <input type="text" class="form-control" id="placa_veiculo" name="placa_veiculo" value="<?= htmlspecialchars($cliente['placa_veiculo'] ?? '') ?>">
          </div>
          <div class="form-group mb-2">
            <label for="status_veiculo" class="block text-gray-700">Status do veículo</label>
            <select id="status_veiculo" name="status_veiculo" class="form-control rounded-lg mt-1 w-full p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="" disabled selected>Selecione</option>
              <option value="proprio" <?= ($cliente['status_veiculo'] ?? '') == 'proprio' ? 'selected' : '' ?>>Próprio</option>
              <option value="alugado"<?= ($cliente['status_veiculo'] ?? '') == 'alugado' ? 'selected' : '' ?>>Alugado</option>
            </select>
          </div>
          <div class="form-group mb-2">
            <label for="valor_aluguel"><strong>Valor do aluguel:</strong></label>
            <input type="text" class="form-control" id="valor_aluguel" name="valor_aluguel" value="<?= htmlspecialchars($cliente['valor_aluguel'] ?? '') ?>">
          </div>
          <div class="form-group mb-2">
            <label for="frequencia_aluguel" class="block text-gray-700">Frequência do aluguel</label>
            <select id="frequencia_aluguel" name="frequencia_aluguel" class="form-control rounded-lg mt-1 w-full p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="" disabled selected>Selecione</option>
              <option value="diario"<?= ($cliente['frequencia_aluguel'] ?? '') == 'diario' ? 'selected' : '' ?>>Diário</option>
              <option value="semanal"<?= ($cliente['frequencia_aluguel'] ?? '') == 'semanal' ? 'selected' : '' ?>>Semanal</option>
              <option value="quinzenal"<?= ($cliente['frequencia_aluguel'] ?? '') == 'quinzenal' ? 'selected' : '' ?>>Quinzenal</option>
              <option value="mensal"<?= ($cliente['frequencia_aluguel'] ?? '') == 'mensal' ? 'selected' : '' ?>>Mensal</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-6">
      <div class="col-md-6 text-center">
        <h5 class="section-title">Print Perfil App:</h5>
        <?php if (!empty($cliente['print_perfil_app']) && $cliente['print_perfil_app'] !== 'sem-foto.png'): ?>
          <img src="/painel/images/comprovantes/<?= htmlspecialchars($cliente['print_perfil_app'] ?? '') ?>" alt="Print Perfil App" class="img-fluid rounded shadow-sm" style="max-width: 350px; border: 2px solid #ddd; display: block; margin: 0 auto;">
        <?php else: ?>
          <p>Não enviado.</p>
        <?php endif; ?>
        <div class="form-group mt-3">
          <label for="print_perfil_app">Substituir Print:</label>
          <input type="file" class="form-control" id="print_perfil_app" name="print_perfil_app">
        </div>
      </div>
      <div class="col-md-6 text-center">
        <h5 class="section-title">Print Veículo App:</h5>
        <?php if (!empty($cliente['print_veiculo_app']) && $cliente['print_veiculo_app'] !== 'sem-foto.png'): ?>
          <img src="/painel/images/comprovantes/<?= htmlspecialchars($cliente['print_veiculo_app'] ?? '') ?>" alt="Print Veículo App" class="img-fluid rounded shadow-sm" style="max-width: 350px; border: 2px solid #ddd; display: block; margin: 0 auto;">
        <?php else: ?>
          <p>Não enviado.</p>
        <?php endif; ?>
        <div class="form-group mt-3">
          <label for="print_veiculo_app">Substituir Print:</label>
          <input type="file" class="form-control" id="print_veiculo_app" name="print_veiculo_app">
        </div>
      </div>
    </div>
    <div class="row mb-6">
      <div class="col-md-6 text-center">
        <h5 class="section-title">Print Ganhos 30 Dias:</h5>
        <?php if (!empty($cliente['print_ganhos_30dias']) && $cliente['print_ganhos_30dias'] !== 'sem-foto.png'): ?>
          <img src="/painel/images/comprovantes/<?= htmlspecialchars($cliente['print_ganhos_30dias'] ?? '') ?>" alt="Print Ganhos 30 Dias" class="img-fluid rounded shadow-sm" style="max-width: 350px; border: 2px solid #ddd; display: block; margin: 0 auto;">
        <?php else: ?>
          <p>Não enviado.</p>
        <?php endif; ?>
        <div class="form-group mt-3">
          <label for="print_ganhos_30dias">Substituir Print:</label>
          <input type="file" class="form-control" id="print_ganhos_30dias" name="print_ganhos_30dias">
        </div>
      </div>
      <div class="col-md-6 text-center">
        <h5 class="section-title">Print Ganhos Hoje:</h5>
        <?php if (!empty($cliente['print_ganhos_hoje']) && $cliente['print_ganhos_hoje'] !== 'sem-foto.png'): ?>
          <img src="/painel/images/comprovantes/<?= htmlspecialchars($cliente['print_ganhos_hoje'] ?? '') ?>" alt="Print Ganhos Hoje" class="img-fluid rounded shadow-sm" style="max-width: 350px; border: 2px solid #ddd; display: block; margin: 0 auto;">
        <?php else: ?>
          <p>Não enviado.</p>
        <?php endif; ?>
        <div class="form-group mt-3">
          <label for="print_ganhos_hoje">Substituir Print:</label>
          <input type="file" class="form-control" id="print_ganhos_hoje" name="print_ganhos_hoje">
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php if ($autonomo): ?>
  <div class="row mb-4">
    <div class="col-md-6 text-center">
      <h5 class="section-title">Extrato 90 Dias:</h5>
      <?php if (!empty($cliente['extrato_90dias']) && $cliente['extrato_90dias'] !== 'sem-foto.png'): ?>
        <img src="/painel/images/comprovantes/<?= htmlspecialchars($cliente['extrato_90dias'] ?? '') ?>" alt="Extrato 90 Dias" class="img-fluid rounded shadow-sm" style="max-width: 450px; border: 2px solid #ddd; display: block; margin: 0 auto;">
      <?php else: ?>
        <p>Não enviado.</p>
      <?php endif; ?>
      <div class="form-group mt-3">
        <label for="extrato_90dias">Substituir Extrato:</label>
        <input type="file" class="form-control" id="extrato_90dias" name="extrato_90dias">
      </div>
    </div>
    <div class="col-md-6">
      <div class="data-card">
        <h6 class="section-title text-start">Dados do autônomo:</h6>
        <div class="form-group mb-2">
          <label for="funcao_autonomo"><strong>Função exercida:</strong></label>
          <input type="text" class="form-control" id="funcao_autonomo" name="funcao_autonomo" value="<?= htmlspecialchars($cliente['funcao_autonomo'] ?? '') ?>">
        </div>
        <div class="form-group mb-2">
          <label for="empresa_autonomo"><strong>Nome da empresa:</strong></label>
          <input type="text" class="form-control" id="empresa_autonomo" name="empresa_autonomo" value="<?= htmlspecialchars($cliente['empresa_autonomo'] ?? '') ?>">
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php if ($assalariado): ?>
  <div class="row mb-6">
    <div class="col-md-6 text-center">
      <h5 class="section-title">Contracheque:</h5>
      <?php if (!empty($cliente['contracheque']) && $cliente['contracheque'] !== 'sem-foto.png'): ?>
        <img src="/painel/images/comprovantes/<?= htmlspecialchars($cliente['contracheque'] ?? '') ?>" alt="Contracheque" class="img-fluid rounded shadow-sm" style="max-width: 450px; border: 2px solid #ddd; display: block; margin: 0 auto;">
      <?php else: ?>
        <p>Não enviado.</p>
      <?php endif; ?>
      <div class="form-group mt-3">
        <label for="contracheque">Substituir Contracheque:</label>
        <input type="file" class="form-control" id="contracheque" name="contracheque">
      </div>
    </div>
    <div class="col-md-6">
      <div class="data-card">
        <h6 class="section-title text-start">Dados do assalariado:</h6>
        <div class="form-group mb-2">
          <label for="funcao_assalariado"><strong>Função exercida:</strong></label>
          <input type="text" class="form-control" id="funcao_assalariado" name="funcao_assalariado" value="<?= htmlspecialchars($cliente['funcao_assalariado'] ?? '') ?>">
        </div>
        <div class="form-group mb-2">
          <label for="empresa_assalariado"><strong>Nome da empresa:</strong></label>
          <input type="text" class="form-control" id="empresa_assalariado" name="empresa_assalariado" value="<?= htmlspecialchars($cliente['empresa_assalariado'] ?? '') ?>">
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<hr>

<h4 class="section-title">Valores de Empréstimos</h4>
<?php if (!empty($cliente)): ?>
    <div class="card loan-values-card">
        <p>
            <strong>Valor Desejado:</strong>
            <span class="loan-value">R$ <?= number_format($cliente['valor_desejado'], 2, ',', '.') ?></span>
        </p>
        <div class="form-group mb-2">
            <label for="valor_desejado"><strong>Valor do empréstimo</strong></label>
            <input type="text" class="form-control" id="valor_desejado" name="valor_desejado" value="<?= htmlspecialchars($cliente['valor_desejado'] ?? '') ?>">
        </div>
    </div>
<?php else: ?>
    <p>Nenhum valor de empréstimo desejado registrado.</p>
<?php endif; ?>

<hr>

<h4 class="section-title">Finalizar Análise</h4>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="form-card mt-4">

            <input type="hidden" name="id_cliente" value="<?= htmlspecialchars($id_cliente ?? '') ?>">

            <div class="form-group mb-3">
                <label for="status_final" class="form-label">Selecione o status final:</label>
                <select class="form-select form-control" id="status_final" name="status_final">
                    <option value="" selected disabled>Escolha uma opção</option>
                    <option value="Aprovado">Aprovado</option>
                    <option value="Reprovado">Reprovado</option>
                    <option value="Pendente">Pendente</option>
                    <option value="Nao Validado">Não Validado</option>
                    <option value="Ativo">Ativo</option>
                    <option value="Inativo">Inativo</option>
                    <option value="Em Atraso">Em Atraso</option>
                    <option value="Negativado">Negativado</option>
                    <option value="Em Analise">Em Análise</option>
                    <option value="Nao Tem Interesse">Não tem Interesse</option>
                </select>
            </div>

            <div id="observacoes-reprovado" class="row" style="display: none;">
                <div class="form-group">
                    <label for="observacoes_reprovado">Observações (Reprovado):</label>
                    <select name="observacoes" id="observacoes_reprovado" class="form-select">
                        <option value="">Selecione uma opção</option>
                        <option value="Outra cidade">Outra cidade</option>
                        <option value="Fraude">Fraude</option>
                        <option value="Inadimplente">Inadimplente</option>
                        <option value="Negado consulta">Negado consulta</option>
                        <option value="Rendimentos baixos">Rendimentos baixos</option>
                        <option value="Solicitou cancelamento">Solicitou cancelamento</option>
                        <option value="CNH vencida">CNH vencida</option>
                        <option value="Não tem endereço no nome">Não tem endereço no nome</option>
                    </select>
                  </div>
                  <div class="form-group mb-2" id="div-observacoes">
                    <label for="observacoes_reprovacao" class="block text-gray-700">Observações (motivo da reprovação)</label>
                    <textarea id="observacoes_reprovacao" name="observacoes_reprovacao" rows="4" class="form-control rounded-lg mt-1 w-full p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                  </div>
            </div>

            <div id="observacoes-pendente" class="col-md-12" style="display: none;">
                <div class="form-group">
                    <label for="observacoes_pendente">Descrição (Pendente):</label>
                    <textarea name="observacoes_pendente_desc" id="observacoes_pendente" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <div id="observacoes-genericas" class="col-md-12" style="display: none;">
                <div class="form-group">
                    <label for="observacoes_genericas">Observações:</label>
                    <textarea name="observacoes_genericas_desc" id="observacoes_genericas" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="index.php?pagina=clientes" id="btn-sair" class="btn btn-outline-danger btn-lg mt-3">Sair sem Salvar</a>
                <button type="submit" class="btn btn-primary btn-lg mt-3">Finalizar Análise</button>
            </div>
        </div>
    </div>
</div>
</form> 



              </div>
          </div>
      </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).ready(function() {

  // Adicione este código dentro da função $(document).ready()
$('#btn-sair').on('click', function(e) {
    e.preventDefault(); // Impede a ação padrão do botão (que não tem, mas é uma boa prática)

    Swal.fire({
        title: 'Tem certeza?',
        text: "Todas as alterações feitas nesta página serão perdidas.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, sair!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Se o usuário confirmar, redirecione para a página de clientes
            window.location.href = 'index.php?pagina=clientes';
        }
    });
});
    // --- LÓGICA DO FORMULÁRIO DE FINALIZAÇÃO DE ANÁLISE ---

    // Lógica para mostrar/esconder campos de observação
    $('#status_final').on('change', function() {
        var status = $(this).val();

        // Esconde e limpa todos os campos de observação
        $('#observacoes-reprovado').hide().find('select').val('');
        $('#observacoes-pendente').hide().find('textarea').val('');
        $('#observacoes-genericas').hide().find('textarea').val('');

        // Remove o atributo 'required' de todos os campos de observação
        $('#observacoes_reprovado').removeAttr('required');
        $('#observacoes_pendente').removeAttr('required');

        // Exibe o campo de observação apropriado e o torna obrigatório se necessário
        if (status === 'Reprovado') {
            $('#observacoes-reprovado').show();
            $('#observacoes_reprovado').attr('required', 'required');
        } else if (status === 'Pendente') {
            $('#observacoes-pendente').show();
            $('#observacoes_pendente').attr('required', 'required');
        } else {
            // Para todos os outros status, mostra o campo de observação genérico
            $('#observacoes-genericas').show();
        }
    });

    // Lógica de validação na submissão do formulário
    $('#form-analise').on('submit', function(e) {
        var statusFinal = $('#status_final').val();
        
        // **NOVA LÓGICA DE VALIDAÇÃO PARA O STATUS "APROVADO"**
        if (statusFinal === 'Aprovado') {
            // Seleciona todas as checkboxes de validação
            // O seletor '.validation-card input[type="checkbox"]' é mais seguro
            // pois só pega as checkboxes dentro dos blocos de validação.
            var totalCheckboxes = $('.validation-card input[type="checkbox"]').length;
            var checkedCheckboxes = $('.validation-card input[type="checkbox"]:checked').length;
            
            if (checkedCheckboxes < totalCheckboxes) {
                Swal.fire({
                    title: 'Atenção!',
                    text: 'Para aprovar o cliente, todas as validações devem ser marcadas.',
                    icon: 'warning'
                });
                e.preventDefault(); // Impede o envio do formulário
            }
        }

        // Validação para status com observações obrigatórias
        if (statusFinal === 'Pendente' && $('#observacoes_pendente').val().trim() === '') {
            Swal.fire({
                title: 'Atenção!',
                text: 'A descrição para o status "Pendente" é obrigatória.',
                icon: 'warning'
            });
            e.preventDefault();
        }

        if (statusFinal === 'Reprovado' && $('#observacoes_reprovado').val().trim() === '') {
            Swal.fire({
                title: 'Atenção!',
                text: 'A observação para o status "Reprovado" é obrigatória.',
                icon: 'warning'
            });
            e.preventDefault();
        }
    });

    // --- LÓGICA DOS ALERTAS DE DUPLICIDADE (mantida) ---

    $(document).on('click', '.btn-resolvido', function() {
    var botao = $(this); // Captura o botão que foi clicado
    var alertaId = botao.data('id');
    var alertaElemento = $('#alerta-' + alertaId);
    
    Swal.fire({
        title: 'Tem certeza?',
        text: "Você irá marcar este alerta como resolvido/ignorado. Ele mudará para a cor verde.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#5cb85c', // Mudando a cor do botão de confirmação para verde
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, Ignorar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/painel/paginas/clientes/marca_alerta_resolvido.php',
                type: 'POST',
                data: {
                    id: alertaId
                },
                success: function(response) {
                    if (response.trim() === 'sucesso') {
                        // 1. Remove a classe de pendente e adiciona a de resolvido (VERDE)
                        alertaElemento.removeClass('alerta-pendente').addClass('alerta-resolvido');
                        
                        // 2. Substitui o botão "Ignorar" por um badge/status
                        botao.replaceWith('<span class="badge bg-success text-white">IGNORADO</span>'); 
                        
                        // A mensagem de "Nenhum alerta pendente" será controlada pelo PHP no próximo carregamento
                        // Mas podemos dar um feedback instantâneo sobre a resolução:
                        
                        Swal.fire(
                            'Ignorado!',
                            'O alerta foi ignorado.',
                            'success'
                        );
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