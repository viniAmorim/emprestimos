<?php
require_once("cabecalho.php");
require_once("rodape.php");

$data_atual = date('Y-m-d');

$pag = 'receber';
$itens_pag = 10;

if (@$receber == 'ocultar') {
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

$checked_vencidas = '';
$checked_pagas = '';
$checked_pendentes = '';
$checked_todas = '';
if (@$_POST['pago'] == "Vencidas") {
  $checked_vencidas = 'true';
} else if (@$_POST['pago'] == "Sim") {
  $checked_pagas = 'true';
} else if (@$_POST['pago'] == "Não") {
  $checked_pendentes = 'true';
} else {
  $checked_todas = 'true';
}

if ($dataInicial == "") {
  $dataInicial = $data_inicio_mes;
}

if ($dataFinal == "") {
  $dataFinal = $data_final_mes;
}

$total_pago = 0;
$total_pendentes = 0;

$total_pagoF = 0;
$total_pendentesF = 0;

//totalizar páginas
if ($pago == 'Vencidas') {
  $query2 = $pdo->query("SELECT * from $pag where data_venc < curDate() and pago != 'Sim' order by id desc");
} else {
  $query2 = $pdo->query("SELECT * from $pag where data_venc >= '$dataInicial' and data_venc <= '$dataFinal' and pago LIKE '%$pago%' order by id desc");
}



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


  <div class="card card-style">
    <div class="content">
      <?php
     
      $query = $pdo->query("SELECT * from $pag where data_venc < curDate() and pago != 'Sim' order by id desc LIMIT $limite, $itens_pag");
      
      $valor_pago = 0;
      $valor_pendentes = 0;
      $res = $query->fetchAll(PDO::FETCH_ASSOC);
      $linhas = @count($res);
      if ($linhas > 0) {
        for ($i = 0; $i < $linhas; $i++) {
          $id = $res[$i]['id']; 
  $descricao = $res[$i]['descricao'];
  $valor = $res[$i]['valor'];
  $data = $res[$i]['data'];
  $data_venc = $res[$i]['data_venc']; 
  $data_pgto = $res[$i]['data_pgto'];
  $usuario_lanc = $res[$i]['usuario_lanc'];
  $usuario_baixa = $res[$i]['usuario_pgto'];
  $referencia = $res[$i]['referencia'];
  $id_ref = $res[$i]['id_ref'];
  $pago = $res[$i]['pago'];
  $obs = $res[$i]['obs'];
   $cliente = $res[$i]['cliente'];

   $query2 = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = @$res2[0]['nome'];


$query2 = $pdo->query("SELECT * from usuarios where id = '$usuario_lanc'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_usuario_lanc = @$res2[0]['nome'];

$query2 = $pdo->query("SELECT * from usuarios where id = '$usuario_baixa'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_usuario_baixa = @$res2[0]['nome'];

  $data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));
  $data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
  $dataF = implode('/', array_reverse(@explode('-', $data)));
  
  $valorF = number_format($valor, 2, ',', '.');

  if($pago == 'Sim'){
    $classe_square = 'verde';
    $classe_baixar = 'ocultar';
    $valor_pago += $valor;
  }else{
    $classe_square = 'text-danger';
    $classe_baixar = '';
    $valor_pendentes += $valor;
  }

  $valor_pagoF = @number_format($valor_pago, 2, ',', '.');
  $valor_pendentesF = @number_format($valor_pendentes, 2, ',', '.');


  if ($pago == 'Sim') {
            $classe_pago = 'verde.jpg';
            $ocultar = 'ocultar';
            $total_pago += $valor;
            $ocultar_pendentes = '';
            $txt_venc = 'Pago';
            $cor_venc = 'gradient-green';
          } else if (strtotime($data_venc) == strtotime($data_hoje) and $pago != 'Sim') {
            $classe_pago = 'amarelo.jpg';
            $txt_venc = 'Venc. Hoje';
            $cor_venc = 'gradient-yellow';
            $ocultar = '';
            $ocultar_pendentes = '';
          } else if ($pago != 'Sim' and strtotime($data_venc) > strtotime($data_hoje)) {
            $txt_venc = 'No prazo';
            $cor_venc = 'gradient-blue';
            $classe_pago = 'azul.jpg';
            $ocultar = '';
            $ocultar_pendentes = '';
          } else {
            $classe_pago = 'vermelho.jpg';
            $ocultar = '';
            $total_pendentes += $valor;
            $ocultar_pendentes = 'ocultar';
            $txt_venc = 'Atrasada';
            $cor_venc = 'gradient-red';
          }

          $descricaoF = mb_strimwidth($descricao, 0, 40, "...");


$valor_multa = 0;
$valor_juros = 0;
$dias_vencido = 0;
$classe_venc = '';
if(@strtotime($data_venc) < @strtotime($data_atual) and $pago != 'Sim'){
$classe_venc = 'text-danger';
$valor_multa = @$multa_sistema;

//calcular quanto dias está atrasado

$data_inicio = new DateTime($data_venc);
$data_fim = new DateTime($data_atual);
$dateInterval = $data_inicio->diff($data_fim);
$dias_vencido = $dateInterval->days;

$valor_juros = $dias_vencido * ($juros_sistema * $valor / 100);

$valor_final = $valor;

}else{
  $valor_final = $valor;
}

$valor_finalF = @number_format($valor_final, 2, ',', '.');

          echo <<<HTML


<div data-splide='{"autoplay":false}' class="splide single-slider slider-no-arrows slider-no-dots" id="user-slider-{$id}">
  <div class="splide__track">
    <div class="splide__list">
      <div class="splide__slide mx-3">
        <div class="d-flex">
          <div
            onclick="mostrar('{$descricao}','{$valor_finalF}','{$data_vencF}','{$data_pgtoF}','{$nome_usuario_lanc}','{$nome_usuario_baixa}','{$pago}','{$referencia}','{$obs}','{$nome_cliente}')">
            
            <h5 class="mt-0 mb-1" style="font-size: 12px;"><img src="images/{$classe_pago}" width="12px" style="float:left; margin-right: 2px; margin-top: 3px">{$descricaoF}</h5>
            <p class="font-10 mt-n2 {$classe_venc} mb-0">R$ {$valor_finalF}  </p>
            <p class="font-10 mt-n2 mb-n2">Venc: {$data_vencF}</p>

          </div>
          <div class="ms-auto"><span class="slider-next "><span class="badge px-2 py-1 {$cor_venc} shadow-bg shadow-bg-s">{$txt_venc}</span></span>
          </div>
        </div>
      </div>
      <div class="splide__slide mx-3">
        <div class="d-flex">
          <div
            onclick="mostrar('{$descricao}','{$valor_finalF}','{$data_vencF}','{$data_pgtoF}','{$nome_usuario_lanc}','{$nome_usuario_baixa}','{$pago}','{$referencia}','{$obs}','{$nome_cliente}')">
           
          </div>
          <div class="ms-auto">
            <a onclick="editar('{$id}','{$descricao}','{$valor}','{$data_venc}','{$obs}','{$cliente}')" href="#" class="{$ocultar} icon icon-xs rounded-circle shadow-l bg-twitter"><i class="fa fa-edit text-white"></i></a>
           
            <a onclick="baixarConta('{$id}')" href="#" class="{$ocultar} icon icon-xs rounded-circle shadow-l bg-green"><i class="bi bi-check-square-fill text-white"></i></a>

            <a onclick="arquivo('{$id}', '{$descricao}')" href="#" class="icon icon-xs rounded-circle shadow-l bg-dark"><i class="fa fa-file text-white"></i></a>

            <a onclick="excluir_reg('{$id}', '{$descricao}')" href="#" class="{$ocultar} icon icon-xs rounded-circle shadow-l bg-google"><i class="bi bi-trash-fill text-white"></i></a>
           

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
      <div class="divider mt-3 mb-3 "></div>
HTML;
        }
      } else {
        echo 'Nenhum Registro Encontrado!';
      }
      ?>

      <div align="right" style="font-size:13px; margin-top: 10px">
        <span style="margin-right: 10px">Total Pendentes <span style="color:red">R$ <?php echo $total_pendentesF ?>
          </span></span>
        <span>Total Pago <span style="color:green">R$ <?php echo $total_pagoF ?> </span></span>
      </div>

      
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
          href="#"><?php echo @$numero_pagina ?> / <?php echo @$num_paginas ?></span></a>
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
  <button id="btn_novo" onclick="limparCampos()" class="main open-popup bg-highlight" data-bs-toggle="offcanvas"
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
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:100%" id="popupForm">
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


         <div class="form-floating mb-3 position-relative">

    <!-- Select com ícone esquerdo -->
    <div class="form-floating flex-grow-1 position-relative">
      <i class="bi bi-info-circle-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>

      <select class="sel2 rounded-xs ps-5 pe-5" name="cliente" id="cliente" style="width:100%;">
        <option value="" data-cor="">Selecionar Cliente</option>
        <?php 
          $query = $pdo->query("SELECT * from clientes order by id asc");
          $res = $query->fetchAll(PDO::FETCH_ASSOC);
          $linhas = @count($res);
          if($linhas > 0){
            for($i=0; $i<$linhas; $i++){
        ?>
          <option value="<?= $res[$i]['id'] ?>" <?php if($cliente == $res[$i]['id']){ ?> selected <?php } ?> ><?= $res[$i]['nome'] ?></option>
        <?php } } ?>
      </select>
    </div>

   

  </div>

    <div class="form-floating mb-3 position-relative">
        <i class="fa-solid fa-pen position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="descricao" name="descricao" placeholder="" required>
        <label for="nome" class="color-theme ps-5">Descrição</label>
        <small class="position-absolute top-50 end-0 translate-middle-y me-3 text-danger"
          style="font-size: 9px;">(Obrigatório)</small>
      </div>
      
    
      <div class="form-floating mb-3 position-relative">
        <i class="bi bi-currency-dollar position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="valor" name="valor" placeholder=""
         oninput="mascaraMoeda(this)" required>
        <label class="color-theme ps-5">Valor</label>
        <small class="position-absolute top-50 end-0 translate-middle-y me-3 text-danger"
          style="font-size: 9px;">(Obrigatório)</small>
      </div>
     
      <div class="form-floating mb-3 position-relative">
        <i class="bi bi-calendar position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="date" class="form-control rounded-xs ps-5" id="data_venc" name="data_venc"
          value="<?php echo $data_hoje ?>" required>
        <label class="color-theme ps-5">Vencimento</label>
        <small class="position-absolute top-50 end-0 translate-middle-y me-3 text-danger"
          style="font-size: 9px;">(Obrigatório)</small>
      </div>
    
    
   
      <div class="form-floating mb-3 position-relative">
        <i class="bi bi-pencil-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="obs" name="obs" placeholder="">
        <label class="color-theme ps-5">Observações</label>
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





<!--BOTÃO PARA CHAMAR A MODAL MOSTRAR -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-mostrar" class="list-group-item"
  id="btn_mostrar">
</a>

<!-- MODAL MOSTRAR -->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:98%" id="menu-share-mostrar">
  <div class="content ">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h1 class="font-11 color-highlight font-700 text-uppercase" id="titulo_dados"></h1>
      </div>
      <div class="align-self-center ms-auto">
        <a href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
    <div class="card overflow-visible">
      <div class="content mb-1">
        <div class="table-responsive">
          <table class="table color-theme mb-4">
            <tbody>
              <tr class="border-fade-blue">
                <td style="font-size: 11px;" class="color-highlight">Valor:</td>
                <td style="font-size: 11px;" id="valor_dados"></td>
              </tr>

              <tr class="border-fade-blue">
                <td style="font-size: 11px;" class="color-highlight">Cliente:</td>
                <td style="font-size: 11px;" id="cliente_dados"></td>
              </tr>
              
              <tr class="border-fade-blue">
                <td style="font-size: 11px;" class="color-highlight">Vencimento:</td>
                <td style="font-size: 11px;" id="data_venc_dados"></td>
              </tr>
             
              <tr class="border-fade-blue">
                <td style="font-size: 11px;" class="color-highlight">Data PGTO:</td>
                <td style="font-size: 11px;" id="data_pgto_dados"></td>
              </tr>           
             
              <tr class="border-fade-blue">
                <td style="font-size: 11px;" class="color-highlight">Lançador Por:</td>
                <td style="font-size: 11px;" id="nome_usuario_lanc_dados"></td>
              </tr>
              <tr class="border-fade-blue">
                <td style="font-size: 11px;" class="color-highlight">Pago Por:</td>
                <td style="font-size: 11px;" id="nome_usuario_baixa_dados"></td>
              </tr>
              <tr class="border-fade-blue">
                <td style="font-size: 11px;" class="color-highlight">Pago:</td>
                <td style="font-size: 11px;" id="pago_dados"></td>
              </tr>

              <tr class="border-fade-blue">
                <td style="font-size: 11px;" class="color-highlight">Referencia:</td>
                <td style="font-size: 11px;" id="referencia_dados"></td>
              </tr>

              <tr class="border-fade-blue">
                <td style="font-size: 11px;" class="color-highlight">OBS:</td>
                <td style="font-size: 11px;" id="obs_dados"></td>
              </tr>
            </tbody>
          </table>
         
        </div>
      </div>
    </div>
  </div>
</div>



<!--BOTÃO PARA CHAMAR A MODAL ARQUIVOS -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-arquivos"
  class="list-group-item" id="btn_arquivos" data-bs-target="#staticBackdrop">
</a>

<!-- MODAL ARQUIVOS -->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:70%;" id="menu-share-arquivos">
  <div class="content ">
    <div class="d-flex pb-0">
      <div class="align-self-center">
        <h1 class="font-14 color-highlight font-700 text-uppercase" id="titulo_arquivo"></h1>
      </div>
      <div class="align-self-center ms-auto">
        <a href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
    <div class="card overflow-visible rounded-xs">
      <div class="content mb-1">
        <div class="table-responsive">
          <table class="table color-theme mb-4">
            <form id="form_arquivos" method="post" style="padding-bottom: 17px; border:1px solid #bdbbbb">
              <div align="center" onclick="arquivo_conta.click()" style="padding-top: 10px; padding-bottom: 10px">
                <img src="images/sem-foto.png" width="85px" id="target-arquivos"><br>
                <img src="images/icone-arquivo.png" width="85px" style="margin-top: -12px">
              </div>
              <input onchange="carregarImgArquivos()" type="file" name="arquivo_conta" id="arquivo_conta"
                hidden="hidden">
             <div class="form-floating mb-3 position-relative">
                  <i class="bi bi-pencil-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
                  <input type="text" class="form-control rounded-xs ps-5" id="nome_arq" name="nome_arq" placeholder="" required>
                  <label class="color-theme ps-5">Nome do Arquvio</label>
                </div>
              <button name="btn_salvar_arquivo" id="btn_salvar_arquivo"
                class="btn btn-full gradient-green rounded-xs text-uppercase w-100 btn-s mt-2" type="submit">Salva <i
                  class="fa-regular fa-circle-check"></i></button>
              <button class="btn btn-full gradient-highlight rounded-xs text-uppercase font-700 w-100 btn-s mt-4 mb-3"
                type="button" id="btn_carregando_arquivo" style="display: none">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando...
              </button>
              <input type="hidden" name="id_arquivo" id="id-arquivo">
            </form>
            <div id="listar-arquivos"></div>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>



<button id="btn_filtrar" onclick="location.reload()" style="display:none"></button>


<script>
  function editar(id, descricao, valor, data_venc, obs, cliente) {
    $('#mensagem').text('');
    $('#titulo_inserir').text('EDITAR REGISTRO');
    $('#id').val(id);
    $('#descricao').val(descricao);
    $('#valor').val(valor);
    $('#data_venc').val(data_venc);
    $('#obs').val(obs);
    $('#cliente').val(cliente).change();
    
    $('#btn_novo_editar').click();
  }
</script>


<script type="text/javascript">
  function mostrar(descricao, valor, data_venc, data_pgto, nome_usuario_lanc, nome_usuario_baixa, pago, referencia, obs, cliente) {

    const botao = document.getElementById('btn_mostrar');
  

    $('#titulo_dados').text(descricao);
    $('#cliente_dados').text(cliente);
    $('#valor_dados').text(valor);   
    $('#data_venc_dados').text(data_venc);
    $('#data_pgto_dados').text(data_pgto);
    $('#nome_usuario_lanc_dados').text(nome_usuario_lanc);
    $('#nome_usuario_baixa_dados').text(nome_usuario_baixa);
    $('#obs_dados').text(obs);
    $('#pago_dados').text(pago);
    $('#referencia_dados').text(referencia);
   
    botao.click();
  }


  function limparCampos() {
    $('#descricao').val('');
    $('#valor').val('0,00');
    $('#data_venc').val("<?= $data_atual ?>");    
    $('#obs').val('');  
    $('#cliente').val('').change();    
    
  }





  function paginar(pag, busca) {
    $('#dataInicialPag').val($('#dataInicial').val());
    $('#dataFinalPag').val($('#dataFinal').val());

    $('#input_pagina').val(pag);
    $('#input_buscar').val(busca);
    $('#paginacao').click();
  }



  function buscar(filtro) {
    if (filtro === '') {
      $('#pago').val('');
    } else {
      $('#pago').val(filtro);
    }
    $('#btn_filtrar').click();
  }


  function formatarMoeda(valor) {
    // Converte o valor para um número
    valor = parseFloat(valor);

    // Formata o número com duas casas decimais e separador de milhar
    return valor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }


</script>



<script type="text/javascript">
  
// ALERT EXCLUIR #######################################
function baixarConta(id) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success", // Adiciona margem à direita do botão "Sim, Excluir!"
            cancelButton: "btn btn-danger me-1",
            container: 'swal-whatsapp-container'
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: "Deseja Baixa a Conta?",
        text: "Definir a conta como Paga!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim, Baixar!",
        cancelButtonText: "Não, Fechar!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Realiza a requisição AJAX para excluir o item
            $.ajax({
                url: '../../painel/paginas/receber/baixar.php',
                method: 'POST',
                data: { id },
                dataType: "html",
                success: function (mensagem) {
                    if (mensagem.trim() == "Baixado com Sucesso") {
                        // Exibe mensagem de sucesso após a exclusão
                        swalWithBootstrapButtons.fire({
                            title: 'Sucesso!',
                            text: 'Baixado com sucesso!',
                            icon: "success",
                            timer: 500,
                            timerProgressBar: true,
                            confirmButtonText: 'OK',

                        });
                    buscar();
                    } else {
                        // Exibe mensagem de erro se a requisição falhar
                        swalWithBootstrapButtons.fire({
                            title: "Opss!",
                            text: mensagem,
                            icon: "error",
                            confirmButtonText: 'OK',
                        });
                    }
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


<script type="text/javascript">var pag = "<?= $pag ?>"</script>