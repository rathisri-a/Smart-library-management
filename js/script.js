const loginForm = document.getElementById('login-form');
const registerForm = document.getElementById('register-form');
const adminBtn = document.getElementById('admin-btn');
const studentBtn = document.getElementById('student-btn');

function showLogin() {
    loginForm.classList.remove('hidden');
    registerForm.classList.add('hidden');
    adminBtn.classList.add('active');
    studentBtn.classList.remove('active');
}

function showRegister() {
    loginForm.classList.add('hidden');
    registerForm.classList.remove('hidden');
    studentBtn.classList.add('active');
    adminBtn.classList.remove('active');
}

function registerStudent(event) {
    event.preventDefault(); // Prevent the form from submitting normally
    const emailInput = registerForm.querySelector('input[type="email"]');
    const email = emailInput.value;

    // Additional logic for email validation can be added here

    // Redirect to the library page
    window.location.href = 'book search'; // Update with the correct path to your library page
}

// Initialize by showing the admin login form
showLogin();
