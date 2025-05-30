<?php 
@session_start();
$id_usuario = @$_SESSION['id'];
$tabela = 'solicitar_emprestimo';
require_once("../../../conexao.php");
$data_atual = date('Y-m-d');

$query = $pdo->query("SELECT * from $tabela where cliente = '$id_usuario' order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
echo <<<HTML
<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Valor</th>	
	<th class="esc">Parcelas</th>	
	<th class="esc">Data</th>	
	<th class="esc">Status</th>	
	<th class="esc">Garantia</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i<$linhas; $i++){
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

	if($status == 'Finalizado'){
		$classe_square = 'verde';
		$classe_baixar = 'ocultar';
		
	}else{
		$classe_square = 'text-danger';
		$classe_baixar = '';
		
	}



echo <<<HTML
<tr style="">
<td>
<input type="checkbox" id="seletor-{$id}" class="form-check-input" onchange="selecionar('{$id}')">
<i class="fa fa-square {$classe_square}"></i>
R$ {$valorF}
</td>
<td class="esc">{$parcelas}</td>
<td class="esc">{$dataF}</td>
<td class="esc {$classe_square}">{$status}</td>
<td class="esc">{$garantia}</td>
<td>
	<big><a href="#" onclick="editar('{$id}','{$valorF}','{$parcelas}','{$data}','{$obs}','{$garantia}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

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
		<span><b>Garantia :</b> {$garantia}</span><br>		
		<span><b>OBS: </b>{$obs}</span><br>
		</p>
		</div>
		</li>										
		</ul>
</li>



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
	function editar(id, valor, parcela, data, obs, garantia){
		$('#mensagem').text('');
    	$('#titulo_inserir').text('Editar Registro');

    	$('#id').val(id);
    	$('#parcelas').val(parcela);
    	$('#valor').val(valor);
    	$('#data').val(data);
    	$('#garantia').val(garantia);
    	$('#obs').val(obs);
    	
    	$('#modalForm').modal('show');
	}



	function limparCampos(){
		$('#id').val('');
    	$('#parcelas').val('');
    	$('#valor').val('');
    	$('#garantia').val('');
    	$('#data').val("<?=$data_atual?>");    	
    	$('#obs').val('');
    	

    	$('#ids').val('');
    	$('#btn-deletar').hide();	
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



	
	
</script>