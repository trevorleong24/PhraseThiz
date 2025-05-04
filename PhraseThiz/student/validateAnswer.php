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

// Function to generate a unique student_quiz_id
function generateUniqueQuizID($con) {
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

// Handle quiz submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ensure necessary POST data is available
    $total_questions = intval($_POST['total_questions'] ?? 0);
    $correct_answers = intval($_POST['correct_answers'] ?? 0);
    $quiz_type = $_POST['quiz_type'] ?? 'grammar'; // Default to grammar if quiz_type is not provided

    // Calculate the score
    $score = ($total_questions > 0) ? ($correct_answers / $total_questions) * 100 : 0;

    // Generate a unique quiz ID
    $student_quiz_id = generateUniqueQuizID($con);

    // Prevent duplicate entries
    $checkQuery = "
        SELECT COUNT(*) AS count 
        FROM studentquiz 
        WHERE student_id = '$student_id' AND quiz_id = 'QZ02'
    ";
    $checkResult = mysqli_query($con, $checkQuery);
    $checkRow = mysqli_fetch_assoc($checkResult);

    if ($checkRow['count'] == 0) {
        // Insert the quiz data into the database
        $insertQuery = "
            INSERT INTO studentquiz (student_quiz_id, student_id, quiz_id, date_taken, score)
            VALUES ('$student_quiz_id', '$student_id', 'QZ02', CURDATE(), $score)
        ";
        if (!mysqli_query($con, $insertQuery)) {
            die("Error: " . mysqli_error($con));
        }
    }

    // Redirect based on quiz type
    mysqli_close($con);
    if ($quiz_type === 'vocabulary') {
        header("Location: ../student/vocabulary-result.php");
    } else {
        header("Location: ../student/grammar-result.php");
    }
    exit();
} else {
    echo "Invalid request method.";
    exit();
}
?>
