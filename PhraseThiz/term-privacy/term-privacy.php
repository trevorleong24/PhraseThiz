<?php
session_start(); // Start the session

// Determine the user's homepage based on their role
function getUserHomepage() {
    if (!isset($_SESSION['role'])) {
        return "../main-homepage.html"; // Default to main homepage if no role
    }

    switch ($_SESSION['role']) {
        case 'instructor':
            return "../instructor/instructor-homepage.php";
        case 'admin':
            return "../admin/admin.php";
        case 'student':
            return "../student/student-homepage.php";
        default:
            return "../main-homepage.html"; // Default for unrecognized roles
    }
}

// Determine the user's profile link based on their role
function getUserProfileLink() {
    if (!isset($_SESSION['role'])) {
        return "#"; // Default to a placeholder if no role
    }

    switch ($_SESSION['role']) {
        case 'instructor':
            return "../instructor/instructor-profile.php";
        case 'admin':
            return null; // Admin username is not clickable
        case 'student':
            return "../student/student-profile.php";
        default:
            return "#"; // Default for unrecognized roles
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseThiz - Master English</title>
    <link rel="stylesheet" href="../css/term-privacy.css">
    <script>
        // JavaScript function to redirect based on user's homepage
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('phraseThizLink').addEventListener('click', function () {
                <?php if (isset($_SESSION['username'])): ?>
                    const userHomepage = "<?php echo getUserHomepage(); ?>";
                    window.location.href = userHomepage;
                <?php else: ?>
                    window.location.href = "../main-homepage.html"; // Default if not logged in
                <?php endif; ?>
            });
        });
    </script>
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
    <div class="tabs">
        <a href="#" id="terms-tab" class="active" onclick="showContent('terms')">Terms of Service</a>
        <a href="#" id="privacy-tab" onclick="showContent('privacy')">Privacy Policy</a>
    </div>

    <div id="terms-content">
        <h2>Terms of Service</h2>
        <p class="effective-dates">Effective Date: 2 April 2015. Last Updated: 14 April 2022</p>
        <div class="content">
            <ol>
                <li><strong>Acceptance of Terms</strong>: By accessing or using this website, you agree to be bound by these Terms of Service.</li>
                <li><strong>Eligibility</strong>: This platform is intended for users who are at least 18 years old. If you are under 18, you must obtain parental consent.</li>
                <li><strong>User Responsibilities</strong>: You are responsible for safeguarding your account credentials and all activities under your account.</li>
                <li><strong>Prohibited Conduct</strong>:
                    <ul>
                        <li>Uploading harmful, illegal, or infringing content.</li>
                        <li>Attempting to disrupt or breach website security.</li>
                        <li>Engaging in fraudulent activities.</li>
                    </ul>
                </li>
                <li><strong>Content Ownership</strong>: All content provided by this platform, including text, graphics, and software, is owned by us or our licensors.</li>
            </ol>
        </div>
    </div>

    <div id="privacy-content" class="hidden">
        <h2>Privacy Policy</h2>
        <p class="effective-dates">Effective Date: 2 April 2015. Last Updated: 14 April 2022</p>
        <div class="content">
            <ol>
                <li><strong>Information Collection</strong>: We collect personal information, including your name, email address, and payment details, for service provision.</li>
                <li><strong>Use of Information</strong>: Your data is used to personalize your experience, process transactions, and provide customer support.</li>
                <li><strong>Data Sharing</strong>: We do not sell or share your data with third parties, except as required by law or for essential services (e.g., payment processors).</li>
                <li><strong>Cookies</strong>: Our website uses cookies to enhance your browsing experience and analyze website traffic.</li>
                <li><strong>User Rights</strong>:
                    <ul>
                        <li>You may request access to, correction of, or deletion of your personal information.</li>
                        <li>You may opt-out of data collection for marketing purposes.</li>
                    </ul>
                </li>
                <li><strong>Data Security</strong>: We implement encryption and secure servers to protect your personal information.</li>
                <li><strong>Policy Updates</strong>: This Privacy Policy may be updated periodically to reflect changes in our practices.</li>
            </ol>
        </div>
    </div>
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
    function showContent(contentType) {
        // Hide both content sections
        document.getElementById('terms-content').classList.add('hidden');
        document.getElementById('privacy-content').classList.add('hidden');

        // Remove 'active' class from both tabs
        document.getElementById('terms-tab').classList.remove('active');
        document.getElementById('privacy-tab').classList.remove('active');

        // Show the selected content section
        if (contentType === 'terms') {
            document.getElementById('terms-content').classList.remove('hidden');
            document.getElementById('terms-tab').classList.add('active');
        } else if (contentType === 'privacy') {
            document.getElementById('privacy-content').classList.remove('hidden');
            document.getElementById('privacy-tab').classList.add('active');
        }
    }
</script>
</body>
</html>
