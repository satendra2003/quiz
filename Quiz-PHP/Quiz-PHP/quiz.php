<?php
session_start();

/* Check if the session variable is set
if (isset($_SESSION["Fname"])) {
    $username = $_SESSION["Fname"];
} else {
    // Handle the case where the session variable is not set, e.g., redirect to login page
    header("Location: index.php");
    exit();
}*/

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

// Include the database connection file
include 'db.php';

// Fetch the logged-in student's details
$email = $_SESSION['email'];
$sql = "SELECT first_name FROM students WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
    $firstName = $student['first_name'];
} else {
    // If no student is found, log out and redirect to the login page
    session_destroy();
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPTOSKILLS</title>
    <link rel="stylesheet" href="style.css">
    <!-- <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/screenfull.js/5.0.2/screenfull.min.js"></script>
</head>

<body>
    
    <div id="login-page">
        <div class="header">
            <div class="logo">
                <img src="images/uptoskills.jpg" alt="UPTOSKILLS Logo">
            </div>
            <div class="title">
                <h2>UPTOSKILLS</h2>
                <p>UPTOSKILLS QUIZ</p>
            </div>
        </div>
        <div class="candidate-info">
            <div class="system">
                <div class="system-name">System Name : <span class="name">C001</span></div>
                <div class="note">Kindly contact the invigilator if there are any discrepancies in the Name and Photograph
                    displayed on the screen or if the photograph is not yours</div>
            </div>
            <div class="candidate">
                
                <div class="system-name">                
                    <p>Welcome, <?php echo htmlspecialchars($firstName); ?> (<?php echo htmlspecialchars($email); ?>)</p>
                </div>
                <div class="subject note">Subject: <span class="name">uptoskills Mock Exam</span></div>
            </div>
            
        </div>
        <div class="guidelines">
            <div>
                <h3>&nbsp;Guidelines for the Quiz:</h3>
                <ul>
                    <li>&nbsp;1)&nbsp;Please ensure you have a stable internet connection throughout the quiz.</li>
                    <li>&nbsp;2)&nbsp;Do not refresh the page or navigate away from the quiz page during the exam.</li>
                    <li>&nbsp;3)&nbsp;Answer all questions to the best of your ability.</li>
                    <li>&nbsp;4)&nbsp;Use of any unauthorized aids or communication during the exam is strictly prohibited.</li>
                    <li>&nbsp;5)&nbsp;Once started, the quiz timer will run continuously. Be mindful of the time remaining.</li>
                    <li><strong>&nbsp;6)&nbsp;Do not exit fullscreen mode during the quiz.</strong> If you attempt to exit fullscreen, you will be redirected to the result page and strict action will be taken.</li>
                    <li><strong>&nbsp;7)&nbsp;You are under invigilation.</strong> Any suspicious behavior or violation of exam rules will be reported.</li>
                </ul>
            </div>
        </div>
        <div class="accept-guidelines">
            <input type="checkbox" id="accept-guidelines-checkbox" required>
            <label for="accept-guidelines-checkbox">I accept the guidelines for the quiz</label><br><br>
            <button id="start-quiz-btn" disabled>Start Quiz</button>
        </div>
    </div>

    <div id="quiz-page" style="display:none;">
        <div class="header">
            <div class="logo">
                <img src="images/uptoskills.jpg" alt="UPTOSKILLS Logo">
            </div>
            <div class="title">
                <h2>UPTOSKILLS</h2>
                <p>UPTOSKILLS QUIZ</p>
            </div>
        </div>
        <div class="paper-row">
            <p>UptoSkill Quizz</p>
            <div>
               
                <span><i class="fa fa-board"></i>UptoSkills Quiz</span>
            </div>
        </div>
        <div class="candidateContainer">
            <div class="candidate-left">
                <div class="row">
                <div class="navbar">
                        <div class="timer" id="countdown">Time Left: <span id="time">90:00</span></div>
                    </div>
                </div>
            </div>
            <div class="candidate-right">
                
                <!-- Wrapper for the video frame and camera-off message -->
                <div class="video-wrapper">
                    <!-- Video element to display the camera stream -->
                    <video id="cameraStream" autoplay playsinline></video>
    
                    <!-- Message to display when the camera is off -->
                    <div id="cameraOffMessage" class="camera-off-message">
                        The camera is off. Please enable it to view the stream.
                    </div>
                </div>

            </div>
            <div class="name">
                <h2 class="candidate-name"> <?php //echo htmlspecialchars($username);?> <?php echo htmlspecialchars($firstName); ?> </h2>
            </div>
        </div>
        <div class="quiz-container">
            <div class="container">
                <main>
                    <div id="quiz-section">
                        <div class="title-row">
                            <span class="special-text">Question Type: Multiple Type Question</span>
                        </div>
                        <div class="lang-row">
                            <div class="lang">
                                View in :<select name="" id="">
                                    <option value="English">English</option>
                                    <option value="Hindi">Hindi</option>
                                </select>
                            </div>
                        </div>
                        <div id="question-container">
                            <div id="quiz">
                                <h2 id="question-number">Question No. 1</h2>
                                <div id="question"></div>
                                <div id="options"></div>
                            </div>
                        </div>
                        
                    </div>
                    <aside>
                        <div id="status-panel">
                            <div> Answered: <span id="answered-count">0</span></div>
                            <div>Not Answered: <span id="not-answered-count">20</span></div>
                            <div>Not Visited: <span id="not-visited-count">20</span></div>
                            <div>Marked for Review: <span id="marked-count">0</span></div>
                            <div>Answered & Marked for Review: <span id="marked-count">0</span></div>
                        </div>
                        <div id="question-nav">
                            <h3>UPTOSKILL</h3>
                            <div id="question-buttons">
                                <!-- Question buttons will be added dynamically here -->
                            </div>
                        </div>
                    </aside>
                </main>
                <footer>
                    <div class="markreview">
                        <button id="mark-for-review-btn">Mark For Review & Next</button>
                        
                    </div>
                    <div class="btn-sprt">
                        <button id="prev-btn">Previous Question</button>
                        <button id="next-btn">Save & Next</button>
                        <button id="submit" onclick='submitQuiz()'>Submit</button>
                    </div>
                    <!-- amit tp -->
                    <!-- <div class="color-instruction">
                        <ul>
                            <li>Red for current question</li>
                            <li>Green for attempted question</li>
                            <li>Yellow for the question marked for review</li>
                        </ul>
                    </div> -->
                    <!-- amit tp -->
                </footer>
            </div>
        </div>
    </div>
   
    <div id="popup" class="popup">
        <div class="popup-content">
            <img src="images/logo.png" alt="Logo" class="popup-logo">
            <h2>Do you want to exit the page?</h2>
            <div class="popup-buttons">
                <button onclick="exitPage()">Yes</button>
                <button onclick="closePopup()">No</button>
            </div>
        </div>
    </div>
    
     <script>
        // Auto logout after 1 minute of inactivity
         let inactivityTime = function () {
             let time;
             window.onload = resetTimer;
             window.onmousemove = resetTimer;
             window.onmousedown = resetTimer; // catches touchscreen presses
             window.ontouchstart = resetTimer;
             window.ontouchmove = resetTimer;
             window.onclick = resetTimer;     // catches touchpad clicks
             window.onkeydown = resetTimer;
             window.addEventListener('scroll', resetTimer, true); // improved; see comments

             function logout() {
                 var xhr = new XMLHttpRequest();
                 xhr.open("POST", "logout.php", true);
                 xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                 xhr.send();
                 alert("You have been logged out due to inactivity.");
                 window.location.href = 'index.php';
             }

             function resetTimer() {
                 clearTimeout(time);
                 time = setTimeout(logout, 60000);  // 1 minute = 60000 milliseconds
             }
         };

         inactivityTime();

        // Check internet connection and logout if disconnected
         function checkConnection() {
             if (!navigator.onLine) {
                 // User is offline, send logout request to server
                 var xhr = new XMLHttpRequest();
                 xhr.open("POST", "logout.php", true);
                 xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                 xhr.send();
                 alert("You have been logged out due to lost internet connection.");
                 window.location.href = 'index.php';
             }
         }

        // Check connection status every 5 seconds
         setInterval(checkConnection, 5000);
        
    </script> 
    <script src="quiz.js"></script>
    <script>
        document.getElementById('mark-for-review-btn').addEventListener('click', ()=>{
            const btns = document.querySelectorAll("#question-buttons button");

            btns.forEach((btn,idx)=>{
                if(currentQuestionIndex === idx){
                    console.log(btn);
                    btn.classList.add('marked-for-review')
                }
            })

            nextQuestion();
        })
        document.getElementById('accept-guidelines-checkbox').addEventListener('change', function() {
            document.getElementById('start-quiz-btn').disabled = !this.checked;
        });

        document.getElementById('start-quiz-btn').addEventListener('click', function() {
            // Hide login page and show quiz page
            document.getElementById('login-page').style.display = 'none';
            document.getElementById('quiz-page').style.display = 'block';
        
            // Request fullscreen mode
             let myDocument = document.documentElement;
             if (myDocument.requestFullscreen) {
                 myDocument.requestFullscreen();
             } else if (myDocument.msRequestFullscreen) {
                 myDocument.msRequestFullscreen();
             } else if (myDocument.mozRequestFullscreen) {
                 myDocument.mozRequestFullscreen();
             } else if (myDocument.webkitRequestFullscreen) {
                 myDocument.webkitRequestFullscreen();
             }
             startWebcam();
        });

         document.addEventListener('fullscreenchange', (event) => {
             if (!document.fullscreenElement) {
                 // Show the popup when exiting fullscreen
                 document.getElementById('popup').style.display = 'block';
             }
         });

         function submitQuizFunction() {
             // Function to handle quiz submission
         //    alert("Quiz submitted!");
             // Add your submission logic here
         }
        
         function exitPage() {
             // Handle the "Yes" button click
             // Redirect to result page or desired action
             window.location.href = 'submit.php'; // Update with your result page URL
         }

         function closePopup() {
             // Handle the "No" button click
             document.getElementById('popup').style.display = 'none';
             // Request fullscreen mode again
             let myDocument = document.documentElement;
             if (myDocument.requestFullscreen) {
                 myDocument.requestFullscreen();
             } else if (myDocument.msRequestFullscreen) {
                 myDocument.msRequestFullscreen();
             } else if (myDocument.mozRequestFullscreen) {
                 myDocument.mozRequestFullscreen();
             } else if (myDocument.webkitRequestFullscreen) {
                 myDocument.webkitRequestFullscreen();
             }
         }

        // async function submitQuizFunction() {
        //     // Collect data and submit
        //     const url = 'submit.php';
        //     const data = {
        //         candidateName: JSON.parse(localStorage.getItem('currentUser')).user.username,
        //         score: calculateScore()
        //     };

        //     const response = await fetch(url, {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json'
        //         },
        //         body: JSON.stringify(data)
        //     });

        //     if (!response.ok) {
        //         throw new Error('Network response was not ok ' + response.statusText);
        //     }

        //     const responseData = await response.json();
        //     alert('Quiz submitted successfully!');

        //     // Redirect to result page or display result
        //     window.location.href = "submit.php";
        // }
        

        // function calculateScore() {
        //     // Dummy function to calculate score
        //     return 10;
        // }

        // // Security
          document.addEventListener('visibilitychange', function() {
             if (document.hidden) {
                 alert("You have attempted to switch tabs! This action is not allowed.");
                 submitQuizFunction();
              }
          });
         var countdownTime = 90 * 60;

         function startCountdown() {
         var timer = setInterval(function() {
             var minutes = Math.floor(countdownTime / 60);
             var seconds = countdownTime % 60;

             // Display the time in MM:SS format
             document.getElementById('countdown').textContent = 'Countdown: ' + 
             (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') + seconds;

             // Decrease countdown time by 1 second
             countdownTime--;

             // Check if countdown reaches 0
             if (countdownTime < 0) {
             clearInterval(timer);
             document.getElementById('countdown').textContent = 'Time expired!';
             // Optionally, you can add further actions when the countdown ends
             }
         }, 1000); // Update every second (1000 milliseconds)
         }

         // Start the countdown when the page loads
         window.onload = function() {
         startCountdown();
         };
        
         // Webcam logic
         function startWebcam() {
             var video = document.getElementById("cameraStream");

             navigator.mediaDevices.getUserMedia({ video: true })
                 .then(function(stream) {
                     video.srcObject = stream;
                 })
                 .catch(function(error) {
                     console.error("Error accessing the webcam: ", error);
                     document.getElementById("cameraOffMessage").style.display = "block";
                     alert('Webcam access is required to proceed with the quiz. Please enable access and reload the page.');
                 });
         }
         // Call the startWebcam() function to initiate the webcam stream
         startWebcam();
         
         
        /* Disable right-click context menu
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
        
        // Disable inspect element and other key combinations
        document.onkeydown = function(e) {
            if (e.key === "F12" || (e.ctrlKey && e.shiftKey && e.key === "I") || 
                (e.ctrlKey && e.shiftKey && e.key === "C") || 
                (e.ctrlKey && e.shiftKey && e.key === "J") || 
                (e.ctrlKey && e.key === "U") || 
                (e.ctrlKey && e.key === "P") || 
                (e.ctrlKey && e.key === "S")) {
                return false;
            }
        };*/

    </script>
</body>

</html>
