function validateform() {
    const name = document.getElementById('signup-name').value.trim();
    const age = parseInt(document.getElementById('signup-age').value);
    const contact = document.getElementById('signup-contact').value.trim();
    const email = document.getElementById('signup-email').value.trim();
    const password = document.getElementById('signup-password').value;
    const confirmPassword = document.getElementById('signup-confirm-password').value;
  
    if (name === '') {
      alert('Full Name is required.');
      return false;
    }
  
    if (isNaN(age) || age < 13 || age > 120) {
      alert('Please enter a valid age between 13 and 120.');
      return false;
    }
  
    const contactRegex = /^\d{10}$/;
    if (!contactRegex.test(contact)) {
      alert('Contact Number must be a valid 10-digit number.');
      return false;
    }
  
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      alert('Invalid email format.');
      return false;
    }
  
    if (password.length < 6) {
      alert('Password must be at least 6 characters long.');
      return false;
    }
  
    if (password !== confirmPassword) {
      alert('Passwords do not match.');
      return false;
    }
  
    // If all validations pass
    return true;
  }
  