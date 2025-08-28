<?php
require_once("cabecalho.php");
require_once("rodape.php");


$pag = 'emprestimos';
$itens_pag = 10;

$cliente = @$_POST['cliente_busca'];
$ativo = @$_POST['ativo']; // Adicionar esta linha para capturar o valor do filtro
$status = @$_POST['status_busca'];

if($cliente > 0){
  $sql_cliente = " and cliente = '$cliente' ";
}else{
  $sql_cliente = "";
}


if($status == ""){
  $sql_status = ' and status is null';
  $cor_btn_ativo = '#436399';
  $cor_texto_btn_ativo = '#FFF';
}

if($status == "Ativos"){
  $sql_status = ' and status is null';
  $cor_btn_ativo = '#436399';
  $cor_texto_btn_ativo = '#FFF';
}

if($status == "Finalizado"){
  $sql_status = " and status = 'Finalizado'";
  $cor_btn_finalizado = '#436399';
  $cor_texto_btn_finalizado = '#FFF';
}

if($status == "Perdido"){
  $sql_status = " and status = 'Perdido'";
  $cor_btn_perdido = '#436399';
  $cor_texto_btn_perdido = '#FFF';
}


if (@$emprestimos == 'ocultar') {
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
$query2 = $pdo->query("SELECT * from $pag where id > 0 $sql_status $sql_cliente order by id desc");
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

<style>
  .tabradio.selected {
    background-color: #007bff !important;
    color: white !important;
  }
</style>

<div class="page-content header-clear-medium" style="margin-top: -25px">

  <!-- BARRA DE PESQUISA-->
  <div class="content">
    <div class="loginform">
      <form method="post">
         <div class="">
      <div class="content">
        <div class="tabs tabs-pill" id="tab-group-2">
          <div class="tab-controls rounded-m p-1">
            <a style="color:<?php echo $cor_texto_btn_ativo ?>; background: <?php echo $cor_btn_ativo ?>" class="font-12 rounded-m tabradio" data-bs-toggle="collapse" href="#tab-4"
              aria-expanded="" name="tabs" id="tabone" onclick="$('#status_busca').val('Ativos'); $('#btn_filtrar').click()">Ativos</a>
            <a style="color:<?php echo $cor_texto_btn_finalizado ?>; background: <?php echo $cor_btn_finalizado ?>" class="font-12 rounded-m tabradio" data-bs-toggle="collapse" href="#tab-5"
              aria-expanded="" name="tabs" id="tabtwo" onclick="$('#status_busca').val('Finalizado'); $('#btn_filtrar').click()">Finalizados</a>
            <a style="color:<?php echo $cor_texto_btn_perdido ?>; background: <?php echo $cor_btn_perdido ?>" class="font-12 rounded-m tabradio" data-bs-toggle="collapse" href="#tab-x"
              aria-expanded="" name="tabs" id="tabthree"
              onclick="$('#status_busca').val('Perdido'); $('#btn_filtrar').click()">Perdidos</a>
           
          </div>
          
        </div>
      </div>
    </div>

<div class="row">
  <div class="col-md-12 position-relative d-flex align-items-center">

    <!-- Select com ícone esquerdo -->
    <div class="form-floating flex-grow-1 position-relative">
      <i class="bi bi-info-circle-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>

      <select class="sel_nulo rounded-xs ps-5 pe-5" name="cliente_busca" id="cliente_busca" onchange="$('#btn_filtrar').click()" style="width:100%;">
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

    <!-- Botão PDF à direita -->
    <a href="#" onclick="$('#status_rel').val($('#status_busca').val()); $('#cliente_rel').val($('#cliente_busca').val()); $('#btn_rel').click()" title="Relatório PDF"
       class="d-flex justify-content-center align-items-center ms-2"
       style="width: 36px; height: 36px; background: #dc3545; border-radius: 50%;">
      <i class="fa fa-file-pdf" style="font-size: 16px; color: white;"></i>
    </a>

  </div>
</div>


    <input type="hidden" name="status_busca" id="status_busca" value="<?php echo $status ?>">

     <button id="btn_filtrar" class="limpar_botao" type="submit" style="display:none"></button>

      </form>
    </div>
  </div>




<form method="POST" action="../../painel/rel/emprestimos_class.php" style="display:none">
<input type="hidden" name="cliente" id="cliente_rel">
<input type="hidden" name="status" id="status_rel">
<button id="btn_rel" type="submit"></button>
</form>



  <div class="card card-style">
    <div class="content">
      <?php
      $query = $pdo->query("SELECT * from $pag where id > 0 $sql_status $sql_cliente  ORDER BY id desc LIMIT $limite, $itens_pag");
      $res = $query->fetchAll(PDO::FETCH_ASSOC);
      $linhas = @count($res);
      if ($linhas > 0) {
        for ($i = 0; $i < $linhas; $i++) {
          $id = $res[$i]['id'];
          $id_emp = $id;
$valor = $res[$i]['valor'];
$parcelas = $res[$i]['parcelas'];
$juros_emp = $res[$i]['juros_emp'];
$data_venc = $res[$i]['data_venc'];
$data = $res[$i]['data'];
$cliente = $res[$i]['cliente'];
$juros = $res[$i]['juros'];
$multa = $res[$i]['multa'];
$usuario = $res[$i]['usuario'];
$obs = $res[$i]['obs'];
$frequencia = $res[$i]['frequencia'];
$tipo_juros = $res[$i]['tipo_juros'];
$status = $res[$i]['status'];
$cliente = $res[$i]['cliente'];

$mostrar_baixa = 'ocultar';
if($status == ''){
  $mostrar_baixa = '';
}

$classe_finalizado = '';
if($status == 'Finalizado'){
  $mostrar_baixa = 'ocultar';
  $classe_finalizado = '<span style="color:blue">(Finalizado)</span>';
}

if($status == 'Perdido'){
  $mostrar_baixa = 'ocultar';
  $classe_finalizado = '<span style="color:red">(Perdido)</span>';
}

$data_vencF = date('d', @strtotime($data_venc));
$dataF = implode('/', array_reverse(explode('-', $data)));
$valorF = number_format($valor, 2, ',', '.');
$jurosF = number_format($juros, 2, ',', '.');
$multaF = number_format($multa, 2, ',', '.');

$query2 = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = @$res2[0]['nome'];

$query2 = $pdo->query("SELECT * from usuarios where id = '$usuario'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_usuario = @$res2[0]['nome'];


$classe_debito = '';
//verificar débito
$query2 = $pdo->query("SELECT * from receber where referencia = 'Empréstimo' and id_ref = '$id' and pago = 'Não' and data_venc < curDate()");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_atras = @count($res2);
if(@count($res2) > 0){
  $classe_debito = 'text-danger';
}

$atrasadas = '';
if($total_atras > 0){
  $atrasadas = '('.$total_atras.')';
}


$total_juros = 0;
//verificar parcelas pagas
$query2 = $pdo->query("SELECT * from receber where referencia = 'Empréstimo' and id_ref = '$id' and pago = 'Sim'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$parcelas_pagas = @count($res2);

//percorrer as parcelas do empréstimo
if($parcelas_pagas > 0){
  for($i2=0; $i2<$parcelas_pagas; $i2++){
    $valor_p1 = @$res2[$i2]['valor'];
    $parcela_sem_juros1 = @$res2[$i2]['parcela_sem_juros'];
    $projecao1 = ($valor_p1 - $parcela_sem_juros1);
    $total_juros += $projecao1;
  }
}
$total_jurosF = number_format($total_juros, 2, ',', '.');

$valor_parc = 0;
$data_ultimo_vencF = '';
$query2 = $pdo->query("SELECT * FROM receber where referencia = 'Empréstimo' and id_ref = '$id' and pago != 'Sim' ");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
for($i2=0; $i2 < @count($res2); $i2++){
  $valor_pc = @$res2[$i2]['valor'];
  
  $data_ultimo_venc = @$res2[$i2]['data_venc'];
  $pago = @$res2[$i2]['pago'];
  $data_ultimo_vencF = implode('/', array_reverse(explode('-', $data_ultimo_venc)));

  $valor_multa = 0;
  $valor_juros = 0;
  $dias_vencido = 0;

  if(@strtotime($data_ultimo_venc) < @strtotime($data_atual) and $pago != 'Sim'){
  $valor_multa = $multa;
  //calcular quanto dias está atrasado

  $data_inicio = new DateTime($data_ultimo_venc);
  $data_fim = new DateTime($data_atual);
  $dateInterval = $data_inicio->diff($data_fim);
  $dias_vencido = $dateInterval->days;


  $valor_juros = $dias_vencido * ($juros * $valor_pc / 100);
  
  }

  $valor_parc += $valor_pc + $valor_juros + $valor_multa;
}

if($tipo_juros == 'Somente Júros'){
  $total_a_pagar = $valor + $valor_parc;
}else{
  $total_a_pagar = $valor_parc;
}

$total_a_pagarF = number_format($total_a_pagar, 2, ',', '.');

//projecao lucro emprestimo
$query2 = $pdo->query("SELECT * FROM receber where referencia = 'Empréstimo' and id_ref = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$num_parcelas = @$res2[0]['parcela'];
$valor_p = @$res2[0]['valor'];
$parcela_sem_juros = @$res2[0]['parcela_sem_juros'];
$projecao = ($valor_p - $parcela_sem_juros) * $parcelas;
$projecaoF = number_format($projecao, 2, ',', '.');
$texto_projecao = '<small><span style="color:blue"> (R$ '.$projecaoF.')</span></small>';

if($tipo_juros == 'Somente Júros'){
  $texto_projecao = '';
}


$nome = $nome_cliente;

          echo <<<HTML
      <div data-splide='{"autoplay":false}' class="splide single-slider slider-no-arrows slider-no-dots" id="user-slider-{$id}">
        <div class="splide__track">
          <div class="splide__list">
            <div class="splide__slide mx-3">
              <div class="d-flex">
                <div style="padding: 4px 8px; border-radius: 6px;  font-size: 11px; line-height: 1.2;">
  <strong><big>{$nome_cliente}</big></strong> {$classe_finalizado}<br>  
  <span>Parcelas: {$parcelas_pagas}/{$parcelas} <span style="color:red">{$atrasadas}</span></span><br>
  <span>Gerado Em: {$dataF}</span><br>
  <span>Juros: {$juros_emp}% <span style="color:#007bff">({$tipo_juros})</span> <br>
   Júros Recebidos <span style="color:green">R$ {$total_jurosF} </span></span><br>
  <span><small>Último Venc: {$data_ultimo_vencF} / Proj: {$texto_projecao}</small></span><br>
</div>
                

                <div class="ms-auto"><span class="px-2 py-1 badge mt-4 p-2 font-12 shadow-bg shadow-bg-s" style="background:green">R$ {$valorF}</span></div>

                <div class="ms-auto"><span class="badge bg-blue-dark mt-0 p-1 font-8 shadow-bg-s"><i class="fa fa-arrow-right"></i></span></div>
                
              </div>




            </div>
            <div class="splide__slide mx-3">

              <div class="d-flex">
                <div class="ms-auto">
                
                <a href="#" onclick="editarEmp('{$id_emp}','{$jurosF}','{$multaF}')" class="icon icon-xs rounded-circle shadow-l" style="background: blue; color:#FFF" title="Editar Dados" >
        <i class="fa fa-edit" style="font-size:14px;"></i>
      </a>


      <form method="POST" action="../../painel/rel/detalhamento_emprestimo_class.php"  style="display:inline;">
        <input type="hidden" name="id" value="{$id_emp}">
        <button type="submit" class="icon icon-xs rounded-circle shadow-l" style="background: red; color:#FFF; outline:none; border:none" title="Detalhamento Empréstimo">
          <i class="fa fa-file-pdf" style="font-size:14px;"></i>
        </button>
      </form>


        <form method="GET" action="../../painel/rel/contrato_class.php"  style="display:inline;">
        <input type="hidden" name="id" value="{$id_emp}">
        <button type="submit" class="icon icon-xs rounded-circle shadow-l" style="background: #4a72b5; color:#FFF; outline:none; border:none" title="Contrato">
          <i class="fa fa-file-pdf" style="font-size:14px;"></i>
        </button>
      </form>
                                

                  <a onclick="arquivo('{$id}','{$nome}')" href="#" class="icon icon-xs rounded-circle shadow-l bg-cinza"><i
                      class="fa fa-file text-white" style="font-size:14px;"></i></a>                 

                  <a onclick="excluir_reg('{$id}', '{$nome}')" href="#" class="icon icon-xs rounded-circle shadow-l bg-google"><i class="bi bi-trash-fill text-white" style="font-size:14px;"></i></a>

                 
                <a href="#" onclick="mostrarParcelasEmp('{$id_emp}')" class="icon icon-xs rounded-circle shadow-l" style="background: #258a56; color:#FFF;" title="Mostrar Parcelas">
        <i class="fa fa-usd" style="font-size:14px; color:white;"></i>
      </a>


      <a href="#" onclick="baixarEmprestimo('{$id_emp}', '{$total_a_pagarF}', '{$cliente}')" class="icon icon-xs rounded-circle shadow-l {$mostrar_baixa}" style="background: #258a56; color:#FFF;" title="Baixar Empréstimo">
        <i class="fa fa-check" style="font-size:14px;"></i>
      </a>
                 

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


      <form method="post" style="display: none;">
        <input type="text" name="pagina" id="input_pagina">
        <input type="text" name="buscar" id="input_buscar">
        <button type="submit" id="paginacao"></button>
      </form>
    </div>
  </div>
</div>





<div hidden="hidden" class="fab" style="z-index: 100 !important; margin-bottom: 60px">
  <button id="btn_novo_editar" class="main open-popup bg-highlight" data-bs-toggle="offcanvas"
    data-bs-target="#popupForm">
  </button>
</div>




<!--BOTÃO PARA CHAMAR A MODAL BAIXAR -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-baixar-contas"
  class="list-group-item" id="btn_baixar" data-bs-target="#staticBackdrop">
</a>

<!-- MODAL BAIXAR -->
<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:95%;" id="menu-share-baixar-contas">
  <div class="content ">
    <div class="d-flex pb-0">
      <div class="align-self-center">
        <h1 class="font-14 color-highlight font-700 text-uppercase" id="titulo_baixar"></h1>
      </div>
      <div class="align-self-center ms-auto">
        <a href="#" data-bs-dismiss="offcanvas" class="icon icon-m" id="btn_fechar_baixar" onclick="abrContas()"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
    <div class="card overflow-visible rounded-xs">
      <div class="content mb-1">
        <div class="table-responsive">
          <form id="form_baixar_contas_empr" method="post">
            <div class="col-12 mt-2">
              <div class="form-custom form-label form-icon mb-3">
                <div class="form-custom form-label form-icon mb-3">
                  <i class="bi bi-currency-dollar font-16"></i>
                  <input type="text" name="valor_baixar" id="valor_baixar" class="form-control rounded-xs"
                    onkeyup=" calcular()" value="" required readonly="" />
                  <label class="color-theme form-label-always-active font-10 opacity-50">Valor <small><small>(Total /
                        Parcial)</small></small> </label>
                </div>
              </div>
            </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-custom form-label form-icon mb-3">
              <div class="form-custom form-label form-icon mb-3">
                <i class="bi bi-currency-dollar font-16"></i>
                <input type="text" name="juros_baixar" id="juros_baixar" class="form-control rounded-xs"
                  onkeyup=" calcular()" placeholder="Ex 15.00" value="0" />
                <label class="color-theme form-label-always-active font-10 opacity-50">Total Júros</label>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="form-custom form-label form-icon mb-3">
              <i class="bi bi-currency-dollar font-16"></i>
              <input type="text" name="multa_baixar" id="multa_baixar" class="form-control rounded-xs"
                onkeyup="calcular()" placeholder="Ex 0.15" value="0" />
              <label class="color-theme form-label-always-active font-10 opacity-50">Multa R$</label>
            </div>
          </div>
        </div>
       
      <div class="row">
       
       <div class="col-12">
            <div class="form-custom form-label form-icon mb-3">
              <div class="form-custom form-label form-icon mb-3">
                <i class="bi bi-calendar-date font-16"></i>
                <input type="date" name="data_baixa" id="data_baixa" class="form-control rounded-xs"
                   value="<?php echo $data_atual ?>" />
                <label class="color-theme form-label-always-active font-10 opacity-50">Data PGTO</label>
              </div>
            </div>
          </div>

      </div>

      <div class="row">          

           <div class="col-6">
          <div class="form-custom form-label  mb-3">
            <select name="forma_pgto" id="forma_pgto" required onchange="calcular()"
              class="form-select rounded-xs" aria-label="Floating label select example">
              <?php
              $query = $pdo->query("SELECT * from formas_pgto order by id desc");
              $res = $query->fetchAll(PDO::FETCH_ASSOC);
              $linhas = @count($res);
              if ($linhas > 0) {
                for ($i = 0; $i < $linhas; $i++) {
                  echo '<option value="' . $res[$i]['nome'] . '">' . $res[$i]['nome'] . '</option>';
                }
              } else {
                echo '<option value="0">Cadastre uma Forma de Pagamento</option>';
              }
              ?>
            </select>
            <label class="olor-theme form-label-always-active font-10 opacity-50">Forma PGTO</label>
          </div>
        </div>


          <div class="col-6">
            <div class="form-custom form-label form-icon mb-3">
              <i class="bi bi-currency-dollar font-16"></i>
              <input type="text" name="valor_final" id="valor_final" class="form-control rounded-xs"
                onkeyup="" placeholder="Ex 0.15" value="0" />
              <label class="color-theme form-label-always-active font-10 opacity-50">Valor Final</label>
            </div>
          </div>

        </div>

          <div class="row" align="right" style="font-size: 14px">
          <div class="col-md-12"> 
              <span style="margin-right: 15px">
                <input type="checkbox" class="form-checkbox" id="residuo_final" name="residuo_final" value="Sim" style="display:inline-block;">
                <label for="residuo_final" style="display:inline-block;"><small>Resíduo Final Empréstimo</small></label>
              </span>
              <br>
              <span>  
                <input type="checkbox" class="form-checkbox" id="residuo" name="residuo" value="Sim" style="display:inline-block;">
                <label for="residuo" style="display:inline-block;"><small>Resíduo Próxima Parcela</small></label>
              </span>
            </div>

            
          </div>


          <button name="btn_baixar" id="btn_baixar"
            class="btn btn-full gradient-highlight rounded-xs text-uppercase font-700 w-100 btn-s mt-4 mb-3"
            type="submit">SALVAR <i class="fa-regular fa-circle-check"></i></button>
          <button class="btn btn-full gradient-highlight rounded-xs text-uppercase font-700 w-100 btn-s mt-4 mb-3"
            type="button" id="btn_carregando" style="display: none">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando...
          </button>
         
         <input type="hidden" class="form-control" id="id_baixar" name="id">    

          </form>
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
        <h1 class="font-11 color-highlight font-700 text-uppercase" id="titulo_arquivo"></h1>
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
                <input type="text" class="form-control rounded-xs ps-5" id="nome-arq" name="nome_arq" placeholder=""
                  required>
                <label class="color-theme ps-5">Nome do Arquvio</label>
              </div>

             

              <button name="btn_salvar_arquivo" id="btn_salvar_arquivo"
                class="btn btn-full gradient-highlight rounded-xs text-uppercase w-100 btn-s mt-2" type="submit">Inserir
                <i class="fa-regular fa-circle-check"></i></button>
              <input type="hidden" name="id_arquivo" id="id-arquivo">
            </form>
            <div id="listar-arquivos"></div>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>




<!--BOTÃO PARA EDITAR JUROS E MULTA -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-editar" class="list-group-item"
  id="modalEditar">
</a>

<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:40%" id="menu-share-editar">
  <div class="content ">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h1 class="font-11 color-highlight font-700 text-uppercase" id="titulo_cob"></h1>
      </div>
      <div class="align-self-center ms-auto">
        <a onclick="mostrarContas($('#id_contas').val(), $('#nome_contas').val())" id="btn-fechar-editar" href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
      
<form id="form_empr" method="post" class="demo-animation m-0 needs-validation m-0">

<div class="row g-2">

    <!-- Valor -->
    <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-cash-coin position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="juros_empr" name="juros" placeholder="Júros se Houver" required onkeyup="mascara_valor('juros_empr')">
        <label class="color-theme ps-5">Júros</label>
      </div>
    </div>


     <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-cash-coin position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="multa_empr" name="multa" placeholder="Multa se Houver" required onkeyup="mascara_valor('multa_empr')">
        <label class="color-theme ps-5">Múlta</label>
      </div>
    </div>

   
    <input type="hidden" class="form-control" id="id_empr" name="id">

    <!-- Botão de Enviar -->
    <div class="col-12 mt-3">
      <button id="btn_editar" type="submit" class="btn btn-primary w-100 rounded-pill">
        <i class="bi bi-save me-2"></i>Editar
      </button>
    </div>

  </div>


</form>

  </div>
</div>











<!--MODAL NOVA PARCELA -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-nova_parcela" class="list-group-item"
  id="modalNovaParcela">
</a>

<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:70%" id="menu-share-nova_parcela">
  <div class="content ">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h1 class="font-11 color-highlight font-700 text-uppercase" id="id_nova_parcela_cliente">Nova Parcela</h1>
      </div>
      <div class="align-self-center ms-auto">
        <a onclick="abrContas()" id="btn-fechar-nova_parcela" href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
      
<form id="form_nova_parcela" method="post" class="demo-animation m-0 needs-validation m-0">

<div class="row g-2">

    <!-- Valor -->
    <div class="col-12">
      <div class="form-floating position-relative">
        <i class="bi bi-chat-left-text-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="descricao_nova" name="descricao" placeholder="" required>
        <label class="color-theme ps-5">Descrição</label>
      </div>
    </div>


     <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-cash-coin position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="valor_nova" name="valor" placeholder="" required onkeyup="mascara_valor('valor_nova')">
        <label class="color-theme ps-5">Valor</label>
      </div>
    </div>


      <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-calendar-date position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="date" class="form-control rounded-xs ps-5" id="data_venc_nova" name="data_venc" required>
        <label class="color-theme ps-5">Vencimento</label>
      </div>
    </div>

    <div class="col-12">
      <div class="form-floating position-relative">
        <i class="bi bi-exclamation-triangle-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="obs_nova" name="obs" placeholder="">
        <label class="color-theme ps-5">Observações</label>
      </div>
    </div>


   
   <input type="hidden" class="form-control" id="id_nova_parcela" name="id">  
          <input type="hidden" class="form-control" id="id_nova_parcela_cliente" name="id_cliente"> 

    <!-- Botão de Enviar -->
    <div class="col-12 mt-3">
      <button id="btn_nova_parcela" type="submit" class="btn btn-primary w-100 rounded-pill">
        <i class="bi bi-save me-2"></i>Nova Parcela
      </button>
    </div>

  </div>


</form>

  </div>
</div>








<!--MODAL MOSTRAR PARCELAS -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-parcelas" class="list-group-item"
  id="modalParcelas">
</a>

<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:90%" id="menu-share-parcelas">
  <div class="content ">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h1 class="font-11 color-highlight font-700 text-uppercase" id="">Parcelas</h1>
      </div>
      <div class="align-self-center ms-auto">
        <a onclick="mostrarContas($('#id_contas').val(), $('#nome_contas').val())" id="btn-fechar-nova_parcela" href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
      
    <small><div id="listar_parcelas" style=""></div></small>

        <input type="hidden" id="id_emprestimo">
        <input type="hidden" id="id_cobranca">  

  </div>
</div>









<!--MODAL AMORTIZAR -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-amortizar" class="list-group-item"
  id="modalAmortizar">
</a>

<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:70%" id="menu-share-amortizar">
  <div class="content ">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h1 class="font-11 color-highlight font-700 text-uppercase" id="id_nova_parcela_cliente">Amortizar Valor</h1>
      </div>
      <div class="align-self-center ms-auto">
        <a onclick="mostrarContas($('#id_contas').val(), $('#nome_contas').val())" id="btn-fechar-amortizar" href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
      
<form id="form_amortizar" method="post" class="demo-animation m-0 needs-validation m-0">

<div class="row g-2">

    
     <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-cash-coin position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="valor_amortizar" name="valor_amortizar" placeholder="" required onkeyup="mascara_valor('valor_amortizar')">
        <label class="color-theme ps-5">Valor</label>
      </div>
    </div>


      <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-calendar-date position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="date" class="form-control rounded-xs ps-5" id="data_amortizar" name="data_amortizar" required value="<?php echo date('Y-m-d') ?>">
        <label class="color-theme ps-5">Data</label>
      </div>
    </div>

    <div class="col-12">
      <div class="form-floating position-relative">
        <i class="bi bi-exclamation-triangle-fill position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="obs_amortizar" name="obs_amortizar" placeholder="">
        <label class="color-theme ps-5">Observações</label>
      </div>
    </div>


   
 <input type="hidden" class="form-control" id="id_amortizar" name="id"> 
          <input type="hidden" class="form-control" id="id_amortizar_cliente" name="id_cliente">  

    <!-- Botão de Enviar -->
    <div class="col-12 mt-3">
      <button id="btn_amortizar" type="submit" class="btn btn-primary w-100 rounded-pill">
        <i class="bi bi-save me-2"></i>Amortizar
      </button>
    </div>

  </div>


</form>

  </div>
</div>









<!--MODAL BAIXAR EMPRSTIMO -->
<a style="display:none" href="#" data-bs-toggle="offcanvas" data-bs-target="#menu-share-baixar_emp" class="list-group-item"
  id="modalBaixarEmprestimo">
</a>

<div class="offcanvas offcanvas-top rounded-m offcanvas-detached" style="height:85%" id="menu-share-baixar_emp">
  <div class="content ">
    <div class="d-flex pb-2">
      <div class="align-self-center">
        <h1 class="font-11 color-highlight font-700 text-uppercase" id="id_nova_baixar_emp">Amortizar Valor</h1>
      </div>
      <div class="align-self-center ms-auto">
        <a onclick="mostrarContas($('#id_contas').val(), $('#nome_contas').val())" id="btn-fechar-baixar_emp" href="#" data-bs-dismiss="offcanvas" class="icon icon-m"><i
            class="bi bi-x-circle-fill color-red-dark font-18 me-n4"></i></a>
      </div>
    </div>
      
<form id="form_baixar_emprestimo" method="post" class="demo-animation m-0 needs-validation m-0">

<div class="row g-2">

    
     <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-cash-coin position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="text" class="form-control rounded-xs ps-5" id="valor_final_emprestimo" name="valor_final_emprestimo" placeholder="" required onkeyup="mascara_valor('valor_final_emprestimo')">
        <label class="color-theme ps-5">Valor</label>
      </div>
    </div>


     <div class="col-6">
      <div class="form-floating position-relative">
        <i class="bi bi-arrow-repeat position-absolute start-0 top-50 translate-middle-y ms-3"></i>

        <select class="form-select rounded-xs ps-5" name="status_baixa" id="status_baixa" required="">
          <option value="Finalizado">Finalizado</option>
      <option value="Perdido">Perdido</option>
        </select>
        <label class="color-theme ps-5">Status Baixa</label>
      </div>
    </div>


      <div class="col-12">
      <div class="form-floating position-relative">
        <i class="bi bi-calendar-date position-absolute start-0 top-50 translate-middle-y ms-3"></i>
        <input type="date" class="form-control rounded-xs ps-5" id="data_baixa_emprestimo" name="data_baixa_emprestimo" required value="<?php echo date('Y-m-d') ?>">
        <label class="color-theme ps-5">Data Pgto</label>
      </div>
    </div>

      <div class="col-12">
          <div class="form-custom form-label  mb-3">
            <select name="forma_pgto_emprestimo" id="forma_pgto_emprestimo" required 
              class="form-select rounded-xs" aria-label="Floating label select example">
              <?php
              $query = $pdo->query("SELECT * from formas_pgto order by id desc");
              $res = $query->fetchAll(PDO::FETCH_ASSOC);
              $linhas = @count($res);
              if ($linhas > 0) {
                for ($i = 0; $i < $linhas; $i++) {
                  echo '<option value="' . $res[$i]['nome'] . '">' . $res[$i]['nome'] . '</option>';
                }
              } else {
                echo '<option value="0">Cadastre uma Forma de Pagamento</option>';
              }
              ?>
            </select>
            <label class="olor-theme form-label-always-active font-10 opacity-50">Forma PGTO</label>
          </div>
        </div>

   
<input type="hidden" class="form-control" id="id_do_emp" name="id">   
          <input type="hidden" class="form-control" id="id_do_cliente" name="id_cliente"> 


          <br>
        <small><div align="center" style="border: 1px solid #000; font-size: 10px"><b>OBS:</b>Ao Finalizar o empréstimo, todas as parcelas que estão pendentes de pagamentos serão excluídas e o valor inserido aqui será lançado no financeiro.</div></small>

    <!-- Botão de Enviar -->
    <div class="col-12 mt-3">
      <button id="btn_baixar_emprestimo" type="submit" class="btn btn-primary w-100 rounded-pill">
        <i class="bi bi-save me-2"></i>Baixar Empréstimo
      </button>
    </div>

  </div>


</form>

  </div>
</div>








<form action="../../painel/rel/recibo_class.php" method="post" style="display:none" >
    <input type="hidden" name="id" value="<?=$id_conta;?>" id="id_conta_recibo">
    <input type="hidden" name="enviar" value="Sim">
    <button id="btn_form" type="submit"></button>
</form>





<script type="text/javascript">


function editarEmp(id, juros, multa){

    const botao = document.getElementById('modalEditar');  

    $('#mensagem_empr').text('');
      $('#titulo_empr').text('Editar Registro');

      $('#id_empr').val(id);
      $('#juros_empr').val(juros);
      $('#multa_empr').val(multa);
          
      botao.click();
  }


  function mostrarParcelasEmp(id_emp){    
    
  const botao = document.getElementById('modalParcelas'); 

    var mostrar = 'emprestimo';     
    
    $.ajax({
        url: 'paginas/clientes/mostar_parcelas.php',
        method: 'POST',
        data: {id_emp, mostrar},
        dataType: "text",

        success: function (mensagem) {           
           $("#listar_parcelas").html(mensagem);
        },      

    });

    $('#id_emprestimo').val(id_emp);
    botao.click();

}


  function baixarEmprestimo(id_emp, valor, cliente){   
    const botao = document.getElementById('modalBaixarEmprestimo'); 
  $('#id_do_emp').val(id_emp);  
  $('#id_do_cliente').val(cliente); 
  $('#valor_final_emprestimo').val(valor);
    
     botao.click(); 
}

  function buscar(status) {

    // Remove a classe selected de todas as abas
    document.querySelectorAll('.tabradio').forEach(tab => {
      tab.classList.remove('selected');
    });

    // Adiciona a classe selected na aba clicada
    event.target.classList.add('selected');

    // Define o valor do status e submete o formulário
    $('#ativo').val(status);
    $('#btn_filtrar_ativo').click();
  }



  function paginar(pag, busca) {
    $('#input_pagina').val(pag);
    $('#input_buscar').val(busca);
    $('#paginacao').click();
  }
</script>


<script>
 
  function formatarMoeda(valor) {
    // Converte o valor para um número
    valor = parseFloat(valor);

    // Formata o número com duas casas decimais e separador de milhar
    return valor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function calcularTaxa() {
    pgto = $('#saida-baixar').val();
    valor = $('#valor-baixar').val();
    $.ajax({
      url: 'paginas/receber/calcular_taxa.php',
      method: 'POST',
      data: { valor, pgto },
      dataType: "html",

      success: function (result) {
        $('#valor-taxa').val(result);
       
      }
    });


  }
</script>


<script>
  // Função para mostrar o placeholder ao focar no campo
  function showPlaceholder(input) {
    input.setAttribute("placeholder", "https://www.dominio.com.br"); // Adiciona o placeholder quando o campo é focado
  }

  // Função para remover o placeholder ao desfocar, caso o campo esteja vazio
  function hidePlaceholder(input) {
    if (input.value === "") {
      input.removeAttribute("placeholder"); // Remove o placeholder quando o campo está vazio
    }
  }
</script>




<script type="text/javascript">var pag = "<?= $pag ?>"</script>


<script>
  const residuoFinal = document.getElementById('residuo_final');
  const residuo = document.getElementById('residuo');

  residuoFinal.addEventListener('change', function () {
    if (this.checked) {
      residuo.checked = false;
    }
  });

  residuo.addEventListener('change', function () {
    if (this.checked) {
      residuoFinal.checked = false;
    }
  });
</script>



<script type="text/javascript">
  function calcular(){      
  var valor = $('#valor_baixar').val();
  var multa = $('#multa_baixar').val();
  var juros = $('#juros_baixar').val();
  var forma_pgto = $('#forma_pgto').val();  

   $.ajax({
          url: '../../painel/paginas/clientes/calcular.php',
          method: 'POST',
          data: {valor, multa, juros, forma_pgto},
          dataType: "html",

          success:function(result){
               $('#valor_final').val(result);
          }
      });

  

  }


   function abrContas() {    
    var id = $('#id_emprestimo').val();

     setTimeout(function () {          
           mostrarParcelasEmp(id)    
          
        }, 500);    
   
  }

</script>
