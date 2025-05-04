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

// Fetch the latest score for each student for the vocabulary quiz (quiz_id = QZ02)
$quiz_id = "QZ02"; // Vocabulary quiz ID
$sql = "
    SELECT 
        sq.student_quiz_id,
        s.username,
        sq.score,
        sq.date_taken
    FROM 
        studentquiz sq
    INNER JOIN 
        student s ON sq.student_id = s.student_id
    WHERE 
        sq.student_quiz_id IN (
            SELECT 
                MAX(subsq.student_quiz_id)
            FROM 
                studentquiz subsq
            WHERE 
                subsq.quiz_id = ?
            GROUP BY 
                subsq.student_id
        )
    ORDER BY 
        sq.date_taken DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $quiz_id);
$stmt->execute();
$result = $stmt->get_result();
$results = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vocabulary Quiz Result</title>
    <link rel="stylesheet" href="../css/vocabulary-instructor-result.css">
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

    <main class="main">
        <h1 class="title">Vocabulary Quiz Result</h1>
        <div class="box-container">
            <div class="results-container">
                <?php if (count($results) > 0): ?>
                    <?php foreach ($results as $row): ?>
                        <div class="result-card">
                            <p>Result ID: <?php echo htmlspecialchars($row['student_quiz_id']); ?></p>
                            <p>Name: <?php echo htmlspecialchars($row['username']); ?></p>
                            <p>Score: <?php echo htmlspecialchars($row['score']); ?></p>
                            <p>Date Taken: <?php echo htmlspecialchars($row['date_taken']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No results available for the vocabulary quiz.</p>
                <?php endif; ?>
            </div>
        </div>
        <button class="back-button" onclick="history.back()">Back</button>
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
    <script src="../js/vocabulary.js"></script>
</body>
</html>
