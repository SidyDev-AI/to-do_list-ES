<?php
function conectarBancoDados() {
  $nomeBancoDados = 'database/to-do database.db';

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
try {
  $db = conectarBancoDados();
  criarTabelaTask($db);
} catch (Exception $e) {
  echo "Erro: " . $e->getMessage();
}