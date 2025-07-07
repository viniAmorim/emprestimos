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
  <!-- Favicon -->
  <link rel="icon" href="img/icone.png" type="image/x-icon" />
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- GSAP -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
  <!-- Tailwind CSS -->
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
    .form-input.invalid {
      border-color: red !important; 
      box-shadow: 0 0 0 10px rgba(239, 68, 68, 0.25) !important; 
    }

  </style>
</head>
<body class="font-poppins bg-gradient-to-br from-primary-dark to-primary text-white min-h-screen overflow-x-hidden">
  <div class="flex flex-col md:flex-row h-screen items-center justify-center">
    <!-- Lado Esquerdo com Imagem -->
    <div class="hidden md:block w-full md:w-1/3 h-1/2 md:h-full bg-left flex items-center justify-center pt-20">
      <img src="img/logo2.png" alt="Imagem de Empréstimo" class="object-contain max-h-full max-w-full" />
    </div>

    <div class="w-full md:w-2/3 p-8 text-white flex items-start justify-center overflow-y-auto">
      <form id="form" class="w-full max-w-4xl space-y-4" novalidate>
        <!-- Etapa 1: Dados Pessoais -->
        <div class="form-step" id="step-1">
          <h2 class="text-xl font-bold mb-4">1. Dados Pessoais</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
            <div>
              <label class="block text-sm font-medium text-white">Nome</label>
              <input type="text" id="nome" name="nome"  placeholder="Nome completo" class="form-input w-full" required onblur="validateField(this)">
            </div>
            <div>
              <label class="block text-sm font-medium text-white">Email</label>
              <input type="email" id="email" name="email" placeholder="Email" class="form-input w-full" required onblur="validateField(this)">
            </div>
            <div>
              <label class="block text-sm font-medium text-white">Telefone</label>
              <input type="text" name="telefone" id="telefone" placeholder="Telefone" class="form-input w-full" required onblur="validateField(this)">
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-white">CPF</label>
              <input type="text" name="cpf" id="cpf" placeholder="CPF" class="form-input w-full" required onblur="validarCPF(this)">
            </div>
            <div>
              <label class="block text-sm font-medium text-white">RG</label>
              <input type="text" name="rg" id="rg" placeholder="RG" class="form-input w-full" required onblur="validateField(this)">
            </div>
            <div>
              <label class="block text-sm font-medium text-white">Data de Nascimento</label>
              <input type="date" id="data_nasc" name="data_nasc" class="form-input w-full" required onblur="validateField(this)">
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div>
              <label class="block text-sm font-medium text-white">Chave Pix em sua titularidade</label>
              <input type="text" name="pix" id="pix" placeholder="CPF, telefone ou e-mail" class="form-input w-full" required onblur="validateField(this)">
            </div>
            <!-- Comprovante de Endereço -->
            <div class="flex items-start gap-4">
              <div class="flex-1">
                <label class="block text-sm font-medium text-white">Comprovante de Endereço</label>
                <input type="file" name="comprovante_endereco" id="comprovante_endereco" onchange="carregarImgComprovanteEndereco(); validateField(this)" accept=".jpg,.jpeg,.png" class="form-input w-full" required>
              </div>
              <div class="w-20 h-20 border border-gray-300 rounded overflow-hidden bg-white">
                <img src="painel/images/comprovantes/sem-foto.png" id="target-comprovante-endereco" class="object-cover w-full h-full">
              </div>
            </div>

            <!-- Comprovante RG/CPF -->
            <div class="flex items-start gap-4">
              <div class="flex-1">
                <label class="block text-sm font-medium text-white">Comprovante CNG ou RG</label>
                <input type="file" id="comprovante_rg" name="comprovante_rg" onchange="carregarImgComprovanteRG(); validateField(this)" accept=".jpg,.jpeg,.png" class="form-input w-full" required>
              </div>
              <div class="w-20 h-20 border border-gray-300 rounded overflow-hidden bg-white">
                <img src="painel/images/comprovantes/sem-foto.png" id="target-comprovante-rg" class="object-cover w-full h-full">
              </div>
            </div>
          </div>

          <div class="text-sm text-gray-300 mt-4 text-right">
            <p>O comprovante de endereço deve ser em sua titularidade (água ou energia)</p>
          </div>

          <div class="pt-4 text-right">
            <button type="button" class="btn-primary" onclick="nextStep()">Próximo</button>
          </div>
        </div>

        <!-- Etapa 2: Login e Foto-->
        <div class="form-step hidden" id="step-2">
          <h2 class="text-xl font-bold mb-4">2.Login</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
            <div>
              <label class="block text-sm font-medium text-white">Senha</label>
              <input type="password" name="senha" id="senha" class="form-input w-full" required onblur="validateField(this)">
            </div>
            <div>
              <label class="block text-sm font-medium text-white">Confirmar Senha</label>
              <input type="password" name="conf_senha" id="conf_senha" class="form-input w-full" required onblur="validateField(this)">
            </div>
          </div>

          <!-- Campo de Upload de Foto -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 items-start">
            <div>
              <label class="block text-sm font-medium text-white">Foto do Usuário</label>
              <input type="file" id="foto_usuario" name="foto_usuario" accept=".jpg,.jpeg,.png" onchange="carregarImg(); validateField(this)" class="form-input w-full" required>
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

        <!-- Etapa 3: Endereço Completo -->
        <div class="form-step hidden" id="step-3">
          <h2 class="text-xl font-bold mb-4">3. Endereço</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-white">CEP</label>
              <input type="text" name="cep" id="cep" class="form-input w-full" onblur="pesquisacep(this.value); validateField(this)" required onblur="validateField(this)">
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
              <label class="block text-sm font-medium text-white">Quadra</label>
              <input type="text" name="quadra" id="quadra" class="form-input w-full" required onblur="validateField(this)">
            </div>

            <div class="md:col-span-1">
              <label class="block text-sm font-medium text-white">Lote</label>
              <input type="number" name="lote" id="lote" class="form-input w-full" min="0" required onblur="validateField(this)">
            </div>
            
            <div class="md:col-span-1">
              <label class="block text-sm font-medium text-white">Número</label>
              <input type="number" name="numero" id="numero" class="form-input w-full" min="0" required onblur="validateField(this)">
            </div>
            <div class="md:col-span-3">
              <label class="block text-sm font-medium text-white">Complemento</label>
              <input type="text" name="complemento" id="complemento" class="form-input w-full" required onblur="validateField(this)">
            </div>
          </div>
          <div class="pt-4 flex justify-between">
            <button type="button" class="btn-primary" onclick="prevStep()">Voltar</button>
            <button type="button" class="btn-primary" onclick="nextStep()">Próximo</button>
          </div>
        </div>

        <!-- Etapa 4: Referência e Ramo de Atuação -->
        <div class="form-step hidden" id="step-4">
          <h2 class="text-xl font-bold mb-4">4. Referência e Ramo de Atuação</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-white">Contato de referência</label>
              <input type="text" name="referencia_contato" id="referencia_contato" class="form-input w-full" required onblur="validateField(this)">
            </div>
            <div>
              <label class="block text-sm font-medium text-white">Nome Completo da referência</label>
              <input type="text" id="referencia_nome" name="referencia_nome" class="form-input w-full" required onblur="validateField(this)">
            </div>
            <div>
              <label for="referencia_parentesco" class="block text-sm font-medium text-white">Grau de parentesco</label>
              <select id="referencia_parentesco" name="referencia_parentesco" class="form-input w-full" required onblur="validateField(this)">
              <option value="Pai">Pai</option>
                  <option value="Mãe">Mãe</option>
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

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-white">Indicação</label>
              <input type="text" id="indicacao" name="indicacao" placeholder="Indicado por" class="form-input w-full" required onblur="validateField(this)">
            </div>
            <div>
              <label for="ramo" class="block text-sm font-medium text-white">Ramo de Atuação</label>
              <select id="ramo" name="ramo" class="form-input w-full" required onblur="validateField(this)">
                <option value="" disabled selected>Selecione um ramo</option>
                <option value="comercio">Comércio</option>
                <option value="uber">Motorista/Entregador App</option>
                <option value="servicos">Serviços</option>
                <option value="industria">Indústria</option>
                <option value="tecnologia">Tecnologia da Informação</option>
                <option value="educacao">Educação</option>
                <option value="saude">Saúde</option>
                <option value="construcao">Construção Civil</option>
                <option value="transportes">Transportes</option>
                <option value="logistica">Logística</option>
                <option value="financeiro">Setor Financeiro</option>
                <option value="juridico">Jurídico</option>
                <option value="agropecuaria">Agropecuária</option>
                <option value="marketing">Marketing e Publicidade</option>
                <option value="recursos_humanos">Recursos Humanos</option>
                <option value="gastronomia">Gastronomia</option>
                <option value="beleza">Beleza e Estética</option>
                <option value="seguranca">Segurança</option>
                <option value="turismo">Turismo e Hotelaria</option>
                <option value="freelancer">Freelancer / Autônomo</option>
                <option value="outros">Outros</option>
              </select>
            </div>
          </div>
          <!-- Campos de Veículo (visíveis apenas se for motorista/entregador) -->
          <div id="veiculo-campos" class="hidden mt-4 border border-yellow-400 p-4 rounded bg-yellow-100 text-black">
            <p class="text-sm font-semibold mb-4">Preencha os campos abaixo se você for motorista ou entregador:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium">Modelo do Veículo</label>
                <input type="text" name="modelo_veiculo" id="modelo_veiculo" class="form-input w-full veiculo-obrigatorio" required onblur="validateField(this)">
              </div>
              <div>
                <label class="block text-sm font-medium">Placa</label>
                <input type="text" name="placa_veiculo" id="placa_veiculo" class="form-input w-full veiculo-obrigatorio" maxlength="7" placeholder="ABC1234" required onblur="validateField(this)">
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
              <div>
                <label class="block text-sm font-medium">Status do Veículo</label>
                <select name="status_veiculo" id="status_veiculo" class="form-input w-full veiculo-obrigatorio" required onblur="validateField(this)">
                  <option value="" disabled selected>Selecione</option>
                  <option value="proprio">Próprio</option>
                  <option value="alugado">Alugado</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium">Valor do Aluguel</label>
                <input type="number" name="valor_aluguel"  id="valor_aluguel" class="form-input w-full veiculo-obrigatorio" placeholder="R$" min="0" step="0.01" required onblur="validateField(this)">
              </div>
            </div>
          </div>


          <div class="pt-4 flex justify-between">
            <button type="button" class="btn-primary" onclick="prevStep()">Voltar</button>
            <button type="button" class="btn-primary" onclick="nextStep()">Próximo</button>
          </div>
        </div>

        <!-- Etapa 5: Finalização -->
        <div class="form-step hidden" id="step-5">
          <h2 class="text-xl font-bold mb-4">4. Finalização</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-white">Valor desejado</label>
              <input type="text" name="valor_desejado" class="form-input w-full" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-white">Valor da parcela</label>
              <input type="text" name="parcela_desejada" class="form-input w-full" required>
            </div>
          </div>
          <div class="pt-4 flex justify-between">
            <button type="button" class="btn-primary" onclick="prevStep()">Voltar</button>
            <button type="submit" class="btn-primary">Finalizar Cadastro</button>
          </div>
        </div>
      </form>

    </div>

  </div>
  
  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- JavaScript for FAQ Accordion and Responsive Utilities -->
  <script>
    // FAQ Accordion
    function toggleFaq(id) {
      const content = document.getElementById(id);
      const icon = document.getElementById('icon-' + id);
      if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.classList.add('rotate-180');
      } else {
        content.classList.add('hidden');
        icon.classList.remove('rotate-180');
      }
    }
    // Utilitários responsivos
    function adjustForScreenSize() {
      const isMobile = window.innerWidth < 768;
      const isSmallMobile = window.innerWidth < 480;
      // Ajustar tamanho do logo
      const logos = document.querySelectorAll('.large-logo');
      logos.forEach(logo => {
        if (isSmallMobile) {
          logo.style.height = '90px';
        } else if (isMobile) {
          logo.style.height = '120px';
        } else {
          logo.style.height = '150px';
        }
      });
      // Ajustar tamanho do logo no footer
      const footerLogos = document.querySelectorAll('.large-logo-footer');
      footerLogos.forEach(logo => {
        if (isSmallMobile) {
          logo.style.height = '50px';
        } else if (isMobile) {
          logo.style.height = '60px';
        } else {
          logo.style.height = '80px';
        }
      });
      // Ajustar padding em cards
      const cards = document.querySelectorAll('.feature-card, .plan-card .bg-glass');
      cards.forEach(card => {
        if (isMobile) {
          card.style.padding = isSmallMobile ? '0.75rem' : '1rem';
        }
      });
    }
    // Melhorar o scroll em dispositivos móveis
    function smoothScrollToElement(elementId) {
      const element = document.getElementById(elementId);
      if (element) {
        const headerOffset = 60;
        const elementPosition = element.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });
      }
    }
    // GSAP Animations
    document.addEventListener('DOMContentLoaded', function() {
      // Executar ajustes responsivos
      adjustForScreenSize();
      // Adicionar listener para redimensionamento
      window.addEventListener('resize', adjustForScreenSize);
      // Substituir links de âncora por scroll suave
      const anchorLinks = document.querySelectorAll('a[href^="#"]');
      anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          const targetId = this.getAttribute('href').substring(1);
          smoothScrollToElement(targetId);
        });
      });
      // Registrar o plugin ScrollTrigger
      gsap.registerPlugin(ScrollTrigger);
      // Animação dos títulos de seção
      gsap.utils.toArray(".gsap-fade-up").forEach(element => {
        gsap.to(element, {
          scrollTrigger: {
            trigger: element,
            start: "top 80%",
          },
          opacity: 1,
          y: 0,
          duration: 0.8,
          ease: "power2.out"
        });
      });
      // Animação dos cards de planos
      gsap.to(".plan-card", {
        scrollTrigger: {
          trigger: "#plans-container",
          start: "top 70%",
        },
        opacity: 1,
        y: 0,
        stagger: 0.1,
        duration: 0.6,
        ease: "back.out(1.2)"
      });
      // Animação dos preços
      gsap.from(".price-text", {
        scrollTrigger: {
          trigger: "#plans-container",
          start: "top 60%",
        },
        textContent: 0,
        duration: 1.5,
        ease: "power1.out",
        snap: { textContent: 1 },
        stagger: 0.1,
        delay: 0.5
      });
      // Animação dos itens de recursos
      gsap.from(".feature-item", {
        scrollTrigger: {
          trigger: "#plans-container",
          start: "top 50%",
        },
        opacity: 0,
        x: -20,
        stagger: 0.05,
        duration: 0.4,
        delay: 0.8
      });
      // Animação dos cards de recursos
      gsap.to(".feature-card", {
        scrollTrigger: {
          trigger: "#features-container",
          start: "top 70%",
        },
        opacity: 1,
        y: 0,
        stagger: 0.1,
        duration: 0.6,
        ease: "back.out(1.2)"
      });
      // Animação dos itens de FAQ
      gsap.to(".faq-item", {
        scrollTrigger: {
          trigger: "#faq-container",
          start: "top 80%",
        },
        opacity: 1,
        y: 0,
        stagger: 0.1,
        duration: 0.6
      });
      // Efeito de hover nos botões de assinatura
      const buttons = document.querySelectorAll('.btn-subscribe');
      buttons.forEach(button => {
        button.addEventListener('mouseenter', () => {
          gsap.to(button, {
            scale: 1.05,
            duration: 0.3,
            ease: "power1.out"
          });
        });
        button.addEventListener('mouseleave', () => {
          gsap.to(button, {
            scale: 1,
            duration: 0.3,
            ease: "power1.out"
          });
        });
      });
      // Efeito de parallax no fundo
      gsap.to("body", {
        backgroundPosition: "50% 100%",
        ease: "none",
        scrollTrigger: {
          trigger: "body",
          start: "top top",
          end: "bottom top",
          scrub: true
        }
      });
    });
  </script>
</body>
</html>


<!-- new added graphs chart js-->
	
	<script src="painel/js/Chart.bundle.js"></script>
	<script src="painel/js/utils.js"></script>
	
	<!-- Classie --><!-- for toggle left push menu script -->
	<script src="painel/js/classie.js"></script>
	
	<!--scrolling js-->
	<script src="painel/js/jquery.nicescroll.js"></script>
	<script src="painel/js/scripts.js"></script>
	<!--//scrolling js-->
	
	<!-- Bootstrap Core JavaScript -->
	<script src="painel/js/bootstrap.js"> </script>
	<!-- //Bootstrap Core JavaScript -->

	<!-- Mascaras JS -->
<script type="text/javascript" src="painel/js/mascaras.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script> 

<script>
let currentStep = 1;
const formSteps = document.querySelectorAll('.form-step');

document.addEventListener('DOMContentLoaded', function() {
    showStep(currentStep);
    setupMasks();
    // Adiciona event listeners para validação em tempo real
    document.querySelectorAll('.form-input').forEach(input => {
        input.addEventListener('input', function() {
            validateField(this);
        });
    });

    document.querySelectorAll('.form-select').forEach(select => {
        select.addEventListener('change', function() {
            validateField(this);
        });
    });

    // Adiciona onblur para campos de arquivo para validação quando saem do foco
    document.getElementById('comprovante_endereco').addEventListener('blur', function() {
        validateField(this);
    });
    document.getElementById('comprovante_rg').addEventListener('blur', function() {
        validateField(this);
    });
    document.getElementById('foto_usuario').addEventListener('blur', function() {
        validateField(this);
    });
});

function showStep(step) {
    formSteps.forEach((s, index) => {
        s.classList.add('hidden');
        if (index + 1 === step) {
            s.classList.remove('hidden');
        }
    });
}

function validateField(field) {
    // Remove a classe 'invalid' no início da validação para evitar que persista
    field.classList.remove('invalid');

    if (field.hasAttribute('required') && field.value.trim() === '') {
        field.classList.add('invalid');
        return false;
    }

    // Validação específica para o email
    if (field.type === 'email') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (field.value.trim() !== '' && !emailRegex.test(field.value)) {
            field.classList.add('invalid');
            return false;
        }
    }

    // Validação para confirmação de senha
    if (field.id === 'conf_senha') {
        const senha = document.getElementById('senha').value;
        if (field.value !== senha) {
            field.classList.add('invalid');
            return false;
        }
    }

    // Validação de arquivos (se required e nenhum arquivo selecionado)
    if (field.type === 'file' && field.hasAttribute('required')) {
        if (field.files.length === 0) {
            field.classList.add('invalid');
            return false;
        }
    }

    // Validação de número mínimo para inputs type="number"
    if (field.type === 'number' && field.hasAttribute('min')) {
        const minValue = parseFloat(field.getAttribute('min'));
        if (parseFloat(field.value) < minValue) {
            field.classList.add('invalid');
            return false;
        }
    }

    // Validação de data de nascimento (exemplo simples: maior de 18 anos)
    if (field.id === 'data_nasc' && field.value) {
        const birthDate = new Date(field.value);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        if (age < 18) { // Exemplo: exige que o usuário tenha pelo menos 18 anos
            field.classList.add('invalid');
            return false;
        }
    }

    field.classList.remove('invalid'); // Remove a classe 'invalid' se o campo for válido
    return true;
}

function validateStep(stepId) {
    let isValid = true;
    const currentFormStep = document.getElementById(stepId);
    const inputs = currentFormStep.querySelectorAll('input[required], select[required], input[type="file"][required], textarea[required]'); // Inclui textarea se houver

    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });

    // Validação adicional para campos de veículo se o ramo for "Motorista/Entregador App"
    const ramoSelect = document.getElementById('ramo');
    if (ramoSelect && ramoSelect.value === 'uber' && stepId === 'step-4') {
        const veiculoCampos = document.querySelectorAll('#veiculo-campos .veiculo-obrigatorio');
        veiculoCampos.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });
    }

    return isValid;
}

function nextStep() {
    if (validateStep(`step-${currentStep}`)) {
        if (currentStep < formSteps.length) {
            currentStep++;
            showStep(currentStep);
            window.scrollTo(0, 0); // Rola para o topo da página ao avançar a etapa
        }
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Erro de Validação',
            text: 'Por favor, preencha todos os campos obrigatórios e corrija os erros antes de continuar.'
        });
    }
}

function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
        window.scrollTo(0, 0); // Rola para o topo da página ao voltar a etapa
    }
}

document.getElementById('ramo').addEventListener('change', function() {
    const veiculoCampos = document.getElementById('veiculo-campos');
    if (this.value === 'uber') {
        veiculoCampos.classList.remove('hidden');
    } else {
        veiculoCampos.classList.add('hidden');
        // Limpa e remove a validação de campos de veículo se não for Uber
        document.querySelectorAll('#veiculo-campos .veiculo-obrigatorio').forEach(input => {
            input.value = '';
            input.classList.remove('invalid');
        });
    }
});

// Funções de Máscara (já existentes no seu código, garantindo que estejam aqui)
function setupMasks() {
    $('#cpf').mask('000.000.000-00');
    $('#telefone').mask('(00) 00000-0000');
    $('#cep').mask('00000-000');
    $('#referencia_contato').mask('(00) 00000-0000');
    $('input[name="valor_desejado"]').mask('000.000.000.000.000,00', {reverse: true});
    $('input[name="parcela_desejada"]').mask('000.000.000.000.000,00', {reverse: true});
    $('input[name="valor_aluguel"]').mask('000.000.000.000.000,00', {reverse: true});
}

// Funções de carregar imagem (já existentes no seu código)
function carregarImgComprovanteEndereco() {
    var file = document.getElementById('comprovante_endereco').files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('target-comprovante-endereco').src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('target-comprovante-endereco').src = 'painel/images/comprovantes/sem-foto.png';
    }
    validateField(document.getElementById('comprovante_endereco')); // Valida o campo após carregar a imagem
}

function carregarImgComprovanteRG() {
    var file = document.getElementById('comprovante_rg').files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('target-comprovante-rg').src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('target-comprovante-rg').src = 'painel/images/comprovantes/sem-foto.png';
    }
    validateField(document.getElementById('comprovante_rg')); // Valida o campo após carregar a imagem
}

function carregarImg() {
    var file = document.getElementById('foto_usuario').files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('foto').src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('foto').src = 'painel/images/comprovantes/sem-foto.png';
    }
    validateField(document.getElementById('foto_usuario')); // Valida o campo após carregar a imagem
}

// Função para validar CPF (simplificada para o exemplo)
function validarCPF(field) {
    const cpf = field.value.replace(/[^\d]/g, ''); // Remove caracteres não numéricos
    // Basic validation: must be 11 digits
    if (cpf.length !== 11 || !/^\d{11}$/.test(cpf)) {
        field.classList.add('invalid');
        return false;
    }
    // Adicione aqui a lógica completa de validação de CPF se necessário,
    // como a validação dos dígitos verificadores.
    field.classList.remove('invalid');
    return true;
}

// Funções de CEP (já existentes no seu código)
function pesquisacep(valor) {
    var cep = valor.replace(/\D/g, '');

    if (cep != "") {
        var validacep = /^[0-9]{8}$/;
        if(validacep.test(cep)) {
            document.getElementById('endereco').value="...";
            document.getElementById('bairro').value="...";
            document.getElementById('cidade').value="...";
            document.getElementById('estado').value="...";

            var script = document.createElement('script');
            script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';
            document.head.appendChild(script);

        } else {
            limpa_formulario_cep();
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Formato de CEP inválido.'
            });
            document.getElementById('cep').classList.add('invalid'); // Adiciona borda vermelha ao CEP inválido
        }
    } else {
        limpa_formulario_cep();
        document.getElementById('cep').classList.add('invalid'); // Adiciona borda vermelha se o CEP estiver vazio
    }
}

function meu_callback(conteudo) {
    if (!("erro" in conteudo)) {
        document.getElementById('endereco').value=(conteudo.logradouro);
        document.getElementById('bairro').value=(conteudo.bairro);
        document.getElementById('cidade').value=(conteudo.localidade);
        document.getElementById('estado').value=(conteudo.uf);
        document.getElementById('cep').classList.remove('invalid'); // Remove a borda vermelha se o CEP for válido
    } else {
        limpa_formulario_cep();
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'CEP não encontrado.'
        });
        document.getElementById('cep').classList.add('invalid'); // Adiciona borda vermelha ao CEP não encontrado
    }
}

function limpa_formulario_cep() {
    document.getElementById('endereco').value=("");
    document.getElementById('bairro').value=("");
    document.getElementById('cidade').value=("");
    document.getElementById('estado').value=("");
}

// GSAP Animations (manter suas animações existentes)
// Certifique-se de que o GSAP está sendo carregado no HTML antes deste script.
// Se você está incluindo o GSAP aqui, verifique se está após a inclusão da biblioteca.
if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);
    gsap.from(".gsap-fade-up", {
        opacity: 0,
        y: 50,
        duration: 1,
        stagger: 0.2,
        ease: "power3.out",
        scrollTrigger: {
            trigger: ".gsap-fade-up",
            start: "top 80%",
        },
    });
}
</script>