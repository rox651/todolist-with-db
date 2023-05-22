const forms = document.querySelectorAll("form");
const todosContainer = document.querySelector(".todos");
const todos = document.querySelectorAll(".todo");
const noTodosMessage = document.querySelector(".todos_none");
const todosCompletedMessage = document.querySelector(".viewTodos__items--left");
const clearCompletedTodosButton = document.querySelector(".viewTodos__items--clear");
const filterOptions = document.querySelectorAll(".option");

isThereTodos();
isThereTodosPending();

forms.forEach(form => {
   form.addEventListener("submit", sendFormData);
});

todos.forEach(todo => {
   const xmark = todo.querySelector(".xmark");

   todo.addEventListener("click", toggleCompleteTodo);
   xmark.addEventListener("click", deleteTodo);
});

filterOptions.forEach(option => {
   option.addEventListener("click", hideAndShowTodos);
});

clearCompletedTodosButton.addEventListener("click", deleteAllCompletedTodos);

async function sendFormData(event) {
   event.preventDefault();

   const currentForm = event.currentTarget;
   const phpFile = currentForm.getAttribute("action");

   const formData = new FormData(currentForm);

   try {
      const response = await fetch(phpFile, {
         method: "POST",
         body: formData,
      });

      const data = await response.json();

      if (data.ok) {
         const newTodo = createTodo(data);
         todosContainer.appendChild(newTodo);
         isThereTodos();
         isThereTodosPending();
         currentForm.reset();
      }
   } catch (error) {
      console.error(error);
   }
}

function isThereTodos() {
   const todosLength = todosContainer.children.length;
   if (todosLength === 0) {
      noTodosMessage.classList.remove("hidden");
      return;
   }

   noTodosMessage.classList.add("hidden");
}

function isThereTodosPending() {
   const currentTodos = document.querySelectorAll(".todo");
   const todosNotCompleted = [];
   currentTodos.forEach(todo => {
      if (!todo.classList.contains("pressed")) {
         todosNotCompleted.push(todo);
      }
   });

   todosCompletedMessage.textContent = `${todosNotCompleted.length} tarea(s) sin finalizar`;
}

function createTodo({ todo_id, name, completed }) {
   const isCompleted = completed === "yes";
   const todo = document.createElement("article");
   todo.setAttribute("data-id", todo_id);
   todo.setAttribute("data-completed", completed);
   todo.classList.add("todo");
   todo.classList.toggle("pressed", isCompleted);
   todo.innerHTML = `
         <span  role="button" class="spanCreated">
        
         </span>
         <p>${name}</p>
         <svg class="xmark" role="button" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="#494C6B" fill-rule="evenodd" d="M16.97 0l.708.707L9.546 8.84l8.132 8.132-.707.707-8.132-8.132-8.132 8.132L0 16.97l8.132-8.132L0 .707.707 0 8.84 8.132 16.971 0z"/></svg>       
   `;

   const xmark = todo.querySelector(".xmark");

   xmark.addEventListener("click", deleteTodo);
   todo.addEventListener("click", toggleCompleteTodo);

   return todo;
}

function deleteTodo(event) {
   event.stopPropagation();
   const todo = event.currentTarget.parentNode;
   const todo_id = todo.getAttribute("data-id");

   const data = {
      todo_id,
   };
   Swal.fire({
      title: "Quieres eliminar tu tarea?",
      text: "Esta accion es irremovible",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Eliminar",
      cancelButtonText: "Cancelar",
   }).then(async result => {
      if (result.isConfirmed) {
         try {
            const response = await fetch("php/delete_todo.php", {
               method: "POST",
               body: JSON.stringify(data),
               headers: {
                  "Content-Type": "application/json",
               },
            });

            const responseData = await response.json();

            if (responseData.ok) {
               Swal.fire({
                  title: "Tarea eliminada",
                  text: "Tu tarea ha sido eliminada",
                  icon: "success",
               });

               todo.remove();
               isThereTodos();
               isThereTodosPending();
            } else {
               Swal.fire({
                  title: "Whoops!",
                  text: "Ha ocurrido un error",
                  icon: "error",
               });
            }
         } catch (error) {
            console.error(error);
         }
      }
   });
}

async function toggleCompleteTodo(event) {
   const todo = event.currentTarget;
   const filterOption = document.querySelector(".option.active-option");
   const dataOption = filterOption.getAttribute("data-completed");
   const todo_id = todo.getAttribute("data-id");
   const completed = todo.getAttribute("data-completed");
   const isCompleted = completed === "yes" ? "no" : "yes";
   todo.setAttribute("data-completed", isCompleted);

   todo.classList.toggle("pressed");

   const data = {
      todo_id,
      completed: isCompleted,
   };

   verifyTodoCategory(todo, dataOption);

   try {
      const response = await fetch("php/complete_todo.php", {
         method: "POST",
         body: JSON.stringify(data),
         headers: {
            "Content-Type": "application/json",
         },
      });

      const responseData = await response.json();

      if (responseData.ok) {
         isThereTodosPending();
      }
   } catch (error) {
      console.error(error);
   }
}

function deleteAllCompletedTodos() {
   const currentTodos = document.querySelectorAll(".todo");
   const todosCompleted = [];

   currentTodos.forEach(todo => {
      if (todo.classList.contains("pressed")) {
         todosCompleted.push(todo);
      }
   });

   if (todosCompleted.length === 0) {
      Swal.fire({
         title: "No hay tareas completadas que borrar",
      });
      return;
   }

   Swal.fire({
      title: "Quieres eliminar todas las tareas completadas?",
      text: "Esta accion es irremovible",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Eliminar",
      cancelButtonText: "Cancelar",
   }).then(async result => {
      if (result.isConfirmed) {
         try {
            const response = await fetch("php/delete_all_todos.php");
            const responseData = await response.json();

            if (responseData.ok) {
               Swal.fire({
                  title: "Tareas eliminadas",
                  text: "Tus tareas completadas han sido eliminada",
                  icon: "success",
               });

               currentTodos.forEach(todo => {
                  if (todo.classList.contains("pressed")) {
                     todo.remove();
                  }
               });

               isThereTodos();
               isThereTodosPending();
            } else {
               Swal.fire({
                  title: "Whoops!",
                  text: "Ha ocurrido un error",
                  icon: "error",
               });
            }
         } catch (error) {
            console.error(error);
         }
      }
   });
}

function hideAndShowTodos(event) {
   filterOptions.forEach(option => {
      option.classList.remove("active-option");
   });

   const currentOption = event.currentTarget;
   currentOption.classList.add("active-option");
   const currentTodos = document.querySelectorAll(".todo");
   const dataOption = currentOption.getAttribute("data-completed");

   currentTodos.forEach(todo => {
      verifyTodoCategory(todo, dataOption);
   });
}

function verifyTodoCategory(todo, dataOption) {
   const todoDataCompleted = todo.getAttribute("data-completed");

   if (dataOption === "all") {
      todo.classList.remove("hide-todo");
      return;
   }

   if (dataOption === todoDataCompleted) {
      todo.classList.remove("hide-todo");
      return;
   }

   todo.classList.add("hide-todo");
}
