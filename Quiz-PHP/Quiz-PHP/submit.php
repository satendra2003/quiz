<?php
ob_start(); // Start output buffering

header("Content-Type: application/json");

$response = array();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);

    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        $response["error"] = "Invalid JSON data";
    } else {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "quizdb";
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            http_response_code(500);
            $response["error"] = "Connection failed: " . $conn->connect_error;
        } else {
            $stmt = $conn->prepare("INSERT INTO results(candidateName, score, isTestGiven, attempted_question, givenQuestionAnswer, submitted_at) VALUES (?, ?, 'yes', ?, ?, NOW())");
$stmt->bind_param("siss", $data['candidateName'], $data['score'], $data['attempted_question'], $data['givenQuestionAnswer']);
$stmt->execute();


            if ($stmt->affected_rows > 0) {
                $response["message"] = "Data inserted successfully";
            } else {
                http_response_code(500);
                $response["error"] = "Failed to insert data";
            }

            $stmt->close();
            $conn->close();
        }
    }
} else {
    http_response_code(405);
    $response["error"] = "Method not allowed";
}

echo json_encode($response);

ob_end_flush(); // End output buffering and send output
?>