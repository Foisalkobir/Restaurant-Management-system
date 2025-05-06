document.getElementById('password-form').addEventListener('submit', function(e) {
    e.preventDefault();
  
    const current = document.getElementById('current').value;
    const newPass = document.getElementById('new').value;
    const confirm = document.getElementById('confirm').value;
  
    if (!current || !newPass || !confirm) {
      alert('Please fill in all fields.');
      return;
    }
  
    if (newPass !== confirm) {
      alert('New passwords do not match.');
      return;
    }
  
    if (newPass.length < 6) {
      alert('Password must be at least 6 characters.');
      return;
    }
  
    alert('Password updated successfully!');
  });
  