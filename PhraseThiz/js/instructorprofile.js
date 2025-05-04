function updateProfile() {
  window.location.href = "instructor-editprofile.php";
}

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('UpdateForm');

  // Handle form submission
  form.addEventListener('submit', (event) => {
      const username = document.getElementById('option1').value.trim();
      const email = document.getElementById('option2').value.trim();
      const dob = document.getElementById('option3').value.trim();
      const institution = document.getElementById('option4').value.trim();
      const experience = document.getElementById('option5').value.trim();
      const certificate = document.getElementById('option6').value.trim();

      // Validate the inputs
      if (!username || !email || !dob || !institution || !experience || !certificate) {
          alert('Please fill in all fields.');
          event.preventDefault(); // Prevent form submission
          return;
      }

      // Validate email format
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(email)) {
          alert('Please enter a valid email address.');
          event.preventDefault(); // Prevent form submission
          return;
      }

      // Show confirmation dialog
      const isConfirmed = confirm('Are you sure you want to update your profile?');
      if (!isConfirmed) {
          event.preventDefault(); // Prevent form submission
          return;
      }
  });
});
