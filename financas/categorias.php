<?php
// ============================================
// Arquivo: categorias.php
// Função: Cadastro e listagem de categorias financeiras
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
    $sql = "SELECT * FROM categorias WHERE id_categoria = $id AND usuario_id = $usuario_id";
    $res = mysqli_query($conexao, $sql);
    $editando = mysqli_fetch_assoc($res);
}

if (isset($_GET["excluir"])) {
    $id = intval($_GET["excluir"]);
    $sql = "DELETE FROM categorias WHERE id_categoria = $id AND usuario_id = $usuario_id";
    mysqli_query($conexao, $sql);
    header("Location: categorias.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST["id"] ?? 0);
    $descricao = mysqli_real_escape_string($conexao, trim($_POST["descricao"]));

    if (empty($descricao)) {
        $erro = "Preencha o nome da categoria.";
    } else {
        if ($id > 0) {
            $sql = "UPDATE categorias SET descricao = '$descricao' WHERE id_categoria = $id AND usuario_id = $usuario_id";
            $sucesso = "Categoria atualizada com sucesso!";
        } else {
            $sql = "INSERT INTO categorias (descricao, usuario_id) VALUES ('$descricao', $usuario_id)";
            $sucesso = "Categoria cadastrada com sucesso!";
        }

        if (!mysqli_query($conexao, $sql)) {
            $erro = "Erro ao salvar categoria.";
            $sucesso = "";
        } else {
            header("Location: categorias.php");
            exit;
        }
    }
}

$sql_categorias = "SELECT * FROM categorias WHERE usuario_id = $usuario_id ORDER BY descricao";
$res_categorias = mysqli_query($conexao, $sql_categorias);

require_once "includes/header.php";
?>
<title>Categorias — Controle Financeiro</title>
<body class="bg-gray-100 min-h-screen flex">

    <?php require_once "includes/menu_financeiro.php"; ?>

    <main class="flex-1 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Categorias</h1>
            <p class="text-gray-500 mt-2">Gerencie suas categorias de receitas e despesas.</p>
        </div>

        <?php if (!empty($sucesso)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6"><?php echo $sucesso; ?></div>
        <?php endif; ?>
        <?php if (!empty($erro)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6"><?php echo $erro; ?></div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8 max-w-3xl">
            <form method="POST" action="categorias.php" class="space-y-4">
                <input type="hidden" name="id" value="<?php echo $editando['id_categoria'] ?? ''; ?>">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Categoria</label>
                    <input type="text" name="descricao" value="<?php echo htmlspecialchars($editando['descricao'] ?? ''); ?>" required placeholder="Ex: Alimentação, Transporte" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-senai-blue">
                </div>
                <button type="submit" class="w-full bg-senai-blue text-white font-semibold rounded-xl py-3 hover:bg-senai-blue-dark transition"><?php echo $editando ? 'Atualizar categoria' : 'Adicionar categoria'; ?></button>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Categorias cadastradas</h2>
                <span class="text-sm text-gray-500"><?php echo mysqli_num_rows($res_categorias); ?> registros</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600">
                            <th class="px-4 py-3">Descrição</th>
                            <th class="px-4 py-3">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($cat = mysqli_fetch_assoc($res_categorias)): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-3"><?php echo htmlspecialchars($cat['descricao']); ?></td>
                                <td class="px-4 py-3 space-x-2">
                                    <a href="categorias.php?editar=<?php echo $cat['id_categoria']; ?>" class="inline-block rounded-lg bg-blue-600 px-3 py-2 text-white text-xs font-semibold hover:bg-blue-700">Editar</a>
                                    <a onclick="return confirm('Deseja excluir esta categoria?')" href="categorias.php?excluir=<?php echo $cat['id_categoria']; ?>" class="inline-block rounded-lg bg-red-600 px-3 py-2 text-white text-xs font-semibold hover:bg-red-700">Excluir</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

<?php require_once "includes/footer.php"; ?>
