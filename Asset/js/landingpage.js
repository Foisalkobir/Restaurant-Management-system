    function closeDemo() {
      document.getElementById('demoModal').style.display = 'none';
    }
    function startDemo() {
      alert('🎉 Demo started! You would now be redirected to the full demo environment.');
      closeDemo();
    }
    function updateMenu() {
      alert('✅ Menu updated successfully! New items added.');
    }
    function addOrder() {
      const orderNumber = Math.floor(Math.random() * 9000) + 1000;
      alert(`🆕 New order #${orderNumber} added to the queue!`);
    }
    function refreshStats() {
      const revenue = document.getElementById('revenue');
      const ordersCount = document.getElementById('orders-count');
      const avgOrder = document.getElementById('avg-order');
      const tablesOccupied = document.getElementById('tables-occupied');

      revenue.textContent = '$' + (Math.random() * 3000 + 2000).toFixed(2);
      ordersCount.textContent = Math.floor(Math.random() * 50 + 100);
      avgOrder.textContent = '$' + (Math.random() * 10 + 20).toFixed(2);
      tablesOccupied.textContent = Math.floor(Math.random() * 8 + 5) + '/15';

      alert('📊 Stats refreshed with latest data!');
    }
    window.onclick = function (event) {
      const modal = document.getElementById('demoModal');
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    };
    window.addEventListener('scroll', function () {
      const header = document.querySelector('header');
      if (window.scrollY > 100) {
        header.style.background = 'rgba(255, 255, 255, 0.98)';
        header.style.boxShadow = '0 2px 25px rgba(0,0,0,0.15)';
      } else {
        header.style.background = 'rgba(255, 255, 255, 0.95)';
        header.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
      }
    });
    function animateOnScroll() {
      const elements = document.querySelectorAll('.feature-card, .demo-card, .stat-card');

      elements.forEach((element) => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;

        if (elementTop < window.innerHeight - elementVisible) {
          element.style.animation = 'fadeInUp 0.6s ease forwards';
        }
      });
    }
    window.addEventListener('scroll', animateOnScroll);
    window.addEventListener('load', animateOnScroll);

    function updateClock() {
      const now = new Date();
      const timeString = now.toLocaleTimeString();

      const timeElements = document.querySelectorAll('.current-time');
      timeElements.forEach((element) => {
        element.textContent = timeString;
      });
    }
    setInterval(updateClock, 1000);
