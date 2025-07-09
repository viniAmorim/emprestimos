<?php
// Este arquivo é incluído pelo index.php, então a conexão já deve estar disponível.
// Se 'conexao.php' não for incluído pelo index.php, mantenha a linha abaixo.
// require_once("../../../conexao.php");

// Variável para indicar a página atual, se necessário para o menu de navegação do painel
$pag = 'painel_alertas';

// Lógica para ocultar o painel de alertas se a variável $painel_alertas estiver definida como 'ocultar'
// (Esta lógica pode ser movida para o index.php se for para controlar a visibilidade do menu)
if(@$painel_alertas == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}

// Lógica para marcar alerta como resolvido (se houver requisição POST)
if (isset($_POST['resolver_alerta_id'])) {
    $alerta_id = $_POST['resolver_alerta_id'];
    $stmt_resolver = $pdo->prepare("UPDATE alertas_duplicidade SET resolvido = TRUE WHERE id = :id");
    $stmt_resolver->bindValue(":id", $alerta_id);
    $stmt_resolver->execute();
    // Redirecionar para a URL amigável para evitar reenvio do formulário
    header("Location: painel-alertas"); // Redireciona para a URL amigável configurada no .htaccess
    exit();
}

// Consulta para buscar alertas não resolvidos
$query_alertas = $pdo->query("SELECT a.*, c.nome as nome_cliente FROM alertas_duplicidade a JOIN clientes c ON a.id_cliente_cadastrado = c.id WHERE a.resolvido = FALSE ORDER BY a.data_alerta DESC");
$alertas = $query_alertas->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Conteúdo HTML que será inserido no layout do index.php -->
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Alertas de Duplicidade</h1>

    <?php if (empty($alertas)): ?>
        <p>Nenhum alerta de duplicidade pendente.</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-gray-700 rounded-lg shadow-md">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-600">ID Alerta</th>
                        <th class="py-2 px-4 border-b border-gray-600">Tipo</th>
                        <th class="py-2 px-4 border-b border-gray-600">Valor Duplicado</th>
                        <th class="py-2 px-4 border-b border-gray-600">Cliente Cadastrado</th>
                        <th class="py-2 px-4 border-b border-gray-600">Data</th>
                        <th class="py-2 px-4 border-b border-gray-600">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alertas as $alerta): ?>
                        <tr class="hover:bg-gray-600">
                            <td class="py-2 px-4 border-b border-gray-600"><?php echo htmlspecialchars($alerta['id']); ?></td>
                            <td class="py-2 px-4 border-b border-gray-600"><?php echo htmlspecialchars($alerta['tipo_alerta']); ?></td>
                            <td class="py-2 px-4 border-b border-gray-600"><?php echo htmlspecialchars($alerta['valor_duplicado']); ?></td>
                            <td class="py-2 px-4 border-b border-gray-600">
                                <a href="editar_cliente.php?id=<?php echo htmlspecialchars($alerta['id_cliente_cadastrado']); ?>" class="text-blue-400 hover:underline">
                                    <?php echo htmlspecialchars($alerta['nome_cliente']); ?> (ID: <?php echo htmlspecialchars($alerta['id_cliente_cadastrado']); ?>)
                                </a>
                            </td>
                            <td class="py-2 px-4 border-b border-gray-600"><?php echo date('d/m/Y H:i:s', strtotime($alerta['data_alerta'])); ?></td>
                            <td class="py-2 px-4 border-b border-gray-600">
                                <form method="POST">
                                    <input type="hidden" name="resolver_alerta_id" value="<?php echo htmlspecialchars($alerta['id']); ?>">
                                    <button type="submit" class="btn-resolver">Marcar como Resolvido</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Estilos e scripts devem ser carregados no index.php principal -->
<!-- Exemplo de como o CSS seria carregado no seu index.php -->
<!--
<style>
    body { font-family: 'Inter', sans-serif; background-color: #1a202c; color: #e2e8f0; padding: 20px; }
    .container { max-width: 1000px; margin: 0 auto; background-color: #2d3748; padding: 20px; border-radius: 8px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #4a5568; padding: 8px; text-align: left; }
    th { background-color: #4299e1; color: white; }
    .btn-resolver { background-color: #48bb78; color: white; padding: 5px 10px; border-radius: 5px; cursor: pointer; }
    .btn-resolver:hover { background-color: #38a169; }
</style>
-->
