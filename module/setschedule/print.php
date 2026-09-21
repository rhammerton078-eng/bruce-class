<?php
// DESTINATION: C:\xampp\htdocs\bruce-class\module\setschedule\print.php
// ACTION:      NEW FILE (create folder module\setschedule if missing)
?>
<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Schedule of Classes (print).
// Standalone printable page, opened in a new tab from Set Schedule. Uses the
// official document look (Times New Roman letterhead + bordered table),
// matching the school's Schedule of Classes sheet.
require_once("../../include/initialize.php");
require_role([ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR]);
global $mydb;

function sp_e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function sp_t($t) { return $t ? date('g:i A', strtotime($t)) : ''; }

$sectionId = isset($_GET['section_id']) ? (int)$_GET['section_id'] : 0;

$mydb->setQuery("SELECT s.SECTION_NAME, c.COURSE_NAME, sy.SCHOOL_YEAR
                 FROM tblsections s
                 LEFT JOIN tblcourses    c  ON c.COURSE_ID = s.COURSE_ID
                 LEFT JOIN tblschoolyear sy ON sy.SY_ID    = s.SY_ID
                 WHERE s.SECTION_ID = ".$sectionId." LIMIT 1");
$section = $mydb->loadSingleResult();

$rows = array(); $totalUnits = 0;
if ($section) {
    $mydb->setQuery("SELECT cs.INSTRUCTOR_NAME,
                            sub.SUBJECT_CODE, sub.SUBJECT_NAME, sub.UNITS,
                            d.DAY_NAME, t.TIME_START, t.TIME_END, r.ROOM_NAME, u.DISPLAYNAME AS TEACHER
                     FROM tblclass_schedules cs
                     LEFT JOIN tblsubjects     sub ON sub.SUBJECT_ID = cs.SUBJECT_ID
                     LEFT JOIN tblschedule_day d   ON d.DAY_ID       = cs.DAY_ID
                     LEFT JOIN tblschedule_time t  ON t.TIME_ID      = cs.TIME_ID
                     LEFT JOIN tblclassroom    r   ON r.ROOM_ID      = cs.ROOM_ID
                     LEFT JOIN tblusers        u   ON u.UID          = cs.TEACHER_UID
                     WHERE cs.SECTION_ID = ".$sectionId."
                     ORDER BY t.TIME_START, d.DAY_NAME");
    $rows = $mydb->loadResultList();
    foreach ($rows as $r) { $totalUnits += (int)$r->UNITS; }
}

/* School name is admin-configurable; address / diocese are fixed for this school. */
$schoolName = 'ST. JOSEPH CATHOLIC SCHOOL OF SAGAY, INC.';
$mydb->setQuery("SELECT SETTING_VALUE FROM tblsettings WHERE SETTING_KEY='SCHOOL_NAME' LIMIT 1");
$sn = $mydb->loadSingleResult();
if ($sn && trim($sn->SETTING_VALUE) !== '') { $schoolName = strtoupper($sn->SETTING_VALUE); }
$schoolAddr = 'Sitio Palanas, Brgy. Poblacion II, Sagay City, Negros Occidental';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Schedule of Classes<?php echo $section ? ' - '.sp_e($section->COURSE_NAME.' '.$section->SECTION_NAME) : ''; ?></title>
<style>
  body { font-family: 'Times New Roman', Times, serif; color:#000; margin:24px; }
  .lh { text-align:center; border-bottom:2px solid #000; padding-bottom:8px; margin-bottom:14px; }
  .lh img { height:70px; margin-bottom:4px; }
  .lh h1 { font-size:16pt; margin:2px 0 0; letter-spacing:.5px; }
  .lh .addr { font-size:9.5pt; margin-top:2px; }
  .lh .doc { margin-top:10px; font-size:12pt; font-weight:bold; letter-spacing:1px; }
  .lh .meta { font-size:10pt; margin-top:4px; font-weight:bold; }
  table { border-collapse:collapse; width:100%; margin-top:6px; }
  th, td { border:1px solid #000; padding:5px 7px; font-size:10pt; vertical-align:top; }
  th { background:#eee; text-align:center; }
  td.c, th.c { text-align:center; }
  tfoot th { text-align:right; }
  .empty { text-align:center; padding:20px; font-style:italic; }
  .foot { margin-top:26px; font-size:10pt; }
  .sign { margin-top:40px; font-size:10pt; }
  .sign .line { border-top:1px solid #000; width:240px; padding-top:3px; }
  @media print { body { margin:0; } .noprint { display:none; } }
</style>
</head>
<body onload="window.print()">

  <div class="lh">
    <img src="<?php echo WEB_ROOT; ?>csr-scc.png" alt="School Seal">
    <h1><?php echo sp_e($schoolName); ?></h1>
    <div class="addr"><?php echo sp_e($schoolAddr); ?></div>
    <div class="addr">Diocese of San Carlos</div>
    <div class="doc">SCHEDULE OF CLASSES</div>
    <?php if ($section): ?>
      <div class="meta">A.Y. <?php echo sp_e($section->SCHOOL_YEAR); ?> &nbsp;&middot;&nbsp; <?php echo sp_e($section->COURSE_NAME.' - '.$section->SECTION_NAME); ?></div>
    <?php endif; ?>
  </div>

  <?php if (!$section): ?>
    <p class="empty">Section not found.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>TIME</th>
          <th>DAY</th>
          <th>CODE</th>
          <th>SUBJECT DESCRIPTION</th>
          <th class="c">UNITS</th>
          <th>ROOM</th>
          <th>INSTRUCTOR</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="7" class="empty">No classes have been scheduled for this section yet.</td></tr>
        <?php else: foreach ($rows as $r):
                $inst = trim((string)$r->INSTRUCTOR_NAME) !== '' ? $r->INSTRUCTOR_NAME : $r->TEACHER; ?>
          <tr>
            <td><?php echo sp_e(sp_t($r->TIME_START).' - '.sp_t($r->TIME_END)); ?></td>
            <td><?php echo sp_e($r->DAY_NAME); ?></td>
            <td><?php echo sp_e($r->SUBJECT_CODE); ?></td>
            <td><?php echo sp_e($r->SUBJECT_NAME); ?></td>
            <td class="c"><?php echo (int)$r->UNITS; ?></td>
            <td><?php echo sp_e($r->ROOM_NAME); ?></td>
            <td><?php echo sp_e($inst); ?></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
      <?php if (!empty($rows)): ?>
      <tfoot>
        <tr>
          <th colspan="4">TOTAL UNITS</th>
          <th class="c"><?php echo $totalUnits; ?></th>
          <th colspan="2"></th>
        </tr>
      </tfoot>
      <?php endif; ?>
    </table>

    <div class="sign">
      Prepared by:
      <div class="line" style="margin-top:34px;">
        <?php echo sp_e($_SESSION['DISPLAYNAME'] ?? ''); ?><br>
        <span style="font-size:9pt;">Registrar's Office</span>
      </div>
    </div>
  <?php endif; ?>

</body>
</html>