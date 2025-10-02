<?php require_once("../conexao.php"); ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="keywords" content="erp, sistema de gestão, vendi anotei, controle financeiro, gestão de estoque, vendas online" />
    <meta name="description" content="Vendi, Anotei - O ERP Completo para Alavancar Seu Negócio! Sistema 100% online, intuitivo e integrado." />
    <meta name="author" content="Hugo Vasconcelos" />
    <title><?php echo $nome_sistema ?></title>
    <link rel="icon" href="img/icone.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://unpkg.com/heic2any@0.0.15/dist/heic2any.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#2d4c63',
                            dark: '#4689bd',
                            light: '#3a6080'
                        },
                        accent: {
                            DEFAULT: '#ffc107',
                            dark: '#e0a800',
                            light: '#ffcd38'
                        },
                        success: '#10b981',
                        warning: '#f59e0b',
                        danger: '#ef4444',
                        customGreen: '#2ba304', // Adicionando a cor personalizada
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style type="text/css">
        /* Estilos base */
        :root {
            --primary-color: #2d4c63;
            --secondary-color: #4588bc;
            --accent-color: #ffc107;
            --silver-color: #c0c0c0;
            --gold-color: #ffd700;
            --diamond-color: #b9f2ff;
            --text-light: #ffffff;
            --text-dark: #333333;
            --border-radius: 10px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            --transition: all 0.3s ease;
            --custom-green: #2ba304; /* Adicionando a cor personalizada */
        }
        body {
            font-family: "Poppins", sans-serif;
            background: #7c3444;
            color: var(--text-light);
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
            color: #000000 !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem 1rem 0.75rem 0.75rem !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
            transition: all 0.2s ease !important;
        }
        .form-input:focus {
            border-color: #ffc107 !important;
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.25) !important;
            outline: none !important;
        }
        .form-input::placeholder {
            color: #9ca3af !important;
        }
        .form-label {
            display: block !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            color: #f3f4f6 !important;
            margin-bottom: 0.375rem !important;
        }
        .form-select {
            background-color: #ffffff !important;
            color: #000000 !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem 1rem 0.75rem 2.5rem !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
            transition: all 0.2s ease !important;
        }
        .form-select:focus {
            border-color: #ffc107 !important;
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.25) !important;
            outline: none !important;
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
                padding: 0.625rem 1rem 0.625rem 2.25rem !important;
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
        .form-input.invalid, .form-select.invalid {
            border-color: red !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.25) !important; /* Mais suave */
        }
        .btn-camera {
          background-color:#92b2d9;
          color: white;
          padding: 0.75rem 1.5rem;
          border-radius: 0.5rem; 
          font-weight: 500;
          transition: background-color 0.2s ease-in-out;
          cursor: pointer;
          border: none; 
        }
        .btn-camera:hover {
          background-color: #5291dd; 
        }
        .info-icon {
          position: relative;
          display: inline-block;
        }

        .info-tooltip {
          visibility: hidden;
          width: 200px;
          background-color: #333;
          color: #fff;
          text-align: center;
          border-radius: 6px;
          padding: 5px;
          position: absolute;
          z-index: 1;
          bottom: 125%;
          left: 50%;
          margin-left: -100px;
          opacity: 0;
          transition: opacity 0.3s;
        }

        .info-icon:hover .info-tooltip {
          visibility: visible;
          opacity: 1;
        }
        /* CSS para o overlay de loading */
        .loading-overlay {
            display: none; /* Esconde o overlay por padrão */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7); /* Fundo semi-transparente */
            z-index: 1000; /* Garante que fique acima de outros elementos */
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .spinner {
            border: 4px solid #f3f3f3; /* Cor do spinner (cinza claro) */
            border-top: 4px solid #3498db; /* Cor da parte que gira (azul) */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        .loading-text {
            margin-top: 15px;
            color: #fff;
            font-size: 1.2em;
        }

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
    </style>
</head>
<body class="font-poppins bg-gradient-to-br from-primary-dark to-primary text-white min-h-screen overflow-x-hidden">
    <div class="flex flex-col md:flex-row h-screen items-center justify-center">
        <div class="hidden md:block w-full md:w-1/3 h-1/2 md:h-full bg-left flex items-center justify-center pt-20">
            <img src="img/logo2.png" alt="Imagem de Empréstimo" class="object-contain max-h-full max-w-full" />
        </div>

        <div class="w-full md:w-2/3 p-8 text-white flex items-start justify-center overflow-y-auto">
            <form id="form" class="w-full max-w-4xl space-y-4" novalidate>
                <div class="form-step" id="step-1">
                    <h2 class="text-xl font-bold mb-4">1. Dados Pessoais</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                        <div>
                            <label class="block text-sm font-medium text-white">Nome completo</label>
                            <input type="text" id="nome" name="nome" placeholder="Nome completo" class="form-input w-full" required onblur="validateField(this)">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white">Email</label>
                            <input type="email" id="email" name="email" placeholder="Email" class="form-input w-full" required onblur="validateField(this)">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white">Celular (Whatsapp)</label>
                            <input type="text" name="telefone" id="telefone" placeholder="Telefone" class="form-input w-full" required onblur="validateField(this)">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-white">CPF</label>
                            <input type="text" name="cpf" id="cpf" placeholder="CPF" class="form-input w-full" required onblur="validateCPF(this)">
                            <div id="cpf-error-backend" class="text-red-500 text-sm mt-1"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white">RG</label>
                            <input type="text" name="rg" id="rg" placeholder="RG" class="form-input w-full" required onblur="validateField(this)">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white">Data de Nascimento</label>
                            <input type="text" id="data_nasc" name="data_nasc" class="form-input w-full" placeholder="DD/MM/AAAA" required onblur="validateField(this)">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-white">Chave Pix em sua titularidade</label>
                            <input type="text" name="pix" id="pix" placeholder="CPF, telefone ou e-mail" class="form-input w-full" required onblur="validateField(this)">
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-white">CNH ou RG</label>
                                <input type="file" id="comprovante_rg" name="comprovante_rg" onchange="carregarImgComprovanteRG(); validateField(this)" accept=".jpg,.jpeg,.png,.heic,.webp,.avif" capture="camera"  class="form-input w-full" required>
                            </div>
                            <div class="w-20 h-20 border border-gray-300 rounded overflow-hidden bg-white">
                                <img src="painel/images/comprovantes/sem-foto.png" id="target-comprovante-rg" class="object-cover w-full h-full">
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-white">Comprovante de Endereço</label>
                                <input type="file" name="comprovante_endereco" id="comprovante_endereco" onchange="carregarImgComprovanteEndereco(); validateField(this)" accept=".jpg,.jpeg,.png,.heic,.webp,.avif" capture="camera"  class="form-input w-full" required>
                            </div>
                            <div class="w-20 h-20 border border-gray-300 rounded overflow-hidden bg-white">
                                <img src="painel/images/comprovantes/sem-foto.png" id="target-comprovante-endereco" class="object-cover w-full h-full">
                            </div>
                        </div>
                    </div>

                    <div class="text-md text-gray-300 mt-4 text-right">
                        <p>Talão de água ou energia com emissão de no máximo 60 dias </p>
                    </div>

                    <div class="pt-4 text-right">
                        <button type="button" class="btn-primary" onclick="nextStep()">Próximo</button>
                    </div>
                </div>

                <div class="form-step hidden" id="step-2">
                    <h2 class="text-xl font-bold mb-4">2.Login</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
                        <div>
                            <label class="block text-sm font-medium text-white">Senha</label>
                            <div class="relative"> <input type="password" name="senha" id="senha" class="form-input w-full" required onblur="validateField(this)">
                                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5" onclick="togglePasswordVisibility('senha', 'togglePassword')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-white">Confirmar Senha</label>
                            <div class="relative"> <input type="password" name="conf_senha" id="conf_senha" class="form-input w-full" required onblur="validateField(this)">
                                <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5" onclick="togglePasswordVisibility('conf_senha', 'toggleConfirmPassword')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 items-start">
                      <div>
                          <label class="block text-sm font-medium text-white">Foto do Usuário</label>
                          <!-- <button type="button" onclick="tirarFoto()" class="btn-primary w-full mb-2">Tirar Foto</button> -->
                          <button type="button" onclick="tirarFoto()" class="btn-camera w-full mb-2 flex items-center justify-center space-x-2">
                            <i class="fas fa-camera"></i> <span>Tirar Foto</span>
                          </button>
                          <video id="cameraPreview" autoplay style="width: 100%; max-width: 300px; display: none;"></video>
                          <canvas id="photoCanvas" style="display: none;"></canvas>
                          <input type="hidden" id="foto_usuario" name="foto_usuario" onchange="validateField(this)">
                      </div>
                      <div class="w-24 h-24 border border-gray-300 rounded overflow-hidden bg-white">
                          <img src="painel/images/comprovantes/sem-foto.png" id="foto" name="foto" class="object-cover w-full h-full">
                      </div>
                    </div>

                    <div class="pt-4 flex justify-between">
                      <button type="button" class="btn-primary" onclick="prevStep()">Voltar</button>
                      <button type="button" class="btn-primary" onclick="nextStep()">Próximo</button>
                    </div>
                </div>

                <div class="form-step hidden" id="step-3">
                    <h2 class="text-xl font-bold mb-4">3. Endereço</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-white">CEP</label>
                            <input type="text" name="cep" id="cep" class="form-input w-full" onblur="pesquisacep(this.value); validateField(this)" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white">Endereço</label>
                            <input type="text" name="endereco" id="endereco" class="form-input w-full" required onblur="validateField(this)">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white">Bairro</label>
                            <input type="text" name="bairro" id="bairro" class="form-input w-full" required onblur="validateField(this)">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 mb-3">
                        <div>
                            <label class="block text-sm font-medium text-white">Cidade</label>
                            <input type="text" name="cidade" id="cidade" class="form-input w-full" required onblur="validateField(this)">
                        </div>
                        <div>
                            <label for="estado" class="block text-sm font-medium text-white">Estado</label>
                            <select id="estado" name="estado" class="form-input w-full" required onblur="validateField(this)">
                                <option value="" disabled selected>Selecione o Estado</option>
                                <option value="AC">Acre</option>
                                <option value="AL">Alagoas</option>
                                <option value="AP">Amapá</option>
                                <option value="AM">Amazonas</option>
                                <option value="BA">Bahia</option>
                                <option value="CE">Ceará</option>
                                <option value="DF">Distrito Federal</option>
                                <option value="ES">Espírito Santo</option>
                                <option value="GO">Goiás</option>
                                <option value="MA">Maranhão</option>
                                <option value="MT">Mato Grosso</option>
                                <option value="MS">Mato Grosso do Sul</option>
                                <option value="MG">Minas Gerais</option>
                                <option value="PA">Pará</option>
                                <option value="PB">Paraíba</option>
                                <option value="PR">Paraná</option>
                                <option value="PE">Pernambuco</option>
                                <option value="PI">Piauí</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <option value="RN">Rio Grande do Norte</option>
                                <option value="RS">Rio Grande do Sul</option>
                                <option value="RO">Rondônia</option>
                                <option value="RR">Roraima</option>
                                <option value="SC">Santa Catarina</option>
                                <option value="SP">São Paulo</option>
                                <option value="SE">Sergipe</option>
                                <option value="TO">Tocantins</option>
                                <option value="EX">Estrangeiro</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div class="md:col-span-1">
                        <div style="display: flex; align-items: center; gap: 8px;">
                          <label class="block text-sm font-medium text-white">Quadra</label>
                          <div class="info-icon">
                              <i class="fas fa-info-circle" style="color: #fff; cursor: pointer;"></i>
                              <span class="info-tooltip">Q-10.</span>
                          </div>
                        </div>
                        <input type="text" name="quadra" id="quadra" class="form-input w-full" required onblur="validateField(this)">
                    </div>

                    <div class="md:col-span-1">
                        <div style="display: flex; align-items: center; gap: 8px;">
                          <label class="block text-sm font-medium text-white">Lote</label>
                          <div class="info-icon">
                              <i class="fas fa-info-circle" style="color: #fff; cursor: pointer;"></i>
                              <span class="info-tooltip">Se não houver lote, preencha com 0.</span>
                          </div>
                        </div>
                        <input type="number" name="lote" id="lote" class="form-input w-full" min="0" required onblur="validateField(this)">
                    </div>

                    <div class="md:col-span-1">
                        <div style="display: flex; align-items: center; gap: 8px;">
                          <label class="block text-sm font-medium text-white">Número</label>
                          <div class="info-icon">
                              <i class="fas fa-info-circle" style="color: #fff; cursor: pointer;"></i>
                              <span class="info-tooltip">Se não houver número, preencha com 0.</span>
                          </div>
                        </div>
                        <input type="number" name="numero" id="numero" class="form-input w-full" min="0" required onblur="validateField(this)">
                    </div>
                        <div class="md:col-span-3">
                          <label class="block text-sm font-medium text-white">Complemento</label>
                          <input type="text" name="complemento" id="complemento" class="form-input w-full" onblur="validateField(this)">
                        </div>
                    </div>
                    <div class="pt-4 flex justify-between">
                        <button type="button" class="btn-primary" onclick="prevStep()">Voltar</button>
                        <button type="button" class="btn-primary" onclick="nextStep()">Próximo</button>
                    </div>
                </div>

                <div class="form-step hidden" id="step-4">
                    <h2 class="text-xl font-bold mb-4">4. Referência (parente de primeiro grau)</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                          <label class="block text-sm font-medium text-white">Nome Completo</label>
                          <input type="text" id="referencia_nome" name="referencia_nome" placeholder="Nome Completo" class="form-input w-full" required onblur="validateField(this)">
                        </div>
                        <div>
                          <label class="block text-sm font-medium text-white">Celular (Whatsapp)</label>
                          <input type="text" name="referencia_contato" id="referencia_contato" placeholder="(XX)XXXX-XXX)" class="form-input w-full" required onblur="validateField(this)">
                        </div>
                        <div>
                          <label for="referencia_parentesco" class="block text-sm font-medium text-white">Grau de parentesco</label>
                            <select id="referencia_parentesco" name="referencia_parentesco" class="form-input w-full" required onblur="validateField(this)">
                              <option value="" disabled selected>Selecione</option>
                              <option value="Pai">Pai</option>
                              <option value="Mãe">Mãe</option>
                              <option value="Marido">Marido</option>
                              <option value="Esposa">Esposa</option>
                              <option value="Filho">Filho</option>
                              <option value="Filha">Filha</option>
                              <option value="Irmão">Irmão</option>
                              <option value="Irmã">Irmã</option>
                              <option value="Tio">Tio</option>
                              <option value="Tia">Tia</option>
                              <option value="Avô">Avô</option>
                              <option value="Avó">Avó</option>
                              <option value="Primo">Primo</option>
                              <option value="Prima">Prima</option>
                              <option value="Sogro">Sogro</option>
                              <option value="Sogra">Sogra</option>
                            </select>
                        </div>
                    </div>

                    <!-- <label class="block text-md font-medium text-white mt-6 mb-6">Quem te indicou?</label> -->

                    <h2 class="text-xl font-bold mb-4 mt-4">4.1 Quem te indicou ?</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <div>
                        <label class="block text-sm font-medium text-white">Nome Completo</label>
                        <input type="text" id="indicacao" name="indicacao" placeholder="Indicado por" class="form-input w-full" onblur="validateField(this)" required>
                      </div>

                      <div>
                        <label class="block text-sm font-medium text-white">Celular (Whatsapp)</label>
                        <input type="text" id="indicacao_contato" name="indicacao_contato" placeholder="Indicado por" class="form-input w-full" onblur="validateField(this)" required>
                      </div>
                    </div>
                  
                    <div class="pt-4 flex justify-between">
                        <button type="button" class="btn-primary" onclick="prevStep()">Voltar</button>
                        <button type="button" class="btn-primary" onclick="nextStep()">Próximo</button>
                    </div>
                </div>


        <!-- Step 5: Ramo de Atuação e Informações do Veículo/Renda -->
        <div class="form-step hidden" id="step-5">
            <h2 class="text-xl font-bold mb-4">5. Ramo de Atuação e Informações de Renda</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="ramo" class="block text-sm font-medium text-white">Ramo de Atuação</label>
                    <select id="ramo" name="ramo" class="form-input w-full" required onchange="handleRamoChange(); validateField(this)">
                        <option value="" disabled selected>Selecione um ramo</option>
                        <option value="autonomo">Autônomo</option>
                        <option value="uber">Motorista/Entregador App</option>
                        <option value="assalariado">Assalariado</option>
                    </select>
                </div>
            </div>

            <!-- Campos para Motorista/Entregador App (Uber) -->
            <div id="veiculo-campos" class="hidden mt-4 border border-yellow-400 p-4 rounded bg-yellow-100 text-black">
                <p class="text-sm font-semibold mb-4">Preencha os campos abaixo se você for motorista ou entregador:</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Modelo do Veículo</label>
                        <input type="text" name="modelo_veiculo" id="modelo_veiculo" class="form-input w-full uber-obrigatorio" required onblur="validateField(this)">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Placa</label>
                        <input type="text" name="placa_veiculo" id="placa_veiculo" class="form-input w-full uber-obrigatorio" maxlength="7" placeholder="ABC1234" required onblur="validatePlaca(this)">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                      <label class="block text-sm font-medium">Status do Veículo</label>
                      <select name="status_veiculo" id="status_veiculo" class="form-input w-full uber-obrigatorio" required onchange="handleStatusVeiculoChange(); validateField(this)">
                        <option value="" disabled selected>Selecione</option>
                        <option value="proprio">Próprio</option>
                        <option value="alugado">Alugado</option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-sm font-medium">Valor do Aluguel</label>
                      <input type="text" name="valor_aluguel" id="valor_aluguel" class="form-input w-full uber-obrigatorio" placeholder="R$" min="0" step="0.01" onblur="validateField(this)">
                    </div>

                    <div>
                      <label class="block text-sm font-medium">Frequência do Aluguel</label>
                      <select name="frequencia_aluguel" id="frequencia_aluguel" class="form-input w-full" onblur="validateField(this)">
                        <option value="" disabled selected>Selecione</option>
                        <option value="diario">Diário</option>
                        <option value="semanal">Semanal</option>
                        <option value="quinzenal">Quinzenal</option>
                        <option value="mensal">Mensal</option>
                      </select>
                    </div>
   
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium">Print da Tela de Perfil dos Apps</label>
                            <input type="file" name="print_perfil_app" id="print_perfil_app" onchange="carregarImgPrintPerfil(); validateField(this)" accept=".jpg,.jpeg,.png,.pdf,.heic,.webp,.avif" class="form-input w-full uber-obrigatorio" required>
                        </div>
                        <div class="w-20 h-20 border border-gray-300 rounded overflow-hidden bg-white flex items-center justify-center">
                            <img src="https://placehold.co/80x80/cccccc/333333?text=Perfil" id="target-print-perfil-app" class="object-cover w-full h-full" onerror="this.src='https://placehold.co/80x80/cccccc/333333?text=Perfil'">
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium">Print da Tela de Veículos dos Apps</label>
                            <input type="file" name="print_veiculo_app" id="print_veiculo_app" onchange="carregarImgPrintVeiculo(); validateField(this)" accept=".jpg,.jpeg,.png,.pdf,.heic,.webp,.avif" class="form-input w-full uber-obrigatorio" required>
                        </div>
                        <div class="w-20 h-20 border border-gray-300 rounded overflow-hidden bg-white flex items-center justify-center">
                            <img src="https://placehold.co/80x80/cccccc/333333?text=Veículo" id="target-print-veiculo-app" class="object-cover w-full h-full" onerror="this.src='https://placehold.co/80x80/cccccc/333333?text=Veículo'">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium">Print dos Ganhos no App (Semana Atual)</label>
                            <input type="file" name="print_ganhos_hoje" id="print_ganhos_hoje" onchange="carregarImgPrintGanhosHoje(); validateField(this)" accept=".jpg,.jpeg,.png,.pdf,.heic,.webp,.avif" class="form-input w-full uber-obrigatorio" required>
                        </div>
                        <div class="w-20 h-20 border border-gray-300 rounded overflow-hidden bg-white flex items-center justify-center">
                            <img src="https://placehold.co/80x80/cccccc/333333?text=GanhosSemana" id="target-print-ganhos-hoje" class="object-cover w-full h-full" onerror="this.src='https://placehold.co/80x80/cccccc/333333?text=GanhosSemana'">
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium">Print dos Ganhos nos Apps (Últimos 30 dias)</label>
                            <input type="file" name="print_ganhos_30dias" id="print_ganhos_30dias" onchange="carregarImgPrintGanhos30Dias(); validateField(this)" accept=".jpg,.jpeg,.png,.pdf,.heic,.webp,.avif" class="form-input w-full uber-obrigatorio" required>
                        </div>
                        <div class="w-20 h-20 border border-gray-300 rounded overflow-hidden bg-white flex items-center justify-center">
                            <img src="https://placehold.co/80x80/cccccc/333333?text=Ganhos30D" id="target-print-ganhos-30dias" class="object-cover w-full h-full" onerror="this.src='https://placehold.co/80x80/cccccc/333333?text=Ganhos30D'">
                        </div>
                    </div>
                </div>
                
            </div>

            <!-- Novos Campos para Autônomo -->
            <div id="autonomo-campos" class="hidden mt-4 border border-blue-400 p-4 rounded bg-blue-100 text-black">
                <p class="text-sm font-semibold mb-4">Preencha os campos abaixo se você for autônomo:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Função Exercida</label>
                        <input type="text" name="funcao_autonomo" id="funcao_autonomo" class="form-input w-full autonomo-obrigatorio" required onblur="validateField(this)">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Nome da Empresa (se houver)</label>
                        <input type="text" name="empresa_autonomo" id="empresa_autonomo" class="form-input w-full autonomo-obrigatorio" onblur="validateField(this)">
                    </div>
                </div>
                <div class="flex items-start gap-4 mt-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium">Extrato dos Últimos 90 Dias</label>
                        <input type="file" name="extrato_90dias" id="extrato_90dias" onchange="carregarImgExtrato90Dias(); validateField(this)" accept=".jpg,.jpeg,.png,.pdf,.heic,.webp,.avif" class="form-input w-full autonomo-obrigatorio" required>
                    </div>
                    <div class="w-20 h-20 border border-gray-300 rounded overflow-hidden bg-white flex items-center justify-center">
                        <img src="https://placehold.co/80x80/cccccc/333333?text=Extrato" id="target-extrato-90dias" class="object-cover w-full h-full" onerror="this.src='https://placehold.co/80x80/cccccc/333333?text=Extrato'">
                    </div>
                </div>

                <div id="campo-comprovante-extra-autonomo" class="hidden mt-4">
                  <div class="flex items-start gap-4">
                      <div class="flex-1">
                          <label class="block text-sm font-medium">Outro Comprovante (opcional)</label>
                          <input type="file" name="comprovante_extra_autonomo" id="comprovante_extra_autonomo" onchange="carregarImgComprovanteExtraAutonomo(); validateField(this)" accept=".jpg,.jpeg,.png,.pdf,.heic,.webp,.avif" class="form-input w-full">
                      </div>
                      <div class="w-20 h-20 border border-gray-300 rounded overflow-hidden bg-white flex items-center justify-center">
                          <img src="https://placehold.co/80x80/cccccc/333333?text=Comprovante" id="target-comprovante-extra" class="object-cover w-full h-full" onerror="this.src='https://placehold.co/80x80/cccccc/333333?text=Comprovante'">
                      </div>
                  </div>
                </div>

                <button type="button" id="btn-mostrar-comprovante-extra-autonomo" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                  + Comprovante
                </button>
            </div>

            <!-- Novos Campos para Assalariado -->
            <div id="assalariado-campos" class="hidden mt-4 border border-green-400 p-4 rounded bg-green-100 text-black">
                <p class="text-sm font-semibold mb-4">Preencha os campos abaixo se você for assalariado:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Função Exercida</label>
                        <input type="text" name="funcao_assalariado" id="funcao_assalariado" class="form-input w-full assalariado-obrigatorio" required onblur="validateField(this)">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Nome da Empresa</label>
                        <input type="text" name="empresa_assalariado" id="empresa_assalariado" class="form-input w-full assalariado-obrigatorio" required onblur="validateField(this)">
                    </div>
                </div>
                <div class="flex items-start gap-4 mt-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium">Contracheque</label>
                        <input type="file" name="contracheque" id="contracheque" onchange="carregarImgContracheque(); validateField(this)" accept=".jpg,.jpeg,.png,.pdf,.heic,.webp,.avif" class="form-input w-full assalariado-obrigatorio" required>
                    </div>
                    <div class="w-20 h-20 border border-gray-300 rounded overflow-hidden bg-white flex items-center justify-center">
                        <img src="https://placehold.co/80x80/cccccc/333333?text=Contracheque" id="target-contracheque" class="object-cover w-full h-full" onerror="this.src='https://placehold.co/80x80/cccccc/333333?text=Contracheque'">
                    </div>
                </div>

                <div id="campo-comprovante-extra-assalariado" class="hidden mt-4">
                  <div class="flex items-start gap-4">
                      <div class="flex-1">
                          <label class="block text-sm font-medium">Outro Comprovante (opcional)</label>
                          <input type="file" name="comprovante_extra_assalariado" id="comprovante_extra_assalariado" onchange="carregarImgComprovanteExtraAssalariado(); validateField(this)" accept=".jpg,.jpeg,.png,.pdf,.heic,.webp,.avif" class="form-input w-full">
                      </div>
                      <div class="w-20 h-20 border border-gray-300 rounded overflow-hidden bg-white flex items-center justify-center">
                          <img src="https://placehold.co/80x80/cccccc/333333?text=Comprovante" id="target-comprovante-extra" class="object-cover w-full h-full" onerror="this.src='https://placehold.co/80x80/cccccc/333333?text=Comprovante'">
                      </div>
                  </div>
                </div>

                <button type="button" id="btn-mostrar-comprovante-extra-assalariado" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                  + Comprovante
                </button>
            </div>

            <div class="pt-4 flex justify-between">
                <button type="button" class="btn-primary" onclick="prevStep()">Voltar</button>
                <button type="button" class="btn-primary" onclick="nextStep()">Próximo</button>
            </div>
        </div>

                <div class="form-step hidden" id="step-6">
                    <h2 class="text-xl font-bold mb-4">6. Finalização</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-white">Valor de empréstimo</label>
                            <input type="text" name="valor_desejado" id="valor_desejado" class="form-input w-full" placeholder="R$" required onblur="validateField(this)">
                            <div class="text-md text-gray-300 mt-4 text-left">
                              <p>*Sujeito a análise de crédito</p>
                            </div>
                        </div>

                       
                        <!-- <div>
                            <label class="block text-sm font-medium text-white">Valor máximo da parcela</label>
                            <input type="text" name="parcela_desejada" id="parcela_desejada" class="form-input w-full" placeholder="R$" required onblur="validateField(this)">
                        </div> -->
                    </div>

                    <div class="pt-4 flex justify-between">
                        <button type="button" class="btn-primary" onclick="prevStep()">Voltar</button>
                        <button type="submit" class="btn-green" id="submitBtn">Finalizar Cadastro</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>

    let stream; 
    let currentStep = 1;
    const totalSteps = 6;

    // Função genérica para manipular o carregamento e pré-visualização de imagens, incluindo HEIC e PDF
async function handleImageUpload(inputId, imgDisplayId, defaultTextPlaceholder = 'Arquivo') {
    const input = document.getElementById(inputId);
    const img = document.getElementById(imgDisplayId);
    const file = input.files[0];

    // Define o placeholder padrão baseado no texto fornecido ou em um padrão
    let placeholderSrc;
    if (imgDisplayId === 'foto') {
        placeholderSrc = "https://placehold.co/96x96/cccccc/333333?text=Foto";
    } else {
        placeholderSrc = `https://placehold.co/80x80/cccccc/333333?text=${defaultTextPlaceholder}`;
    }

    if (!file) {
        img.src = placeholderSrc;
        img.alt = `Placeholder de ${defaultTextPlaceholder}`;
        // Limpa o data attribute se nenhum arquivo for selecionado
        input.removeAttribute('data-converted-file');
        // Chama a validação se sua lógica de validação `validateField` depender disso
        if (typeof validateField === 'function') {
            validateField(input);
        }
        return;
    }

    // 1. Trata arquivos PDF
    if (file.type === 'application/pdf') {
        img.src = `https://placehold.co/80x80/cccccc/333333?text=PDF`;
        img.alt = 'Documento PDF selecionado';
        input.removeAttribute('data-converted-file'); // Não é uma imagem convertida
        if (typeof validateField === 'function') {
            validateField(input);
        }
        // Swal.fire({
        //     icon: 'info',
        //     title: 'Arquivo PDF selecionado',
        //     text: 'Pré-visualizações de PDF não são suportadas diretamente no navegador. O arquivo será enviado.',
        //     confirmButtonText: 'Ok'
        // });
        return;
    }

    // 2. Trata arquivos HEIC/HEIF (requer a biblioteca heic2any.min.js)
    if (file.type === 'image/heic' || file.type === 'image/heif') {
        try {
            // Converte HEIC para JPEG (formato amplamente suportado)
            const convertedBlob = await heic2any({
                blob: file,
                toType: 'image/jpeg',
                quality: 0.8
            });

            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                img.alt = 'Pré-visualização HEIC Convertida';
                // Armazena a Data URL do arquivo convertido. Isso será crucial para o FormData.
                input.setAttribute('data-converted-file', e.target.result);
                if (typeof validateField === 'function') {
                    validateField(input);
                }
            };
            reader.readAsDataURL(convertedBlob);
            console.log(`Arquivo HEIC/HEIF de ${inputId} convertido e pré-visualizado.`);

        } catch (error) {
            console.error(`Erro ao converter HEIC/HEIF para ${inputId}:`, error);
            img.src = placeholderSrc; // Volta para o placeholder
            img.alt = 'Erro na pré-visualização';
            input.removeAttribute('data-converted-file'); // Remove o atributo em caso de erro
            if (typeof validateField === 'function') {
                validateField(input);
            }
            Swal.fire({
                icon: 'error',
                title: 'Erro de Pré-visualização',
                text: `Não foi possível pré-visualizar o arquivo HEIC/HEIF para "${input.name}". Por favor, tente outro formato ou verifique o arquivo.`,
                confirmButtonText: 'Ok'
            });
        }
        return;
    }

    // 3. Trata outros formatos de imagem (JPG, PNG, WebP, AVIF, etc.)
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            img.alt = `Pré-visualização de ${defaultTextPlaceholder}`;
            input.removeAttribute('data-converted-file'); // Garante que não há um data-converted-file de uma tentativa anterior
            if (typeof validateField === 'function') {
                validateField(input);
            }
        };
        reader.readAsDataURL(file);
        console.log(`Arquivo de imagem padrão de ${inputId} pré-visualizado.`);
    } else {
        // Para qualquer outro tipo de arquivo que você aceitou mas não pode pré-visualizar
        img.src = placeholderSrc;
        img.alt = `Arquivo ${defaultTextPlaceholder} selecionado`;
        input.removeAttribute('data-converted-file');
        if (typeof validateField === 'function') {
            validateField(input);
        }
        // Swal.fire({
        //     icon: 'warning',
        //     title: 'Formato não suportado para pré-visualização',
        //     text: `Este tipo de arquivo (para "${input.name}") pode não ser exibido corretamente na pré-visualização, mas será enviado se for válido.`,
        //     confirmButtonText: 'Ok'
        // });
    }
}

   // --- FUNÇÕES DE MANIPULAÇÃO DE IMAGEM (Refatoradas) ---

function carregarImgComprovanteExtraAutonomo() {
    handleImageUpload('comprovante_extra_autonomo', 'target-comprovante-extra-autonomo', 'Comprovante');
}

function carregarImgComprovanteExtraAssalariado() {
    handleImageUpload('comprovante_extra_assalariado', 'target-comprovante-extra-assalariado', 'Comprovante');
}

function carregarImg() { // Para foto_usuario
    handleImageUpload('foto_usuario', 'foto', 'Foto');
}

function carregarImgComprovanteEndereco() {
    handleImageUpload('comprovante_endereco', 'target-comprovante-endereco', 'Endereço');
}

function carregarImgComprovanteRG() {
    handleImageUpload('comprovante_rg', 'target-comprovante-rg', 'CNH/RG');
}

function carregarImgPrintPerfil() {
    handleImageUpload('print_perfil_app', 'target-print-perfil-app', 'Perfil');
}

function carregarImgPrintVeiculo() {
    handleImageUpload('print_veiculo_app', 'target-print-veiculo-app', 'Veículo');
}

function carregarImgPrintGanhosHoje() {
    handleImageUpload('print_ganhos_hoje', 'target-print-ganhos-hoje', 'GanhosSemana');
}

function carregarImgPrintGanhos30Dias() {
    handleImageUpload('print_ganhos_30dias', 'target-print-ganhos-30dias', 'Ganhos30D');
}

function carregarImgExtrato90Dias() {
    handleImageUpload('extrato_90dias', 'target-extrato-90dias', 'Extrato');
}

function carregarImgContracheque() {
    handleImageUpload('contracheque', 'target-contracheque', 'cheque');
}

    // Função para alternar a visibilidade do campo extra
    document.getElementById('btn-mostrar-comprovante-extra-autonomo').addEventListener('click', function() {
        const campoExtra = document.getElementById('campo-comprovante-extra-autonomo');
        campoExtra.classList.toggle('hidden'); // Alterna a classe 'hidden'
    });

   

    // Função para alternar a visibilidade do campo extra
    document.getElementById('btn-mostrar-comprovante-extra-assalariado').addEventListener('click', function() {
        const campoExtra = document.getElementById('campo-comprovante-extra-assalariado');
        campoExtra.classList.toggle('hidden'); // Alterna a classe 'hidden'
    });


    async function tirarFoto() {
        const cameraPreview = document.getElementById('cameraPreview');
        const photoCanvas = document.getElementById('photoCanvas');
        const photoDisplay = document.getElementById('foto');
        const fotoUsuarioInput = document.getElementById('foto_usuario');

        if (!stream) { // Se a câmera não estiver ativa, solicita acesso
            try {
                // Solicita acesso à câmera do usuário
                stream = await navigator.mediaDevices.getUserMedia({ video: true });
                cameraPreview.srcObject = stream;
                cameraPreview.style.display = 'block'; // Mostra o vídeo da câmera
            } catch (err) {
                console.error("Erro ao acessar a câmera:", err);
                alert("Não foi possível acessar a câmera. Verifique as permissões.");
                return;
            }
        } else { // Se a câmera já estiver ativa, tira a foto
            const context = photoCanvas.getContext('2d');
            photoCanvas.width = cameraPreview.videoWidth;
            photoCanvas.height = cameraPreview.videoHeight;
            context.drawImage(cameraPreview, 0, 0, photoCanvas.width, photoCanvas.height);

            // Converte a imagem do canvas para Base64
            const imageDataUrl = photoCanvas.toDataURL('image/png');
            
            // Exibe a imagem capturada
            photoDisplay.src = imageDataUrl;
            
            // Armazena a imagem Base64 no input hidden
            fotoUsuarioInput.value = imageDataUrl;

            // Opcional: Para a câmera após tirar a foto
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
                cameraPreview.srcObject = null;
                cameraPreview.style.display = 'none'; // Esconde o vídeo da câmera
            }

            // Chama a função de validação, se houver
            validateField(fotoUsuarioInput);
        }
    }

 

    // --- FUNÇÕES DE MÁSCARAS ---
    function setupMasks() {
        $('#cpf').mask('000.000.000-00');
        $('#telefone').mask('(00) 00000-0000');
        $('#cep').mask('00000-000');
        $('#data_nasc').mask('00/00/0000');
        $('#referencia_contato').mask('(00) 00000-0000');
        $('#indicacao_contato').mask('(00) 00000-0000');
        // Usando um ARRAY de máscaras para a placa (Mercosul e Antiga)
        $('#placa_veiculo').mask(['AAA0A00', 'AAA0000'], {
            translation: {
                'A': { pattern: /[A-Za-z]/ },
                '0': { pattern: /[0-9]/ }
            }
        });
        $('#valor_desejado').mask('000.000.000.000.000,00', {reverse: true});
        // $('#parcela_desejada').mask('000.000.000.000.000,00', {reverse: true});
        $('#valor_aluguel').mask('000.000.000.000.000,00', {reverse: true});
    }

    // Inicializa as máscaras quando o documento estiver pronto
    $(document).ready(function() {
        setupMasks();
        showStep(currentStep);
        handleRamoChange();
        handleStatusVeiculoChange();
    });

    // --- FUNÇÕES DE VALIDAÇÃO ---
    function validateEmail(email) {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    function checkCpfDuplicado(cpf, inputElement) {
    const errorDiv = document.getElementById('cpf-error-backend');
    const nextButton = document.querySelector('#step-1 .btn-primary'); 

    errorDiv.innerText = 'Verificando...';
    nextButton.disabled = true;

    // Ajuste a chamada fetch para enviar JSON
    fetch('/painel/paginas/check_cpf.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json' // Define o tipo de conteúdo como JSON
        },
        body: JSON.stringify({ cpf: cpf }) // Converte os dados para uma string JSON
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            errorDiv.innerText = data.message;
            markInvalid(inputElement, '');
        } else {
            errorDiv.innerText = '';
            markValid(inputElement);
        }
        
        const isCpfValid = !inputElement.classList.contains('is-invalid');
        if (isCpfValid && data.success) {
            nextButton.disabled = false;
        } else {
            nextButton.disabled = true;
        }
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
        errorDiv.innerText = 'Erro ao verificar o CPF. Tente novamente.';
        nextButton.disabled = true;
    });
}

    function validateCPF(input) {
      const cpf = input.value.replace(/\D/g, '');
      const errorBackendDiv = document.getElementById('cpf-error-backend');
      const nextButton = document.querySelector('#step-1 .btn-primary');

      // 1. Validação de formato e dígitos (local)
      if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
          markInvalid(input, 'CPF inválido.');
          errorBackendDiv.innerText = '';
          nextButton.disabled = true; // Desabilita o botão
          return false;
      }

      let sum = 0;
      let remainder;

      // Lógica do primeiro dígito verificador
      for (let i = 1; i <= 9; i++) {
          sum += parseInt(cpf.substring(i - 1, i)) * (11 - i);
      }
      remainder = (sum * 10) % 11;

      if ((remainder === 10) || (remainder === 11)) {
          remainder = 0;
      }
      if (remainder !== parseInt(cpf.substring(9, 10))) {
          markInvalid(input, 'CPF inválido.');
          errorBackendDiv.innerText = '';
          nextButton.disabled = true; // Desabilita o botão
          return false;
      }

      // Lógica do segundo dígito verificador
      sum = 0;
      for (let i = 1; i <= 10; i++) {
          sum += parseInt(cpf.substring(i - 1, i)) * (12 - i);
      }
      remainder = (sum * 10) % 11;

      if ((remainder === 10) || (remainder === 11)) {
          remainder = 0;
      }
      if (remainder !== parseInt(cpf.substring(10, 11))) {
          markInvalid(input, 'CPF inválido.');
          errorBackendDiv.innerText = '';
          nextButton.disabled = true; // Desabilita o botão
          return false;
      }
      
      // Se a validação local passar, chame a verificação no banco de dados.
      markValid(input);
      checkCpfDuplicado(cpf, input);
      
      return true;
    }

    // function validateCPF(input) {
    //     const cpf = input.value.replace(/\D/g, '');
    //     if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
    //         markInvalid(input, 'CPF inválido.');
    //         return false;
    //     }

    //     let sum = 0;
    //     let remainder;

    //     for (let i = 1; i <= 9; i++) {
    //         sum = sum + parseInt(cpf.substring(i - 1, i)) * (11 - i);
    //     }
    //     remainder = (sum * 10) % 11;

    //     if ((remainder === 10) || (remainder === 11)) {
    //         remainder = 0;
    //     }
    //     if (remainder !== parseInt(cpf.substring(9, 10))) {
    //         markInvalid(input, 'CPF inválido.');
    //         return false;
    //     }

    //     sum = 0;
    //     for (let i = 1; i <= 10; i++) {
    //         sum = sum + parseInt(cpf.substring(i - 1, i)) * (12 - i);
    //     }
    //     remainder = (sum * 10) % 11;

    //     if ((remainder === 10) || (remainder === 11)) {
    //         remainder = 0;
    //     }
    //     if (remainder !== parseInt(cpf.substring(10, 11))) {
    //         markInvalid(input, 'CPF inválido.');
    //         return false;
    //     }

    //     markValid(input);
    //     return true;
    // }

    // NOVA FUNÇÃO DE VALIDAÇÃO DE DATA DE NASCIMENTO
    function validateDataNascimento(input) {
        const dateString = input.value;
        const regex = /^\d{2}\/\d{2}\/\d{4}$/;

        if (!regex.test(dateString)) {
            markInvalid(input, 'Data inválida. Use o formato DD/MM/AAAA.');
            return false;
        }

        const parts = dateString.split('/');
        const day = parseInt(parts[0], 10);
        const month = parseInt(parts[1], 10);
        const year = parseInt(parts[2], 10);

        // Validações básicas de intervalo
        if (day < 1 || day > 31 || month < 1 || month > 12 || year < 1900 || year > new Date().getFullYear()) {
            markInvalid(input, 'Data inválida. Verifique dia, mês ou ano.');
            return false;
        }

        const date = new Date(year, month - 1, day); // Mês é 0-indexed (0=Jan, 11=Dez)
        // Verifica se a data é realmente uma data válida (ex: 31/02/2023 não é válida)
        if (date.getFullYear() !== year || date.getMonth() + 1 !== month || date.getDate() !== day) {
            markInvalid(input, 'Data inválida. Verifique o dia/mês/ano.');
            return false;
        }

        // Validação de idade mínima (ex: mínimo 18 anos)
        const today = new Date();
        let age = today.getFullYear() - year;
        const m = today.getMonth() - (month - 1); // Mês atual - (mês de nasc - 1)
        if (m < 0 || (m === 0 && today.getDate() < day)) {
            age--;
        }
        if (age < 18) { // Defina a idade mínima necessária
            markInvalid(input, 'Você deve ter pelo menos 18 anos.');
            return false;
        }

        markValid(input);
        return true;
    }

    function validatePassword(input) {
        const senha = $('#senha').val();
        const confSenha = $('#conf_senha').val();

        if (senha === '' || confSenha === '') {
            markInvalid(input, 'Por favor, preencha ambos os campos de senha.');
            return false;
        }
        if (senha.length < 6) {
            markInvalid($('#senha')[0], 'A senha deve ter no mínimo 6 caracteres.');
            return false;
        }
        if (senha !== confSenha) {
            markInvalid($('#conf_senha')[0], 'As senhas não coincidem.');
            return false;
        }
        markValid($('#senha')[0]);
        markValid($('#conf_senha')[0]);
        return true;
    }

    function validatePlaca(input) {
        const placa = input.value.toUpperCase();
        const re = /^[A-Z]{3}\d{4}$|^[A-Z]{3}\d[A-Z]\d{2}$/;
        if (!re.test(placa)) {
            markInvalid(input, 'Formato de placa inválido (ex: ABC1234 ou ABC1A23).');
            return false;
        }
        markValid(input);
        return true;
    }

    // Função para marcar um campo como inválido e exibir mensagem de erro
    function markInvalid(input, message = '') {
        input.classList.add('invalid');
        input.style.borderColor = '#fc8181';

        let errorMessageElement = input.nextElementSibling;
        // Verifica se o próximo irmão é um elemento de mensagem de erro, ou cria um
        if (!errorMessageElement || !errorMessageElement.classList.contains('error-message')) {
            errorMessageElement = document.createElement('p');
            errorMessageElement.classList.add('error-message', 'text-red-400', 'text-sm', 'mt-1');
            input.parentNode.insertBefore(errorMessageElement, input.nextSibling);
        }
        errorMessageElement.textContent = message;
        errorMessageElement.classList.remove('hidden');
    }

    // Função para marcar um campo como válido e ocultar mensagem de erro
    function markValid(input) {
        input.classList.remove('invalid');
        input.style.borderColor = '#4a5568'; // Borda padrão

        let errorMessageElement = input.nextElementSibling;
        if (errorMessageElement && errorMessageElement.classList.contains('error-message')) {
            errorMessageElement.classList.add('hidden');
            errorMessageElement.textContent = '';
        }
    }

  // Validação genérica de campo
  function validateField(input) {
    console.log(`Validando campo: ${input.id || input.name}, Valor: "${input.value}"`);
    console.log("Campo validado:", input.id, input.value ? "Com valor" : "Sem valor");
    const type = input.type;
    const id = input.id;
    let isValid = true;
    let errorMessage = '';

    if (input.hasAttribute('required') && input.value.trim() === '') {
      isValid = false;
      errorMessage = 'Este campo é obrigatório.';
    } else {
      if (id === 'nome' || id === 'referencia_nome' || id === 'indicacao') {
        const nomeCompleto = input.value.trim();
        // Verifica se o nome completo tem pelo menos 2 palavras (Nome + Sobrenome)
        // A expressão /\s+/ considera um ou mais espaços como separador
        if (nomeCompleto.split(/\s+/).length < 2) {
            isValid = false;
            errorMessage = 'Por favor, insira o nome completo (nome e pelo menos um sobrenome).';
        }
      } 
        
        // VALIDAÇÕES ANTIGAS (Usando 'else if' para evitar conflitos)
        else if (id === 'email') {
            if (!validateEmail(input.value)) {
                isValid = false;
                errorMessage = 'Email inválido.';
            }
        } else if (id === 'cpf') {
            isValid = validateCPF(input);
            if (!isValid) errorMessage = input.getAttribute('data-invalid');
        } else if (id === 'data_nasc') { // VALIDAÇÃO DA DATA DE NASCIMENTO
            isValid = validateDataNascimento(input);
            // A função validateDataNascimento já marca como inválido e define a mensagem
            if (!isValid) {
                // Tenta obter a mensagem da própria função de validação de data
                let errorMessageElement = input.nextElementSibling;
                if (errorMessageElement && errorMessageElement.classList.contains('error-message')) {
                    errorMessage = errorMessageElement.textContent;
                } else {
                    errorMessage = 'Data de Nascimento inválida.';
                }
            }
        } else if (id === 'telefone' || id === 'referencia_contato' || id === 'indicacao_contato') {
            if (input.value.replace(/\D/g, '').length < 11) {
                isValid = false;
                errorMessage = 'Telefone inválido (mínimo 11 dígitos incluindo DDD).';
            }
        } else if (id === 'senha' || id === 'conf_senha') {
            isValid = validatePassword(input);
            if (!isValid) errorMessage = input.getAttribute('data-invalid');
        } else if (id === 'placa_veiculo') {
            isValid = validatePlaca(input);
            if (!isValid) errorMessage = input.getAttribute('data-invalid');
        } else if (type === 'file') {
            if (input.hasAttribute('required') && input.files.length === 0) {
                isValid = false;
                errorMessage = 'Por favor, anexe o arquivo.';
            }
        } else if (type === 'number') {
            if (input.hasAttribute('required') && input.value.trim() === '') {
                isValid = false;
                errorMessage = 'Este campo é obrigatório.';
            } else if (input.min && parseFloat(input.value) < parseFloat(input.min)) {
                isValid = false;
                errorMessage = `O valor mínimo é ${input.min}.`;
            }
        } else if (input.tagName === 'SELECT' && input.value === '') {
            isValid = false;
            errorMessage = 'Por favor, selecione uma opção.';
        }
    }

    // 3. Aplica o resultado da validação
    if (isValid) {
        // Assume que 'markValid' e 'markInvalid' são funções que manipulam classes CSS e mensagens de erro
        markValid(input);
    } else {
        markInvalid(input, errorMessage);
    }
    console.log(`Campo ${input.id || input.name} é válido: ${isValid}`);
    return isValid;
    }

    // Validação da etapa atual antes de prosseguir
    function validateCurrentStep() {
        console.log(`--- Validando Etapa ${currentStep} ---`);
        const currentStepElement = document.getElementById(`step-${currentStep}`);
        // Seleciona todos os campos que podem ser obrigatórios nesta etapa
        const inputs = currentStepElement.querySelectorAll('[required], .uber-obrigatorio, .autonomo-obrigatorio, .assalariado-obrigatorio');
        let allValid = true;
        let firstInvalidField = null;
        let invalidMessages = [];

        const ramoSelect = document.getElementById('ramo');
        const selectedRamo = ramoSelect ? ramoSelect.value : '';

        inputs.forEach(input => {
            // Lógica condicional para campos obrigatórios baseada no ramo
            const isUberField = input.classList.contains('uber-obrigatorio');
            const isAutonomoField = input.classList.contains('autonomo-obrigatorio');
            const isAssalariadoField = input.classList.contains('assalariado-obrigatorio');

            let shouldValidate = true;

            if (isUberField && selectedRamo !== 'uber') {
                shouldValidate = false;
            } else if (isAutonomoField && selectedRamo !== 'autonomo') {
                shouldValidate = false;
            } else if (isAssalariadoField && selectedRamo !== 'assalariado') {
                shouldValidate = false;
            }

            if (!shouldValidate) {
                markValid(input);
                console.log(`Pulando validação para (ramo diferente): ${input.id || input.name}`);
                return;
            }

            const isValidField = validateField(input);
            if (!isValidField) {
                allValid = false;
                const message = input.getAttribute('data-invalid') || 'Campo inválido ou vazio.';
                let fieldName = input.previousElementSibling ? input.previousElementSibling.textContent.replace(':', '').trim() : input.placeholder || input.id || input.name;
                invalidMessages.push(`${fieldName}: ${message}`);
                if (!firstInvalidField) {
                    firstInvalidField = input;
                }
            }
        });

        // Validação específica para o Step 1 (duplicidade de nome e telefone)
        if (currentStep === 1) {
            const nomeInput = document.getElementById('nome');
            const telefoneInput = document.getElementById('telefone');
        }

        // Validação específica para o Step 2 (confirmação de senha)
        if (currentStep === 2) {
            const senha = document.getElementById('senha');
            const confSenha = document.getElementById('conf_senha');
            if (senha.value !== confSenha.value) {
                markInvalid(confSenha, 'As senhas não coincidem.');
                invalidMessages.push('Confirmação de Senha: As senhas não coincidem.');
                allValid = false;
                if (!firstInvalidField) firstInvalidField = confSenha;
            } else {
                markValid(confSenha);
            }
        }


        if (!allValid) {
            let errorMessageHTML = invalidMessages.map(msg => `<li>${msg}</li>`).join('');
            Swal.fire({
                icon: 'error',
                title: 'Erro de Validação!',
                html: `Por favor, corrija os seguintes campos antes de prosseguir:<ul class="text-left mt-2">${errorMessageHTML}</ul>`,
                confirmButtonText: 'Ok',
                customClass: {
                    popup: 'swal2-responsive-popup'
                }
            });
            if (firstInvalidField) {
                firstInvalidField.focus(); // Foca no primeiro campo inválido
            }
            console.log("Validação falhou para a etapa atual. Campos inválidos:", invalidMessages);
            return false;
        }
        console.log("Validação bem-sucedida para a etapa atual.");
        return true;
    }


    // --- NAVEGAÇÃO ENTRE ETAPAS ---
    function showStep(stepNumber) {
        document.querySelectorAll('.form-step').forEach((step, index) => {
            if (index + 1 === stepNumber) {
                step.classList.remove('hidden');
                // Animação GSAP para mostrar a etapa
                gsap.fromTo(step, { opacity: 0, y: 50 }, { opacity: 1, y: 0, duration: 0.5, ease: "power2.out" });
            } else {
                step.classList.add('hidden');
            }
        });
        // Garante o estado correto dos campos específicos do ramo ao mostrar a etapa 5
        if (stepNumber === 5) {
            handleRamoChange();
            handleStatusVeiculoChange();
        }
    }

    function nextStep() {
        if (validateCurrentStep()) {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    }

    
    // --- FUNÇÃO DE PESQUISA DE CEP ---
    function pesquisacep(valor) {
        var cep = valor.replace(/\D/g, '');

        if (cep != "") {
            var validacep = /^[0-9]{8}$/;

            if (validacep.test(cep)) {
                document.getElementById('endereco').value = "...";
                document.getElementById('bairro').value = "...";
                document.getElementById('cidade').value = "...";
                document.getElementById('estado').value = "...";

                var script = document.createElement('script');
                script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=callbackMeuCep';
                document.head.appendChild(script);

            } else {
                markInvalid(document.getElementById('cep'), 'Formato de CEP inválido.');
                limpa_formulário_cep();
            }
        } else {
            markValid(document.getElementById('cep'));
            limpa_formulário_cep();
        }
    }

    function limpa_formulário_cep() {
        document.getElementById('endereco').value = "";
        document.getElementById('bairro').value = "";
        document.getElementById('cidade').value = "";
        document.getElementById('estado').value = "";
        markValid(document.getElementById('endereco'));
        markValid(document.getElementById('bairro'));
        markValid(document.getElementById('cidade'));
        markValid(document.getElementById('estado'));
    }

    function callbackMeuCep(conteudo) {
        if (!("erro" in conteudo)) {
            document.getElementById('endereco').value = (conteudo.logradouro);
            document.getElementById('bairro').value = (conteudo.bairro);
            document.getElementById('cidade').value = (conteudo.localidade);
            document.getElementById('estado').value = (conteudo.uf);
            markValid(document.getElementById('cep'));
            markValid(document.getElementById('endereco'));
            markValid(document.getElementById('bairro'));
            markValid(document.getElementById('cidade'));
            markValid(document.getElementById('estado'));
        } else {
            markInvalid(document.getElementById('cep'), 'CEP não encontrado.');
            limpa_formulário_cep();
        }
    }

    // --- LÓGICA CONDICIONAL PARA CAMPOS DE RAMO DE ATUAÇÃO ---
    function handleRamoChange() {
        const ramoSelect = document.getElementById('ramo');
        const selectedRamo = ramoSelect.value;

        const veiculoCampos = document.getElementById('veiculo-campos');
        const autonomoCampos = document.getElementById('autonomo-campos');
        const assalariadoCampos = document.getElementById('assalariado-campos');

        // Seleciona todos os campos que podem ser condicionalmente obrigatórios
        const allConditionalInputs = document.querySelectorAll('.uber-obrigatorio, .autonomo-obrigatorio, .assalariado-obrigatorio');

        // Esconde todos os blocos e remove 'required' de todos os campos condicionais
        veiculoCampos.classList.add('hidden');
        autonomoCampos.classList.add('hidden');
        assalariadoCampos.classList.add('hidden');

        allConditionalInputs.forEach(input => {
            input.removeAttribute('required');
            input.value = ''; // Limpa o valor
            if (input.type === 'file') {
                // Reseta as pré-visualizações de imagem para os placeholders
                if (input.id === 'print_perfil_app') document.getElementById('target-print-perfil-app').src = "https://placehold.co/80x80/cccccc/333333?text=Perfil";
                if (input.id === 'print_veiculo_app') document.getElementById('target-print-veiculo-app').src = "https://placehold.co/80x80/cccccc/333333?text=Veículo";
                if (input.id === 'print_ganhos_hoje') document.getElementById('target-print-ganhos-hoje').src = "https://placehold.co/80x80/cccccc/333333?text=GanhosSemana";
                if (input.id === 'print_ganhos_30dias') document.getElementById('target-print-ganhos-30dias').src = "https://placehold.co/80x80/cccccc/333333?text=Ganhos30D";
                if (input.id === 'extrato_90dias') document.getElementById('target-extrato-90dias').src = "https://placehold.co/80x80/cccccc/333333?text=Extrato";
                if (input.id === 'contracheque') document.getElementById('target-contracheque').src = "https://placehold.co/80x80/cccccc/333333?text=Contracheque";
            }
            markValid(input); // Marca como válido
        });

        // Mostra o bloco correto e define 'required' para seus campos
        if (selectedRamo === 'uber') {
            veiculoCampos.classList.remove('hidden');
            veiculoCampos.querySelectorAll('.uber-obrigatorio').forEach(input => {
                input.setAttribute('required', 'required');
                validateField(input);
            });
            handleStatusVeiculoChange(); // Garante que o campo de aluguel esteja correto
        } else if (selectedRamo === 'autonomo') {
            autonomoCampos.classList.remove('hidden');
            autonomoCampos.querySelectorAll('.autonomo-obrigatorio').forEach(input => {
                input.setAttribute('required', 'required');
                validateField(input);
            });
        } else if (selectedRamo === 'assalariado') {
            assalariadoCampos.classList.remove('hidden');
            assalariadoCampos.querySelectorAll('.assalariado-obrigatorio').forEach(input => {
                input.setAttribute('required', 'required');
                validateField(input);
            });
        }
    }

// --- LÓGICA CONDICIONAL PARA CAMPOS DE VEÍCULO ---
function handleStatusVeiculoChange() {
    const statusVeiculoSelect = document.getElementById('status_veiculo');
    const valorAluguelInput = document.getElementById('valor_aluguel');
    const frequenciaAluguelInput = document.getElementById('frequencia_aluguel');

    const valorAluguelDiv = valorAluguelInput.closest('div');
    const frequenciaAluguelDiv = frequenciaAluguelInput.closest('div');

    if (statusVeiculoSelect && statusVeiculoSelect.value === 'alugado') {
        // Mostra ambos os campos
        valorAluguelDiv.style.display = 'block';
        frequenciaAluguelDiv.style.display = 'block';

        // Torna ambos os campos obrigatórios
        valorAluguelInput.setAttribute('required', 'required');
        frequenciaAluguelInput.setAttribute('required', 'required');
    } else {
        // Esconde ambos os campos
        valorAluguelDiv.style.display = 'none';
        frequenciaAluguelDiv.style.display = 'none';

        // Remove a obrigatoriedade e limpa os valores
        valorAluguelInput.removeAttribute('required');
        valorAluguelInput.value = '';
        markValid(valorAluguelInput);

        frequenciaAluguelInput.removeAttribute('required');
        frequenciaAluguelInput.value = '';
        markValid(frequenciaAluguelInput);
    }
}

    // --- TOGGLE VISIBILIDADE DA SENHA ---
    function togglePasswordVisibility(fieldId, buttonId) {
        const passwordField = document.getElementById(fieldId);
        const toggleButton = document.getElementById(buttonId);

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500"><path stroke-linecap="round" stroke-linejoin="round" d="M3.988 5.862a1.01 1.01 0 0 0 .6-.967V4.774A.75.75 0 0 1 5.373 4h13.254a.75.75 0 0 1 .715.774v.121a1.01 1.01 0 0 0 .6.967l1.353.451c.328.109.52.433.52.779V9.5a2.25 2.25 0 0 1-2.25 2.25h-5.467A.75.75 0 0 0 12 12.75h-.008a.75.75 0 0 0-.75.75v.375c0 .414-.336.75-.75.75H10.5a.75.75 0 0 1-.75-.75v-.375a.75.75 0 0 0-.75-.75h-.008a.75.75 0 0 0-.75.75v.375c0 .414-.336.75-.75.75H6.75A2.25 2.25 0 0 1 4.5 9.5V7.162c0-.346.192-.67.52-.779l1.353-.451Zm-.611 11.233a1.01 1.01 0 0 0 .6-.967V17.274A.75.75 0 0 1 5.373 16h13.254a.75.75 0 0 1 .715.774v.121a1.01 1.01 0 0 0 .6.967l1.353.451c.328.109.52.433.52.779V21.5a2.25 2.25 0 0 1-2.25 2.25H4.5A2.25 2.25 0 0 1 2.25 21.5v-2.338c0-.346.192-.67.52-.779l1.353-.451Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>`;
        } else {
            passwordField.type = 'password';
            toggleButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>`;
        }
    }

// --- SUBMISSÃO DO FORMULÁRIO (AJAX) ---
$('#form').submit(function(event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    if (!validateCurrentStep()) {
        return; // Se a validação da última etapa falhar, não prossegue.
    }

    // Se tudo validado, prepare os dados do formulário
    var formData = new FormData(this);

    $.ajax({
        url: '../painel/paginas/clientes/salvar.php', // O arquivo PHP que processa o cadastro
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json', // <--- ESSA LINHA É FUNDAMENTAL! Diz ao jQuery para esperar JSON.

        // --- ADICIONE ESTES DOIS BLOCOS ---
        beforeSend: function() {
            // Exibe o overlay de loading antes da requisição começar
            $('#loading-overlay').css('display', 'flex');
        },
        complete: function() {
            // Esconde o overlay de loading quando a requisição termina (sucesso ou erro)
            $('#loading-overlay').css('display', 'none');
        },
        
        success: function(response) {
            // REMOVA O BLOCO TRY/CATCH E O JSON.parse(response) AQUI!
            // Com 'dataType: "json"', a variável 'response' JÁ É UM OBJETO JAVASCRIPT.
            if (response.success) { // Agora 'response.success' funcionará diretamente
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: response.message, // 'response.message' funcionará diretamente
                    confirmButtonText: 'Ok'
                }).then(() => {
                    // Redirecionar ou limpar o formulário
                    window.location.href = '/'; // Exemplo de redirecionamento
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro no Cadastro!',
                    text: response.message, // 'response.message' funcionará diretamente
                    confirmButtonText: 'Ok'
                });
            }
        },
        error: function(xhr, status, error) {
            // Este bloco 'error' é importante para depurar problemas de comunicação HTTP
            console.error("Erro AJAX:", xhr.responseText); // xhr.responseText conterá a resposta bruta (HTML, JSON de erro, etc.)
            Swal.fire({
                icon: 'error',
                title: 'Erro de Comunicação!',
                text: 'Não foi possível conectar ao servidor ou houve um erro inesperado. Detalhes: ' + xhr.responseText,
                confirmButtonText: 'Ok'
            });
        }
    });
});
</script>

<div id="loading-overlay" class="loading-overlay">
    <div class="spinner"></div>
    <div class="loading-text">Finalizando cadastro...</div>
</div>

</body>
</html>