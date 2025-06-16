<?php
require_once("../../../conexao.php");

// Define o nome do arquivo e cabeçalho CSV
$arquivo = "clientes_export_" . date('Ymd_His') . ".csv";
header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename={$arquivo}");

// Abre o stream de saída
$output = fopen('php://output', 'w');

// Consulta
$query = $pdo->query("SELECT * FROM clientes ORDER BY id DESC");

// Verifica se há resultados
$primeira_linha = $query->fetch(PDO::FETCH_ASSOC);
if ($primeira_linha) {
    // Escreve o cabeçalho dinamicamente
    fputcsv($output, array_keys($primeira_linha));

    // Converte a primeira linha (especialmente o campo data_cad)
    $linha_convertida = $primeira_linha;
    if (isset($linha_convertida['data_cad'])) {
        $linha_convertida['data_cad'] = formatarData($linha_convertida['data_cad']);
    }
    fputcsv($output, $linha_convertida);

    // Escreve as demais linhas
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        if (isset($row['data_cad'])) {
            $row['data_cad'] = formatarData($row['data_cad']);
        }
        fputcsv($output, $row);
    }
}

fclose($output);
exit;

// Função auxiliar para formatar a data
function formatarData($data)
{
    if (!$data || $data === '0000-00-00') return '';
    $timestamp = strtotime($data);
    return $timestamp !== false ? date('d/m/Y', $timestamp) : '';
}
?>
