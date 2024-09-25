<?php
// fetchQuestions.php

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if not logged in
    header('Location: index.php');
    exit();
}

// Database configuration (adjust according to your setup)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quizdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch 20 random questions from the database
$sql = "SELECT * FROM questions ORDER BY RAND() LIMIT 20";
$result = $conn->query($sql);

$questions = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $question = array(
            'id' => $row['id'],
            'question_text' => $row['question_text'],
            'option_1' => $row['option_1'],
            'option_2' => $row['option_2'],
            'option_3' => $row['option_3'],
            'option_4' => $row['option_4'],
            'correct_answer' => $row['correct_answer']
        );
        $questions[] = $question;
    }
} else {
    // No questions found
    echo "No questions found.";
}

// Close connection
$conn->close();

// Return questions as JSON
header('Content-Type: application/json');
echo json_encode($questions);
?>
