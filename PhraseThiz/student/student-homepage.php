<?php
session_start(); // Start the session
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login/login.php"); // Redirect to login if not logged in as student
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseThiz - Master English</title>
    <link rel="stylesheet" href="../css/student-homepage.css">
</head>
<body>
<header>
    <div class="logo-nav">
        <a href="../student/student-homepage.php" class="logo">PhraseThiz</a>
        <div class="dropdown">
            <button class="dropdown-btn">Our Quiz ▼</button>
            <div class="dropdown-content">
                <a href="../student/grammar.php">Grammar</a>
                <a href="../student/vocabulary.php">Vocabulary</a>
            </div>
        </div>
    </div>
    <div class="auth-container">
        <?php if (isset($_SESSION['username'])): ?>
            <span>Welcome, <a href="../student/student-profile.php" class="profile-link"><?php echo htmlspecialchars($_SESSION['username']); ?></a>!</span>
        <?php else: ?>
            <a href="../login/login.php" class="login-btn">Login</a>
        <?php endif; ?>
    </div>
</header>

<section class="main-container">
    <h1>Welcome to PhraseThiz – Master English, One Phrase at a Time!</h1>
    <p>
        Unlock your potential and enhance your English proficiency with PhraseThiz. Whether you're on campus or on the move, 
        PhraseThiz offers an engaging experience to help you track progress and achieve your goals.
    </p>
    <a href="../student/grammar.php" class="start-btn">Start Now</a>
</section>

<script src="../js/script.js"></script>

<section class="about-page">
    <h2>ABOUT US</h2>
    <p class="about-description">
        PhraseThiz is a web-based interactive learning platform aimed at improving English proficiency among university students. Our mission is to provide an engaging, accessible, and personalized learning environment that allows students to enhance their language skills with ease and confidence. Whether you're aiming for better communication skills or preparing for exams, PhraseThiz offers the tools you need to succeed.
    </p>
    
    <div class="about-feature">
    <div class="feature-image" style="background-image: url('../image/homepage1.png');"></div>
    <div class="feature-text">
            <h3>Engaging Quizzes and Instant Feedback</h3>
            <p>PhraseThiz provides a collection of quizzes designed to reinforce your understanding of key English language concepts. Complete quizzes, receive immediate feedback, and continuously track your progress as you work towards mastering the language.</p>
        </div>
    </div>
    
    <div class="about-feature reverse">
        <div class="feature-text">
            <h3>Progress Monitoring and Personalized Learning</h3>
            <p>Track your achievements and pinpoint areas that need improvement with our detailed progress monitoring tools. Personalized recommendations help you stay on top of your learning goals, ensuring that every moment spent on PhraseThiz gets you closer to fluency.</p>
        </div>
        <div class="feature-image" style="background-image: url('../image/homepage2.png');"></div>
    </div>
    
    <div class="about-feature">
    <div class="feature-image" style="background-image: url('../image/homepage3.png');"></div>
        <div class="feature-text">
            <h3>For Instructors</h3>
            <p>Create, manage, and monitor quizzes effortlessly through our intuitive instructor tools. Design quizzes tailored to your students' needs, provide real-time feedback, and track class progress – all in one seamless platform.</p>
        </div>
    </div>
</section>

<section class="why-choose">
        <h2>Why Choose PhraseThiz?</h2>
        <div class="features-grid">
            <div class="feature-box">
                <h3>Interactive Learning</h3>
                <p>Engage with quizzes that challenge you and help reinforce your understanding with instant feedback.</p>
            </div>
            <div class="feature-box">
                <h3>Mobile-Optimized</h3>
                <p>Access PhraseThiz anytime, anywhere with a design that adapts seamlessly to smartphones and tablets.</p>
            </div>
            <div class="feature-box">
                <h3>Progress Tracking</h3>
                <p>Get detailed insights into your learning journey, helping you focus on the areas that need improvement.</p>
            </div>
            <div class="feature-box">
                <h3>Instructor-Friendly</h3>
                <p>Instructors can easily create quizzes, manage students, and track class performance through an intuitive interface.</p>
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

</body>
</html>
