<?php
require_once '../../funcoes/SimpleXLSX.php';
require_once("../../../conexao.php");

use Shuchkin\SimpleXLSX; // <-- ESSENCIAL para funcionar

try {
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] == 0) {
        $arquivoTmp = $_FILES['arquivo']['tmp_name'];
        $extensao = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);

        if ($extensao === 'xlsx') {
            if ($xlsx = SimpleXLSX::parse($arquivoTmp)) {
                $rows = $xlsx->rows();

                // Remove cabeçalho
                unset($rows[0]);

                $sql = "INSERT INTO clientes 
                    (nome, telefone, cpf, email, endereco, data_nasc, data_cad, obs, bairro, cidade, estado, cep, telefone_sec, senha_crip, foto)
                    VALUES (:nome, :telefone, :cpf, :email, :endereco, :data_nasc, :data_cad, :obs, :bairro, :cidade, :estado, :cep, :telefone_sec, :senha_crip, :foto)";

                $stmt = $pdo->prepare($sql);

                foreach ($rows as $row) {
                    $senha = $row[13] ?? '123'; // senha opcional na 14ª coluna (índice 13)
                    $senha_crip = password_hash($senha, PASSWORD_DEFAULT);

                    $stmt->execute([
                        ':nome'         => $row[0] ?? null,
                        ':telefone'     => $row[1] ?? null,
                        ':cpf'          => $row[2] ?? null,
                        ':email'        => $row[3] ?? null,
                        ':endereco'     => $row[4] ?? null,
                        ':data_nasc'    => !empty($row[5]) ? date('Y-m-d', strtotime($row[5])) : null,
                        ':data_cad'     => !empty($row[6]) ? date('Y-m-d', strtotime($row[6])) : date('Y-m-d'),
                        ':obs'          => $row[7] ?? null,
                        ':bairro'       => $row[8] ?? null,
                        ':cidade'       => $row[9] ?? null,
                        ':estado'       => $row[10] ?? null,
                        ':cep'          => $row[11] ?? null,
                        ':telefone_sec' => $row[12] ?? null,
                        ':senha_crip'   => $senha_crip,
                        ':foto'   => 'sem-foto.jpg',
                    ]);
                }

                //echo "Importação concluída com sucesso!";
            } else {
                echo "Erro ao ler o arquivo XLSX: " . SimpleXLSX::parseError();
            }
        } else {
            echo "Formato inválido. Envie um arquivo .xlsx.";
        }
    } else {
        echo "Nenhum arquivo enviado.";
    }
} catch (PDOException $e) {
    echo "Erro no banco de dados: " . $e->getMessage();
}


echo 'Importado com Sucesso';