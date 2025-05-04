// Get references to DOM elements
const loginTab = document.getElementById('login-tab');
const signupTab = document.getElementById('signup-tab');
const loginContainer = document.getElementById('login-container');
const signupContainer = document.getElementById('signup-container');
const instructorSignupContainer = document.getElementById('instructor-signup-container');
const studentSignupContainer = document.getElementById('student-signup-container');
const studentRole = document.getElementById('student-role');
const instructorRole = document.getElementById('instructor-role');
const selectRoleButton = document.getElementById('select-role-button');
const switchToSignup = document.getElementById('switch-to-signup');
const switchToLogin = document.getElementById('switch-to-login');

let selectedRole = null; // Track selected role

// Set the login form as the default landing page
window.addEventListener('DOMContentLoaded', () => {
    loginContainer.classList.add('active'); // Ensure login container is active
    signupContainer.classList.remove('active'); // Ensure signup container is hidden
    studentSignupContainer.classList.remove('active'); // Hide student signup
    instructorSignupContainer.classList.remove('active'); // Hide instructor signup
    loginTab.classList.add('active'); // Highlight login tab
    signupTab.classList.remove('active'); // Unhighlight signup tab
});

// Highlight Student Role
studentRole.addEventListener('click', () => {
    selectedRole = 'student'; // Set selected role to student
    studentRole.classList.add('active');
    instructorRole.classList.remove('active'); // Remove highlight from instructor
});

// Highlight Instructor Role
instructorRole.addEventListener('click', () => {
    selectedRole = 'instructor'; // Set selected role to instructor
    instructorRole.classList.add('active');
    studentRole.classList.remove('active'); // Remove highlight from student
});

// Handle Role Selection Button
selectRoleButton.addEventListener('click', () => {
    if (selectedRole === 'student') {
        signupContainer.classList.remove('active'); // Hide role selection container
        studentSignupContainer.classList.add('active'); // Show student signup container
    } else if (selectedRole === 'instructor') {
        signupContainer.classList.remove('active'); // Hide role selection container
        instructorSignupContainer.classList.add('active'); // Show instructor signup container
    } else {
        alert('Please select a role first!'); // Alert if no role is selected
    }
});

// Clear error messages dynamically
function clearErrorMessage() {
    const errorMessage = document.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.remove();
    }
}

// Switch to Sign-Up Tab
signupTab.addEventListener('click', () => {
    loginContainer.classList.remove('active'); // Hide login container
    signupContainer.classList.add('active'); // Show role selection container
    studentSignupContainer.classList.remove('active'); // Hide student signup container
    instructorSignupContainer.classList.remove('active'); // Hide instructor signup container
    signupTab.classList.add('active'); // Highlight signup tab
    loginTab.classList.remove('active'); // Remove highlight from login tab
    clearErrorMessage(); // Clear error message
});

// Switch to Login Tab
loginTab.addEventListener('click', () => {
    loginContainer.classList.add('active'); // Show login container
    signupContainer.classList.remove('active'); // Hide role selection container
    studentSignupContainer.classList.remove('active'); // Hide student signup container
    instructorSignupContainer.classList.remove('active'); // Hide instructor signup container
    loginTab.classList.add('active'); // Highlight login tab
    signupTab.classList.remove('active'); // Remove highlight from signup tab
    clearErrorMessage(); // Clear error message
});

// Footer: Switch to Sign-Up from Login
switchToSignup.addEventListener('click', () => {
    signupTab.click(); // Simulate clicking signup tab
    clearErrorMessage(); // Clear error message
});

// Footer: Switch to Login from Sign-Up
switchToLogin.addEventListener('click', () => {
    loginTab.click(); // Simulate clicking login tab
    clearErrorMessage(); // Clear error message
});
