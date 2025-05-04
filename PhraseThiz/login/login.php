<?php
include("../conn/conn.php");

session_start(); // Start the session

$successMessage = isset($_SESSION['successMessage']) ? $_SESSION['successMessage'] : "";
$errorMessage = isset($_SESSION['errorMessage']) ? $_SESSION['errorMessage'] : "";

// Clear session messages
unset($_SESSION['successMessage'], $_SESSION['errorMessage']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Login logic
        $emailOrUsername = mysqli_real_escape_string($con, $_POST['email']);
        $password = $_POST['password'];

        // Check in students table
        $studentQuery = "SELECT * FROM student WHERE (email='$emailOrUsername' OR username='$emailOrUsername')";
        $studentResult = mysqli_query($con, $studentQuery);
        $student = mysqli_fetch_assoc($studentResult);

        if ($student && $password === $student['password']) { // Plain-text password check
            // Set session for student
            $_SESSION['username'] = $student['username'];
            $_SESSION['role'] = 'student';
            $_SESSION['student_id'] = $student['student_id']; // Store student_id in session

            // Redirect to student homepage
            header("Location: ../student/student-homepage.php");
            exit();
        }

        // Check in instructors table
        $instructorQuery = "SELECT * FROM instructor WHERE (email='$emailOrUsername' OR username='$emailOrUsername')";
        $instructorResult = mysqli_query($con, $instructorQuery);
        $instructor = mysqli_fetch_assoc($instructorResult);

        if ($instructor && $password === $instructor['password']) { // Plain-text password check
            // Set session for instructor
            $_SESSION['username'] = $instructor['username'];
            $_SESSION['role'] = 'instructor';
            $_SESSION['instructor_id'] = $instructor['instructor_id'];

            // Redirect to instructor homepage
            header("Location: ../instructor/instructor-homepage.php");
            exit();
        }

        // Check in administrator table
        $adminQuery = "SELECT * FROM administrator WHERE (email='$emailOrUsername' OR username='$emailOrUsername')";
        $adminResult = mysqli_query($con, $adminQuery);
        $admin = mysqli_fetch_assoc($adminResult);

        if ($admin && $password === $admin['password']) { // Plain-text password check
            // Set session for administrator
            $_SESSION['username'] = $admin['username'];
            $_SESSION['role'] = 'admin';
            $_SESSION['admin_id'] = $admin['admin_id']; // Store admin_id in session

            // Redirect to admin homepage
            header("Location: ../admin/admin.php");
            exit();
        }

        // If no match found
        $_SESSION['errorMessage'] = "Invalid email/username or password.";
        header("Location: ../login/login.php"); // Redirect to avoid resubmission
        exit();
    } else {
        // Signup logic
        $role = $_POST['role']; // Either 'student' or 'instructor'

        // Check if the terms checkbox is accepted
        if (!isset($_POST['terms'])) {
            $_SESSION['errorMessage'] = "You must accept the Terms of Service to sign up.";
            header("Location: ../login/login.php");
            exit();
        } else {
            // Common fields
            $username = mysqli_real_escape_string($con, $_POST['username']);
            $password = mysqli_real_escape_string($con, $_POST['password']);
            $email = mysqli_real_escape_string($con, $_POST['email']);
            $dob = mysqli_real_escape_string($con, $_POST['dob']);

            if ($role === 'student') {
                // Fetch the latest student_id
                $result = mysqli_query($con, "SELECT student_id FROM student ORDER BY student_id DESC LIMIT 1");
                $lastId = mysqli_fetch_assoc($result)['student_id'];

                // Generate the next student_id
                if ($lastId) {
                    $num = (int)substr($lastId, 1); // Extract the numeric part of the last student_id
                    $newNum = str_pad($num + 1, 2, '0', STR_PAD_LEFT); // Increment and pad with leading zeroes
                    $studentId = "S" . $newNum;
                } else {
                    $studentId = "S01"; // Default ID if no records exist
                }

                $adminId = "A01"; // Example admin ID, replace with your logic

                $sql = "INSERT INTO student (student_id, username, password, email, dob, admin_id) 
                        VALUES ('$studentId', '$username', '$password', '$email', '$dob', '$adminId')";

                if (mysqli_query($con, $sql)) {
                    $_SESSION['successMessage'] = "Signup successful! Please log in.";
                    header("Location: ../login/login.php"); // Redirect to login page
                    exit();
                } else {
                    $_SESSION['errorMessage'] = "Error: " . mysqli_error($con);
                    header("Location: ../login/login.php");
                    exit();
                }

            } elseif ($role === 'instructor') {
                // Fetch the latest instructor_id
                $result = mysqli_query($con, "SELECT instructor_id FROM instructor ORDER BY instructor_id DESC LIMIT 1");
                $lastId = mysqli_fetch_assoc($result)['instructor_id'];

                // Generate the next instructor_id
                if ($lastId) {
                    $num = (int)substr($lastId, 1); // Extract the numeric part of the last instructor_id
                    $newNum = str_pad($num + 1, 2, '0', STR_PAD_LEFT); // Increment and pad with leading zeroes
                    $instructorId = "I" . $newNum;
                } else {
                    $instructorId = "I01"; // Default ID if no records exist
                }

                $institution = mysqli_real_escape_string($con, $_POST['institution']);
                $experience = mysqli_real_escape_string($con, $_POST['experience']);
                $certificate = mysqli_real_escape_string($con, $_POST['certificate']);
                $adminId = "A01"; // Example admin ID, replace with your logic

                $sql = "INSERT INTO instructor (instructor_id, username, password, email, dob, institution, experience, certificate, admin_id) 
                        VALUES ('$instructorId', '$username', '$password', '$email', '$dob', '$institution', '$experience', '$certificate', '$adminId')";

                if (mysqli_query($con, $sql)) {
                    $_SESSION['successMessage'] = "Signup successful! Please log in.";
                    header("Location: ../login/login.php"); // Redirect to login page
                    exit();
                } else {
                    $_SESSION['errorMessage'] = "Error: " . mysqli_error($con);
                    header("Location: ../login/login.php");
                    exit();
                }
            } else {
                $_SESSION['errorMessage'] = "Invalid role selected.";
                header("Location: ../login/login.php");
                exit();
            }
        }
    }
}

mysqli_close($con);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseThiz</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <a href="../homepage/main-homepage.html" class="close-icon" aria-label="Close">
        &#10006;
    </a>
    <div class="left-section">
        <h1>The best way to study. <br> Sign up for free.</h1>
        <div class="brand-name">PhraseThiz</div>
    </div>
    <div class="right-section">
        <div class="tab-container">
            <a href="#" id="signup-tab">Sign Up</a>
            <a href="#" id="login-tab" class="active">Log In</a>
        </div>

        <!-- Login Section -->
        <div class="form-container active" id="login-container">
            <form action="login.php" method="POST">
                <label for="email">Email or Username</label>
                <input type="text" name="email" id="email" placeholder="Enter your email address or username" required>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
                <button type="submit" name="login">Log In</button>
            </form>
            <div class="form-footer">
                <p>New to PhraseThiz? <a href="#" id="switch-to-signup">Create an account</a></p>
                <!-- Error and Success Messages -->
                <?php if (!empty($errorMessage)): ?>
                    <p style="color: red; margin-top: 10px; font-weight: 900;"><?php echo $errorMessage; ?></p>
                <?php elseif (!empty($successMessage)): ?>
                    <p style="color: green; margin-top: 10px; font-weight: 900;"><?php echo $successMessage; ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Role Selection Section -->
        <div class="signup-container" id="signup-container">
            <div class="role-selection">
                <div class="role" id="student-role">
                    <img src="../image/student.jpg" alt="Student Icon">
                    <p>Student</p>
                </div>
                <div class="role" id="instructor-role">
                    <img src="../image/instructor.jpg" alt="Instructor Icon">
                    <p>Instructor</p>
                </div>
            </div>
            <button id="select-role-button">Select Role</button>
        </div>

        <!-- Student Sign-Up Section -->
        <form action="login.php" method="POST" class="student-signup" id="student-signup-container">
            <input type="hidden" name="role" value="student">
            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label class="checkbox-container">
                <input type="checkbox" name="terms" required>
                I accept PhraseQuiz's <a href="../term-privacy/term-privacy.php">Terms of Service</a> and <a href="../term-privacy/term-privacy.php">Privacy Policy</a>
            </label>

            <button type="submit">Sign Up</button>

            <div class="form-footer">
                <p>Already have an account? <a href="#" id="switch-to-login">Log in</a></p>
            </div>
        </form>

        <!-- Instructor Sign-Up Section -->
        <form action="login.php" method="POST" class="instructor-signup" id="instructor-signup-container">
            <input type="hidden" name="role" value="instructor">
            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob" required>
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            
            <label for="institution">Institution</label>
            <input type="text" id="institution" name="institution" required>
            
            <label for="experience">Experience</label>
            <input type="text" id="experience" name="experience" required>
            
            <label for="certificate">Certificate</label>
            <input type="text" id="certificate" name="certificate" required>
            
            <label class="checkbox-container">
                <input type="checkbox" name="terms" required>
                I accept PhraseQuiz's <a href="../term-privacy/term-privacy.php">Terms of Service</a> and <a href="../term-privacy/term-privacy.php">Privacy Policy</a>
            </label>
            
            <button type="submit">Sign Up</button>
            
            <div class="form-footer">
                <p>Already have an account? <a href="#" id="switch-to-login">Log in</a></p>
            </div>
        </form>
    </div>

    <script src="../js/login.js"></script>
</body>
</html>

