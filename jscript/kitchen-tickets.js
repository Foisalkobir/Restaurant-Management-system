const form = document.getElementById('ticket-form');
const ticketList = document.getElementById('ticket-list');

form.addEventListener('submit', function(e) {
  e.preventDefault();

  const tableNumber = document.getElementById('table-number').value.trim();
  const items = document.getElementById('items').value.trim();

  if (!tableNumber || !items) {
    alert('Please fill in all fields.');
    return;
  }

  const ticket = document.createElement('div');
  ticket.className = 'ticket';

  const statusStages = ['Pending', 'Preparing', 'Ready'];
  let currentStage = 0;

  ticket.innerHTML = `
    <p><strong>Table:</strong> ${tableNumber}</p>
    <p><strong>Items:</strong> ${items}</p>
    <p class="status"><strong>Status:</strong> <span>${statusStages[currentStage]}</span></p>
    <button class="status-btn">Next Stage</button>
  `;

  const statusBtn = ticket.querySelector('.status-btn');
  const statusText = ticket.querySelector('.status span');

  statusBtn.addEventListener('click', () => {
    currentStage++;
    if (currentStage < statusStages.length) {
      statusText.textContent = statusStages[currentStage];
    } else {
      statusText.textContent = 'Completed';
      statusText.style.color = 'green';
      statusBtn.remove(); // remove the button
    }
  });

  ticketList.appendChild(ticket);
  form.reset();
});
