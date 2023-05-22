<?php
if (isset($_POST["register"])) {
    require_once 'conection.php';

    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));
    $confirm_password = mysqli_real_escape_string($conn, trim($_POST['confirm-password']));

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "<strong class='auth-form_alert'>Por favor, complete todos los campos obligatorios.</strong>";
        exit;
    }


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<strong class='auth-form_alert'>El correo electrónico no es válido.</strong>";
        exit;
    }

    if ($password !== $confirm_password) {
        echo "<strong class='auth-form_alert'>Las contraseñas no coinciden.</strong>";
        exit;
    }


    $email_filtered = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password_hash = password_hash($password, PASSWORD_DEFAULT);



    $verify_user_exists_query = "SELECT id,name FROM users WHERE email = ?";
    $stmt = $conn->prepare($verify_user_exists_query);
    $stmt->bind_param("s",  $email_filtered);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<strong class='auth-form_alert'>El correo electrónico ya está registrado./strong>";
        exit;
    }


    $register_query = "INSERT INTO users (name, email, pass) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($register_query);
    $stmt->bind_param("sss", $name,  $email_filtered, $password_hash);
    $stmt->execute();

    $user_id = $stmt->insert_id;
    $user_name = $name;
    echo "user_id" . $user_id;

    session_start();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $user_name;

    header('Location: index.php');
    exit;
}
