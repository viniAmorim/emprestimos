<?php 
@session_start();
$id_usuario = @$_SESSION['id'];
require_once("../../../../conexao.php");
$editar_contas = '';

$pagina = 'receber';
$cliente = $_POST['id'];

$tipo_juros = '';
$data_atual = date('Y-m-d');


$query = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$telefone = $res[0]['telefone'];

echo <<<HTML
<small>
HTML;
$query = $pdo->query("SELECT * FROM $pagina where referencia = 'Conta' and cliente = '$cliente'  order by id asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
echo <<<HTML
	<table class="table table-hover" id="">
		<thead> 
			<tr> 				
				
				<th>Valor</th>				
				<th>Vencimento</th>									
				<th>Baixar</th>
			</tr> 
		</thead> 
		<tbody> 
HTML;
for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
$id_par = $res[$i]['id'];
$valor = $res[$i]['valor'];
$parcela = $res[$i]['parcela'];
$data_venc = $res[$i]['data_venc'];
$data_pgto = $res[$i]['data_pgto'];
$pago = $res[$i]['pago'];
$ref_pix = $res[$i]['ref_pix'];
$dias_frequencia = $res[$i]['frequencia'];
$referencia = $res[$i]['referencia'];
$id_ref = $res[$i]['id_ref'];
$nova_parcela = $parcela + 1;
$descricao = $res[$i]['descricao'];
$recorrencia = $res[$i]['recorrencia'];
$cliente = $res[$i]['cliente'];

//dados do cliente
$query2 = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = $res2[0]['nome'];
$tel_cliente = $res2[0]['telefone'];
$tel_cliente = '55'.preg_replace('/[ ()-]+/' , '' , $tel_cliente);
$telefone_envio = $tel_cliente;


$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));
$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
$valorF = number_format($valor, 2, ',', '.');


$valor_final = $valor;
if($data_pgtoF == '00/00/0000' || $data_pgtoF == ''){
	$data_pgtoF = 'Pendente';
	$classe_pago = 'text-danger';
	$ocultar_baixar = '';
	$ocultar_pendentes = 'ocultar';
}else{
	$classe_pago = 'text-success';
	$ocultar_baixar = 'ocultar';
	$ocultar_pendentes = '';
}

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

$valor_final = $valor_juros + $valor_multa + $valor;

}

$valor_finalF = @number_format($valor_final, 2, ',', '.');

if($parcela == 0){
	$parcela = 'Quitado';
}

$read_only = '';
if($editar_contas != ""){
	$read_only = 'readonly';
}


//verificar se já existe uma conta exatamente igual essa no banco de dados para não criar uma nova


if($recorrencia == 'Sim' and $tipo_juros == 'Somente Júros' and @strtotime($data_venc) < @strtotime($data_atual) and $pago != 'Sim' ){


	if($dias_frequencia == 30 || $dias_frequencia == 31){			
			$novo_vencimento = date('Y-m-d', @strtotime("+1 month",@strtotime($data_venc)));
		}else if($dias_frequencia == 90){			
			$novo_vencimento = date('Y-m-d', @strtotime("+3 month",@strtotime($data_venc)));
		}else if($dias_frequencia == 180){ 
			$novo_vencimento = date('Y-m-d', @strtotime("6 month",@strtotime($data_venc)));
		}else if($dias_frequencia == 360 || $dias_frequencia == 365){ 			
			$novo_vencimento = date('Y-m-d', @strtotime("+12 month",@strtotime($data_venc)));

		}else{			
			$novo_vencimento = date('Y-m-d', @strtotime("+$dias_frequencia days",@strtotime($data_venc)));
		}




		//verificação de feriados
	require("../../verificar_feriados.php");

	//verificar se a parcela já está criada
	$query2 = $pdo->query("SELECT * from receber where (referencia = 'Cobrança' or referencia = 'Empréstimo') and id_ref = '$id_ref' and data_venc = '$novo_vencimento'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$ja_criada = @count($res2);

	
	if($ja_criada == 0){
	//criar outra conta a receber na mesma data de vencimento com a frequência associada
	$pdo->query("INSERT INTO receber SET cliente = '$cliente', referencia = '$referencia', id_ref = '$id_ref', valor = '$valor', parcela = '$nova_parcela', usuario_lanc = '$id_usuario', data = curDate(), data_venc = '$novo_vencimento', pago = 'Não', descricao = '$descricao', frequencia = '$dias_frequencia', recorrencia = 'Sim', hora_alerta = '$hora_random' ");
	$ult_id_conta = $pdo->lastInsertId();



$pdo->query("UPDATE receber SET recorrencia = '' where id = '$id_par'");

}

}

echo <<<HTML
			<tr>					
				
				<td class="{$classe_pago}"><input $read_only type="text" name="valor" id="valor_{$id_par}" value="{$valorF}" style="width:40px; background: transparent; border:none; outline: none; border-bottom:1px solid #000;" onkeyup="mascara_valor('valor_{$id_par}')" onblur="editar_contas_parc('{$id_par}')"></td>				
				<td class="{$classe_venc}"><input $read_only type="date" name="data_venc" id="data_venc_{$id_par}" value="{$data_venc}" style="width:110px; background: transparent; border:none; outline: none; border-bottom:1px solid #000; height:21px" onchange="editar_contas_parc('{$id_par}')"></td>
				

				
				<td>	

					<a href="#" onclick="baixarParcelaParc('{$id_par}', '{$valor}', '{$valor_multa}', '{$valor_juros}')" title="Dar Baixa"
   style="width:23px; height:23px; border-radius:50%; background:#28a745; color:#fff; display:inline-block; text-align:center; line-height:23px;"
   class="{$ocultar_baixar}">
   <i class="fa fa-check-square" style="font-size:13px;"></i>
</a>

<a href="#" onclick="cobrarParc('0', '{$parcela}', '{$valor_final}', '{$data_venc}', '{$telefone}', '{$valor_multa}', '{$valor_juros}', '{$id_par}', '{$dias_vencido}')"
   title="Cobrança"
   style="width:23px; height:23px; border-radius:50%; background:#25d366; color:#fff; display:inline-block; text-align:center; line-height:23px;"
   class="{$ocultar_baixar}">
   <i class="bi bi-whatsapp" style="font-size:13px;"></i>
</a>

<form method="POST" action="../../painel/rel/recibo_class.php" style="display:inline;">
   <input type="hidden" name="id" value="{$id_par}">
   <input type="hidden" name="enviar" value="Não">
   <button type="submit" title="Recibo" class="{$ocultar_pendentes}"
      style="width:23px; height:23px; border-radius:50%; background:#dc3545; color:#fff; display:inline-block; text-align:center; line-height:23px; border:none;">
      <i class="fa fa-file-pdf" style="font-size:13px;"></i>
   </button>
</form>




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
	echo 'Nenhum registro cadastrado!';
}

?>


<script type="text/javascript">

	function editar_contas_parc(id){
	var valor = $('#valor_'+id).val();
	var data = $('#data_venc_'+id).val();	

	 $.ajax({
	        url: '../../painel/paginas/clientes/editar_valores.php',
	        method: 'POST',
	        data: {id, valor, data},
	        dataType: "html",

	        success:function(result){   
                toast(result, 'verde');	           
	        }
	    });
}


function baixarParcelaParc(id_par, valor, multa, juros){	 

		const botao = document.getElementById('btn_baixar'); 
	$('#id_baixar').val(id_par);	
	$('#valor_baixar').val(valor);
	$('#multa_baixar').val(multa);
	$('#juros_baixar').val(juros);

	const residuoFinal = document.getElementById('residuo_final');
  	const residuo = document.getElementById('residuo');
   residuo.checked = false;
   residuoFinal.checked = false;

	calcular();
    botao.click();
}

function cobrarParc(id_emp, parcela, valor, data, telefone, multa, juros, id_par, dias_vencido){

	var instancia = "<?=$instancia?>";
	var token = "<?=$token?>";

	var id_empr = $('#id_emprestimo').val();
	var id_cob = $('#id_cobranca').val();
	
	if(multa == ""){
		multa = 0;
	}

	if(juros == ""){
		juros = 0;
	}


	if(instancia.trim() == "" || token.trim() == ""){
		alert('Insira um Token e Instancia Whatsapp nas configurações');
		return;
	}
	$.ajax({
	        url: '../../painel/paginas/clientes/gerar_cobranca.php',
	        method: 'POST',
	        data: {parcela, valor, data, telefone, multa, juros, id_par, dias_vencido},
	        dataType: "html",

	        success:function(result){	
	        //alert(result) 
	        	alertsucessoInfo('Cobrança Efetuada!');           	
	           	 
	        }
	    });
}

</script>


