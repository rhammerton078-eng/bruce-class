<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Shared public masthead.
// Optional before include: $PAGE_TITLE, $NAV_MODE ('full' | 'min').
if (!defined('WEB_ROOT')) { require_once(dirname(__DIR__) . '/include/initialize.php'); }
$PAGE_TITLE = isset($PAGE_TITLE) ? $PAGE_TITLE : 'St. Joseph Catholic School of Sagay Inc.';
$NAV_MODE   = isset($NAV_MODE)   ? $NAV_MODE   : 'full';
$SJCS_FB    = 'https://web.facebook.com/profile.php?id=100063888224255';

/* On the homepage the section links must SCROLL, not reload the page.
   Everywhere else they must jump back to the homepage first. */
$sjcsScript = basename(parse_url($_SERVER['SCRIPT_NAME'], PHP_URL_PATH));
$IS_HOME    = ($sjcsScript === 'index.php' || $sjcsScript === '');
$H          = $IS_HOME ? '' : WEB_ROOT;   // href prefix for in-page anchors
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo htmlspecialchars($PAGE_TITLE); ?></title>
<script>
  /* same storage key as the admin side, so the choice carries across */
  if (localStorage.getItem('sjcsTheme') === 'dark') {
    document.documentElement.classList.add('sjcs-dark');
  }
</script>
<link rel="icon" href="<?php echo WEB_ROOT; ?>csr-scc.png">
<link rel="stylesheet" href="<?php echo WEB_ROOT; ?>plugins/fontawesome-free/css/all.min.css">
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&amp;family=Playfair+Display:wght@700;800;900&amp;display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo WEB_ROOT; ?>public.css?v=<?php echo @filemtime(dirname(__DIR__) . '/public.css'); ?>">
</head>
<body>

<div id="sjcsLoader" class="sjcs-loader" role="status" aria-label="Loading">
  <div class="sjcs-loader-inner">
    <span class="sjcs-loader-ring"></span>
    <img class="sjcs-loader-logo" src="<?php echo WEB_ROOT; ?>csr-scc.png" alt="St. Joseph Catholic School of Sagay Inc.">
    <span class="sjcs-loader-label">Wisdom &middot; Virtues &middot; Faith</span>
  </div>
</div>

<!-- Utility bar -->
<div class="sjcs-topbar">
  <div class="sjcs-topbar-inner">
    <span><?php echo date('l, F j, Y'); ?> &middot; Sagay City, Negros Occidental</span>
    <span>Enrollment for S.Y. 2026-2027 is open &middot; <a href="<?php echo WEB_ROOT; ?>apply.php">Apply Now</a></span>
  </div>
</div>

<!-- Nameplate -->
<div class="sjcs-masthead-bar">
  <div class="sjcs-masthead-inner">
    <a class="sjcs-masthead-seal" href="<?php echo WEB_ROOT; ?>" aria-label="Home">
      <img class="sjcs-masthead-logo" src="<?php echo WEB_ROOT; ?>csr-scc.png" alt="School seal">
    </a>
    <div class="sjcs-masthead-titles">
      <h1>St. Joseph Catholic School of Sagay, Inc.</h1>
      <p>Wisdom &middot; Virtues &middot; Faith &middot; Est. 1994</p>
    </div>
    <div class="sjcs-masthead-apply">
      <a href="<?php echo WEB_ROOT; ?>apply.php" class="sjcs-btn sjcs-btn-primary sjcs-btn-sm">Enroll Now</a>
    </div>
  </div>
</div>

<!-- Section navigation -->
<header class="sjcs-nav" id="sjcsNav">
  <div class="sjcs-nav-inner">
    <a class="sjcs-nav-compact" href="<?php echo WEB_ROOT; ?>">
      <img src="<?php echo WEB_ROOT; ?>csr-scc.png" alt=""><span>St. Joseph CSSI</span>
    </a>
    <button class="sjcs-nav-toggle" type="button" aria-label="Toggle menu"
            onclick="document.querySelector('.sjcs-links').classList.toggle('open')">
      <i class="fas fa-bars"></i>
    </button>
    <nav class="sjcs-links">
      <?php if ($NAV_MODE === 'full'): ?>
        <a href="<?php echo $H; ?>#top">Home</a>
        <a href="<?php echo $H; ?>#news">News</a>
        <a href="<?php echo $H; ?>#programs">Programs</a>
        <a href="<?php echo $H; ?>#campus">Campus Life</a>
        <a href="<?php echo $H; ?>#contact">Contact</a>
        <a href="<?php echo WEB_ROOT; ?>portals.php">Portals</a>
      <?php else: ?>
        <a href="<?php echo WEB_ROOT; ?>">Home</a>
        <a href="<?php echo WEB_ROOT; ?>#news">News</a>
        <a href="<?php echo WEB_ROOT; ?>#programs">Programs</a>
        <a href="<?php echo WEB_ROOT; ?>portals.php">Portals</a>
      <?php endif; ?>
      <a class="sjcs-fb" href="<?php echo $SJCS_FB; ?>" target="_blank" rel="noopener" title="Facebook Page" aria-label="Facebook Page"><i class="fab fa-facebook-f"></i></a>
      <button type="button" class="sjcs-theme" id="sjcsThemeToggle" title="Toggle dark mode" aria-label="Toggle dark mode"><i class="fas fa-moon" id="sjcsThemeIcon"></i></button>
    </nav>
  </div>
</header>

<a id="top"></a>