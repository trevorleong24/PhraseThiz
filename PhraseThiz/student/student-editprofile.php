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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get student ID from the session
    $student_id = $_SESSION['student_id'];

    // Get form inputs
    $username = htmlspecialchars(trim($_POST['option1']));
    $email = htmlspecialchars(trim($_POST['option2']));
    $dob = htmlspecialchars(trim($_POST['option3'])); // Ensure date format validation if needed

    // Validate inputs
    if (!empty($username) && !empty($email) && !empty($dob)) {
        // Update the database
        $query = "UPDATE STUDENT SET username = ?, email = ?, dob = ? WHERE student_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssss", $username, $email, $dob, $student_id);

        if ($stmt->execute()) {
            // Success - redirect back to the profile page with success flag
            header("Location: student-profile.php?update=success");
            exit();
        } else {
            // Error
            echo "Error updating profile: " . $con->error;
        }
    } else {
        // Handle empty fields
        echo "Please fill in all the fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../css/student-edit.css">
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
        <span>Welcome, <a href="student-profile.php" class="profile-link"><?php echo htmlspecialchars($_SESSION['username']); ?></a>!</span>
    </div>
</header>
<main>
    <section class="form-container">
        <h1>Edit Profile</h1>
        <form id="UpdateForm" method="POST" action="">
            <label for="option1">Username:</label>
            <input type="text" id="option1" name="option1" placeholder="Enter your username" maxlength="50">

            <label for="option2">Email Address:</label>
            <input type="email" id="option2" name="option2" placeholder="Enter your email" maxlength="100">

            <label for="option3">Date of Birth:</label>
            <input type="date" id="option3" name="option3" placeholder="Enter your date of birth">

            <div class="form-buttons">
                <button type="button" id="Back" class="back-btn" onclick="location.href='student-profile.php'">Back</button>
                <button type="submit" id="Update" class="update-btn">Update</button>
            </div>
        </form>
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
                <li><a href="../student/grammar.php">Grammar</a></li>
                <li><a href="../student/vocabulary.php">Vocabulary</a></li>
            </ul>
        </div>
        <div class="footer-copyright">
            <p>Copyright &copy; 2024 PhraseThiz. All rights reserved</p>
        </div>
    </div>
</footer>
<script src="../js/studentprofile.js"></script>
</body>
</html>
