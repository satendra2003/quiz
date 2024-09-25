<?php
// Include the database connection file
include 'db.php';

// Initialize a variable to hold any error messages
$errorMessage = '';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve email from POST data
    $email = $_POST['email'];

    // Check if email is not empty
    if (!empty($email)) {
        // Query the database for the student
        $sql = "SELECT * FROM students WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Student exists, start session
            session_start();
            $_SESSION['email'] = $email;

            // Redirect to quiz page
            header('Location: quiz.php');
            exit();
        } else {
            // Student does not exist, show an error message
            $errorMessage = "Invalid email address. Please try again.";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    } else {
        $errorMessage = "Please enter an email address.";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UPTOSKILLS</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/screenfull.js/5.0.2/screenfull.min.js"></script>
</head>

<body>
      
  <!-- Login Page -->
  <div id="login-page">
    <div class="header">
      <div class="logo">
        <img src="images/uptoskills.jpg" alt="UPTOSKILLS Logo">
      </div>
      <div class="title">
        <h2>UPTOSKILLS</h2>
        <p>QUIZ</p>
      </div>
    </div>
    <div class="candidate-info">
      <div class="system">
        <div class="system-name">System Name : <span class="name">C001</span></div>
        <div class="note">Kindly contact the invigilator if there are any discrepancies in the Name and Photograph
          displayed on the screen or if the photograph is not yours</div>
      </div>
      <div class="candidate">
        <div class="subject">Subject: <span class="name">UptoSkills Mock Exam</span></div>
      </div>
     
    </div>
    
    <div class="container2">
        <div class="login-form">
          <h2>Student Login</h2>
            <?php
                if ($errorMessage) {
                    echo "<p style='color:red;'>$errorMessage</p>";
                }
            ?>
          <form method="post">
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Submit</button>
          </form>
        </div>
    </div>
    
  </div>

</body>
</html>