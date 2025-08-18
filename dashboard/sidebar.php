  <div class="sidebar">
      <div class="sidebar-header">
          <span class="logo">💻</span>
          <div class="title"> 
            <div class="main-title">Cyber Cafe</div>
            <div class="sub-title">Management Systam</div>
          </div>
      </div>

      <div class="sidebar-section">
          <p class="section-title">Platform</p>
          <ul class="menu">
              <li><a href="index.php">
              <img src='./public/svg/dashboard.svg' alt='Dashboard Icon' class='icon' width="14" height="14"/> 
              Dashboard</a></li>
              <li class="has-submenu">
                  <span class="submenu-toggle">
                                  <img src='./public/svg/computers.svg' alt='Dashboard Icon' class='icon' width="14" height="14"/> 
Computers</span>
                  <ul class="submenu">
                      <li><a href="./computers.php">List</a></li>
                      <li><a href="./computersadd.php">Add</a></li>
                      <li><a href="./computerdeleted.php">Deleted</a></li>
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