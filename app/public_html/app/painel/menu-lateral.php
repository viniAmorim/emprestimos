<?php
require_once("../../conexao.php");
@session_start();
$id_usuario = @$_SESSION['id'];

if (@$_SESSION['nivel'] != 'Administrador') {
  require_once("../../painel/verificar_permissoes.php");
}



$query = $pdo->query("SELECT * from usuarios where id = '$id_usuario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas > 0) {
  $nome_usuario = $res[0]['nome'];
  $nivel_usuario = $res[0]['nivel'];
  $foto_usuario = $res[0]['foto'];
}
?>
<div class="bg-theme mx-3 rounded-m shadow-m mt-3 mb-3">
  <div class="d-flex px-2 pb-2 pt-2">
    <div>
      <a href="#"><img src="../../painel/images/perfil/<?php echo $foto_usuario ?>" width="45" class="rounded-s"
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
            <a data-bs-toggle="offcanvas" data-bs-target="#menu-config" href="#"
              class="color-theme opacity-70 list-group-item py-1 <?php echo $configuracoes ?>"><strong
                class="font-500 font-12">Configurações</strong><i class="bi bi-chevron-right"></i></a>
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
      <i class="bi bi-chevron-right"></i>
    </a>

    <div class="<?php echo @$menu_pessoas ?>">
      <a data-bs-toggle="collapse" href="#collapse-list-1" aria-controls="collapse-list-1">
        <i class="gradient-blue shadow-bg shadow-bg-xs bi bi-person-fill"></i>
        <span>Pessoas</span>
        <i class="bi bi-chevron-right"></i>
      </a>
      <div id="collapse-list-1" class="collapse" style="background: #f5f5f5; border-radius: 10px">
        <span >
          <a href="clientes" class="<?php echo @$clientes ?>" onclick="navigateToPage(event, 'clientes')">
            <span class="font-12">Clientes</span>
            <i class="bi bi-chevron-right" style="margin-right: 10px"></i>
          </a>
        </span>

        <span class="<?php echo @$usuarios ?>">
          <a href="usuarios" class="<?php echo @$usuarios ?>" onclick="navigateToPage(event, 'usuarios')">
            <span class="font-12">Usuários</span>
            <i class="bi bi-chevron-right" style="margin-right: 10px"></i>
          </a>
        </span>
       
       
      </div>
    </div>

    <?php if($recursos != "Cobranças"){ ?>
     <span >
      <a class="<?php echo @$emprestimos ?>" href="emprestimos" id="nav-comps" onclick="navigateToPage(event, 'emprestimos')"><i
          class="gradient-green rounded-s bg-teal-dark bi bi-currency-dollar"></i>
          <span>Empréstimos</span>
        <i class="bi bi-chevron-right"></i>
      </a>
    </span>
  <?php } ?>


  <?php if($recursos != "Empréstimos"){ ?>
    <span >
      <a class="<?php echo @$cobrancas ?>" href="cobrancas" id="nav-comps" onclick="navigateToPage(event, 'cobrancas')"><i
          class="gradient-blue rounded-s bg-teal-dark bi bi-cash"></i>
          <span>Cobranças</span>
        <i class="bi bi-chevron-right"></i>
      </a>
    </span>
  <?php } ?>


    <div >
      <a class="<?php echo @$menu_cadastros ?>" data-bs-toggle="collapse" href="#collapse-list-3" aria-controls="collapse-list-3">
        <i class="gradient-yellow shadow-bg shadow-bg-xs fa-solid fa-sliders"></i>
        <span>Cadastros</span>
        <i class="bi bi-chevron-right"></i>
      </a>

      <div id="collapse-list-3" class="collapse" style="background: #f5f5f5; border-radius: 10px">
        <span >
          <a class="<?php echo @$status_clientes ?>" href="status_clientes"  onclick="navigateToPage(event, 'status_clientes')">
            <span class="font-12">Status Clientes</span>
            <i class="bi bi-chevron-right" style="margin-right: 10px"></i>
          </a>
        </span>

        <span class="<?php echo @$formas_pgto ?>">
          <a href="formas_pgto"  onclick="navigateToPage(event, 'formas_pgto')">
            <span class="font-12">Forma PGTO</span>
            <i class="bi bi-chevron-right" style="margin-right: 10px"></i>
          </a>
        </span>
        <span class="<?php echo @$frequencias ?>">
          <a href="frequencias" class="<?php echo @$frequencias ?>" onclick="navigateToPage(event, 'frequencias')">
            <span class="font-12">Frequências</span>
            <i class="bi bi-chevron-right" style="margin-right: 10px"></i>
          </a>
        </span>
        <span >
          <a href="cargos" class="<?php echo @$cargos ?>" onclick="navigateToPage(event, 'cargos')">
            <span class="font-12">Cargos</span></s>
            <i class="bi bi-chevron-right" style="margin-right: 10px"></i>
          </a>
        </span>
         <span >
          <a href="feriados" class="<?php echo @$feriados ?>" onclick="navigateToPage(event, 'feriados')">
            <span class="font-12">Feriados</span></s>
            <i class="bi bi-chevron-right" style="margin-right: 10px"></i>
          </a>
        </span>
         <span >
          <a href="sistemas" class="<?php echo @$sistemas ?>" onclick="navigateToPage(event, 'sistemas')">
            <span class="font-12">Sistemas</span></s>
            <i class="bi bi-chevron-right" style="margin-right: 10px"></i>
          </a>
        </span>
              
        

      </div>
    </div>



    <div class="<?php echo @$menu_financeiro ?>">
      <a data-bs-toggle="collapse" href="#collapse-list-2" aria-controls="collapse-list-2">
        <i class="gradient-green shadow-bg shadow-bg-xs bi bi-currency-exchange"></i>
        <span>Financeiro</span>
        <i class="bi bi-chevron-right"></i>
      </a>

      <div id="collapse-list-2" class="collapse" style="background: #f5f5f5; border-radius: 10px">
        <span >
          <a class="<?php echo @$pagar ?>" href="pagar" onclick="navigateToPage(event, 'pagar')">
            <span class="font-12">Contas a Pagar</span>
            <i class="bi bi-chevron-right" style="margin-right: 10px"></i>
          </a>
        </span>
        <span >
          <a class="<?php echo @$receber ?>" href="receber" onclick="navigateToPage(event, 'receber')">
            <span class="font-12">Contas a Receber</span>
            <i class="bi bi-chevron-right" style="margin-right: 10px"></i>
          </a>
        </span>
         <span >
          <a class="<?php echo @$receber_vencidas ?>" href="receber_vencidas" onclick="navigateToPage(event, 'receber_vencidas')">
            <span class="font-12">Receber Vencidas</span>
            <i class="bi bi-chevron-right" style="margin-right: 10px"></i>
          </a>
        </span>

         <span >
          <a class="<?php echo @$relatorios_financeiro ?>" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-financ" >
            <span class="font-12">Relatório Financeiro</span>
            <i class="bi bi-chevron-right" style="margin-right: 10px"></i>
          </a>
        </span>

         <span >
          <a class="<?php echo @$relatorios_debitos ?>" href="../../painel/rel/debitos_class.php" >
            <span class="font-12">Relatório Débitos</span>
            <i class="bi bi-chevron-right" style="margin-right: 10px"></i>
          </a>
        </span>

        <?php if($recursos != "Cobranças"){ ?>
       <span >
          <a class="<?php echo @$relatorio_lucros ?>" href="lucro" onclick="navigateToPage(event, 'lucro')">
            <span class="font-12">Lucro</span>
            <i class="bi bi-chevron-right" style="margin-right: 10px"></i>
          </a>
        </span>
      <?php } ?>

        <span class="<?php echo @$relatorios_caixa ?>">
          <a href="../../painel/rel/caixa_class.php"  >
            <span class="font-12">Relatório Diário Caixa</span>
            <i class="bi bi-chevron-right" style="margin-right: 10px"></i>
          </a>
        </span>

        <span class="<?php echo @$relatorios_ina ?>">
          <a href="../../painel/rel/sintetico_inadimplentes_class.php"  >
            <span class="font-12">Relatório Inadimplêntes</span>
            <i class="bi bi-chevron-right" style="margin-right: 10px"></i>
          </a>
        </span>

      </div>
    </div>

    <?php if($recursos != "Cobranças"){ ?>
    <span class="<?php echo @$solicitar_emprestimo ?>">
      <a href="solicitar_emprestimo" id="nav-comps" onclick="navigateToPage(event, 'solicitar_emprestimo')">
        <i class="gradient-magenta rounded-s bg-orange-dark fa-solid fa-list-check"></i><span>Solicitações Empréstimos</span>
        <i class="bi bi-chevron-right"></i>
      </a>
    </span>
  <?php } ?>

     <span >
      <a class="<?php echo @$gestao_mensagens ?>" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-gestao" >
          <i class="gradient-teal rounded-s bg-teal-dark bi bi-whatsapp"></i>
          <span>Gestão de Mensagens</span>
        <i class="bi bi-chevron-right"></i>
      </a>
    </span>




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


