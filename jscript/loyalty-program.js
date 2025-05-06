document.getElementById("loyalty-form").addEventListener("submit", function (e) {
    e.preventDefault();
  
    const name = document.getElementById("customer-name").value.trim();
    const visits = parseInt(document.getElementById("visits").value);
    const spent = parseFloat(document.getElementById("total-spent").value);
  
    if (!name || isNaN(visits) || isNaN(spent) || visits <= 0 || spent <= 0) {
      alert("Please enter valid customer details.");
      return;
    }
  
    // Calculate points: 1 point per 100 BDT
    const points = Math.floor(spent / 100);
  
    // Determine tier and reward
    let tier = "Bronze";
    let reward = "5% Discount";
  
    if (points >= 30) {
      tier = "Gold";
      reward = "Free Dessert + 15% Discount";
    } else if (points >= 15) {
      tier = "Silver";
      reward = "10% Discount";
    }
  
    // Show result
    document.getElementById("res-name").innerText = name;
    document.getElementById("res-visits").innerText = visits;
    document.getElementById("res-spent").innerText = "৳" + spent.toFixed(2);
    document.getElementById("res-points").innerText = points;
    document.getElementById("res-tier").innerText = tier;
    document.getElementById("res-reward").innerText = reward;
  
    document.getElementById("loyalty-result").classList.remove("hidden");
  });
  