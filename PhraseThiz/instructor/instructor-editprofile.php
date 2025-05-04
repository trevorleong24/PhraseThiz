<?php
include("../conn/conn.php");
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'instructor') {
    header("Location: ../login/login.php"); // Redirect to login if not logged in
    exit();
}

// Ensure instructor_id is set
if (!isset($_SESSION['instructor_id'])) {
    header("Location: ../login/login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get instructor ID from the session
    $instructor_id = $_SESSION['instructor_id'];

    // Get form inputs
    $username = htmlspecialchars(trim($_POST['option1']));
    $email = htmlspecialchars(trim($_POST['option2']));
    $dob = htmlspecialchars(trim($_POST['option3'])); // Ensure date format validation if needed
    $institution = htmlspecialchars(trim($_POST['option4']));
    $experience = htmlspecialchars(trim($_POST['option5']));
    $certificate = htmlspecialchars(trim($_POST['option6']));

    // Validate inputs
    if (!empty($username) && !empty($email) && !empty($dob) && !empty($institution) && !empty($experience) && !empty($certificate)) {
        // Update the database
        $query = "UPDATE INSTRUCTOR SET username = ?, email = ?, dob = ?, institution = ?, experience = ?, certificate = ? WHERE instructor_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("sssssss", $username, $email, $dob, $institution, $experience, $certificate, $instructor_id);

        if ($stmt->execute()) {
            // Success - redirect back to the profile page with success flag
            header("Location: instructor-profile.php?update=success");
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
    <link rel="stylesheet" href="../css/instructor-editprofile.css">
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
        <h1>Edit Profile</h1>
        <form id="UpdateForm" method="POST" action="">
            <label for="option1">Username:</label>
            <input type="text" id="option1" name="option1" placeholder="Enter your username" maxlength="50">

            <label for="option2">Email Address:</label>
            <input type="email" id="option2" name="option2" placeholder="Enter your email" maxlength="100">

            <label for="option3">Date of Birth:</label>
            <input type="date" id="option3" name="option3" placeholder="Enter your date of birth">

            <label for="option4">Institution:</label>
            <input type="text" id="option4" name="option4" placeholder="Enter your institution" maxlength="100">

            <label for="option5">Experience:</label>
            <input type="text" id="option5" name="option5" placeholder="Enter your experience" maxlength="255">

            <label for="option6">Certificate:</label>
            <input type="text" id="option6" name="option6" placeholder="Enter your certificates" maxlength="255">

            <div class="form-buttons">
                <button type="button" id="Back" class="back-btn" onclick="location.href='instructor-profile.php'">Back</button>
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
                <li><a href="../instructor/grammar-instructor.php">Grammar</a></li>
                <li><a href="../instructor/vocabulary-instructor.php">Vocabulary</a></li>
            </ul>
        </div>
        <div class="footer-copyright">
            <p>Copyright &copy; 2024 PhraseThiz. All rights reserved</p>
        </div>
    </div>
</footer>
<script src="../js/instructorprofile.js"></script>
</body>
</html>
