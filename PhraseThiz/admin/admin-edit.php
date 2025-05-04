<?php
include("../conn/conn.php"); // Include the database connection
session_start(); // Start the session

// Check if user is logged in and has admin privileges
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login/login.php'); // Redirect to login page if not authorized
    exit();
}

// Initialize variables
$type = isset($_GET['type']) ? $_GET['type'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$data = [];

if ($type && $id) {
    // Fetch details based on type
    if ($type === 'student') {
        $query = "SELECT * FROM STUDENT WHERE student_id = '" . mysqli_real_escape_string($con, $id) . "'";
    } elseif ($type === 'instructor') {
        $query = "SELECT * FROM INSTRUCTOR WHERE instructor_id = '" . mysqli_real_escape_string($con, $id) . "'";
    } else {
        die("Invalid type specified.");
    }

    $result = mysqli_query($con, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
    } else {
        die("Record not found.");
    }
} else {
    die("Invalid request.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $dob = mysqli_real_escape_string($con, $_POST['dob']);

    if ($type === 'student') {
        $update_query = "UPDATE STUDENT SET username = '$username', email = '$email', password = '$password', dob = '$dob' WHERE student_id = '" . mysqli_real_escape_string($con, $id) . "'";
    } elseif ($type === 'instructor') {
        $institution = mysqli_real_escape_string($con, $_POST['institution']);
        $experience = mysqli_real_escape_string($con, $_POST['experience']);
        $certificate = mysqli_real_escape_string($con, $_POST['certificate']);
        $update_query = "UPDATE INSTRUCTOR SET username = '$username', email = '$email', password = '$password', dob = '$dob', institution = '$institution', experience = '$experience', certificate = '$certificate' WHERE instructor_id = '" . mysqli_real_escape_string($con, $id) . "'";
    } else {
        die("Invalid type specified.");
    }

    if (mysqli_query($con, $update_query)) {
        header("Location: ../admin/admin.php?success=" . ucfirst($type) . " updated successfully!");
        exit();
    } else {
        $error_message = "Error updating record: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseThiz - Edit Details</title>
    <link rel="stylesheet" href="../css/admin-edit.css">
</head>
<body>
<header>
    <div class="logo-nav">
        <a href="../admin/admin.php" class="logo">PhraseThiz Admin</a>
    </div>
    <div class="auth-container">
        <span>Welcome, Admin!</span>
        <a href="../logout/logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<div class="container">
    <h1>Update <?php echo ucfirst($type); ?> Details</h1>

    <?php if (isset($error_message)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="id"><?php echo ucfirst($type); ?> ID:</label>
            <input type="text" id="id" name="id" value="<?php echo htmlspecialchars($data[$type . '_id']); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($data['username']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($data['password']); ?>" required>
        </div>

        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($data['dob']); ?>" required>
        </div>

        <?php if ($type === 'instructor'): ?>
            <div class="form-group">
                <label for="institution">Institution:</label>
                <input type="text" id="institution" name="institution" value="<?php echo htmlspecialchars($data['institution']); ?>">
            </div>

            <div class="form-group">
                <label for="experience">Experience:</label>
                <input type="text" id="experience" name="experience" value="<?php echo htmlspecialchars($data['experience']); ?>">
            </div>

            <div class="form-group">
                <label for="certificate">Certificate:</label>
                <input type="text" id="certificate" name="certificate" value="<?php echo htmlspecialchars($data['certificate']); ?>">
            </div>
        <?php endif; ?>

        <div class="button-group">
            <button type="button" class="back-btn" onclick="window.location.href='../admin/admin.php'">Back</button>
            <button type="submit" class="update-btn">Update</button>
        </div>
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
        <div class="footer-copyright">
            <p>Copyright &copy; 2024 PhraseThiz. All rights reserved</p>
        </div>
    </div>
</footer>


</body>
</html>
