<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Administrator dashboard.
// Basic-education school (Nursery to Junior High School). Layout follows the
// AdminLTE v4 dashboard pattern (info boxes, recap chart + progress rail,
// donut with legend, accent tiles, activity table) in the school palette.
// Styles are scoped here so the page is correct even if theme/custom.css
// has not been copied yet.

global $mydb;

function dh_has($t)   { global $mydb; return $mydb->tableExists($t); }
function dh_cnt($q)   { global $mydb; $mydb->setQuery($q); return (int)$mydb->num_rows(); }
function dh_rows($q)  { global $mydb; $mydb->setQuery($q); return $mydb->loadResultList(); }
function dh_one($q)   { global $mydb; $mydb->setQuery($q); return $mydb->loadSingleResult(); }
function dh_e($v)     { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function dh_peso($n)  { return 'P'.number_format((float)$n, 2); }

/* ---- active school year ---- */
$activeSY = null; $activeSYID = 0;
if (dh_has('tblschoolyear')) {
    $r = dh_one("SELECT SY_ID, SCHOOL_YEAR FROM `tblschoolyear` WHERE STATUS='Active' ORDER BY SY_ID DESC LIMIT 1");
    if ($r) { $activeSY = $r->SCHOOL_YEAR; $activeSYID = (int)$r->SY_ID; }
}
$syF = $activeSYID > 0 ? " AND SY_ID='".$activeSYID."' " : "";

/* ---- headline figures ---- */
$totalStudents = dh_has('tblstudent')  ? dh_cnt("SELECT S_ID FROM `tblstudent`") : 0;
$totalSections = dh_has('tblsections') ? dh_cnt("SELECT SECTION_ID FROM `tblsections`") : 0;
$totalSubjects = dh_has('tblsubjects') ? dh_cnt("SELECT SUBJECT_ID FROM `tblsubjects`") : 0;
$enrolledNow = 0; $inProgress = 0; $totalRecords = 0;
if (dh_has('tblenrollment')) {
    $enrolledNow  = dh_cnt("SELECT ENROLLMENT_ID FROM `tblenrollment` WHERE STATUS='Enrolled' ".$syF);
    $inProgress   = dh_cnt("SELECT ENROLLMENT_ID FROM `tblenrollment` WHERE STATUS IN ('Registered','Assigned','Sectioned','Paid') ".$syF);
    $totalRecords = dh_cnt("SELECT ENROLLMENT_ID FROM `tblenrollment` WHERE 1=1 ".$syF);
}
$pendingApplicants = dh_has('tblapplicants')
    ? dh_cnt("SELECT APPLICANT_ID FROM `tblapplicants` WHERE STATUS NOT IN ('Enrolled','Rejected')") : 0;

/* ---- collections ---- */
$collected = 0;
if (dh_has('tblpayments')) {
    $mydb->setQuery("SHOW COLUMNS FROM `tblpayments` LIKE 'STATUS'");
    $hasStatus = ($mydb->num_rows() > 0);
    $w = $hasStatus ? " WHERE STATUS='Approved' " : "";
    $r = dh_one("SELECT COALESCE(SUM(AMOUNT),0) AS T FROM `tblpayments` ".$w);
    $collected = $r ? (float)$r->T : 0;
}

/* ---- pipeline ---- */
$stages = array(
    'Registered' => '#9AA8A1',
    'Assigned'   => '#4FA98C',
    'Sectioned'  => '#008060',
    'Paid'       => '#C9922B',
    'Enrolled'   => '#95BF47',
);
$pipeline = array(); $pipeTotal = 0;
foreach ($stages as $k => $c) {
    $n = dh_has('tblenrollment') ? dh_cnt("SELECT ENROLLMENT_ID FROM `tblenrollment` WHERE STATUS='".$k."' ".$syF) : 0;
    $pipeline[$k] = $n; $pipeTotal += $n;
}

/* ---- per grade level: enrolled vs still processing ---- */
$levels = array();
if (dh_has('tblenrollment')) {
    foreach (dh_rows("SELECT e.YEAR_LEVEL,
                             SUM(CASE WHEN e.STATUS='Enrolled' THEN 1 ELSE 0 END) AS DONE,
                             SUM(CASE WHEN e.STATUS IN ('Registered','Assigned','Sectioned','Paid') THEN 1 ELSE 0 END) AS BUSY,
                             COALESCE(MIN(c.LEVEL_ORDER), 99) AS ORD
                      FROM `tblenrollment` e
                      LEFT JOIN `tblcourses` c ON c.COURSE_ID = e.COURSE_ID
                      WHERE 1=1 ".str_replace('SY_ID','e.SY_ID',$syF)."
                      GROUP BY e.YEAR_LEVEL
                      ORDER BY ORD ASC, e.YEAR_LEVEL ASC") as $l) {
        $levels[] = array('lbl'=>$l->YEAR_LEVEL, 'done'=>(int)$l->DONE, 'busy'=>(int)$l->BUSY);
    }
}
$lvlLabels = array(); $lvlDone = array(); $lvlBusy = array();
foreach ($levels as $l) { $lvlLabels[] = $l['lbl']; $lvlDone[] = $l['done']; $lvlBusy[] = $l['busy']; }

/* Short axis codes: "Grade 10" is too wide to sit horizontally 13 times
   across the chart, so the axis shows G10 and the tooltip keeps the full
   name. Nursery 1 -> N1, Kindergarten -> K, Grade 1 -> G1. */
function dh_levelcode($label) {
    $t = trim($label);
    if (preg_match('/^Grade\s*(\d+)/i', $t, $m))   { return 'G'.$m[1]; }
    if (preg_match('/^Nursery\s*(\d+)/i', $t, $m)) { return 'N'.$m[1]; }
    if (stripos($t, 'kinder') === 0)                 { return 'K'; }
    return strtoupper(substr($t, 0, 3));
}
$lvlShort = array();
foreach ($lvlLabels as $lb) { $lvlShort[] = dh_levelcode($lb); }

/* ---- three-year trend, by department ----
   Grouped by the LEVEL_ORDER ladder rather than the YEAR_LEVEL text, and
   limited to the three most recent school years so the chart stays legible. */
$trendYears = array(); $trendData = array();
$depts = array('Early Childhood', 'Elementary', 'Junior High School');
if (dh_has('tblschoolyear') && dh_has('tblenrollment')) {
    $syRows = dh_rows("SELECT SY_ID, SCHOOL_YEAR FROM `tblschoolyear`
                       ORDER BY SCHOOL_YEAR DESC LIMIT 3");
    $syRows = array_reverse($syRows);
    foreach ($depts as $d) { $trendData[$d] = array(); }
    foreach ($syRows as $sy) {
        $trendYears[] = $sy->SCHOOL_YEAR;
        $found = array();
        foreach (dh_rows("SELECT COALESCE(c.DEPARTMENT,'Other') AS DEPT, COUNT(*) AS N
                          FROM `tblenrollment` e
                          LEFT JOIN `tblcourses` c ON c.COURSE_ID = e.COURSE_ID
                          WHERE e.SY_ID = '".(int)$sy->SY_ID."' AND e.STATUS = 'Enrolled'
                          GROUP BY DEPT") as $t) {
            $found[$t->DEPT] = (int)$t->N;
        }
        foreach ($depts as $d) { $trendData[$d][] = isset($found[$d]) ? $found[$d] : 0; }
    }
}
$hasTrend = (count($trendYears) > 1);

/* ---- recent activity ---- */
$recent = array();
if (dh_has('tblenrollment') && dh_has('tblstudent')) {
    $recent = dh_rows("SELECT e.ENROLLMENT_ID, e.YEAR_LEVEL, e.STATUS, s.IDNO, s.LNAME, s.FNAME, sec.SECTION_NAME
        FROM `tblenrollment` e
        JOIN `tblstudent` s ON s.S_ID = e.S_ID
        LEFT JOIN `tblsections` sec ON sec.SECTION_ID = e.SECTION_ID
        ORDER BY e.ENROLLMENT_ID DESC LIMIT 7");
}
/* ---- learners by province: drives the Philippine map ----
   PROVINCE is added by database/ph_location_update.sql. The dashboard
   checks for the column first, so this page still renders correctly on an
   install where that migration has not been run yet. */
$hasProvince = false;
if (dh_has('tblstudent')) {
    $mydb->setQuery("SHOW COLUMNS FROM `tblstudent` LIKE 'PROVINCE'");
    $hasProvince = ($mydb->num_rows() > 0);
}
$provCounts = array(); $provTotal = 0; $provUnknown = 0;
if ($hasProvince) {
    foreach (dh_rows("SELECT TRIM(`PROVINCE`) AS P, COUNT(*) AS N
                      FROM `tblstudent`
                      WHERE `PROVINCE` IS NOT NULL AND `PROVINCE` <> ''
                      GROUP BY 1 ORDER BY N DESC, P ASC") as $r) {
        $provCounts[$r->P] = (int)$r->N;
        $provTotal += (int)$r->N;
    }
    $r = dh_one("SELECT COUNT(*) AS N FROM `tblstudent` WHERE `PROVINCE` IS NULL OR `PROVINCE` = ''");
    $provUnknown = $r ? (int)$r->N : 0;
}
$provMax = !empty($provCounts) ? max($provCounts) : 0;

/* ---- the school's own location, for the pin on the map ----
   Sitio Palanas, Brgy. Poblacion II, Sagay City, Negros Occidental.
   These coordinates are approximate. To make them exact: open Google Maps,
   right-click the school building, click the lat/long that appears at the
   top of the menu (it copies), and paste the two numbers here. */
$schoolLat   = 10.9440;
$schoolLng   = 123.4258;
$schoolName  = 'St. Joseph Catholic School of Sagay, Inc.';
$schoolAddr  = 'Sitio Palanas, Brgy. Poblacion II, Sagay City, Negros Occidental';

function dh_badge($s) {
    $m = array('Registered'=>'secondary','Assigned'=>'info','Sectioned'=>'primary','Paid'=>'warning',
               'Enrolled'=>'success','Reserved'=>'secondary','Dropped'=>'danger','Completed'=>'dark');
    return isset($m[$s]) ? $m[$s] : 'secondary';
}
?>

<style>
/* ===== dashboard, AdminLTE v4 pattern in the school palette ===== */
.sjd{--d:#002D2D;--g:#008060;--l:#95BF47;--o:#5E8E3E;--a:#C9922B;--ln:#E3E9E4;--mu:#79857E;--ink:#12201C;}
.sjd .row{margin-left:-10px;margin-right:-10px;}
.sjd [class*="col-"]{padding-left:10px;padding-right:10px;}

/* welcome strip */
.sjd .sj-hello{background:linear-gradient(115deg,#002D2D 0%,#014038 58%,#008060 100%);color:#fff;
  border-radius:10px;padding:20px 24px;margin-bottom:20px;display:flex;align-items:center;
  justify-content:space-between;gap:16px;flex-wrap:wrap;}
.sjd .sj-hello .eyebrow{margin:0 0 4px;font-size:.66rem;letter-spacing:.18em;text-transform:uppercase;color:var(--l);}
.sjd .sj-hello h2{margin:0 0 3px;font-size:1.38rem;font-weight:700;}
.sjd .sj-hello p{margin:0;font-size:.88rem;color:rgba(255,255,255,.8);}
.sjd .sj-hello p a{color:var(--l);}
.sjd .sj-hello-act{display:flex;align-items:center;gap:9px;flex-wrap:wrap;}
.sjd .sj-hello .btn-print{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.35);color:#fff;
  font-weight:700;font-size:.78rem;letter-spacing:.06em;padding:9px 16px;border-radius:7px;cursor:pointer;
  text-transform:uppercase;white-space:nowrap;}
.sjd .sj-hello .btn-print:hover{background:rgba(255,255,255,.22);}
.sjd .sj-hello .btn-go{background:var(--l);color:var(--d);font-weight:700;font-size:.78rem;letter-spacing:.06em;
  text-transform:uppercase;padding:10px 18px;border-radius:6px;text-decoration:none;white-space:nowrap;}

/* info boxes (icon left, label + value right) */
.sjd .sj-info{display:flex;align-items:center;gap:14px;background:#fff;border:1px solid var(--ln);
  border-radius:10px;padding:14px 16px;margin-bottom:20px;text-decoration:none;color:var(--ink);
  transition:box-shadow .18s ease,transform .18s ease;}
.sjd .sj-info:hover{box-shadow:0 8px 20px rgba(0,45,45,.1);transform:translateY(-2px);color:var(--ink);}
.sjd .sj-info .ic{width:56px;height:56px;flex:0 0 auto;border-radius:10px;display:flex;align-items:center;
  justify-content:center;color:#fff;font-size:1.35rem;}
.sjd .sj-info .bd{display:flex;flex-direction:column;min-width:0;}
.sjd .sj-info .t{display:block;font-size:.82rem;color:var(--mu);margin:0 0 3px;}
.sjd .sj-info .v{display:block;font-size:1.6rem;font-weight:700;color:var(--d);line-height:1.1;}
.sjd .bg-d{background:#0B5563;} .sjd .bg-g{background:var(--g);}
.sjd .bg-a{background:var(--a);} .sjd .bg-o{background:var(--o);}

/* cards */
.sjd .sj-card{background:#fff;border:1px solid var(--ln);border-radius:10px;margin-bottom:20px;}
.sjd .sj-card-h{display:flex;align-items:center;justify-content:space-between;gap:12px;
  padding:14px 18px;border-bottom:1px solid var(--ln);}
.sjd .sj-card-h h3{margin:0;font-size:1rem;font-weight:700;color:var(--d);}
.sjd .sj-card-h .meta{font-size:.72rem;text-transform:uppercase;letter-spacing:.08em;color:var(--mu);}
.sjd .sj-card-h a.meta{color:var(--g);font-weight:700;text-decoration:none;}
.sjd .sj-card-b{padding:18px;}
.sjd .sj-card-b.flush{padding:0;}
.sjd .chart{position:relative;height:262px;}
.sjd .chart.sm{height:236px;}
.sjd .sj-empty{color:var(--mu);font-size:.9rem;text-align:center;padding:30px 0;margin:0;}

/* progress rail */
.sjd .sj-goal{margin-bottom:14px;}
.sjd .sj-goal:last-child{margin-bottom:0;}
.sjd .sj-goal-t{display:flex;justify-content:space-between;align-items:baseline;margin-bottom:6px;
  font-size:.86rem;color:#42504A;}
.sjd .sj-goal-t b{color:var(--d);font-size:.92rem;}
.sjd .sj-goal-t b span{color:var(--mu);font-weight:400;}
.sjd .sj-track{height:7px;background:#EEF2EF;border-radius:99px;overflow:hidden;}
.sjd .sj-track i{display:block;height:100%;border-radius:99px;}

/* summary strip */
.sjd .sj-strip{display:grid;grid-template-columns:repeat(4,1fr);border-top:1px solid var(--ln);}
.sjd .sj-strip div{padding:14px 10px;text-align:center;border-right:1px solid var(--ln);}
.sjd .sj-strip div:last-child{border-right:none;}
.sjd .sj-strip b{display:block;font-size:1.15rem;font-weight:700;color:var(--d);line-height:1.2;}
.sjd .sj-strip span{font-size:.68rem;text-transform:uppercase;letter-spacing:.09em;color:var(--mu);}

/* accent tiles */
.sjd .sj-tile{display:flex;align-items:center;gap:14px;border-radius:10px;padding:16px 18px;
  margin-bottom:12px;color:#fff;text-decoration:none;}
.sjd .sj-tile:hover{color:#fff;opacity:.94;}
.sjd .sj-tile i.ico{font-size:1.5rem;opacity:.9;width:30px;text-align:center;}
.sjd .sj-tile .tl{font-size:.84rem;opacity:.9;}
.sjd .sj-tile .tv{font-size:1.2rem;font-weight:700;line-height:1.2;}

/* table */
.sjd table.sj-tbl{width:100%;margin:0;}
.sjd table.sj-tbl thead th{background:#F7FAF8;border-bottom:1px solid var(--ln);font-size:.7rem;
  text-transform:uppercase;letter-spacing:.07em;color:var(--mu);padding:11px 18px;font-weight:700;}
.sjd table.sj-tbl tbody td{padding:12px 18px;border-top:1px solid #EFF3F0;font-size:.9rem;vertical-align:middle;color:var(--ink);}
.sjd table.sj-tbl tbody tr:hover{background:#F9FBFA;}

/* quick links */
.sjd .sj-q{display:flex;align-items:center;gap:11px;padding:11px 12px;margin-bottom:8px;background:#fff;
  border:1px solid var(--ln);border-left:3px solid var(--l);border-radius:7px;color:var(--ink);
  text-decoration:none;font-size:.9rem;transition:transform .15s ease,background .15s ease;}
.sjd .sj-q:last-child{margin-bottom:0;}
.sjd .sj-q:hover{background:#F5F9F6;transform:translateX(3px);color:var(--ink);border-left-color:var(--g);}
.sjd .sj-q > i:first-child{color:var(--g);width:18px;text-align:center;}
.sjd .sj-q span{flex:1;}
.sjd .sj-q .ch{color:var(--mu);font-size:.72rem;}

/* ---------- Philippine map ---------- */
.sjd .sj-map{height:430px;border-radius:8px;background:#DCE6E1;border:1px solid var(--ln);}
/* Sea tone is deliberately darker than the land fill, so provinces with no
   learners still read as land instead of disappearing into the card. */
.sjd .sj-map .leaflet-container{background:#DCE6E1;border-radius:8px;font-family:inherit;}
.sjd .sj-map .sj-count{background:none;border:none;box-shadow:none;padding:0;margin:0;
  color:#FFFFFF;font-weight:800;font-size:.72rem;letter-spacing:.02em;
  text-shadow:0 0 3px rgba(0,45,45,.85),0 1px 2px rgba(0,45,45,.7);}
.sjd .sj-map .sj-count::before{display:none;}
.sjd .sj-map .leaflet-tooltip.sj-count{opacity:1;}
.sjd .sj-pin{background:none;border:none;}
.sjd .sj-pin i{font-size:1.55rem;color:var(--a);text-shadow:0 0 3px #fff,0 2px 5px rgba(0,45,45,.45);}
.sjd .sj-pin i.pulse{animation:sjPin 2.4s ease-out infinite;}
@keyframes sjPin{0%{transform:translateY(0);}50%{transform:translateY(-3px);}100%{transform:translateY(0);}}
.sjd .leaflet-popup-content-wrapper{border-radius:8px;box-shadow:0 8px 22px rgba(0,45,45,.18);}
.sjd .leaflet-popup-content{margin:11px 13px;font-size:.84rem;line-height:1.4;color:var(--ink);}
.sjd .leaflet-popup-content b{color:var(--d);display:block;margin-bottom:2px;}
.sjd .leaflet-popup-content span{color:var(--mu);font-size:.78rem;}
.sjd .sj-map .leaflet-control-zoom a{color:var(--d);border-color:var(--ln);font-weight:700;}
.sjd .sj-map .leaflet-control-zoom a:hover{background:#F5F9F6;color:var(--g);}
.sjd .sj-maptip{background:#fff;border:1px solid var(--ln);border-left:3px solid var(--g);border-radius:7px;
  padding:8px 11px;box-shadow:0 6px 16px rgba(0,45,45,.12);font-size:.84rem;line-height:1.35;color:var(--ink);}
.sjd .sj-maptip b{display:block;color:var(--d);font-size:.9rem;}
.sjd .sj-maptip span{color:var(--mu);font-size:.78rem;}
.sjd .sj-mapfoot{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;
  padding:11px 4px 0;font-size:.72rem;color:var(--mu);}
.sjd .sj-legend{display:flex;align-items:center;gap:7px;}
.sjd .sj-legend i{width:20px;height:9px;border-radius:2px;display:inline-block;}
.sjd .sj-mapreset{background:#fff;border:1px solid var(--ln);border-radius:6px;color:var(--g);
  font-weight:700;font-size:.72rem;padding:5px 11px;cursor:pointer;}
.sjd .sj-mapreset:hover{background:#F5F9F6;}
.sjd .sj-plist{list-style:none;margin:0;padding:0;}
.sjd .sj-plist li{display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid #EFF3F0;font-size:.88rem;}
.sjd .sj-plist li:last-child{border-bottom:none;}
.sjd .sj-plist .nm{flex:1;color:var(--ink);}
.sjd .sj-plist .ct{font-weight:700;color:var(--d);}
.sjd .sj-plist .bar{width:64px;height:6px;background:#EEF2EF;border-radius:99px;overflow:hidden;}
.sjd .sj-plist .bar i{display:block;height:100%;background:var(--g);border-radius:99px;}
.sjd .sj-plist .muted .nm,.sjd .sj-plist .muted .ct{color:var(--mu);}
.sjd .sj-note{background:#FBF6EA;border:1px solid #EBDCBB;border-left:3px solid var(--a);border-radius:7px;
  padding:12px 14px;font-size:.85rem;color:#6B5620;margin:0;}
.sjd .sj-note code{background:rgba(0,0,0,.05);padding:1px 5px;border-radius:3px;color:#5A4718;}
html.dark-mode-custom .sjd .sj-map,
html.dark-mode-custom .sjd .sj-map .leaflet-container{background:#16282300;background-color:#162823;}

/* ---------- printable report ----------
   On screen this block is hidden and the dashboard is shown. On paper it is
   the other way round: the widgets, charts, map and buttons are dropped and
   this plain report is printed under the letterhead from theme/template.php. */
.sjd .sj-print{ display:none; }
@media print {
  .sjd .sj-hello, .sjd .row, .sjd .sj-card, .sjd .sj-strip { display:none !important; }
  .sjd .sj-print { display:block !important; }
  .sjd .sj-print h3{ font-size:11pt; font-weight:bold; margin:16px 0 5px; padding-bottom:2px;
    border-bottom:1px solid #000; letter-spacing:.4px; }
  .sjd .sj-print table{ width:100%; border-collapse:collapse; font-size:9.5pt; margin-bottom:4px; }
  .sjd .sj-print th{ text-align:left; border-bottom:1px solid #000; padding:4px 6px; font-weight:bold; }
  .sjd .sj-print td{ border-bottom:1px solid #BBB; padding:4px 6px; }
  .sjd .sj-print td.n, .sjd .sj-print th.n{ text-align:right; }
  .sjd .sj-print .kv td{ border-bottom:1px dotted #999; }
  .sjd .sj-print .kv td.n{ font-weight:bold; }
  .sjd .sj-print .sign{ margin-top:42px; width:100%; }
  .sjd .sj-print .sign td{ border:none; padding-top:26px; font-size:9pt; text-align:center; width:50%; }
  .sjd .sj-print .sign .ln{ border-top:1px solid #000; padding-top:3px; margin:0 22px; }
  .sjd .sj-print .foot{ margin-top:22px; font-size:8pt; font-style:italic; color:#333; text-align:center; }
}

@media (max-width:576px){ .sjd .sj-strip{grid-template-columns:repeat(2,1fr);} }
</style>

<section class="content sjd">
  <div class="container-fluid">

    <?php /* ===== PRINT-ONLY REPORT =====
             Screen-only widgets are hidden by the @media print rules above,
             so what comes out of the printer is this document, not a
             screenshot of the dashboard. */ ?>
    <div class="sj-print">

      <table class="kv">
        <tr><td style="width:60%">School Year</td>
            <td class="n"><?php echo $activeSY ? dh_e($activeSY) : 'Not set'; ?></td></tr>
        <tr><td>Total Learners on record</td><td class="n"><?php echo number_format($totalStudents); ?></td></tr>
        <tr><td>Enrolled this School Year</td><td class="n"><?php echo number_format($enrolledNow); ?></td></tr>
        <tr><td>Still processing</td><td class="n"><?php echo number_format($inProgress); ?></td></tr>
        <tr><td>Enrollment records this School Year</td><td class="n"><?php echo number_format($totalRecords); ?></td></tr>
        <tr><td>Applicants pending</td><td class="n"><?php echo number_format($pendingApplicants); ?></td></tr>
        <tr><td>Sections</td><td class="n"><?php echo number_format($totalSections); ?></td></tr>
        <tr><td>Subjects</td><td class="n"><?php echo number_format($totalSubjects); ?></td></tr>
        <tr><td>Payments collected</td><td class="n"><?php echo dh_peso($collected); ?></td></tr>
      </table>

      <h3>Enrollment by Stage</h3>
      <table>
        <thead><tr><th>Stage</th><th class="n" style="width:16%">Records</th><th class="n" style="width:16%">Share</th></tr></thead>
        <tbody>
          <?php foreach ($pipeline as $stg => $cnt): ?>
          <tr>
            <td><?php echo dh_e($stg); ?></td>
            <td class="n"><?php echo number_format($cnt); ?></td>
            <td class="n"><?php echo $pipeTotal > 0 ? number_format(($cnt/$pipeTotal)*100, 1).'%' : '0.0%'; ?></td>
          </tr>
          <?php endforeach; ?>
          <tr><th>Total</th><th class="n"><?php echo number_format($pipeTotal); ?></th><th class="n">100.0%</th></tr>
        </tbody>
      </table>

      <?php if ($hasTrend): ?>
      <h3>Enrolment Trend by Department</h3>
      <table>
        <thead><tr><th>School Year</th>
          <?php foreach ($depts as $d): ?><th class="n"><?php echo dh_e($d); ?></th><?php endforeach; ?>
          <th class="n">Total</th></tr></thead>
        <tbody>
          <?php foreach ($trendYears as $i => $yr): $rowT = 0; ?>
          <tr>
            <td><?php echo dh_e($yr); ?></td>
            <?php foreach ($depts as $d): $rowT += $trendData[$d][$i]; ?>
              <td class="n"><?php echo $trendData[$d][$i]; ?></td>
            <?php endforeach; ?>
            <td class="n"><?php echo $rowT; ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>

      <h3>Enrollment by Grade Level</h3>
      <?php if (empty($levels)): ?>
        <p style="font-size:9.5pt;">No enrollment records for this school year.</p>
      <?php else: ?>
      <table>
        <thead><tr><th>Grade Level</th><th class="n" style="width:18%">Enrolled</th>
                   <th class="n" style="width:18%">In Progress</th><th class="n" style="width:14%">Total</th></tr></thead>
        <tbody>
          <?php $gd=0; $gb=0; foreach ($levels as $l): $gd += $l['done']; $gb += $l['busy']; ?>
          <tr>
            <td><?php echo dh_e($l['lbl']); ?></td>
            <td class="n"><?php echo $l['done']; ?></td>
            <td class="n"><?php echo $l['busy']; ?></td>
            <td class="n"><?php echo $l['done'] + $l['busy']; ?></td>
          </tr>
          <?php endforeach; ?>
          <tr><th>Total</th><th class="n"><?php echo $gd; ?></th><th class="n"><?php echo $gb; ?></th>
              <th class="n"><?php echo $gd+$gb; ?></th></tr>
        </tbody>
      </table>
      <?php endif; ?>

      <?php if ($hasProvince && ($provTotal > 0 || $provUnknown > 0)): ?>
      <h3>Learners by Province</h3>
      <table>
        <thead><tr><th>Province</th><th class="n" style="width:18%">Learners</th></tr></thead>
        <tbody>
          <?php foreach ($provCounts as $pn => $pc): ?>
          <tr><td><?php echo dh_e($pn); ?></td><td class="n"><?php echo $pc; ?></td></tr>
          <?php endforeach; ?>
          <?php if ($provUnknown > 0): ?>
          <tr><td>Unspecified</td><td class="n"><?php echo $provUnknown; ?></td></tr>
          <?php endif; ?>
        </tbody>
      </table>
      <?php endif; ?>

      <?php if (!empty($recent)): ?>
      <h3>Recent Enrollment Activity</h3>
      <table>
        <thead><tr><th style="width:16%">ID No.</th><th>Learner</th><th style="width:16%">Grade Level</th>
                   <th style="width:16%">Section</th><th style="width:15%">Status</th></tr></thead>
        <tbody>
          <?php foreach ($recent as $r): ?>
          <tr>
            <td><?php echo dh_e($r->IDNO); ?></td>
            <td><?php echo dh_e($r->LNAME.', '.$r->FNAME); ?></td>
            <td><?php echo dh_e($r->YEAR_LEVEL); ?></td>
            <td><?php echo $r->SECTION_NAME ? dh_e($r->SECTION_NAME) : 'Not sectioned'; ?></td>
            <td><?php echo dh_e($r->STATUS); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>

      <table class="sign">
        <tr>
          <td><div class="ln">SCHOOL ADMINISTRATOR</div></td>
          <td><div class="ln">REGISTRAR</div></td>
        </tr>
      </table>

      <p class="foot">
        This report reflects the records held in the school information system
        at the date and time printed above.
      </p>
    </div>

    <!-- welcome -->
    <div class="sj-hello">
      <div>
        <p class="eyebrow">Administrator Dashboard</p>
        <h2>Good day<?php echo isset($_SESSION['DISPLAYNAME']) ? ', '.dh_e($_SESSION['DISPLAYNAME']) : ''; ?>.</h2>
        <p>
          <?php if ($activeSY): ?>
            Active School Year <strong><?php echo dh_e($activeSY); ?></strong> &middot; <?php echo date('l, F j, Y'); ?>
          <?php else: ?>
            No active school year is set. <a href="<?php echo WEB_ROOT; ?>module/generic/index.php?t=tblschoolyear">Set one now</a>.
          <?php endif; ?>
        </p>
      </div>
      <div class="sj-hello-act">
        <button type="button" class="btn-print" onclick="window.print();"><i class="fas fa-print"></i>&nbsp; Print Report</button>
        <a class="btn-go" href="<?php echo WEB_ROOT; ?>module/enrollment/index.php"><i class="fas fa-clipboard-list"></i>&nbsp; Go to Enrollment</a>
      </div>
    </div>

    <!-- info boxes -->
    <div class="row">
      <div class="col-lg-3 col-sm-6">
        <a class="sj-info" href="<?php echo WEB_ROOT; ?>module/student/">
          <span class="ic bg-d"><i class="fas fa-user-graduate"></i></span>
          <span class="bd"><span class="t">Total Learners</span><span class="v"><?php echo $totalStudents; ?></span></span>
        </a>
      </div>
      <div class="col-lg-3 col-sm-6">
        <a class="sj-info" href="<?php echo WEB_ROOT; ?>module/enrollment/index.php">
          <span class="ic bg-g"><i class="fas fa-check-circle"></i></span>
          <span class="bd"><span class="t">Enrolled<?php echo $activeSY ? ' this S.Y.' : ''; ?></span><span class="v"><?php echo $enrolledNow; ?></span></span>
        </a>
      </div>
      <div class="col-lg-3 col-sm-6">
        <a class="sj-info" href="<?php echo WEB_ROOT; ?>module/enrollment/index.php">
          <span class="ic bg-a"><i class="fas fa-hourglass-half"></i></span>
          <span class="bd"><span class="t">In Progress</span><span class="v"><?php echo $inProgress; ?></span></span>
        </a>
      </div>
      <div class="col-lg-3 col-sm-6">
        <a class="sj-info" href="<?php echo WEB_ROOT; ?>portal/registrar/applicants.php">
          <span class="ic bg-o"><i class="fas fa-file-signature"></i></span>
          <span class="bd"><span class="t">Applicants Pending</span><span class="v"><?php echo $pendingApplicants; ?></span></span>
        </a>
      </div>
    </div>

    <!-- recap: chart + progress rail + strip -->
    <div class="sj-card">
      <div class="sj-card-h">
        <h3>Enrollment Recap</h3>
        <span class="meta"><?php echo $activeSY ? dh_e($activeSY) : 'All school years'; ?></span>
      </div>
      <div class="sj-card-b">
        <div class="row">
          <div class="col-lg-8">
            <?php if (empty($lvlLabels)): ?>
              <p class="sj-empty">No enrollment records yet for this school year.</p>
            <?php else: ?>
              <div class="chart"><canvas id="sjRecap"></canvas></div>
            <?php endif; ?>
          </div>
          <div class="col-lg-4">
            <h3 style="font-size:.95rem;font-weight:700;color:#002D2D;margin:0 0 14px;text-align:center;">Stage Completion</h3>
            <?php if ($pipeTotal < 1): ?>
              <p class="sj-empty">Nothing to show yet.</p>
            <?php else: foreach ($stages as $k => $color):
                  $n = $pipeline[$k]; $pct = $pipeTotal > 0 ? round(($n/$pipeTotal)*100) : 0; ?>
              <div class="sj-goal">
                <div class="sj-goal-t">
                  <span><?php echo dh_e($k); ?></span>
                  <b><?php echo $n; ?><span>/<?php echo $pipeTotal; ?></span></b>
                </div>
                <div class="sj-track"><i style="width:<?php echo $pct; ?>%;background:<?php echo $color; ?>;"></i></div>
              </div>
            <?php endforeach; endif; ?>
          </div>
        </div>
      </div>
      <div class="sj-strip">
        <div><b><?php echo $totalRecords; ?></b><span>Enrollment Records</span></div>
        <div><b><?php echo $enrolledNow; ?></b><span>Fully Enrolled</span></div>
        <div><b><?php echo $inProgress; ?></b><span>Still Processing</span></div>
        <div><b><?php echo dh_peso($collected); ?></b><span>Payments Collected</span></div>
      </div>
    </div>

    <!-- three-year enrollment trend -->
    <?php if ($hasTrend): ?>
    <div class="row">
      <div class="col-12">
        <div class="sj-card">
          <div class="sj-card-h">
            <h3>Enrolment Trend by Department</h3>
            <span class="meta">Last <?php echo count($trendYears); ?> school years</span>
          </div>
          <div class="sj-card-b">
            <div style="height:280px;"><canvas id="sjTrend"></canvas></div>
          </div>
          <div class="sj-strip">
            <?php
              $tTotals = array();
              foreach ($trendYears as $i => $yr) {
                  $sum = 0;
                  foreach ($depts as $d) { $sum += $trendData[$d][$i]; }
                  $tTotals[] = $sum;
              }
              foreach ($trendYears as $i => $yr):
                $delta = ($i > 0) ? $tTotals[$i] - $tTotals[$i-1] : null;
            ?>
            <div>
              <b><?php echo number_format($tTotals[$i]); ?></b>
              <span><?php echo dh_e($yr); ?>
                <?php if ($delta !== null): ?>
                  &middot; <?php echo ($delta >= 0 ? '+' : '').number_format($delta); ?>
                <?php endif; ?>
              </span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- where the learners come from -->
    <div class="row">
      <div class="col-lg-8">
        <div class="sj-card">
          <div class="sj-card-h">
            <h3>Where Our Learners Come From</h3>
            <span class="meta"><?php echo $hasProvince ? number_format($provTotal).' mapped' : 'Setup needed'; ?></span>
          </div>
          <div class="sj-card-b">
            <?php if (!$hasProvince): ?>
              <p class="sj-note">
                The map needs the <code>PROVINCE</code> field, which this database does not have yet.
                Import <code>database/ph_location_update.sql</code> once in phpMyAdmin, then reload this page.
              </p>
            <?php elseif ($provTotal < 1): ?>
              <p class="sj-empty">No learner has a province recorded yet.</p>
            <?php else: ?>
              <div class="sj-map" id="sjPhMap"></div>
              <div class="sj-mapfoot">
                <div class="sj-legend">
                  <span>None</span>
                  <i style="background:#F4F7F5;border:1px solid #C3D1CA;"></i>
                  <i style="background:#C8DFA8;"></i><i style="background:#95BF47;"></i>
                  <i style="background:#5E8E3E;"></i><i style="background:#008060;"></i>
                  <i style="background:#002D2D;"></i>
                  <span>More learners</span>
                </div>
                <button type="button" class="sj-mapreset" id="sjPhMapReset">Reset view</button>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="sj-card">
          <div class="sj-card-h">
            <h3>Top Provinces</h3>
            <a class="meta" href="<?php echo WEB_ROOT; ?>module/student/">View learners</a>
          </div>
          <div class="sj-card-b">
            <?php if (!$hasProvince): ?>
              <p class="sj-empty">Run the location update first.</p>
            <?php elseif ($provTotal < 1 && $provUnknown < 1): ?>
              <p class="sj-empty">No learners on record.</p>
            <?php else: ?>
              <ul class="sj-plist">
                <?php $shown = 0; foreach ($provCounts as $pn => $pc): if ($shown++ >= 7) break;
                      $pw = $provMax > 0 ? round(($pc/$provMax)*100) : 0; ?>
                <li>
                  <span class="nm"><?php echo dh_e($pn); ?></span>
                  <span class="bar"><i style="width:<?php echo $pw; ?>%;"></i></span>
                  <span class="ct"><?php echo $pc; ?></span>
                </li>
                <?php endforeach; ?>
                <?php if ($provUnknown > 0): ?>
                <li class="muted">
                  <span class="nm">Unspecified</span>
                  <span class="bar"></span>
                  <span class="ct"><?php echo $provUnknown; ?></span>
                </li>
                <?php endif; ?>
              </ul>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- activity + donut + tiles -->
    <div class="row">
      <div class="col-lg-8">
        <div class="sj-card">
          <div class="sj-card-h">
            <h3>Recent Enrollment Activity</h3>
            <a class="meta" href="<?php echo WEB_ROOT; ?>module/enrollment/index.php">View all</a>
          </div>
          <div class="sj-card-b flush">
            <?php if (empty($recent)): ?>
              <p class="sj-empty">Nothing recorded yet.</p>
            <?php else: ?>
            <div class="table-responsive">
              <table class="sj-tbl">
                <thead><tr><th>ID No.</th><th>Learner</th><th>Grade Level</th><th>Section</th><th>Status</th></tr></thead>
                <tbody>
                  <?php foreach ($recent as $r): ?>
                  <tr>
                    <td><?php echo dh_e($r->IDNO); ?></td>
                    <td><?php echo dh_e(trim($r->LNAME.', '.$r->FNAME)); ?></td>
                    <td><?php echo dh_e($r->YEAR_LEVEL); ?></td>
                    <td><?php echo $r->SECTION_NAME ? dh_e($r->SECTION_NAME) : '<span class="text-muted">Not sectioned</span>'; ?></td>
                    <td><span class="badge badge-<?php echo dh_badge($r->STATUS); ?>"><?php echo dh_e($r->STATUS); ?></span></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="sj-card">
          <div class="sj-card-h"><h3>Quick Actions</h3></div>
          <div class="sj-card-b">
            <div class="row">
              <div class="col-md-6">
                <a class="sj-q" href="<?php echo WEB_ROOT; ?>module/student/"><i class="fas fa-user-plus"></i><span>Add a learner</span><i class="fas fa-chevron-right ch"></i></a>
                <a class="sj-q" href="<?php echo WEB_ROOT; ?>module/enrollment/index.php"><i class="fas fa-clipboard-list"></i><span>Process enrollment</span><i class="fas fa-chevron-right ch"></i></a>
              </div>
              <div class="col-md-6">
                <a class="sj-q" href="<?php echo WEB_ROOT; ?>module/payments/index.php"><i class="fas fa-money-bill-wave"></i><span>Record a payment</span><i class="fas fa-chevron-right ch"></i></a>
                <a class="sj-q" href="<?php echo WEB_ROOT; ?>module/generic/index.php?t=tblsections"><i class="fas fa-chalkboard"></i><span>Manage sections</span><i class="fas fa-chevron-right ch"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="sj-card">
          <div class="sj-card-h"><h3>Enrollment Stages</h3></div>
          <div class="sj-card-b">
            <?php if ($pipeTotal < 1): ?>
              <p class="sj-empty">No data yet.</p>
            <?php else: ?>
              <div class="chart sm"><canvas id="sjDonut"></canvas></div>
            <?php endif; ?>
          </div>
        </div>

        <a class="sj-tile" style="background:#0B5563;" href="<?php echo WEB_ROOT; ?>module/generic/index.php?t=tblsections">
          <i class="fas fa-chalkboard ico"></i>
          <span><span class="tl">Sections</span><br><span class="tv"><?php echo $totalSections; ?></span></span>
        </a>
        <a class="sj-tile" style="background:#5E8E3E;" href="<?php echo WEB_ROOT; ?>module/subject">
          <i class="fas fa-book-open ico"></i>
          <span><span class="tl">Subjects</span><br><span class="tv"><?php echo $totalSubjects; ?></span></span>
        </a>
        <a class="sj-tile" style="background:#C9922B;" href="<?php echo WEB_ROOT; ?>module/generic/index.php?t=tblschoolyear">
          <i class="fas fa-calendar-alt ico"></i>
          <span><span class="tl">School Year</span><br><span class="tv"><?php echo $activeSY ? dh_e($activeSY) : 'Not set'; ?></span></span>
        </a>
      </div>
    </div>

  </div>
</section>

<script>
/* This file is included ABOVE the script tags in theme/template.php, so
   jQuery and Chart.js do not exist yet at parse time. Wait for window load
   (all scripts finished) instead of using $(function(){...}). */
window.addEventListener('load', function () {
  if (typeof Chart === 'undefined') { return; }

  var recap = document.getElementById('sjRecap');
  if (recap) {
    new Chart(recap.getContext('2d'), {
      type: 'line',
      data: {
        labels: <?php echo json_encode($lvlShort); ?>,
        datasets: [
          { label:'Enrolled', data: <?php echo json_encode($lvlDone); ?>,
            backgroundColor:'rgba(149,191,71,.35)', borderColor:'#5E8E3E', borderWidth:2,
            pointBackgroundColor:'#5E8E3E', pointRadius:4, fill:true, lineTension:.35 },
          { label:'In progress', data: <?php echo json_encode($lvlBusy); ?>,
            backgroundColor:'rgba(0,128,96,.18)', borderColor:'#008060', borderWidth:2,
            pointBackgroundColor:'#008060', pointRadius:4, fill:true, lineTension:.35 }
        ]
      },
      options:{ responsive:true, maintainAspectRatio:false,
        legend:{ position:'bottom', labels:{ boxWidth:12, padding:14 } },
        tooltips:{ mode:'index', intersect:false, callbacks:{
          /* the axis is abbreviated, so the tooltip carries the full name */
          title: function (items) {
            var full = <?php echo json_encode($lvlLabels); ?>;
            return (items.length && full[items[0].index]) ? full[items[0].index] : '';
          } } },
        scales:{ yAxes:[{ ticks:{ beginAtZero:true, precision:0 }, gridLines:{ color:'#EEF2EF' } }],
                 /* Horizontal, never rotated. maxRotation 0 keeps the codes
                    flat and autoSkip off keeps all 13 grade levels visible. */
                 xAxes:[{ gridLines:{ display:false },
                          ticks:{ maxRotation:0, minRotation:0, autoSkip:false,
                                  padding:6, fontSize:11, fontStyle:'600' } }] } }
    });
  }

  /* ---------- three-year enrolment trend ---------- */
  var trendEl = document.getElementById('sjTrend');
  if (trendEl && typeof Chart !== 'undefined') {
    /* NOTE: this project ships Chart.js 2.9.3, so the v2 option shape is
       required - xAxes/yAxes arrays, gridLines, legend and tooltips at the
       top level. The v3 shape (scales.x, plugins.legend) silently renders
       an unstacked, unstyled chart here. */
    new Chart(trendEl.getContext('2d'), {
      type: 'bar',
      data: {
        labels: <?php echo json_encode($trendYears); ?>,
        datasets: [
          { label: 'Early Childhood',
            data: <?php echo json_encode($trendData['Early Childhood']); ?>,
            backgroundColor: '#95BF47', maxBarThickness: 64 },
          { label: 'Elementary',
            data: <?php echo json_encode($trendData['Elementary']); ?>,
            backgroundColor: '#008060', maxBarThickness: 64 },
          { label: 'Junior High School',
            data: <?php echo json_encode($trendData['Junior High School']); ?>,
            backgroundColor: '#002D2D', maxBarThickness: 64 }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: { position: 'bottom',
                  labels: { boxWidth: 12, padding: 16, usePointStyle: true, fontSize: 11 } },
        tooltips: {
          mode: 'index', intersect: false,
          callbacks: {
            footer: function (items) {
              var t = 0;
              items.forEach(function (i) { t += Number(i.yLabel) || 0; });
              return 'Total enrolled: ' + t;
            }
          }
        },
        scales: {
          xAxes: [{ stacked: true, gridLines: { display: false } }],
          yAxes: [{ stacked: true,
                    ticks: { beginAtZero: true, precision: 0 },
                    gridLines: { color: 'rgba(0,45,45,.07)' } }]
        }
      }
    });
  }

  /* ---------- Philippine province map ---------- */
  var mapEl = document.getElementById('sjPhMap');
  if (mapEl && typeof L !== 'undefined') {
    var counts = <?php echo json_encode($provCounts, JSON_UNESCAPED_UNICODE); ?>;
    var maxN   = <?php echo (int)$provMax; ?>;

    /* Palette steps are the same greens used by the rest of the dashboard,
       so the map reads as part of the page and not a bolted-on widget. */
    var RAMP = ['#C8DFA8', '#95BF47', '#5E8E3E', '#008060', '#002D2D'];
    /* EMPTY is near-white land on a darker sea, with a real border colour,
       so a province with no learners is still clearly a province. */
    var EMPTY = '#F4F7F5', LINE = '#A9BAB2', HOVER = '#C9922B';

    function countFor(name) {
      if (counts.hasOwnProperty(name)) { return counts[name]; }
      /* tolerate spelling differences between the DB text and the map file */
      var k = name.toLowerCase();
      for (var key in counts) {
        if (counts.hasOwnProperty(key) && key.toLowerCase() === k) { return counts[key]; }
      }
      return 0;
    }

    function fillFor(n) {
      if (n <= 0 || maxN <= 0) { return EMPTY; }
      var r = n / maxN;
      if (r > 0.80) { return RAMP[4]; }
      if (r > 0.60) { return RAMP[3]; }
      if (r > 0.40) { return RAMP[2]; }
      if (r > 0.20) { return RAMP[1]; }
      return RAMP[0];
    }

    /* No tile layer on purpose: the shapes come from a local file, so the
       map still works when the office has no internet. */
    var map = L.map(mapEl, {
      zoomControl: true,
      scrollWheelZoom: false,   /* enabled on click, so the page still scrolls */
      attributionControl: false
    });

    var tip = L.control({ position: 'topright' });
    tip.onAdd = function () {
      this._d = L.DomUtil.create('div', 'sj-maptip');
      this.show(null);
      return this._d;
    };
    tip.show = function (props) {
      if (!props) {
        this._d.innerHTML = '<b>Philippines</b><span>Hover a province</span>';
      } else {
        var n = countFor(props.name);
        this._d.innerHTML = '<b>' + props.name + '</b><span>' +
          (n === 1 ? '1 learner' : n + ' learners') + '</span>';
      }
    };
    tip.addTo(map);

    function style(f) {
      return {
        fillColor: fillFor(countFor(f.properties.name)),
        weight: 0.6, color: LINE, opacity: 1, fillOpacity: 1
      };
    }

    var layer = null;

    function onEach(feature, lyr) {
      lyr.on({
        mouseover: function (e) {
          var t = e.target;
          t.setStyle({ weight: 1.8, color: HOVER, fillOpacity: 1 });
          if (t.bringToFront) { t.bringToFront(); }
          tip.show(feature.properties);
        },
        mouseout: function (e) {
          if (layer) { layer.resetStyle(e.target); }
          tip.show(null);
        },
        click: function (e) {
          map.fitBounds(e.target.getBounds());
        }
      });
      lyr.bindTooltip(feature.properties.name, { sticky: true, direction: 'top' });
    }

    var GEO = '<?php echo WEB_ROOT; ?>assets/geo/ph-provinces.json';
    var xhr = new XMLHttpRequest();
    xhr.open('GET', GEO, true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState !== 4) { return; }
      if (xhr.status !== 200) {
        mapEl.innerHTML = '<p class="sj-empty">Map data could not be loaded.</p>';
        return;
      }
      var geo;
      try { geo = JSON.parse(xhr.responseText); }
      catch (err) { mapEl.innerHTML = '<p class="sj-empty">Map data is unreadable.</p>'; return; }

      layer = L.geoJSON(geo, { style: style, onEachFeature: onEach }).addTo(map);

      /* Minimal count labels: only provinces that actually have learners get
         a number, so the map stays quiet instead of stamping 88 zeros across
         the country. The label is a non-interactive tooltip so it never
         blocks a hover or a click on the province underneath. */
      layer.eachLayer(function (lyr) {
        var n = countFor(lyr.feature.properties.name);
        if (n <= 0) { return; }
        lyr.bindTooltip(String(n), {
          permanent: true,
          direction: 'center',
          className: 'sj-count',
          interactive: false
        });
      });
      var home = layer.getBounds();
      map.fitBounds(home, { padding: [8, 8] });

      /* The school itself. A divIcon is used instead of Leaflet's default
         PNG marker so the pin inherits the school gold and never depends on
         the plugin's image path resolving correctly. */
      var pin = L.divIcon({
        className: 'sj-pin',
        html: '<i class="fas fa-map-marker-alt pulse"></i>',
        iconSize: [22, 30],
        iconAnchor: [11, 28],
        popupAnchor: [0, -26]
      });
      L.marker([<?php echo $schoolLat; ?>, <?php echo $schoolLng; ?>], {
          icon: pin, title: <?php echo json_encode($schoolName); ?>, zIndexOffset: 1000
        })
        .addTo(map)
        .bindPopup('<b>' + <?php echo json_encode($schoolName); ?> + '</b><span>' +
                   <?php echo json_encode($schoolAddr); ?> + '</span>');

      var reset = document.getElementById('sjPhMapReset');
      if (reset) {
        reset.addEventListener('click', function () { map.fitBounds(home, { padding: [8, 8] }); });
      }
    };
    xhr.send();

    /* Click once to take control of the wheel, leave to give it back. */
    mapEl.addEventListener('click', function () { map.scrollWheelZoom.enable(); });
    mapEl.addEventListener('mouseleave', function () { map.scrollWheelZoom.disable(); });
  }

  var donut = document.getElementById('sjDonut');
  if (donut) {
    new Chart(donut.getContext('2d'), {
      type:'doughnut',
      data:{ labels: <?php echo json_encode(array_keys($stages)); ?>,
             datasets:[{ data: <?php echo json_encode(array_values($pipeline)); ?>,
                         backgroundColor: <?php echo json_encode(array_values($stages)); ?>,
                         borderWidth:2, borderColor:'#fff' }] },
      options:{ responsive:true, maintainAspectRatio:false, cutoutPercentage:62,
                legend:{ position:'right', labels:{ boxWidth:12, padding:10, fontSize:11 } } }
    });
  }
});
</script>