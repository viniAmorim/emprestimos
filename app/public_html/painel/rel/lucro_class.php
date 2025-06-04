<?php 
@session_start();
require_once("../../conexao.php");

$id_usu = $_SESSION['id'] ?? '';
$nivel_usu = $_SESSION['nivel'] ?? '';

$dataInicial = $_POST['dataInicial'] ?? '';
$dataFinal = $_POST['dataFinal'] ?? '';
$cliente = $_POST['cliente'] ?? '';
$corretor = $_POST['corretor'] ?? '';

// Simula os GETs esperados pelo lucro.php
$_GET['dataInicial'] = $dataInicial;
$_GET['dataFinal'] = $dataFinal;
$_GET['id_usu'] = $id_usu;
$_GET['nivel_usu'] = $nivel_usu;
$_GET['cliente'] = $cliente;
$_GET['corretor'] = $corretor;

// Captura o HTML gerado por lucro.php
ob_start();
include('lucro.php');
$html = ob_get_clean();

// Carrega o DOMPDF
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Inicializa a classe
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$pdf = new Dompdf($options);

// Define o tamanho do papel e orientação
$pdf->set_paper('A4', 'portrait');

// Carrega o conteúdo
$pdf->load_html($html);
$pdf->render();

// Mostra o PDF no navegador (inline)
$pdf->stream('emprestimos.pdf', ['Attachment' => false]);
?>
