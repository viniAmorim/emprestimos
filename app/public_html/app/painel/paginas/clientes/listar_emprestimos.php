<?php 
require_once("../../../../conexao.php");
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
				<th>Ações</th>				
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
	$classe_finalizado = '<span style="color:blue">(F)</span>';
}

if($status == 'Perdido'){
	$mostrar_baixa = 'ocultar';
	$classe_finalizado = '<span style="color:red">(P)</span>';
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
	<td>
		<div class="d-flex flex-wrap align-items-center gap-1">

			<!-- Editar -->
			<a href="#" onclick="editarEmp('{$id_emp}','{$jurosF}','{$multaF}')" class="d-inline-flex justify-content-center align-items-center bg-primary text-white rounded-circle" style="width:28px; height:28px;" title="Editar Dados">
				<i class="fa fa-edit" style="font-size:14px;"></i>
			</a>

			<!-- Detalhamento PDF -->
			<form method="POST" action="../../painel/rel/detalhamento_emprestimo_class.php"  style="display:inline;">
				<input type="hidden" name="id" value="{$id_emp}">
				<button type="submit" class="d-inline-flex justify-content-center align-items-center bg-danger rounded-circle border-0" style="width:28px; height:28px;" title="Detalhamento Empréstimo">
					<i class="fa fa-file-pdf" style="font-size:14px; color:white;"></i>
				</button>
			</form>

			<!-- Nova Parcela -->
			<a href="#" onclick="novaParcela('{$id_emp}', '{$cliente}')" class="d-inline-flex justify-content-center align-items-center bg-primary text-white rounded-circle" style="width:28px; height:28px;" title="Adicionar Parcela">
				<i class="fa fa-plus" style="font-size:14px;"></i>
			</a>

			<!-- Mostrar Parcelas -->
			<a href="#" onclick="mostrarParcelasEmp('{$id_emp}')" class="d-inline-flex justify-content-center align-items-center bg-success rounded-circle" style="width:28px; height:28px;" title="Mostrar Parcelas">
				<i class="fa fa-usd" style="font-size:14px; color:white;"></i>
			</a>

			<!-- Amortizar -->
			<a href="#" onclick="amortizar('{$id_emp}', '{$cliente}')" class="d-inline-flex justify-content-center align-items-center bg-warning text-dark rounded-circle {$mostrar_baixa} {$icone_somente_juros}" style="width:28px; height:28px;" title="Amortizar Valor">
				<i class="fa fa-usd" style="font-size:14px;"></i>
			</a>

			<!-- Baixar Empréstimo -->
			<a href="#" onclick="baixarEmprestimo('{$id_emp}', '{$total_a_pagarF}', '{$cliente}')" class="d-inline-flex justify-content-center align-items-center bg-success text-white rounded-circle {$mostrar_baixa}" style="width:28px; height:28px;" title="Baixar Empréstimo">
				<i class="fa fa-check" style="font-size:14px;"></i>
			</a>

		</div>
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
    botao.click();

}


	function baixarEmprestimo(id_emp, valor, cliente){	 
		const botao = document.getElementById('modalBaixarEmprestimo'); 
	$('#id_do_emp').val(id_emp);	
	$('#id_do_cliente').val(cliente);	
	$('#valor_final_emprestimo').val(valor);
		
     botao.click(); 
}


function novaParcela(id_emp, cliente){	
	const botao = document.getElementById('modalNovaParcela');  
	$('#id_nova_parcela').val(id_emp);
	$('#id_nova_parcela_cliente').val(cliente);
    botao.click();
    
}


function amortizar(id_emp, cliente){	
	const botao = document.getElementById('modalAmortizar');  
	$('#id_amortizar').val(id_emp);
	$('#id_amortizar_cliente').val(cliente);    
    botao.click(); 
    
}

</script>


