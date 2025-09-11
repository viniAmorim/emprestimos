<?php
require_once("../../conexao.php");
@session_start();
$id_usuario = @$_SESSION['id'];


$query = $pdo->query("SELECT * from clientes where id = '$id_usuario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas > 0) {
  $nome_usuario = $res[0]['nome'];
  $nivel_usuario = 'Cliente';
  $foto_usuario = $res[0]['foto'];
}
?>
<div class="bg-theme mx-3 rounded-m shadow-m mt-3 mb-3">
  <div class="d-flex px-2 pb-2 pt-2">
    <div>
      <a href="#"><img src="../../painel/images/clientes/<?php echo $foto_usuario ?>" width="45" class="rounded-s"
          alt="img"></a>
    </div>
    <div class="ps-2 align-self-center">
      <h5 class="ps-1 mb-0 line-height-xs pt-1"><?php echo $nome_usuario ?></h5>
      <h6 class="ps-1 mb-0 font-400 opacity-40"><?php echo $nivel_usuario ?></h6>
    </div>
    <div class="ms-auto">
      <a href="#" data-bs-toggle="dropdown" class="icon icon-m ps-3"><i
          class="bi bi-three-dots-vertical font-18 color-theme"></i></a>
      <div class="dropdown-menu  bg-transparent border-0 mt-n1 ms-3">
        <div class="card card-style rounded-m shadow-xl mt-1 me-1">
          <div class="list-group list-custom list-group-s list-group-flush rounded-xs px-3 py-1">
            <a data-bs-toggle="offcanvas" data-bs-target="#menu-perfil" href="#"
              class="color-theme opacity-70 list-group-item py-1"><strong class="font-500 font-12">Editar
                Perfil</strong><i class="bi bi-chevron-right"></i></a>
          
            <a href="logout.php" class="color-theme opacity-70 list-group-item py-1"><strong
                class="font-500 font-12">Sair</strong><i class="bi bi-chevron-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>





<span class="menu-divider">NAVEGAÇÃO</span>
<div class="menu-list">
  <div class="card card-style rounded-m p-3 py-2 mb-0">

    <a href="index.php" id="nav-homes" onclick="navigateToPage(event, 'index')"><i class="gradient-night shadow-bg shadow-bg-xs bi bi-house-fill <?php echo @$home ?>"></i><span>Home</span>
      
    </a>

    
    <?php if($recursos != "Cobranças"){ ?>
     <span >
      <a class="<?php echo @$emprestimos ?>" href="emprestimos" id="nav-comps" onclick="navigateToPage(event, 'emprestimos')"><i
          class="gradient-green rounded-s bg-teal-dark bi bi-currency-dollar"></i>
          <span>Empréstimos</span>
       
      </a>
    </span>
  <?php } ?>


  <?php if($recursos != "Empréstimos"){ ?>
    <span >
      <a class="<?php echo @$cobrancas ?>" href="cobrancas" id="nav-comps" onclick="navigateToPage(event, 'cobrancas')"><i
          class="gradient-blue rounded-s bg-teal-dark bi bi-cash"></i>
          <span>Cobranças</span>
       
      </a>
    </span>
  <?php } ?>



  <a href="receber" id="nav-receber" onclick="navigateToPage(event, 'receber')"><i class="gradient-night shadow-bg shadow-bg-xs bi bi-currency-exchange <?php echo @$receber ?>"></i><span>Minhas Contas</span>
      
    </a>


  

    <?php if($recursos != "Cobranças"){ ?>
    <span class="<?php echo @$solicitar_emprestimo ?>">
      <a href="solicitar_emprestimo" id="nav-comps" onclick="navigateToPage(event, 'solicitar_emprestimo')">
        <i class="gradient-magenta rounded-s bg-orange-dark fa-solid fa-list-check"></i><span>Solicitações Empréstimos</span>
        
      </a>
    </span>
  <?php } ?>

     


  </div>
</div>

<span class="menu-divider mt-4">CONFIGURAÇÕES</span>
<div class="menu-list">
  <div class="card card-style rounded-m p-3 py-2 mb-0">
    <a href="" data-bs-toggle="offcanvas" data-bs-target="#menu-color">
      <i class="gradient-highlight shadow-bg shadow-bg-xs bi bi-palette-fill"></i>
      <span>Destaques</span>
      <i class="bi bi-chevron-right"></i>
    </a>
    <a href="#" data-toggle-theme data-trigger-switch="switch-1">
      <i class="gradient-dark shadow-bg shadow-bg-xs bi bi-moon-fill font-13"></i>
      <span>Dark Mode</span>
      <div class="form-switch ios-switch switch-green switch-s me-2">
        <input type="checkbox" data-toggle-theme class="ios-input" id="switch-1">
        <label class="custom-control-label" for="switch-1"></label>
      </div>
    </a>
  </div>
</div>




<span class="menu-divider mt-4"></span>
<div class="menu-content px-3">

</div>

<p class="text-center mb-0 mt-n3 pb-3 font-9 font-600 color-theme">Desenvolvido por <a href="https://www.hugocursos.com.br" target="_blank">Hugo Vasconcelos</a> <span class="copyright-year"></span>.</p>


