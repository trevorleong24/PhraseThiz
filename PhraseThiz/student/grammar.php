<?php
session_start(); // Start the session

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login/login.php"); // Redirect to login if not logged in
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "phrasethiz"; // Your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch quiz details using the title
$sql_quiz = "SELECT quiz_id, title, description FROM quiz WHERE title = 'Grammar Quiz'";
$result_quiz = $conn->query($sql_quiz);

// Initialize title, description, and quiz_id
$quiz_title = "No title available.";
$quiz_description = "No description available.";
$quiz_id = null;

if ($result_quiz && $result_quiz->num_rows > 0) {
    $row_quiz = $result_quiz->fetch_assoc();
    $quiz_title = $row_quiz['title'];
    $quiz_description = $row_quiz['description'];
    $quiz_id = $row_quiz['quiz_id'];
}

// Query to count the number of questions for the specific quiz_id
$question_count = 0;
if ($quiz_id) {
    $sql_questions = "SELECT COUNT(*) AS question_count FROM question WHERE quiz_id = ?";
    $stmt = $conn->prepare($sql_questions);
    $stmt->bind_param("s", $quiz_id);
    $stmt->execute();
    $result_questions = $stmt->get_result();

    if ($result_questions && $result_questions->num_rows > 0) {
        $row_questions = $result_questions->fetch_assoc();
        $question_count = $row_questions['question_count'];
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseThiz - Grammar Quiz</title>
    <link rel="stylesheet" href="../css/grammar.css">
</head>
<body>
<header class="header">
    <div class="header-left">
        <a href="student-homepage.php" class="logo">PhraseThiz</a>
        <div class="dropdown">
            <button class="dropdown-btn">Our Quiz ▼</button>
            <div class="dropdown-content">
                <a href="../student/grammar.php">Grammar</a>
                <a href="../student/vocabulary.php">Vocabulary</a>
            </div>
        </div>
    </div>
    <div class="header-right">
        <span>Welcome, <a href="../student/student-profile.php" class="profile-link"><?php echo htmlspecialchars($_SESSION['username']); ?></a>!</span>
    </div>
</header>

<section class="quiz-container">
    <h1><?php echo $quiz_title; ?></h1>
    <div class="quiz-box">
        <!-- Number of Questions Container -->
        <div class="questions-box">
            <span class="questions-number"><?php echo $question_count; ?></span>
            <p class="questions-text">QUESTIONS</p>
        </div>
        <!-- Description Container -->
        <div class="quiz-info-box">
            <p>
                <?php echo $quiz_description; ?>
            </p>
            <button class="start-quiz-btn" onclick="startQuiz()">Start Quiz</button>
        </div>
    </div>
</section>

<footer>
    <div class="footer-container">
        <div class="footer-section">
            <h3>About Us</h3>
            <ul>
                <li><a href="../about-us/about-us.php">About PhraseThiz</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Resources</h3>
            <ul>
                <li><a href="../term-privacy/term-privacy.php">Terms</a></li>
                <li><a href="../term-privacy/term-privacy.php">Privacy</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="../student/grammar.php">Grammar</a></li>
                <li><a href="../student/vocabulary.php">Vocabulary</a></li>
            </ul>
        </div>
        <div class="footer-copyright">
            <p>Copyright &copy; 2024 PhraseThiz. All rights reserved</p>
        </div>
    </div>
</footer>

<script>
    function startQuiz() {
        window.location.href = "../student/grammar-quiz.php"; // Redirect to the quiz page
    }
</script>
</body>
</html>
