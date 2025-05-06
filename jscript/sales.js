const salesData = [
    { orderId: 101, date: '2025-05-02', table: 1, items: 'Burger, Coke', total: 15.5 },
    { orderId: 102, date: '2025-05-02', table: 2, items: 'Pizza', total: 12.0 },
    { orderId: 103, date: '2025-05-03', table: 3, items: 'Pasta, Lemonade', total: 18.75 }
  ];
  
  function filterSales() {
    const selectedDate = document.getElementById('date-filter').value;
    const tbody = document.getElementById('sales-body');
    const totalDisplay = document.getElementById('total-sales');
  
    tbody.innerHTML = '';
    let total = 0;
  
    const filtered = salesData.filter(sale => sale.date === selectedDate);
  
    if (filtered.length === 0) {
      tbody.innerHTML = '<tr><td colspan="5">No records found for selected date.</td></tr>';
      totalDisplay.textContent = '$0.00';
      return;
    }
  
    filtered.forEach(sale => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${sale.orderId}</td>
        <td>${sale.date}</td>
        <td>${sale.table}</td>
        <td>${sale.items}</td>
        <td>$${sale.total.toFixed(2)}</td>
      `;
      tbody.appendChild(row);
      total += sale.total;
    });
  
    totalDisplay.textContent = `$${total.toFixed(2)}`;
  }
  