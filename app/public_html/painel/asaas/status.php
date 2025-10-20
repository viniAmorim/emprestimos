<?php
@session_start();
require("../../conexao.php");
require("config/configApi.php");

$id_conta = filter_var($_GET['id'], FILTER_SANITIZE_STRING);

//ref pix testes aprovado pay_wdx0czye9gp2u4tj
$query2 = $pdo->prepare("SELECT * FROM receber where id = :id_conta");
$query2->bindValue(":id_conta", "$id_conta");
$query2->execute();
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$hash = @$res2[0]['hash'];
$cliente = @$res2[0]['cliente'];
$id_ref = @$res2[0]['id_ref'];
$valor = @$res2[0]['valor'];
$parcela = @$res2[0]['parcela'];
$nova_parcela = $parcela + 1;
$recorrencia = @$res2[0]['recorrencia'];
$referencia = @$res2[0]['referencia'];
$data_venc = @$res2[0]['data_venc'];
$dias_frequencia = @$res2[0]['frequencia'];
$descricao = @$res2[0]['descricao'];
$pago = @$res2[0]['pago'];
$ref_pix = @$res2[0]['ref_pix'];

//CALCULAR OS JUROS PARA A CONTA CASO EXISTA
$data_atual = date('Y-m-d');

if($referencia == 'Cobrança'){   
    $sql_consulta = 'cobrancas';
}else{    
    $sql_consulta = 'emprestimos';
}

$query22 = $pdo->query("SELECT * FROM $sql_consulta where id = '$id_ref'");
$res22 = $query22->fetchAll(PDO::FETCH_ASSOC);
$multa = @$res22[0]['multa'];
$juros = @$res22[0]['juros'];


$valor_multa = 0;
$valor_juros = 0;
$dias_vencido = 0;

if(@strtotime($data_venc) < @strtotime($data_atual) and $pago != 'Sim'){

$valor_multa = $multa;

//calcular quanto dias está atrasado

$data_inicio = new DateTime($data_venc);
$data_fim = new DateTime($data_atual);
$dateInterval = $data_inicio->diff($data_fim);
$dias_vencido = $dateInterval->days;

$valor_juros = $dias_vencido * ($juros * $valor / 100);
$valor_juros = number_format($valor_juros, 2, '.', '.');

$valor = $valor_juros + $valor_multa + $valor;

}

//dados do cliente
$query22 = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res22 = $query22->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = $res22[0]['nome'];
$tel_cliente = $res22[0]['telefone'];
$tel_cliente = '55'.preg_replace('/[ ()-]+/' , '' , $tel_cliente);
$telefone_envio = $tel_cliente;
$hash = @$res2[0]['hash2'];




// Verifica o pagamento se ainda não estiver marcado como pago
if ($api_pagamento == 'Asaas' and $pago != 'Sim') {
    $payment_id = $ref_pix;

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.asaas.com/v3/payments/' . $payment_id, // Consulta completa da cobrança
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'accept: application/json',
            'content-type: application/json',
            'User-Agent: MeuSistema/1.0',
            'access_token: ' . $access_token
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $resultado = json_decode($response);

    // Apenas para visualizar o retorno completo em teste:
    // echo "<pre>"; print_r($resultado); echo "</pre>"; exit();

    // Variáveis corretas agora:
    $status_api = $resultado->status ?? 'PENDING'; // Status
    $valor_liquido = $resultado->netValue ?? 0; // Valor líquido recebido
    $total_pago = $resultado->value ?? 0; // Valor da cobrança
    $tipo_pagamento = $resultado->billingType ?? 'PIX'; // Forma de pagamento

    //echo "Status: $status_api <br>";
    //echo "Valor Pago (líquido): R$ " . number_format($valor_liquido, 2, ',', '.') . "<br>";
    //echo "Valor Original: R$ " . number_format($total_pago, 2, ',', '.') . "<br>";
    //echo "Forma de Pagamento: $tipo_pagamento <br>";


    if($tipo_pagamento == 'CREDIT_CARD'){
        $tipo_pagamento = 'Cartão de Crédito';
    }
   
    if($tipo_pagamento == 'BOLETO'){
        $tipo_pagamento = 'Boleto';
    }

    if($tipo_pagamento == 'PIX '){
        $tipo_pagamento = 'Pix';
    }

    //echo $status_api;

    // Se confirmado ou recebido, atualiza como pago no banco
    if (in_array($status_api, ['RECEIVED', 'CONFIRMED'])) {
        

if($recorrencia == 'Sim'){

    if($dias_frequencia == 30 || $dias_frequencia == 31){           
            $novo_vencimento = date('Y-m-d', @strtotime("+1 month",@strtotime($data_venc)));
        }else if($dias_frequencia == 90){           
            $novo_vencimento = date('Y-m-d', @strtotime("+3 month",@strtotime($data_venc)));
        }else if($dias_frequencia == 180){ 
            $novo_vencimento = date('Y-m-d', @strtotime("6 month",@strtotime($data_venc)));
        }else if($dias_frequencia == 360 || $dias_frequencia == 365){           
            $novo_vencimento = date('Y-m-d', @strtotime("+12 month",@strtotime($data_venc)));

        }else{          
            $novo_vencimento = date('Y-m-d', @strtotime("+$dias_frequencia days",@strtotime($data_venc)));
        }

             //verificar se a parcela já está criada
    $query2 = $pdo->query("SELECT * from receber where (referencia = 'Cobrança' or referencia = 'Empréstimo') and id_ref = '$id_ref' and data_venc = '$novo_vencimento'");
    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
    $ja_criada = @count($res2);

    if($ja_criada == 0){

        //verificação de feriados
    require("verificar_feriados.php");

    //criar outra conta a receber na mesma data de vencimento com a frequência associada
    $pdo->query("INSERT INTO receber SET cliente = '$cliente', referencia = '$referencia', id_ref = '$id_ref', valor = '$valor', parcela = '$nova_parcela', usuario_lanc = '0', data = curDate(), data_venc = '$novo_vencimento', pago = 'Não', descricao = '$descricao', frequencia = '$dias_frequencia', recorrencia = 'Sim', hora_alerta = '$hora_random' ");
       $ult_id_conta = $pdo->lastInsertId();


    }
}

$pdo->query("UPDATE receber SET pago = 'Sim', valor = '$total_pago', data_pgto = curDate(), hora = curTime(), forma_pgto = '$tipo_pagamento' where ref_pix = '$ref_pix'");

    }


}

// Se já pago, redireciona direto
if ($pago == 'Sim') {
    echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=" . $url_sistema . "painel/asaas/obrigado.php?id=" . $id_conta . "'>";
    exit;
}
?>
