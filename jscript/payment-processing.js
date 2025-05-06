function splitBill() {
    const seatCount = parseInt(document.getElementById('seat-count').value);
    const totalAmount = parseFloat(document.getElementById('total-amount').value);
    const splitSection = document.getElementById('split-section');
  
    if (!seatCount || !totalAmount || seatCount <= 0 || totalAmount <= 0) {
      alert("Please enter valid seat count and total amount.");
      return;
    }
  
    splitSection.innerHTML = "<h3>Split Payment</h3>";
    const perSeatAmount = (totalAmount / seatCount).toFixed(2);
  
    for (let i = 1; i <= seatCount; i++) {
      const block = `
        <div>
          <p><strong>Seat ${i}</strong> - ৳${perSeatAmount}</p>
          <label>Payment Method:</label>
          <select required>
            <option value="cash">Cash</option>
            <option value="card">Card</option>
            <option value="bkash">bKash</option>
            <option value="nagad">Nagad</option>
          </select>
        </div>
        <hr />
      `;
      splitSection.innerHTML += block;
    }
  
    document.querySelector('.submit-btn').classList.remove('hidden');
  }
  
  document.getElementById("payment-form").addEventListener("submit", function (e) {
    e.preventDefault();
    const seatCount = parseInt(document.getElementById('seat-count').value);
    const totalAmount = parseFloat(document.getElementById('total-amount').value);
    const receiptOutput = document.getElementById("receipt-output");
    const receiptDetails = document.getElementById("receipt-details");
  
    receiptDetails.innerHTML = "";
    const perSeat = (totalAmount / seatCount).toFixed(2);
    for (let i = 1; i <= seatCount; i++) {
      receiptDetails.innerHTML += `<p>Seat ${i}: ৳${perSeat} - Paid</p>`;
    }
  
    receiptOutput.classList.remove("hidden");
  });
  