<?php
require_once("cabecalho.php");
require_once("rodape.php");
@session_start();
$id_usuario = @$_SESSION['id'];



$pag = 'solicitar_emprestimo';
$itens_pag = 10;


if (@$usuarios == 'ocultar') {
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
$query2 = $pdo->query("SELECT * from $pag where cliente = '$id_usuario' order by id desc");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$linhas2 = @count($res2);

$num_paginas = ceil($linhas2 / $itens_pag);
if ($pag_proxima == $num_paginas) {
  $pag_inativa_prox = 'desabilitar_botao';
  $pag_proxima = $pagina;
} else {
  $pag_inativa_prox = '';
}

$data_hoje = date('Y-m-d');

?>



<div class="page-content header-clear-medium">


  <div class="card card-style" id="listar">
    <div class="content">


      <?php
      $query = $pdo->query("SELECT * from solicitar_emprestimo where id > 0 and cliente = '$id_usuario'  order by id desc LIMIT $limite, $itens_pag");
      $res = $query->fetchAll(PDO::FETCH_ASSOC);
      $linhas = @count($res);
      if ($linhas > 0) {
        for ($i = 0; $i < $linhas; $i++) {
        $id = $res[$i]['id']; 
  $cliente = $res[$i]['cliente'];
  $valor = $res[$i]['valor'];
  $data = $res[$i]['data'];
  $parcelas = $res[$i]['parcelas']; 
  $obs = $res[$i]['obs'];
  $garantia = $res[$i]['garantia'];
  $status = $res[$i]['status'];
  

$query2 = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = @$res2[0]['nome'];

  $dataF = implode('/', array_reverse(@explode('-', $data)));
  
  $valorF = number_format($valor, 2, ',', '.');

  if($status == 'Pendente'){
    $classe_square = 'vermelho.jpg';
    $classe_baixar = 'ocultar';
    $icone = 'bi-check-square';
    $titulo_link = 'Marcar como Pendente';
    $acao = 'Concluída';
    $bg_square = 'bg-danger';
    
  }else{
    $classe_square = 'verde.jpg';
    $classe_baixar = '';
    $icone = 'bi-square';
    $titulo_link = 'Marcar como Concluída';
    $acao = 'Pendente';
    $bg_square = 'bg-success';
    
  }


          echo <<<HTML

     <div data-splide='{"autoplay":false}' class="splide single-slider slider-no-arrows slider-no-dots" id="user-slider-{$id}">
  <div class="splide__track">
    <div class="splide__list">
      <div class="splide__slide mx-3">
        <div class="d-flex">
          <div>            
            <h5 class="mt-0 mb-1" style="font-size: 12px;"><img src="images/{$classe_square}" width="12px" style="float:left; margin-right: 2px; margin-top: 3px">{$nome_cliente}</h5>
            <p class="font-12 mt-n2 mb-0"><span class="text-success">R$ {$valorF} </span> / {$parcelas} Parcelas </p>            
            <p class="font-10 mt-n2 mb-n2">Garantia: {$garantia}</p>

          </div>
          <div class="ms-auto"><span class="slider-next "><span class="badge px-2 py-1 {$bg_square} shadow-bg shadow-bg-s">{$status}</span></span>
          </div>
        </div>
      </div>
      <div class="splide__slide mx-3">
        <div class="d-flex">
          
          <div class="ms-auto">           
           
                     <a onclick="editar('{$id}','{$valorF}','{$parcelas}','{$data}','{$obs}','{$garantia}')" href="#" class="icon icon-xs rounded-circle shadow-l bg-twitter"><i class="fa fa-edit text-white"></i></a>

            <a onclick="excluir_reg('{$id}', '{$nome_cliente}')" href="#" class=" icon icon-xs rounded-circle shadow-l bg-google"><i class="bi bi-trash-fill text-white"></i></a>
           

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


</div>


<div class="fab" style="z-index: 100 !important; margin-bottom: 60px">
  <button id="btn_novo" class="main open-popup bg-highlight" data-bs-toggle="offcanvas" data-bs-target="#popupForm">
    +
  </button>
</div>



<div hidden="hidden" class="fab" style="z-index: 100 !important; margin-bottom: 60px">
  <button id="btn_novo_editar" class="main open-popup bg-highlight" data-bs-toggle="offcanvas"
    data-bs-target="#popupForm">

  </button>
</div>



<!-- MODAL USUÁRIOS -->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:96%" id="popupForm">
  <div class="content mb-0">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h2 align="center" class="mb-n1 font-12 color-highlight font-700 text-uppercase pt-1" id="titulo_inserir">
          SOLICITAR EMPRÉSTIMO</h2>
      </div>
      <div class="align-self-center ms-auto">
        <a onclick="limparCampos()" href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
    <form id="form_solic" method="post" class="demo-animation m-0">

    
    <!-- VALOR -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-currency-dollar position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="valor" name="valor" placeholder="Valor" required onkeyup="mascara_valor('valor')">
  <label class="color-theme ps-5">Valor</label>
  <small class="position-absolute top-50 end-0 translate-middle-y me-3 text-danger"
          style="font-size: 9px;">(Obrigatório)</small>
</div>

<!-- PARCELAS -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-list-ol position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="number" class="form-control rounded-xs ps-5" id="parcelas" name="parcelas" placeholder="Parcelas" required>
  <label class="color-theme ps-5">Parcelas</label>
  <small class="position-absolute top-50 end-0 translate-middle-y me-3 text-danger"
          style="font-size: 9px;">(Obrigatório)</small>
</div>

<!-- DATA -->
<div class="form-floating mb-3 position-relative">
        <i class="bi bi-calendar position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="date" class="form-control rounded-xs ps-5" id="data_solic" name="data"
          value="<?php echo $data_hoje ?>" required>
        <label class="color-theme ps-5">Data</label>
        <small class="position-absolute top-50 end-0 translate-middle-y me-3 text-danger"
          style="font-size: 9px;">(Obrigatório)</small>
      </div>



<!-- OBSERVAÇÕES -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-chat-left-text position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="obs" name="obs" placeholder="Observações">
  <label class="color-theme ps-5">Observações</label>
</div>

<!-- GARANTIA -->
<div class="form-floating mb-3 position-relative">
  <i class="bi bi-shield-check position-absolute start-0 top-50 translate-middle-y ms-3"></i>
  <input type="text" class="form-control rounded-xs ps-5" id="garantia" name="garantia" placeholder="Qual Garantia?">
  <label class="color-theme ps-5">Garantia (se necessário)</label>
</div>

<!-- HIDDEN FIELDS -->

<input type="hidden" id="cliente" name="cliente" value="<?php echo $id_usuario ?>">


      <button name="btn_salvar" id="btn_salvar_solic"
        class="btn btn-full gradient-highlight rounded-xs text-uppercase font-700 w-100 btn-s mt-4 mb-3"
        type="submit">Salvar</button>
      <div align="center" style="display:none" id="img_loader_usuarios"><img src="images/loader.gif"></div>
      <input type="hidden" name="id" id="id">
    </form>
  </div>
</div>



<!--BOTÃO PARA CHAMAR A MODAL MOSTRAR -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-mostrar" class="list-group-item"
  id="btn_mostrar">
</a>



<!--BOTÃO PARA CHAMAR A MODAL PERMISSÕES -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-acessos" class="list-group-item"
  id="btn_acessos">
</a>



<script>
  function editar(id, valor, parcela, data, obs, garantia) {
    $('#mensagem').text('');
    $('#titulo_inserir').text('EDITAR REGISTRO');


    $('#id').val(id);
   $('#parcelas').val(parcela);
      $('#valor').val(valor);
      $('#data_solic').val(data);
      $('#garantia').val(garantia);
      $('#obs').val(obs);
   
    
    $('#btn_novo_editar').click();
  }



</script>


<script type="text/javascript">
  
  function limparCampos() {
    $('#id').val('');
   $('#parcelas').val('');
      $('#valor').val('');
      $('#garantia').val('');
      $('#data_solic').val("<?=$data_hoje?>");     
      $('#obs').val('');
   
  }


  function paginar(pag, busca) {
    $('#input_pagina').val(pag);
    $('#input_buscar').val(busca);
    $('#paginacao').click();
  }
</script>






<script type="text/javascript">
  var pag = "<?= $pag ?>"
</script>