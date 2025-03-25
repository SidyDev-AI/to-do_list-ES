<?php
function conectarBancoDados() {
  $nomeBancoDados = 'database/tododatabase.db';

  try {
    $db = new SQLite3($nomeBancoDados);
    if (!$db) {
      throw new Exception("Erro ao conectar ou criar o banco de dados: " . $db->lastErrorMsg());
    }
    return $db;
  } catch (Exception $e) {
    die($e->getMessage());
  }
}

function criarTabelaTask($db) {
    $sql = "CREATE TABLE IF NOT EXISTS task (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        description VARCHAR(100) NOT NULL,
        completed BOOLEAN DEFAULT FALSE
    )";

    if (!$db->exec($sql)) {
        throw new Exception("Erro ao criar a tabela 'task': " . $db->lastErrorMsg());
    }
}

function adicionarTask($db, $description) {
  $stmt = $db->prepare("INSERT INTO task (description) VALUES (:description)");
  $stmt->bindValue(':description', $description, SQLITE3_TEXT);

  if ($stmt->execute()) {
      error_log("Tarefa adicionada: " . $description);
      return true;
  } else {
      error_log("Erro ao adicionar tarefa: " . $db->lastErrorMsg());
      return false;
  }
}

function deletarTask($db, $id) {
  $stmt = $db->prepare("DELETE FROM task WHERE id = :id");
  $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

  if ($stmt->execute()) {
      error_log("Tarefa com ID $id excluída.");
      return true;
  } else {
      error_log("Erro ao excluir tarefa: " . $db->lastErrorMsg());
      return false;
  }
}

try {

  $db = conectarBancoDados();
  criarTabelaTask($db);

  if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    if (deletarTask($db, $deleteId)) {
        echo "Tarefa excluída com sucesso!";
    } else {
        echo "Erro ao excluir a tarefa.";
    }
  }

} catch (Exception $e) {
  echo "Erro: " . $e->getMessage();
}
?>
