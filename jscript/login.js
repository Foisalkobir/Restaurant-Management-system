// Sample users with roles
const users = [
  { email: "admin@example.com", password: "admin123", role: "admin" },
  { email: "john@example.com", password: "user123", role: "user" }
];

document.getElementById("login-form").addEventListener("submit", function (e) {
  e.preventDefault();

  const email = document.getElementById("login-email").value.trim();
  const password = document.getElementById("login-password").value;

  const user = users.find(u => u.email === email && u.password === password);

  if (user) {
    localStorage.setItem("loggedInUser", user.email);
    localStorage.setItem("userRole", user.role);

    if (user.role === "admin") {
      window.location.href = "admin-dashboard.html";
    } else {
      window.location.href = "dashboard.html";
    }
  } else {
    alert("Invalid email or password.");
  }
});

document.getElementById('login-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const email = document.getElementById('login-email').value;
  const password = document.getElementById('login-password').value;

  // Simulate role check (replace with real check in database)
  if (email === "admin@example.com") {
    window.location.href = "adminprofile.html";
  } else {
    window.location.href = "profile.html";
  }
});
