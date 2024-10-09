<?php
require 'db.php';

// Initialize a variable to store messages
$message = '';

// Get cardId from query parameter
$cardId = isset($_GET['cardId']) ? $_GET['cardId'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardId = $_POST['cardId']; // This will be set from the form submission
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Prepare and execute the insert statement
    $stmt = $pdo->prepare("INSERT INTO cardholders (cardId, name, email) VALUES (?, ?, ?)");
    $stmt->execute([$cardId, $name, $email]);

    // Set a success message
    $message = "Cardholder added successfully! Redirecting to the home page...";
    
    // Redirect to index.php after 2 seconds
    header("Refresh: 2; url=index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Cardholder</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #eef2f3;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 400px; /* Fixed width for better control */
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            font-size: 24px; /* Increased font size for heading */
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            color: #555;
            font-weight: bold; /* Make labels bold */
        }

        input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        input:focus {
            border-color: #007BFF; /* Change border color on focus */
            outline: none; /* Remove default outline */
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px; /* Increased font size for better readability */
            transition: background-color 0.3s, transform 0.3s; /* Smooth transitions */
        }

        button:hover {
            background-color: #0056b3;
            transform: translateY(-1px); /* Slight upward movement on hover */
        }

        .message {
            margin-top: 20px;
            font-size: 16px;
            color: #28a745; /* Green color for success */
            text-align: center; /* Center the message */
        }

        @media (max-width: 480px) {
            .container {
                width: 90%; /* Responsive width for smaller screens */
            }

            h1 {
                font-size: 22px; /* Smaller heading on mobile */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Cardholder</h1>
        
        <!-- Display success message if available -->
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        
        <form method="POST">
            <label for="cardId">Card ID:</label>
            <input type="text" id="cardId" name="cardId" value="<?php echo htmlspecialchars($cardId); ?>" readonly required>
            
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <button type="submit">Add Cardholder</button>
        </form>
    </div>
</body>
</html>
