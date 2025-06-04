<?php 
@session_start();
require_once("../../conexao.php");

// Pega os dados do formulário
$dataInicial = $_POST['dataInicial'] ?? '';
$dataFinal = $_POST['dataFinal'] ?? '';
$cliente = $_POST['cliente'] ?? '';

// Disponibiliza como variáveis GET para o script incluído
$_GET['dataInicial'] = $dataInicial;
$_GET['dataFinal'] = $dataFinal;
$_GET['cliente'] = $cliente;

// Captura o conteúdo HTML renderizado por cobrancas.php
ob_start();
include('cobrancas.php');
$html = ob_get_clean();

// Carrega o DOMPDF
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Inicializa a classe do DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$pdf = new Dompdf($options);

// Define o tamanho do papel e orientação
$pdf->set_paper('A4', 'portrait');
$pdf->load_html($html);
$pdf->render();

// Envia o PDF ao navegador (inline)
$pdf->stream(
    'cobrancas.pdf',
    array("Attachment" => false)
);
?>
