<?php 
$tabela = 'receber';
require_once("../../../conexao.php");
$data_atual = date('Y-m-d');
@session_start();
$id_usuario = @$_SESSION['id'];

$id = $_POST['id'];
$data_baixa = $_POST['data_baixa'];
$forma_pgto = $_POST['forma_pgto'];
$valor_final = $_POST['valor_final'];
$residuo = @$_POST['residuo'];
$residuo_final = @$_POST['residuo_final'];
$residuo_parcela = @$_POST['residuo_parcela'];

$valor_baixar = $_POST['valor_baixar'];

$valor_final = str_replace('.', '', $valor_final);
$valor_final = str_replace(',', '.', $valor_final);




$query2 = $pdo->query("SELECT * from receber where id = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$hash = @$res2[0]['hash'];
$cliente = @$res2[0]['cliente'];
$referencia = @$res2[0]['referencia'];
$id_ref = @$res2[0]['id_ref'];
$valor = @$res2[0]['valor'];
$parcela = @$res2[0]['parcela'];
$nova_parcela = $parcela + 1;
$recorrencia = @$res2[0]['recorrencia'];
$data_venc = @$res2[0]['data_venc'];
$dias_frequencia = @$res2[0]['frequencia'];
$descricao = @$res2[0]['descricao'];
$parcela_sem_juros = @$res2[0]['parcela_sem_juros'];

//verificar se a parcela está sendo paga sem redisuo e se ela possui frequencia, pois se possuir vai ser necessario puxar os valores de residuos existentes para criar a proxima
$valor_pc = 0;
if($residuo_final != "Sim" and $residuo != "Sim" and $residuo_parcela != "Sim" and $dias_frequencia > 0){
	$query2 = $pdo->query("SELECT * from receber where referencia = '$referencia' and id_ref = '$id_ref' and parcela = '$parcela'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		for($i2=0; $i2<@count($res2); $i2++){
			$valor_pc += @$res2[$i2]['valor'];
		}
		$valor = $valor_pc;
}

if($residuo_final == "Sim" or $residuo == "Sim" or $residuo_parcela == "Sim"){
	$valor_baixar = @$valor_baixar + @$multa_baixar + @$juros_baixar;
	//atualizar data do vencimento para data atual para não cobrar novamente os juros dos dias que já foram adicionados
	$pdo->query("UPDATE receber SET data_venc = curDate() where id = '$id'");
}


$parcela_seguinte = $parcela + 1;

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


$valor_residuo = 0;
if($residuo == "Sim"){
	$valor_residuo = $valor_baixar - $valor_final;
	

	if($referencia == "Empréstimo"){
		$query2 = $pdo->query("SELECT * from emprestimos where id = '$id_ref'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$valor_parcela = @$res2[0]['valor_parcela'];
		$tipo_juros = @$res2[0]['tipo_juros'];
		$valor_emp = @$res2[0]['valor'];
		$juros_do_emp = @$res2[0]['juros_emp'];
		if($tipo_juros == "Somente Júros"){
			$valor = $valor_emp * $juros_do_emp / 100;
		}
	}else{
		$query2 = $pdo->query("SELECT * from cobrancas where id = '$id_ref'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$valor_parcela = @$res2[0]['valor_parcela'];
	}

	
	$valor = $valor_parcela + $valor_residuo;

}

if($residuo_final == "Sim"){

	$valor_residuo = $valor_baixar - $valor_final;
	if($valor_residuo > 0){

		if($referencia == "Empréstimo"){
		$query2 = $pdo->query("SELECT * from emprestimos where id = '$id_ref'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$frequencia = @$res2[0]['frequencia'];
		}else{
			$query2 = $pdo->query("SELECT * from cobrancas where id = '$id_ref'");
			$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
			$frequencia = @$res2[0]['frequencia'];
		}		

		$query2 = $pdo->query("SELECT * from frequencias where frequencia = '$frequencia'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$dias_freq_emp = @$res2[0]['dias'];


		//buscar a ultima parcela
		$query2 = $pdo->query("SELECT * from receber where referencia = 'Empréstimo' and id_ref = '$id_ref' order by id desc limit 1");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$ultima_parcela = @$res2[0]['parcela'];
		$nova_parcela = $ultima_parcela + 1;
		$data_venc_ultima = @$res2[0]['data_venc'];
		$nova_data_venc = date('Y-m-d', strtotime("+$dias_freq_emp days",strtotime($data_venc_ultima)));

		$i = $nova_parcela;

		$pdo->query("INSERT INTO receber SET cliente = '$cliente', referencia = 'Empréstimo', id_ref = '$id_ref', valor = '$valor_residuo', parcela = '$nova_parcela', usuario_lanc = '$id_usuario', data = curDate(), data_venc = '$nova_data_venc', pago = 'Não', descricao = '$descricao', frequencia = '0', recorrencia = '', hora_alerta = '$hora_random' ");
		$ult_id_conta = $pdo->lastInsertId();


		$pdo->query("UPDATE emprestimos set parcelas = '$nova_parcela' where id = '$id_ref'");
	}
}


if($residuo_parcela == "Sim"){	
	if($referencia == "Empréstimo"){
		$ref_residuo = 'Empréstimo';
	}else{
		$ref_residuo = 'Cobrança';
	}
	$descricao = '(Resíduo) '.$descricao;
	$valor_residuo = $valor_baixar - $valor_final;
	if($valor_residuo > 0){
		//alterar o valor da parcela
		$pdo->query("UPDATE receber SET valor = '$valor_residuo' where id = '$id'");

		//criar um parcela paga com o valor pago
		$pdo->query("INSERT INTO receber SET cliente = '$cliente', referencia = '$ref_residuo', id_ref = '$id_ref', valor = '$valor_final', parcela = '$parcela', usuario_lanc = '$id_usuario', data = curDate(), data_venc = curDate(), data_pgto = curDate(), usuario_pgto = '$id_usuario', pago = 'Sim', descricao = '$descricao', frequencia = '0', recorrencia = '' ");
		$id = $pdo->lastInsertId();
		echo 'Salvo com Sucesso*'.$id;
		exit();
	}
}


//dados do cliente
$query = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = $res[0]['nome'];
$tel_cliente = $res[0]['telefone'];
$tel_cliente = '55'.preg_replace('/[ ()-]+/' , '' , $tel_cliente);
$telefone_envio = $tel_cliente;


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
	$pdo->query("INSERT INTO receber SET cliente = '$cliente', referencia = '$referencia', id_ref = '$id_ref', valor = '$valor', parcela = '$nova_parcela', usuario_lanc = '$id_usuario', data = curDate(), data_venc = '$novo_vencimento', pago = 'Não', descricao = '$descricao', frequencia = '$dias_frequencia', recorrencia = 'Sim', parcela_sem_juros = '$parcela_sem_juros', hora_alerta = '$hora_random' ");
	$ult_id_conta = $pdo->lastInsertId();


}else{
	//quando nao tiver recorrencia verificar se há uma nova parcela desse emprestimo / recorrencia para poder somar o valor que ficou do residuo nela
	$query2 = $pdo->query("SELECT * from receber where referencia = '$referencia' and id_ref = '$id_ref' and pago != 'Sim' and parcela = '$parcela_seguinte' ");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0 and $residuo == "Sim"){
		$id_conta = @$res2[0]['id'];
		$pdo->query("UPDATE receber SET valor = '$valor' WHERE id = '$id_conta' ");
	}

}

echo 'Salvo com Sucesso*'.$id;
?>