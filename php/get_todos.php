<?php
require_once 'conection.php';

$query = "SELECT * FROM todos WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    foreach ($result as $row) {
        $todo_id = $row['id'];
        $name = $row['name'];
        $completed = $row['completed'];
        $is_completed = $completed == "yes";
        $classPressed = $is_completed ?  'pressed' : ''
?>
        <article data-id="<?= $todo_id ?>" data-completed="<?= $completed ?>" class="todo <?= $classPressed ?>">
            <span role="button" class="spanCreated ">
            </span>
            <p><?= $name ?></p>
            <svg class="xmark" role="button" xmlns="http://www.w3.org/2000/svg" width="18" height="18">
                <path fill="#494C6B" fill-rule="evenodd" d="M16.97 0l.708.707L9.546 8.84l8.132 8.132-.707.707-8.132-8.132-8.132 8.132L0 16.97l8.132-8.132L0 .707.707 0 8.84 8.132 16.971 0z" />
            </svg>
        </article>
<?php
    }
}

$stmt->close();
