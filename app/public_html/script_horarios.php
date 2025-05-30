<?php 
require_once("conexao.php");





$query = $pdo->query("SELECT * from receber where pago != 'Sim' and cliente > 0 and cliente is not null order by id asc limit 0,200 ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas = @count($res);
for ($i = 0; $i < $contas; $i++){

	$id_conta = $res[$i]['id'];

	//variaveis para os disparos de notificações
$hora_rand = rand(9, 16);
$minutos_rand = rand(0, 59);
if($hora_rand < 10){
	$hora_rand = '0'.$hora_rand;
}
if($minutos_rand < 10){
	$minutos_rand = '0'.$minutos_rand;
}	

$hora_random = $hora_rand.':'.$minutos_rand.':00';

$pdo->query("UPDATE receber SET hora_alerta = '$hora_random' where id = '$id_conta' ");

}


?>