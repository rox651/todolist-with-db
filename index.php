<?php

session_start();
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
   $user_name = $_SESSION['user_name'];
} else {
   header('Location: login.php');
   exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <link rel="icon" type="image/png" sizes="32x32" href="./images/favicon-32x32.png" />
   <title>Todo app</title>
   <link rel="stylesheet" href="./css/style.css" />
</head>

<body>
   <div class="formTodo">
      <header class="header">
         <h1 class="header__title" title="TODO">TODO</h1>
         <a class="header__logout" href="./php/logout.php">Cerrar sesion</a>

         <h2 class="header__user">Bienvenido <?= $user_name ?></h2>
      </header>

      <form action="php/todo_list.php" enctype="multipart/form-data" method="post" class="formTodo__ctn">
         <input class="formTodo__input" name="formTodo__input" placeholder="Crea una nueva tarea..." type="text" />
      </form>
      <p class="todos_none hidden">No hay ninguna tarea por ahora</p>
      <main class="todoBody">
         <div class="todos">
            <?php
            include("./php/get_todos.php")
            ?>
         </div>
         <aside class="viewTodos">
            <p class="viewTodos__items--left">0 tarea(s) sin finalizar</p>
            <div class="options">
               <h3 class="option active-option" data-completed="all">Todos</h3>
               <h3 class="option" data-completed="no">Activos</h3>
               <h3 class="option" data-completed="yes">Completados</h3>
            </div>
            <p class="viewTodos__items--clear">Borrar completados</p>
         </aside>
      </main>
   </div>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script src="./js/todos.js"></script>
</body>

</html>