<?php 
require_once("../../conexao.php");

$id = $_GET['id'];

// Caminho alternativo (uso via include)
ob_start();
include("contrato.php"); // contrato.php deve tratar $_GET['id']
$html = ob_get_clean();

/*
// Alternativa via HTTP - cuidado com localhost
$html = @file_get_contents($url_sistema."painel/rel/contrato.php?id=$id");
if ($html === false) {
    die("Erro ao carregar o HTML do contrato.");
}
*/

//CARREGAR DOMPDF
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

//INICIALIZAR A CLASSE DO DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$pdf = new DOMPDF($options);

//Definir o tamanho do papel e orientação da página
$pdf->set_paper('A4', 'portrait');

//CARREGAR O CONTEÚDO HTML
$pdf->load_html($html);

//RENDERIZAR O PDF
$pdf->render();

//NOMEAR O PDF GERADO
$pdf->stream(
	'contrato.pdf',
	array("Attachment" => false)
);
?>
