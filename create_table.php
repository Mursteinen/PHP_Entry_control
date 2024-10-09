<?php
$pdo = new PDO('sqlite:logs.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create cardholders table
$pdo->exec("CREATE TABLE IF NOT EXISTS cardholders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    cardId TEXT UNIQUE,
    name TEXT,
    email TEXT
)");

// Create logs table if it doesn't exist
$pdo->exec("CREATE TABLE IF NOT EXISTS logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    cardId TEXT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    action TEXT,
    FOREIGN KEY (cardId) REFERENCES cardholders (cardId)
)");

echo "Tables created successfully!";
?>
