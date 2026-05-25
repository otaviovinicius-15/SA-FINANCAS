<?php
// ============================================
// Arquivo: dashboard.php
// Função: Página inicial do usuário no sistema financeiro
// ============================================

session_start();
require_once "logado.php";
require_once "conexao_financeiro.php";

$usuario_id = $_SESSION["usuario_id"];
$usuario_nome = $_SESSION["usuario_nome"] ?? "Usuário";
$nomeSimples = explode(" ", trim($usuario_nome))[0];

$sql_receitas = "SELECT COALESCE(SUM(valor), 0) AS total FROM movimentacoes WHERE usuario_id = $usuario_id AND tipo = 'receita'";
$sql_despesas = "SELECT COALESCE(SUM(valor), 0) AS total FROM movimentacoes WHERE usuario_id = $usuario_id AND tipo = 'despesa'";
$sql_categorias = "SELECT COUNT(*) AS total FROM categorias WHERE usuario_id = $usuario_id";
$sql_metas = "SELECT COUNT(*) AS total, COALESCE(SUM(valor_atual), 0) AS atual, COALESCE(SUM(valor_meta), 0) AS meta FROM metas WHERE usuario_id = $usuario_id";

$res_receitas = mysqli_query($conexao, $sql_receitas);
$res_despesas = mysqli_query($conexao, $sql_despesas);
$res_categorias = mysqli_query($conexao, $sql_categorias);
$res_metas = mysqli_query($conexao, $sql_metas);

$total_receitas = mysqli_fetch_assoc($res_receitas)["total"];
$total_despesas = mysqli_fetch_assoc($res_despesas)["total"];
$total_categorias = mysqli_fetch_assoc($res_categorias)["total"];
$metas_data = mysqli_fetch_assoc($res_metas);
$total_metas = $metas_data["total"];
$total_meta_atual = $metas_data["atual"];
$total_meta_valor = $metas_data["meta"];

$saldo = $total_receitas - $total_despesas;
$progresso_metas = $total_meta_valor > 0 ? round(($total_meta_atual / $total_meta_valor) * 100) : 0;

$sql_ultimas = "SELECT m.*, c.descricao AS categoria FROM movimentacoes m LEFT JOIN categorias c ON m.categoria_id = c.id_categoria WHERE m.usuario_id = $usuario_id ORDER BY m.data_movimento DESC LIMIT 6";
$res_ultimas = mysqli_query($conexao, $sql_ultimas);

require_once "includes/header.php";
?>
<title>Dashboard Financeiro</title>
<body class="bg-gray-100 min-h-screen flex">

    <?php require_once "includes/menu_financeiro.php"; ?>

    <main class="flex-1 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Olá, <?php echo htmlspecialchars($nomeSimples); ?>!</h1>
            <p class="text-gray-500 mt-2">Resumo rápido das suas finanças.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <p class="text-sm text-gray-500">Receitas</p>
                <p class="text-3xl font-bold text-green-600">R$ <?php echo number_format($total_receitas, 2, ',', '.'); ?></p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <p class="text-sm text-gray-500">Despesas</p>
                <p class="text-3xl font-bold text-red-600">R$ <?php echo number_format($total_despesas, 2, ',', '.'); ?></p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <p class="text-sm text-gray-500">Saldo Atual</p>
                <p class="text-3xl font-bold <?php echo $saldo >= 0 ? 'text-sky-600' : 'text-red-600'; ?>">R$ <?php echo number_format($saldo, 2, ',', '.'); ?></p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <p class="text-sm text-gray-500">Metas</p>
                <p class="text-3xl font-bold text-indigo-600"><?php echo $total_metas; ?></p>
                <p class="text-xs text-gray-400 mt-2">Progresso médio <?php echo $progresso_metas; ?>%</p>
            </div>
        </div>

        <div class="grid gap-4 xl:grid-cols-3 mb-8">
            <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Últimas movimentações</h2>
                        <p class="text-sm text-gray-500">Visualize as entradas e saídas recentes.</p>
                    </div>
                    <a href="movimentacoes.php" class="text-senai-blue font-semibold hover:underline">Ver todas</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600">
                                <th class="px-4 py-3">Data</th>
                                <th class="px-4 py-3">Descrição</th>
                                <th class="px-4 py-3">Categoria</th>
                                <th class="px-4 py-3">Tipo</th>
                                <th class="px-4 py-3">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($mov = mysqli_fetch_assoc($res_ultimas)): ?>
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="px-4 py-3"><?php echo date('d/m/Y', strtotime($mov['data_movimento'])); ?></td>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($mov['descricao']); ?></td>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($mov['categoria'] ?? 'Sem categoria'); ?></td>
                                    <td class="px-4 py-3 text-sm font-semibold <?php echo $mov['tipo'] === 'receita' ? 'text-green-600' : 'text-red-600'; ?>"><?php echo ucfirst($mov['tipo']); ?></td>
                                    <td class="px-4 py-3 font-semibold">R$ <?php echo number_format($mov['valor'], 2, ',', '.'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Resumo de categorias</h2>
                <p class="text-sm text-gray-500 mb-4">Você tem <?php echo $total_categorias; ?> categorias cadastradas.</p>
                <div class="space-y-3">
                    <div class="rounded-2xl bg-blue-50 p-4">
                        <p class="text-sm text-gray-600">Total em metas</p>
                        <p class="text-xl font-bold text-indigo-700">R$ <?php echo number_format($total_meta_atual, 2, ',', '.'); ?> / R$ <?php echo number_format($total_meta_valor, 2, ',', '.'); ?></p>
                    </div>
                    <div class="rounded-2xl bg-green-50 p-4">
                        <p class="text-sm text-gray-600">Receitas previstas</p>
                        <p class="text-xl font-bold text-green-700">R$ <?php echo number_format($total_receitas, 2, ',', '.'); ?></p>
                    </div>
                    <div class="rounded-2xl bg-red-50 p-4">
                        <p class="text-sm text-gray-600">Despesas registradas</p>
                        <p class="text-xl font-bold text-red-700">R$ <?php echo number_format($total_despesas, 2, ',', '.'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php require_once "includes/footer.php"; ?>
