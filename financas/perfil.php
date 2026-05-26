<?php
// ============================================
// Arquivo: perfil.php
// Função: Exibição e atualização do perfil do usuário
// ============================================

session_start();
require_once "logado.php";
require_once "conexao_financeiro.php";

$usuario_id = $_SESSION["usuario_id"];
$sucesso = "";
$erro = "";

$sql_usuario = "SELECT * FROM usuarios WHERE id_usuario = $usuario_id";
$res_usuario = mysqli_query($conexao, $sql_usuario);
$usuario = mysqli_fetch_assoc($res_usuario);

if (!$usuario) {
    header("Location: logout.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = mysqli_real_escape_string($conexao, trim($_POST["nome"]));
    $email = mysqli_real_escape_string($conexao, trim($_POST["email"]));
    $telefone = mysqli_real_escape_string($conexao, trim($_POST["telefone"]));
    $senha = $_POST["senha"];

    if (empty($nome) || empty($email)) {
        $erro = "Nome e e-mail são obrigatórios.";
    } else {
        $sql = "UPDATE usuarios SET nome = '$nome', email = '$email', telefone = '$telefone'";

        if (!empty($senha)) {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql .= ", senha = '$senha_hash'";
        }

        $sql .= " WHERE id_usuario = $usuario_id";

        if (mysqli_query($conexao, $sql)) {
            $sucesso = "Perfil atualizado com sucesso!";
            $usuario["nome"] = $nome;
            $usuario["email"] = $email;
            $usuario["telefone"] = $telefone;
            if (!empty($senha)) {
                $usuario["senha"] = $senha_hash;
            }
        } else {
            $erro = "Erro ao atualizar perfil.";
        }
    }
}

require_once "includes/header.php";
?>
<title>Perfil — Controle Financeiro</title>
<body class="bg-gray-100 min-h-screen flex">

    <?php require_once "includes/menu_financeiro.php"; ?>

    <main class="flex-1 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Meu Perfil</h1>
            <p class="text-gray-500 mt-2">Atualize suas informações pessoais e acesso.</p>
        </div>

        <?php if (!empty($sucesso)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6"><?php echo $sucesso; ?></div>
        <?php endif; ?>
        <?php if (!empty($erro)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6"><?php echo $erro; ?></div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-3xl">
            <form method="POST" action="perfil.php" class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nome</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-senai-blue">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">E-mail</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-senai-blue">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Telefone</label>
                    <input type="text" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone']); ?>" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-senai-blue">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nova senha</label>
                    <input type="password" name="senha" placeholder="Deixe em branco para manter a senha atual" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-senai-blue">
                </div>

                <button type="submit" class="w-full bg-senai-blue text-white font-semibold rounded-xl py-3 hover:bg-senai-blue-dark transition">Salvar alterações</button>
            </form>
        </div>
    </main>

<?php require_once "includes/footer.php"; ?>
