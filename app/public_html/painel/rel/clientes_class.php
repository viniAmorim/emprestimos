<?php 
@session_start();
$visualizar_usuario = @$_SESSION['visualizar'];
$id_usuario = @$_SESSION['id'];

require_once("../../conexao.php");

$status_cliente_rel = $_POST['status_cliente_rel'];

ob_start();
include("clientes.php");
$html = ob_get_clean();


//CARREGAR DOMPDF
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

header("Content-Transfer-Encoding: binary");
header("Content-Type: image/png");

//INICIALIZAR A CLASSE DO DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$pdf = new DOMPDF($options);


//Definir o tamanho do papel e orientação da página
$pdf->set_paper('A4', 'portrait');

//CARREGAR O CONTEÚDO HTML
$pdf->load_html($html);

//RENDERIZAR O PDF
$pdf->render();
//NOMEAR O PDF GERADO


$pdf->stream(
	'clientes.pdf',
	array("Attachment" => false)
);

 ?>