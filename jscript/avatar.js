document.getElementById('avatar-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const file = document.getElementById('avatar').files[0];
  if (!file) return alert("Please choose an image");

  const reader = new FileReader();
  reader.onload = function() {
    document.getElementById('preview').src = reader.result;
    alert('Avatar uploaded!');
  };
  reader.readAsDataURL(file);
});
