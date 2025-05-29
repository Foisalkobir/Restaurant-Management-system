function showSection(section) {
  document.querySelectorAll('section').forEach(s => s.style.display = 'none');
  const el = document.getElementById(section);
  if(el) el.style.display = 'block';

  if(section === 'menu') loadMenu();
  else if(section === 'reservations') {
    loadTableMap();
    setMinDate();
  }
}

function showAuth(type) {
  document.getElementById('authModal').style.display = 'flex';
  if(type === 'login') switchToLogin();
  else switchToSignup();
}
function closeAuth() {
  document.getElementById('authModal').style.display = 'none';
}
function switchToLogin() {
  document.getElementById('loginForm').classList.remove('hidden');
  document.getElementById('signupForm').classList.add('hidden');
}
function switchToSignup() {
  document.getElementById('loginForm').classList.add('hidden');
  document.getElementById('signupForm').classList.remove('hidden');
}

function handleLogin(e) {
  e.preventDefault();
  const email = document.getElementById('loginEmail').value;
  const password = document.getElementById('loginPassword').value;
  const users = {
    'admin@restaurant.com': {name:'Admin User', role:'admin'},
    'manager@restaurant.com': {name:'Manager User', role:'manager'},
    'staff@restaurant.com': {name:'Staff Member', role:'staff'},
    'customer@restaurant.com': {name:'John Customer', role:'customer'}
  };
  if(users[email] && password.endsWith('123')) {
    currentUser = {...users[email], email};
    loginSuccess();
  } else {
    document.getElementById('loginError').textContent = 'Invalid credentials.';
  }
}
function handleSignup(e) {
  e.preventDefault();
  const password = document.getElementById('signupPassword').value;
  const confirmPassword = document.getElementById('signupConfirmPassword').value;
  if(password !== confirmPassword) {
    document.getElementById('signupError').textContent = 'Passwords do not match';
    return;
  }
  document.getElementById('signupSuccess').textContent = 'Account created! Please login.';
  setTimeout(() => {
    switchToLogin();
    document.getElementById('signupSuccess').textContent = '';
  }, 2000);
}
function loginSuccess() {
  closeAuth();
  document.getElementById('authButtons').classList.add('hidden');
  document.getElementById('userButtons').classList.remove('hidden');
  document.getElementById('welcomeUser').textContent = `Welcome, ${currentUser.name}`;
  if(currentUser.role !== 'customer') {
    showSection('dashboard');
    if(currentUser.role === 'admin')
      document.getElementById('adminPanel').classList.remove('hidden');
  } else {
    showSection('home');
  }
}
function logout() {
  currentUser = null;
  document.getElementById('authButtons').classList.remove('hidden');
  document.getElementById('userButtons').classList.add('hidden');
  document.getElementById('adminPanel').classList.add('hidden');
  showSection('home');
}

const menuItems = [
  {id:1,name:"Grilled Salmon",description:"Fresh Atlantic salmon with herbs and lemon",price:24.99,category:"Main Course",image:"https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=300&h=200&fit=crop",dietary:["gluten-free"]},
  {id:2,name:"Beef Tenderloin",description:"Premium cut with seasonal vegetables",price:32.99,category:"Main Course",image:"https://images.unsplash.com/photo-1558030006-450675393462?w=300&h=200&fit=crop",dietary:[]},
  {id:3,name:"Caesar Salad",description:"Classic romaine with parmesan and croutons",price:12.99,category:"Appetizer",image:"https://images.unsplash.com/photo-1551248429-40975aa4de74?w=300&h=200&fit=crop",dietary:["vegetarian"]},
  {id:4,name:"Chocolate Lava Cake",description:"Warm chocolate cake with vanilla ice cream",price:8.99,category:"Dessert",image:"https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=300&h=200&fit=crop",dietary:["vegetarian"]}
];
function loadMenu() {
  const menuGrid = document.getElementById('menuGrid');
  menuGrid.innerHTML = '';
  menuItems.forEach(item => {
    const div = document.createElement('div');
    div.className = 'menu-item';
    div.innerHTML = `
      <img src="${item.image}" alt="${item.name}" onerror="this.src='placeholder.svg'"/>
      <div class="menu-item-content">
        <h3>${item.name}</h3>
        <p>${item.description}</p>
        <div class="price">$${item.price.toFixed(2)}</div>
        ${item.dietary.length ? `<small>${item.dietary.join(', ')}</small>` : ''}
      </div>
    `;
    menuGrid.appendChild(div);
  });
}

function loadTableMap() {
  const tableMap = document.getElementById('tableMap');
  tableMap.innerHTML = '';
  for(let i=1;i<=20;i++) {
    const table = document.createElement('div');
    table.className = 'table available';
    table.textContent = i;
    table.onclick = () => selectTable(i, table);
    if(Math.random()<0.3){
      table.className = 'table occupied';
      table.onclick = null;
    }
    tableMap.appendChild(table);
  }
}
let selectedTable = null;
function selectTable(number, el) {
  document.querySelectorAll('.table.selected').forEach(t=>{
    t.classList.remove('selected');
    t.classList.add('available');
  });
  el.classList.remove('available');
  el.classList.add('selected');
  selectedTable = number;
}
function setMinDate() {
  const dateInput = document.getElementById('reservationDate');
  const tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate()+1);
  dateInput.min = tomorrow.toISOString().split('T')[0];
}

document.getElementById('reservationForm').addEventListener('submit', e => {
  e.preventDefault();
  if(!selectedTable) { alert('Please select a table'); return; }
  alert('Reservation confirmed! Table ' + selectedTable);
  e.target.reset();
  selectedTable = null;
  loadTableMap();
});
document.getElementById('contactForm').addEventListener('submit', e => {
  e.preventDefault();
  alert('Thanks for contacting us! We will respond soon.');
  e.target.reset();
});

document.addEventListener('DOMContentLoaded', () => {
  showSection('home');
});
