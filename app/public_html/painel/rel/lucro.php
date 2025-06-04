<?php 
include('../../conexao.php');
include('data_formatada.php');

$dataFinal = $_GET['dataFinal'];
$dataInicial = $_GET['dataInicial'];
$id_usu = $_GET['id_usu'];
$nivel_usu = $_GET['nivel_usu'];
$cliente = $_GET['cliente'];
$corretor = $_GET['corretor'];

$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(explode('-', $dataFinal)));

$datas = ($dataInicial == $dataFinal) ? 
    "Período de Apuração: $dataInicialF" : 
    "Período de Apuração: $dataInicialF à $dataFinalF";
?>
<!DOCTYPE html>
<html>
<head>
<style>
@import url('https://fonts.cdnfonts.com/css/tw-cen-mt-condensed');
@page { margin: 145px 20px 25px 20px; }
#header { position: fixed; top: -110px; height: 35px; text-align: center; padding-bottom: 100px; }
#footer { position: fixed; bottom: -60px; height: 80px; }
#footer .page:after { content: counter(page, my-sec-counter); }
body { font-family: 'Tw Cen MT', sans-serif; }
.text-danger { color: red; }
.text-primary { color: blue; }
.green { color: green; }
.red { color: red; }
</style>
</head>
<body>

<?php
$src_logo = 'data:image/jpeg;base64,' . base64_encode(file_get_contents('../../img/logo.jpg'));
?>

<div id="header">
	<div style="border-style: solid; font-size: 10px; height: 50px;">
		<table style="width: 100%;">
			<tr>
				<td style="width: 20%;">
					<img src="<?= $src_logo ?>" width="160px" style="margin-top: 5px; margin-left: 7px;">
				</td>
				<td style="width: 80%; text-align: right; font-size: 9px; padding-right: 10px;">
					<b><big>RELATÓRIO DE EMPRÉSTIMOS</big></b><br>
					<?= mb_strtoupper($datas) ?><br>
					<?= mb_strtoupper($data_hoje) ?>
				</td>
			</tr>		
		</table>
	</div>
	<br>
	<table style="border-bottom-style: solid; font-size: 9px; margin-bottom:10px; width: 100%;">
		<tr style="background-color:#CCC">
			<td style="width:25%">CLIENTE</td>
			<td style="width:10%">EMPRESTADO</td>
			<td style="width:10%">PARCELAS</td>
			<td style="width:10%">DATA</td>
			<td style="width:17%">JÚROS %</td>					
			<td style="width:13%">FREQ. PGTO</td>
			<td style="width:9%">TOTAL PAGO</td>
			<td style="width:8%">LUCRO</td>
		</tr>
	</table>
</div>

<div id="footer">
	<hr style="margin-bottom: 0;">
	<table style="width:100%;">
		<tr>
			<td style="width:60%; font-size: 10px; text-align: left;">
				<?= $nome_sistema ?> / Telefone: <?= $telefone_sistema ?> / Email: <?= $email_sistema ?>
			</td>
			<td style="width:40%; font-size: 10px; text-align: right;">
				<p class="page">Página</p>
			</td>
		</tr>
	</table>
</div>

<div id="content">
	<table style="width: 100%; font-size:9px; text-transform: uppercase;">
		<tbody>
<?php
$valor_emprestado = 0;
$valor_do_lucro_total = 0;
$classe_lucro_final = 'red';

$sql_corretor = $corretor ? "AND usuario = '$corretor'" : '';
$sql_cliente = $cliente ? "AND cliente = '$cliente'" : '';

$query = $pdo->query("SELECT * FROM emprestimos WHERE data BETWEEN '$dataInicial' AND '$dataFinal' $sql_corretor $sql_cliente ORDER BY id DESC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($res as $row) {
	$id = $row['id'];
	$valor = $row['valor'];
	$parcelas = $row['parcelas'];
	$juros_emp = $row['juros_emp'];
	$data = $row['data'];
	$cliente_id = $row['cliente'];
	$frequencia = $row['frequencia'];
	$tipo_juros = $row['tipo_juros'];

	$valor_emprestado += $valor;
	$dataF = implode('/', array_reverse(explode('-', $data)));
	$valorF = number_format($valor, 2, ',', '.');

	$nome_cliente = $pdo->query("SELECT nome FROM clientes WHERE id = '$cliente_id'")->fetchColumn();
	$nome_usuario = $pdo->query("SELECT nome FROM usuarios WHERE id = '{$row['usuario']}'")->fetchColumn();

	// Débito
	$classe_debito = '';
	$pendentes = $pdo->query("SELECT COUNT(*) FROM receber WHERE referencia = 'Empréstimo' AND id_ref = '$id' AND pago = 'Não' AND data_venc < CURDATE()")->fetchColumn();
	if ($pendentes > 0) $classe_debito = 'text-danger';

	// Parcelas pagas e lucro
	$parcelas_pagas = $pdo->query("SELECT * FROM receber WHERE referencia = 'Empréstimo' AND id_ref = '$id' AND pago = 'Sim'")->fetchAll(PDO::FETCH_ASSOC);
	$total_pago = array_sum(array_column($parcelas_pagas, 'valor'));
	$lucro = $total_pago - $valor;

	$valor_total_pagoF = number_format($total_pago, 2, ',', '.');
	$lucroF = number_format($lucro, 2, ',', '.');
	$classe_lucro = ($lucro > 0) ? 'green' : 'red';

	echo "
	<tr>
		<td class='{$classe_debito}' style='width:25%'>{$nome_cliente}</td>
		<td style='width:10%'>R$ {$valorF}</td>
		<td style='width:10%'>" . count($parcelas_pagas) . " / {$parcelas}</td>
		<td style='width:10%'>{$dataF}</td>
		<td style='width:15%'>{$juros_emp}% <small><span class='text-primary'>({$tipo_juros})</span></small></td>
		<td style='width:13%'>{$frequencia}</td>
		<td style='width:9%; color:green'>R$ {$valor_total_pagoF}</td>
		<td style='width:8%; color:{$classe_lucro}'>R$ {$lucroF}</td>
	</tr>";

	$valor_do_lucro_total += $lucro;
}

$classe_lucro_final = ($valor_do_lucro_total > 0) ? 'green' : 'red';
$valor_do_lucro_totalF = number_format($valor_do_lucro_total, 2, ',', '.');
?>
		</tbody>
	</table>
</div>

<hr>
<table>
	<tr>
		<td colspan="3" style="font-size: 10px; width: 540px;"></td>
		<td style="font-size: 10px; width:180px; text-align: right;">
			TOTAL LUCRO: <span class="<?= $classe_lucro_final ?>">R$ <?= $valor_do_lucro_totalF ?></span>
		</td>
	</tr>
</table>

</body>
</html>
