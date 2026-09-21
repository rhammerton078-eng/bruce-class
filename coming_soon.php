<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - shared "not yet available"
// notice for portals that don't exist yet. Included (not redirected to)
// by student-portal.php and apply.php so each keeps its own URL/title.
if (!defined('WEB_ROOT')) { require_once(__DIR__ . "/include/initialize.php"); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo htmlspecialchars($comingSoonTitle ?? 'Coming Soon'); ?> - St. Joseph Catholic School of Sagay Inc.</title>
<link rel="stylesheet" href="<?php echo WEB_ROOT; ?>plugins/fontawesome-free/css/all.min.css">
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
<link rel="stylesheet" href="<?php echo WEB_ROOT; ?>public.css?v=<?php echo @filemtime(__DIR__.'/public.css'); ?>">
</head>
<body>

<header class="sjcs-nav">
  <div class="sjcs-nav-inner">
    <a class="sjcs-brand" href="<?php echo WEB_ROOT; ?>">
      <img src="<?php echo WEB_ROOT; ?>csr-scc.png" alt="Logo">
      <span>St. Joseph Catholic School of Sagay Inc.</span>
    </a>
    <nav class="sjcs-links">
      <a href="<?php echo WEB_ROOT; ?>">Home</a>
      <a href="<?php echo WEB_ROOT; ?>portals.php">School Portals</a>
    </nav>
  </div>
</header>

<section class="sjcs-section" style="text-align:center; padding-top:90px; padding-bottom:100px;">
  <div class="sjcs-container">
    <div class="sjcs-portal-icon" style="margin:0 auto 20px; width:80px; height:80px; font-size:2rem;">
      <i class="fa fa-tools"></i>
    </div>
    <h2 class="sjcs-section-title" style="margin-bottom:14px;"><?php echo htmlspecialchars($comingSoonTitle ?? 'Coming Soon'); ?></h2>
    <p class="sjcs-muted" style="max-width:520px; margin:0 auto 28px;"><?php echo htmlspecialchars($comingSoonMessage ?? 'This portal is being built and is not available yet.'); ?></p>
    <a href="<?php echo WEB_ROOT; ?>portals.php" class="sjcs-btn sjcs-btn-primary">Back to School Portals</a>
  </div>
</section>

<footer class="sjcs-footer">
  <div class="sjcs-container">
    <p>&copy; 2026 St. Joseph Catholic School of Sagay Inc. All rights reserved.</p>
  </div>
</footer>

</body>
</html>
