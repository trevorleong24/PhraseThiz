<?php
session_start(); // Start the session

// Redirect if not logged in as instructor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'instructor') {
    header("Location: ../login/login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "phrasethiz"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate the next sequential ID
function getNextId($conn, $table, $column, $prefix) {
    $sql = "SELECT $column FROM $table WHERE $column LIKE '$prefix%' ORDER BY $column ASC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $existingIds = [];
        while ($row = $result->fetch_assoc()) {
            $existingIds[] = (int)substr($row[$column], strlen($prefix)); // Extract numeric part
        }

        // Find the first missing ID in the sequence
        $nextNumber = 1;
        while (in_array($nextNumber, $existingIds)) {
            $nextNumber++;
        }
        return $prefix . str_pad($nextNumber, 2, '0', STR_PAD_LEFT); // Format as Q01, Q02, etc.
    } else {
        return $prefix . "01"; // Default to Q01 if no records exist
    }
}

// Initialize message
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $question = trim($_POST['question']);
    $option1 = trim($_POST['option1']);
    $option2 = trim($_POST['option2']);
    $option3 = trim($_POST['option3']);
    $option4 = trim($_POST['option4']);
    $answer = trim($_POST['answer']);
    $quiz_id = "QZ02"; // Vocabulary quiz ID

    // Validate form data
    if (empty($question) || empty($option1) || empty($option2) || empty($option3) || empty($option4) || empty($answer)) {
        $message = "All fields are required!";
    } else {
        // Generate unique IDs
        $question_id = getNextId($conn, 'question', 'question_id', 'Q');
        $answer_id = getNextId($conn, 'answer', 'answer_id', 'A');

        // Insert question into QUESTION table
        $stmt_question = $conn->prepare("INSERT INTO question (question_id, question_text, `Option 1`, `Option 2`, `Option 3`, `Option 4`, quiz_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt_question->bind_param("sssssss", $question_id, $question, $option1, $option2, $option3, $option4, $quiz_id);

        if ($stmt_question->execute()) {
            // Insert correct answer into ANSWER table
            $is_correct = 1; // Boolean value for the correct answer
            $stmt_answer = $conn->prepare("INSERT INTO answer (answer_id, answer_text, is_correct, question_id) VALUES (?, ?, ?, ?)");
            $stmt_answer->bind_param("ssis", $answer_id, $answer, $is_correct, $question_id);

            if ($stmt_answer->execute()) {
                $message = "Question and answer added successfully!";
            } else {
                $message = "Error adding answer: " . $stmt_answer->error;
            }
            $stmt_answer->close();
        } else {
            $message = "Error adding question: " . $stmt_question->error;
        }
        $stmt_question->close();
    }

    // Redirect to avoid form resubmission
    $_SESSION['message'] = $message;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Retrieve the message from the session and clear it
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vocabulary Quiz Question</title>
    <link rel="stylesheet" href="../css/grammar-instructor-quizedit.css">
</head>
<body>
<header class="header">
    <div class="header-left">
        <a href="../instructor/instructor-homepage.php" class="logo">PhraseThiz</a>
        <div class="dropdown">
            <button class="dropdown-btn">Our Quiz ▼</button>
            <div class="dropdown-content">
                <a href="../instructor/grammar-instructor.php">Grammar</a>
                <a href="../instructor/vocabulary-instructor.php">Vocabulary</a>
            </div>
        </div>
    </div>
    <div class="header-right">
    <span>Welcome, <a href="../instructor/instructor-profile.php" class="profile-link"><?php echo htmlspecialchars($_SESSION['username']); ?></a>!</span>
    </div>
</header>
<main>
    <section class="form-container">
        <h1>Add Vocabulary Quiz Question</h1>
        <?php if (!empty($message)) : ?>
            <script>
                alert("<?php echo addslashes($message); ?>");
            </script>
        <?php endif; ?>
        <form method="POST">
            <label for="question">Question:</label>
            <input type="text" id="question" name="question" placeholder="Enter your question here" required>

            <label for="option1">Option 1:</label>
            <input type="text" id="option1" name="option1" placeholder="Enter option 1" required>

            <label for="option2">Option 2:</label>
            <input type="text" id="option2" name="option2" placeholder="Enter option 2" required>

            <label for="option3">Option 3:</label>
            <input type="text" id="option3" name="option3" placeholder="Enter option 3" required>

            <label for="option4">Option 4:</label>
            <input type="text" id="option4" name="option4" placeholder="Enter option 4" required>

            <label for="answer">Correct Answer:</label>
            <input type="text" id="answer" name="answer" placeholder="Enter the correct answer" required>

            <div class="form-buttons">
            <button type="submit" id="addQuestion">Add Question</button>
            <button type="reset">Reset</button>
        </div>
        </form>
        <button class="back-button" onclick="window.location.href='../instructor/vocabulary-instructor.php'">Back</button>
    </section>
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
                <li><a href="../instructor/grammar-instructor.php">Grammar</a></li>
                <li><a href="../instructor/vocabulary-instructor.php">Vocabulary</a></li>
            </ul>
        </div>
        <div class="footer-copyright">
            <p>Copyright &copy; 2024 PhraseThiz. All rights reserved</p>
        </div>
    </div>
</footer>
</body>
</html>
