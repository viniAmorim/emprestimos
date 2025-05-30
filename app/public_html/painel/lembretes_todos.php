<?php 
require_once("../conexao.php");
$data_atual = date('Y-m-d');

$hora_atual = date('H');
$hora_alerta = $hora_atual + 1;

$frequencia_index = @$_POST['frequencia_index'];
$status_cliente_index = @$_POST['status_cliente_index'];

$total_cobrancas = 0;

//variaveis para os disparos de notificações
$hora_rand = rand($hora_alerta, $hora_alerta);
$minutos_rand = rand(0, 59);
if($hora_rand < 10){
	$hora_rand = '0'.$hora_rand;
}
if($minutos_rand < 10){
	$minutos_rand = '0'.$minutos_rand;
}	

$hora_random_script = $hora_rand.':'.$minutos_rand.':00';

//verificar se tem conta vencidas
	$query = $pdo->query("SELECT * from receber where pago = 'Não' and data_venc = curDate()");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$linhas = @count($res);
	if($linhas > 0){
		for($i=0; $i<$linhas; $i++){	


			$cliente = @$res[$i]['cliente'];
			$referencia = @$res[$i]['referencia'];
			$id_ref = @$res[$i]['id_ref'];
			$id_conta = $res[$i]['id'];	

			if($frequencia_index > 0){
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
				
				if($frequencia_index != $dias_freq_emp){
					continue;
				}

				

			}

			if($status_cliente_index != ""){
				$query2 = $pdo->query("SELECT * from clientes where id = '$cliente'");
				$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
				$status_cliente = @$res2[0]['status_cliente'];

				if($status_cliente != $status_cliente_index){
					continue;
				}
			}

			$total_cobrancas += 1;
					
			$id_conta = $res[$i]['id'];	
			$pdo->query("UPDATE receber set hora_alerta = '$hora_random_script', alerta = null where id = '$id_conta'");
		        
			}
	}


echo 'Total de '.$total_cobrancas.' lembretes de pagamentos agendados para serem disparados dentro de uma hora !!';

?>