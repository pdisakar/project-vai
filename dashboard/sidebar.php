  <div class="sidebar">
      <div class="sidebar-header">
          <span class="logo">💻</span>
          <span class="title">Cyber Cafe<br>Management System</span>
      </div>

      <div class="sidebar-section">
          <p class="section-title">Platform</p>
          <ul class="menu">
              <li><a href="index.php">Dashboard</a></li>
              <li class="has-submenu">
                  <span class="submenu-toggle">Computers</span>
                  <ul class="submenu">
                      <li><a href="./computer/computers.php">List</a></li>
                      <li><a href="./computer/add.php">Add</a></li>
                      <li><a href="./computer/deleted.php">Deleted</a></li>
                  </ul>

              </li>
          </ul>
      </div>
  </div>


  <script>
      const sidebar = document.querySelector('.sidebar');
      const toggles = sidebar.querySelectorAll('.submenu-toggle');

      toggles.forEach(toggle => {
          toggle.addEventListener('click', () => {
              const submenu = toggle.nextElementSibling;
              if (submenu.style.display === 'block') {
                  submenu.style.display = 'none';
              } else {
                  submenu.style.display = 'block';
              }
          });
      });
  </script>