<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseThiz - Master English</title>
    <link rel="stylesheet" href="../css/about-us.css">
</head>
<body>
<header>
    <div class="logo-nav">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="#" class="logo" id="phraseThizLink">PhraseThiz Admin</a>
        <?php else: ?>
            <a href="#" class="logo" id="phraseThizLink">PhraseThiz</a>
        <?php endif; ?>

        <?php if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'): ?>
    <div class="dropdown">
        <button class="dropdown-btn">Our Quiz ▼</button>
        <div class="dropdown-content">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'instructor'): ?>
                <a href="../instructor/grammar-instructor.php">Grammar</a>
                <a href="../instructor/vocabulary-instructor.php">Vocabulary</a>
            <?php else: ?>
                <a href="../student/grammar.php">Grammar</a>
                <a href="../student/vocabulary.php">Vocabulary</a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

    </div>
    <div class="auth-container">
        <?php if (isset($_SESSION['username'])): ?>
            <?php 
                $username = htmlspecialchars($_SESSION['username']);
                $role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

                if ($role === 'student') {
                    echo '<span>Welcome, <a href="../student/student-profile.php" class="profile-link">' . $username . '</a>!</span>';
                } elseif ($role === 'instructor') {
                    echo '<span>Welcome, <a href="../instructor/instructor-profile.php" class="profile-link">' . $username . '</a>!</span>';
                } elseif ($role === 'admin') {
                    echo '<span>Welcome, ' . $username . '!</span> <a href="../logout/logout.php">Logout</a>';
                } else {
                    echo '<span>Welcome, ' . $username . '!</span>';
                }
            ?>
        <?php else: ?>
            <a href="../login/login.php" class="login-btn">Login</a>
        <?php endif; ?>
    </div>
</header>


<div class="container">
    <h1>About Us</h1>
    <p>
        PhraseThiz is a web-based interactive learning platform aimed at improving English proficiency among university students. 
        Our mission is to provide an engaging, accessible, and personalized learning environment that allows students to enhance 
        their language skills with ease and confidence. Whether you're aiming for better communication skills or preparing for exams, 
        PhraseThiz offers the tools you need to succeed.
    </p>

    <h2 class="section-title">Vision</h2>
    <p>
        To be the leading platform that empowers university students to master English through interactive, accessible, and personalized 
        learning experiences, fostering global communication and lifelong success.
    </p>

    <h2 class="section-title">Mission</h2>
    <ol>
        <li>To improve English proficiency among university students by offering an engaging and interactive learning platform.</li>
        <li>To create a supportive and accessible environment that adapts to the unique learning needs and goals of each student.</li>
        <li>To enhance students’ confidence in English communication, whether for academic, personal, or professional purposes.</li>
    </ol>

    <h2 class="section-title">Goals</h2>
    <ol>
        <li>Provide a wide range of interactive tools, exercises, and resources designed to improve reading, writing, listening, and speaking skills.</li>
        <li>Implement adaptive learning paths that cater to individual skill levels and learning progress.</li>
        <li>Partner with universities and educational institutions to integrate PhraseThiz into their curriculum and extracurricular activities.</li>
        <li>Continuously update the platform with new content and features to maintain relevance and engagement.</li>
        <li>Offer performance tracking and feedback mechanisms to help students monitor their progress and achieve their learning goals efficiently.</li>
    </ol>
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
        <?php if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'): ?>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'instructor'): ?>
                        <li><a href="../instructor/grammar-instructor.php">Grammar</a></li>
                        <li><a href="../instructor/vocabulary-instructor.php">Vocabulary</a></li>
                    <?php else: ?>
                        <li><a href="../student/grammar.php">Grammar</a></li>
                        <li><a href="../student/vocabulary.php">Vocabulary</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>
        <div class="footer-copyright">
            <p>Copyright &copy; 2024 PhraseThiz. All rights reserved</p>
        </div>
    </div>
</footer>

<script>
    document.getElementById('phraseThizLink').addEventListener('click', function(event) {
        event.preventDefault();
        <?php if (isset($_SESSION['role'])): ?>
            <?php if ($_SESSION['role'] === 'student'): ?>
                window.location.href = '../student/student-homepage.php';
            <?php elseif ($_SESSION['role'] === 'instructor'): ?>
                window.location.href = '../instructor/instructor-homepage.php';
            <?php elseif ($_SESSION['role'] === 'admin'): ?>
                window.location.href = '../admin/admin.php';
            <?php else: ?>
                window.location.href = '../main-homepage.html';
            <?php endif; ?>
        <?php else: ?>
            window.location.href = '../main-homepage.html';
        <?php endif; ?>
    });
</script>

</body>
</html>
