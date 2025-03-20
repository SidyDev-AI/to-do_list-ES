<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="src/css/style.css">
  <title>To-Do List</title>
</head>
<body>
  <section id="to-do">
    <h1>Things To Do</h1>

    <form action="" class="to-do_form">
      <input type="text" name="description" placeholder="Write your task here" required>
      <button type="submit" class="form-btn">
        <i class="fa-solid fa-plus"></i>
      </button>
    </form>

    <section id="tasks">
      <div class="task">
        <input type="checkbox" name="progress" class="progress">
        <p class="task-description">Prova Amanhã</p>
        <div class="task-actions">
          <a class="action-btn edit-btn">
            <i class="fa-regular fa-pen-to-square"></i>
          </a>
          <a class="action-btn delete-btn">
            <i class="fa-solid fa-eraser"></i>
          </a>
        </div>

        <form action="" class="to-do_form edit-task hidden">
          <input type="text" name="description" placeholder="Edit your task here">
          <button type="submit" class="form-btn confirm-btn">
            <i class="fa-solid fa-check"></i>
          </button>
        </form>
      </div>
    </section>
  </section>

  <script src="src/js/script.js"></script>
</body>
</html>