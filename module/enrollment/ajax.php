<?php
// Enrollment module data endpoints
require_once("../../include/initialize.php");
require_once("fees.php");
global $mydb;
enroll_ensure_schema($mydb);

$act = isset($_POST['act']) ? $_POST['act'] : '';

/* Student photo, same convention as module/student/ajax.php. */
function enrollment_student_photo_url($photo, $idno) {
    $dir = dirname(__DIR__).DIRECTORY_SEPARATOR.'student'.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR;
    $web = WEB_ROOT.'module/student/image/';
    if (!empty($photo)) {
        $safe = preg_replace('/[^A-Za-z0-9_.-]/', '', (string)$photo);
        if ($safe !== '' && is_file($dir.$safe)) { return $web.$safe.'?v='.filemtime($dir.$safe); }
    }
    $idnoSafe = preg_replace('/[^A-Za-z0-9_-]/', '', (string)$idno);
    if ($idnoSafe !== '') {
        foreach (array('jpg','jpeg','png','JPG','PNG') as $ext) {
            if (is_file($dir.$idnoSafe.'.'.$ext)) { return $web.$idnoSafe.'.'.$ext.'?v='.filemtime($dir.$idnoSafe.'.'.$ext); }
        }
    }
    return is_file($dir.'default.png') ? $web.'default.png' : '';
}

/* -----------------------------------------------------------------
   One enrollment row, for the Sectioning and Edit modals.
   ----------------------------------------------------------------- */
if ($act === 'row') {

    $id = intval($_POST['ENROLLMENT_ID']);
    $output = array();

    $mydb->setQuery("SELECT e.*,
            s.IDNO, s.LNAME, s.FNAME, s.MNAME, s.PHOTO,
            c.COURSE_CODE, c.COURSE_NAME,
            sy.SCHOOL_YEAR
        FROM `tblenrollment` e
        JOIN `tblstudent`    s  ON s.S_ID       = e.S_ID
        JOIN `tblcourses`    c  ON c.COURSE_ID  = e.COURSE_ID
        JOIN `tblschoolyear` sy ON sy.SY_ID     = e.SY_ID
        WHERE e.ENROLLMENT_ID = '".$id."' LIMIT 1");

    foreach ($mydb->loadResultList() as $r) {
        $output['ENROLLMENT_ID'] = $r->ENROLLMENT_ID;
        $output['S_ID']          = $r->S_ID;
        $output['IDNO']          = $r->IDNO;
        $output['FULLNAME']      = trim($r->LNAME.', '.$r->FNAME.' '.$r->MNAME);
        $output['PICTURE_URL']   = enrollment_student_photo_url($r->PHOTO, $r->IDNO);
        $output['COURSE_ID']     = $r->COURSE_ID;
        $output['COURSE_TEXT']   = $r->COURSE_CODE.' - '.$r->COURSE_NAME;
        $output['SY_ID']         = $r->SY_ID;
        $output['SCHOOL_YEAR']   = $r->SCHOOL_YEAR;
        $output['SEMESTER']      = $r->SEMESTER;
        $output['YEAR_LEVEL']    = $r->YEAR_LEVEL;
        $output['CATEGORY']      = $r->CATEGORY;
        $output['CURRICULUM_YR'] = ($r->CURRICULUM_YR === null) ? '' : $r->CURRICULUM_YR;
        $output['SECTION_ID']    = ($r->SECTION_ID === null) ? '' : $r->SECTION_ID;
        $output['STATUS']        = $r->STATUS;
        $output['DATE_RESERVED'] = ($r->DATE_RESERVED === null || $r->DATE_RESERVED == '0000-00-00') ? '' : substr($r->DATE_RESERVED, 0, 10);
        $output['DATE_ENROLLED'] = ($r->DATE_ENROLLED === null || $r->DATE_ENROLLED == '0000-00-00') ? '' : substr($r->DATE_ENROLLED, 0, 10);
    }
    echo json_encode($output);
    exit;
}

/* -----------------------------------------------------------------
   Sections available for a given course + school year.
   ----------------------------------------------------------------- */
if ($act === 'sections') {
    $course = intval($_POST['COURSE_ID']);
    $sy     = intval($_POST['SY_ID']);
    $rows   = array();
    if ($course > 0 && $sy > 0) {
        /* Ordered by the grade ladder (tblcourses.LEVEL_ORDER), not by the
           YEAR_LEVEL text - text sorting puts Grade 10 between Grade 1 and
           Grade 2, and Kindergarten last. */
        $mydb->setQuery("SELECT s.SECTION_ID, s.SECTION_NAME, s.YEAR_LEVEL
            FROM `tblsections` s
            LEFT JOIN `tblcourses` c ON c.COURSE_ID = s.COURSE_ID
            WHERE s.COURSE_ID = '".$course."' AND s.SY_ID = '".$sy."'
            ORDER BY COALESCE(c.LEVEL_ORDER, 99) ASC, s.SECTION_NAME ASC");
        foreach ($mydb->loadResultList() as $row) {
            $rows[] = array('SECTION_ID'=>$row->SECTION_ID,'SECTION_NAME'=>$row->SECTION_NAME,'YEAR_LEVEL'=>$row->YEAR_LEVEL);
        }
    }
    echo json_encode($rows);
    exit;
}

/* -----------------------------------------------------------------
   STAGE 1: ASSIGN SUBJECTS - the subjects offered for this student's
   course/year/semester, flagged if already taken.
   ----------------------------------------------------------------- */
if ($act === 'subjects') {

    $id = intval($_POST['ENROLLMENT_ID']);
    $out = array('header'=>array(), 'subjects'=>array(), 'rate'=>ENROLL_RATE_PER_UNIT);

    $mydb->setQuery("SELECT e.ENROLLMENT_ID, e.COURSE_ID, e.YEAR_LEVEL, e.SEMESTER,
            s.IDNO, s.LNAME, s.FNAME, s.MNAME
        FROM `tblenrollment` e
        JOIN `tblstudent` s ON s.S_ID = e.S_ID
        WHERE e.ENROLLMENT_ID = '".$id."' LIMIT 1");
    $e = $mydb->loadSingleResult();
    if (!$e) { echo json_encode($out); exit; }

    $out['header'] = array(
        'ENROLLMENT_ID' => $e->ENROLLMENT_ID,
        'IDNO'          => $e->IDNO,
        'FULLNAME'      => trim($e->LNAME.', '.$e->FNAME.' '.$e->MNAME)
    );

    // Which subjects are already assigned
    $taken = array();
    $mydb->setQuery("SELECT SUBJECT_ID FROM `tblenrollment_details` WHERE ENROLLMENT_ID = '".$id."'");
    foreach ($mydb->loadResultList() as $t) { $taken[intval($t->SUBJECT_ID)] = true; }

    // Offered subjects for this course + year level + semester
    $mydb->setQuery("SELECT SUBJECT_ID, SUBJECT_CODE, SUBJECT_NAME, UNITS
        FROM `tblsubjects`
        WHERE COURSE_ID = '".intval($e->COURSE_ID)."'
          AND YEAR_LEVEL = '".$mydb->escape_value($e->YEAR_LEVEL)."'
          AND SEMESTER   = '".$mydb->escape_value($e->SEMESTER)."'
        ORDER BY SUBJECT_CODE ASC");
    foreach ($mydb->loadResultList() as $s) {
        $out['subjects'][] = array(
            'SUBJECT_ID'   => $s->SUBJECT_ID,
            'SUBJECT_CODE' => $s->SUBJECT_CODE,
            'SUBJECT_NAME' => $s->SUBJECT_NAME,
            'UNITS'        => $s->UNITS,
            'AMOUNT'       => floatval($s->UNITS) * ENROLL_RATE_PER_UNIT,
            'CHECKED'      => isset($taken[intval($s->SUBJECT_ID)])
        );
    }
    echo json_encode($out);
    exit;
}

/* -----------------------------------------------------------------
   PAYMENT modal data: registration + tuition balances and history.
   ----------------------------------------------------------------- */
if ($act === 'payinfo') {

    $id = intval($_POST['ENROLLMENT_ID']);
    $out = array('header'=>array(), 'fees'=>array(), 'history'=>array());

    $mydb->setQuery("SELECT e.ENROLLMENT_ID, s.IDNO, s.LNAME, s.FNAME, s.MNAME
        FROM `tblenrollment` e
        JOIN `tblstudent` s ON s.S_ID = e.S_ID
        WHERE e.ENROLLMENT_ID = '".$id."' LIMIT 1");
    $e = $mydb->loadSingleResult();
    if (!$e) { echo json_encode($out); exit; }

    $out['header'] = array('ENROLLMENT_ID'=>$e->ENROLLMENT_ID, 'IDNO'=>$e->IDNO,
        'FULLNAME'=>trim($e->LNAME.', '.$e->FNAME.' '.$e->MNAME));
    $out['fees'] = enroll_fee_summary($mydb, $id);

    $mydb->setQuery("SELECT DATE_PAID, FEE_TYPE, AMOUNT, OR_NUMBER, REF_NO, METHOD, STATUS, CASHIER
        FROM `tblpayments`
        WHERE ENROLLMENT_ID = '".$id."'
        ORDER BY PAYMENT_ID DESC");
    foreach ($mydb->loadResultList() as $p) {
        $out['history'][] = array(
            'DATE'   => $p->DATE_PAID,
            'TYPE'   => $p->FEE_TYPE,
            'AMOUNT' => floatval($p->AMOUNT),
            'OR'     => $p->OR_NUMBER,
            'REF'    => $p->REF_NO,
            'METHOD' => $p->METHOD,
            'STATUS' => $p->STATUS,
            'CASHIER'=> ($p->CASHIER === null || $p->CASHIER === '') ? ($p->METHOD === 'Online' ? 'Online Submission' : '-') : $p->CASHIER
        );
    }
    echo json_encode($out);
    exit;
}

/* -----------------------------------------------------------------
   PENDING ONLINE PAYMENTS queue (STATUS = 'Pending').
   ----------------------------------------------------------------- */
if ($act === 'pending') {

    $rows = array();
    $mydb->setQuery("SELECT p.PAYMENT_ID, p.DATE_PAID, p.FEE_TYPE, p.AMOUNT, p.REF_NO, p.PROOF,
            s.IDNO, s.LNAME, s.FNAME, s.MNAME
        FROM `tblpayments` p
        JOIN `tblenrollment` e ON e.ENROLLMENT_ID = p.ENROLLMENT_ID
        JOIN `tblstudent`    s ON s.S_ID = e.S_ID
        WHERE p.STATUS = 'Pending'
        ORDER BY p.PAYMENT_ID DESC");
    foreach ($mydb->loadResultList() as $p) {
        $rows[] = array(
            'PAYMENT_ID' => $p->PAYMENT_ID,
            'DATE'       => $p->DATE_PAID,
            'IDNO'       => $p->IDNO,
            'STUDENT'    => trim($p->LNAME.', '.$p->FNAME.' '.$p->MNAME),
            'TYPE'       => $p->FEE_TYPE,
            'AMOUNT'     => floatval($p->AMOUNT),
            'REF'        => $p->REF_NO,
            'PROOF'      => $p->PROOF ? (WEB_ROOT.'module/enrollment/proof/'.rawurlencode($p->PROOF)) : ''
        );
    }
    echo json_encode(array('count'=>count($rows), 'rows'=>$rows));
    exit;
}

/* =================================================================
   DataTables list
   ================================================================= */
/* $rate is no longer used by this list - Amount Due comes from the
   grade-level fee schedule joined below. */

$base = "FROM `tblenrollment` e
    JOIN `tblstudent`     s   ON s.S_ID      = e.S_ID
    JOIN `tblcourses`     c   ON c.COURSE_ID = e.COURSE_ID
    JOIN `tblschoolyear`  sy  ON sy.SY_ID    = e.SY_ID
    LEFT JOIN `tblsections` sec ON sec.SECTION_ID = e.SECTION_ID  ";

$where = " WHERE 1=1 ";

$filter = isset($_POST['status_filter']) ? trim($_POST['status_filter']) : '';
if ($filter !== '') { $where .= " AND e.STATUS = '".$mydb->escape_value($filter)."' "; }

if (isset($_POST["search"]["value"]) && $_POST["search"]["value"] != '') {
    $ss = $mydb->escape_value($_POST["search"]["value"]);
    $where .= " AND (s.LNAME LIKE '%".$ss."%' OR s.FNAME LIKE '%".$ss."%' OR s.IDNO LIKE '%".$ss."%'
                 OR c.COURSE_CODE LIKE '%".$ss."%' OR e.STATUS LIKE '%".$ss."%') ";
}

$orderCols = array(
    0 => 'e.ENROLLMENT_ID', 1 => 's.IDNO', 2 => 's.LNAME', 3 => 'c.COURSE_CODE',
    /* Semester column removed from the UI, so indexes shift down by one. */
    4 => 'sy.SCHOOL_YEAR', 5 => 'e.YEAR_LEVEL', 6 => 'sec.SECTION_NAME',
    9 => 'e.STATUS'
);
$orderBy = " ORDER BY e.ENROLLMENT_ID DESC ";
if (isset($_POST['order'][0]['column'])) {
    $ci = intval($_POST['order'][0]['column']);
    $dir = (isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) == 'asc') ? 'ASC' : 'DESC';
    if (isset($orderCols[$ci])) { $orderBy = " ORDER BY ".$orderCols[$ci]." ".$dir." "; }
}

$limit = "";
if (isset($_POST['length']) && $_POST['length'] != -1) {
    $limit = " LIMIT ".intval($_POST['start']).", ".intval($_POST['length'])." ";
}

/* Amount Due comes from the grade-level fee schedule, not from subject
   units.

   It is read with a correlated subquery rather than a LEFT JOIN on
   purpose. tblfeeschedule carries SY_ID, so a school that prices a second
   school year gets two rows for the same COURSE_ID - and a JOIN would then
   return EVERY enrollment twice, silently doubling the list and inflating
   the filtered count. The subquery returns exactly one row and prefers the
   schedule for this enrollment's own school year, matching the same rule
   enroll_schedule() uses in fees.php. Units are a college idea: a basic-education learner has no unit
   load, so the old "SUM(UNITS) * rate" always evaluated to 0 and every row
   in this list showed P0.00. Amount Paid counts every approved payment,
   not just Tuition, so registration and other fees are reflected too. */
$select = "SELECT e.ENROLLMENT_ID, e.YEAR_LEVEL, e.SEMESTER, e.STATUS,
    s.IDNO, s.LNAME, s.FNAME, s.MNAME,
    c.COURSE_CODE, sy.SCHOOL_YEAR, sec.SECTION_NAME,
    (SELECT COALESCE(f.TUITION_FEE,0) + COALESCE(f.MISC_FEE,0)
              + COALESCE(f.BOOKS_FEE,0) + COALESCE(f.OTHER_FEE,0)
       FROM `tblfeeschedule` f
      WHERE f.COURSE_ID = e.COURSE_ID
      ORDER BY (f.SY_ID = e.SY_ID) DESC, f.SY_ID IS NULL DESC, f.FEESCHEDULE_ID DESC
      LIMIT 1) AS AMT_DUE,
    (SELECT COALESCE(SUM(p.AMOUNT),0)
       FROM `tblpayments` p
      WHERE p.ENROLLMENT_ID = e.ENROLLMENT_ID AND p.STATUS = 'Approved') AS AMT_PAID ";

$mydb->setQuery($select.$base.$where.$orderBy.$limit);
$rows = $mydb->loadResultList();

$mydb->setQuery("SELECT e.ENROLLMENT_ID ".$base.$where);
$filtered = $mydb->num_rows();

$mydb->setQuery("SELECT ENROLLMENT_ID FROM `tblenrollment`");
$total = $mydb->num_rows();

$data = array();
$i = isset($_POST['start']) ? intval($_POST['start']) + 1 : 1;

foreach ($rows as $r) {

    $badge = array('Registered'=>'secondary','Assigned'=>'info','Sectioned'=>'primary',
        'Paid'=>'warning','Enrolled'=>'success','Dropped'=>'danger','Completed'=>'dark');
    $statusClass = isset($badge[$r->STATUS]) ? $badge[$r->STATUS] : 'secondary';

    $section = ($r->SECTION_NAME === null || $r->SECTION_NAME == '')
        ? '<span class="text-muted">Not sectioned</span>' : htmlspecialchars($r->SECTION_NAME);

    $eid = $r->ENROLLMENT_ID;
    $bAssign  = '<button type="button" EID="'.$eid.'" class="btn btn-primary btn-xs doAssign" title="Assign Subjects"><span class="fa fa-book fw-fa"></span></button> ';
    $bSection = '<button type="button" EID="'.$eid.'" class="btn btn-primary btn-xs doSectioning" title="Sectioning"><span class="fa fa-chalkboard fw-fa"></span></button> ';
    $bPay     = '<button type="button" EID="'.$eid.'" class="btn btn-success btn-xs doPayment" title="Payment"><span class="fa fa-cash-register fw-fa"></span></button> ';
    $bPrint   = '<a href="print.php?id='.$eid.'" target="_blank"><button type="button" class="btn btn-default btn-xs" title="Print Registration Form"><span class="fa fa-print fw-fa"></span></button></a> ';
    $bEdit    = '<button type="button" EID="'.$eid.'" class="btn btn-warning btn-xs editEnrollment" title="Edit"><span class="fa fa-edit fw-fa"></span></button> ';
    $bDelete  = '<a href="controller.php?action=delete&id='.$eid.'" onclick="return confirm(\'Delete this enrollment record?\');"><button type="button" class="btn btn-danger btn-xs" title="Delete"><span class="fa fa-trash fw-fa"></span></button></a>';

    switch ($r->STATUS) {
        case 'Registered': $actions = $bAssign.$bEdit.$bDelete; break;
        case 'Assigned':   $actions = $bSection.$bEdit.$bDelete; break;
        case 'Sectioned':  $actions = $bPay.$bPrint.$bEdit.$bDelete; break;
        case 'Paid':       $actions = $bPay.$bPrint.$bEdit.$bDelete; break;
        case 'Enrolled':   $actions = $bPrint.$bPay.$bEdit.$bDelete; break;
        default:           $actions = $bEdit.$bDelete; break;
    }

    $data[] = array(
        $i,
        htmlspecialchars($r->IDNO),
        htmlspecialchars(trim($r->LNAME.', '.$r->FNAME.' '.$r->MNAME)),
        htmlspecialchars($r->COURSE_CODE),
        htmlspecialchars($r->SCHOOL_YEAR),
        htmlspecialchars($r->YEAR_LEVEL),
        $section,
        peso($r->AMT_DUE),
        peso($r->AMT_PAID),
        '<span class="badge badge-'.$statusClass.'">'.htmlspecialchars($r->STATUS).'</span>',
        $actions
    );
    $i++;
}

echo json_encode(array('data'=>$data, 'recordsTotal'=>$total, 'recordsFiltered'=>$filtered));
?>