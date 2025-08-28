<?php
@session_start();
require_once("../../conexao.php");
$pag_inicial = 'home';
$data_page = 'index';

$id_usuario = @$_SESSION['id'];

$query = $pdo->query("SELECT * from receber where pago = 'Não' and data_venc = curDate()");
  $res = $query->fetchAll(PDO::FETCH_ASSOC);
  $total_vencendo_msg = @count($res);

    $query = $pdo->query("SELECT * from receber where pago = 'Não' and data_venc < curDate()");
  $res = $query->fetchAll(PDO::FETCH_ASSOC);
  $total_vencidas_msg = @count($res);

if (@$_SESSION['aut_token_portalapp'] != 'portalapp2024') {
  echo "<script>localStorage.setItem('id_usu', '')</script>";
  unset($_SESSION['id'], $_SESSION['nome'], $_SESSION['nivel']);
  $_SESSION['msg'] = "";
  echo '<script>window.location="../"</script>';
  exit();
}


if (@$_SESSION['nivel'] != 'Administrador') {
  require_once("../../painel/verificar_permissoes.php");
}



$buscar = @$_POST['buscar'];

$dataInicial = @$_POST['dataInicial'];
$dataFinal = @$_POST['dataFinal'];
$pago = @$_POST['pago'];

$data_hoje = date('Y-m-d');
$data_atual = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$data_inicio_mes = $ano_atual . "-" . $mes_atual . "-01";
$data_inicio_ano = $ano_atual . "-01-01";
$data_mes = $ano_atual."-".$mes_atual."-01";

$data_ontem = date('Y-m-d', strtotime("-1 days", strtotime($data_atual)));
$data_amanha = date('Y-m-d', strtotime("+1 days", strtotime($data_atual)));


if ($mes_atual == '04' || $mes_atual == '06' || $mes_atual == '09' || $mes_atual == '11') {
  $data_final_mes = $ano_atual . '-' . $mes_atual . '-30';
} else if ($mes_atual == '02') {
  $bissexto = date('L', @mktime(0, 0, 0, 1, 1, $ano_atual));
  if ($bissexto == 1) {
    $data_final_mes = $ano_atual . '-' . $mes_atual . '-29';
  } else {
    $data_final_mes = $ano_atual . '-' . $mes_atual . '-28';
  }

} else {
  $data_final_mes = $ano_atual . '-' . $mes_atual . '-31';
}


if (@$_GET['pagina'] != "") {
  $pagina = @$_GET['pagina'];


} else {
  $pagina = $pag_inicial;
}



$query = $pdo->query("SELECT * from usuarios where id = '$id_usuario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas > 0) {
  $nome_usuario = $res[0]['nome'];
  $email_usuario = $res[0]['email'];
  $telefone_usuario = $res[0]['telefone'];
  $senha_usuario = $res[0]['senha'];
  $nivel_usuario = $res[0]['nivel'];
  $foto_usuario = $res[0]['foto'];
  $endereco_usuario = $res[0]['endereco'];
} else {
  echo '<script>window.location="../"</script>';
  exit();
}

require_once("cabecalho.php");
require_once("rodape.php");
require_once("alertas.php");

echo "<script>localStorage.setItem('pagina', '$pagina')</script>";
require_once("paginas/" . $pagina . ".php");

?>




<!-- MODAL CONFIGURAÇÕES-->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:98%" id="menu-config">
  <div class="content mb-0">
    <div class="d-flex pb-2">
      <div class="align-self-center text-uppercase">
        <span style="margin-left: 130px !important" class="font-14 color-highlight font-700 ">CONFIGURAÇÕES</span>
      </div>
      <div class="align-self-center ms-auto">
        <button style="border: none; background: transparent; margin-right: 12px" data-bs-dismiss="offcanvas"
          id="btn-fechar-config" aria-label="Close" data-bs-dismiss="modal" type="button"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i>
        </button>
      </div>
    </div><br>
    <form id="form-config" class="demo-animation m-0">

    

    <!-- Exemplo de bloco -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-person-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control ps-5" id="nome_sistema" name="nome_sistema" placeholder="Nome do Sistema" value="<?php echo @$nome_sistema ?>" required>
  <label class="color-theme ps-5">Nome do Sistema</label>
  <small class="position-absolute top-50 end-0 translate-middle-y me-3 text-danger" style="font-size: 9px;">(Obrigatório)</small>
</div>

<div class="form-floating mb-3 position-relative">
  <i class="bi bi-envelope-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="email" class="form-control ps-5" id="email_sistema" name="email_sistema" placeholder="Email" value="<?php echo @$email_sistema ?>">
  <label class="color-theme ps-5">Email do Sistema</label>
</div>

<div class="form-floating mb-3 position-relative">
  <i class="bi bi-telephone-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control ps-5" id="telefone_sistema" name="telefone_sistema" placeholder="Telefone" value="<?php echo @$telefone_sistema ?>" required>
  <label class="color-theme ps-5">Telefone do Sistema</label>
</div>

<div class="form-floating mb-3 position-relative">
  <i class="bi bi-building position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control ps-5" id="cnpj_sistema" name="cnpj_sistema" placeholder="CNPJ" value="<?php echo @$cnpj_sistema ?>">
  <label class="color-theme ps-5">CNPJ</label>
</div>

<div class="form-floating mb-3 position-relative">
  <i class="bi bi-geo-alt-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control ps-5" id="endereco_sistema" name="endereco_sistema" placeholder="Endereço" value="<?php echo @$endereco_sistema ?>">
  <label class="color-theme ps-5">(Rua Número Bairro Cidade)</label>
</div>

<!-- Campos de juros, multa, marca d'água -->
<div class="row g-2">
  <div class="col-6">
    <div class="form-floating mb-3">
      <input type="number" class="form-control" id="juros_emprestimo" name="juros_emprestimo" placeholder="%" value="<?php echo @$juros_emprestimo ?>">
      <label>Juros %</label>
    </div>
  </div>

  <div class="col-6">
    <div class="form-floating mb-3">
      <input type="text" class="form-control" id="multa_sistema" name="multa_sistema" placeholder="Multa R$" value="<?php echo @$multa_sistema ?>">
      <label>Multa R$</label>
    </div>
  </div>

  <div class="col-6">
    <div class="form-floating mb-3">
      <input type="text" class="form-control" id="juros_sistema" name="juros_sistema" placeholder="Juros Dia" value="<?php echo @$juros_sistema ?>">
      <label>Juros Dia %</label>
    </div>
  </div>

  <div class="col-6">
    <div class="form-floating mb-3">
      <select class="form-select" name="marca_dagua">
        <option value="Sim" <?php if(@$marca_dagua == 'Sim'){?> selected <?php } ?> >Sim</option>
        <option value="Não" <?php if(@$marca_dagua == 'Não'){?> selected <?php } ?> >Não</option>
      </select>
      <label>Marca D'água</label>
    </div>
  </div>
</div>

<!-- Continuação com API, token, etc -->
<div class="form-floating mb-3">
  <select class="form-select" name="taxa_sistema">
    <option value="Cliente" <?php if(@$taxa_sistema == 'Cliente'){?> selected <?php } ?> >Cliente</option>
    <option value="Empresa" <?php if(@$taxa_sistema == 'Empresa'){?> selected <?php } ?> >Empresa</option>
  </select>
  <label>Taxa Sistema</label>
</div>

<div class="form-floating mb-3">
  <select class="form-select" name="seletor_api">
    <option value="menuia" <?php if(@$seletor_api == 'menuia'){?> selected <?php } ?> >Menuia</option>
    <option value="wm" <?php if(@$seletor_api == 'wm'){?> selected <?php } ?> >WordMensagens</option>
  </select>
  <label>Selecionar API</label>
</div>

<div class="form-floating mb-3">
  <input type="text" class="form-control" id="token" name="token" placeholder="App Key" value="<?php echo @$token ?>">
  <label>Token (appkey)</label>
</div>

<div class="form-floating mb-3">
  <input type="text" class="form-control" id="instancia" name="instancia" placeholder="Auth Key" value="<?php echo @$instancia ?>">
  <label>Instância (authkey)</label>
</div>

<div class="form-floating mb-3">
  <select class="form-select" name="dias_criar_parcelas">
    <option value="Final de Semana" <?php if(@$dias_criar_parcelas == 'Final de Semana'){?> selected <?php } ?> >Sábados e Domingos</option>
    <option value="DomingoSegunda" <?php if(@$dias_criar_parcelas == 'DomingoSegunda'){?> selected <?php } ?> >Domingos e Segundas</option>
    <option value="Domingos" <?php if(@$dias_criar_parcelas == 'Domingos'){?> selected <?php } ?> >Somente Domingos</option>
    <option value="" <?php if(@$dias_criar_parcelas == ''){?> selected <?php } ?> >Criar Todos os Dias</option>
  </select>
  <label>Não Criar Parcelas</label>
</div>

<div class="form-floating mb-3">
  <input type="text" class="form-control" id="pix_sistema" name="pix_sistema" placeholder="Deixar vazio se for usar Mercado Pago" value="<?php echo @$pix_sistema ?>">
  <label>Chave Pix Sistema</label>
</div>

<div class="form-floating mb-3">
  <input type="text" class="form-control" id="saldo_inicial" name="saldo_inicial" placeholder="Saldo inicial sistema" value="<?php echo @$saldo_inicial ?>">
  <label>Saldo Inicial</label>
</div>

<div class="form-floating mb-3">
  <input type="number" class="form-control" id="dias_aviso" name="dias_aviso" placeholder="Lembrar o Vencimento dias antes" value="<?php echo @$dias_aviso ?>">
  <label>Dias Alerta Lembrete</label>
</div>

<div class="form-floating mb-3">
  <select class="form-select" name="recursos">
    <option value="Empréstimos e Cobranças" <?php if(@$recursos == 'Empréstimos e Cobranças'){?> selected <?php } ?> >Empréstimos e Cobranças</option>
    <option value="Empréstimos" <?php if(@$recursos == 'Empréstimos'){?> selected <?php } ?> >Somente Empréstimos</option>
    <option value="Cobranças" <?php if(@$recursos == 'Cobranças'){?> selected <?php } ?> >Somente Cobranças</option>
  </select>
  <label>Recursos Sistema</label>
</div>

<div class="form-floating mb-3">
  <select class="form-select" name="cobrar_automatico">
    <option value="Sim" <?php if(@$cobrar_automatico == 'Sim'){?> selected <?php } ?> >Sim</option>
    <option value="Não" <?php if(@$cobrar_automatico == 'Não'){?> selected <?php } ?> >Não</option>
  </select>
  <label>Cobrar Automáticamente</label>
</div>

<div class="form-floating mb-3">
  <select class="form-select" name="entrada_sistema">
    <option value="Login" <?php if(@$entrada_sistema == 'Login'){?> selected <?php } ?> >Login</option>
    <option value="Site" <?php if(@$entrada_sistema == 'Site'){?> selected <?php } ?> >Site</option>
  </select>
  <label>Entrada Sistema</label>
</div>

<div class="form-floating mb-3">
  <input type="text" class="form-control" id="token" name="public_key" placeholder="Public Key Mercado Pago" value="<?php echo @$public_key ?>">
  <label>Public Key (Mercado Pago)</label>
</div>

<div class="form-floating mb-3">
  <input type="text" class="form-control" id="instancia" name="access_token" placeholder="Access Token Mercado Pago" value="<?php echo @$access_token ?>">
  <label>Access Token (Mercado Pago)</label>
</div>




<div class="row">
  <!-- Logo -->
  <div class="col-6 col-md-4">
    <div class="form-group" align="center" onclick="foto_logo.click()">
      <img src="../../img/<?php echo $logo_sistema ?>" width="80px" id="target-logo"><br>
      <img src="../painel/images/icone-arquivo.png" width="100px" style="margin-top: -12px">
    </div>
    <input class="form-control" type="file" name="foto-logo" onChange="carregarImgLogo();" id="foto_logo" style="display:none">
  </div>

  <!-- Ícone -->
  <div class="col-6 col-md-4">
    <div class="form-group" align="center" onclick="foto_icone.click()">
      <img src="../../img/<?php echo $icone_sistema ?>" width="80px" id="target-icone"><br>
      <img src="../painel/images/icone-arquivo.png" width="100px" style="margin-top: -12px">
    </div>
    <input class="form-control" type="file" name="foto-icone" onChange="carregarImgIcone();" id="foto_icone" style="display:none">
  </div>
</div>

<div class="row" style="margin-top: 20px">
  <!-- Logo Relatório -->
  <div class="col-6 col-md-4">
    <div class="form-group" align="center" onclick="foto_logo_rel.click()">
      <img src="../../img/<?php echo @$logo_rel ?>" width="80px" height="30px" id="target-logo-rel"><br>
      <img src="../painel/images/icone-arquivo.png" width="100px" style="margin-top: -12px">
    </div>
    <input class="form-control" type="file" name="foto-logo-rel" onChange="carregarImgLogoRel();" id="foto_logo_rel" style="display:none">
  </div>

  <!-- Logo Site -->
  <div class="col-6 col-md-4">
    <div class="form-group" align="center" onclick="logo_site.click()">
      
      <img src="../../img/<?php echo $logo_site ?>" width="80px" height="30px" id="target-logo_site"><br>
      <img src="../painel/images/icone-arquivo.png" width="100px" style="margin-top: -12px">

    </div>
    <input class="form-control" type="file" name="logo_site" onChange="carregarLogoSite();" id="logo_site" style="display:none">
    
  </div>
</div>

<div class="row" style="margin-top: 20px">
  <!-- Fundo Login -->
  <div class="col-6 col-md-4">
    <div class="form-group" align="center" onclick="fundo_login.click()">

      <img src="../../img/<?php echo $fundo_login ?>" width="80px" id="target-fundo"><br>
      <img src="../painel/images/icone-arquivo.png" width="100px" style="margin-top: -12px">
    </div>
    <input class="form-control" type="file" name="fundo_login" onChange="carregarImgFundo();" id="fundo_login" style="display:none">
   
  </div>

  <!-- Assinatura -->
  <div class="col-6 col-md-4">
    <div class="form-group" align="center" onclick="foto_assinatura.click()">
      <img src="../../img/<?php echo @$assinatura ?>" width="80px" id="target-assinatura"><br>
      <img src="../painel/images/icone-arquivo.png" width="100px" style="margin-top: -12px">
    </div>
    <input class="form-control" type="file" name="foto-assinatura" onChange="carregarImgAssinatura();" id="foto_assinatura" style="display:none">
  </div>
</div>



     
     
      <button name="btn_salvar" id="btn_salvar_config"
        class="btn btn-full bg-blue-dark rounded-xs text-uppercase font-700 w-100 btn-s mt-4 mb-3" type="submit">SALVAR
      </button>
    </form>
  </div>
</div>




<!-- RELAT FINANC -->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:95%;" id="menu-financ">
  <div class="content ">
    <div class="d-flex pb-0">
      <div class="align-self-center">
        <h1 class="font-14 color-highlight font-700 text-uppercase">RELATÓRIO FINANCEIRO</h1>
      </div>
      <div class="align-self-center ms-auto">
        <a href="#" data-bs-dismiss="offcanvas" class="icon icon-m" id="btn_fechar_baixar"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
    <div class="card overflow-visible rounded-xs">
      <div class="content mb-1">
        <div class="table-responsive">
          <form method="post" action="../../painel/rel/financeiro_class.php">
            

            <div class="row">

  <div class="col-12">
    <div class="form-floating mb-3 position-relative">
      <i class="bi bi-calendar-event position-absolute start-0 top-50 translate-middle-y ms-3"></i>
      <input type="date" class="form-control rounded-xs ps-5" name="dataInicial" id="dataInicial" value="<?php echo $data_atual ?>">
      <label for="dataInicial" class="color-theme ps-5">Data Inicial</label>
    </div>
  </div>

  <div class="col-12">
    <div class="form-floating mb-3 position-relative">
      <i class="bi bi-calendar-event-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
      <input type="date" class="form-control rounded-xs ps-5" name="dataFinal" id="dataFinal" value="<?php echo $data_atual ?>">
      <label for="dataFinal" class="color-theme ps-5">Data Final</label>
    </div>
  </div>

  <div class="col-12">
    <div class="form-floating mb-3 position-relative">
      <i class="bi bi-funnel-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
      <select name="filtro_data" id="filtro_data" class="form-select rounded-xs ps-5 pe-5">
        <option value="data">Data de Lançamento</option>
        <option value="data_venc">Data de Vencimento</option>
        <option value="data_pgto">Data de Pagamento</option>
      </select>
      <label for="filtro_data" class="color-theme ps-5">Filtro por Data</label>
    </div>
  </div>

  <div class="col-12">
    <div class="form-floating mb-3 position-relative">
      <i class="bi bi-arrow-left-right position-absolute start-0 top-50 translate-middle-y ms-3"></i>
      <select name="filtro_tipo" id="filtro_tipo" class="form-select rounded-xs ps-5 pe-5">
        <option value="receber">Entradas / Ganhos</option>
        <option value="pagar">Saídas / Despesas</option>
      </select>
      <label for="filtro_tipo" class="color-theme ps-5">Entradas / Saídas</label>
    </div>
  </div>

  <div class="col-12">
    <div class="form-floating mb-3 position-relative">
      <i class="bi bi-collection-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
      <select name="filtro_lancamento" id="filtro_lancamento" class="form-select rounded-xs ps-5 pe-5">
        <option value="">Tudo</option>
        <option value="Conta">Despesas</option>
        <option value="Empréstimo">Empréstimos</option>
        <option value="Cobrança">Cobranças Recorrentes</option>
      </select>
      <label for="filtro_lancamento" class="color-theme ps-5">Tipo de Lançamento</label>
    </div>
  </div>

  <div class="col-12">
    <div class="form-floating mb-3 position-relative">
      <i class="bi bi-check2-circle position-absolute start-0 top-50 translate-middle-y ms-3"></i>
      <select name="filtro_pendentes" id="filtro_pendentes" class="form-select rounded-xs ps-5 pe-5">
        <option value="">Tudo</option>
        <option value="Não">Pendentes</option>
        <option value="Sim">Pago</option>
      </select>
      <label for="filtro_pendentes" class="color-theme ps-5">Pendentes / Pago</label>
    </div>
  </div>

     <button 
            class="btn btn-full gradient-highlight rounded-xs text-uppercase font-700 w-100 btn-s mt-1 mb-3"
            type="submit">GERAR RELATÓRIO <i class="fa-regular fa-circle-check"></i></button>

</div>
    
        

          </form>
        </div>
      </div>
    </div>
  </div>
</div>









<!-- GESTÃO MENSAGEM -->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:95%;" id="menu-gestao">
  <div class="content ">
    <div class="d-flex pb-0">
      <div class="align-self-center">
        <h1 class="font-14 color-highlight font-700 text-uppercase">GESTÃO DE MENSAGENS</h1>
      </div>
      <div class="align-self-center ms-auto">
        <a href="#" data-bs-dismiss="offcanvas" class="icon icon-m" id="btn_fechar_baixar"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
    <div class="card overflow-visible rounded-xs">
      <div class="content mb-1">
        <div class="table-responsive">
         
          <div class="container-fluid">

  <!-- Verificação de mensagens -->
  <div class="text-center mb-3" id="textos_verificar_mensagens"></div>

  <!-- Informações sobre contas -->
  <div class="row mb-3 text-center">
   
    <div class="col-6">
      <div class="bg-danger text-white rounded p-2">
        <small>Contas Vencidas</small><br>
        <b><span id="contas_vencidas"><?php echo $total_vencidas_msg ?></span></b>
      </div>
    </div>
     <div class="col-6">
      <div class="bg-warning text-dark rounded p-2">
        <small>Vencendo Hoje</small><br>
        <b><span id="contas_vencendo_hoje"><?php echo $total_vencendo_msg ?></span></b>
      </div>
    </div>
  </div>

  <!-- Botões lado a lado sem dropdown e com texto menor -->
<div class="row mb-3">
  <div class="col-6 text-center">
    <button class="btn btn-danger btn-sm w-100" onclick="cobrarTodos()">
      <small><i class="bi bi-exclamation-circle me-1"></i>Cobrar Vencidas</small>
    </button>
  </div>

  <div class="col-6 text-center">
    <button class="btn btn-primary btn-sm w-100" onclick="lembretesTodos()">
      <small><i class="bi bi-bell me-1"></i>Cobrar de Hoje</small>
    </button>
  </div>
</div>


  <hr>

  <!-- Filtros: status e frequência -->
  <div class="row">
    <!-- Status -->
    <div class="col-12 mb-3">
      <div class="d-flex align-items-center mb-2" style="gap: 10px;">
        <label class="mb-0"><small>Todos os Status</small></label>
        <div id="preview_cor_status_index" style="width: 17px; height: 17px; border: 1px solid #ccc; border-radius: 4px;"></div>
      </div>
      <div class="form-floating">
        <select class="form-select" name="status_cliente" id="status_cliente_index" onchange="atualizarCorStatusIndex()">
          <option value="" data-cor="">Selecionar Status</option>
          <?php 
          $query = $pdo->query("SELECT * from status_clientes order by id asc");
          $res = $query->fetchAll(PDO::FETCH_ASSOC);
          foreach ($res as $item) {
            echo '<option value="'.$item['nome'].'" data-cor="'.$item['cor'].'">'.$item['nome'].'</option>';
          }
          ?>
        </select>
        <label for="status_cliente_index">Status</label>
      </div>
    </div>

    <!-- Frequência -->
    <div class="col-12 mb-3">
      <div class="form-floating">
        <select class="form-select" name="frequencia" id="frequencia_index">
          <?php 
          $query = $pdo->query("SELECT * from frequencias order by id asc");
          $res = $query->fetchAll(PDO::FETCH_ASSOC);
          foreach ($res as $item) {
            echo '<option value="'.$item['dias'].'">'.$item['frequencia'].'</option>';
          }
          ?>
        </select>
        <label for="frequencia_index">Frequência</label>
      </div>
    </div>
  </div>

  
</div>


        </div>
      </div>
    </div>
  </div>
</div>











<!--FIM DA PAGINA-->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/swiper.min.js"></script>
<script src="js/ajax.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>



<!-- Mascaras JS -->
<script type="text/javascript" src="js/mascaras.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

<script type="text/javascript">
  $(document).ready(function () {
    $('.sel_nulo').select2({
    });
    $('.sel2').select2({
      dropdownParent: $('#popupForm')
    });
    $('.sel3').select2({
      dropdownParent: $('#popupForm')
    });
    $('.sel4').select2({
      dropdownParent: $('#popupForm')
    });
    $('.sel5').select2({
      dropdownParent: $('#popupForm')
    });
    $('.sel11').select2({
      dropdownParent: $('#popupForm')
    });
  });
</script>

<script>
  function navigateToPage(event, page) {
    event.preventDefault(); // Impede o comportamento padrão do link
    window.location.href = page; // Redireciona para a página especificada

    // Após um pequeno atraso, redireciona para a página correspondente
    setTimeout(function () {
      location.reload(); // Atualiza a página atual
    }, 100); // 100 milissegundos de atraso
  }
</script>


<style type="text/css">
  .select2-selection__rendered {
    line-height: 40px !important;
    font-size: 12px !important;
    color: #666666 !important;
    margin-top: 3px !important;
  }

  .select2-selection {
    height: 50px !important;
    font-size: 10px !important;
    color: #666666 !important;
    border-radius: 10px !important;
    border: 2px solid #e8e8e8 !important;
  }
</style>


<script>
  $("#form-perfil").submit(function () {
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
      url: "../../painel/editar-perfil.php",
      type: 'POST',
      data: formData,
      success: function (mensagem) {
        //ARMAZENAR O RETORNO PARA A MSG DE SUCESSO
			$('#toast-message').text(mensagem.trim());
        if (mensagem.trim() == "Editado com Sucesso") {
          //toast(mensagem, 'verde')
          $('#not_salvar').click();
          $('#btn-fechar-perfil').click();
          //location.reload();
          setTimeout(function () {
            location.reload();
          }, 1000); // 2000 milissegundos = 2 segundos
          
        } else {
          //$('#msg-perfil').addClass('text-danger')
          toast(mensagem, 'vermelha')
        }
      },
      cache: false,
      contentType: false,
      processData: false,
    });
  });


  // FORM CONFIG ==================================
  $("#form-config").submit(function () {
    $('#btn_salvar_config').hide();
    $('#btn_carregando_config').show();
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
      url: "../../painel/editar-config.php",
      type: 'POST',
      data: formData,
      success: function (mensagem) {
        //ARMAZENAR O RETORNO PARA A MSG DE SUCESSO
			$('#toast-message').text(mensagem.trim());
        if (mensagem.trim() == "Editado com Sucesso") {
          //toast(mensagem, 'verde')
          $('#btn-fechar-config').click();
          $('#not_salvar').click();
          setTimeout(function () {
            location.reload();
          }, 1000); // 1000 milissegundos = 1 segundos
        } else {
          //$('#msg-config').addClass('text-danger')
          toast(mensagem, 'vermelha')
        }
        $('#btn_salvar_config').show();
        $('#btn_carregando_config').hide();
      },
      cache: false,
      contentType: false,
      processData: false,
    });
  });
</script>



<script type="text/javascript">
  function carregarImgPerfil() {
    var target = document.getElementById('target_perfil');
    var file = document.querySelector("#foto_perfil").files[0];

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
  function carregarImgLogo() {
    var target = document.getElementById('target-logo');
    var file = document.querySelector("#foto_logo").files[0];

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
  function carregarImgPainel() {
    var target = document.getElementById('target-painel');
    var file = document.querySelector("#foto_painel").files[0];

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
  function carregarImgAssinatura() {
    var target = document.getElementById('target-assinatura');
    var file = document.querySelector("#assinatura_rel").files[0];

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
  function carregarImgLogoRel() {
    var target = document.getElementById('target-rel');
    var file = document.querySelector("#foto_logo_rel").files[0];

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
  function carregarImgIcone() {
    var target = document.getElementById('target-icone');
    var file = document.querySelector("#foto-logo-icone").files[0];

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
  function carregarImgFundo() {
    var target = document.getElementById('target-fundo');
    var file = document.querySelector("#fundo_login").files[0];

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
  function carregarLogoSite() {
    var target = document.getElementById('target-logo_site');
    var file = document.querySelector("#logo_site").files[0];

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

  function limpa_formulário_cep() {
    //Limpa valores do formulário de cep.
    document.getElementById('endereco').value = ("");
    document.getElementById('bairro').value = ("");
    document.getElementById('cidade').value = ("");
    document.getElementById('estado').value = ("");
    //document.getElementById('ibge').value=("");
  }

  function meu_callback(conteudo) {
    if (!("erro" in conteudo)) {
      //Atualiza os campos com os valores.
      document.getElementById('endereco').value = (conteudo.logradouro);
      document.getElementById('bairro').value = (conteudo.bairro);
      document.getElementById('cidade').value = (conteudo.localidade);
      document.getElementById('estado').value = (conteudo.uf);
      //document.getElementById('ibge').value=(conteudo.ibge);
    } //end if.
    else {
      //CEP não Encontrado.
      limpa_formulário_cep();
      alert("CEP não encontrado.");
    }
  }

  function pesquisacep(valor) {

    //Nova variável "cep" somente com dígitos.
    var cep = valor.replace(/\D/g, '');

    //Verifica se campo cep possui valor informado.
    if (cep != "") {

      //Expressão regular para validar o CEP.
      var validacep = /^[0-9]{8}$/;

      //Valida o formato do CEP.
      if (validacep.test(cep)) {

        //Preenche os campos com "..." enquanto consulta webservice.
        document.getElementById('endereco').value = "...";
        document.getElementById('bairro').value = "...";
        document.getElementById('cidade').value = "...";
        document.getElementById('estado').value = "...";
        //document.getElementById('ibge').value="...";

        //Cria um elemento javascript.
        var script = document.createElement('script');

        //Sincroniza com o callback.
        script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';

        //Insere script no documento e carrega o conteúdo.
        document.body.appendChild(script);

      } //end if.
      else {
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


</script>

<script>
function atualizarCorStatusIndex() {
    var select = document.getElementById('status_cliente_index');
    var corSelecionada = select.options[select.selectedIndex].getAttribute('data-cor');
    var preview = document.getElementById('preview_cor_status_index');

    if(corSelecionada) {
        preview.style.backgroundColor = corSelecionada;
    } else {
        preview.style.backgroundColor = "#ffffff";
    }
}
</script>






<script type="text/javascript">
  

function cobrarTodos() {
  var frequencia_index = $('#frequencia_index').val();
    var status_cliente_index = $('#status_cliente_index').val();

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success", // Adiciona margem à direita do botão "Sim, Excluir!"
            cancelButton: "btn btn-danger me-1",
            container: 'swal-whatsapp-container'
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: "Deseja Enviar as Cobranças?",
        text: "Vai ser enviado para todos com débito!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim, Enviar!",
        cancelButtonText: "Não, Cancelar!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Realiza a requisição AJAX para excluir o item
            $.ajax({
                url: '../../painel/cobrar_todos.php',
                method: 'POST',
                data: { frequencia_index, status_cliente_index },
                dataType: "html",
                success: function (mensagem) {
                    
                        // Exibe mensagem de sucesso após a exclusão
                        swalWithBootstrapButtons.fire({
                            title: 'Sucesso!',
                            text: 'Excluido com sucesso!',
                            icon: "success",
                            timer: 500,
                            timerProgressBar: true,
                            confirmButtonText: 'OK',

                        });
                      
                       // Exibe mensagem de erro se a requisição falhar
                        swalWithBootstrapButtons.fire({
                            title: "Agendados!!",
                            text: mensagem,
                            icon: "success",
                            confirmButtonText: 'OK',
                        });
                  
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "Fecharei em 1 segundo.",
                icon: "error",
                timer: 1000,
                timerProgressBar: true,
            });
        }
    });
}
</script>




<script type="text/javascript">
  

function lembretesTodos() {
  var frequencia_index = $('#frequencia_index').val();
    var status_cliente_index = $('#status_cliente_index').val();

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success", // Adiciona margem à direita do botão "Sim, Excluir!"
            cancelButton: "btn btn-danger me-1",
            container: 'swal-whatsapp-container'
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: "Deseja Enviar os Lembretes?",
        text: "Todos com contas vencendo hoje!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim, Enviar!",
        cancelButtonText: "Não, Cancelar!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Realiza a requisição AJAX para excluir o item
            $.ajax({
                url: '../../painel/lembretes_todos.php',
                method: 'POST',
                data: { frequencia_index, status_cliente_index },
                dataType: "html",
                success: function (mensagem) {
                    
                        // Exibe mensagem de sucesso após a exclusão
                        swalWithBootstrapButtons.fire({
                            title: 'Sucesso!',
                            text: 'Excluido com sucesso!',
                            icon: "success",
                            timer: 500,
                            timerProgressBar: true,
                            confirmButtonText: 'OK',

                        });
                      
                       // Exibe mensagem de erro se a requisição falhar
                        swalWithBootstrapButtons.fire({
                            title: "Agendados!!",
                            text: mensagem,
                            icon: "success",
                            confirmButtonText: 'OK',
                        });
                  
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "Fecharei em 1 segundo.",
                icon: "error",
                timer: 1000,
                timerProgressBar: true,
            });
        }
    });
}
</script>