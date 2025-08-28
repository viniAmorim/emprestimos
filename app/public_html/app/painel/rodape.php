
<!-- MENU DE INSTALAÇÃO DO APP-->
<div class="offcanvas offcanvas-bottom rounded-m offcanvas-detached" id="menu-install-pwa-ios">
  <div class="content">
    <img src="app/icons/icon-128x128.png" alt="img" width="80" class="rounded-l mx-auto my-4">
    <h1 class="text-center font-800 font-20">Adicionar à tela inicial</h1>
    <p class="boxed-text-xl">
      Instale o Sistema na sua tela inicial e acesse-o como um aplicativo comum. Abra o menu do seu navegador e toque em "Adicionar à Tela inicial".
    </p>
    <a href="#"
      class="pwa-dismiss close-menu gradient-blue shadow-bg shadow-bg-s btn btn-s btn-full text-uppercase font-700  mt-n2"
      data-bs-dismiss="offcanvas">Mais tarde</a>
  </div>
</div>

<div class="offcanvas offcanvas-bottom rounded-m offcanvas-detached" id="menu-install-pwa-android">
  <div class="content">
    <img src="app/icons/icon-128x128.png" alt="img" width="80" class="rounded-m mx-auto my-4">
    <h1 class="text-center font-700 font-20">Instalar</h1>
    <p class="boxed-text-l">
      Instale o Sistema na sua tela inicial para desfrutar de uma experiência única e nativa.
    </p>
    <a href="#"
      class="pwa-install btn btn-m rounded-s text-uppercase font-900 gradient-highlight shadow-bg shadow-bg-s btn-full">Adicionar
      na Tela Inicical</a><br>
    <a href="#" data-bs-dismiss="offcanvas"
      class="pwa-dismiss close-menu color-theme text-uppercase font-900 opacity-50 font-11 text-center d-block mt-n1">Mais
      tarde</a>
  </div>
</div>


<!-- Footer Bar-->
<div id="footer-bar" class="footer-bar footer-bar-detached">
  <a class="<?php echo @$clientes ?>" href="clientes" onclick="navigateToPage(event, 'clientes')"><i class="bi bi-people-fill font-15"></i><span>Clientes</span></a>
   <?php if($recursos != "Cobranças"){ ?>
  <a class="<?php echo @$emprestimos ?>" href="emprestimos" onclick="navigateToPage(event, 'emprestimos')"><i class="bi bi-list-task font-17"></i><span>Empréstimos</span></a>
<?php }else{ ?>
<a class="<?php echo @$cobrancas ?>" href="cobrancas" onclick="navigateToPage(event, 'cobrancas')"><i class="bi bi-list-task font-17"></i><span>Empréstimos</span></a>
<?php } ?>
  <a class="<?php echo @$home ?>" href="index" onclick="navigateToPage(event, 'index')"><i class="bi bi-house-fill font-16"></i><span>Home</span></a>
  <a class="<?php echo @$gestao_mensagens ?>" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-gestao"><i class="bi bi-chat-dots-fill font-16"></i><span>Mensagens</span></a>
  <a href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-main"><i class="bi bi-list"></i><span>Menu</span></a>
</div>

<script>
  // Função para ativar a navegação
  function activateNav() {
    // Obtém o caminho atual da URL
    const currentPath = window.location.pathname.split('/').pop(); // Obtém o último segmento da URL

    // Seleciona todos os links do footer
    const links = document.querySelectorAll('#footer-bar a');

    // Itera sobre os links e ativa o correspondente
    links.forEach(link => {
      const href = link.getAttribute('href');
      if (href === currentPath) {
        link.classList.add('active-nav'); // Adiciona a classe active-nav
      } else {
        link.classList.remove('active-nav'); // Remove a classe active-nav
      }
    });
  }

  // Chama a função ao carregar a página
  window.onload = activateNav;
</script>


<script src="scripts/bootstrap.min.js"></script>
<script src="scripts/custom.js"></script>





<!-- Base Js File -->
<script src="../_service-worker.js"></script>
<script src="js/base.js"></script>
