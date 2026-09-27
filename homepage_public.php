<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Public homepage.
// Included from index.php; initialize.php already ran, so WEB_ROOT and $mydb exist.
// Visual source of truth: the approved Stitch screen "St. Joseph Catholic School -
// Home (Final Refinement)". Styles live in assets/bruce/home.css and behaviour in
// assets/bruce/home.js. The shared public header/footer used by apply.php,
// portals.php and article.php are left untouched.

global $mydb;

if (!function_exists('sjcs_img'))  { function sjcs_img($p)  { return WEB_ROOT . ltrim($p, '/'); } }
if (!function_exists('sjcs_e'))    { function sjcs_e($v)    { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); } }
if (!function_exists('bc_asset'))  {
    // Cache-busted URL for a file in assets/bruce/.
    function bc_asset($file) {
        $path = __DIR__ . '/assets/bruce/' . $file;
        return WEB_ROOT . 'assets/bruce/' . $file . '?v=' . (is_file($path) ? filemtime($path) : '1');
    }
}

$SJCS_FB    = 'https://web.facebook.com/profile.php?id=100063888224255';
$SJCS_ADDR  = 'Sitio Palanas, Brgy. Poblacion II (Pob. 2), Sagay City, Negros Occidental';
$SJCS_MAP   = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode('St. Joseph Catholic School of Sagay Inc., ' . $SJCS_ADDR);
$SJCS_MAIL1 = 'sjpsagaylearningschool@gmail.com';
$SJCS_MAIL2 = 'st.josephcatholicschool24@gmail.com';
$FOUNDED    = 1994;
$YEARS      = max(1, (int)date('Y') - $FOUNDED);

/* ---- Section switches from tblhomepage_sections (admin-controlled).
        A section is shown unless the table explicitly disables it. ---- */
$sectionEnabled = array();
if ($mydb->tableExists('tblhomepage_sections')) {
    $mydb->setQuery("SELECT SECTION_KEY, IS_ENABLED FROM tblhomepage_sections");
    foreach ($mydb->loadResultList() as $s) { $sectionEnabled[$s->SECTION_KEY] = (int)$s->IS_ENABLED === 1; }
}
function bc_on($key) { global $sectionEnabled; return !isset($sectionEnabled[$key]) || $sectionEnabled[$key]; }

/* ---- Published stories from the database ---- */
$news = array();
if ($mydb->tableExists('tblnews')) {
    $mydb->setQuery("SELECT NEWS_ID, TITLE, SUBTITLE, AUTHOR, CATEGORY, FEATURED_IMAGE, DATE_PUBLISHED
                     FROM tblnews WHERE STATUS = 'Published'
                     ORDER BY IS_FEATURED DESC, DATE_PUBLISHED DESC LIMIT 4");
    $news = $mydb->loadResultList();
}

/* ---- Built-in stories (real school photos in assets/news/), used until
        articles are published. Import database/seed_news_batch.sql to turn
        these into real, clickable articles. ---- */
$fallbackStories = array(
  array('cat'=>'Sports','date'=>'2026-09-05','img'=>'assets/news/athletics-01.jpg','pos'=>'50% 40%',
        'title'=>'St. Joseph Catholic School of Sagay, Inc. Ready for the Sagay Private Schools Association Athletic Meet',
        'deck'=>'The school sends its delegation to this year\'s Sagay Private Schools Association Athletic Meet. Viva San Jose!',
        'alt'=>'The school delegation gathers with the school banner before the opening of the athletic meet'),
  array('cat'=>'Faith','date'=>'2026-09-08','img'=>'assets/news/mama-mary.jpg','pos'=>'50% 62%',
        'title'=>'A Blessed Birthday to Our Beloved Mama Mary',
        'deck'=>'May we always follow your example of humility, faith, and unwavering love for God.',
        'alt'=>'The school community marks the Nativity of the Blessed Virgin Mary'),
  array('cat'=>'Events','date'=>'2026-08-27','img'=>'assets/news/caravan-02.jpg','pos'=>'50% 32%',
        'title'=>'Caravan: A Journey Through An Insightful Centerpiece',
        'deck'=>'St. Joseph Catholic School of Sagay, Inc. joined the JEEPGYM Caravan and Dapat Isa Lang Movement at the University of St. La Salle, Bacolod.',
        'alt'=>'Delegates at the JEEPGYM Caravan, Negros Island Region'),
  array('cat'=>'Events','date'=>'2026-08-27','img'=>'assets/news/caravan-05.jpg','pos'=>'50% 35%',
        'title'=>'What is JEEPGYM? A Framework for Holistic Formation',
        'deck'=>'Justice and Peace, Ecological Integrity, Engaged Citizenship, Poverty Reduction, Gender Equality, Youth Empowerment, and Media Education.',
        'alt'=>'Fr. Wilmer S. Tria, CEAP Vice President, presents the JEEPGYM framework'),
);

// Normalise both sources into one shape for the template.
$stories = array();
if (!empty($news)) {
    foreach ($news as $n) {
        $stories[] = array(
            'cat'   => $n->CATEGORY ?: 'News',
            'date'  => $n->DATE_PUBLISHED,
            'img'   => $n->FEATURED_IMAGE,
            'pos'   => '50% 45%',
            'title' => $n->TITLE,
            'deck'  => $n->SUBTITLE,
            'alt'   => $n->TITLE,
            'href'  => WEB_ROOT . 'article.php?id=' . (int)$n->NEWS_ID,
        );
    }
} else {
    $stories = $fallbackStories;
}
$lead = $stories[0];
$side = array_slice($stories, 1, 3);

$programs = array(
  array('no'=>'01','group'=>'Early Childhood','name'=>'Nursery 1','level'=>'3 years old','prog'=>'Early Childhood Program','icon'=>'fa-baby',
        'text'=>'The very first classroom. Short days, plenty of play, and a gentle first taste of routine, song, and prayer.'),
  array('no'=>'02','group'=>'Early Childhood','name'=>'Nursery 2','level'=>'4 years old','prog'=>'Early Childhood Program','icon'=>'fa-shapes',
        'text'=>'Shapes, colours, counting, and early letters. Children build confidence in a group before formal schooling begins.'),
  array('no'=>'03','group'=>'Basic Education','name'=>'Kindergarten','level'=>'5 years old','prog'=>'Basic Education Program','icon'=>'fa-pencil-alt',
        'text'=>'The bridge into school proper. Reading, writing and numbers arrive, still through play and story.'),
  array('no'=>'04','group'=>'Basic Education','name'=>'Elementary','level'=>'Grades 1 to 6','prog'=>'Basic Education Program','icon'=>'fa-book-open',
        'text'=>'Six years of steady formation across every core subject, with sports, competitions and parish service alongside.'),
  array('no'=>'05','group'=>'Basic Education','name'=>'Junior High School','level'=>'Grades 7 to 10','prog'=>'Basic Education Program','icon'=>'fa-graduation-cap',
        'text'=>'Grades 8 to 10 are newly approved. Heavier subjects, real responsibility, and preparation for senior high.'),
);

$stages = array(
  array('short'=>'Nursery 1 &amp; 2','name'=>'Early Childhood','meta'=>'Nursery 1 &amp; 2 &middot; Ages 3 to 4','badge'=>'Ages 3&ndash;4','word'=>'One','group'=>'Early Childhood',
        'text'=>'The first steps. Play, song, story and prayer build the habits of listening and kindness, and children learn to be part of a group before they learn to read.'),
  array('short'=>'Kindergarten','name'=>'Kindergarten','meta'=>'Age 5','badge'=>'Age 5','word'=>'Two','group'=>'Basic Education',
        'text'=>'The bridge into school proper: reading, writing and numbers through play and story.'),
  array('short'=>'Elementary (Grades 1&ndash;6)','name'=>'Elementary','meta'=>'Grades 1 to 6','badge'=>'Grades 1&ndash;6','word'=>'Three','group'=>'Basic Education',
        'text'=>'Steady formation across every core subject, with sports, competitions and parish service.'),
  array('short'=>'Junior High (Grades 7&ndash;10)','name'=>'Junior High School','meta'=>'Grades 7 to 10','badge'=>'Grades 7&ndash;10','word'=>'Four','group'=>'Basic Education',
        'text'=>'Heavier subjects, real responsibility and preparation for senior high.'),
);

$portals = array(
  array('name'=>'Applicant Portal','tag'=>'Open','icon'=>'fa-file-signature','solid'=>true,'href'=>WEB_ROOT.'apply.php','action'=>'Apply now',
        'text'=>'Apply for admission, complete requirements, and track your application status.'),
  array('name'=>'Student Portal','tag'=>'Coming soon','icon'=>'fa-user-graduate','solid'=>true,'href'=>WEB_ROOT.'student-portal.php','action'=>'Learn more',
        'text'=>'View enrollment, subjects, grades, attendance, payments, and schedules.'),
  array('name'=>'Teacher Portal','tag'=>'Faculty','icon'=>'fa-chalkboard-teacher','solid'=>true,'href'=>WEB_ROOT.'portals.php?role=Teacher','action'=>'Sign in',
        'text'=>'Manage assigned classes, attendance, grades, and schedules.'),
  array('name'=>'Registrar Portal','tag'=>'Registrar','icon'=>'fa-folder-open','solid'=>false,'href'=>WEB_ROOT.'portals.php?role=Registrar','action'=>'Sign in',
        'text'=>'Manage applicants, enrollment, student records, programs, subjects, and sections.'),
  array('name'=>'Cashier Portal','tag'=>'Finance','icon'=>'fa-receipt','solid'=>false,'href'=>WEB_ROOT.'portals.php?role=Cashier','action'=>'Sign in',
        'text'=>'Manage payments, fees, receipts, and balances.'),
  array('name'=>'Administrator','tag'=>'Admin','icon'=>'fa-user-shield','solid'=>true,'href'=>WEB_ROOT.'portals.php?role=Administrator','action'=>'Sign in',
        'text'=>'Manage the entire system, its users and its settings.'),
);

$campus = array(
  array('img'=>'assets/news/caravan-06.jpg','w'=>1080,'h'=>1440,'ratio'=>'4-5','cap'=>'A session on the role of Catholic institutions','tag'=>'Formation',
        'alt'=>'A speaker leads a session on the role of Catholic institutions at the JEEPGYM Caravan'),
  array('img'=>'assets/news/athletics-02.jpg','w'=>1440,'h'=>1080,'ratio'=>'4-3','cap'=>'Team huddle before the games','tag'=>'Sports',
        'alt'=>'Student athletes huddle together before the games at the athletic meet'),
  array('img'=>'assets/news/athletics-04.jpg','w'=>1600,'h'=>1200,'ratio'=>'4-3','cap'=>'Student athletes at the opening parade','tag'=>'Sports',
        'alt'=>'Student athletes in school colours line up for the opening parade'),
  array('img'=>'assets/news/caravan-03.jpg','w'=>1440,'h'=>1080,'ratio'=>'16-7','cap'=>'JEEPGYM Caravan plenary, Bacolod','tag'=>'Events',
        'alt'=>'Delegates seated at the JEEPGYM Caravan plenary in Bacolod'),
);

$navLinks = array(
  array('about','About'), array('programs','Programs'), array('journey','Journey'),
  array('news','News'), array('campus','Campus Life'), array('contact','Contact'),
);
$drawerLinks = array(
  array('about','About'), array('programs','Programs'), array('faith','Faith Formation'), array('journey','Learner&rsquo;s Journey'),
  array('news','News'), array('campus','Campus Life'), array('contact','Contact'),
);
$dots = array(
  array('top','Top of page'), array('about','School highlights'), array('programs','Programs'), array('faith','Faith formation'),
  array('patron','Our patron'), array('journey','Learner&rsquo;s journey'), array('news','News'), array('campus','Campus life'),
  array('portals','Portals'), array('contact','Contact'),
);

function bc_date($d) { return $d ? strtoupper(date('F j, Y', strtotime($d))) : ''; }
function bc_arrow() { return '<i class="fas fa-arrow-right bc-arrow" aria-hidden="true"></i>'; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>St. Joseph Catholic School of Sagay, Inc. &mdash; Home</title>
<meta name="description" content="St. Joseph Catholic School of Sagay, Inc. welcomes children from Nursery through Junior High School under the Diocese of San Carlos.">
<script>document.documentElement.classList.add('js');</script>
<link rel="icon" href="<?php echo WEB_ROOT; ?>csr-scc.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400..600;1,9..144,400&amp;family=Geist+Mono:wght@400;500;600&amp;family=Geist:wght@400;500;600&amp;display=swap">
<link rel="stylesheet" href="<?php echo WEB_ROOT; ?>plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="<?php echo bc_asset('home.css'); ?>">
<script src="<?php echo bc_asset('home.js'); ?>" defer></script>
</head>
<body class="bc">

<a class="bc-skip" href="#main-content">Skip to main content</a>

<!-- Right-edge section dots (desktop) -->
<nav class="bc-dots" aria-label="Page sections">
  <?php foreach ($dots as $d): ?>
    <a href="#<?php echo $d[0]; ?>" data-dot="<?php echo $d[0]; ?>" aria-label="Go to <?php echo $d[1]; ?>"><span></span></a>
  <?php endforeach; ?>
</nav>

<!-- 1. HEADER -->
<header class="bc-header" id="bcHeader">
  <div class="bc-wrap bc-header-inner">
    <a class="bc-brand" href="#top">
      <span class="bc-seal" aria-hidden="true">SJ</span>
      <span class="bc-brand-text">
        <span class="bc-brand-name">St. Joseph Catholic School</span>
        <span class="bc-brand-sub">Sagay, Inc. &middot; Est. <?php echo $FOUNDED; ?></span>
      </span>
    </a>
    <nav class="bc-nav" aria-label="Primary">
      <?php foreach ($navLinks as $l): ?>
        <a class="bc-nav-link" href="#<?php echo $l[0]; ?>" data-spy="<?php echo $l[0]; ?>"><?php echo $l[1]; ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="bc-header-actions">
      <a class="bc-btn bc-btn-outline bc-btn-sm" href="#portals">Portals</a>
      <a class="bc-btn bc-btn-primary bc-btn-sm" href="<?php echo WEB_ROOT; ?>apply.php">Apply Now</a>
    </div>
    <button class="bc-menu-btn" id="bcMenuBtn" type="button" aria-controls="bcDrawer" aria-expanded="false" aria-label="Open navigation menu">
      <span class="bc-menu-lines" aria-hidden="true"><span></span><span></span></span>
    </button>
  </div>
</header>

<!-- Mobile / tablet menu -->
<div class="bc-drawer" id="bcDrawer" role="dialog" aria-modal="true" aria-label="Site menu" hidden>
  <div class="bc-drawer-head">
    <span class="bc-brand">
      <span class="bc-seal bc-seal-sm" aria-hidden="true">SJ</span>
      <span class="bc-brand-name">St. Joseph Catholic School</span>
    </span>
    <button class="bc-icon-btn" id="bcDrawerClose" type="button" aria-label="Close navigation menu"><i class="fas fa-times" aria-hidden="true"></i></button>
  </div>
  <nav class="bc-drawer-nav" aria-label="Mobile">
    <?php foreach ($drawerLinks as $i => $l): ?>
      <a href="#<?php echo $l[0]; ?>" style="--i:<?php echo $i; ?>"><span class="bc-drawer-no"><?php echo sprintf('%02d', $i + 1); ?></span><span class="bc-drawer-label"><?php echo $l[1]; ?></span></a>
    <?php endforeach; ?>
  </nav>
  <div class="bc-drawer-actions">
    <a class="bc-btn bc-btn-outline bc-btn-block" href="#portals">Visit Portals</a>
    <a class="bc-btn bc-btn-primary bc-btn-block" href="<?php echo WEB_ROOT; ?>apply.php">Apply Now</a>
  </div>
</div>

<main id="main-content" tabindex="-1">

<!-- 2. HERO -->
<?php if (bc_on('hero')): ?>
<section class="bc-hero" id="top" aria-labelledby="bcHeroTitle">
  <div class="bc-hero-emblem" aria-hidden="true" data-parallax="0.12">
    <svg viewBox="0 0 200 200" fill="none">
      <circle cx="100" cy="100" r="90" stroke="currentColor" stroke-dasharray="4 6" stroke-width="1.5"/>
      <path d="M100 20 C100 80 40 100 40 100 C40 100 100 120 100 180 C100 120 160 100 160 100 C160 100 100 80 100 20 Z" stroke="currentColor"/>
      <line x1="100" y1="10" x2="100" y2="190" stroke="currentColor" stroke-dasharray="2 4" stroke-width=".5"/>
      <line x1="10" y1="100" x2="190" y2="100" stroke="currentColor" stroke-dasharray="2 4" stroke-width=".5"/>
    </svg>
  </div>
  <div class="bc-wrap bc-hero-grid">
    <div class="bc-hero-copy">
      <p class="bc-pill" data-reveal><span class="bc-pill-dot" aria-hidden="true"></span>Wisdom &middot; Virtues &middot; Faith</p>
      <h1 class="bc-hero-title" id="bcHeroTitle">
        <span class="bc-line"><span class="bc-line-in" style="--d:50ms">Where Ambition</span></span>
        <span class="bc-line"><span class="bc-line-in" style="--d:140ms">Becomes</span></span>
        <span class="bc-line"><span class="bc-line-in bc-accent" style="--d:230ms">Achievement.</span></span>
      </h1>
      <p class="bc-lede" data-reveal style="--d:300ms">St. Joseph Catholic School of Sagay, Inc. welcomes children from Nursery through Junior High School. Under the Diocese of San Carlos, we form learners who are confident in their studies and rooted in their faith.</p>
      <div class="bc-hero-actions" data-reveal style="--d:380ms">
        <a class="bc-btn bc-btn-primary" href="<?php echo WEB_ROOT; ?>apply.php"><span>Apply for Admission</span><?php echo bc_arrow(); ?></a>
        <a class="bc-btn bc-btn-outline" href="#portals">Visit the Portals</a>
      </div>
    </div>
    <div class="bc-folio" data-reveal style="--d:200ms">
      <div class="bc-folio-item">
        <p class="bc-kicker">Academic horizon</p>
        <p class="bc-folio-title">Nursery to Grade 10</p>
        <p class="bc-folio-text">Early Childhood, Kindergarten, Elementary and Junior High.</p>
      </div>
      <div class="bc-folio-item">
        <p class="bc-kicker">Campus location</p>
        <p class="bc-folio-title">Sagay City</p>
        <p class="bc-folio-text">Sitio Palanas, Brgy. Poblacion II, Negros Occidental.</p>
      </div>
      <div class="bc-folio-item">
        <p class="bc-kicker">Ecclesial affiliation</p>
        <p class="bc-folio-title">Diocese of San Carlos</p>
        <p class="bc-folio-text">A Catholic basic education school, established <?php echo $FOUNDED; ?>.</p>
      </div>
    </div>
  </div>
  <div class="bc-scroll-cue">
    <a href="#about"><span>Scroll to explore</span><span class="bc-scroll-line" aria-hidden="true"><span></span></span></a>
  </div>
</section>
<?php endif; ?>

<!-- 3. HIGHLIGHTS -->
<section class="bc-stats" id="about" aria-labelledby="bcStatsTitle">
  <h2 class="bc-sr" id="bcStatsTitle">St. Joseph at a glance</h2>
  <div class="bc-wrap bc-stats-grid">
    <div class="bc-stat" data-reveal>
      <p class="bc-stat-figure"><span data-count="<?php echo $YEARS; ?>"><?php echo $YEARS; ?></span></p>
      <p class="bc-stat-label">Years of service</p>
      <p class="bc-stat-line">Since <?php echo $FOUNDED; ?></p>
      <span class="bc-stat-rule" aria-hidden="true"></span>
    </div>
    <div class="bc-stat" data-reveal style="--d:80ms">
      <p class="bc-stat-figure"><span data-count="13">13</span></p>
      <p class="bc-stat-label">Grade levels</p>
      <p class="bc-stat-line">Nursery 1 to Grade 10</p>
      <span class="bc-stat-rule" aria-hidden="true"></span>
    </div>
    <div class="bc-stat" data-reveal style="--d:160ms">
      <p class="bc-stat-figure bc-stat-word">Nursery&ndash;10</p>
      <p class="bc-stat-label">Basic education</p>
      <span class="bc-stat-rule" aria-hidden="true"></span>
    </div>
    <div class="bc-stat" data-reveal style="--d:240ms">
      <p class="bc-stat-figure bc-stat-word">San Carlos</p>
      <p class="bc-stat-label">Diocese</p>
      <span class="bc-stat-rule" aria-hidden="true"></span>
    </div>
  </div>
</section>

<!-- 4. PROGRAMS -->
<?php if (bc_on('programs')): ?>
<section class="bc-section" id="programs" aria-labelledby="bcProgramsTitle">
  <div class="bc-wrap">
    <div class="bc-sec-head bc-sec-head-rule" data-reveal>
      <div>
        <p class="bc-kicker">Programs offered</p>
        <h2 class="bc-h2" id="bcProgramsTitle">Nursery to Junior High School</h2>
      </div>
      <p class="bc-sec-intro">Nursery to Junior High School, guided by Wisdom, Virtues and Faith. We are also accepting transferees and returning students.</p>
    </div>
    <div class="bc-program-grid">
      <?php foreach ($programs as $i => $p): ?>
      <article class="bc-card bc-program" data-reveal style="--d:<?php echo ($i % 3) * 80; ?>ms">
        <div>
          <div class="bc-program-top">
            <span class="bc-program-icon" aria-hidden="true"><i class="fas <?php echo $p['icon']; ?>"></i></span>
            <span class="bc-program-meta"><span><?php echo $p['no']; ?></span><span><?php echo sjcs_e($p['group']); ?></span></span>
          </div>
          <h3 class="bc-h3"><?php echo sjcs_e($p['name']); ?></h3>
          <p class="bc-program-level"><?php echo sjcs_e($p['level']); ?></p>
          <p class="bc-program-text"><?php echo sjcs_e($p['text']); ?></p>
        </div>
        <p class="bc-card-foot"><?php echo sjcs_e($p['prog']); ?></p>
      </article>
      <?php endforeach; ?>
      <article class="bc-card bc-program bc-program-dark" data-reveal style="--d:160ms">
        <div>
          <div class="bc-program-top">
            <span class="bc-program-icon" aria-hidden="true"><i class="fas fa-exchange-alt"></i></span>
            <span class="bc-program-meta"><span>06</span><span>Admissions</span></span>
          </div>
          <h3 class="bc-h3">Transferees &amp; Returning</h3>
          <p class="bc-program-level">Accepted at every level</p>
          <p class="bc-program-text">Moving from another school or returning after time away? You are welcome, based on requirements.</p>
        </div>
        <div class="bc-card-foot bc-card-foot-split">
          <span>Requirements apply</span>
          <a class="bc-link-arrow" href="<?php echo WEB_ROOT; ?>apply.php">Apply now <?php echo bc_arrow(); ?></a>
        </div>
      </article>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 5. FAITH FORMATION -->
<section class="bc-faith" id="faith" aria-labelledby="bcFaithTitle">
  <div class="bc-wrap bc-faith-grid">
    <div class="bc-faith-copy" data-reveal>
      <p class="bc-kicker bc-kicker-gold">Faith formation</p>
      <h2 class="bc-h2" id="bcFaithTitle">Formed in faith, not only in subjects.</h2>
      <p class="bc-faith-text">Every school day here opens and closes in prayer. Children take part in the life of the parish, learn the sacraments at the right age, and grow up seeing faith lived out by the adults around them rather than only described.</p>
      <blockquote class="bc-quote">
        <p>&ldquo;Education with Faith, Excellence, and Purpose.&rdquo;</p>
        <footer>&mdash; St. Joseph Catholic School of Sagay, Inc.</footer>
      </blockquote>
    </div>
    <div class="bc-faith-emblem-wrap" data-parallax="0.08">
      <div class="bc-faith-emblem">
        <span class="bc-faith-ring" aria-hidden="true"></span>
        <div class="bc-faith-emblem-inner">
          <svg class="bc-faith-cross" viewBox="0 0 64 64" fill="none" aria-hidden="true">
            <line x1="32" y1="6" x2="32" y2="58" stroke="currentColor" stroke-width="2" stroke-dasharray="2 3"/>
            <line x1="14" y1="24" x2="50" y2="24" stroke="currentColor" stroke-width="2" stroke-dasharray="2 3"/>
            <circle cx="32" cy="24" r="3" fill="currentColor"/>
          </svg>
          <p class="bc-faith-motto">Wisdom &middot; Virtues &middot; Faith</p>
          <p class="bc-kicker bc-kicker-gold">Diocese of San Carlos</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 6. PATRON -->
<section class="bc-patron" id="patron" aria-labelledby="bcPatronTitle">
  <div class="bc-patron-inner" data-reveal>
    <div class="bc-lily" aria-hidden="true" data-parallax="0.06">
      <div class="bc-float">
        <svg viewBox="0 0 320 400" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="160" cy="180" r="130" stroke="#C9A13B" stroke-dasharray="2 4" opacity=".55"/>
          <circle cx="160" cy="180" r="140" stroke="#C9A13B" stroke-width=".75" opacity=".35"/>
          <circle cx="160" cy="180" r="146" stroke="#C9A13B" stroke-dasharray="1 5" stroke-width=".5" opacity=".25"/>
          <path d="M160 170 Q160 260 159 385" stroke="#0F3A2B" stroke-linecap="round" stroke-width="5"/>
          <path d="M160 170 Q160 260 159 385" stroke="#C9A13B" stroke-linecap="round" opacity=".85"/>
          <path d="M159 270 C130 255 105 260 85 285 C115 285 142 280 159 274" fill="#0F3A2B" stroke="#C9A13B" stroke-linejoin="round" stroke-width="1.2"/>
          <path d="M159 272 Q122 268 85 285" stroke="#C9A13B" stroke-width=".75" opacity=".7"/>
          <path d="M160 305 C190 290 215 295 235 320 C205 320 178 315 160 309" fill="#0F3A2B" stroke="#C9A13B" stroke-linejoin="round" stroke-width="1.2"/>
          <path d="M160 307 Q198 303 235 320" stroke="#C9A13B" stroke-width=".75" opacity=".7"/>
          <path d="M160 185 C130 180 80 145 68 100 C92 110 135 145 160 185 Z" fill="#F8F5EC" stroke="#C9A13B" stroke-linejoin="round" stroke-width="1.2"/>
          <path d="M160 185 Q115 145 68 100" stroke="#C9A13B" stroke-width=".65" opacity=".65"/>
          <path d="M160 185 C190 180 240 145 252 100 C228 110 185 145 160 185 Z" fill="#F8F5EC" stroke="#C9A13B" stroke-linejoin="round" stroke-width="1.2"/>
          <path d="M160 185 Q205 145 252 100" stroke="#C9A13B" stroke-width=".65" opacity=".65"/>
          <path d="M160 195 C142 150 132 85 160 25 C188 85 178 150 160 195 Z" fill="#F8F5EC" stroke="#C9A13B" stroke-linejoin="round" stroke-width="1.3"/>
          <path d="M160 195 L160 25" stroke="#C9A13B" stroke-width=".85" opacity=".8"/>
          <path d="M160 140 Q150 100 154 60" stroke="#C9A13B" stroke-width=".5" opacity=".5"/>
          <path d="M160 140 Q170 100 166 60" stroke="#C9A13B" stroke-width=".5" opacity=".5"/>
          <path d="M160 195 C135 190 95 195 80 220 C105 210 140 205 160 195 Z" fill="#F8F5EC" stroke="#C9A13B" stroke-linejoin="round" stroke-width="1.2"/>
          <path d="M160 195 Q120 200 80 220" stroke="#C9A13B" stroke-width=".65" opacity=".6"/>
          <path d="M160 195 C185 190 225 195 240 220 C215 210 180 205 160 195 Z" fill="#F8F5EC" stroke="#C9A13B" stroke-linejoin="round" stroke-width="1.2"/>
          <path d="M160 195 Q200 200 240 220" stroke="#C9A13B" stroke-width=".65" opacity=".6"/>
          <g stroke="#C9A13B" stroke-linecap="round">
            <path d="M160 180 Q145 130 130 90"/><ellipse cx="129" cy="88" rx="4" ry="2" fill="#C9A13B" transform="rotate(-30 129 88)"/>
            <path d="M160 180 Q152 115 148 70"/><ellipse cx="148" cy="68" rx="4" ry="2" fill="#C9A13B" transform="rotate(-10 148 68)"/>
            <path d="M160 180 Q160 105 160 60" stroke-width="1.3"/><circle cx="160" cy="58" r="3.2" fill="#C9A13B"/>
            <path d="M160 180 Q168 115 172 70"/><ellipse cx="172" cy="68" rx="4" ry="2" fill="#C9A13B" transform="rotate(10 172 68)"/>
            <path d="M160 180 Q175 130 190 90"/><ellipse cx="191" cy="88" rx="4" ry="2" fill="#C9A13B" transform="rotate(30 191 88)"/>
          </g>
        </svg>
      </div>
    </div>
    <p class="bc-kicker bc-kicker-light">Our patron</p>
    <h2 class="bc-h2" id="bcPatronTitle">The Lily of St. Joseph</h2>
    <p class="bc-patron-text">The lily is the sign of St. Joseph: quiet integrity, faithful work and care for those entrusted to him. It is the standard we hold up to our learners and to ourselves.</p>
    <ul class="bc-badges" aria-label="Values of St. Joseph">
      <li><i class="fas fa-check-circle" aria-hidden="true"></i>Patron St. Joseph</li>
      <li><i class="fas fa-tools" aria-hidden="true"></i>Faithful work</li>
      <li><i class="fas fa-shield-alt" aria-hidden="true"></i>Quiet integrity</li>
    </ul>
  </div>
</section>

<!-- 7. LEARNER'S JOURNEY -->
<section class="bc-journey" id="journey" aria-labelledby="bcJourneyTitle">
  <div class="bc-wrap">
    <div data-reveal>
      <p class="bc-kicker bc-kicker-gold">The learner&rsquo;s journey</p>
      <h2 class="bc-h2" id="bcJourneyTitle">From the first day of Nursery to Grade 10.</h2>
    </div>

    <!-- Desktop: path + tabs + panel -->
    <div class="bc-journey-desktop">
      <div class="bc-track" id="bcTrack">
        <svg class="bc-track-svg" viewBox="0 0 800 80" fill="none" aria-hidden="true" focusable="false">
          <path class="bc-track-guide" d="M 50 40 Q 250 10 450 40 T 750 40"/>
          <path class="bc-track-draw" id="bcTrackPath" d="M 50 40 Q 250 10 450 40 T 750 40"/>
          <?php $fallbackXY = array(array(50,40), array(283,24), array(517,45), array(750,40)); ?>
          <?php foreach ($stages as $i => $s): ?>
          <g class="bc-node" data-stage="<?php echo $i; ?>">
            <circle class="bc-node-ring" cx="<?php echo $fallbackXY[$i][0]; ?>" cy="<?php echo $fallbackXY[$i][1]; ?>" r="14"/>
            <circle class="bc-node-dot" cx="<?php echo $fallbackXY[$i][0]; ?>" cy="<?php echo $fallbackXY[$i][1]; ?>" r="7"/>
          </g>
          <?php endforeach; ?>
        </svg>
        <div class="bc-track-labels">
          <?php foreach ($stages as $i => $s): ?>
            <button type="button" class="bc-track-label" data-stage="<?php echo $i; ?>" tabindex="-1" aria-hidden="true"><?php echo $s['short']; ?></button>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="bc-journey-grid">
        <div class="bc-tabs" role="tablist" aria-label="Stages of learning" aria-orientation="vertical">
          <?php foreach ($stages as $i => $s): $on = $i === 0; ?>
          <button type="button" class="bc-tab" role="tab" id="bcTab<?php echo $i; ?>" aria-controls="bcPanel<?php echo $i; ?>" aria-selected="<?php echo $on ? 'true' : 'false'; ?>" tabindex="<?php echo $on ? '0' : '-1'; ?>" data-stage="<?php echo $i; ?>">
            <span>
              <span class="bc-tab-no">Stage <?php echo sprintf('%02d', $i + 1); ?></span>
              <span class="bc-tab-name"><?php echo $s['name']; ?></span>
              <span class="bc-tab-meta"><?php echo $s['meta']; ?></span>
            </span>
            <?php echo bc_arrow(); ?>
          </button>
          <?php endforeach; ?>
        </div>
        <div class="bc-panel-box">
          <?php foreach ($stages as $i => $s): ?>
          <div class="bc-panel" role="tabpanel" id="bcPanel<?php echo $i; ?>" aria-labelledby="bcTab<?php echo $i; ?>" tabindex="0"<?php echo $i === 0 ? '' : ' hidden'; ?>>
            <p class="bc-panel-top"><span class="bc-badge-green"><?php echo $s['badge']; ?></span><span class="bc-kicker bc-kicker-gold">Stage <?php echo $s['word']; ?></span></p>
            <h3 class="bc-panel-title"><?php echo $s['name']; ?></h3>
            <p class="bc-panel-meta"><?php echo $s['meta']; ?></p>
            <p class="bc-panel-text"><?php echo $s['text']; ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Mobile / tablet: vertical timeline -->
    <div class="bc-vline" id="bcVline">
      <div class="bc-vline-track" aria-hidden="true"><span class="bc-vline-fill" id="bcVfill"></span></div>
      <ol class="bc-vline-list">
        <?php foreach ($stages as $i => $s): ?>
        <li class="bc-vstage<?php echo $i === 0 ? ' is-active' : ''; ?>" data-stage="<?php echo $i; ?>">
          <span class="bc-vnode" aria-hidden="true"></span>
          <div class="bc-vcard">
            <p class="bc-kicker">Stage <?php echo sprintf('%02d', $i + 1); ?> &middot; <?php echo $s['group']; ?></p>
            <h3 class="bc-vtitle"><?php echo $s['name']; ?></h3>
            <p class="bc-vmeta"><?php echo $s['meta']; ?></p>
            <p class="bc-vtext"><?php echo $s['text']; ?></p>
          </div>
        </li>
        <?php endforeach; ?>
      </ol>
    </div>
  </div>
</section>

<!-- 8. NEWS -->
<?php if (bc_on('news')): ?>
<section class="bc-section" id="news" aria-labelledby="bcNewsTitle">
  <div class="bc-wrap">
    <div class="bc-news-head" data-reveal>
      <div>
        <p class="bc-kicker">The School Chronicle</p>
        <h2 class="bc-h2" id="bcNewsTitle">News &amp; Announcements</h2>
      </div>
      <a class="bc-news-more" href="<?php echo $SJCS_FB; ?>" target="_blank" rel="noopener">Follow on Facebook <?php echo bc_arrow(); ?></a>
    </div>

    <div class="bc-news-grid">
      <article class="bc-lead" data-reveal>
        <figure class="bc-media bc-ratio-16-10 bc-clip" data-lightbox data-cat="<?php echo sjcs_e($lead['cat']); ?>" data-title="<?php echo sjcs_e($lead['title']); ?>" data-text="<?php echo sjcs_e($lead['deck']); ?>">
          <?php if (!empty($lead['img'])): ?>
          <img src="<?php echo sjcs_img($lead['img']); ?>" alt="<?php echo sjcs_e($lead['alt']); ?>" width="1440" height="1080" loading="lazy" decoding="async" style="object-position:<?php echo $lead['pos']; ?>">
          <?php endif; ?>
          <span class="bc-media-tag"><?php echo sjcs_e($lead['cat']); ?></span>
        </figure>
        <p class="bc-date"><?php echo bc_date($lead['date']); ?></p>
        <h3 class="bc-lead-title"><?php if (!empty($lead['href'])): ?><a href="<?php echo $lead['href']; ?>"><?php echo sjcs_e($lead['title']); ?></a><?php else: echo sjcs_e($lead['title']); endif; ?></h3>
        <?php if (!empty($lead['deck'])): ?><p class="bc-lead-deck"><?php echo sjcs_e($lead['deck']); ?></p><?php endif; ?>
        <a class="bc-link-arrow" href="<?php echo !empty($lead['href']) ? $lead['href'] : $SJCS_FB; ?>"<?php echo empty($lead['href']) ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo !empty($lead['href']) ? 'Read full story' : 'Read more on Facebook'; ?> <?php echo bc_arrow(); ?></a>
      </article>

      <div class="bc-feed">
        <?php foreach ($side as $i => $s): ?>
        <article class="bc-feed-item" data-reveal style="--d:<?php echo 80 + $i * 80; ?>ms">
          <figure class="bc-media bc-ratio-16-6 bc-clip" data-lightbox data-cat="<?php echo sjcs_e($s['cat']); ?>" data-title="<?php echo sjcs_e($s['title']); ?>" data-text="<?php echo sjcs_e($s['deck']); ?>">
            <?php if (!empty($s['img'])): ?>
            <img src="<?php echo sjcs_img($s['img']); ?>" alt="<?php echo sjcs_e($s['alt']); ?>" width="1440" height="1080" loading="lazy" decoding="async" style="object-position:<?php echo $s['pos']; ?>">
            <?php endif; ?>
          </figure>
          <p class="bc-feed-meta"><span class="bc-feed-cat"><?php echo sjcs_e($s['cat']); ?></span><span><?php echo bc_date($s['date']); ?></span></p>
          <h3 class="bc-feed-title"><?php if (!empty($s['href'])): ?><a href="<?php echo $s['href']; ?>"><?php echo sjcs_e($s['title']); ?></a><?php else: echo sjcs_e($s['title']); endif; ?></h3>
          <?php if (!empty($s['deck'])): ?><p class="bc-feed-deck"><?php echo sjcs_e($s['deck']); ?></p><?php endif; ?>
        </article>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Featured video (real school video in assets/home/) -->
    <div class="bc-video" id="multimedia" data-reveal>
      <div class="bc-video-frame bc-ratio-16-9">
        <video controls playsinline preload="none" poster="<?php echo sjcs_img('assets/home/teaser-poster.jpg'); ?>" width="1280" height="720" aria-label="Featured video: Presbyteral Ordination of Rev. Faustino G. Cede&ntilde;o Jr.">
          <source src="<?php echo sjcs_img('assets/home/teaser.mp4'); ?>" type="video/mp4">
        </video>
      </div>
      <div class="bc-video-copy">
        <p class="bc-kicker">Featured video</p>
        <h3 class="bc-video-title">Presbyteral Ordination of Rev. Faustino G. Cede&ntilde;o Jr.</h3>
        <p class="bc-video-text">A milestone for our parish community. The St. Joseph family joined the faithful of Sagay in celebrating the ordination, a moment of thanksgiving for the gift of vocation and service to the Church.</p>
        <p class="bc-feed-meta"><span class="bc-feed-cat">St. Joseph Parish, Sagay</span><span>Diocese of San Carlos</span></p>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 9. CAMPUS LIFE -->
<section class="bc-campus" id="campus" aria-labelledby="bcCampusTitle">
  <div class="bc-wrap">
    <div class="bc-sec-head" data-reveal>
      <div>
        <p class="bc-kicker">Campus life</p>
        <h2 class="bc-h2" id="bcCampusTitle">A school day, from prayer to play.</h2>
      </div>
      <p class="bc-sec-intro">Moments from our activities, competitions and gatherings.</p>
    </div>
    <div class="bc-campus-grid">
      <?php foreach ($campus as $i => $c): ?>
      <figure class="bc-card bc-photo bc-photo-<?php echo $i + 1; ?>" data-reveal style="--d:<?php echo $i * 80; ?>ms">
        <div class="bc-media bc-ratio-<?php echo $c['ratio']; ?> bc-clip" data-lightbox data-cat="Campus Life" data-title="<?php echo sjcs_e($c['cap']); ?>" data-text=""<?php echo $i === 0 ? ' data-parallax-img="0.04"' : ''; ?>>
          <img src="<?php echo sjcs_img($c['img']); ?>" alt="<?php echo sjcs_e($c['alt']); ?>" width="<?php echo $c['w']; ?>" height="<?php echo $c['h']; ?>" loading="lazy" decoding="async">
        </div>
        <figcaption class="bc-photo-cap"><span><?php echo sjcs_e($c['cap']); ?></span><span class="bc-photo-tag"><?php echo sjcs_e($c['tag']); ?></span></figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 10. PORTALS -->
<?php if (bc_on('portals')): ?>
<section class="bc-section" id="portals" aria-labelledby="bcPortalsTitle">
  <div class="bc-wrap">
    <div class="bc-portal-head" data-reveal>
      <p class="bc-kicker">The school portals</p>
      <h2 class="bc-h2" id="bcPortalsTitle">Six doors, one school.</h2>
      <p class="bc-sec-intro">Staff, students and applicants each have their own portal. Choose the one that fits you.</p>
    </div>
    <div class="bc-portal-grid">
      <?php foreach ($portals as $i => $p): ?>
      <article class="bc-card bc-portal" data-reveal style="--d:<?php echo ($i % 3) * 60; ?>ms">
        <div>
          <div class="bc-portal-top">
            <span class="bc-portal-icon<?php echo $p['solid'] ? '' : ' is-light'; ?>" aria-hidden="true"><i class="fas <?php echo $p['icon']; ?>"></i></span>
            <span class="bc-portal-tag<?php echo $i === 0 ? ' is-open' : ''; ?>"><?php echo sjcs_e($p['tag']); ?></span>
          </div>
          <h3 class="bc-h3 bc-h3-sm"><?php echo sjcs_e($p['name']); ?></h3>
          <p class="bc-portal-text"><?php echo sjcs_e($p['text']); ?></p>
        </div>
        <a class="bc-portal-action<?php echo $i === 0 ? ' is-green' : ''; ?>" href="<?php echo $p['href']; ?>"><span><?php echo sjcs_e($p['action']); ?><span class="bc-sr"> &mdash; <?php echo sjcs_e($p['name']); ?></span></span><?php echo bc_arrow(); ?></a>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 11. CONTACT -->
<section class="bc-contact" id="contact" aria-labelledby="bcContactTitle">
  <div class="bc-wrap">
    <div data-reveal>
      <p class="bc-kicker">Location &amp; contact</p>
      <h2 class="bc-h2" id="bcContactTitle">Visit, write, or follow along.</h2>
    </div>
    <div class="bc-contact-grid">
      <article class="bc-card bc-contact-card" data-reveal>
        <div>
          <p class="bc-contact-kicker"><i class="fas fa-map-marker-alt" aria-hidden="true"></i><span class="bc-kicker">Campus</span></p>
          <h3 class="bc-h3">Visit Us</h3>
          <p class="bc-contact-text"><?php echo $SJCS_ADDR; ?></p>
        </div>
        <div class="bc-card-foot"><a class="bc-link-arrow" href="<?php echo $SJCS_MAP; ?>" target="_blank" rel="noopener">Open in Google Maps <i class="fas fa-external-link-alt bc-arrow" aria-hidden="true"></i><span class="bc-sr"> (opens in a new tab)</span></a></div>
      </article>
      <article class="bc-card bc-contact-card" data-reveal style="--d:80ms">
        <div>
          <p class="bc-contact-kicker"><i class="fas fa-envelope" aria-hidden="true"></i><span class="bc-kicker">Email</span></p>
          <h3 class="bc-h3">Email Us</h3>
          <p class="bc-contact-mail"><?php echo $SJCS_MAIL1; ?><br><?php echo $SJCS_MAIL2; ?></p>
        </div>
        <div class="bc-card-foot"><button type="button" class="bc-link-arrow" id="bcEmailBtn" aria-haspopup="dialog">Choose an address <?php echo bc_arrow(); ?></button></div>
      </article>
      <article class="bc-card bc-contact-card" data-reveal style="--d:160ms">
        <div>
          <p class="bc-contact-kicker"><i class="fab fa-facebook-f" aria-hidden="true"></i><span class="bc-kicker">Bulletins</span></p>
          <h3 class="bc-h3">Follow Our News</h3>
          <p class="bc-contact-text">St. Joseph Catholic School of Sagay, Inc. &middot; News, announcements and photos</p>
        </div>
        <div class="bc-card-foot"><a class="bc-link-arrow" href="<?php echo $SJCS_FB; ?>" target="_blank" rel="noopener">Official Facebook Page <i class="fas fa-external-link-alt bc-arrow" aria-hidden="true"></i><span class="bc-sr"> (opens in a new tab)</span></a></div>
      </article>
    </div>
  </div>
</section>

<!-- 12. CALL TO ACTION -->
<section class="bc-section bc-cta-section" id="apply" aria-labelledby="bcCtaTitle">
  <div class="bc-wrap">
    <div class="bc-cta" data-reveal>
      <div class="bc-cta-mark" aria-hidden="true" data-parallax="0.1">
        <svg viewBox="0 0 100 100" fill="none"><line x1="50" y1="5" x2="50" y2="95" stroke="currentColor" stroke-width="2" stroke-dasharray="4 8"/><line x1="20" y1="35" x2="80" y2="35" stroke="currentColor" stroke-width="2" stroke-dasharray="4 8"/></svg>
      </div>
      <div class="bc-cta-copy">
        <p class="bc-kicker bc-kicker-light">Admissions &middot; S.Y. 2026&ndash;2027</p>
        <h2 class="bc-h2" id="bcCtaTitle">Begin your journey at St. Joseph.</h2>
        <p class="bc-cta-text">We welcome new learners from Nursery 1 to Grade 10, including transferees and returning students.</p>
      </div>
      <div class="bc-cta-actions">
        <a class="bc-btn bc-btn-cream" href="<?php echo WEB_ROOT; ?>apply.php"><span>Apply Now</span><?php echo bc_arrow(); ?></a>
        <a class="bc-btn bc-btn-ghost-light" href="#programs">Explore Programs</a>
      </div>
    </div>
  </div>
</section>

</main>

<!-- 13. FOOTER -->
<footer class="bc-footer">
  <div class="bc-wrap">
    <div class="bc-footer-top">
      <div class="bc-footer-brand">
        <p class="bc-brand">
          <img class="bc-footer-seal" src="<?php echo WEB_ROOT; ?>csr-scc.png" alt="St. Joseph Catholic School of Sagay, Inc. seal" width="44" height="44" loading="lazy">
          <span class="bc-footer-name">St. Joseph Catholic School</span>
        </p>
        <p class="bc-kicker bc-kicker-gold">Wisdom &middot; Virtues &middot; Faith</p>
        <p class="bc-footer-quote">&ldquo;Education with Faith, Excellence, and Purpose.&rdquo;</p>
      </div>
      <div class="bc-footer-col">
        <p class="bc-footer-title">Explore</p>
        <ul>
          <li><a href="#top">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#programs">Programs</a></li>
          <li><a href="#news">News</a></li>
          <li><a href="#campus">Campus Life</a></li>
          <li><a href="#multimedia">Multimedia</a></li>
        </ul>
      </div>
      <div class="bc-footer-col">
        <p class="bc-footer-title">Quick links</p>
        <ul>
          <li><a href="<?php echo WEB_ROOT; ?>apply.php">Apply for Admission</a></li>
          <li><a href="<?php echo WEB_ROOT; ?>portals.php">School Portals</a></li>
          <li><a href="<?php echo WEB_ROOT; ?>student-portal.php">Student Portal</a></li>
          <li><a href="<?php echo WEB_ROOT; ?>login.php">Staff Sign In</a></li>
        </ul>
      </div>
      <div class="bc-footer-col bc-footer-contact">
        <p class="bc-footer-title">Contact us</p>
        <address><?php echo str_replace(', Sagay', ',<br>Sagay', $SJCS_ADDR); ?></address>
        <ul>
          <li><a href="https://mail.google.com/mail/?view=cm&amp;fs=1&amp;to=<?php echo rawurlencode($SJCS_MAIL1); ?>" target="_blank" rel="noopener">Email the school</a></li>
          <li><a href="<?php echo $SJCS_MAP; ?>" target="_blank" rel="noopener">Get directions</a></li>
          <li><a href="<?php echo $SJCS_FB; ?>" target="_blank" rel="noopener">Facebook page</a></li>
        </ul>
      </div>
    </div>
    <div class="bc-footer-bottom">
      <p>&copy; <?php echo date('Y'); ?> St. Joseph Catholic School of Sagay, Inc. &middot; Diocese of San Carlos &middot; All rights reserved.</p>
      <p class="bc-footer-tags"><span>Est. <?php echo $FOUNDED; ?></span><span>Sagay City</span><span>Diocese of San Carlos</span></p>
    </div>
  </div>
</footer>

<!-- Email address chooser -->
<div class="bc-modal" id="bcEmailModal" hidden>
  <div class="bc-modal-backdrop" data-close></div>
  <div class="bc-modal-box" role="dialog" aria-modal="true" aria-labelledby="bcEmailTitle">
    <button type="button" class="bc-icon-btn bc-modal-x" data-close aria-label="Close"><i class="fas fa-times" aria-hidden="true"></i></button>
    <h2 class="bc-modal-title" id="bcEmailTitle">Which address would you like to email?</h2>
    <p class="bc-modal-note">Your message opens in Gmail. Pick the one that fits your concern.</p>
    <a class="bc-modal-opt" href="https://mail.google.com/mail/?view=cm&amp;fs=1&amp;to=<?php echo rawurlencode($SJCS_MAIL1); ?>" target="_blank" rel="noopener">
      <span><strong><?php echo $SJCS_MAIL1; ?></strong><em>General inquiries and enrollment</em></span><?php echo bc_arrow(); ?>
    </a>
    <a class="bc-modal-opt" href="https://mail.google.com/mail/?view=cm&amp;fs=1&amp;to=<?php echo rawurlencode($SJCS_MAIL2); ?>" target="_blank" rel="noopener">
      <span><strong><?php echo $SJCS_MAIL2; ?></strong><em>School office and records</em></span><?php echo bc_arrow(); ?>
    </a>
  </div>
</div>

<!-- Photo preview -->
<div class="bc-lightbox" id="bcLightbox" hidden>
  <div class="bc-modal-backdrop" data-close></div>
  <figure class="bc-lb-box" role="dialog" aria-modal="true" aria-labelledby="bcLbTitle">
    <button type="button" class="bc-icon-btn bc-lb-x" data-close aria-label="Close preview"><i class="fas fa-times" aria-hidden="true"></i></button>
    <div class="bc-lb-stage"><img id="bcLbImg" alt=""></div>
    <figcaption class="bc-lb-info">
      <p class="bc-kicker" id="bcLbCat"></p>
      <h2 class="bc-lb-title" id="bcLbTitle"></h2>
      <p class="bc-lb-text" id="bcLbText"></p>
      <div class="bc-lb-nav">
        <button type="button" class="bc-icon-btn" id="bcLbPrev" aria-label="Previous photo"><i class="fas fa-chevron-left" aria-hidden="true"></i></button>
        <span id="bcLbCount"></span>
        <button type="button" class="bc-icon-btn" id="bcLbNext" aria-label="Next photo"><i class="fas fa-chevron-right" aria-hidden="true"></i></button>
      </div>
    </figcaption>
  </figure>
</div>

</body>
</html>
