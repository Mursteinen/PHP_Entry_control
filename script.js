document.getElementById('card-input').addEventListener('keypress', function(event) {
    // Check if the key pressed is "Enter"
    if (event.key === 'Enter') {
        const cardId = event.target.value.trim(); // Get the card ID and trim any extra spaces
        
        if (cardId) {
            // Send the card ID to the server
            fetch('log.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ cardId }),
            })
            .then(response => response.json())
            .then(data => {
                // Check if no cardholder was found
                if (data.message.includes("No cardholder found")) {
                    // Redirect to add_cardholder.php with cardId as a query parameter
                    window.location.href = `add_cardholder.php?cardId=${encodeURIComponent(cardId)}`;
                } else {
                    // Display log entry
                    document.getElementById('log').innerHTML += `<p>${data.message}</p>`;
                }
                // Clear the input after processing
                event.target.value = ''; // Clear the input field for the next scan
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
    }
});
