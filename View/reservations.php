<section id="reservations" class="menu-section" style="display:none;">
  <div class="reservation-form">
    <h2>Make a Reservation</h2>
    <form id="reservationForm">
      <div class="form-group">
        <label>Date</label>
        <input type="date" id="reservationDate" required />
      </div>
      <div class="form-group">
        <label>Time</label>
        <select id="reservationTime" required>
          <option value="">Select Time</option>
          <option value="17:00">5:00 PM</option>
          <option value="17:30">5:30 PM</option>
          <option value="18:00">6:00 PM</option>
          <option value="18:30">6:30 PM</option>
          <option value="19:00">7:00 PM</option>
          <option value="19:30">7:30 PM</option>
          <option value="20:00">8:00 PM</option>
          <option value="20:30">8:30 PM</option>
          <option value="21:00">9:00 PM</option>
        </select>
      </div>
      <div class="form-group">
        <label>Party Size</label>
        <select id="partySize" required>
          <option value="">Select Size</option>
          <option value="1">1 Person</option>
          <option value="2">2 People</option>
          <option value="3">3 People</option>
          <option value="4">4 People</option>
          <option value="5">5 People</option>
          <option value="6">6 People</option>
          <option value="7">7 People</option>
          <option value="8">8 People</option>
        </select>
      </div>

      <h3>Select Your Table</h3>
      <div class="table-map" id="tableMap"></div>

      <div class="form-group">
        <label>Special Requests</label>
        <textarea
          id="specialRequests"
          rows="3"
          placeholder="Any special requirements or dietary restrictions?"
        ></textarea>
      </div>

      <button type="submit" class="btn btn-primary" style="width: 100%">
        Make Reservation
      </button>
    </form>
  </div>
</section>
