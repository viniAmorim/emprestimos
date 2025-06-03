<?php 
require_once("../../conexao.php");

// Receber dados do POST
$cliente = $_POST['cliente'] ?? '';
$status = $_POST['status'] ?? '';

// Garantir que o script incluído receba os POSTs
$_POST['cliente'] = $cliente;
$_POST['status']  = $status;

// Capturar a saída HTML do relatório
ob_start();
require("emprestimos.php");
$html = ob_get_clean();

// Carregar DOMPDF
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true); // permite imagens externas
$pdf = new Dompdf($options);

// Definir tamanho do papel e orientação
$pdf->set_paper('A4', 'portrait');

// Carregar HTML no DOMPDF
$pdf->load_html($html);

// Renderizar PDF
$pdf->render();

// Enviar para o navegador
$pdf->stream(
	'emprestimos.pdf',
	array("Attachment" => false) // true para forçar download
);
?>
