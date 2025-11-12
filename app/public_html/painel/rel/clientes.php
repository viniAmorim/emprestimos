<?php 
include('data_formatada.php');

if($visualizar_usuario == 'Não'){
	$sql_visualizar = " and usuario = '$id_usuario' ";
}else{
	$sql_visualizar = " ";
}

?>
<!DOCTYPE html>
<html>
<head>

<style>

@import url('https://fonts.cdnfonts.com/css/tw-cen-mt-condensed');
@page { margin: 145px 20px 25px 20px; }
#header { position: fixed; left: 0px; top: -110px; bottom: 100px; right: 0px; height: 35px; text-align: center; padding-bottom: 100px; }
#content {margin-top: 0px;}
#footer { position: fixed; left: 0px; bottom: -60px; right: 0px; height: 80px; }
#footer .page:after {content: counter(page, my-sec-counter);}
body {font-family: 'Tw Cen MT', sans-serif;}

.marca{
	position:fixed;
	left:50;
	top:100;
	width:80%;
	opacity:8%;
}

</style>

</head>
<body>
<?php 
if($marca_dagua == 'Sim'){ ?>
<img class="marca" src="<?php echo $url_sistema ?>img/logo.jpg">	
<?php } ?>


<div id="header" >

	<div style="border-style: solid; font-size: 10px; height: 50px;">
		<table style="width: 100%; border: 0px solid #ccc;">
			<tr>
				<td style="border: 1px; solid #000; width: 7%; text-align: left;">
					<img style="margin-top: 7px; margin-left: 7px;" id="imag" src="<?php echo $url_sistema ?>img/logo.jpg" width="170px">
				</td>
				<td style="width: 30%; text-align: left; font-size: 13px;">
					
				</td>
				<td style="width: 1%; text-align: center; font-size: 13px;">
				
				</td>
				<td style="width: 47%; text-align: right; font-size: 9px;padding-right: 10px;">
						<b><big>RELATÓRIO DE CLIENTES</big></b><br>  <br> <?php echo mb_strtoupper($data_hoje) ?>
				</td>
			</tr>		
		</table>
	</div>

<br>


		<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 12px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>
				
				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
					<td style="width:50%">CLIENTE</td>					
					<td style="width:30%">STATUS</td>						
					<td style="width:20%">TELEFONE</td>
					
					
				</tr>
			</thead>
		</table>
</div>

<div id="footer" class="row">
<hr style="margin-bottom: 0;">
	<table style="width:100%;">
		<tr style="width:100%;">
			<td style="width:60%; font-size: 10px; text-align: left;"><?php echo $nome_sistema ?> Telefone: <?php echo $telefone_sistema ?></td>
			<td style="width:40%; font-size: 10px; text-align: right;"><p class="page">Página  </p></td>
		</tr>
	</table>
</div>

<div id="content" style="margin-top: 0;">



		<table style="width: 100%; table-layout: fixed; font-size:11px; text-transform: uppercase;">
			<thead>
				<tbody>
					<?php

$total_ina = 0;
$total_inaF = 0;
if($status_cliente_rel == ""){
	$query9 = $pdo->query("SELECT * from clientes where id > 0 $sql_visualizar order by nome asc");
}else{
	$query9 = $pdo->query("SELECT * from clientes where status_cliente = '$status_cliente_rel' $sql_visualizar order by nome asc");
}

$res9 = $query9->fetchAll(PDO::FETCH_ASSOC);
$linhas9 = @count($res9);
if($linhas9 > 0){
for($i9=0; $i9<$linhas9; $i9++){
	$id_pessoa = $res9[$i9]['id'];
	$nome_pessoa = $res9[$i9]['nome'];
	$tel_pessoa = $res9[$i9]['telefone'];
	$status = $res9[$i9]['status_cliente'];

	$query2 = $pdo->query("SELECT * from status_clientes where nome = '$status'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$cor = @$res2[0]['cor'];
if($cor == ""){
	$ocultar_cor = 'none';
}else{
	$ocultar_cor = '';
}

  	 ?>

  	 
<tr>
<td style="width:50%">
	<?php echo $nome_pessoa ?></td>
<td style="width:30%; color:<?php echo $cor ?>"><?php echo $status ?></td>
<td style="width:20%;"> <?php echo $tel_pessoa ?></td>
    </tr>

<?php } }  ?>
				</tbody>
	
			</thead>
		</table>
	


</div>


<hr>
		
		
</body>

</html>


