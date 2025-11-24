<?php 
$tabela = 'receber';
require_once("../../../conexao.php");

@session_start();
$id_usuario = @$_SESSION['id'];

$id = $_POST['id'];
$data_atual = date('Y-m-d');

$data_baixa = $data_atual;



//recuperar o hash e excluir o agendamento das mensagens
$query2 = $pdo->query("SELECT * from receber where id = '$id' and pago != 'Sim'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$hash = @$res2[0]['hash'];
$cliente = @$res2[0]['cliente'];
$id_ref = @$res2[0]['id_ref'];
$valor = @$res2[0]['valor'];
$parcela = @$res2[0]['parcela'];
$nova_parcela = $parcela + 1;
$recorrencia = @$res2[0]['recorrencia'];
$data_venc = @$res2[0]['data_venc'];
$dias_frequencia = @$res2[0]['frequencia'];
$descricao = @$res2[0]['descricao'];
$forma_pgto = @$res2[0]['forma_pgto'];
$valor_final = @$res2[0]['valor'];
$referencia = @$res2[0]['referencia'];
$parcela_sem_juros = @$res2[0]['parcela_sem_juros'];


if($referencia == "Empréstimo"){
	$query2 = $pdo->query("SELECT * from emprestimos where id = '$id_ref'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_comissao = @$res2[0]['comissao'];
	$usuario_emprestimo = @$res2[0]['usuario'];

	$query2 = $pdo->query("SELECT * from usuarios where id = '$usuario_emprestimo'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$nome_usuario = @$res2[0]['nome'];

	if($total_comissao > 0){
		$total_lucro = $valor - $parcela_sem_juros;
		$total_valor_comissao = $total_lucro * $total_comissao / 100;

		$descricao_comissao = 'Comissão: '.$nome_usuario;

		//lançar o valor da comissão na tabela de contas a pagar
		$pdo->query("INSERT INTO pagar SET descricao = '$descricao_comissao', valor = '$total_valor_comissao', data = curDate(), data_venc = curDate(), usuario_lanc = '$id_usuario', referencia = 'Comissão', pago = 'Não', funcionario = '$usuario_emprestimo' ");
	}
	
}

// se quiser que no emprestimos somente juros ele pegue a proxima parcela com base no valor atual do emprestimo, no caso de ele ter sido amortizado

if($referencia == "Empréstimo" and $juros_amortizacao != 'Não'){
		$query2 = $pdo->query("SELECT * from emprestimos where id = '$id_ref'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$valor_parcela = @$res2[0]['valor_parcela'];
		$tipo_juros = @$res2[0]['tipo_juros'];
		$valor_emp = @$res2[0]['valor'];
		$juros_do_emp = @$res2[0]['juros_emp'];
		if($tipo_juros == "Somente Júros"){
			$valor = $valor_emp * $juros_do_emp / 100;
		}
	}


$pdo->query("UPDATE $tabela SET data_pgto = '$data_baixa', pago = 'Sim', forma_pgto = '$forma_pgto', valor = '$valor_final', usuario_pgto = '$id_usuario', hora = curTime() WHERE id = '$id' ");

if($recorrencia == 'Sim'){

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

	//criar outra conta a receber na mesma data de vencimento com a frequência associada
	$pdo->query("INSERT INTO receber SET cliente = '$cliente', referencia = '$referencia', id_ref = '$id_ref', valor = '$valor', parcela = '$nova_parcela', usuario_lanc = '$id_usuario', data = curDate(), data_venc = '$novo_vencimento', pago = 'Não', descricao = '$descricao', frequencia = '$dias_frequencia', recorrencia = 'Sim', hora_alerta = '$hora_random' ");
}


echo 'Baixado com Sucesso';
?>