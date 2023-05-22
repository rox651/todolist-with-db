<?php
if (isset($_GET["login"])) {
    require_once 'conection.php';

    $email = mysqli_real_escape_string($conn, trim($_GET['email']));
    $password = mysqli_real_escape_string($conn, trim($_GET['password']));


    if (empty($email) || empty($password)) {
        echo "<strong class='auth-form_alert'>Por favor, complete todos los campos obligatorios.</strong>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<strong class='auth-form_alert'>El correo electrónico no es válido.</strong>";
        exit;
    }

    $email_filtered = filter_var($email, FILTER_SANITIZE_EMAIL);

    $stmt = $conn->prepare("SELECT id, name, pass FROM users WHERE email = ?");
    $stmt->bind_param("s", $email_filtered);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $user_id = $row['id'];
        $user_name = $row["name"];
        $password_hash = $row['pass'];

        if (password_verify($password,  $password_hash)) {

            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $user_name;

            header('Location: index.php');
            exit;
        } else {
            echo "<strong class='auth-form_alert'>Contraseña incorrecta.</strong>";
        }
    } else {
        echo "<strong class='auth-form_alert'>Usuario no encontrado.</strong>";
    }
}
