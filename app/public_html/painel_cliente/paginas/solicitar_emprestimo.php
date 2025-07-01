<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Empréstimo</title>

    <link rel="icon" href="img/icone.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeric/1.2.6/numeric.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/financial.js/0.1.1/financial.min.js"></script>

    <style type="text/css">
        /* Estilos base */
        :root {
            --primary-color: #2d4c63;
            --secondary-color: #4588bc;
            --accent-color: #ffc107;
            --silver-color: #c0c0c0;
            --gold-color: #ffd700;
            --diamond-color: #b9f2ff;
            --text-light: #333333; /* Branco puro */
            --text-dark: #333333;
            --border-radius: 10px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            --transition: all 0.3s ease;
            --custom-green: #2ba304;
        }
        body {
            font-family: "Poppins", sans-serif;
            color: var(--text-light); /* Cor padrão do texto do body (branco) */
            min-height: 100vh;
            line-height: 1.6;
        }
        /* Animações */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
        }
        .animate-pulse-button {
            animation: pulse 2s infinite;
        }
        .animate-pulse-button:hover {
            animation: none;
        }
        .bg-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        /* Estilos para animações GSAP */
        .gsap-reveal {
            opacity: 0;
            visibility: hidden;
        }
        .gsap-fade-up {
            opacity: 0;
            transform: translateY(30px);
        }
        .gsap-fade-in {
            opacity: 0;
        }
        .gsap-scale-in {
            opacity: 0;
            transform: scale(0.9);
        }
        .gsap-stagger-item {
            opacity: 0;
            transform: translateY(20px);
        }
        /* Estilo para o logo grande */
        .large-logo {
            width: auto !important;
            height: 120px !important;
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.3)) !important;
            animation: logoGlow 3s infinite alternate !important;
        }
        @keyframes logoGlow {
            from { filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.3)); }
            to { filter: drop-shadow(0 0 15px rgba(255, 193, 7, 0.5)); }
        }
        /* Estilo para o badge de lançamento */
        .launch-badge {
            background: linear-gradient(135deg, #ff7e5f, #feb47b);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }
        /* Estilo para os cards de recursos */
        .feature-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 193, 7, 0.3);
        }
        .feature-icon {
            color: #2ba304; /* Verde personalizado */
        }
        /* Estilo para a seção de chamada à ação */
        .cta-section {
            background: linear-gradient(135deg, rgba(45, 76, 99, 0.8), rgba(26, 44, 61, 0.8)), url('img/pattern.png');
            background-size: cover;
            background-position: center;
        }
        /* Estilo para a seção de depoimentos */
        .testimonial-card {
            position: relative;
        }
        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: -20px;
            left: 20px;
            font-size: 80px;
            color: rgba(255, 193, 7, 0.2);
            font-family: serif;
            line-height: 1;
        }
        /* Estilo para a timeline de lançamento */
        .timeline-container {
            position: relative;
        }
        .timeline-container::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 50%;
            width: 2px;
            background-color: rgba(255, 193, 7, 0.5);
            transform: translateX(-50%);
        }
        .timeline-item {
            position: relative;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 16px;
            height: 16px;
            background-color: #ffc107;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
        }
        /* Estilo para a seção de contato social */
        .social-icon {
            transition: all 0.3s ease;
        }
        .social-icon:hover {
            transform: translateY(-3px) scale(1.1);
            color: #ffc107;
        }
        /* Ajustes responsivos */
        @media (max-width: 640px) {
            .large-logo {
                height: 80px !important;
            }
            .feature-card {
                padding: 1rem !important;
            }
            .feature-icon {
                font-size: 2rem !important;
                margin-bottom: 0.75rem !important;
            }
            .plan-card .p-6 {
                padding: 1rem !important;
            }
        }
        /* Melhorar a visualização em dispositivos móveis */
        .container {
            width: 100%;
            padding-right: 1rem;
            padding-left: 1rem;
        }
        @media (min-width: 640px) {
            .container {
                padding-right: 1.5rem;
                padding-left: 1.5rem;
            }
        }
        @media (min-width: 1024px) {
            .container {
                padding-right: 2rem;
                padding-left: 2rem;
            }
        }
        /* Estilos para formulários - Ajustado para texto mais escuro */
        .form-input {
            background-color: #ffffff !important;
            color: #000000 !important; /* Cor do texto do input: preto */
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 1rem 1rem 1rem 2.5rem !important; /* AUMENTADO O PADDING AQUI */
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
            transition: all 0.2s ease !important;
        }
        .form-input:focus {
            border-color: #ffc107 !important;
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.25) !important;
            outline: none !important;
        }
        .form-input::placeholder {
            color: #9ca3af !important; /* Cor do placeholder */
        }
        .form-label {
            display: block !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            color: var(--text-light) !important;
            margin-bottom: 0.375rem !important;
        }
        .form-select {
            color: #9ca3af !important; 
            background-color: #ffffff !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
            transition: all 0.2s ease !important;
        }

        .form-select option {
            color: #9ca3af !important;  /* Cor do texto das opções na lista */
        }

        .form-select option[disabled] {
            color: #6b7280 !important; /* Cor para a opção desabilitada/placeholder */
        }

        /* Você já tinha isso, mas reforce para a opção selecionada se necessário */
        .form-select option:checked {
            color: #000000 !important;
            background-color: #f0f0f0 !important; /* Adicione um fundo para contraste, se desejar */
        }
        /* Botões */
        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
            font-weight: 600;
            padding: 10px 25px;
            border-radius: 30px;
            transition: var(--transition);
            animation: pulse 2s infinite;
        }
        .btn-primary:hover {
            background-color: #e0a800;
            border-color: #e0a800;
            transform: translateY(-3px);
            animation: none;
        }

        /* Botão verde personalizado */
        .btn-green {
            background-color: #2ba304;
            border-color: #2ba304;
            color: white;
            font-weight: 600;
            padding: 10px 25px;
            border-radius: 30px;
            transition: var(--transition);
            animation: pulseGreen 2s infinite;
        }

        .btn-green:hover {
            background-color: #239003;
            border-color: #239003;
            transform: translateY(-3px);
            animation: none;
        }

        @keyframes pulseGreen {
            0% { box-shadow: 0 0 0 0 rgba(43, 163, 4, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(43, 163, 4, 0); }
            100% { box-shadow: 0 0 0 0 rgba(43, 163, 4, 0); }
        }

        .animate-pulse-green {
            animation: pulseGreen 2s infinite;
        }

        .animate-pulse-green:hover {
            animation: none;
        }

        /* Animações */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        /* Decorações */
        .decoration-dot {
            position: absolute;
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 9999px;
            background-color: var(--accent-color);
            opacity: 0.7;
        }
        /* Efeito de brilho */
        .glow {
            box-shadow: 0 0 15px rgba(255, 193, 7, 0.5);
        }
        /* Efeito de gradiente no texto */
        .text-gradient {
            background: linear-gradient(90deg, #ffc107, #ffcd38);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        /* Ajuste para ícones nos inputs */
        .input-icon {
            position: absolute !important;
            top: 50% !important;
            left: 0.75rem !important;
            transform: translateY(-50%) !important;
            color: #6b7280 !important;
            pointer-events: none !important;
        }
        /* Estilo para o badge de pagamento seguro */
        .secure-payment-badge {
            background-color: rgba(16, 185, 129, 0.1) !important;
            border: 1px solid rgba(16, 185, 129, 0.3) !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem !important;
            display: flex !important;
            align-items: center !important;
            margin-top: 1rem !important;
        }
        .secure-payment-badge i {
            color: #10b981 !important;
            margin-right: 0.75rem !important;
        }
        /* Estilo para o logo grande no footer */
        .large-logo-footer {
            width: auto !important;
            height: 80px !important;
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.2)) !important;
            animation: logoFooterGlow 3s infinite alternate !important;
        }
        @keyframes logoFooterGlow {
            from { filter: drop-shadow(0 0 3px rgba(255, 255, 255, 0.2)); }
            to { filter: drop-shadow(0 0 10px rgba(255, 193, 7, 0.4)); }
        }
        /* Ajustes responsivos para dispositivos móveis */
        @media (max-width: 640px) {
            .large-logo {
                height: 90px !important;
            }
            .bg-glass {
                border-radius: 1rem !important;
            }
            .form-input, .form-select {
                padding: 0.8rem 1rem 0.8rem 2.25rem !important; /* AUMENTADO O PADDING AQUI PARA TELAS MENORES */
            }
            .secure-payment-badge {
                padding: 0.5rem !important;
                margin-top: 0.75rem !important;
            }
            .large-logo-footer {
                height: 60px !important;
            }
        }
        /* Melhorar a visualização em dispositivos muito pequenos */
        @media (max-width: 360px) {
            .form-label {
                font-size: 0.75rem !important;
            }
            .form-input, .form-select {
                font-size: 0.875rem !important;
            }
            .btn-primary {
                font-size: 0.875rem !important;
                padding: 0.625rem !important;
            }
            .plan-price {
                flex-direction: row;
                align-items: baseline;
            }
            .amount {
                font-size: 28px;
            }
            .plan-features li {
                font-size: 14px;
            }
        }
        /* Ajustes adicionais para espaçamento em dispositivos móveis */
        @media (max-width: 640px) {
            .mb-20 {
                margin-bottom: 2.5rem !important;
            }
            .py-12 {
                padding-top: 2rem !important;
                padding-bottom: 2rem !important;
            }
            .gap-8 {
                gap: 1.5rem !important;
            }
        }
        /* Cor personalizada para os ícones */
        .custom-green-icon {
            color: #2ba304 !important;
        }
        .hidden { display: none; }
        .bg-left {
            background-color: #f9fafb; /* cinza claro */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }
        /* Estilo para o título do modal */
        #exampleModalLabel {
            width: 100% !important; 
            display: block !important; 
        }

        /* APLICANDO A COR ESPECÍFICA AO SPAN DO TÍTULO */
        #titulo_inserir {
            color: #30526c !important; 
        }
        .text-bold {
            font-weight: 900 !important; /* Ou 800. 700 é o padrão de 'bold'. */
            /* Mantenha a cor se for diferente do padrão */
            color: inherit; /* Ou a cor que você deseja */
        }
    </style>
</head>
<body>

<?php 
    $pag = 'solicitar_emprestimo';
?>

<div class="main-page margin-mobile">

<div class="row">
    <div class="col-md-2">
        <a onclick="inserir()" type="button" class="btn btn-primary">Solicitar Empréstimo</a>
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

<div class="bs-example widget-shadow" style="padding:15px;margin-top:0px" id="listar">

</div>

</div>

<input type="hidden" id="ids">

<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-2xl font-bold text-center" id="exampleModalLabel"><span id="titulo_inserir"></span></h4>
                <button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form">
                <div class="modal-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        <div> 
                            <label class="form-label">Valor do Empréstimo</label>
                            <input type="text" class="form-control money form-input w-full" id="valor_emprestimo" name="valor_emprestimo" placeholder="Valor do Empréstimo">
                        </div>
                        <div> 
                            <label class="form-label">Valor Parcela</label>
                            <input type="text" class="form-control money form-input w-full" id="valor_parcela" name="valor_parcela" placeholder="Valor Parcela">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                      <div class="form-group">
                          <label for="taxa_juros">Taxa Juros Total (%):</label>
                          <input type="number" step="0.01" id="taxa_juros" name="taxa_juros" class="form-control" placeholder="Ex: 30" required>
                          <small id="taxa_mensal_resultado" class="form-text text-muted"></small>
                      </div>
                        <div> 
                            <label class="form-label">Total de Parcelas</label>
                            <input type="number" class="form-control form-input w-full" id="total_parcelas" name="total_parcelas" placeholder="Número de Parcelas" min="1">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        <div> 
                            <label class="form-label">Tipo de Vencimento</label>
                            <select class="form-control form-select w-full" id="tipo_vencimento" name="tipo_vencimento">
                                <option value="" disabled selected>Selecione</option>
                                <option value="diario">Diário</option>
                                <option value="semanal">Semanal</option>
                                <option value="quinzenal">Quinzenal</option>
                                <option value="mensal">Mensal</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <small class="text-bold">Empréstimo sujeito a análise.</small>
                        </div>
                    </div>
                    
                    <input type="hidden" class="form-control" id="id" name="id" >
                    <input type="hidden" class="form-control" id="cliente" name="cliente" value="<?php echo $id_usuario ?>">    

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>

<script>
    // Função para limpar e converter para float
    function limparParaNumero(valor) {
        if (typeof valor === 'string') {
            // Remove tudo exceto dígitos, ponto e vírgula, depois substitui vírgula por ponto
            return parseFloat(valor.replace(/[^\d.,-]/g, '').replace(',', '.'));
        }
        return parseFloat(valor);
    }

    const valorEmprestimoInput = document.getElementById('valor_emprestimo');
    const valorParcelaInput = document.getElementById('valor_parcela');
    const taxaJurosInput = document.getElementById('taxa_juros'); // Taxa Total da Operação
    const totalParcelasInput = document.getElementById('total_parcelas');
    const tipoVencimentoSelect = document.getElementById('tipo_vencimento');
    const taxaMensalResultado = document.getElementById('taxa_mensal_resultado'); // Novo elemento para exibir a taxa mensal

    // Adiciona event listeners para os campos de entrada
    // O evento 'input' é disparado a cada alteração no valor do campo.
    valorEmprestimoInput.addEventListener('input', calcularJurosSimples);
    valorParcelaInput.addEventListener('input', calcularJurosSimples);
    taxaJurosInput.addEventListener('input', calcularJurosSimples);
    totalParcelasInput.addEventListener('input', calcularJurosSimples);
    tipoVencimentoSelect.addEventListener('change', calcularJurosSimples); // O select usa 'change'

    function calcularJurosSimples() {
        // Limpa os valores para cálculos
        let PV = limparParaNumero(valorEmprestimoInput.value) || null;
        let PMT = limparParaNumero(valorParcelaInput.value) || null;
        let taxa_total = limparParaNumero(taxaJurosInput.value) || null; // Taxa Total da Operação em %
        let n = limparParaNumero(totalParcelasInput.value) || null;
        let tipoVencimento = tipoVencimentoSelect.value;

        // Converter taxa_total para decimal (se for o caso)
        if (taxa_total !== null) {
            taxa_total = taxa_total / 100;
        }

        // Determinar qual campo está vazio e tentar calcular
        try {
            if (PV === null && PMT !== null && taxa_total !== null && n !== null && taxa_total !== -1) {
                // Calcular PV: PV = PMT × n / (1 + taxa)
                PV = (PMT * n) / (1 + taxa_total);
                valorEmprestimoInput.value = PV.toFixed(2);
            } else if (PMT === null && PV !== null && taxa_total !== null && n !== null && n !== 0 && (1 + taxa_total) !== 0) {
                // Calcular PMT: PMT = PV × (1 + taxa) / n
                PMT = (PV * (1 + taxa_total)) / n;
                valorParcelaInput.value = PMT.toFixed(2);
            } else if (taxa_total === null && PV !== null && PMT !== null && n !== null && PV !== 0) {
                // Calcular taxa_total: taxa = (PMT × n / PV) – 1
                taxa_total = ((PMT * n) / PV) - 1;
                taxaJurosInput.value = (taxa_total * 100).toFixed(2); // Exibir em %
            } else if (n === null && PV !== null && PMT !== null && taxa_total !== null && (1 + taxa_total) !== 0 && PMT !== 0) {
                // Calcular n: n = PV × (1 + taxa) / PMT
                n = (PV * (1 + taxa_total)) / PMT;
                totalParcelasInput.value = Math.ceil(n); // Arredonda para cima para número de parcelas
            }
        } catch (e) {
            console.error("Erro no cálculo financeiro de juros simples:", e);
            // Poderia exibir uma mensagem de erro ao usuário
        }

        // Após calcular os campos, calcular a taxa mensal
        calcularTaxaMensal(taxa_total, n, tipoVencimento);
    }

    function calcularTaxaMensal(taxa_total, n, tipoVencimento) {
        // if (taxa_total === null || n === null || n === 0) {
        //     taxaMensalResultado.innerText = 'Preencha Taxa Total e Nº Parcelas para Taxa Mensal.';
        //     return;
        // }

        let r_periodo = taxa_total / n; // Taxa por período
        let fator = 0;

        switch (tipoVencimento) {
            case 'diario':
                fator = 30;
                break;
            case 'semanal':
                fator = 4;
                break;
            case 'quinzenal':
                fator = 2;
                break;
            case 'mensal':
                fator = 1;
                break;
            default:
                taxaMensalResultado.innerText = 'Selecione o Tipo de Vencimento.';
                return;
        }

        let r_mes = r_periodo * fator;
        taxaMensalResultado.innerText = `Taxa Mensal Aproximada: ${(r_mes * 100).toFixed(2)}%`;
    }

    // Chama a função de cálculo quando a página é carregada
    document.addEventListener('DOMContentLoaded', calcularJurosSimples);
</script>

</body>
</html>