<?php
session_start();

//conexão com o banco de dados
require_once('conexao.php');

//alerta
$mensagem = "";

//verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $senha = $_POST['password'];

    $stmt = $conexao->prepare("SELECT id_usuario, nome, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    //verifica se o email existe
    if ($resultado->num_rows > 0) {

        $usuario = $resultado->fetch_assoc();

        //verifica a senha
        if (password_verify($senha, $usuario['senha'])) {

            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nome'] = $usuario['nome'];

            header("Location: financas/dashboard.php");
            exit();

        } else {
            $mensagem = "Senha incorreta !";
        }

    } else {
        $mensagem = "E-mail não cadastrado !";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Gestão 360</title>
</head>
<body>

    <!--Título do form-->
    <div class="auth-wrapper">
        <aside class="auth-sidebar">
            <div class="login-title">
                <h2>Gestão 360</h2>
                <p>Sua plataforma de gestão financeira pessoal</p>
            </div>
        </aside>

        <!--Formulário de login-->
        <main class="login-card">
            <div class="login-auth">
                <form id="login-form" method="POST" action="">

                    <div class="login-group">
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

                    <div class="login-group">
                        <label for="password">Senha</label><br>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            placeholder="************" 
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <!--Botão entrar-->
                    <button type="submit">Entrar</button>
                </form>
            </div>
        </main>

        <!--Rodapé-->
        <p>Não tem uma conta ? <a href="cadastro.php">Registre-se</a></p>
    </div>
</body>

<!--mensagem de erro-->
<?php if (!empty($mensagem)) : ?>

<script>
    alert("<?php echo $mensagem; ?>");
</script>

<?php endif; ?>
</html>