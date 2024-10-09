<?php
$pdo = new PDO('sqlite:logs.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create logs table if it doesn't exist
$pdo->exec("CREATE TABLE IF NOT EXISTS logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    cardId TEXT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
)");
?>
