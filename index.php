<?php
require_once('database/conn.php');

$tasks = [];
$sql = $db->query("SELECT * FROM task");

if ($sql && $sql->fetchArray(SQLITE3_ASSOC)) {
  $sql->reset();
  while ($row = $sql->fetchArray(SQLITE3_ASSOC)) {
    $tasks[] = $row;
  }
}

## if para processar o formulário para adicionar uma nova task
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['description'])) {
  $descricao = trim($_POST['description']);

  if (strlen($descricao) > 0) {
      $stmt = $db->prepare("INSERT INTO task (description) VALUES (:description)");
      $stmt->bindValue(':description', $descricao, SQLITE3_TEXT);
      
      if ($stmt->execute()) {
          $lastId = $db->lastInsertRowID();
          $newTask = ['id' => $lastId, 'description' => $descricao];

          error_log("Nova task adicionada com ID: " . $lastId); // Log no servidor
          header('Content-Type: application/json');
          echo json_encode(['success' => true, 'message' => 'Task adicionada, recarregue a página']);
          exit();
      } else {
          error_log("Erro ao inserir no banco!");
          header('Content-Type: application/json');
          echo json_encode(['success' => false, 'error' => 'Erro ao inserir no banco de dados']);
          exit();
      }
  }
}
?>
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
    
    <?php if (!empty($message)): ?> ## if para exibir a mensagem de sucesso ou erro
  <p class="message"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

    <form action="" class="to-do_form">
      <input type="text" name="description" placeholder="Write your task here" required>
      <button type="submit" class="form-btn">
        <i class="fa-solid fa-plus"></i>
      </button>
    </form>

    <section id="tasks">
      <?php if (!empty($tasks)): ?>
      <?php foreach($tasks as $task): ?>
        <div class="task">
          <input type="checkbox" name="progress" class="progress" <?= $task['completed'] ? 'checked' : '' ?> >
          <p class="task-description">
            <?= $task['description'] ?>
          </p>
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
      <?php endforeach ?>
      <?php endif ?>
    </section>
  </section>

  <script src="src/js/script.js"></script>
</body>
</html>