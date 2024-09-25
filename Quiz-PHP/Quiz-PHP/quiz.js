let currentQuestionIndex = 0;
let score = 0;
const totalQuestions = 20;
let answeredQuestions = Array(totalQuestions).fill(false);
let markedForReview = Array(totalQuestions).fill(false);
let notVisitedQuestions = Array(totalQuestions).fill(true);
let selectedAnswerIndex = Array(totalQuestions).fill(-1);
const answerArray = Array(totalQuestions).fill(-1);
const attemptedArray = Array(totalQuestions).fill(-1);
var questions = [];

console.log("hello",answerArray)
async function getData() {
    try {
        const response = await fetch('connection.php');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const fetchedQuestions = await response.json();
        questions = fetchedQuestions; 

        initializeQuiz();
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

getData(); // Call this function to fetch questions when the page loads

// Function to initialize quiz interface
function initializeQuiz() {
    const questionButtonsContainer = document.getElementById('question-buttons');
    questionButtonsContainer.innerHTML = ''; // Clear previous buttons if any

    for (let i = 0; i < totalQuestions; i++) {
        const button = document.createElement('button');
        button.textContent = i + 1;
        button.setAttribute('data-index', i);
        button.addEventListener('click', () => {
            goToQuestion(i);
            updateButtonStyles();
        });
        questionButtonsContainer.appendChild(button);
    }

    updateStatus();
    showQuestion();
}

/* Function to display current question and options
function showQuestion() {
    const currentQuestion = questions[currentQuestionIndex];

    // Parse the options from string to JSON array
    const parsedOptions = JSON.parse(currentQuestion.options);

    const questionNumberElement = document.getElementById('question-number');
    const questionElement = document.getElementById('question');
    const optionsContainer = document.getElementById('options');

    questionNumberElement.textContent = `Question No. ${currentQuestion.id}`;
    questionElement.textContent = currentQuestion.question;
    optionsContainer.innerHTML = ''; // Clear previous options if any

    // Create radio inputs for options
    parsedOptions.forEach((option, index) => {
        const optionElement = document.createElement('div');
        const inputElement = document.createElement('input');
        const labelElement = document.createElement('label');

        inputElement.type = 'radio';
        inputElement.name = 'option';
        inputElement.value = index;
        inputElement.id = `${index}`;

        // Check if the option is selected
        if (selectedAnswerIndex[currentQuestionIndex] === index) {
            inputElement.checked = true;
        }

        inputElement.addEventListener('click', (e) => {
            attemptedArray[currentQuestionIndex] = 1;
            let userAnswer = parsedOptions[e.target.id]
            let orignalAnswer =questions[currentQuestionIndex].correct_answer
          
            
            if(userAnswer == orignalAnswer){
                answerArray[currentQuestionIndex]=1
                console.log(answerArray)
            }
            else{
                answerArray[currentQuestionIndex] = -1
                console.log(answerArray)

            }
            
            // console.log(questions[currentQuestionIndex])
            selectedAnswerIndex[currentQuestionIndex] = index;
            answeredQuestions[currentQuestionIndex] = true;
            notVisitedQuestions[currentQuestionIndex] = false;
            updateStatus();
            updateButtonStyles();
        });

        labelElement.htmlFor = `${index}`;
        labelElement.textContent = option;

        optionElement.appendChild(inputElement);
        optionElement.appendChild(labelElement);

        optionsContainer.appendChild(optionElement);
    });

    updateButtonStyles();
}*/

function showQuestion() {
    const currentQuestion = questions[currentQuestionIndex];

    // Parse the options from string to JSON array
    const parsedOptions = JSON.parse(currentQuestion.options);

    const questionNumberElement = document.getElementById('question-number');
    const questionElement = document.getElementById('question');
    const optionsContainer = document.getElementById('options');

    //questionNumberElement.textContent = `Question No. ${currentQuestion.id}`;
    questionNumberElement.textContent = `Question No. ${currentQuestionIndex + 1}`;
    questionElement.textContent = currentQuestion.question;
    optionsContainer.innerHTML = ''; // Clear previous options if any

    // Create radio inputs for options
    parsedOptions.forEach((option, index) => {
        const optionElement = document.createElement('div');
        const inputElement = document.createElement('input');
        const labelElement = document.createElement('label');

        inputElement.type = 'radio';
        inputElement.name = 'option';
        inputElement.value = index;
        inputElement.id = `${index}`;

        // Check if the option is selected
        if (selectedAnswerIndex[currentQuestionIndex] === index) {
            inputElement.checked = true;
        }

        inputElement.addEventListener('click', (e) => {
            let userAnswer = parsedOptions[e.target.id]
            let orignalAnswer =questions[currentQuestionIndex].correct_answer
          
            
            if(userAnswer == orignalAnswer){
                answerArray[currentQuestionIndex]=1
                attemptedArray[currentQuestionIndex] = e.target.id;

                console.log('attempted',attemptedArray)
            }
            else{
                answerArray[currentQuestionIndex] = -1
                attemptedArray[currentQuestionIndex] = e.target.id;
                // console.log(answerArray)
                console.log('attempted',attemptedArray)


            }
            
            // console.log(questions[currentQuestionIndex])
            selectedAnswerIndex[currentQuestionIndex] = index;
            answeredQuestions[currentQuestionIndex] = true;
            notVisitedQuestions[currentQuestionIndex] = false;
            updateStatus();
            updateButtonStyles();
        });

        labelElement.htmlFor = `${index}`;
        labelElement.textContent = option;

        optionElement.appendChild(inputElement);
        optionElement.appendChild(labelElement);

        optionsContainer.appendChild(optionElement);
    });

    updateButtonStyles();
}

// Function to navigate to the next question
function nextQuestion() {
    if (currentQuestionIndex < totalQuestions - 1) {
        currentQuestionIndex++;
        showQuestion();
    } else {
        // Handle end of quiz (e.g., show summary or submit quiz)
        submitQuiz();
    }
}

// Function to navigate to the previous question
function prevQuestion() {
    if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        showQuestion();
    }
}


function goToQuestion(num){
    currentQuestionIndex = num
    showQuestion();
}

// Function to update button styles based on question status
function updateButtonStyles() {
    const buttons = document.querySelectorAll('#question-buttons button');
    buttons.forEach((button, index) => {
        button.classList.remove('not-visited', 'visited-not-answered', 'visited-answered', 'active', 'marked-for-review');

        if (currentQuestionIndex === index) {
            button.classList.add('active');
        } else if (answeredQuestions[index] && selectedAnswerIndex[index] !== -1) {
            button.classList.add('visited-answered');
        } else if (!notVisitedQuestions[index]) {
            button.classList.add('visited-not-answered');
        } else {
            button.classList.add('not-visited');
        }

        if (markedForReview[index]) {
            button.classList.add('marked-for-review');
        }
    });
}

// Function to update quiz status (answered, not answered, marked for review, etc.)
function updateStatus() {
    const answeredCount = answeredQuestions.filter(Boolean).length;
    const notAnsweredCount = totalQuestions - answeredCount;
    const markedCount = markedForReview.filter(Boolean).length;
    const notVisitedCount = notVisitedQuestions.filter(Boolean).length;

    document.getElementById('answered-count').textContent = answeredCount;
    document.getElementById('not-answered-count').textContent = notAnsweredCount;
    document.getElementById('marked-count').textContent = markedCount;
    document.getElementById('not-visited-count').textContent = notVisitedCount;

    updateButtonStyles();
}

// Function to mark current question for review
function markForReview() {
    markedForReview[currentQuestionIndex] = !markedForReview[currentQuestionIndex];
    updateButtonStyles();
}

// Function to clear selected response for current question
function clearResponse() {
    selectedAnswerIndex[currentQuestionIndex] = -1;
    answeredQuestions[currentQuestionIndex] = false;
    updateStatus();
    updateButtonStyles();

    const options = document.getElementsByName('option');
    options.forEach(option => {
        option.checked = false;
    });
}

/*function submitQuiz() {
    // Calculate score based on selected answers
    score = answerArray.filter((answer) => answer === 1).length;
    let attempted = attemptedArray.filter((answer) => answer === 1).length;
    console.log("score"+score);
    console.log('attempted'+attempted);

    const url = 'submit.php';
    const data = {
        'candidateName': document.querySelector('.candidate-name').textContent,
        'score': score,
        'attempted_question':attempted
    };

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.text()) // Get raw response as text
    .then(text => {
        console.log('Raw response:', text); // Log raw response for debugging
        const data = JSON.parse(text); // Parse JSON response

        // Check if the response contains a success message
        if (data.message) {
            // Display success message and redirect after a short delay
            const submissionMessage = document.createElement('p');
            submissionMessage.textContent = 'Submitted successfully!';
            submissionMessage.classList.add('submission-success');
            document.body.appendChild(submissionMessage);

            setTimeout(() => {
                window.location.href = "submit_quiz.php";
            }, 2000); // Redirect after 2 seconds
        } else {
            throw new Error(data.error);
        }
    })
    .catch(error => {
        console.error('Error submitting quiz:', error);
        // Handle error (e.g., show error message to user)
        const errorMessage = document.createElement('p');
        errorMessage.textContent = `Error: ${error.message}`;
        errorMessage.classList.add('submission-error');
        document.body.appendChild(errorMessage);
    });
}*/

function submitQuiz() {
    // Calculate score based on selected answers
    score = answerArray.filter((answer) => answer === 1).length;
    let qadata = [];
    attemptedArray.filter((question,index)=>{
        if(question !== -1){
           
            let options = questions[index].options
            options= JSON.parse(options)
            let selectedOption = options[+question]


            obj = {
                question: questions[index].question,
                answer: selectedOption
            }

            qadata.push(obj)
            // console.log(qadata)
        }
    });
    
    const url = 'submit.php';
    const data = {
        'candidateName': document.querySelector('.candidate-name').textContent,
        'score': score,
        'attempted_question':JSON.stringify(qadata),
        'givenQuestionAnswer':JSON.stringify(questions)
    };


    // console.log(data)
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.text()) // Get raw response as text
    .then(text => {
        console.log('Raw response:', text); // Log raw response for debugging
        const data = JSON.parse(text); // Parse JSON response

        // Check if the response contains a success message
        if (data.message) {
            // Display success message and redirect after a short delay
            const submissionMessage = document.createElement('p');
            submissionMessage.textContent = 'Submitted successfully!';
            submissionMessage.classList.add('submission-success');
            document.body.appendChild(submissionMessage);

            setTimeout(() => {
                window.location.href = "submit_quiz.php";
            }, 2000); // Redirect after 2 seconds
        } else {
            throw new Error(data.error);
        }
    })
    .catch(error => {
        console.error('Error submitting quiz:', error);
        // Handle error (e.g., show error message to user)
        const errorMessage = document.createElement('p');
        errorMessage.textContent = `Error: ${error.message}`;
        errorMessage.classList.add('submission-error');
        document.body.appendChild(errorMessage);
    });
}


// Event listeners for navigation buttons
document.getElementById('next-btn').addEventListener('click', nextQuestion);
document.getElementById('prev-btn').addEventListener('click', prevQuestion);
document.getElementById('mark-for-review-btn').addEventListener('click', markForReview);


// Fetch questions and initialize quiz on page load
document.addEventListener('DOMContentLoaded', () => {
    // fetchQuestions();
    getData();
});