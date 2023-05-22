<?php
session_start();

$user_id = $_SESSION['user_id'];
$completed = "yes";


require_once "conection.php";

$query =  "DELETE FROM todos WHERE user_id = ? AND completed = ? ";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $user_id, $completed);
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

header('Content-Type: application/json');
echo json_encode($response);
