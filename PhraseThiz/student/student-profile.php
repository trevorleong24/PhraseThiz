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

// Get student ID from the session
$student_id = $_SESSION['student_id'];

// Fetch student details from the database
$query = "SELECT username, email, dob FROM STUDENT WHERE student_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
    $username = htmlspecialchars($student['username']);
    $email = htmlspecialchars($student['email']);
    $dob = htmlspecialchars($student['dob']);
} else {
    header("Location: ../login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="../css/student-profile.css">
</head>
<body>
    <div class="page-container">
        <!-- Header Section -->
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
            <div class="user-profile">
                <span>Welcome, <a href="../student/student-profile.php" class="profile-link"><?php echo $username; ?></a>!</span>
            </div>
        </header>

        <!-- Main Content Section -->
        <main class="main-content">
            <section class="profile-section">
                <div class="profile-container">
                    <aside class="profile-sidebar">
                        <div class="avatar-placeholder">
                            <img src="../image/student.jpg" alt="Student Icon">
                        </div>
                        <div class="avatar-profile-text">
                            <h3><?php echo $username; ?></h3>
                            <p>Student</p>
                        </div>
                    </aside>
                    <section class="profile-content">
                        <h2>Your Profile</h2>
                        <div class="details-box">
                            <h3>Your Details</h3>
                            <p><strong>Username:</strong> <?php echo $username; ?></p>
                            <p><strong>Email:</strong> <?php echo $email; ?></p>
                            <p><strong>Date of Birth:</strong> <?php echo $dob; ?></p>
                            <button class="update-button" onclick="updateProfile()">Update</button>
                            <button class="logout-button" onclick="window.location.href='../logout/logout.php'">Logout</button>
                        </div>
                    </section>
                </div>
            </section>
        </main>

        <!-- Footer Section -->
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
                <div class="footer-section">
                    <p>Copyright &copy; 2024 PhraseThiz. All rights reserved</p>
                </div>
            </div>
        </footer>
    </div>
<?php if (isset($_GET['update']) && $_GET['update'] === 'success') : ?>
  <script>
      alert('Profile updated successfully.');
      // Remove the update=success parameter from the URL
      const url = new URL(window.location.href);
      url.searchParams.delete('update');
      window.history.replaceState(null, null, url.toString());
  </script>
<?php endif; ?>
<script src="../js/studentprofile.js" defer></script>
</body>
</html>

