<?php
require_once("cabecalho.php");
require_once("rodape.php");

$valor_emprestado = 0;
$valor_do_lucro_total = 0;

$pag = 'emprestimos';
$itens_pag = 10;

$cliente = @$_POST['cliente_busca'];
$ativo = @$_POST['ativo']; // Adicionar esta linha para capturar o valor do filtro
$corretor = @$_POST['corretor'];
$dataInicial = @$_POST['dataInicial'];
$dataFinal = @$_POST['dataFinal'];

if($dataInicial == ""){
  $dataInicial = $data_mes;
}

if($dataFinal == ""){
  $dataFinal = $data_final_mes;
}

if($cliente > 0){
  $sql_cliente = " and cliente = '$cliente' ";
}else{
  $sql_cliente = "";
}

if($corretor > 0){
  $sql_corretor = " and usuario = '$corretor' ";
}else{
  $sql_corretor = "";
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
$query2 = $pdo->query("SELECT * from $pag where id > 0 $sql_corretor $sql_cliente order by id desc");
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
       


<div class="row">

   <div style="margin-top: -15px; padding: 15px">
      <input type="date" name="dataInicial" id="dataInicial" class="round-small form-control rounded-xs"
        value="<?php echo $dataInicial ?>" onchange="$('#btn_filtrar').click()" style="width:42%; float:left; padding: 10px" />
      <img src="images/icons/black/icone_datas4.png" style="float:left; width:13%">
      <input type="date" name="dataFinal" id="dataFinal" class="round-small form-control rounded-xs"
        value="<?php echo $dataFinal ?>" onchange="$('#btn_filtrar').click()" style="width:42%; float:right; padding: 10px" />
    </div>

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

  </div>


  <!-- Select com ícone esquerdo -->
    <div class="form-floating flex-grow-1 position-relative">
      

      <select class="sel_nulo rounded-xs ps-5 pe-5" name="corretor" id="corretor" onchange="$('#btn_filtrar').click()" style="width:100%;">
        <option value="" data-cor="">Selecionar Corretor</option>
        <?php 
          $query = $pdo->query("SELECT * from usuarios order by nome asc");
          $res = $query->fetchAll(PDO::FETCH_ASSOC);
          $linhas = @count($res);
          if($linhas > 0){
            for($i=0; $i<$linhas; $i++){
        ?>
          <option value="<?= $res[$i]['id'] ?>" <?php if($cliente == $res[$i]['id']){ ?> selected <?php } ?> ><?= $res[$i]['nome'] ?></option>
        <?php } } ?>
      </select>

       <!-- Botão PDF à direita -->
   <a href="#" 
   onclick="$('#corretor_rel').val($('#corretor').val()); $('#cliente_rel').val($('#cliente_busca').val()); $('#dataInicialRel').val($('#dataInicial').val()); $('#dataFinalRel').val($('#dataFinal').val()); $('#btn_rel').click()" 
   title="Relatório PDF"
   class="btn btn-full bg-red-dark rounded-xs text-uppercase w-100 btn-s mt-2">
   <i class="fa fa-file-pdf" style="font-size: 16px; color: white;"></i> GERAR RELATÓRIO
</a>
    </div>


   

</div>


    <input type="hidden" name="status_busca" id="status_busca" value="<?php echo $status ?>">

     <button id="btn_filtrar" class="limpar_botao" type="submit" style="display:none"></button>

      </form>
    </div>
  </div>




<form method="POST" action="../../painel/rel/lucro_class.php" style="display:none">
<input type="hidden" name="cliente" id="cliente_rel">
<input type="hidden" name="corretor" id="corretor_rel">
<input type="hidden" name="dataInicial" id="dataInicialRel">
<input type="hidden" name="dataFinal" id="dataFinalRel">
<button id="btn_rel" type="submit"></button>
</form>



  <div class="card card-style">
    <div class="content">
      <?php
      $query = $pdo->query("SELECT * from $pag where data_venc >= '$dataInicial' and data_venc <= '$dataFinal' $sql_corretor $sql_cliente  ORDER BY id desc LIMIT $limite, $itens_pag");
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



$valor_emprestado += $valor ;

$data_vencF = date('d', @strtotime($data_venc));
$dataF = implode('/', array_reverse(@explode('-', $data)));
$valorF = @number_format($valor, 2, ',', '.');
$jurosF = @number_format($juros, 2, ',', '.');
$multaF = @number_format($multa, 2, ',', '.');
$valor_emprestadoF = @number_format($valor_emprestado, 2, ',', '.');



$valor_total_pago = 0;
//verificar parcelas pagas
$query2 = $pdo->query("SELECT * from receber where referencia = 'Empréstimo' and id_ref = '$id' and pago = 'Sim'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$parcelas_pagas = @count($res2);
for($i2=0; $i2<$parcelas_pagas; $i2++){
  $valor_parcela = @$res2[$i2]['valor'];
  $valor_total_pago += $valor_parcela;
}

$lucro_emprestimo = $valor_total_pago - $valor;

$valor_total_pagoF = @number_format($valor_total_pago, 2, ',', '.');
$lucro_emprestimoF = @number_format($lucro_emprestimo, 2, ',', '.');

if($lucro_emprestimo > 0){
  $classe_lucro = 'text-success';
}else{
  $classe_lucro = 'text-danger';
}


$valor_do_lucro_total += $lucro_emprestimo;
if($valor_do_lucro_total > 0){
  $classe_lucro_final = 'text-success';
}else{
  $classe_lucro_final = 'text-danger';
}

          echo <<<HTML
      <div  class="splide single-slider slider-no-arrows slider-no-dots" id="user-slider-{$id}">
        <div class="splide__track">
          <div class="splide__list">
            <div class="splide__slide mx-3">
              <div class="d-flex">
                <div style="padding: 4px 8px; border-radius: 6px;  font-size: 11px; line-height: 1.2;">
  <strong><big>{$nome_cliente}</big></strong> {$classe_finalizado}<br>  
  <span>Parcelas: {$parcelas_pagas}/{$parcelas} </span><br>
  <span>Gerado Em: {$dataF}</span><br>
  <span>Juros: {$juros_emp}% <span style="color:#007bff">({$frequencia})</span> <br>
   Total Pago <span style="color:green">R$ {$valor_total_pagoF} </span></span><br>
  <span class="{$classe_lucro}"><big>Lucro: {$lucro_emprestimoF} </big></span><br>
</div>
                

                <div class="ms-auto"><span class="px-2 py-1 badge mt-4 p-2 font-12 shadow-bg shadow-bg-s" style="background:green">R$ {$valorF}</span></div>

               
                
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


      <div align="right">

<?php 
$valor_do_lucro_totalF = @number_format($valor_do_lucro_total, 2, ',', '.');
 ?>

<span style="font-size: 12px">Total Lucro <span class="<?php echo $classe_lucro_final ?>">R$ <?php echo $valor_do_lucro_totalF ?></span></span>
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











