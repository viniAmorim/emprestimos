<?php 
@session_start();
require_once("conexao.php");

if($entrada_sistema == 'Login'){
  // Verifica se o usuário está logado (exemplo: verifica se existe uma sessão)
  if (!isset($_SESSION['usuario_logado_pagina'])) {    
      echo '<script>window.location="login.php"</script>';
    }
  
}

$data_atual = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="pt-BR">
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
              dark: '#1a2c3d',
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
      --secondary-color: #1a2c3d;
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
      background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
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
      color: var(--text-dark);
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
  </style>
</head>
<body class="font-poppins bg-gradient-to-br from-primary-dark to-primary text-white min-h-screen overflow-x-hidden">
  <div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="container mx-auto">
      
      <!-- Bloco CTA Simplificado -->
      <div class="mb-12 bg-primary-dark p-8 rounded-xl border border-accent/20 shadow-lg">
        <div class="text-center mb-6" >
          <!-- Logo adicionada acima do título -->
          <div class="flex justify-center mb-6" style="padding:0; margin-top:-60px">
            <img src="img/<?php echo $logo_site ?>" alt="Logo" style="height: 200px; width: auto; padding:0;" />
          </div>
          
          <h1 class="text-2xl md:text-4xl font-bold mb-4">
            Conheça nossa Corretora <span class="text-accent">HOJE MESMO!</span>
          </h1>
          <p class="text-gray-300 text-lg max-w-3xl mx-auto">
            <small>O <strong class="text-white"><?php echo $nome_sistema ?></strong> é a solução ideal para você que precisa de um empréstimo rápido, seguro e sem burocracia. Com um processo simples e transparente, ajudamos você a conquistar seus objetivos com as melhores condições do mercado.</small>
          </p>
        </div>
        
        <div class="flex flex-col sm:flex-row justify-center gap-4 mb-6">
          <a target="_blank" href="cadastro" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-lg text-white bg-customGreen hover:bg-green-700 focus:outline-none animate-pulse-green transition-all duration-300">
            <i class="fas fa-rocket mr-2"></i> Começar Agora
          </a>
          <a href="acesso" class="inline-flex items-center justify-center px-6 py-3 border border-white/20 text-base font-medium rounded-lg shadow-lg text-white hover:bg-white/10 focus:outline-none transition-all duration-300">
            <i class="fas fa-unlock mr-2"></i> Acesso Cliente
          </a>

          <a href="login" class="inline-flex items-center justify-center px-6 py-3 border border-white/20 text-base font-medium rounded-lg shadow-lg text-white hover:bg-white/10 focus:outline-none transition-all duration-300" style="background:#575958">
            <i class="fas fa-unlock-alt mr-2"></i> Acesso Gestão
          </a>
        </div>
        
       
      </div>
      
    
      <!-- Footer -->
      <footer class="py-4 md:py-6 border-t border-gray-700 gsap-fade-up">
        <div class="container mx-auto flex flex-col md:flex-row items-center justify-center md:justify-between px-4">
          <!-- Logo Grande no Footer - ajustado para ser menor em dispositivos móveis -->
         <div class="flex justify-center mb-4 md:mb-6">
          <img src="img/<?php echo $logo_site ?>" alt="Logo" style="height: 120px; width: auto;" />
        </div>
          <div class="text-center md:text-right">
            <p class="text-gray-400 text-sm">&copy; <?php echo date('Y'); ?> <?php echo $nome_sistema ?>. </p>
            <p class="text-gray-500 text-xs md:text-sm mt-1 md:mt-2">Contato <?php echo $telefone_sistema ?></p>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
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

