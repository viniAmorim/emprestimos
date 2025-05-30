<?php 
$tabela = 'receber';
require_once("../../../conexao.php");
$data_atual = date('Y-m-d');

$parcela = $_POST['parcela'];
$valor = $_POST['valor'];
$data = $_POST['data'];
$telefone = $_POST['telefone'];
$multa = $_POST['multa'];
$juros = $_POST['juros'];
$id_conta = $_POST['id_par'];
$dias_vencido = @$_POST['dias_vencido'];

$query2 = $pdo->query("SELECT * FROM receber where id = '$id_conta'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$referencia = $res2[0]['referencia'];
$id_ref = $res2[0]['id_ref'];

$tot_parcelas = '';

if($referencia == 'Empréstimo'){
		//pegar o total de parcelas do empréstimo
		$query2 = $pdo->query("SELECT * FROM receber where referencia = 'Empréstimo' and id_ref = '$id_ref'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_parcelas = @count($res2);

		$query2 = $pdo->query("SELECT * FROM emprestimos where id = '$id_ref'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$tipo_juros = $res2[0]['tipo_juros'];
		if($tipo_juros != 'Somente Júros'){
			$tot_parcelas = ' / '.$total_parcelas;
		}
}


$tel_cliente = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
$telefone_envio = $tel_cliente;

$valorF = @number_format($valor, 2, ',', '.');
$jurosF = @number_format($juros, 2, ',', '.');
$multaF = @number_format($multa, 2, ',', '.');
$dataF = implode('/', array_reverse(@explode('-', $data)));

if(strtotime($data) < strtotime($data_atual)){
	$titulo_mensagem = '❗ATENÇÃO❗%0A ⚠️ *CONSTA EM ATRASO* ⚠️ %0A';
}else{
	$titulo_mensagem = '*✅💰LEMBRETE DE PAGAMENTO* %0A';
}

$link_pgto = $url_sistema.'pagar/'.$id_conta;


//mensagem da cobrança
$mensagem = $titulo_mensagem;
$mensagem .= @mb_strtoupper($nome_sistema).' %0A%0A';

if(strtotime($data) < strtotime($data_atual)){	
	$mensagem .= '❌ *PARCELA VENCIDA* ❌ %0A';
}

if($parcela > 0){
	$mensagem .= 'Parcela: *'.$parcela.''.$tot_parcelas.'* %0A';
}

if($multa > 0){
	$mensagem .= 'Multa Atraso: *R$ '.$multaF.'* %0A';
}

if($juros > 0){
	$mensagem .= 'Júros Atraso: *R$ '.$jurosF.'* %0A';
	$mensagem .= 'Dias Atraso: *'.$dias_vencido.'* %0A';
}
$mensagem .= 'Valor: *R$ '.$valorF.'* %0A';
$mensagem .= 'Vencimento: *'.$dataF.'* %0A%0A';



if($pix_sistema != ""){
	$mensagem .= '*Chave Pix:* %0A';
	$mensagem .= $pix_sistema;	
}else{
	$mensagem .= '⬇️ CLIQUE PARA PAGAR ⬇️ %0A%0A';
	$mensagem .= '*Link Pagamento:* %0A';
	$mensagem .= $link_pgto;
}

//api wordmensagens (com urlenconde)
//$mensagem .= urlencode($link_pgto);

require('../../apis/texto.php');

?>