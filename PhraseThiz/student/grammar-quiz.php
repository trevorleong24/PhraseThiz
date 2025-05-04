<?php
include("../conn/conn.php");
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login/login.php"); // Redirect to login if not logged in
    exit();
}

// Ensure student_id is set
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$quiz_id = "QZ01"; // Replace with your actual quiz ID if needed
$current_question_index = isset($_GET['index']) ? (int)$_GET['index'] : 0;

// Fetch all questions for the quiz
$sql = "
    SELECT 
        q.question_id, 
        q.question_text, 
        q.`Option 1` AS option1, 
        q.`Option 2` AS option2, 
        q.`Option 3` AS option3, 
        q.`Option 4` AS option4, 
        a.answer_text
    FROM question q
    INNER JOIN answer a ON q.question_id = a.question_id
    WHERE q.quiz_id = '$quiz_id' AND a.is_correct = 1
    ORDER BY q.question_id
";
$result = mysqli_query($con, $sql);

$questions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $questions[] = $row;
}

// Function to generate the next student_quiz_id
function getNextStudentQuizId($con) {
    $sql = "SELECT student_quiz_id FROM studentquiz ORDER BY LENGTH(student_quiz_id) DESC, student_quiz_id DESC LIMIT 1";
    $result = mysqli_query($con, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        // Extract the numeric part and increment it
        $last_id = (int)substr($row['student_quiz_id'], 2);
        $new_id = $last_id + 1;
        return "SQ" . str_pad($new_id, 2, "0", STR_PAD_LEFT);
    } else {
        // No records found, start with SQ01
        return "SQ01";
    }
}

$feedback = ""; // To store feedback message
$next_url = ""; // To store the URL to redirect after 1.5s if needed

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_answer = $_POST['answer'] ?? '';
    $current_question_id = $questions[$current_question_index]['question_id'];
    $correct_answer = $questions[$current_question_index]['answer_text'];

    if (!isset($_SESSION['quiz_score'])) {
        $_SESSION['quiz_score'] = 0; // Initialize quiz score
    }

    // Normalize answers and compare
    if (trim(strtolower($selected_answer)) === trim(strtolower($correct_answer))) {
        $_SESSION['quiz_score']++;
        $feedback = "Correct!";
    } else {
        $feedback = "Wrong! The correct answer was: " . htmlspecialchars($correct_answer);
    }

    // Check if this is the last question
    if ($current_question_index + 1 >= count($questions)) {
        // Quiz is complete
        $score = $_SESSION['quiz_score'];
        $total_questions = count($questions);
        $percentage_score = ($score / $total_questions) * 100; // Calculate percentage

        $student_quiz_id = getNextStudentQuizId($con); // Get the next ID
        $date_taken = date('Y-m-d');

        // Insert percentage score into the database
        $insert_sql = "
            INSERT INTO studentquiz (student_quiz_id, student_id, quiz_id, date_taken, score)
            VALUES ('$student_quiz_id', '$student_id', '$quiz_id', '$date_taken', $percentage_score)
        ";
        mysqli_query($con, $insert_sql);

        unset($_SESSION['quiz_score']); // Clear session quiz score

        // After showing feedback, go to the result page
        $next_url = "../student/grammar-result.php";
    } else {
        // Move to next question
        $next_index = $current_question_index + 1;
        $next_url = "../student/grammar-quiz.php?index=$next_index";
    }
}

if ($current_question_index >= count($questions) && empty($feedback)) {
    die("Quiz complete! Thank you for participating.");
}

$current_question = $questions[$current_question_index];
$question_text = $current_question['question_text'];
$options = [
    $current_question['option1'],
    $current_question['option2'],
    $current_question['option3'],
    $current_question['option4']
];

mysqli_close($con);

// Darker background colors for feedback
$is_correct = (!empty($feedback) && strpos($feedback, 'Correct!') === 0);
$feedback_bg_color = $is_correct ? '#28a745' : '#dc3545'; // Darker green for correct, darker red for wrong
$feedback_text_color = '#ffffff'; // White text for contrast
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grammar Quiz</title>
    <link rel="stylesheet" href="../css/grammar-quiz.css">
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

<div class="container" style="display: flex; align-items: center; justify-content: center;">
    <h1 style="flex: 1; text-align: center;">
        Q<?php echo str_pad($current_question_index + 1, 2, "0", STR_PAD_LEFT); ?>
    </h1>
    <form id="quiz-form" method="POST" action="../student/grammar-quiz.php?index=<?php echo $current_question_index; ?>" style="flex: 2; text-align: center;">
        <div class="question"><?php echo htmlspecialchars($question_text); ?></div>
        <ul class="options" style="list-style: none; padding: 0; margin: 0;">
            <?php foreach ($options as $option): ?>
                <li style="margin-bottom: 10px;">
                    <input type="radio" name="answer" value="<?php echo htmlspecialchars($option); ?>" required>
                    <?php echo htmlspecialchars($option); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <button type="submit" class="btn" style="margin-top: 10px;">Submit</button>
        
        <?php if (!empty($feedback)): ?>
            <div class="feedback" style="margin-top: 10px; padding: 10px; border-radius: 5px; background-color: <?php echo $feedback_bg_color; ?>; color: <?php echo $feedback_text_color; ?>;">
                <?php echo $feedback; ?>
            </div>
        <?php endif; ?>
    </form>
</div>

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

<?php if (!empty($feedback) && !empty($next_url)): ?>
<script>
    // Show feedback briefly, then fade out and redirect
    setTimeout(function() {
        document.body.classList.add('fade-out');
    }, 500); // Start fade-out at 0.5s

    setTimeout(function() {
        window.location = "<?php echo $next_url; ?>";
    }, 1500); // Redirect after 1.5s total
</script>
<?php endif; ?>

</body>
</html>