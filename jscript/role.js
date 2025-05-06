document.getElementById('role-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = document.getElementById('user-email').value;
    const role = document.getElementById('role-select').value;
  
    if (!email || !role) {
      alert('Please fill in all fields.');
      return;
    }
  
    alert(`Role "${role}" assigned to ${email}`);
    // Simulate saving role; in real app, send to server/database.
  });
  