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

try {
  $db = conectarBancoDados();
  criarTabelaTask($db);
} catch (Exception $e) {
  echo "Erro: " . $e->getMessage();
}