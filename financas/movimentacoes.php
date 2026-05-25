<?php
// ============================================
// Arquivo: movimentacoes.php
// Função: Cadastro e listagem de movimentações financeiras
// ============================================

session_start();
require_once "logado.php";
require_once "conexao_financeiro.php";

$usuario_id = $_SESSION["usuario_id"];
$sucesso = "";
$erro = "";
$editando = NULL;

if (isset($_GET["editar"])) {
    $id = intval($_GET["editar"]);
    $sql = "SELECT * FROM movimentacoes WHERE id_movimento = $id AND usuario_id = $usuario_id";
    $res = mysqli_query($conexao, $sql);
    $editando = mysqli_fetch_assoc($res);
}

if (isset($_GET["excluir"])) {
    $id = intval($_GET["excluir"]);
    $sql = "DELETE FROM movimentacoes WHERE id_movimento = $id AND usuario_id = $usuario_id";
    mysqli_query($conexao, $sql);
    header("Location: movimentacoes.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST["id"] ?? 0);
    $descricao = mysqli_real_escape_string($conexao, $_POST["descricao"]);
    $valor = str_replace([','], ['.'], $_POST["valor"]);
    $data_movimento = $_POST["data_movimento"];
    $tipo = $_POST["tipo"];
    $categoria_id = intval($_POST["categoria_id"]);

    if (empty($descricao) || empty($valor) || empty($data_movimento) || empty($tipo)) {
        $erro = "Preencha todos os campos obrigatórios.";
    } else {
        if ($id > 0) {
            $sql = "UPDATE movimentacoes SET descricao = '$descricao', valor = '$valor', data_movimento = '$data_movimento', tipo = '$tipo', categoria_id = $categoria_id WHERE id_movimento = $id AND usuario_id = $usuario_id";
            $sucesso = "Movimentação atualizada com sucesso!";
        } else {
            $sql = "INSERT INTO movimentacoes (descricao, valor, data_movimento, tipo, categoria_id, usuario_id) VALUES ('$descricao', '$valor', '$data_movimento', '$tipo', $categoria_id, $usuario_id)";
            $sucesso = "Movimentação cadastrada com sucesso!";
        }

        if (!mysqli_query($conexao, $sql)) {
            $erro = "Erro ao salvar movimentação.";
            $sucesso = "";
        } else {
            header("Location: movimentacoes.php");
            exit;
        }
    }
}

$sql_categorias = "SELECT * FROM categorias WHERE usuario_id = $usuario_id ORDER BY descricao";
$res_categorias = mysqli_query($conexao, $sql_categorias);

$sql_mov = "SELECT m.*, c.descricao AS categoria FROM movimentacoes m LEFT JOIN categorias c ON m.categoria_id = c.id_categoria WHERE m.usuario_id = $usuario_id ORDER BY m.data_movimento DESC";
$res_mov = mysqli_query($conexao, $sql_mov);

require_once "includes/header.php";
?>
<title>Movimentações — Controle Financeiro</title>
<body class="bg-gray-100 min-h-screen flex">

    <?php require_once "includes/menu_financeiro.php"; ?>

    <main class="flex-1 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Movimentações</h1>
            <p class="text-gray-500 mt-2">Cadastre receitas e despesas ou atualize lançamentos existentes.</p>
        </div>

        <?php if (!empty($sucesso)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6"><?php echo $sucesso; ?></div>
        <?php endif; ?>
        <?php if (!empty($erro)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6"><?php echo $erro; ?></div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8 max-w-3xl">
            <form method="POST" action="movimentacoes.php" class="space-y-4">
                <input type="hidden" name="id" value="<?php echo $editando['id_movimento'] ?? ''; ?>">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Descrição</label>
                    <input type="text" name="descricao" value="<?php echo htmlspecialchars($editando['descricao'] ?? ''); ?>" required placeholder="Ex: Salário, Supermercado" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-senai-blue">
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Valor</label>
                        <input type="text" name="valor" value="<?php echo htmlspecialchars($editando['valor'] ?? ''); ?>" required placeholder="0,00" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-senai-blue">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Data</label>
                        <input type="date" name="data_movimento" value="<?php echo htmlspecialchars($editando['data_movimento'] ?? date('Y-m-d')); ?>" required class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-senai-blue">
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo</label>
                        <select name="tipo" required class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-senai-blue">
                            <option value="receita" <?php echo (isset($editando['tipo']) && $editando['tipo'] === 'receita') ? 'selected' : ''; ?>>Receita</option>
                            <option value="despesa" <?php echo (isset($editando['tipo']) && $editando['tipo'] === 'despesa') ? 'selected' : ''; ?>>Despesa</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Categoria</label>
                        <select name="categoria_id" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-senai-blue">
                            <option value="0">Sem categoria</option>
                            <?php while ($cat = mysqli_fetch_assoc($res_categorias)): ?>
                                <option value="<?php echo $cat['id_categoria']; ?>" <?php echo (isset($editando['categoria_id']) && $editando['categoria_id'] == $cat['id_categoria']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['descricao']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full bg-senai-blue text-white font-semibold rounded-xl py-3 hover:bg-senai-blue-dark transition"><?php echo $editando ? 'Atualizar movimentação' : 'Adicionar movimentação'; ?></button>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Movimentações cadastradas</h2>
                <span class="text-sm text-gray-500"><?php echo mysqli_num_rows($res_mov); ?> registros</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600">
                            <th class="px-4 py-3">Data</th>
                            <th class="px-4 py-3">Descrição</th>
                            <th class="px-4 py-3">Tipo</th>
                            <th class="px-4 py-3">Categoria</th>
                            <th class="px-4 py-3">Valor</th>
                            <th class="px-4 py-3">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($mov = mysqli_fetch_assoc($res_mov)): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-3"><?php echo date('d/m/Y', strtotime($mov['data_movimento'])); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($mov['descricao']); ?></td>
                                <td class="px-4 py-3 text-sm font-semibold <?php echo $mov['tipo'] === 'receita' ? 'text-green-600' : 'text-red-600'; ?>"><?php echo ucfirst($mov['tipo']); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($mov['categoria'] ?? 'Sem categoria'); ?></td>
                                <td class="px-4 py-3 font-semibold">R$ <?php echo number_format($mov['valor'], 2, ',', '.'); ?></td>
                                <td class="px-4 py-3 space-x-2">
                                    <a href="movimentacoes.php?editar=<?php echo $mov['id_movimento']; ?>" class="inline-block rounded-lg bg-blue-600 px-3 py-2 text-white text-xs font-semibold hover:bg-blue-700">Editar</a>
                                    <a onclick="return confirm('Confirma exclusão?')" href="movimentacoes.php?excluir=<?php echo $mov['id_movimento']; ?>" class="inline-block rounded-lg bg-red-600 px-3 py-2 text-white text-xs font-semibold hover:bg-red-700">Excluir</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

<?php require_once "includes/footer.php"; ?>
