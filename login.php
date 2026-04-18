<?php
session_start();

require_once "DataBase/conexao.php";
require "Functions.php";
    ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--| Bootstrap |-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!--| Google Fonts |-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!--| CSS |-->
    <link rel="stylesheet" href="CSS/login.css">

    <title>Sistema de Comanda</title>
</head>

<body>

    <div class="login-container">
        <div class="login-box">
            <h2>Acesso ao P.D.V Churrascaria <span>W</span>
                <span2>3</span2>
            </h2>
            <p>Insira as suas credenciais para iniciar</p>

            <form class="form-login" method="POST">
                <label for="username">Nome:</label>
                <input type="text" id="username" name="username" placeholder="Nome de Usuário" required>

                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" placeholder="•••••••••••••" required>

                <button type="submit" value="login" name="login" class="btn-login">Entrar no Sistema</button>
            </form>
        </div>
    </div>
</body>

</html>

<?php
try {
    if (isset($_REQUEST['login'])) {
        $usuario = $_REQUEST['username'];
        $senha = $_REQUEST['password'];

        $id = LoginVerify(name: $usuario, password: $senha, conn: $conn);

         if ($id !== null) {
            $_SESSION['UserId'] = $id;
            header("Location: index.php");
            exit();
        } else {
            $erro = "Usuário ou senha inválidos.";
        }
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    $erro = "Erro interno. Tente novamente.";
}
?>
