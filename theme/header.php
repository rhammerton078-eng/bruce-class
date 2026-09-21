<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Admin top bar.
$sjName = isset($_SESSION['DISPLAYNAME']) ? $_SESSION['DISPLAYNAME'] : 'User';
$sjRole = isset($_SESSION['TYPE']) ? $_SESSION['TYPE'] : 'Staff';
$sjInit = strtoupper(substr(trim($sjName), 0, 1));
?>
<nav class="main-header navbar navbar-expand sjcs-topnav">

  <!-- Left -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" data-controlsidebar-side="false" href="#" role="button" title="Toggle menu">
        <i class="fas fa-bars"></i>
      </a>
    </li>
  </ul>

  <!-- Right -->
  <ul class="navbar-nav ml-auto align-items-center">

    <li class="nav-item">
      <a class="nav-link sjcs-iconbtn" href="#" id="darkModeToggle" title="Toggle dark mode">
        <i class="fas fa-moon" id="darkModeIcon"></i>
      </a>
    </li>

    <li class="nav-item dropdown sjcs-user">
      <a class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown" href="#">
        <span class="sjcs-avatar"><?php echo htmlspecialchars($sjInit); ?></span>
        <span class="d-none d-sm-inline-block sjcs-user-meta">
          <span class="nm"><?php echo htmlspecialchars($sjName); ?></span>
          <span class="rl"><?php echo htmlspecialchars($sjRole); ?></span>
        </span>
      </a>

      <div class="dropdown-menu dropdown-menu-right sjcs-usermenu">
        <div class="sjcs-usermenu-head">
          <span class="sjcs-avatar lg"><?php echo htmlspecialchars($sjInit); ?></span>
          <div>
            <strong><?php echo htmlspecialchars($sjName); ?></strong>
            <span><?php echo htmlspecialchars($sjRole); ?></span>
          </div>
        </div>

        <a class="dropdown-item sjcs-logout-link" href="#" id="sjcsLogoutBtn">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </div>
    </li>

  </ul>
</nav>

<!-- Sign out confirmation -->
<div class="sjcs-confirm" id="sjcsLogoutModal" hidden>
  <div class="sjcs-confirm-backdrop" data-lo-close="1"></div>
  <div class="sjcs-confirm-box" role="dialog" aria-modal="true" aria-labelledby="sjcsLogoutTitle">
    <span class="sjcs-confirm-ic"><i class="fas fa-sign-out-alt"></i></span>
    <h3 id="sjcsLogoutTitle">Are you sure you want to logout?</h3>
    <p>You are signed in as <strong><?php echo htmlspecialchars($sjName); ?></strong>. Any unsaved work on this page will be lost.</p>
    <div class="sjcs-confirm-actions">
      <button type="button" class="sjcs-cbtn ghost" data-lo-close="1"><i class="fas fa-times"></i> Cancel</button>
      <a class="sjcs-cbtn danger" href="<?php echo WEB_ROOT; ?>logout.php"><i class="fas fa-sign-out-alt"></i> Yes, Logout</a>
    </div>
  </div>
</div>

<script>
  /* Plain JS on purpose: jQuery is loaded near the bottom of template.php,
     so it does not exist yet at this point in the page. */
  document.addEventListener('DOMContentLoaded', function () {
    var htmlEl = document.documentElement;
    var toggle = document.getElementById('darkModeToggle');
    var icon   = document.getElementById('darkModeIcon');

    function setIcon(dark) {
      if (!icon) { return; }
      icon.classList.toggle('fa-moon', !dark);
      icon.classList.toggle('fa-sun', dark);
    }

    var isDark = (localStorage.getItem('sjcsTheme') === 'dark');
    htmlEl.classList.toggle('dark-mode-custom', isDark);
    if (document.body) { document.body.classList.toggle('dark-mode-custom', isDark); }
    setIcon(isDark);

    if (toggle) {
      toggle.addEventListener('click', function (e) {
        e.preventDefault();
        var nowDark = htmlEl.classList.toggle('dark-mode-custom');
        if (document.body) { document.body.classList.toggle('dark-mode-custom', nowDark); }
        localStorage.setItem('sjcsTheme', nowDark ? 'dark' : 'light');
        setIcon(nowDark);
        toggle.setAttribute('title', nowDark ? 'Switch to light mode' : 'Switch to dark mode');
      });
    }

    /* sign out confirmation */
    var loBtn   = document.getElementById('sjcsLogoutBtn');
    var loModal = document.getElementById('sjcsLogoutModal');
    if (loBtn && loModal) {
      loBtn.addEventListener('click', function (e) {
        e.preventDefault();
        loModal.hidden = false;
        document.body.style.overflow = 'hidden';
      });
      loModal.addEventListener('click', function (ev) {
        if (ev.target.getAttribute('data-lo-close')) {
          loModal.hidden = true;
          document.body.style.overflow = '';
        }
      });
      document.addEventListener('keydown', function (ev) {
        if (ev.key === 'Escape' && !loModal.hidden) {
          loModal.hidden = true;
          document.body.style.overflow = '';
        }
      });
    }
  });
</script>