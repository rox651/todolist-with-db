<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicia sesion</title>
    <link rel="stylesheet" href="./css/style.css" />

</head>

<body class="auth">
    <form method="get" class="auth-form">
        <h1 class="auth-form_title">Inicia sesión</h1>

        <fieldset class="auth-form_field">
            <label class="auth-form_field--label" for="">Email</label>
            <input class="auth-form_field--input" autocomplete name="email" type="email">
        </fieldset>
        <fieldset class="auth-form_field">
            <label class="auth-form_field--label" for="">Contraseña</label>
            <input class="auth-form_field--input" autocomplete name="password" type="password">
        </fieldset>

        <input class="auth-form_submit" name="login" type="submit" value="Iniciar sesión">

        <aside>
            No tienes una cuenta? <a class="auth-form_link" href="register.php">Registrate aquí</a>
        </aside>
        <?php include("php/login.php") ?>

    </form>

</body>

</html>