<?php 
@session_start();

// Pegando o ID da sessão
$id_usuario = $_SESSION['id'] ?? null;

if (!$id_usuario) {
    die('Usuário não autenticado.');
}

// Disponibilizando o ID para o include (caso o script use $_GET['id_usuario'])
$_GET['id_usuario'] = $id_usuario;

require_once("../../conexao.php");

// Captura do HTML renderizado
ob_start();
include('sintetico_ina.php');
$html = ob_get_clean();

// DOMPDF
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Inicializa o DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$pdf = new Dompdf($options);

// Configurações do PDF
$pdf->set_paper('A4', 'portrait');
$pdf->load_html($html);
$pdf->render();

// Envia o PDF ao navegador (inline)
$pdf->stream(
	'inadimplentes.pdf',
	array("Attachment" => false)
);
?>
