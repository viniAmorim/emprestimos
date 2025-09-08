<?php 
$tabela = 'clientes';

require_once("../../../conexao.php");

$id = $_POST['id'];

try {
    $pdo->beginTransaction();

    $pdo->query("DELETE FROM alertas_duplicidade WHERE id_cliente_cadastrado = '$id'");

    $query_cliente = $pdo->query("SELECT * FROM $tabela WHERE id = '$id'");
    $res = $query_cliente->fetchAll(PDO::FETCH_ASSOC);
    $total_reg = @count($res);

    if ($total_reg > 0) {
        $comprovante_rg = $res[0]['comprovante_rg'];
        $comprovante_endereco = $res[0]['comprovante_endereco'];
        $foto = $res[0]['foto'];

        if($comprovante_rg != "sem-foto.png"){
            @unlink('../../images/comprovantes/'.$comprovante_rg);
        }

        if($comprovante_endereco != "sem-foto.png"){
            @unlink('../../images/comprovantes/'.$comprovante_endereco);
        }

        if($foto != "sem-foto.jpg"){
            @unlink('../../images/clientes/'.$foto);
        }
    }
   
    $pdo->query("DELETE FROM $tabela WHERE id = '$id' ");

    
    $pdo->commit();

    echo 'Excluído com Sucesso';

} catch (PDOException $e) {
    $pdo->rollBack();
  
    echo 'Erro ao excluir: ' . $e->getMessage();
}
?>
