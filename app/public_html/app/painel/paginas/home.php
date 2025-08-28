<?php
if (@$home == 'ocultar') {
  echo "<script>window.location='index'</script>";
  exit();
}


//total de clientes
$query = $pdo->query("SELECT * from clientes ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_clientes = @count($res);

//total de empréstimos mês
$query = $pdo->query("SELECT * from emprestimos where data >= '$data_mes' and data <= '$data_final_mes' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_emprestimos_mes_inicio = @count($res);
$total_emprestimos_mes = @count($res);


//total de cobrancas mês
$query = $pdo->query("SELECT * from cobrancas where data >= '$data_mes' and data <= '$data_final_mes' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_cobrancas_mes_inicio = @count($res);
$total_cobrancas_mes = @count($res);


//total vencidos
$query = $pdo->query("SELECT * from receber where data_venc < curDate() and pago != 'Sim' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_parcelas_debitos = @count($res);
$total_emprestimos_vencidos = 0;
for($i=0; $i<$total_parcelas_debitos; $i++){
  $valor = $res[$i]['valor'];
  $total_emprestimos_vencidos += $valor;
}
$total_emprestimos_vencidosF = number_format($total_emprestimos_vencidos, 2, ',', '.');


//total de empréstimos receber mes pendentes
$query = $pdo->query("SELECT * from receber where data_venc >= '$data_mes' and data_venc <= '$data_final_mes'  and pago != 'Sim' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas_receber_mes = @count($res);
$total_receber_mes = 0;
for($i=0; $i<$linhas_receber_mes; $i++){
  $valor = $res[$i]['valor'];
  $total_receber_mes += $valor;
}
$total_receber_mesF = number_format($total_receber_mes, 2, ',', '.');



//total a receber no mes
$query = $pdo->query("SELECT * from receber where data_venc >= '$data_mes' and data_venc <= '$data_final_mes'  ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
$total_receber_mes_emprestimos = 0;
for($i=0; $i<$linhas; $i++){
  $valor = $res[$i]['valor'];
  $total_receber_mes_emprestimos += $valor;
}
$total_receber_mes_emprestimosF = number_format($total_receber_mes_emprestimos, 2, ',', '.');


//total recebido no mes
$query = $pdo->query("SELECT * from receber where data_venc >= '$data_mes' and data_venc <= '$data_final_mes'  and pago = 'Sim' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
$total_recebido = 0;
for($i=0; $i<$linhas; $i++){
  $valor = $res[$i]['valor'];
  $total_recebido += $valor;
}
$total_recebidoF = number_format($total_recebido, 2, ',', '.');


if($total_recebido > 0 and $total_receber_mes_emprestimos > 0){
    $porcentagem_receber = ($total_recebido / $total_receber_mes_emprestimos) * 100;
}else{
    $porcentagem_receber = 0;
}



//total de contas a pagar mês
$query = $pdo->query("SELECT * from pagar where data_venc >= '$data_mes' and data_venc <= '$data_final_mes' and referencia = 'Conta' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pendentes_mes = @count($res);


//total de contas pagas mês
$query = $pdo->query("SELECT * from pagar where data_venc >= '$data_mes' and data_venc <= '$data_final_mes' and referencia = 'Conta' and pago = 'Sim' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pagas_mes = @count($res);

if($contas_pendentes_mes > 0 and $contas_pagas_mes > 0){
    $porcentagem_pagar = ($contas_pagas_mes / $contas_pendentes_mes) * 100;
}else{
    $porcentagem_pagar = 0;
}


//total de clientes débitos
$total_clientes_debitos = 0;
$query = $pdo->query("SELECT * from clientes ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
for($i=0; $i<$linhas; $i++){
  $id_cliente = $res[$i]['id'];

  $query2 = $pdo->query("SELECT * from receber where data_venc < curDate() and pago != 'Sim' and cliente = '$id_cliente' ");
  $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
  $total_clientes_deb = @count($res2);
  if($total_clientes_deb > 0){
    $total_clientes_debitos += 1;
  }
}

if($total_clientes_debitos > 0 and $total_clientes > 0){
    $porcentagem_clientes = ($total_clientes_debitos / $total_clientes) * 100;
    
}else{
    $porcentagem_clientes = 0;
}




//novos cards

$total_capital_emprestado = 0;
//total de capital emprestado mês
$query = $pdo->query("SELECT * from emprestimos ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_cap = @count($res);
for($i=0; $i<$total_cap; $i++){
  $valor = $res[$i]['valor'];
  $status = $res[$i]['status'];
  if($status != 'Finalizado'){
    $total_capital_emprestado += $valor;
  }
  
}
$total_capital_emprestadoF = number_format($total_capital_emprestado, 2, ',', '.');


$total_emprestado_mes = 0;
//total de capital emprestado mês
$query = $pdo->query("SELECT * from emprestimos where data >= '$data_mes' and data <= '$data_final_mes'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_cap = @count($res);

for($i=0; $i<$total_cap; $i++){
  $valor = $res[$i]['valor'];
  $status = $res[$i]['status'];
  if($status != 'Finalizado'){
    $total_emprestado_mes += $valor;
  }
  
}
$total_emprestado_mesF = number_format($total_emprestado_mes, 2, ',', '.');



//total de capital emprestado mês
$query = $pdo->query("SELECT * from emprestimos where status = 'Finalizado'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$emprestimos_finalizados = @count($res);

?>


<!-- CARDS -->
<div class="page-content header-clear-medium">



  <!-- CARD 01 -->
  <div class="card" style="float:left; width:50%; border-radius: 20px; margin-bottom: 3px">
    <div class="card-body px-0 py-0">
      <div class="list-group list-custom list-group-m rounded-xs list-group-flush px-3">
        <a href="clientes" class="list-group-item d-flex justify-content-between align-items-center"  onclick="navigateToPage(event, 'clientes')">
          <div class="d-flex align-items-center">
            <img src="images/cards/clientes.png" width="30px" />
            <span style="margin-left: 3px; font-size: 8px; color:#000" class="font-700"></span>
          </div>
          <span class="badge rounded-xl bg-green-dark"><?php echo $total_clientes ?></span>
        </a>
         <div align="center" class="d-flex " style="color:#939393; font-size: 10px;"> <span
            class="font-700">CLIENTES CADASTRADOS</span>
         
        </div>
      </div>
    </div>
  </div>

  <?php if($recursos != "Cobranças"){ ?>
  <!-- CARD 02 -->
  <div class="card" style="float:right; width:50%; border-radius: 20px; margin-bottom: 3px">
    <div class="card-body px-0 py-0">
      <div class="list-group list-custom list-group-m rounded-xs list-group-flush px-3">
        <a href="emprestimos" class="list-group-item d-flex justify-content-between align-items-center"  onclick="navigateToPage(event, 'emprestimos')">
          <div class="d-flex align-items-center">
            <img src="images/cards/financ.png" width="30px" />
            <span style="margin-left: 3px; font-size: 8px; color:#000" class="font-700"></span>
          </div>
          <span class="badge rounded-xl bg-green-dark"><?php echo $total_emprestimos_mes_inicio ?></span>
        </a>
        <div align="center" class="d-flex " style="color:#939393; font-size: 10px;"> <span
            class="font-700">EMPRÉSTIMOS MÊS</span>
         
        </div>
      </div>
    </div>
  </div>
<?php }else{ ?>
  <div class="card" style="float:right; width:50%; border-radius: 20px; margin-bottom: 3px">
    <div class="card-body px-0 py-0">
      <div class="list-group list-custom list-group-m rounded-xs list-group-flush px-3">
        <a href="cobrancas" class="list-group-item d-flex justify-content-between align-items-center"  onclick="navigateToPage(event, 'cobrancas')">
          <div class="d-flex align-items-center">
            <img src="images/cards/financ.png" width="30px" />
            <span style="margin-left: 3px; font-size: 8px; color:#000" class="font-700"></span>
          </div>
          <span class="badge rounded-xl bg-green-dark"><?php echo $total_cobrancas_mes_inicio ?></span>
        </a>
         <div align="center" class="d-flex " style="color:#939393; font-size: 10px;"> <span
            class="font-700">COBRANÇAS MÊS</span>
         
        </div>
      </div>
    </div>
  </div>
<?php } ?>


  <!-- CARD 05 -->
  <div class="card" style="float:left; width:50%; border-radius: 20px; margin-bottom: 3px">
    <div class="card-body px-0 py-0">
      <div class="list-group list-custom list-group-m rounded-xs list-group-flush px-3">
        <a href="receber" class="list-group-item d-flex justify-content-between align-items-center"  onclick="navigateToPage(event, 'receber')">
          <div class="d-flex align-items-center">
            <img src="images/cards/financeiro_negativo.png" width="30px" />
            <span style="margin-left: 3px; font-size: 8px; color:#000" class="font-700"></span>
          </div>
          <span class="badge rounded-xl bg-red-dark">R$ <?php echo $total_emprestimos_vencidosF ?></span>
        </a>
        <div align="center" class="d-flex " style="color:#939393; font-size: 10px;"> <span
            class="font-700">TOTAL VENCIDO</span>
         
        </div>
      </div>
    </div>
  </div>


  <!-- CARD 06 -->
  <div class="card" style="float:right; width:50%; border-radius: 20px; margin-bottom: 3px">
    <div class="card-body px-0 py-0">
      <div class="list-group list-custom list-group-m rounded-xs list-group-flush px-3">
        <a href="receber" class="list-group-item d-flex justify-content-between align-items-center"  onclick="navigateToPage(event, 'receber')">
          <div class="d-flex align-items-center">
            <img src="images/cards/financeiro_positivo.png" width="30px" />
            <span style="margin-left: 3px; font-size: 8px; color:#000" class="font-700"></span>
          </div>
          <span class="badge rounded-xl bg-green-dark">R$ <?php echo $total_receber_mesF ?></span>
        </a>
        <div align="center" class="d-flex " style="color:#939393; font-size: 10px;"> <span
            class="font-700">TOTAL RECEBER MÊS</span>
         
        </div>
      </div>
    </div>
  </div>


 


  <!-- CARD 08 -->
  <div class="card" style="float:left; width:50%; border-radius: 20px">
    <div class="card-body px-0 py-0">
      <div class="list-group list-custom list-group-m rounded-xs list-group-flush px-3">
        <a href="receber" class="list-group-item d-flex justify-content-between align-items-center"  onclick="navigateToPage(event, 'receber')">
          <div class="d-flex align-items-center">
            <img src="images/cards/financeiro_negativo2.png" width="30px" />
            <span style="margin-left: 3px; font-size: 8px; color:#000" class="font-700"></span>
          </div>
          <span class="badge rounded-xl bg-red-dark"> <?php echo $total_parcelas_debitos ?></span>
        </a>
        <div align="center" class="d-flex " style="color:#939393; font-size: 10px;"> <span
            class="font-700">PARCELAS DÉBITO</span>
         
        </div>
      </div>
    </div>
  </div>



<?php if($recursos != "Cobranças"){ ?>
  <div class="card" style="float:right; width:50%; border-radius: 20px">
    <div class="card-body px-0 py-0">
      <div class="list-group list-custom list-group-m rounded-xs list-group-flush px-3">
        <a href="emprestimos" class="list-group-item d-flex justify-content-between align-items-center"  onclick="navigateToPage(event, 'emprestimos')">
          <div class="d-flex align-items-center">
            <img src="images/cards/financeiro_positivo2.png" width="30px" />
            <span style="margin-left: 3px; font-size: 8px; color:#000" class="font-700"></span>
          </div>
          <span class="badge rounded-xl bg-primary-dark"> <?php echo $total_capital_emprestadoF ?></span>
        </a>
         <div align="center" class="d-flex " style="color:#939393; font-size: 10px;"> <span
            class="font-700">CAPITAL EMPRESTADO</span>
         
        </div>
      </div>
    </div>
  </div>



  <div class="card" style="float:left; width:50%; border-radius: 20px">
    <div class="card-body px-0 py-0">
      <div class="list-group list-custom list-group-m rounded-xs list-group-flush px-3">
        <a href="receber" class="list-group-item d-flex justify-content-between align-items-center"  onclick="navigateToPage(event, 'receber')">
          <div class="d-flex align-items-center">
            <img src="images/cards/financeiro_positivo3.png" width="30px" />
            <span style="margin-left: 3px; font-size: 8px; color:#000" class="font-700"></span>
          </div>
          <span class="badge rounded-xl bg-green-dark"> <?php echo $total_emprestado_mesF ?></span>
        </a>
         <div align="center" class="d-flex " style="color:#939393; font-size: 10px;"> <span
            class="font-700">EMPRESTADO MÊS</span>
         
        </div>
      </div>
    </div>
  </div>


  <div class="card" style="float:right; width:50%; border-radius: 20px">
    <div class="card-body px-0 py-0">
      <div class="list-group list-custom list-group-m rounded-xs list-group-flush px-3">
        <a href="receber" class="list-group-item d-flex justify-content-between align-items-center"  onclick="navigateToPage(event, 'receber')">
          <div class="d-flex align-items-center">
            <img src="images/cards/financ.png" width="30px" />
            <span style="margin-left: 3px; font-size: 8px; color:#000" class="font-700"></span>
          </div>
          <span class="badge rounded-xl bg-primary-dark"> <?php echo $total_recebidoF ?></span>
        </a>
         <div align="center" class="d-flex " style="color:#939393; font-size: 10px;"> <span
            class="font-700">RECEBIDOS MÊS</span>
         
        </div>
      </div>
    </div>
  </div>


    <div class="card" style="float:right; width:50%; border-radius: 20px">
    <div class="card-body px-0 py-0">
      <div class="list-group list-custom list-group-m rounded-xs list-group-flush px-3">
        <a href="emprestimos" class="list-group-item d-flex justify-content-between align-items-center"  onclick="navigateToPage(event, 'emprestimos')">
          <div class="d-flex align-items-center">
            <img src="images/cards/data.png" width="30px" />
            <span style="margin-left: 3px; font-size: 8px; color:#000" class="font-700"></span>
          </div>
          <span class="badge rounded-xl bg-primary-dark"> <?php echo $emprestimos_finalizados ?></span>
        </a>
         <div align="center" class="d-flex " style="color:#939393; font-size: 10px;"> <span
            class="font-700">EMPR FINALIZADOS</span>
         
        </div>
      </div>
    </div>
  </div>


   <div class="card" style="float:left; width:50%; border-radius: 20px">
    <div class="card-body px-0 py-0">
      <div class="list-group list-custom list-group-m rounded-xs list-group-flush px-3">
        <a href="clientes" class="list-group-item d-flex justify-content-between align-items-center"  onclick="navigateToPage(event, 'clientes')">
          <div class="d-flex align-items-center">
            <img src="images/cards/clientes.png" width="30px" />
            <span style="margin-left: 3px; font-size: 8px; color:#000" class="font-700"></span>
          </div>
          <span class="badge rounded-xl bg-primary-dark"> <?php echo $total_clientes_debitos ?></span>
        </a>
         <div align="center" class="d-flex " style="color:#939393; font-size: 10px;"> <span
            class="font-700">CLIENTES DÉBITO</span>
         
        </div>
      </div>
    </div>
  </div>


<?php } ?>


</div>




<script>
    function mostrar(usuario, usuario_lanc, data, hora, descricao, status, hora_mensagem, prioridade, titulo, recorrencia) {
    const botao = document.getElementById('btn_mostrar');

    $('#titulo_dados').text(titulo);
		$('#usuario_dados').text(usuario);
		$('#usuario_lanc_dados').text(usuario_lanc);
		$('#data_dados').text(data);
		$('#hora_dados').text(hora);
		$('#descricao_dados').text(descricao);
		$('#status_dados').text(status);
		$('#hora_mensagem_dados').text(hora_mensagem);
		$('#prioridade_dados').text(prioridade);
		$('#recorrencia_dados').text(recorrencia);

botao.click();
}


</script>
