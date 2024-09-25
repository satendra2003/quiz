<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Quiz Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            text-align: center;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Submitted Quiz Successfully</h2>
        <div id="responseMessage" class="message success">You have submitted your quiz successfully.</div>
    </div>
    <script>
    // Disable F12, F11, and other key combinations
        document.addEventListener('keydown', function (e) {
            if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I') || e.key === 'F11') {
                e.preventDefault();
            }
        });
        // Replace current state with submit_success.html to prevent back navigation
        history.pushState(null, null, 'submit_success.html');
    
        // Listen for the popstate event to handle back navigation
        window.addEventListener('popstate', function(event) {
            // Redirect to another page or handle as needed
            window.location.href = 'submit_success.html';
        });
        window.addEventListener('contextmenu', function (e) {
            e.preventDefault();
        });
    </script>

</body>
</html>