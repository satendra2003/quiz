document.addEventListener("DOMContentLoaded", function() {
    // Retrieve user's answers from localStorage
    const userAnswers = JSON.parse(localStorage.getItem("userAnswers"));
    if (!userAnswers) {
        // Handle if userAnswers is not available
        return;
    }
    
    // Calculate the score
    let score = 0;
    const correctAnswers = ["b", "c", "a", "c", "b", "a", "c", "c", "b", "b"];
    userAnswers.forEach((answer, index) => {
        if (answer === correctAnswers[index]) {
            score++;
        }
    });
    
    // Display the score
    document.getElementById("score").textContent = score;
    
    // Provide feedback based on the score
    let feedback = "";
    if (score === 10) {
        feedback = "Congratulations! You got all questions correct.";
    } else if (score >= 7) {
        feedback = "Well done! You did a great job.";
    } else if (score >= 5) {
        feedback = "Good effort! Keep practicing to improve.";
    } else {
        feedback = "Don't worry! Keep learning and try again.";
    }
    document.getElementById("feedback").textContent = feedback;
});


document.getElementById('quizForm').addEventListener('submit', function (event) {
    // Prevent the default form submission behavior
    event.preventDefault();

    // Define the correct answers
    var correctAnswers = ["b", "c", "a", "c", "b", "a", "c", "c", "b", "b"];
    
    // Initialize score
    var score = 0;

    // Iterate through each question and check the user's selected answer
    for (var i = 1; i <= 10; i++) {
        var userAnswer = document.querySelector('input[name="q' + i + '"]:checked');
        
        if (userAnswer) {
            // If user answered the question, check if it's correct
            if (userAnswer.value === correctAnswers[i - 1]) {
                score++; // Increment score for correct answer
            }
        }
    }

    // Display the score
    var resultContainer = document.getElementById('result');
    resultContainer.innerHTML = "Your score: " + score + "/10";
    resultContainer.style.display = "block";

    // Hide the submit button after submission
    document.getElementById('submitBtn').style.display = 'none';
});
