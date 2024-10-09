<?php
require 'db.php';

// Fetch all cardholders with their latest log entry, including the timestamp
$stmt = $pdo->query("
    SELECT c.cardId, c.name, c.email, l.inhouse, l.timestamp
    FROM cardholders c
    LEFT JOIN (
        SELECT l1.cardId, l1.inhouse, l1.timestamp
        FROM logs l1
        INNER JOIN (
            SELECT cardId, MAX(timestamp) AS maxTimestamp
            FROM logs
            GROUP BY cardId
        ) l2 ON l1.cardId = l2.cardId AND l1.timestamp = l2.maxTimestamp
    ) l ON c.cardId = l.cardId
    ORDER BY c.name ASC
");
$cardholders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($cardholders);
?>
