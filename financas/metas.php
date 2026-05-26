<?php
// ============================================
// Arquivo: metas.php
// Função: Cadastro e listagem de metas financeiras
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
    $sql = "SELECT * FROM metas WHERE id_meta = $id AND usuario_id = $usuario_id";
    $res = mysqli_query($conexao, $sql);
    $editando = mysqli_fetch_assoc($res);
}

if (isset($_GET["excluir"])) {
    $id = intval($_GET["excluir"]);
    $sql = "DELETE FROM metas WHERE id_meta = $id AND usuario_id = $usuario_id";
    mysqli_query($conexao, $sql);
    header("Location: metas.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST["id"] ?? 0);
    $nome = mysqli_real_escape_string($conexao, trim($_POST["nome"]));
    $valor_meta = str_replace([','], ['.'], $_POST["valor_meta"]);
    $valor_atual = str_replace([','], ['.'], $_POST["valor_atual"] ?? '0');
    $prazo = $_POST["prazo"];

    if (empty($nome) || empty($valor_meta) || empty($prazo)) {
        $erro = "Preencha nome, valor meta e prazo.";
    } else {
        if ($valor_atual === '') {
            $valor_atual = '0';
        }

        if ($id > 0) {
            $sql = "UPDATE metas SET nome = '$nome', valor_meta = '$valor_meta', valor_atual = '$valor_atual', prazo = '$prazo' WHERE id_meta = $id AND usuario_id = $usuario_id";
            $sucesso = "Meta atualizada com sucesso!";
        } else {
            $sql = "INSERT INTO metas (nome, valor_meta, valor_atual, prazo, usuario_id) VALUES ('$nome', '$valor_meta', '$valor_atual', '$prazo', $usuario_id)";
            $sucesso = "Meta cadastrada com sucesso!";
        }

        if (!mysqli_query($conexao, $sql)) {
            $erro = "Erro ao salvar meta.";
            $sucesso = "";
        } else {
            header("Location: metas.php");
            exit;
        }
    }
}

$sql_metas = "SELECT * FROM metas WHERE usuario_id = $usuario_id ORDER BY prazo ASC";
$res_metas = mysqli_query($conexao, $sql_metas);

require_once "includes/header.php";
?>
<title>Metas — Controle Financeiro</title>
<body class="bg-gray-100 min-h-screen flex">

    <?php require_once "includes/menu_financeiro.php"; ?>

    <main class="flex-1 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Metas</h1>
            <p class="text-gray-500 mt-2">Cadastre e acompanhe suas metas financeiras.</p>
        </div>

        <?php if (!empty($sucesso)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6"><?php echo $sucesso; ?></div>
        <?php endif; ?>
        <?php if (!empty($erro)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6"><?php echo $erro; ?></div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8 max-w-3xl">
            <form method="POST" action="metas.php" class="space-y-4">
                <input type="hidden" name="id" value="<?php echo $editando['id_meta'] ?? ''; ?>">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Meta</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($editando['nome'] ?? ''); ?>" required placeholder="Ex: Poupança para viagem" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-senai-blue">
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Valor Meta</label>
                        <input type="text" name="valor_meta" value="<?php echo htmlspecialchars($editando['valor_meta'] ?? ''); ?>" required placeholder="0,00" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-senai-blue">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Valor Atual</label>
                        <input type="text" name="valor_atual" value="<?php echo htmlspecialchars($editando['valor_atual'] ?? '0'); ?>" placeholder="0,00" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-senai-blue">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Prazo</label>
                    <input type="date" name="prazo" value="<?php echo htmlspecialchars($editando['prazo'] ?? ''); ?>" required class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-senai-blue">
                </div>

                <button type="submit" class="w-full bg-senai-blue text-white font-semibold rounded-xl py-3 hover:bg-senai-blue-dark transition"><?php echo $editando ? 'Atualizar meta' : 'Adicionar meta'; ?></button>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Metas cadastradas</h2>
                <span class="text-sm text-gray-500"><?php echo mysqli_num_rows($res_metas); ?> registros</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600">
                            <th class="px-4 py-3">Meta</th>
                            <th class="px-4 py-3">Valor atual</th>
                            <th class="px-4 py-3">Valor desejado</th>
                            <th class="px-4 py-3">Prazo</th>
                            <th class="px-4 py-3">Progresso</th>
                            <th class="px-4 py-3">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($meta = mysqli_fetch_assoc($res_metas)): ?>
                            <?php
                                $valorAtual = floatval($meta['valor_atual']);
                                $valorMeta = floatval($meta['valor_meta']);
                                $progresso = $valorMeta > 0 ? round(($valorAtual / $valorMeta) * 100) : 0;
                                if ($progresso > 100) $progresso = 100;
                            ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-3"><?php echo htmlspecialchars($meta['nome']); ?></td>
                                <td class="px-4 py-3">R$ <?php echo number_format($valorAtual, 2, ',', '.'); ?></td>
                                <td class="px-4 py-3">R$ <?php echo number_format($valorMeta, 2, ',', '.'); ?></td>
                                <td class="px-4 py-3"><?php echo date('d/m/Y', strtotime($meta['prazo'])); ?></td>
                                <td class="px-4 py-3">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                        <div class="bg-senai-blue h-2.5 rounded-full" style="width: <?php echo $progresso; ?>%;"></div>
                                    </div>
                                    <span class="text-xs text-gray-500 mt-1 block"><?php echo $progresso; ?>%</span>
                                </td>
                                <td class="px-4 py-3 space-x-2">
                                    <a href="metas.php?editar=<?php echo $meta['id_meta']; ?>" class="inline-block rounded-lg bg-blue-600 px-3 py-2 text-white text-xs font-semibold hover:bg-blue-700">Editar</a>
                                    <a onclick="return confirm('Deseja excluir esta meta?')" href="metas.php?excluir=<?php echo $meta['id_meta']; ?>" class="inline-block rounded-lg bg-red-600 px-3 py-2 text-white text-xs font-semibold hover:bg-red-700">Excluir</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

<?php require_once "includes/footer.php"; ?>
