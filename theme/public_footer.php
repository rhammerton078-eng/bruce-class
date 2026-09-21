<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Shared public footer.
$SJCS_FB = 'https://web.facebook.com/profile.php?id=100063888224255';
$SJCS_ADDR  = 'Sitio Palanas, Brgy. Poblacion II (Pob. 2), Sagay City, Negros Occidental';
$SJCS_MAP   = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode('St. Joseph Catholic School of Sagay Inc., ' . $SJCS_ADDR);
$SJCS_MAIL1 = 'sjpsagaylearningschool@gmail.com';
$SJCS_MAIL2 = 'st.josephcatholicschool24@gmail.com';
/* Opens the Gmail compose window when the visitor is signed in to Gmail,
   and falls back to their default mail app if Gmail is not available. */
if (!function_exists('sjcs_gmail')) {
    function sjcs_gmail($to) {
        return 'https://mail.google.com/mail/?view=cm&fs=1&to=' . rawurlencode($to);
    }
}
if (!isset($H)) {
    $sjcsScript = basename(parse_url($_SERVER['SCRIPT_NAME'], PHP_URL_PATH));
    $H = ($sjcsScript === 'index.php' || $sjcsScript === '') ? '' : WEB_ROOT;
}
?>
<footer class="sjcs-footer">
  <div class="sjcs-container sjcs-footer-top">

    <div class="sjcs-footer-brand">
      <img src="<?php echo WEB_ROOT; ?>csr-scc.png" alt="St. Joseph Catholic School of Sagay Inc. seal">
      <div>
        <strong>St. Joseph Catholic School of Sagay, Inc.</strong>
        <span>Wisdom &middot; Virtues &middot; Faith<br>Education with Faith, Excellence, and Purpose.</span>
      </div>
    </div>

    <div class="sjcs-footer-col">
      <h4>Explore</h4>
      <a class="sjcs-foot-tab" href="<?php echo $H; ?>#top">Home</a>
      <a class="sjcs-foot-tab" href="<?php echo $H; ?>#news">News</a>
      <a class="sjcs-foot-tab" href="<?php echo $H; ?>#programs">Programs</a>
      <a class="sjcs-foot-tab" href="<?php echo $H; ?>#campus">Campus Life</a>
      <a class="sjcs-foot-tab" href="<?php echo $H; ?>#multimedia">Multimedia</a>
    </div>

    <div class="sjcs-footer-col">
      <h4>Quick Links</h4>
      <a href="<?php echo WEB_ROOT; ?>apply.php">Apply for Admission</a>
      <a href="<?php echo WEB_ROOT; ?>portals.php">School Portals</a>
      <a href="<?php echo WEB_ROOT; ?>student-portal.php">Student Portal</a>
      <a href="<?php echo WEB_ROOT; ?>login.php">Staff Sign In</a>
    </div>

    <div class="sjcs-footer-col">
      <h4>Contact Us</h4>
      <a href="<?php echo $SJCS_MAP; ?>" target="_blank" rel="noopener" title="Open in Google Maps"><i class="fas fa-map-marker-alt"></i><?php echo $SJCS_ADDR; ?></a>
      <a href="<?php echo sjcs_gmail($SJCS_MAIL1); ?>" target="_blank" rel="noopener" title="Compose in Gmail"><i class="fas fa-envelope"></i><?php echo $SJCS_MAIL1; ?></a>
      <a href="<?php echo sjcs_gmail($SJCS_MAIL2); ?>" target="_blank" rel="noopener" title="Compose in Gmail"><i class="fas fa-envelope"></i><?php echo $SJCS_MAIL2; ?></a>
      <a href="<?php echo $SJCS_MAP; ?>" target="_blank" rel="noopener"><i class="fas fa-directions"></i>Get Directions</a>
      <a class="sjcs-fb-btn" href="<?php echo $SJCS_FB; ?>" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i> Facebook Page</a>
    </div>

  </div>

  <div class="sjcs-footer-bottom">
    <p>&copy; <?php echo date('Y'); ?> St. Joseph Catholic School of Sagay, Inc. &middot; Diocese of San Carlos &middot; All rights reserved.</p>
  </div>
</footer>

<script>
/* ---- Dark mode toggle ---- */
(function () {
  var btn  = document.getElementById('sjcsThemeToggle');
  var icon = document.getElementById('sjcsThemeIcon');
  var root = document.documentElement;
  function paint(dark) {
    if (!icon) { return; }
    icon.classList.toggle('fa-moon', !dark);
    icon.classList.toggle('fa-sun', dark);
  }
  paint(root.classList.contains('sjcs-dark'));
  if (!btn) { return; }
  btn.addEventListener('click', function () {
    var dark = root.classList.toggle('sjcs-dark');
    localStorage.setItem('sjcsTheme', dark ? 'dark' : 'light');
    paint(dark);
  });
})();

/* ---- Mobile menu: close after a link is tapped ---- */
document.querySelectorAll('.sjcs-links a').forEach(function (a) {
  a.addEventListener('click', function () {
    var links = document.querySelector('.sjcs-links');
    if (links) { links.classList.remove('open'); }
  });
});

/* ---- Loading screen. Hides on load, then honours any #hash so section
        links from another page land in the right place. ---- */
(function () {
  var loader = document.getElementById('sjcsLoader');
  function jumpToHash() {
    if (!window.location.hash) { return; }
    var el = document.querySelector(window.location.hash);
    if (el) { setTimeout(function () { el.scrollIntoView({ behavior: 'auto', block: 'start' }); }, 60); }
  }
  function hide() {
    if (loader) { loader.classList.add('hide'); }
    document.body.classList.remove('sjcs-loading');
    jumpToHash();
  }
  document.body.classList.add('sjcs-loading');
  if (!loader) { jumpToHash(); return; }
  window.addEventListener('load', function () { setTimeout(hide, 350); });
  setTimeout(hide, 3000); // failsafe
})();

/* ---- Compact brand in the sticky bar once scrolled ---- */
(function () {
  var nav = document.getElementById('sjcsNav');
  if (!nav) { return; }
  function onScroll() { nav.classList.toggle('is-scrolled', window.scrollY > 140); }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
})();

/* ---- Highlight the nav link for the section in view.
        Skipped on pages that use section tabs, which set the active
        link themselves. ---- */
(function () {
  if (document.body.hasAttribute('data-active-tab')) { return; }
  var links = Array.prototype.slice.call(document.querySelectorAll('.sjcs-links a[href*="#"]'));
  if (!links.length || !('IntersectionObserver' in window)) { return; }
  var map = {};
  links.forEach(function (a) {
    var id = a.getAttribute('href').split('#')[1];
    if (id) { map[id] = a; }
  });
  var sections = Object.keys(map).map(function (id) { return document.getElementById(id); }).filter(Boolean);
  if (!sections.length) { return; }
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (e.isIntersecting) {
        links.forEach(function (a) { a.classList.remove('is-active'); });
        if (map[e.target.id]) { map[e.target.id].classList.add('is-active'); }
      }
    });
  }, { rootMargin: '-45% 0px -50% 0px' });
  sections.forEach(function (s) { io.observe(s); });
})();

/* ---- Reveal content as it scrolls into view ---- */
(function () {
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var targets = document.querySelectorAll('.sjcs-lead-story, .sjcs-rail, .sjcs-mcard, .sjcs-card, .sjcs-gallery-item, .sjcs-video-frame, .sjcs-portal-card, .sjcs-sec-head');
  if (reduce || !('IntersectionObserver' in window) || !targets.length) { return; }
  var seen = {};
  targets.forEach(function (el) {
    el.classList.add('sjcs-reveal');
    var key = el.parentNode ? (el.parentNode.className || 'x') : 'x';
    seen[key] = (seen[key] === undefined) ? 0 : seen[key] + 1;
    var d = seen[key] % 6;
    if (d > 0) { el.setAttribute('data-delay', d); }
  });
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target); }
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
  targets.forEach(function (el) { io.observe(el); });
})();
</script>
</body>
</html>