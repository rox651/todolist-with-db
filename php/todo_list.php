<?php
if ($_POST) {
    session_start();

    $todo_name = trim($_POST["formTodo__input"]);
    $completed = "no";
    $user_id = $_SESSION['user_id'];


    if (empty($todo_name)) {
        $response = [
            "ok" => false,
            'message' => "<strong class='error'>Escribe algun texto</strong>",

        ];
    } else {
        require_once 'conection.php';

        $query = "INSERT INTO todos (user_id,name,completed) VALUES (?,?,?);";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $user_id, $todo_name, $completed);
        $stmt->execute();

        $response = [
            'ok' => true,
            "todo_id" => $stmt->insert_id,
            "name" => $todo_name,
            "completed" => $completed
        ];
    }


    header('Content-Type: application/json');
    echo json_encode($response);
}
