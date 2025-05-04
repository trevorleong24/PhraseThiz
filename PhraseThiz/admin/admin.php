<?php
include("../conn/conn.php"); // Include the database connection
session_start(); // Start the session

// Check if user is logged in and has admin privileges
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login/login.php'); // Redirect to login page if not authorized
    exit();
}

// Handle delete requests
if (isset($_GET['action']) && isset($_GET['type']) && $_GET['action'] === 'delete') {
    $type = $_GET['type'];
    $id = $_GET['id'];
    if ($type === 'student') {
        $delete_query = "DELETE FROM STUDENT WHERE student_id = '$id'";
    } elseif ($type === 'instructor') {
        $delete_query = "DELETE FROM INSTRUCTOR WHERE instructor_id = '$id'";
    }
    if (isset($delete_query)) {
        if (mysqli_query($con, $delete_query)) {
            $_SESSION['message'] = ucfirst($type) . " deleted successfully!";
        } else {
            $_SESSION['message'] = "Failed to delete " . ucfirst($type) . ": " . mysqli_error($con);
        }
        header('Location: ../admin/admin.php'); // Redirect to refresh the page
        exit();
    }
}

// Fetch students from the database
$students_query = "SELECT * FROM STUDENT";
$students_result = mysqli_query($con, $students_query);
if (!$students_result) {
    die("Error fetching students: " . mysqli_error($con));
}

// Fetch instructors from the database
$instructors_query = "SELECT * FROM INSTRUCTOR";
$instructors_result = mysqli_query($con, $instructors_query);
if (!$instructors_result) {
    die("Error fetching instructors: " . mysqli_error($con));
}

// Retrieve and clear message
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message after displaying
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseThiz - Admin Settings</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<header>
    <div class="logo-nav">
        <a href="../admin/admin.php" class="logo">PhraseThiz Admin</a>
    </div>
    <div class="auth-container">
        <?php if (isset($_SESSION['username'])): ?>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="../logout/logout.php" class="logout-btn">Logout</a>
        <?php else: ?>
            <a href="../login/login.php" class="login-btn">Login</a>
        <?php endif; ?>
    </div>
</header>

<section class="main-container">
    <h1>Settings</h1>

    <?php if (!empty($message)): ?>
        <div class="success-message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="search-container" style="font-family: 'Poppins', sans-serif;">
        <input type="text" id="searchInput" class="search-bar" placeholder="Search by name or details...">
        <button class="search-button">Search</button>
    </div>

    <div class="container">
        <h2>Student Details</h2>
        <div class="card-grid">
            <?php 
            if (mysqli_num_rows($students_result) > 0) {
                while ($student = mysqli_fetch_assoc($students_result)) {
            ?>
                <div class="card">
                    <p>Student ID: <?php echo htmlspecialchars($student['student_id']); ?></p>
                    <p>Username: <?php echo htmlspecialchars($student['username']); ?></p>
                    <p>Email: <?php echo htmlspecialchars($student['email']); ?></p>
                    <p>Date of Birth: <?php echo htmlspecialchars($student['dob']); ?></p>
                    <button class="edit-btn" onclick="editStudent('<?php echo $student['student_id']; ?>')">Edit</button>
                    <button class="delete-btn" onclick="deleteStudent('<?php echo $student['student_id']; ?>')">Delete</button>
                </div>
            <?php 
                }
            } else {
                echo "<p>No students found.</p>";
            }
            ?>
        </div>
    </div>

    <div class="container">
        <h2>Instructor Details</h2>
        <div class="card-grid">
            <?php 
            if (mysqli_num_rows($instructors_result) > 0) {
                while ($instructor = mysqli_fetch_assoc($instructors_result)) {
            ?>
                <div class="card">
                    <p>Instructor ID: <?php echo htmlspecialchars($instructor['instructor_id']); ?></p>
                    <p>Username: <?php echo htmlspecialchars($instructor['username']); ?></p>
                    <p>Email: <?php echo htmlspecialchars($instructor['email']); ?></p>
                    <p>Institution: <?php echo htmlspecialchars($instructor['institution']); ?></p>
                    <p>Experience: <?php echo htmlspecialchars($instructor['experience']); ?></p>
                    <button class="edit-btn" onclick="editInstructor('<?php echo $instructor['instructor_id']; ?>')">Edit</button>
                    <button class="delete-btn" onclick="deleteInstructor('<?php echo $instructor['instructor_id']; ?>')">Delete</button>
                 </div>
            <?php 
                }
            } else {
                echo "<p>No instructors found.</p>";
            }
            ?>
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
        <div class="footer-copyright">
            <p>Copyright &copy; 2024 PhraseThiz. All rights reserved</p>
        </div>
    </div>
</footer>

<script>
function editStudent(id) {
    window.location.href = '../admin/admin-edit.php?type=student&id=' + id;
}

function deleteStudent(id) {
    if (confirm('Are you sure you want to delete this student?')) {
        window.location.href = '../admin/admin-delete.php?type=student&id=' + id;
    }
}

function editInstructor(id) {
    window.location.href = '../admin/admin-edit.php?type=instructor&id=' + id;
}

function deleteInstructor(id) {
    if (confirm('Are you sure you want to delete this instructor?')) {
        window.location.href = '../admin/admin-delete.php?type=instructor&id=' + id;
    }
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchValue = this.value.toLowerCase();
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        const cardText = card.innerText.toLowerCase();
        card.style.display = cardText.includes(searchValue) ? '' : 'none';
    });
});
</script>
</body>
</html>
