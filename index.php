<?php
require_once('database/conn.php');

// Verificar a solicitação da exclusão de uma tarefa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
  $deleteId = $_POST['delete_id'];

  try {
      // Consulta de exclusão
      $stmt = $db->prepare("DELETE FROM task WHERE id = :id");
      $stmt->bindValue(':id', $deleteId, SQLITE3_INTEGER);

      if ($stmt->execute()) {
          error_log("Tarefa com ID $deleteId excluída com sucesso.");
          // Após excluir, volta à página para atualizar a lista
          header('Location: ' . $_SERVER['PHP_SELF']);
          exit();
      } else {
          error_log("Erro ao excluir a tarefa com ID $deleteId");
      }
  } catch (Exception $e) {
      error_log("Erro ao excluir tarefa: " . $e->getMessage());
  }
}

// Consultar todas as tarefas no banco de dados
$tasks = [];
$sql = $db->query("SELECT * FROM task");

if ($sql && $sql->fetchArray(SQLITE3_ASSOC)) {
  $sql->reset();
  while ($row = $sql->fetchArray(SQLITE3_ASSOC)) {
    $tasks[] = $row;
  }
}

// Processar o formulário para adicionar uma nova task
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['description']) && !isset($_POST['delete_id'])) {
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
    
    <?php if (!empty($message)): ?> <!-- Exibir mensagens de sucesso ou erro -->
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
              <?= htmlspecialchars($task['description']) ?>
            </p>
            <div class="task-actions">
              <a class="action-btn edit-btn">
                <i class="fa-regular fa-pen-to-square"></i>
              </a>

              <form method="POST" action="" style="display:inline;">
                <input type="hidden" name="delete_id" value="<?= $task['id'] ?>"> 
                <button type="submit" class="action-btn delete-btn" style="background: none; border: none; padding: 0;">
                  <i class="fa-solid fa-eraser"></i>
                </button>
              </form>
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
