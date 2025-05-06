document.getElementById("signup-form").addEventListener("submit", function (e) {
    e.preventDefault();
  
    let isValid = true;
  
    const name = document.getElementById("name");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const contact = document.getElementById("contact");
  
    // Clear previous messages
    document.getElementById("success-msg").innerText = "";
    document.querySelectorAll(".error").forEach(el => el.innerText = "");
  
    // Name validation
    if (name.value.trim() === "") {
      document.getElementById("name-error").innerText = "Name is required.";
      isValid = false;
    }
  
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value.trim())) {
      document.getElementById("email-error").innerText = "Enter a valid email.";
      isValid = false;
    }
  
    // Password validation
    if (password.value.length < 6) {
      document.getElementById("password-error").innerText = "Password must be at least 6 characters.";
      isValid = false;
    }
  
    // Contact number validation
    const contactRegex = /^\d{10}$/;
    if (!contactRegex.test(contact.value)) {
      document.getElementById("contact-error").innerText = "Enter a valid 10-digit number.";
      isValid = false;
    }
  
    if (isValid) {
      document.getElementById("success-msg").innerText = "Signup successful!";
      // Optionally, reset the form
      this.reset();
    }
  });
  