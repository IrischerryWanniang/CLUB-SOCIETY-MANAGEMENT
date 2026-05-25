// Dummy store for registered user
let registeredUser = {};

function register() {
  const username = document.getElementById('regUsername').value;
  const password = document.getElementById('regPassword').value;

  if (username && password) {
    registeredUser.username = username;
    registeredUser.password = password;
    alert("Registered successfully! Please log in.");
    showLogin();
  } else {
    alert("Please fill in both fields.");
  }
}

function login() {
  const username = document.getElementById('loginUsername').value;
  const password = document.getElementById('loginPassword').value;

  if (username === registeredUser.username && password === registeredUser.password) {
    alert("Login successful!");
  } else {
    alert("Invalid credentials.");
  }
}

function showLogin() {
  document.getElementById('registerForm').style.display = 'none';
  document.getElementById('loginForm').style.display = 'block';
}

function showRegister() {
  document.getElementById('loginForm').style.display = 'none';
  document.getElementById('registerForm').style.display = 'block';
}
