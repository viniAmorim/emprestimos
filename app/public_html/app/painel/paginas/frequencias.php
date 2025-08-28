<?php
require_once("cabecalho.php");
require_once("rodape.php");

$pag = 'frequencias';
$itens_pag = 10;

if (@$frequencias == 'ocultar') {
  echo "<script>window.location='index'</script>";
  exit();
}

// pegar a pagina atual
if (@$_POST['pagina'] == "") {
  @$_POST['pagina'] = 0;
}
$pagina = intval(@$_POST['pagina']);
$limite = $pagina * $itens_pag;

$numero_pagina = $pagina + 1;

if ($pagina > 0) {
  $pag_anterior = $pagina - 1;
  $pag_inativa_ant = '';
} else {
  $pag_anterior = $pagina;
  $pag_inativa_ant = 'desabilitar_botao';
}

$pag_proxima = $pagina + 1;


//totalizar páginas
$query2 = $pdo->query("SELECT * from frequencias where frequencia like '%$buscar%' order by id desc");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$linhas2 = @count($res2);

$num_paginas = ceil($linhas2 / $itens_pag);
if ($pag_proxima == $num_paginas) {
  $pag_inativa_prox = 'desabilitar_botao';
  $pag_proxima = $pagina;
} else {
  $pag_inativa_prox = '';

}

?>

<div class="page-content header-clear-medium">

  <!-- BARRA DE PESQUISA-->
  <div class="content m-2 p-1">
    <div class="loginform">
      <form method="post">
        <div class="search-box search-color bg-theme rounded-xl">
          <input type="text" name="buscar" id="buscar" value="<?php echo $buscar ?>" class="form_input required p-1"
            placeholder="Buscar <?php echo ucfirst($pag); ?>"
            style="background: transparent !important; width:86%; float:left; border: none !important" />
          <button id="btn_filtrar" class="limpar_botao" type="submit"><img src="images/icons/black/search.png"
              width="20px" style="float:left; margin-top: 5px"></button>
        </div>
      </form>
    </div>
  </div>


  <div class="card card-style" id="listar">
    <div class="content">


      <?php
      $query = $pdo->query("SELECT * from frequencias where frequencia like '%$buscar%'  order by id desc LIMIT $limite, $itens_pag");
      $res = $query->fetchAll(PDO::FETCH_ASSOC);
      $linhas = @count($res);
      if ($linhas > 0) {
        for ($i = 0; $i < $linhas; $i++) {
          $id = $res[$i]['id'];
          $frequencia = $res[$i]['frequencia'];
          $dias = $res[$i]['dias'];

          echo <<<HTML

      <div data-splide='{"autoplay":false}' class="splide single-slider slider-no-arrows slider-no-dots"
        id="user-slider-{$id}">
        <div class="splide__track">
          <div class="splide__list">
            <div class="splide__slide mx-3">
              <div class="d-flex">
                <div>
                  <h6 class="mt-1 mb-0">{$frequencia}</h6>
                  <p class="font-10 mt-n1 color-red-dark">Dias: {$dias}%</p>
                </div>
                <div class="ms-auto"><span class="slider-next badge bg-highlight mt-2 p-2 font-8 shadow-bg-s">AÇÕES</span></div>
              </div>
            </div>
            <div class="splide__slide mx-3">
              <div class="d-flex">

                <div class="ms-auto">
                  <a onclick="editar('{$id}','{$frequencia}','{$dias}')"  href="#" class="icon icon-xs rounded-circle shadow-l bg-twitter"><i
                      class="fa fa-edit text-white"></i></a>
                  <a onclick="excluir_reg('{$id}', '{$frequencia}')" href="#" class="icon icon-xs rounded-circle shadow-l bg-google"><i
                      class="bi bi-trash-fill text-white"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="divider mt-3 mb-3"></div>
HTML;
        }
      } else {
        echo 'Nenhum Registro Encontrado!';
      }
      ?>
      <form method="post" style="display:none">
        <input type="text" name="pagina" id="input_pagina">
        <input type="text" name="buscar" id="input_buscar">
        <button type="submit" id="paginacao"></button>
      </form>
    </div>
  </div>
  <!-- PAGINAÇÃO -->
  <nav aria-label="pagination-demo">
    <ul class="pagination pagination- justify-content-center">
      <li class="page-item">
        <a onclick="paginar('<?php echo $pag_anterior ?>', '<?php echo $buscar ?>')"
          class="page-link py-2 rounded-xs color-black bg-transparent bg-theme shadow-xl border-0 <?php echo $pag_inativa_ant ?>"
          href="#" tabindex="-1" aria-disabled="true"><i class="bi bi-chevron-left"></i></a>
      </li>
      <li class="page-item"><a class="page-link py-2 rounded-xs shadow-l border-0 color-dark"
          href="#"><?php echo @$numero_pagina ?> /
          <?php echo @$num_paginas ?></span></a>
      </li>
      <li class="page-item">
        <a onclick="paginar('<?php echo $pag_proxima ?>', '<?php echo $buscar ?>')"
          class="page-link py-2 rounded-xs color-black bg-transparent bg-theme shadow-l border-0 <?php echo $pag_inativa_prox ?>"
          href="#"><i class="bi bi-chevron-right"></i>
        </a>
      </li>
    </ul>
  </nav>
</div>


<div class="fab" style="z-index: 100 !important; margin-bottom: 60px">
  <button onclick="limparCampos()" id="btn_novo" class="main open-popup bg-highlight" data-bs-toggle="offcanvas"
    data-bs-target="#popupForm">
    +
  </button>
</div>


<div hidden="hidden" class="fab" style="z-index: 100 !important; margin-bottom: 60px">
  <button id="btn_novo_editar" class="main open-popup bg-highlight" data-bs-toggle="offcanvas"
    data-bs-target="#popupForm">

  </button>
</div>


<!-- MODAL CADASTRO-->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:50%" id="popupForm">
  <div class="content mb-0">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h2 align="center" class="mb-n1 font-12 color-highlight font-700 text-uppercase pt-1" id="titulo_inserir">
          INSERIR DADOS</h2>
      </div>
      <div class="align-self-center ms-auto">
        <button onclick="limparCampos()" style="border: none; background: transparent; margin-right: 12px"
          data-bs-dismiss="offcanvas" id="btn-fechar" aria-label="Close" data-bs-dismiss="modal" type="button"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i>
        </button>
      </div>
    </div>
    <form id="form" method="post" class="demo-animation needs-validation m-0">
      <div class="row">
        <div class="col-8">

        <div class="form-floating mb-3 position-relative">
        <i class="bi bi-calendar-check position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="frequencia" name="frequencia" placeholder="" required>
        <label for="nome" class="color-theme ps-5">Frequência</label>
        <small class="position-absolute top-50 end-0 translate-middle-y me-3 text-danger"
          style="font-size: 9px;">(Obrigatório)</small>
      </div>
        </div>
        <div class="col-4">
         <div class="form-floating mb-3 position-relative">
        <i class="fa-solid fa-list-ol position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="dias" name="dias" placeholder="" required>
        <label for="nome" class="color-theme ps-5">Dias</label>
        <small class="position-absolute top-50 end-0 translate-middle-y me-3 text-danger"
          style="font-size: 9px;">(*)</small>
      </div>
        </div>
      </div>
      <button name="btn_salvar" id="btn_salvar"
        class="btn btn-full gradient-highlight rounded-xs text-uppercase font-700 w-100 btn-s mt-4 mb-3"
        type="submit">SALVAR <i class="fa-regular fa-circle-check"></i></button>
      <button class="btn btn-full gradient-highlight rounded-xs text-uppercase font-700 w-100 btn-s mt-4 mb-3"
        type="button" id="btn_carregando" style="display: none">
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando...
      </button>
      <input type="hidden" name="id" id="id">
    </form>
  </div>
</div>


<script>
  function editar(id, frequencia, dias) {
    $('#mensagem').text('');
    $('#titulo_inserir').text('EDITAR REGISTRO');
    $('#id').val(id);
    $('#frequencia').val(frequencia);
    $('#dias').val(dias);
    $('#btn_novo_editar').click();
  }
</script>


<script type="text/javascript">
  function limparCampos() {
    $('#id').val('');
    $('#frequencia').val('');
    $('#dias').val('');
  }

  function paginar(pag, busca) {
    $('#input_pagina').val(pag);
    $('#input_buscar').val(busca);
    $('#paginacao').click();
  }
</script>

<script type="text/javascript">var pag = "<?= $pag ?>"</script>