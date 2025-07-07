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
      padding: 0.75rem 1rem 0.75rem 2.5rem !important;
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
              <input type="text" name="nome" placeholder="Nome completo" class="form-input w-full" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-white">Email</label>
              <input type="email" id="email" name="email" placeholder="Email" class="form-input w-full" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-white">Telefone</label>
              <input type="text" name="telefone" id="telefone" placeholder="Telefone" class="form-input w-full" required>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-white">CPF</label>
              <input type="text" name="cpf" id="cpf" placeholder="CPF" class="form-input w-full" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-white">RG</label>
              <input type="text" name="rg" id="rg" placeholder="RG" class="form-input w-full" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-white">Data de Nascimento</label>
              <input type="date" id="data_nasc" name="data_nasc" class="form-input w-full" required>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div>
              <label class="block text-sm font-medium text-white">Chave Pix em sua titularidade</label>
              <input type="text" name="pix" id="pix" placeholder="CPF, telefone ou e-mail" class="form-input w-full">
            </div>
            <!-- Comprovante de Endereço -->
            <div class="flex items-start gap-4">
              <div class="flex-1">
                <label class="block text-sm font-medium text-white">Comprovante de Endereço</label>
                <input type="file" name="comprovante_endereco" id="comprovante_endereco" onchange="carregarImgComprovanteEndereco()" accept=".jpg,.jpeg,.png" class="form-input w-full" required>
              </div>
              <div class="w-20 h-20 border border-gray-300 rounded overflow-hidden bg-white">
                <img src="painel/images/comprovantes/sem-foto.png" id="target-comprovante-endereco" class="object-cover w-full h-full">
              </div>
            </div>

            <!-- Comprovante RG/CPF -->
            <div class="flex items-start gap-4">
              <div class="flex-1">
                <label class="block text-sm font-medium text-white">Comprovante RG/CPF</label>
                <input type="file" id="comprovante_rg" name="comprovante_rg" onchange="carregarImgComprovanteRG()" accept=".jpg,.jpeg,.png" class="form-input w-full" required>
              </div>
              <div class="w-20 h-20 border border-gray-300 rounded overflow-hidden bg-white">
                <img src="painel/images/comprovantes/sem-foto.png" id="target-comprovante-rg" class="object-cover w-full h-full">
              </div>
            </div>
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
              <input type="password" name="senha" id="senha" class="form-input w-full" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-white">Confirmar Senha</label>
              <input type="password" name="conf_senha" id="conf_senha" class="form-input w-full" required>
            </div>
          </div>

          <!-- Campo de Upload de Foto -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 items-start">
            <div>
              <label class="block text-sm font-medium text-white">Foto do Usuário</label>
              <input type="file" id="foto_usuario" name="foto_usuario" accept=".jpg,.jpeg,.png" onchange="carregarImg()" class="form-input w-full" required>
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
              <input type="text" name="cep" id="cep" class="form-input w-full" onblur="pesquisacep(this.value)" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-white">Endereço</label>
              <input type="text" name="endereco" id="endereco" class="form-input w-full" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-white">Bairro</label>
              <input type="text" name="bairro" id="bairro" class="form-input w-full" required>
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 mb-3">
            <div>
              <label class="block text-sm font-medium text-white">Cidade</label>
              <input type="text" name="cidade" id="cidade" class="form-input w-full" required>
            </div>
            <div>
              <label for="estado" class="block text-sm font-medium text-white">Estado</label>
              <select id="estado" name="estado" class="form-input w-full" required>
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
            <div class="md:col-span-3">
              <label class="block text-sm font-medium text-white">Complemento</label>
              <input type="text" name="complemento" class="form-input w-full">
            </div>

            <div class="md:col-span-1">
              <label class="block text-sm font-medium text-white">Quadra</label>
              <input type="text" name="quadra" class="form-input w-full">
            </div>

            <div class="md:col-span-1">
              <label class="block text-sm font-medium text-white">Lote</label>
              <input type="number" name="lote" class="form-input w-full" min="0">
            </div>
            
            <div class="md:col-span-1">
              <label class="block text-sm font-medium text-white">Número</label>
              <input type="number" name="numero" class="form-input w-full" min="0">
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
              <input type="text" name="referencia_contato" id="referencia_contato" class="form-input w-full" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-white">Nome Completo da referência</label>
              <input type="text" id="referencia_nome" name="referencia_nome" class="form-input w-full" required>
            </div>
            <div>
              <label for="referencia_parentesco" class="block text-sm font-medium text-white">Grau de parentesco</label>
              <select id="referencia_parentesco" name="referencia_parentesco" class="form-input w-full" required>
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
              <input type="text" id="indicacao" name="indicacao" placeholder="Indicado por" class="form-input w-full" required>
            </div>
            <div>
              <label for="ramo" class="block text-sm font-medium text-white">Ramo de Atuação</label>
              <select id="ramo" name="ramo" class="form-input w-full" required>
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
                <input type="text" name="modelo_veiculo" class="form-input w-full veiculo-obrigatorio">
              </div>
              <div>
                <label class="block text-sm font-medium">Placa</label>
                <input type="text" name="placa_veiculo" class="form-input w-full veiculo-obrigatorio" maxlength="7" placeholder="ABC1234">
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
              <div>
                <label class="block text-sm font-medium">Status do Veículo</label>
                <select name="status_veiculo" class="form-input w-full veiculo-obrigatorio">
                  <option value="" disabled selected>Selecione</option>
                  <option value="proprio">Próprio</option>
                  <option value="alugado">Alugado</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium">Valor do Aluguel</label>
                <input type="number" name="valor_aluguel" class="form-input w-full veiculo-obrigatorio" placeholder="R$" min="0" step="0.01">
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
  // Máscara para CPF
  document.addEventListener('DOMContentLoaded', function () {
    const cpfInput = document.getElementById('cpf');

    cpfInput.addEventListener('input', function (e) {
      let value = e.target.value.replace(/\D/g, '');

      if (value.length > 11) value = value.slice(0, 11);

      value = value.replace(/(\d{3})(\d)/, '$1.$2');
      value = value.replace(/(\d{3})(\d)/, '$1.$2');
      value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

      e.target.value = value;
    });
  });

  // Máscara para RG
  document.addEventListener('DOMContentLoaded', function () {
    const rgInput = document.getElementById('rg');
    if (rgInput) {
      rgInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');

        if (value.length > 9) value = value.slice(0, 9);

        value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{0,1})$/, '$1.$2.$3-$4');
        e.target.value = value;
      });
    }
  });

  // VALORES (R$)
  function formatarMoeda(valor) {
    valor = valor.replace(/\D/g, '');
    valor = (parseInt(valor, 10) / 100).toFixed(2);
    return 'R$ ' + valor
      .replace('.', ',')
      .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }

  const camposMoeda = document.querySelectorAll('.money');

  camposMoeda.forEach(function (campo) {
    campo.addEventListener('input', function (e) {
      let cursor = campo.selectionStart;
      let valorAntigo = campo.value;
      campo.value = formatarMoeda(campo.value);
      let diff = campo.value.length - valorAntigo.length;
      campo.setSelectionRange(cursor + diff, cursor + diff);
    });

    // Opcional: inicia já formatado se tiver valor
    campo.addEventListener('blur', function () {
      campo.value = formatarMoeda(campo.value);
    });
  });
    
    function meu_callback(conteudo) {
      if (!("erro" in conteudo)) {
        //Atualiza os campos com os valores retornados.
        document.getElementById('endereco').value = conteudo.logradouro;
        document.getElementById('bairro').value = conteudo.bairro;
        document.getElementById('cidade').value = conteudo.localidade;
        document.getElementById('estado').value = conteudo.uf;
      } else {
        //CEP não encontrado.
        limpa_formulário_cep();
        alert("CEP não encontrado.");
      }
    }

    function limpa_formulário_cep() {
      document.getElementById('endereco').value = "";
      document.getElementById('bairro').value = "";
      document.getElementById('cidade').value = "";
      document.getElementById('estado').value = "";
    }

        
    function pesquisacep(valor) {
      //Nova variável "cep" somente com dígitos.
      var cep = valor.replace(/\D/g, '');

      //Verifica se campo cep possui valor informado.
      if (cep != "") {
        //Expressão regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;

        //Valida o formato do CEP.
        if(validacep.test(cep)) {
          //Preenche os campos com "..." enquanto consulta webservice.
          document.getElementById('endereco').value="...";
          document.getElementById('bairro').value="...";
          document.getElementById('cidade').value="...";
          document.getElementById('estado').value="...";
          //document.getElementById('ibge').value="...";

          //Cria um elemento javascript.
          var script = document.createElement('script');

          //Sincroniza com o callback.
          script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

          //Insere script no documento e carrega o conteúdo.
          document.body.appendChild(script);
        } else {
          //cep é inválido.
          limpa_formulário_cep();
          alert("Formato de CEP inválido.");
        }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    };

    $("#form").submit(function (event) {

      event.preventDefault();
      var formData = new FormData(this);

      // Exibe alerta de carregamento
      Swal.fire({
          title: 'Salvando...',
          text: 'Aguarde um instante.',
          icon: 'info',
          showConfirmButton: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          didOpen: () => {
              Swal.showLoading()
          }
      });

      $.ajax({
          url: 'painel/paginas/clientes/salvar.php',
          type: 'POST',
          data: formData,

          success: function (mensagem) {
              $('#mensagem').text('');
              $('#mensagem').removeClass();

              if (mensagem.trim() == "Salvo com Sucesso") {
                  // Mostra alerta de sucesso com SweetAlert
                  Swal.fire({
                      title: 'Sucesso!',
                      text: 'Cadastrado com sucesso!',
                      icon: 'success',
                      confirmButtonText: 'OK'
                  }).then((result) => {
                      if (result.isConfirmed) {
                          window.location = "http://localhost/";
                      }
                  });
              } else {
                  // Mostra erro com SweetAlert
                  Swal.fire({
                      title: 'Erro ao cadastrar!',
                      text: mensagem,
                      icon: 'error',
                      confirmButtonText: 'OK'
                  });
              }
          },

          cache: false,
          contentType: false,
          processData: false,
      });
  });


</script>

<script type="text/javascript">
	function carregarImgComprovanteEndereco() {
		var target = document.getElementById('target-comprovante-endereco');
		var file = document.querySelector("#comprovante_endereco").files[0];


		var arquivo = file['name'];
		resultado = arquivo.split(".", 2);

		if(resultado[1] === 'pdf'){
			$('#target-comprovante-endereco').attr('src', "painel/images/pdf.png");
			return;
		}

		if(resultado[1] === 'rar' || resultado[1] === 'zip'){
			$('#target-comprovante-endereco').attr('src', "painel/images/rar.png");
			return;
		}

		var reader = new FileReader();

		reader.onloadend = function () {
			target.src = reader.result;
		};

		if (file) {
			reader.readAsDataURL(file);

		} else {
			target.src = "";
		}
	}
</script>

<script type="text/javascript">
	function carregarImgComprovanteRG() {
		var target = document.getElementById('target-comprovante-rg');
		var file = document.querySelector("#comprovante_rg").files[0];


		var arquivo = file['name'];
		resultado = arquivo.split(".", 2);

		if(resultado[1] === 'pdf'){
			$('#target-comprovante-rg').attr('src', "painel/images/pdf.png");
			return;
		}

		if(resultado[1] === 'rar' || resultado[1] === 'zip'){
			$('#target-comprovante-rg').attr('src', "painel/images/rar.png");
			return;
		}

		var reader = new FileReader();

		reader.onloadend = function () {
			target.src = reader.result;
		};

		if (file) {
			reader.readAsDataURL(file);

		} else {
			target.src = "";
		}
	}
</script>


<script>

  let currentStep = 1;
  const totalSteps = 5;

  function showStep(step) {
    // Oculta todos os steps
    const steps = document.querySelectorAll('.form-step');
    steps.forEach((s) => s.classList.add('hidden'));

    // Mostra o step atual
    const current = document.getElementById(`step-${step}`);
    if (current) current.classList.remove('hidden');
  }

  function nextStep() {
    const currentFormStep = document.getElementById(`step-${currentStep}`);
    const inputs = currentFormStep.querySelectorAll('input, select, textarea');
    let valid = true;

    inputs.forEach(input => {
      if (!input.checkValidity()) {
        input.classList.add('border-red-500');
        valid = false;
      } else {
        input.classList.remove('border-red-500');
      }
    });

    // Verificação da senha na etapa 2
    if (valid && currentStep === 2) {
      const senha = document.getElementById('senha').value;
      const confSenha = document.getElementById('conf_senha').value;

      if (senha !== confSenha) {
        Swal.fire({
          icon: 'error',
          title: 'Senhas não coincidem',
          text: 'A senha e a confirmação precisam ser iguais.',
        });

        document.getElementById('senha').classList.add('border-red-500');
        document.getElementById('conf_senha').classList.add('border-red-500');
        return;
      }
    }

    // Validação extra na etapa 4 (motorista/entregador)
    if (currentStep === 4) {
      const ramo = document.getElementById('ramo').value.toLowerCase();
      if (ramo.includes('uber') || ramo.includes('entregador')) {
        const camposVeiculo = document.querySelectorAll('.veiculo-obrigatorio');
        let camposInvalidos = [];

        camposVeiculo.forEach((campo) => {
          if (!campo.value.trim()) {
            campo.classList.add('border-red-500');
            camposInvalidos.push(campo);
          } else {
            campo.classList.remove('border-red-500');
          }
        });

        if (camposInvalidos.length > 0) {
          Swal.fire({
            icon: 'error',
            title: 'Campos obrigatórios',
            text: 'Preencha todos os dados do veículo para continuar.',
          });
          return;
        }
      }
    }

    if (!valid) {
      Swal.fire({
        icon: 'error',
        title: 'Campos obrigatórios',
        text: 'Preencha todos os campos corretamente antes de continuar.',
      });
      return;
    }

    if (currentStep < totalSteps) {
      currentStep++;
      showStep(currentStep);
    }
  }

  function prevStep() {
    if (currentStep > 1) {
      currentStep--;
      showStep(currentStep);
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    showStep(currentStep); // Exibe o primeiro step ao carregar
    const ramoSelect = document.getElementById('ramo');
    const camposVeiculo = document.getElementById('veiculo-campos');

    ramoSelect.addEventListener('change', function () {
      const valor = this.value.toLowerCase();
      if (valor.includes('uber') || valor.includes('entregador')) {
        camposVeiculo.classList.remove('hidden');
      } else {
        camposVeiculo.classList.add('hidden');
        camposVeiculo.querySelectorAll('input, select').forEach(el => el.value = '');
      }
    });
  });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
      const aplicarMascaraTelefone = (input) => {
        input.addEventListener('input', function (e) {
          let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não é dígito

          // Se o valor estiver vazio, não aplique máscara e retorne
          if (value.length === 0) {
            e.target.value = '';
            return;
          }

          if (value.length > 11) {
            value = value.slice(0, 11);
          }

          let maskedValue = '';
          if (value.length <= 2) {
            // Apenas os dois primeiros dígitos (para o DDD)
            maskedValue = `(${value}`;
          } else if (value.length <= 6) {
            // DDD e os primeiros 4 ou 5 dígitos
            maskedValue = `(${value.substring(0, 2)}) ${value.substring(2)}`;
          } else if (value.length <= 10) {
            // Telefone fixo ou 8 dígitos de celular (9xxxx-xxxx)
            maskedValue = `(${value.substring(0, 2)}) ${value.substring(2, 6)}-${value.substring(6)}`;
          } else {
            // Celular de 9 dígitos
            maskedValue = `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7, 11)}`;
          }

          e.target.value = maskedValue;
        });
      };

      const telefoneInput = document.getElementById('telefone');
      const referenciaContatoInput = document.getElementById('referencia_contato'); // Assumindo que este campo existe

      if (telefoneInput) aplicarMascaraTelefone(telefoneInput);
      if (referenciaContatoInput) aplicarMascaraTelefone(referenciaContatoInput);
    });
  </script>
