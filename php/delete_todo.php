<?php
session_start();

$payload = file_get_contents('php://input');
$data = json_decode($payload, false);
$user_id = $_SESSION['user_id'];

if ($data) {

    require_once "conection.php";
    $todo_id = $data->todo_id;


    $query =  "DELETE FROM todos WHERE user_id = ? AND id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $todo_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = [
            "ok" => true,
        ];
    } else {
        $response = [
            "ok" => false,
        ];
    }

    $stmt->close();
}



header('Content-Type: application/json');
echo json_encode($response);
