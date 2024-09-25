<?php
// session_start(); // Start session

// Assuming form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establish database connection
    $conn = new mysqli("localhost", "root", "", "quizdb");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve email from form submission (you should sanitize and validate inputs)
    $email = $conn->real_escape_string($_POST["email"]);

    // Query to check if the email exists in your users table
    $sql = "SELECT * FROM students WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch user details
        $user = $result->fetch_assoc();
        
        // Store user information in session variables
        // $_SESSION["Fname"] = $user["Jagdeesh"]; // Example: storing user name

        // Redirect to the quiz page
        header("Location: quiz.php");
        exit();
    } else {
        // User does not exist or incorrect credentials, show an alert or handle as needed
        echo "<script>alert('Incorrect email. Please try again.');</script>";
    }

    // Close database connection
    $conn->close();
}
?>
