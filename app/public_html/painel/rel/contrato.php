<?php 
require_once("../../conexao.php");
$id = $_GET['id'] ?? 0;

$query = $pdo->prepare("SELECT * FROM emprestimos WHERE id = :id");
$query->execute(['id' => $id]);
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = count($res);

if ($linhas > 0) {
    $valor = $res[0]['valor'];
    $parcelas = $res[0]['parcelas'];
    $juros_emp = $res[0]['juros_emp'];
    $data_venc = $res[0]['data_venc'];
    $data = $res[0]['data'];
    $cliente = $res[0]['cliente'];
    $juros = $res[0]['juros'];
    $multa = $res[0]['multa'];
    $usuario = $res[0]['usuario'];
    $obs = $res[0]['obs'];
    $frequencia = $res[0]['frequencia'];
    $tipo_juros = $res[0]['tipo_juros'];

    $data_vencF = date('d', strtotime($data_venc));
    $dataF = implode('/', array_reverse(explode('-', $data)));
    $valorF = number_format($valor, 2, ',', '.');
    $jurosF = number_format($juros, 2, ',', '.');
    $multaF = number_format($multa, 2, ',', '.');

    $query2 = $pdo->prepare("SELECT * FROM clientes WHERE id = :cliente");
    $query2->execute(['cliente' => $cliente]);
    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
    $nome_cliente = $res2[0]['nome'] ?? '';
    $cpf_cliente = $res2[0]['cpf'] ?? '';
    $pessoa = $res2[0]['pessoa'] ?? '';

    $tipo_pessoa = ($pessoa == "Física") ? ' CPF ' : ' empresa inscrita no CNPJ ';

    $query2 = $pdo->prepare("SELECT * FROM usuarios WHERE id = :usuario");
    $query2->execute(['usuario' => $usuario]);
    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
    $nome_usuario = $res2[0]['nome'] ?? '';

    $classe_debito = '';
    $query2 = $pdo->prepare("SELECT * FROM receber WHERE referencia = 'Empréstimo' AND id_ref = :id AND pago = 'Não' AND data_venc < CURDATE()");
    $query2->execute(['id' => $id]);
    if ($query2->rowCount() > 0) {
        $classe_debito = 'text-danger';
    }

    $query2 = $pdo->prepare("SELECT * FROM receber WHERE referencia = 'Empréstimo' AND id_ref = :id AND pago = 'Sim'");
    $query2->execute(['id' => $id]);
    $parcelas_pagas = $query2->rowCount();
} else {
    echo 'Empréstimo não encontrado!';
    exit();
}

include('data_formatada.php');
$data_atual = $data_hoje;
?>

<div align="center" style="border-bottom:1px solid #000; font-size:18px; font-weight: bold">
    Modelo de Contrato de Empréstimo Empresa Simples de Crédito – (ESC)
</div>

<p style="font-size:11px; color:red; text-align:center">
    Consultar um profissional especializado para avaliar as melhores condições para seu caso. Este é apenas um modelo de exemplo!
</p>

<div style="font-size:13px">
    <p>
        O presente contrato define as condições gerais aplicáveis ao empréstimo concedido pela <b><?php echo mb_strtoupper($nome_sistema) ?></b>, na data de <?php echo $dataF ?>, <?php if ($cnpj_sistema != "") { ?>inscrita no CNPJ sob o número <?php echo $cnpj_sistema ?>, doravante denominada Mutuante, <?php } ?>e <b><?php echo $nome_cliente ?></b>, <?php echo $tipo_pessoa . $cpf_cliente ?>, doravante denominada Mutuária, de acordo com a Lei Complementar nº 167 de 25/04/2018.
    </p>

    <br><br><b>CLÁUSULA PRIMEIRA: DEFINIÇÕES</b>
    <p>a) <b>TAXA DE JUROS TOTAL</b> – Remuneração da ESC, incluindo encargos e despesas da operação.</p>
    <p>b) <b>COAF</b> – Conselho de Controle de Atividades Financeiras.</p>
    <p>c) <b>CONTRATO</b> – O presente instrumento e eventuais anexos.</p>
    <p>d) <b>CONTRATANTE MUTUÁRIA</b> – Parte tomadora do empréstimo.</p>
    <p>e) <b>IOF</b> – Imposto sobre Operações Financeiras incidente.</p>
    <p>f) <b>CONTRATADA MUTUANTE</b> – A ESC que concede o empréstimo.</p>
    <p>g) <b>DEVEDOR SOLIDÁRIO</b> – Pessoa física garantidora do contrato.</p>
    <p>h) <b>QUADRO RESUMO</b> – Informações detalhadas sobre valores, prazos, juros e garantias.</p>

    <br><br><b>CLÁUSULA SEGUNDA: O EMPRÉSTIMO</b>
    <p>a) A Mutuante concede à Mutuária um empréstimo conforme Quadro Resumo.</p>
    <p>b) A Mutuária deverá restituir o valor total com juros compostos diários.</p>
    <p>c) A tolerância no pagamento não configura novação ou alteração contratual.</p>

    <br><br><b>CLÁUSULA TERCEIRA: TARIFAS</b>
    <p>a) A Mutuante não cobrará tarifa de originação.</p>

    <br><br><b>CLÁUSULA QUARTA: O PAGAMENTO DO EMPRÉSTIMO</b>
    <p>a) Parcelas poderão ser representadas por Notas Promissórias.</p>
    <p>b) Garantias poderão incluir cessão fiduciária de direitos creditórios.</p>
    <p>c) Se o vencimento cair em dia não útil, o pagamento poderá ser feito no próximo dia útil.</p>

    <br><br><b>CLÁUSULA QUINTA: PAGAMENTO ANTECIPADO</b>
    <p>a) Permitido, com redução proporcional dos juros.</p>

    <br><br><b>CLÁUSULA SEXTA: ATRASO</b>
    <p>a) Qualquer inadimplemento caracteriza atraso.</p>
    <p>b) Não exige notificação prévia para ser configurado.</p>
    <p>c) Encargos por atraso:<br>
        - Juros moratórios: <b><?php echo $juros ?>%</b> ao dia<br>
        - Multa moratória: <b>R$ <?php echo $multaF ?></b></p>

    <br><br><b>CLÁUSULA SÉTIMA: VENCIMENTO ANTECIPADO</b>
    <p>a) Ocorrerá em caso de fraude ou informações falsas no contrato.</p>

    <br><br><b>CLÁUSULA OITAVA: DISPOSIÇÕES GERAIS</b>
    <p>a) A Mutuária declara veracidade das informações e está ciente das leis aplicáveis.</p>
    <p>b) Autoriza consulta de crédito e compartilhamento com órgãos de proteção.</p>
    <p>c) Contrato poderá ser registrado em entidade autorizada pelo Banco Central.</p>
    <p>d) Contrato constitui título executivo extrajudicial.</p>
    <p>e) Foro da sede da Mutuante será eleito para dirimir dúvidas.</p>
    <p>f) Firmado em 3 vias de igual teor.</p>
    <p>(<?php echo mb_strtoupper($data_atual) ?>)</p>

    <br><br><br>
    ___________________________________________________________<br>
    <b><?php echo $nome_cliente ?><br>
    <?php echo $cpf_cliente ?><br>
    CONTRATANTE MUTUÁRIA</b>

    <br><br><br><br>
    ___________________________________________________________<br>
    <b>Nome do Devedor Solidário<br>
    CPF do Devedor Solidário<br>
    DEVEDOR SOLIDÁRIO</b>

    <br><br><br><br>
    ___________________________________________________________<br>
    <b><?php echo $nome_usuario ?><br>
    CONTRATADA MUTUANTE</b>

    <br><br><br><br>
    ___________________________________________________________<br>
    Testemunha 1 - Nome e CPF

    <br><br><br><br>
    ___________________________________________________________<br>
    Testemunha 2 - Nome e CPF
</div>
