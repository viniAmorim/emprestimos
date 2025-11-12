<?php 
require_once("../conexao.php");

$query = $pdo->query("SELECT * from receber where data_venc = curDate() and pago != 'Sim' and (alerta is null or alerta != 'Sim') and cliente > 0 and cliente is not null and hora_alerta <= curTime() ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pagar_vencidas = @count($res);
for ($i = 0; $i < $contas_pagar_vencidas; $i++) {
	$valor = $res[$i]['valor'];
	$descricao = $res[$i]['descricao'];
	$id = $res[$i]['id'];	
	$cliente = $res[$i]['cliente'];
	$vencimento = $res[$i]['data_venc'];
	$parcela = $res[$i]['parcela'];
	$referencia = $res[$i]['referencia'];
	$id_ref = $res[$i]['id_ref'];

	$tot_parcelas = '';

	if($referencia == 'Cobrança'){		
		$sql_consulta = 'cobrancas';
	}else{		
		$sql_consulta = 'emprestimos';

		//pegar o total de parcelas do empréstimo
		$query2 = $pdo->query("SELECT * FROM receber WHERE referencia = 'Empréstimo' AND id_ref = '$id_ref' GROUP BY parcela");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_parcelas = @count($res2);

		$query2 = $pdo->query("SELECT * FROM emprestimos where id = '$id_ref'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$tipo_juros = @$res2[0]['tipo_juros'];
		if($tipo_juros != 'Somente Júros'){
			$tot_parcelas = ' / '.$total_parcelas;
		}
	}

	$vencimentoF = implode('/', array_reverse(@explode('-', $vencimento)));

	$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if (@count($res2) > 0) {
		$nome_cliente = $res2[0]['nome'];
		$telefone_cliente = $res2[0]['telefone'];
		$bloquear_disparos = $res2[0]['bloquear_disparos'];
	} else {
		$nome_cliente = 'Sem Registro';
		$telefone_cliente = "";
		$bloquear_disparos = "";
	}

$telefone_envio = '55' . preg_replace('/[ ()-]+/', '', $telefone_cliente);

$link_pgto = $url_sistema.'pagar/'.$id;
$valorF = @number_format($valor, 2, ',', '.');

$mensagem = '*✅💰SUA PARCELA VENCE HOJE* %0A';
$mensagem .= @mb_strtoupper($nome_sistema).' %0A%0A';

if($referencia == 'Cobrança'){
		$mensagem .= 'Descrição: *'.$descricao.'* %0A';
	}

if($parcela > 0){
		$mensagem .= 'Parcela: *'.$parcela.''.$tot_parcelas.'* %0A';
	}

$mensagem .= 'Valor: *'.$valorF.'* %0A';
$mensagem .= 'Vencimento: *'.$vencimentoF.'* %0A%0A';
if($pix_sistema != ""){
    $mensagem .= '*Chave Pix:* %0A';
    $mensagem .= $pix_sistema;  
}else{
	$mensagem .= '⬇️ CLIQUE PARA PAGAR ⬇️ %0A%0A';
    $mensagem .= '*Link Pagamento:* %0A';
    $mensagem .= $link_pgto;
}

if($bloquear_disparos != "Sim"){
	require('texto.php');
}

		if(@$status_mensagem == "Mensagem enviada com sucesso." and $seletor_api == 'menuia'){
			$pdo->query("UPDATE receber SET alerta = 'Sim' where id = '$id'");
		}

		if($seletor_api != 'menuia'){
			$pdo->query("UPDATE receber SET alerta = 'Sim' where id = '$id'");
		}


}

echo $contas_pagar_vencidas;

 ?>