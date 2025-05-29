<section
  id="dashboard"
  class="dashboard"
  style="display:none;"
>
  <div class="dashboard-header">
    <h2>Restaurant Dashboard</h2>
    <p>Welcome back! Here's your restaurant overview</p>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-number" id="todayOrders">0</div>
      <div>Orders Today</div>
    </div>
    <div class="stat-card">
      <div class="stat-number" id="todayRevenue">$0</div>
      <div>Revenue Today</div>
    </div>
    <div class="stat-card">
      <div class="stat-number" id="activeReservations">0</div>
      <div>Active Reservations</div>
    </div>
    <div class="stat-card">
      <div class="stat-number" id="staffOnDuty">0</div>
      <div>Staff On Duty</div>
    </div>
  </div>

  <div class="dashboard-grid">
    <div class="dashboard-card" onclick="showModule('menu-management')">
      <div class="card-icon">📱</div>
      <h3>Menu Management</h3>
      <p>Edit menu items, prices, and availability</p>
    </div>
    <div class="dashboard-card" onclick="showModule('reservations-management')">
      <div class="card-icon">📅</div>
      <h3>Reservations</h3>
      <p>Manage table bookings and floor plan</p>
    </div>
    <div class="dashboard-card" onclick="showModule('order-tracking')">
      <div class="card-icon">🍳</div>
      <h3>Kitchen Orders</h3>
      <p>Track order progress and timing</p>
    </div>
    <div class="dashboard-card" onclick="showModule('staff-management')">
      <div class="card-icon">👥</div>
      <h3>Staff Schedule</h3>
      <p>Manage shifts and assignments</p>
    </div>
    <div class="dashboard-card" onclick="showModule('inventory')">
      <div class="card-icon">📦</div>
      <h3>Inventory</h3>
      <p>Track stock levels and supplies</p>
    </div>
    <div class="dashboard-card" onclick="showModule('reports')">
      <div class="card-icon">📊</div>
      <h3>Reports</h3>
      <p>Sales analytics and performance</p>
    </div>
  </div>

  <div class="admin-panel hidden" id="adminPanel">
    <h3>Admin Controls</h3>
    <div class="dashboard-grid">
      <div class="dashboard-card" onclick="showModule('user-management')">
        <div class="card-icon">👤</div>
        <h3>User Management</h3>
        <p>Manage staff accounts and permissions</p>
      </div>
      <div class="dashboard-card" onclick="showModule('system-settings')">
        <div class="card-icon">⚙️</div>
        <h3>System Settings</h3>
        <p>Configure restaurant settings</p>
      </div>
    </div>
  </div>
</section>
