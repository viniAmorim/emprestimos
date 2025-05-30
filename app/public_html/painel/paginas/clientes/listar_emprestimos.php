<?php 
require_once("../../../conexao.php");
$pagina = 'emprestimos';
$id = $_POST['id'];

$data_atual = date('Y-m-d');

echo <<<HTML
<small>
HTML;
$query = $pdo->query("SELECT * FROM $pagina where cliente = '$id'  order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
echo <<<HTML
	<table class="table table-hover" id="">
		<thead> 
			<tr> 				
				<th>Valor</th>
				<th>Parcelas</th>				
				<th>Prox Venc</th>				
				<th>Júros</th>
				<th>Data</th>
				<th>Ver Parcelas</th>
			</tr> 
		</thead> 
		<tbody> 
HTML;
for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
$id_emp = $res[$i]['id'];
$valor = $res[$i]['valor'];
$parcelas = $res[$i]['parcelas'];
$juros_emp = $res[$i]['juros_emp'];
$data_venc = $res[$i]['data_venc'];
$data = $res[$i]['data'];
$tipo_juros = $res[$i]['tipo_juros'];
$status = $res[$i]['status'];
$cliente = $res[$i]['cliente'];
$multa = $res[$i]['multa'];
$juros = $res[$i]['juros'];


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

$data_vencF = date('d', strtotime($data_venc));
$dataF = implode('/', array_reverse(explode('-', $data)));
$valorF = number_format($valor, 2, ',', '.');

$jurosF = number_format($juros, 2, ',', '.');
$multaF = number_format($multa, 2, ',', '.');

$classe_deb = '';
$query2 = $pdo->query("SELECT * FROM receber where referencia = 'Empréstimo' and id_ref = '$id_emp' and data_venc < curDate() and pago != 'Sim'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_reg2 = @count($res2);
if($total_reg2 > 0){
	$classe_deb = 'text-danger';	
	
}

$atrasadas = '';
if($total_reg2 > 0){
	$atrasadas = '('.$total_reg2.')';
}

$valor_parc = 0;
$data_ultimo_vencF = '';
$query2 = $pdo->query("SELECT * FROM receber where referencia = 'Empréstimo' and id_ref = '$id_emp' and pago != 'Sim' ");
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
	$icone_somente_juros = '';
}else{
	$total_a_pagar = $valor_parc;
	$icone_somente_juros = 'ocultar';
}

$total_a_pagarF = number_format($total_a_pagar, 2, ',', '.');

echo <<<HTML
			<tr>					
				<td class="{$classe_deb}">R$ {$valorF} {$classe_finalizado}</td>
				<td class="">{$parcelas} <span style="color:red"><small>{$atrasadas}</small></span></td>				
				<td class=" {$classe_deb}">{$data_ultimo_vencF}</td>
				<td class="">{$juros_emp}%</td>
				<td class="">{$dataF}</td>
				<td>


				<big><a href="#" onclick="editarEmp('{$id_emp}','{$jurosF}','{$multaF}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>


     <form   method="POST" action="rel/detalhamento_emprestimo_class.php" target="_blank" style="display:inline-block">
					<input type="hidden" name="id" value="{$id_emp}">
					<big><button style="background:transparent; border:none; margin:0; padding:0" class="" title="Detalhamento Empréstimo"><i class="fa fa-file-pdf-o text-danger"></i></button></big>
		</form>		

					<big><a class="" href="#" onclick="novaParcela('{$id_emp}', '{$cliente}')" title="Adicionar Parcela"><i class="fa fa-plus text-primary "></i></a></big>

					<big><a href="#" onclick="mostrarParcelasEmp('{$id_emp}')" title="Mostrar Parcelas"><i class="fa fa-money verde"></i></a></big>

					<big><a class="{$mostrar_baixa} {$icone_somente_juros}" href="#" onclick="amortizar('{$id_emp}', '{$cliente}')" title="Amortizar Valor"><i class="fa fa-usd verde "></i></a></big>

					<big><a class="{$mostrar_baixa}" href="#" onclick="baixarEmprestimo('{$id_emp}', '{$total_a_pagarF}', '{$cliente}')" title="Baixar Empréstimo"><i class="fa fa-check verde "></i></a></big>
				</td>  
			</tr> 
HTML;
}
echo <<<HTML
		</tbody> 
	</table>
</small>
HTML;
}else{
	echo 'Não possui nenhum emprestimo cadastrado!';
}

?>


<script type="text/javascript">

	function editarEmp(id, juros, multa){

		$('#mensagem_empr').text('');
    	$('#titulo_empr').text('Editar Registro');

    	$('#id_empr').val(id);
    	$('#juros_empr').val(juros);
    	$('#multa_empr').val(multa);
    	    
    	$('#modalEditar').modal('show');
	}

	function mostrarParcelasEmp(id_emp){	
		var mostrar = 'emprestimo';
		$('#id_cobranca').val('');	
    
    $.ajax({
        url: 'paginas/' + pag + "/mostar_parcelas.php",
        method: 'POST',
        data: {id_emp, mostrar},
        dataType: "text",

        success: function (mensagem) {           
           $("#listar_parcelas").html(mensagem);
        },      

    });

    $('#id_emprestimo').val(id_emp);
    $('#modalParcelas').modal('show');

}


	function baixarEmprestimo(id_emp, valor, cliente){	 
	$('#id_do_emp').val(id_emp);	
	$('#id_do_cliente').val(cliente);	
	$('#valor_final_emprestimo').val(valor);
		
    $('#modalBaixarEmprestimo').modal('show');
}


function novaParcela(id_emp, cliente){	
	$('#id_nova_parcela').val(id_emp);
	$('#id_nova_parcela_cliente').val(cliente);
    $('#modalNovaParcela').modal('show');	   
    
}


function amortizar(id_emp, cliente){	
	$('#id_amortizar').val(id_emp);
	$('#id_amortizar_cliente').val(cliente);
    $('#modalAmortizar').modal('show');	   
    
}

</script>


