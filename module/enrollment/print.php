<?php
// Registration form (printable) - St. Joseph Catholic School of Sagay Inc.
require_once("../../include/initialize.php");
require_once("fees.php");
require_role([ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR]);
global $mydb;

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$mydb->setQuery("SELECT e.*, s.IDNO, s.LNAME, s.FNAME, s.MNAME, s.SEX, s.CONTACT_NO, s.HOME_ADD,
        c.COURSE_CODE, c.COURSE_NAME, sy.SCHOOL_YEAR, sec.SECTION_NAME
    FROM `tblenrollment` e
    JOIN `tblstudent`    s   ON s.S_ID = e.S_ID
    JOIN `tblcourses`    c   ON c.COURSE_ID = e.COURSE_ID
    JOIN `tblschoolyear` sy  ON sy.SY_ID = e.SY_ID
    LEFT JOIN `tblsections` sec ON sec.SECTION_ID = e.SECTION_ID
    WHERE e.ENROLLMENT_ID = '".$id."' LIMIT 1");
$e = $mydb->loadSingleResult();

$subjects = array();
$totalUnits = 0;
if ($e) {
    $mydb->setQuery("SELECT sub.SUBJECT_CODE, sub.SUBJECT_NAME, sub.UNITS
        FROM `tblenrollment_details` d
        JOIN `tblsubjects` sub ON sub.SUBJECT_ID = d.SUBJECT_ID
        WHERE d.ENROLLMENT_ID = '".$id."'
        ORDER BY sub.SUBJECT_CODE ASC");
    foreach ($mydb->loadResultList() as $s) { $subjects[] = $s; $totalUnits += floatval($s->UNITS); }
}
function h($v){ return htmlspecialchars((string)$v); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registration Form<?php echo $e ? ' - '.h($e->IDNO) : ''; ?></title>
<style>
  body { font-family: 'Times New Roman', serif; color:#111; margin:0; padding:24px; }
  .sheet { max-width: 820px; margin: 0 auto; }
  .noprint { text-align:center; margin-bottom:16px; }
  .btnprint { background:#7a1f2b; color:#fff; border:none; padding:8px 18px; border-radius:4px; cursor:pointer; font-size:14px; }
  .head { text-align:center; border-bottom:2px solid #111; padding-bottom:8px; margin-bottom:6px; }
  .head img { height:64px; vertical-align:middle; }
  .head h1 { font-size:19px; margin:6px 0 0; letter-spacing:.5px; }
  .head .sub { font-size:12px; }
  .formtitle { text-align:center; font-weight:bold; margin:10px 0; font-size:13px; letter-spacing:1px; }
  .row { display:flex; justify-content:space-between; font-size:12px; margin:2px 0; }
  table { width:100%; border-collapse:collapse; margin-top:8px; font-size:12px; }
  .info td { padding:4px 6px; vertical-align:bottom; }
  .info .lbl { font-size:10px; font-style:italic; color:#333; border-top:1px solid #111; }
  .subjects th, .subjects td { border-bottom:1px solid #999; text-align:left; padding:3px 6px; }
  .subjects th { border-bottom:1px solid #111; }
  .units { text-align:right; }
  .totals { text-align:right; font-style:italic; font-size:12px; margin-top:4px; }
  .sign { display:flex; flex-wrap:wrap; justify-content:space-between; margin-top:40px; font-size:12px; }
  .sigbox { width:46%; margin-bottom:34px; }
  .sigline { border-top:1px solid #111; text-align:center; padding-top:3px; margin-top:26px; }
  .sigrole { text-align:center; font-size:10px; color:#333; }
  @media print { .noprint { display:none; } body { padding:0; } }
</style>
</head>
<body>
<div class="sheet">

  <div class="noprint"><button class="btnprint" onclick="window.print();">Print This Form</button></div>

  <?php if (!$e): ?>
    <p style="text-align:center;color:#900;">That enrollment record was not found.</p>
  <?php else: ?>

  <div class="head">
    <img src="<?php echo WEB_ROOT; ?>csr-scc.png" alt="Seal">
    <h1>ST. JOSEPH CATHOLIC SCHOOL OF SAGAY, INC.</h1>
    <div class="sub">Sitio Palanas, Brgy. Poblacion II, Sagay City, Negros Occidental</div>
    <div class="sub">Diocese of San Carlos</div>
  </div>

  <div class="formtitle">BASIC EDUCATION DEPARTMENT<br>REGISTRATION FORM</div>

  <div class="row">
    <div>Student ID No.: <strong><?php echo h($e->IDNO); ?></strong></div>
    <div>Date Printed: <?php echo date('m/d/Y'); ?></div>
  </div>

  <table class="info">
    <tr>
      <td style="width:34%"><strong><?php echo h($e->LNAME); ?></strong></td>
      <td style="width:33%"><strong><?php echo h($e->FNAME); ?></strong></td>
      <td style="width:20%"><strong><?php echo h($e->MNAME); ?></strong></td>
      <td style="width:13%"><strong><?php echo h(strtoupper((string)$e->SEX)); ?></strong></td>
    </tr>
    <tr>
      <td class="lbl">(Surname)</td><td class="lbl">(First Name)</td><td class="lbl">(Middle Name)</td><td class="lbl">(Sex)</td>
    </tr>
    <tr><td colspan="4"><strong><?php echo h($e->HOME_ADD); ?></strong></td></tr>
    <tr><td colspan="4" class="lbl">(Address)</td></tr>
    <tr>
      <td><strong><?php echo h($e->CONTACT_NO); ?></strong></td>
      <td><strong><?php echo h($e->SCHOOL_YEAR); ?></strong></td>
      <td><strong><?php echo h($e->SEMESTER); ?></strong></td>
      <td><strong><?php echo h($e->COURSE_CODE); ?></strong></td>
    </tr>
    <tr>
      <td class="lbl">(Contact)</td><td class="lbl">(School Year)</td><td class="lbl">(Term)</td><td class="lbl">(Grade Level)</td>
    </tr>
    <tr>
      <td><strong><?php echo h($e->YEAR_LEVEL); ?></strong></td>
      <td><strong><?php echo ($e->SECTION_NAME ? h($e->SECTION_NAME) : 'Not sectioned'); ?></strong></td>
      <td colspan="2"></td>
    </tr>
    <tr><td class="lbl">(Grade Level)</td><td class="lbl">(Section)</td><td colspan="2"></td></tr>
  </table>

  <table class="subjects">
    <thead><tr><th style="width:22%">Course Code</th><th>Course Description</th><th class="units" style="width:12%">&nbsp;</th></tr></thead>
    <tbody>
      <?php if (empty($subjects)): ?>
        <tr><td colspan="3" style="text-align:center;color:#777;">No subjects assigned yet.</td></tr>
      <?php else: foreach ($subjects as $s): ?>
        <tr><td><?php echo h($s->SUBJECT_CODE); ?></td><td><?php echo h($s->SUBJECT_NAME); ?></td><td class="units">&nbsp;</td></tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
  <div class="totals">(Total Subjects) <?php echo count($subjects); ?></div>

  <div class="sign">
    <div class="sigbox"><div style="font-size:10px;">Approved:</div><div class="sigline">&nbsp;</div><div class="sigrole">PRINCIPAL</div></div>
    <div class="sigbox"><div style="font-size:10px;">Validated:</div><div class="sigline">&nbsp;</div><div class="sigrole">REGISTRAR</div></div>
    <div class="sigbox"><div class="sigline">&nbsp;</div><div class="sigrole">GUIDANCE COUNSELOR</div></div>
    <div class="sigbox"><div class="sigline">&nbsp;</div><div class="sigrole">TREASURER</div></div>
    <div class="sigbox" style="width:100%"><div class="sigline" style="width:46%; margin-left:auto;">&nbsp;</div><div class="sigrole" style="width:46%; margin-left:auto;">STUDENT SIGNATURE</div></div>
  </div>

  <?php endif; ?>
</div>
</body>
</html>