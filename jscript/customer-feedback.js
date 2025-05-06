document.getElementById("feedback-form").addEventListener("submit", function (e) {
    e.preventDefault();
  
    const rating = document.getElementById("rating").value;
    const comments = document.getElementById("comments").value;
  
    if (!rating || !comments.trim()) {
      alert("Please fill in all fields.");
      return;
    }
  
    // Simulate sending data to a backend (you can later replace this with fetch/AJAX)
    console.log("Rating:", rating);
    console.log("Comments:", comments);
  
    // Show response and hide form
    document.getElementById("feedback-form").classList.add("hidden");
    document.getElementById("response").classList.remove("hidden");
  });
  