<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Public homepage (newspaper front page).
// Included from index.php; initialize.php already ran, so WEB_ROOT and $mydb exist.

global $mydb;

function sjcs_img($p)  { return WEB_ROOT . ltrim($p, '/'); }
function sjcs_slug($s) { $s = strtolower(trim((string)$s)); $s = preg_replace('/[^a-z0-9]+/', '-', $s); return trim($s, '-'); }
function sjcs_e($v)    { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

$programGroups = array(
  'Early Childhood Program' => array(
    array('tag'=>'Nursery 1','name'=>'Nursery 1','detail'=>'3 years old','icon'=>'fa-baby'),
    array('tag'=>'Nursery 2','name'=>'Nursery 2','detail'=>'4 years old','icon'=>'fa-shapes'),
  ),
  'Basic Education Program' => array(
    array('tag'=>'Kinder','name'=>'Kindergarten','detail'=>'5 years old','icon'=>'fa-pencil-alt'),
    array('tag'=>'Grades 1-6','name'=>'Elementary','detail'=>'Grades 1 to 6','icon'=>'fa-book-open'),
    array('tag'=>'Grades 7-10','name'=>'Junior High School','detail'=>'Grades 7 to 10','icon'=>'fa-graduation-cap'),
  ),
);

// ---- Published stories from the database ----
$news = array();
if ($mydb->tableExists('tblnews')) {
    $mydb->setQuery("SELECT NEWS_ID, TITLE, SUBTITLE, AUTHOR, CATEGORY, FEATURED_IMAGE, DATE_PUBLISHED
                     FROM tblnews WHERE STATUS = 'Published'
                     ORDER BY IS_FEATURED DESC, DATE_PUBLISHED DESC LIMIT 9");
    $news = $mydb->loadResultList();
}
$categories = array();
if (!empty($news)) {
    $mydb->setQuery("SELECT DISTINCT CATEGORY FROM tblnews
                     WHERE STATUS='Published' AND CATEGORY IS NOT NULL AND CATEGORY<>'' ORDER BY CATEGORY");
    foreach ($mydb->loadResultList() as $c) { $categories[] = $c->CATEGORY; }
}

/* ---- Built-in stories, used until articles are published. Import
   database/seed_news_batch.sql to turn these into real, clickable articles. */
$stories = array(
  array('cat'=>'Sports','date'=>'2026-09-05','img'=>'assets/news/athletics-01.jpg',
        'title'=>'St. Joseph Catholic School of Sagay, Inc. Ready for the Sagay Private Schools Association Athletic Meet',
        'deck'=>'The school sends its delegation to this year\'s Sagay Private Schools Association Athletic Meet. Viva San Jose!',
        'caption'=>'The delegation gathers with the school banner before the opening of the meet.'),
  array('cat'=>'Faith','date'=>'2026-09-08','img'=>'assets/news/mama-mary.jpg',
        'title'=>'A Blessed Birthday to Our Beloved Mama Mary',
        'deck'=>'May we always follow your example of humility, faith, and unwavering love for God.',
        'caption'=>'The school community marks the Nativity of the Blessed Virgin Mary.'),
  array('cat'=>'Events','date'=>'2026-08-27','img'=>'assets/news/caravan-02.jpg',
        'title'=>'Caravan: A Journey Through An Insightful Centerpiece',
        'deck'=>'St. Joseph Catholic School of Sagay, Inc. joined the JEEPGYM Caravan and Dapat Isa Lang Movement at the University of St. La Salle, Bacolod.',
        'caption'=>'Delegates at the JEEPGYM Caravan, Negros Island Region.'),
  array('cat'=>'Events','date'=>'2026-08-27','img'=>'assets/news/caravan-05.jpg',
        'title'=>'What is JEEPGYM? A Framework for Holistic Formation',
        'deck'=>'Justice and Peace, Ecological Integrity, Engaged Citizenship, Poverty Reduction, Gender Equality, Youth Empowerment, and Media Education.',
        'caption'=>'Fr. Wilmer S. Tria, CEAP Vice President, presents the framework.'),
  array('cat'=>'Events','date'=>'2026-08-27','img'=>'assets/news/caravan-06.jpg',
        'title'=>'The Role of Catholic Institutions in the Dapat Isa Lang Movement',
        'deck'=>'Catholic schools take part in this advocacy not for self-promotion, but to form learners deeply rooted in their faith.',
        'caption'=>'A session on the role of Catholic institutions.'),
  array('cat'=>'Sports','date'=>'2026-09-05','img'=>'assets/news/athletics-04.jpg',
        'title'=>'Student Athletes Line Up for the Opening Parade',
        'deck'=>'Players from every year level joined the opening ceremonies in school colors.',
        'caption'=>'Student athletes at the opening parade.'),
);
$useDb = !empty($news);

$SJCS_MAP_URL = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode('St. Joseph Catholic School of Sagay Inc., Sitio Palanas, Brgy. Poblacion II, Sagay City, Negros Occidental');
$PAGE_TITLE = 'St. Joseph Catholic School of Sagay Inc.';
$NAV_MODE   = 'full';
require_once(__DIR__ . '/theme/public_header.php');

// Lead + remaining stories for the fallback layout
$lead = $stories[0];
$rest = array_slice($stories, 1);
?>


<!-- Page header shown on the individual tabs (not on Home) -->
<section class="sjcs-tabhead" id="sjcsTabHead" hidden>
  <div class="sjcs-container">
    <p class="sjcs-tabhead-kicker" id="sjcsTabKicker">Section</p>
    <h1 class="sjcs-tabhead-title" id="sjcsTabTitle">Title</h1>
    <p class="sjcs-tabhead-sub" id="sjcsTabSub">Subtitle</p>
  </div>
</section>

<!-- ================= FRONT PAGE ================= -->
<section class="sjcs-front sjcs-panel" data-tab="home news">
  <div class="sjcs-container">

    <div class="sjcs-dateline">
      <span>Vol. I &middot; The School Chronicle</span>
      <span><?php echo date('l, F j, Y'); ?></span>
      <span>Diocese of San Carlos</span>
    </div>

    <div class="sjcs-front-grid">

      <!-- LEAD STORY -->
      <div class="sjcs-lead-story">
        <?php if ($useDb):
              $n = $news[0];
              $cat = $n->CATEGORY ?: 'News'; ?>
          <span class="sjcs-kicker"><?php echo sjcs_e($cat); ?></span>
          <h2><a href="<?php echo WEB_ROOT; ?>article.php?id=<?php echo (int)$n->NEWS_ID; ?>" style="color:inherit;text-decoration:none;"><?php echo sjcs_e($n->TITLE); ?></a></h2>
          <?php if (!empty($n->SUBTITLE)): ?><p class="sjcs-lead-deck"><?php echo sjcs_e($n->SUBTITLE); ?></p><?php endif; ?>
          <p class="sjcs-byline"><?php echo sjcs_e($n->AUTHOR ?: 'St. Joseph CSSI'); ?><?php if (!empty($n->DATE_PUBLISHED)): ?> &middot; <?php echo date('F j, Y', strtotime($n->DATE_PUBLISHED)); ?><?php endif; ?></p>
          <?php if (!empty($n->FEATURED_IMAGE)): ?>
          <figure class="sjcs-lead-photo">
            <img src="<?php echo sjcs_img($n->FEATURED_IMAGE); ?>" alt="<?php echo sjcs_e($n->TITLE); ?>">
          </figure>
          <?php endif; ?>
          <a class="sjcs-btn sjcs-btn-dark sjcs-btn-sm" href="<?php echo WEB_ROOT; ?>article.php?id=<?php echo (int)$n->NEWS_ID; ?>">Read Full Story</a>
        <?php else: ?>
          <span class="sjcs-kicker"><?php echo sjcs_e($lead['cat']); ?></span>
          <h2><?php echo sjcs_e($lead['title']); ?></h2>
          <p class="sjcs-lead-deck"><?php echo sjcs_e($lead['deck']); ?></p>
          <p class="sjcs-byline">St. Joseph CSSI &middot; <?php echo date('F j, Y', strtotime($lead['date'])); ?></p>
          <figure class="sjcs-lead-photo">
            <img src="<?php echo sjcs_img($lead['img']); ?>" alt="<?php echo sjcs_e($lead['title']); ?>">
            <figcaption><?php echo sjcs_e($lead['caption']); ?></figcaption>
          </figure>
        <?php endif; ?>
      </div>

      <!-- SIDEBAR RAIL -->
      <aside class="sjcs-rail">
        <div class="sjcs-rail-box">
          <span class="flag">Early Registration</span>
          <h3>Enrollment for S.Y. 2026-2027</h3>
          <p>Registration opens <strong>February 12, 2026</strong>. We are also accepting transferees and returning students. Newly approved: Grades 8 to 10.</p>
          <a href="<?php echo WEB_ROOT; ?>apply.php" class="sjcs-btn sjcs-btn-primary sjcs-btn-sm">Apply Online</a>
        </div>

        <h3 class="sjcs-rail-head">At a Glance</h3>
        <ul class="sjcs-rail-list">
          <li><i class="fas fa-school"></i> Nursery to Junior High School</li>
          <li><i class="fas fa-calendar-alt"></i> Established 1994</li>
          <li><i class="fas fa-map-marker-alt"></i> Sitio Palanas, Pob. II, Sagay City</li>
          <li><i class="fas fa-church"></i> Diocese of San Carlos</li>
        </ul>

        <h3 class="sjcs-rail-head">In Brief</h3>
        <?php if ($useDb): foreach (array_slice($news, 1, 4) as $b): ?>
          <a class="sjcs-brief" href="<?php echo WEB_ROOT; ?>article.php?id=<?php echo (int)$b->NEWS_ID; ?>">
            <span><?php echo sjcs_e($b->CATEGORY ?: 'News'); ?></span>
            <h4><?php echo sjcs_e($b->TITLE); ?></h4>
          </a>
        <?php endforeach; else: foreach (array_slice($rest, 0, 4) as $b): ?>
          <div class="sjcs-brief">
            <span><?php echo sjcs_e($b['cat']); ?></span>
            <h4><?php echo sjcs_e($b['title']); ?></h4>
          </div>
        <?php endforeach; endif; ?>
      </aside>

    </div>
  </div>
</section>

<!-- ================= NEWS COLUMNS ================= -->
<section class="sjcs-section sjcs-panel" data-tab="home news" id="news">
  <div class="sjcs-container">
    <div class="sjcs-sec-head">
      <h2>More News</h2><span class="bar"></span>
      <a class="more" href="https://web.facebook.com/profile.php?id=100063888224255" target="_blank" rel="noopener">Follow on Facebook</a>
    </div>

    <?php if ($useDb): ?>
      <div class="sjcs-chips" id="sjcsChips">
        <button type="button" class="sjcs-chip is-active" data-cat="all">All</button>
        <?php foreach ($categories as $cat): ?>
          <button type="button" class="sjcs-chip" data-cat="<?php echo sjcs_e(sjcs_slug($cat)); ?>"><?php echo sjcs_e($cat); ?></button>
        <?php endforeach; ?>
      </div>
      <div class="sjcs-mgrid" id="sjcsMgrid">
        <?php foreach (array_slice($news, 1) as $n):
              $cat = $n->CATEGORY ?: 'News';
              $isNew = (!empty($n->DATE_PUBLISHED) && strtotime($n->DATE_PUBLISHED) >= strtotime('-21 days')); ?>
          <a class="sjcs-mcard" data-cat="<?php echo sjcs_e(sjcs_slug($cat)); ?>" href="<?php echo WEB_ROOT; ?>article.php?id=<?php echo (int)$n->NEWS_ID; ?>">
            <?php if (!empty($n->FEATURED_IMAGE)): ?>
            <div class="sjcs-mcard-media">
              <img src="<?php echo sjcs_img($n->FEATURED_IMAGE); ?>" alt="<?php echo sjcs_e($n->TITLE); ?>">
              <?php if ($isNew): ?><span class="sjcs-tag is-new">New</span><?php endif; ?>
            </div>
            <?php endif; ?>
            <p class="sjcs-mcard-date"><?php echo sjcs_e($cat); ?><?php if (!empty($n->DATE_PUBLISHED)): ?> &middot; <?php echo date('M j, Y', strtotime($n->DATE_PUBLISHED)); ?><?php endif; ?></p>
            <h3><?php echo sjcs_e($n->TITLE); ?></h3>
            <?php if (!empty($n->SUBTITLE)): ?><p class="sjcs-mcard-sub"><?php echo sjcs_e($n->SUBTITLE); ?></p><?php endif; ?>
          </a>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="sjcs-mgrid">
        <?php foreach ($rest as $s): ?>
          <div class="sjcs-mcard">
            <div class="sjcs-mcard-media">
              <img src="<?php echo sjcs_img($s['img']); ?>" alt="<?php echo sjcs_e($s['title']); ?>">
              <span class="sjcs-tag">
                <?php echo sjcs_e($s['cat']); ?>
              </span>
            </div>
            <p class="sjcs-mcard-date"><?php echo sjcs_e($s['cat']); ?> &middot; <?php echo date('M j, Y', strtotime($s['date'])); ?></p>
            <h3><?php echo sjcs_e($s['title']); ?></h3>
            <p class="sjcs-mcard-sub"><?php echo sjcs_e($s['deck']); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- ================= PROGRAMS ================= -->
<section class="sjcs-section sjcs-section-alt sjcs-panel" data-tab="home programs" id="programs">
  <div class="sjcs-container">
    <div class="sjcs-sec-head">
      <h2>Programs Offered</h2><span class="bar"></span>
      <a class="more" href="<?php echo WEB_ROOT; ?>apply.php">Enroll Now</a>
    </div>
    <?php foreach ($programGroups as $groupName => $items): ?>
      <div class="sjcs-prog-group">
        <h3><?php echo sjcs_e($groupName); ?></h3>
        <div class="sjcs-grid">
          <?php foreach ($items as $p): ?>
            <div class="sjcs-card">
              <span class="sjcs-prog-icon"><i class="fas <?php echo sjcs_e($p['icon']); ?>"></i></span>
              <div class="sjcs-card-badge"><?php echo sjcs_e($p['tag']); ?></div>
              <h3><?php echo sjcs_e($p['name']); ?></h3>
              <p><?php echo sjcs_e($p['detail']); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
    <p class="sjcs-muted sjcs-center">We are also accepting transferees and returning students.</p>
  </div>
</section>

<!-- ================= FEATURED VIDEO ================= -->
<section class="sjcs-video-band sjcs-panel" data-tab="home campus" id="multimedia">
  <div class="sjcs-container">
    <div class="sjcs-video-split">

      <div class="sjcs-video-col">
        <div class="sjcs-video-frame">
          <video controls muted autoplay loop playsinline preload="metadata"
                 poster="<?php echo sjcs_img('assets/home/teaser-poster.jpg'); ?>">
            <source src="<?php echo sjcs_img('assets/home/teaser.mp4'); ?>" type="video/mp4">
            Your browser does not support the video tag.
          </video>
        </div>
      </div>

      <div class="sjcs-video-info">
        <p class="sjcs-video-kicker">Featured Video</p>
        <h2>Presbyteral Ordination of Rev. Faustino G. Cede&ntilde;o Jr.</h2>
        <p class="sjcs-video-text">
          A milestone for our parish community. The St. Joseph family joined the faithful of Sagay
          in celebrating the ordination, a moment of thanksgiving for the gift of vocation and
          service to the Church.
        </p>
        <ul class="sjcs-video-facts">
          <li><i class="fas fa-church"></i> St. Joseph Parish, Sagay</li>
          <li><i class="fas fa-map-marker-alt"></i> Sagay City, Negros Occidental</li>
          <li><i class="fas fa-cross"></i> Diocese of San Carlos</li>
        </ul>
        <a class="sjcs-btn sjcs-btn-primary sjcs-btn-sm" href="https://web.facebook.com/profile.php?id=100063888224255" target="_blank" rel="noopener">More on Facebook</a>
      </div>

    </div>
  </div>
</section>

<!-- ================= CAMPUS LIFE ================= -->
<section class="sjcs-section sjcs-panel" data-tab="home campus" id="campus">
  <div class="sjcs-container">
    <div class="sjcs-sec-head">
      <h2>Campus Life</h2><span class="bar"></span>
      <span class="more">Photo Gallery</span>
    </div>
    <div class="sjcs-gallery">
      <figure class="sjcs-gallery-item">
        <img src="<?php echo sjcs_img('assets/news/athletics-02.jpg'); ?>" alt="Team huddle at the athletic meet">
        <figcaption>Team huddle before the games</figcaption>
      </figure>
      <figure class="sjcs-gallery-item">
        <img src="<?php echo sjcs_img('assets/news/caravan-03.jpg'); ?>" alt="JEEPGYM Caravan plenary">
        <figcaption>JEEPGYM Caravan plenary, Bacolod</figcaption>
      </figure>
      <figure class="sjcs-gallery-item">
        <img src="<?php echo sjcs_img('assets/news/caravan-08.jpg'); ?>" alt="Workshop session">
        <figcaption>Workshop session with delegates</figcaption>
      </figure>
      <figure class="sjcs-gallery-item">
        <img src="<?php echo sjcs_img('assets/news/athletics-03.jpg'); ?>" alt="Student athletes">
        <figcaption>Student athletes at the venue</figcaption>
      </figure>
      <figure class="sjcs-gallery-item">
        <img src="<?php echo sjcs_img('assets/news/caravan-01.jpg'); ?>" alt="Registration at the caravan">
        <figcaption>Registration of delegates</figcaption>
      </figure>
      <figure class="sjcs-gallery-item">
        <img src="<?php echo sjcs_img('assets/news/caravan-04.jpg'); ?>" alt="Delegates with the Bishop">
        <figcaption>Delegates with the Bishop</figcaption>
      </figure>
    </div>
  </div>
</section>


<!-- ================= CONTACT ================= -->
<section class="sjcs-section sjcs-section-alt sjcs-panel" data-tab="home contact" id="contact">
  <div class="sjcs-container">
    <div class="sjcs-sec-head">
      <h2>Contact Us</h2><span class="bar"></span>
      <a class="more" href="<?php echo WEB_ROOT; ?>apply.php">Enroll Now</a>
    </div>
    <div class="sjcs-contact-grid">
      <a class="sjcs-contact-card" href="<?php echo $SJCS_MAP_URL; ?>" target="_blank" rel="noopener">
        <span class="sjcs-contact-ic"><i class="fas fa-map-marker-alt"></i></span>
        <div class="sjcs-contact-body">
          <h3>Visit Us</h3>
          <p>Sitio Palanas, Brgy. Poblacion II (Pob. 2),<br>Sagay City, Negros Occidental</p>
        </div>
        <span class="sjcs-contact-go">Open in Google Maps <i class="fas fa-arrow-right"></i></span>
      </a>
      <button type="button" class="sjcs-contact-card" id="sjcsEmailCard" aria-haspopup="dialog">
        <span class="sjcs-contact-ic"><i class="fas fa-envelope"></i></span>
        <div class="sjcs-contact-body">
          <h3>Email Us</h3>
          <p>sjpsagaylearningschool@gmail.com<br>st.josephcatholicschool24@gmail.com</p>
        </div>
        <span class="sjcs-contact-go">Choose an address <i class="fas fa-arrow-right"></i></span>
      </button>
      <a class="sjcs-contact-card" href="https://web.facebook.com/profile.php?id=100063888224255" target="_blank" rel="noopener">
        <span class="sjcs-contact-ic"><i class="fab fa-facebook-f"></i></span>
        <div class="sjcs-contact-body">
          <h3>Follow Us</h3>
          <p>St. Joseph Catholic School of Sagay, Inc.<br>News, announcements and photos</p>
        </div>
        <span class="sjcs-contact-go">Open Facebook Page <i class="fas fa-arrow-right"></i></span>
      </a>
    </div>
  </div>
</section>

<!-- ================= CTA ================= -->
<section class="sjcs-cta sjcs-panel" data-tab="home">
  <div class="sjcs-container">
    <h2>Ready to join St. Joseph?</h2>
    <p>Send your application online, or sign in to the portal that fits you.</p>
    <div class="sjcs-cta-actions">
      <a href="<?php echo WEB_ROOT; ?>apply.php" class="sjcs-btn sjcs-btn-primary">Apply for Admission</a>
      <a href="<?php echo WEB_ROOT; ?>portals.php" class="sjcs-btn sjcs-btn-outline-light">School Portals</a>
    </div>
  </div>
</section>

<script>
/* ---------------------------------------------------------------
   Section tabs. The nav links act as tabs on the homepage:
   Home shows everything, the others show only their own section.
   --------------------------------------------------------------- */
(function () {
  var panels = document.querySelectorAll('.sjcs-panel');
  var links  = Array.prototype.slice.call(document.querySelectorAll('.sjcs-links a[href*="#"], .sjcs-foot-tab'));
  if (!panels.length) { return; }

  var VALID = { top:'home', home:'home', news:'news', programs:'programs', campus:'campus', multimedia:'campus', contact:'contact' };

  function tabFromHash(hash) {
    var id = (hash || '').replace('#', '');
    return VALID[id] || 'home';
  }

  function show(tab, doScroll) {
    panels.forEach(function (p) {
      var tabs = (p.getAttribute('data-tab') || '').split(' ');
      p.style.display = (tabs.indexOf(tab) !== -1) ? '' : 'none';
    });
    links.forEach(function (a) {
      var t = tabFromHash('#' + (a.getAttribute('href') || '').split('#')[1]);
      a.classList.toggle('is-active', t === tab && (a.getAttribute('href') || '').indexOf('#') !== -1);
    });
    document.body.setAttribute('data-active-tab', tab);

    var COPY = {
      news:     ['The School Chronicle', 'News & Announcements', 'Stories, events and milestones from the St. Joseph community.'],
      programs: ['Admissions',           'Programs Offered',     'Nursery to Junior High School, guided by Wisdom, Virtues and Faith.'],
      campus:   ['Student Life',         'Campus Life',          'Moments from our activities, competitions and gatherings.'],
      contact:  ['Get in Touch',         'Contact Us',           'Visit the campus, send us an email, or follow us online.']
    };
    var head = document.getElementById('sjcsTabHead');
    if (head) {
      if (COPY[tab]) {
        document.getElementById('sjcsTabKicker').textContent = COPY[tab][0];
        document.getElementById('sjcsTabTitle').textContent  = COPY[tab][1];
        document.getElementById('sjcsTabSub').textContent    = COPY[tab][2];
        head.hidden = false;
      } else {
        head.hidden = true;
      }
    }
    if (doScroll) { window.scrollTo({ top: 0, behavior: 'smooth' }); }
    // re-run reveal for panels that were hidden when first observed
    document.querySelectorAll('.sjcs-panel .sjcs-reveal').forEach(function (el) { el.classList.add('is-visible'); });
  }

  links.forEach(function (a) {
    var href = a.getAttribute('href') || '';
    if (href.indexOf('#') === -1) { return; }
    var id = href.split('#')[1];
    if (!VALID[id]) { return; }
    a.addEventListener('click', function (ev) {
      ev.preventDefault();
      var tab = VALID[id];
      if (history.replaceState) { history.replaceState(null, '', '#' + id); }
      else { window.location.hash = id; }
      show(tab, true);
      var menu = document.querySelector('.sjcs-links');
      if (menu) { menu.classList.remove('open'); }
    });
  });

  show(tabFromHash(window.location.hash), false);
  window.addEventListener('hashchange', function () { show(tabFromHash(window.location.hash), true); });
})();

(function () {
  var chips = document.querySelectorAll('#sjcsChips .sjcs-chip');
  var cards = document.querySelectorAll('#sjcsMgrid .sjcs-mcard');
  if (!chips.length) { return; }
  chips.forEach(function (chip) {
    chip.addEventListener('click', function () {
      chips.forEach(function (c) { c.classList.remove('is-active'); });
      chip.classList.add('is-active');
      var cat = chip.getAttribute('data-cat');
      cards.forEach(function (card) {
        card.style.display = (cat === 'all' || card.getAttribute('data-cat') === cat) ? '' : 'none';
      });
    });
  });
})();
</script>


<!-- Email address chooser -->
<div class="sjcs-modal" id="sjcsEmailModal" hidden>
  <div class="sjcs-modal-backdrop" data-close="1"></div>
  <div class="sjcs-modal-box" role="dialog" aria-modal="true" aria-labelledby="sjcsEmailModalTitle">
    <button type="button" class="sjcs-modal-x" data-close="1" aria-label="Close">&times;</button>
    <h3 id="sjcsEmailModalTitle">Which address would you like to email?</h3>
    <p class="sjcs-modal-note">Your message opens in Gmail. Pick the one that fits your concern.</p>

    <a class="sjcs-modal-opt" href="https://mail.google.com/mail/?view=cm&amp;fs=1&amp;to=<?php echo rawurlencode('sjpsagaylearningschool@gmail.com'); ?>" target="_blank" rel="noopener">
      <span class="sjcs-modal-ic"><i class="fas fa-envelope"></i></span>
      <span class="sjcs-modal-opt-body">
        <strong>sjpsagaylearningschool@gmail.com</strong>
        <em>General inquiries and enrollment</em>
      </span>
      <i class="fas fa-arrow-right"></i>
    </a>

    <a class="sjcs-modal-opt" href="https://mail.google.com/mail/?view=cm&amp;fs=1&amp;to=<?php echo rawurlencode('st.josephcatholicschool24@gmail.com'); ?>" target="_blank" rel="noopener">
      <span class="sjcs-modal-ic"><i class="fas fa-envelope"></i></span>
      <span class="sjcs-modal-opt-body">
        <strong>st.josephcatholicschool24@gmail.com</strong>
        <em>School office and records</em>
      </span>
      <i class="fas fa-arrow-right"></i>
    </a>
  </div>
</div>

<script>
(function () {
  var card  = document.getElementById('sjcsEmailCard');
  var modal = document.getElementById('sjcsEmailModal');
  if (!card || !modal) { return; }

  function open()  { modal.hidden = false; document.body.style.overflow = 'hidden'; }
  function close() { modal.hidden = true;  document.body.style.overflow = ''; }

  card.addEventListener('click', open);
  modal.addEventListener('click', function (ev) {
    if (ev.target.getAttribute('data-close')) { close(); }
  });
  modal.querySelectorAll('.sjcs-modal-opt').forEach(function (a) {
    a.addEventListener('click', function () { setTimeout(close, 120); });
  });
  document.addEventListener('keydown', function (ev) {
    if (ev.key === 'Escape' && !modal.hidden) { close(); }
  });
})();
</script>


<!-- Image preview (lightbox) -->
<div class="sjcs-lb" id="sjcsLightbox" hidden>
  <div class="sjcs-lb-backdrop" data-lb-close="1"></div>
  <button type="button" class="sjcs-lb-x" data-lb-close="1" aria-label="Close preview">&times;</button>
  <button type="button" class="sjcs-lb-nav prev" id="sjcsLbPrev" aria-label="Previous image"><i class="fas fa-chevron-left"></i></button>
  <button type="button" class="sjcs-lb-nav next" id="sjcsLbNext" aria-label="Next image"><i class="fas fa-chevron-right"></i></button>
  <figure class="sjcs-lb-box" role="dialog" aria-modal="true">
    <div class="sjcs-lb-stage"><img id="sjcsLbImg" src="" alt=""></div>
    <figcaption class="sjcs-lb-info">
      <p class="sjcs-lb-cat" id="sjcsLbCat"></p>
      <h3 id="sjcsLbTitle"></h3>
      <p class="sjcs-lb-text" id="sjcsLbText"></p>
      <p class="sjcs-lb-count" id="sjcsLbCount"></p>
    </figcaption>
  </figure>
</div>

<script>
/* ---------------------------------------------------------------
   Image preview. Any homepage photo opens enlarged with its
   caption and details. Cards that link to a full article keep
   their link; only their photo opens the preview.
   --------------------------------------------------------------- */
(function () {
  var lb = document.getElementById('sjcsLightbox');
  if (!lb) { return; }

  var elImg   = document.getElementById('sjcsLbImg');
  var elCat   = document.getElementById('sjcsLbCat');
  var elTitle = document.getElementById('sjcsLbTitle');
  var elText  = document.getElementById('sjcsLbText');
  var elCount = document.getElementById('sjcsLbCount');

  var items = [];

  function txt(node, sel) {
    var n = node.querySelector(sel);
    return n ? n.textContent.trim() : '';
  }

  /* Gallery tiles */
  document.querySelectorAll('.sjcs-gallery-item').forEach(function (fig) {
    var img = fig.querySelector('img');
    if (!img) { return; }
    items.push({ el: img, src: img.getAttribute('src'), cat: 'Campus Life',
                 title: txt(fig, 'figcaption'), text: '' });
  });

  /* Lead story photo */
  document.querySelectorAll('.sjcs-lead-photo').forEach(function (fig) {
    var img = fig.querySelector('img');
    if (!img) { return; }
    var story = fig.closest('.sjcs-lead-story');
    items.push({ el: img, src: img.getAttribute('src'),
                 cat: story ? txt(story, '.sjcs-kicker') : '',
                 title: story ? txt(story, 'h2') : (img.getAttribute('alt') || ''),
                 text: (story ? txt(story, '.sjcs-lead-deck') : '') || txt(fig, 'figcaption') });
  });

  /* News cards */
  document.querySelectorAll('.sjcs-mcard').forEach(function (card) {
    var img = card.querySelector('.sjcs-mcard-media img');
    if (!img) { return; }
    items.push({ el: img, src: img.getAttribute('src'),
                 cat: txt(card, '.sjcs-mcard-date'),
                 title: txt(card, 'h3'),
                 text: txt(card, '.sjcs-mcard-sub') });
  });

  if (!items.length) { return; }

  var index = 0;

  function render(i) {
    var it = items[i];
    if (!it) { return; }
    index = i;
    elImg.setAttribute('src', it.src);
    elImg.setAttribute('alt', it.title || '');
    elCat.textContent   = it.cat || '';
    elCat.style.display = it.cat ? '' : 'none';
    elTitle.textContent = it.title || '';
    elText.textContent  = it.text || '';
    elText.style.display = it.text ? '' : 'none';
    elCount.textContent = (i + 1) + ' of ' + items.length;
  }

  function open(i)  { render(i); lb.hidden = false; document.body.style.overflow = 'hidden'; }
  function close()  { lb.hidden = true; document.body.style.overflow = ''; }
  function step(d)  { render((index + d + items.length) % items.length); }

  items.forEach(function (it, i) {
    var frame = it.el.parentNode;
    if (frame && frame.classList) { frame.classList.add('sjcs-zoomable'); }
    it.el.addEventListener('click', function (ev) {
      ev.preventDefault();   // keeps a linked card from navigating
      ev.stopPropagation();
      open(i);
    });
  });

  lb.addEventListener('click', function (ev) {
    if (ev.target.getAttribute('data-lb-close')) { close(); }
  });
  document.getElementById('sjcsLbPrev').addEventListener('click', function () { step(-1); });
  document.getElementById('sjcsLbNext').addEventListener('click', function () { step(1); });
  document.addEventListener('keydown', function (ev) {
    if (lb.hidden) { return; }
    if (ev.key === 'Escape')     { close(); }
    if (ev.key === 'ArrowLeft')  { step(-1); }
    if (ev.key === 'ArrowRight') { step(1); }
  });
})();
</script>

<?php require_once(__DIR__ . '/theme/public_footer.php'); ?>