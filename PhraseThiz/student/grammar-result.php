<?php
// Include the database connection file
include("../conn/conn.php");
session_start();

// Check if the user is logged in
if (!isset($_SESSION['student_id']) || !isset($_SESSION['username'])) {
    header("Location: ../login/login.php"); // Redirect to login if not logged in
    exit();
}

// Get the student ID and username from the session
$student_id = $_SESSION['student_id'];
$username = $_SESSION['username'];

// Generate a unique student_quiz_id
function generateUniqueQuizID($con) {
    // Fetch the latest student_quiz_id
    $query = "SELECT student_quiz_id FROM studentquiz ORDER BY LENGTH(student_quiz_id) DESC, student_quiz_id DESC LIMIT 1";
    $result = mysqli_query($con, $query);

    $latestID = 'SQ00'; // Default starting point
    if ($row = mysqli_fetch_assoc($result)) {
        $latestID = $row['student_quiz_id'];
    }

    // Extract the numeric part and increment it
    $numericPart = intval(substr($latestID, 2)); // Remove "SQ" prefix and convert to integer
    $nextID = $numericPart + 1;

    // Generate the new student_quiz_id with zero-padding
    return 'SQ' . str_pad($nextID, 2, "0", STR_PAD_LEFT);
}

// Generate the unique ID
$student_quiz_id = generateUniqueQuizID($con);

// Calculate the quiz score dynamically (example logic)
$total_questions = 10; // Total number of questions in the quiz
$correct_answers = 10; // Replace this with the actual number of correct answers from the quiz logic
$score = ($correct_answers / $total_questions) * 100; // Calculate percentage

// Prevent duplicate entries by checking if the quiz data already exists for the student
$checkQuery = "
    SELECT COUNT(*) AS count 
    FROM studentquiz 
    WHERE student_id = '$student_id' AND quiz_id = 'QZ01'";
$checkResult = mysqli_query($con, $checkQuery);
$checkRow = mysqli_fetch_assoc($checkResult);

if ($checkRow['count'] == 0) { // Only insert if no previous entry exists
    // Insert the quiz data into the database
    $insertQuery = "
        INSERT INTO studentquiz (student_quiz_id, student_id, quiz_id, date_taken, score)
        VALUES ('$student_quiz_id', '$student_id', 'QZ01', CURDATE(), $score)";
    if (!mysqli_query($con, $insertQuery)) {
        die("Error: " . mysqli_error($con));
    }
}

// Fetch the latest quiz score for the student
$sql = "
    SELECT score 
    FROM studentquiz 
    WHERE student_id = '$student_id'
    ORDER BY date_taken DESC, student_quiz_id DESC 
    LIMIT 1";
$result = mysqli_query($con, $sql);

$score = 0;
if ($row = mysqli_fetch_assoc($result)) {
    $score = $row['score'];
}

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grammar Quiz Result</title>
    <link rel="stylesheet" href="../css/grammar-result.css">
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
<main>
    <div class="quiz-result-container">
        <h2>Grammar Quiz Result</h2>
        <div class="result-box">
            <div class="score-section">
                <p class="score <?php 
                    if ($score <= 50) echo 'red'; 
                    elseif ($score <= 70) echo 'orange'; 
                    else echo 'green'; 
                ?>">
                    <?php echo htmlspecialchars($score); ?>%
                </p>
                <p class="rating">
                    <?php 
                    if ($score >= 90) echo "EXCELLENT";
                    elseif ($score >= 75) echo "GOOD";
                    elseif ($score >= 50) echo "AVERAGE";
                    else echo "NEEDS IMPROVEMENT";
                    ?>
                </p>
            </div>
            <div class="thank-you-box">
                <p class="thank-you-message">Thank you for answering, <?php echo htmlspecialchars($username); ?>.</p>
            </div>
        </div>
    </div>
</main>
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
</body>
</html>
