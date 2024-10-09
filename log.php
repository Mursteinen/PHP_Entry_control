<?php
header('Content-Type: application/json');
require 'db.php'; // Include your database connection file

$data = json_decode(file_get_contents('php://input'), true);
$cardId = $data['cardId'] ?? null; // Get the Card ID from the input

if ($cardId) {
    $now = date('Y-m-d H:i:s');

    // Fetch the cardholder's name from the cardholders table
    $stmt = $pdo->prepare("SELECT name FROM cardholders WHERE cardid = ?");
    $stmt->execute([$cardId]);
    $cardholder = $stmt->fetchColumn(); // Get the cardholder's name

    // If no cardholder is found, return an error message
    if (!$cardholder) {
        echo json_encode(['message' => "No cardholder found for Card ID: $cardId"]);
        exit;
    }

    // Check the logs for the last entry for the scanned card ID
    $stmt = $pdo->prepare("SELECT * FROM logs WHERE cardId = ? ORDER BY timestamp DESC LIMIT 1");
    $stmt->execute([$cardId]);
    $lastEntry = $stmt->fetch();

    // If there is a last entry, determine check-in or check-out
    if ($lastEntry) {
        $lastEntryTime = new DateTime($lastEntry['timestamp']);
        $currentEntryTime = new DateTime($now);
        $interval = $lastEntryTime->diff($currentEntryTime);
        $hoursDiff = $interval->h + ($interval->days * 24); // Calculate hours difference

        // Automatic check-out if inhouse is 1 and more than 10 hours have passed
        if ($lastEntry['inhouse'] == 1 && $hoursDiff > 10) {
            // Automatically check out the cardholder
            $stmt = $pdo->prepare("UPDATE logs SET inhouse = 0 WHERE cardId = ? AND timestamp = ?");
            $stmt->execute([$cardId, $lastEntry['timestamp']]);
            $message = "Cardholder '$cardholder' with Card ID $cardId was automatically checked out after 10 hours.";
            // After automatic check-out, log a new entry with inhouse = 0
            $inhouseValue = 0;
        } else {
            // Determine the action based on the last entry
            if ($lastEntry['inhouse'] == 1) {
                // Last entry is a check-in (inhouse = 1)
                $message = "Cardholder '$cardholder' with Card ID $cardId checked out.";
                $inhouseValue = 0; // Set inhouse to 0 for check-out
            } else {
                // Last entry is a check-out (inhouse = 0)
                $message = "Cardholder '$cardholder' with Card ID $cardId checked in.";
                $inhouseValue = 1; // Set inhouse to 1 for check-in
            }
        }
    } else {
        // If there’s no previous entry for this Card ID, consider it a check-in
        $message = "Cardholder '$cardholder' with Card ID $cardId checked in.";
        $inhouseValue = 1; // Set inhouse to 1 for check-in
    }

    // Log the entry regardless of check-in or check-out
    $stmt = $pdo->prepare("INSERT INTO logs (cardId, timestamp, inhouse) VALUES (?, ?, ?)");
    $stmt->execute([$cardId, $now, $inhouseValue]); // Log the entry with the corresponding inhouse value

    // Return the message
    echo json_encode(['message' => $message]);
} else {
    echo json_encode(['message' => 'Card ID is required.']);
}
?>
