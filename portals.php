<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - The School Portals
require_once("include/initialize.php");
$PAGE_TITLE = 'The School Portals - St. Joseph Catholic School of Sagay Inc.';
$NAV_MODE   = 'min';
require_once(__DIR__ . '/theme/public_header.php');
?>

<section class="sjcs-tabhead">
  <div class="sjcs-container">
    <p class="sjcs-tabhead-kicker">Sign In</p>
    <h1 class="sjcs-tabhead-title">The School Portals</h1>
    <p class="sjcs-tabhead-sub">Choose the account that fits you. Staff, students and applicants each have their own portal.</p>
  </div>
</section>

<section class="sjcs-section">
  <div class="sjcs-container">

    <p class="sjcs-portal-groupname">Staff Portals</p>
    <div class="sjcs-portal-grid">

      <button type="button" class="sjcs-portal-card sjcs-signin" data-role="Administrator" data-icon="fa fa-user-shield">
        <div class="sjcs-portal-icon"><i class="fa fa-user-shield"></i></div>
        <h3>Administrator</h3>
        <p>Manage the entire system.</p>
        <span class="sjcs-portal-go">Sign In <i class="fas fa-arrow-right"></i></span>
      </button>

      <button type="button" class="sjcs-portal-card sjcs-signin" data-role="Registrar" data-icon="fa fa-folder-open">
        <div class="sjcs-portal-icon"><i class="fa fa-folder-open"></i></div>
        <h3>Registrar</h3>
        <p>Manage applicants, enrollment, student records, programs, subjects, and sections.</p>
        <span class="sjcs-portal-go">Sign In <i class="fas fa-arrow-right"></i></span>
      </button>

      <button type="button" class="sjcs-portal-card sjcs-signin" data-role="Cashier" data-icon="fa fa-money-bill-wave">
        <div class="sjcs-portal-icon"><i class="fa fa-money-bill-wave"></i></div>
        <h3>Cashier</h3>
        <p>Manage payments, fees, receipts, and balances.</p>
        <span class="sjcs-portal-go">Sign In <i class="fas fa-arrow-right"></i></span>
      </button>

      <button type="button" class="sjcs-portal-card sjcs-signin" data-role="Teacher" data-icon="fa fa-chalkboard-teacher">
        <div class="sjcs-portal-icon"><i class="fa fa-chalkboard-teacher"></i></div>
        <h3>Teacher</h3>
        <p>Manage assigned classes, attendance, grades, and schedules.</p>
        <span class="sjcs-portal-go">Sign In <i class="fas fa-arrow-right"></i></span>
      </button>

    </div>

    <p class="sjcs-portal-groupname">Students &amp; Applicants</p>
    <div class="sjcs-portal-grid">

      <a href="<?php echo WEB_ROOT; ?>student-portal.php" class="sjcs-portal-card">
        <div class="sjcs-portal-icon"><i class="fa fa-user-graduate"></i></div>
        <h3>Student</h3>
        <p>View enrollment, subjects, grades, attendance, payments, and schedules.</p>
        <span class="sjcs-portal-go">Sign In <i class="fas fa-arrow-right"></i></span>
      </a>

      <a href="<?php echo WEB_ROOT; ?>apply.php" class="sjcs-portal-card">
        <div class="sjcs-portal-icon"><i class="fa fa-file-signature"></i></div>
        <h3>Applicant</h3>
        <p>Apply for admission, complete requirements, and track your application status.</p>
        <span class="sjcs-portal-go">Apply Now <i class="fas fa-arrow-right"></i></span>
      </a>

    </div>
  </div>
</section>


<!-- Sign-in popup -->
<div class="sjcs-signin-modal" id="sjcsSignin" hidden>
  <div class="sjcs-signin-backdrop" data-si-close="1"></div>
  <div class="sjcs-signin-box" role="dialog" aria-modal="true" aria-labelledby="sjcsSigninTitle">
    <button type="button" class="sjcs-signin-x" data-si-close="1" aria-label="Close">&times;</button>

    <div class="sjcs-signin-head">
      <div class="sjcs-signin-seal">
        <img src="<?php echo WEB_ROOT; ?>csr-scc.png" alt="St. Joseph Catholic School of Sagay Inc. seal">
        <span class="sjcs-signin-ic" id="sjcsSigninIcon"><i class="fas fa-user-shield"></i></span>
      </div>
      <p class="sjcs-signin-school">St. Joseph Catholic School of Sagay, Inc.</p>
      <p class="sjcs-signin-kicker" id="sjcsSigninRole">Administrator</p>
      <h3 id="sjcsSigninTitle">Sign in to continue</h3>
      <p class="sjcs-signin-sub">Enter your credentials to start your session.</p>
    </div>

    <div class="sjcs-signin-error" id="sjcsSigninError" hidden>
      <i class="fas fa-exclamation-circle"></i><span id="sjcsSigninErrorText"></span>
    </div>

    <form action="<?php echo WEB_ROOT; ?>login.php" method="post" autocomplete="off">
      <div class="sjcs-field">
        <label for="si_username">Username</label>
        <div class="sjcs-input">
          <i class="fas fa-user lead"></i>
          <input type="text" id="si_username" name="username" placeholder="Enter your username" required>
        </div>
      </div>
      <div class="sjcs-field">
        <label for="si_userpass">Password</label>
        <div class="sjcs-input">
          <i class="fas fa-lock lead"></i>
          <input type="password" id="si_userpass" name="userpass" placeholder="Enter your password" required>
          <button type="button" class="sjcs-eye" id="sjcsSigninEye" aria-label="Show password"><i class="fas fa-eye"></i></button>
        </div>
      </div>
      <button type="submit" name="btnLogin" class="sjcs-submit"><i class="fas fa-sign-in-alt"></i>Log In</button>
    </form>

    <p class="sjcs-signin-note">
      <i class="fas fa-shield-alt"></i>
      <span>Restricted area. Unauthorized access is prohibited.</span>
    </p>
  </div>
</div>

<script>
(function () {
  var modal = document.getElementById('sjcsSignin');
  if (!modal) { return; }
  var roleEl = document.getElementById('sjcsSigninRole');
  var iconEl = document.getElementById('sjcsSigninIcon');
  var user   = document.getElementById('si_username');

  function open(role, icon) {
    var errBox = document.getElementById('sjcsSigninError');
    if (errBox && window.location.search.indexOf('err=') === -1) { errBox.hidden = true; }
    roleEl.textContent = role || 'Staff';
    iconEl.innerHTML = '<i class="' + (icon || 'fas fa-user-shield') + '"></i>';
    modal.hidden = false;
    document.body.style.overflow = 'hidden';
    setTimeout(function () { user.focus(); }, 60);
  }
  function close() { modal.hidden = true; document.body.style.overflow = ''; }

  document.querySelectorAll('.sjcs-signin').forEach(function (card) {
    card.addEventListener('click', function () {
      open(card.getAttribute('data-role'), card.getAttribute('data-icon'));
    });
  });
  modal.addEventListener('click', function (ev) {
    if (ev.target.getAttribute('data-si-close')) { close(); }
  });
  document.addEventListener('keydown', function (ev) {
    if (ev.key === 'Escape' && !modal.hidden) { close(); }
  });

  /* Deep link from the homepage portal cards: ?role=Teacher opens that
     role's sign-in popup directly. Unknown roles are ignored. */
  (function () {
    var m = window.location.search.match(/[?&]role=([A-Za-z]+)/);
    if (!m) { return; }
    var card = document.querySelector('.sjcs-signin[data-role="' + m[1] + '"]');
    if (card) { open(card.getAttribute('data-role'), card.getAttribute('data-icon')); }
  })();

  /* A failed sign-in sends us back here with ?err=... - reopen the popup
     with the reason shown, then clean the URL. */
  (function () {
    var m = window.location.search.match(/[?&]err=([a-z]+)/);
    if (!m) { return; }
    var msg = (m[1] === 'empty')
      ? 'Please enter both your username and password.'
      : 'Invalid username or password. Please try again.';
    var box = document.getElementById('sjcsSigninError');
    document.getElementById('sjcsSigninErrorText').textContent = msg;
    box.hidden = false;
    open('Staff', 'fas fa-user-shield');
    if (history.replaceState) {
      history.replaceState(null, '', window.location.pathname);
    }
  })();

  var eye = document.getElementById('sjcsSigninEye');
  var pw  = document.getElementById('si_userpass');
  eye.addEventListener('click', function () {
    var show = pw.getAttribute('type') === 'password';
    pw.setAttribute('type', show ? 'text' : 'password');
    eye.innerHTML = show ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
    pw.focus();
  });
})();
</script>

<?php require_once(__DIR__ . '/theme/public_footer.php'); ?>