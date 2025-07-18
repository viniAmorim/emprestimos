<?php 
$tabela = 'emprestimos';
require_once("../../../conexao.php");
$data_atual = date('Y-m-d');

$cliente = @$_POST['p1'];
$status = @$_POST['p2'];
$filtro_data = @$_POST['p3'] ?? 'hoje';

if($status == ""){
	$sql_status = ' status is null';
}

if($status == "Ativos"){
	$sql_status = ' status is null';
}

if($status == "Finalizado"){
	$sql_status = " status = 'Finalizado'";
}

if($status == "Perdido"){
	$sql_status = " status = 'Perdido'";
}

$data_atual = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$data_mes = $ano_atual."-".$mes_atual."-01";
$data_ano = $ano_atual."-01-01";

if($mes_atual == '04' || $mes_atual == '06' || $mes_atual == '09' || $mes_atual == '11'){
	$data_final_mes = $ano_atual.'-'.$mes_atual.'-30';
}else if($mes_atual == '02'){
	$bissexto = date('L', @mktime(0, 0, 0, 1, 1, $ano_atual));
	if($bissexto == 1){
		$data_final_mes = $ano_atual.'-'.$mes_atual.'-29';
	}else{
		$data_final_mes = $ano_atual.'-'.$mes_atual.'-28';
	}
	
}else{
	$data_final_mes = $ano_atual.'-'.$mes_atual.'-31';
}

$data_filtro_sql = ''; 

if ($filtro_data == 'hoje') {
    $data_filtro_sql = "AND data = curDate()";
} elseif ($filtro_data == 'mes') {
    $data_filtro_sql = "AND MONTH(data) = MONTH(curDate()) AND YEAR(data) = YEAR(curDate())";
} elseif ($filtro_data == 'ano') {
    $data_filtro_sql = "AND YEAR(data) = YEAR(curDate())";
} elseif ($filtro_data == 'todos') {
    $data_filtro_sql = ""; 
}

if($cliente == ""){
	$query = $pdo->query("SELECT * from $tabela where $sql_status $data_filtro_sql order by id desc");
}else{
	$query = $pdo->query("SELECT * from $tabela where $sql_status and cliente = '$cliente' $data_filtro_sql order by id desc");
}



$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
echo <<<HTML
<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Cliente</th>	
	<th class="esc">Valor / Proj. Lucro</th>	
	<th class="esc">Parcelas</th>
	<th class="esc">Data</th>	
	<th class="esc">Júros</th>	
	<th class="esc">Júros Recebidos</th>	
	<th class="esc">Próx Parcela</th>		
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;


for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
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




echo <<<HTML
<tr style="">
<td class="{$classe_debito}">
<input type="checkbox" id="seletor-{$id}" class="form-check-input" onchange="selecionar('{$id}')">
{$nome_cliente} {$classe_finalizado}
</td>
<td class="esc">R$ {$valorF} {$texto_projecao}</td>
<td class="esc">{$parcelas_pagas} / {$parcelas} <span style="color:red"><small>{$atrasadas}</small></span></td>
<td class="esc">{$dataF}</td>
<td class="esc">{$juros_emp}% <small><span class="text-primary">({$tipo_juros})</span></small></td>
<td class="esc" style="color:green">R$ {$total_jurosF}</td>
<td class="esc {$classe_debito}">{$data_ultimo_vencF}</td>
<td>
	
	<big><a href="#" onclick="editar('{$id}','{$jurosF}','{$multaF}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

	<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
</li>



<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-info-circle text-primary "></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>
		<span><b>Multa por Atraso:</b> R$ {$multaF}</span><br>
		<span><b>Júros dia Atraso:</b> {$jurosF}%</span><br>
		<span><b>Efetuado Por:</b> {$nome_usuario}</span><br>
		<span><b>Frequência Pgto:</b>{$frequencia}</span><br>
		<span><b>OBS:</b>{$obs}</span><br>
		</p>
		</div>
		</li>										
		</ul>
</li>

  <form   method="POST" action="rel/detalhamento_emprestimo_class.php" target="_blank" style="display:inline-block">
					<input type="hidden" name="id" value="{$id}">
					<big><button style="background:transparent; border:none; margin:0; padding:0" class="" title="Detalhamento Empréstimo"><i class="fa fa-file-pdf-o text-danger"></i></button></big>
		</form>	


<big><a href="#" onclick="arquivo('{$id}','{$nome_cliente}')" title="Inserir / Ver Arquivos"><i class="fa fa-file-archive-o" style="color:#3d1002"></i></a></big>

<big><a href="rel/contrato_class.php?id={$id}" target="_blank" title="Gerar Contrato"><i class="fa fa-file-pdf-o text-primary"></i></a></big>

<big><a href="#" onclick="mostrarParcelas('{$id}')" title="Mostrar Parcelas"><i class="fa fa-money verde"></i></a></big>

	<big><a class="{$mostrar_baixa}" href="#" onclick="baixarEmprestimo('{$id}', '{$total_a_pagarF}', '{$cliente}')" title="Baixar Empréstimo"><i class="fa fa-check verde "></i></a></big>

</td>
</tr>
HTML;

}


echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
HTML;

}else{
	echo '<small>Nenhum Registro Encontrado!</small>';
}
?>



<script type="text/javascript">
	$(document).ready( function () {		
    $('#tabela').DataTable({
    	"language" : {
            //"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
        },
        "ordering": false,
		"stateSave": true
    });
} );
</script>

<script type="text/javascript">

	function editar(id, juros, multa){
		$('#mensagem_empr').text('');
    	$('#titulo_empr').text('Editar Registro');

    	$('#id_empr').val(id);
    	$('#juros_empr').val(juros);
    	$('#multa_empr').val(multa);
    	    
    	$('#modalEditar').modal('show');
	}
	
	function selecionar(id){

		var ids = $('#ids').val();

		if($('#seletor-'+id).is(":checked") == true){
			var novo_id = ids + id + '-';
			$('#ids').val(novo_id);
		}else{
			var retirar = ids.replace(id + '-', '');
			$('#ids').val(retirar);
		}

		var ids_final = $('#ids').val();
		if(ids_final == ""){
			$('#btn-deletar').hide();
		}else{
			$('#btn-deletar').show();
		}
	}

	function deletarSel(){
		var ids = $('#ids').val();
		var id = ids.split("-");
		
		for(i=0; i<id.length-1; i++){
			excluir(id[i]);			
		}

		limparCampos();
	}



	function arquivo(id, nome){		    	
    	$('#nome_arquivo').text(nome);    	
    	$('#id_arquivo').val(id);    	  	
    	$('#mensagem_arquivo').text(''); 

    	listarArquivos();
    	$('#modalArquivos').modal('show');
	}

	


	
</script>


<script type="text/javascript">

	function mostrarParcelas(id_emp){	

		var mostrar = 'emprestimo';
		$("#listar_parcelas").html('Carregando!!!');
    
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
    $('#modalParcelas').modal('show');

}


	function baixarEmprestimo(id_emp, valor, cliente){	 
	$('#id_do_emp').val(id_emp);	
	$('#id_do_cliente').val(cliente);	
	$('#valor_final_emprestimo').val(valor);	
	
    $('#modalBaixarEmprestimo').modal('show');
}


</script>


