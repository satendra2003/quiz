<?php
// db.php
$servername = "localhost"; // Typically 'localhost'
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "quizdb"; // Your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>