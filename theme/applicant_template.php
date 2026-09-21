<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Applicant Portal shell
// Deliberately NOT theme/template.php (the admin AdminLTE shell) - an
// applicant should see a simple, focused nav for their own pipeline,
// not the full admin sidebar.
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo htmlspecialchars($title ?? 'Applicant Portal'); ?> - St. Joseph Catholic School of Sagay Inc.</title>
<link rel="stylesheet" href="<?php echo WEB_ROOT; ?>plugins/fontawesome-free/css/all.min.css">
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
<link rel="stylesheet" href="<?php echo WEB_ROOT; ?>public.css?v=<?php echo @filemtime(dirname(__DIR__).'/public.css'); ?>">
<style>
.sjcs-portal-shell { max-width: 900px; margin: 0 auto; padding: 40px 24px 70px; }
.sjcs-status-pill { display:inline-block; padding:6px 16px; border-radius:999px; background:var(--sjcs-emerald); color:#fff; font-weight:700; font-size:0.85rem; }
.sjcs-portal-nav-row { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:28px; }
.sjcs-portal-nav-row a { padding:9px 18px; border-radius:999px; background:#fff; border:1px solid #d7ddd9; text-decoration:none; color:var(--sjcs-text); font-weight:600; font-size:0.9rem; }
.sjcs-portal-nav-row a.active { background:var(--sjcs-emerald); border-color:var(--sjcs-emerald); color:#fff; }
</style>
</head>
<body>

<header class="sjcs-nav">
  <div class="sjcs-nav-inner">
    <a class="sjcs-brand" href="<?php echo WEB_ROOT; ?>portal/applicant/index.php">
      <img src="<?php echo WEB_ROOT; ?>csr-scc.png" alt="Logo">
      <span>St. Joseph Catholic School of Sagay Inc.</span>
    </a>
    <nav class="sjcs-links">
      <span><?php echo htmlspecialchars($_SESSION['DISPLAYNAME'] ?? ''); ?></span>
      <a href="<?php echo WEB_ROOT; ?>logout.php">Logout</a>
    </nav>
  </div>
</header>

<div class="sjcs-portal-shell">
  <div class="sjcs-portal-nav-row">
    <a href="<?php echo WEB_ROOT; ?>portal/applicant/index.php" class="<?php echo ($title=='Home') ? 'active' : ''; ?>">Dashboard</a>
    <a href="<?php echo WEB_ROOT; ?>portal/applicant/admission_form.php" class="<?php echo ($title=='Admission Form') ? 'active' : ''; ?>">Admission Form</a>
    <a href="<?php echo WEB_ROOT; ?>portal/applicant/payment.php" class="<?php echo ($title=='Payment') ? 'active' : ''; ?>">Payment</a>
  </div>

  <?php require_once($content); ?>
</div>

<footer class="sjcs-footer">
  <div class="sjcs-container">
    <p>&copy; 2026 St. Joseph Catholic School of Sagay Inc. All rights reserved.</p>
  </div>
</footer>

</body>
</html>
