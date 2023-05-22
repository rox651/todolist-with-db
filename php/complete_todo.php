<?php
session_start();

$payload = file_get_contents('php://input');
$data = json_decode($payload, false);
$user_id = $_SESSION['user_id'];

if ($data) {

    require_once "conection.php";
    $completed = $data->completed;
    $todo_id = $data->todo_id;

    $query = "UPDATE todos SET completed = ? WHERE user_id = ? AND id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $completed, $user_id, $todo_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = [
            "ok" => true,
            'data' => $data,
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
