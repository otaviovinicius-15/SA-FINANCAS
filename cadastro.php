<?php

require_once "conexao.php";

$mensagem = '';

// verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome   = $_POST["name"];
    $email  = $_POST["email"];
    $tel    = $_POST["telefone"];
    $senha  = $_POST["password"];

    // valida email
    $stmt = mysqli_prepare($conexao, "SELECT id_usuario FROM USUARIOS WHERE EMAIL = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    // verifica se email está cadastrado
    if (mysqli_stmt_num_rows($stmt) > 0) {

        $mensagem = "E-mail já está cadastrado!";

    } else {

        // criptografa senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // insere usuário no banco
        $stmt = mysqli_prepare($conexao, "INSERT INTO USUARIOS (NOME, EMAIL, TELEFONE, SENHA) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $nome, $email, $tel, $senhaHash);

        // verifica cadastro
        if (mysqli_stmt_execute($stmt)) {

            header("Location: login.php");
            exit();

        } else {

            $mensagem = "Erro ao cadastrar!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cadastro.css">
    <title>Gestão 360</title>
</head>
<body>

    <!--Título do form-->
    <div class="auth-wrapper">
        <aside class="auth-sidebar">
            <div class="register-title">
                <h1>Crie sua conta</h1>
                <p>Junte-se a plataforma e controle seus gastos</p>
            </div>
        </aside>

        <!--Formulário de cadastro-->
        <main class="register-card">
            <div class="register-auth">
                <form id="register-form" method="POST" action="">

                    <div class="register-group">
                        <label for="name">Nome</label><br>
                        <input 
                            type="text" 
                            id="name" 
                            name="name"
                            placeholder="Nome Completo" 
                            required
                        >
                    </div>

                    <div class="register-group">
                        <label for="email">E-mail</label><br>
                        <input 
                            type="email" 
                            id="email" 
                            name="email"
                            placeholder="E-mail" 
                            autocomplete="email"
                            required
                        >
                    </div>

                    <div class="register-group">
                        <label for="telefone">Telefone</label><br>
                        <input 
                            type="tel" 
                            id="telefone" 
                            name="telefone"
                            placeholder="(51) 99999-9999" 
                            autocomplete="tel"
                            required
                        >
                    </div>

                    <div class="register-group">
                        <label for="password">Senha</label><br>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            placeholder="************" 
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <!--Botão entrar-->
                    <button type="submit">Entrar</button>
                </form>
            </div>
        </main>

        <!--Rodapé-->
        <p>Já possui uma conta ? <a href="login.php">Fazer Login</a></p>
    </div>
</body>

<!--mensagem de erro-->
<?php if (!empty($mensagem)) : ?>

<script>
    alert("<?php echo $mensagem; ?>");
</script>

<?php endif; ?>
</html>