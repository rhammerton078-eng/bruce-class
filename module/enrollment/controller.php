<?php
// Enrollment controller
require_once("../../include/initialize.php");
require_once("fees.php");
require_role([ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR]);
global $mydb;
enroll_ensure_schema($mydb);

$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

switch ($action) {
    case 'assign'     : doAssignSubjects(); break;
    case 'section'    : doSectioning();     break;
    case 'pay'        : doPayment();        break;
    case 'approvepay' : doApprovePayment(); break;
    case 'rejectpay'  : doRejectPayment();  break;
    case 'edit'       : doEdit();           break;
    case 'delete'     : doDelete();         break;
}

/* Confirms a section really belongs to the given course and school year. */
function sectionBelongsTo($sectionId, $courseId, $syId) {
    global $mydb;
    $mydb->setQuery("SELECT SECTION_ID FROM `tblsections`
        WHERE SECTION_ID = '".intval($sectionId)."'
          AND COURSE_ID  = '".intval($courseId)."'
          AND SY_ID      = '".intval($syId)."' LIMIT 1");
    return ($mydb->num_rows() >= 1);
}

/* After any payment/approval, move the record forward if the flow allows.
   Registration paid unlocks enrollment; tuition paid marks 'Paid'. */
function applyFlowGuards($eid) {
    global $mydb;
    $eid = intval($eid);

    $mydb->setQuery("SELECT STATUS, SECTION_ID FROM `tblenrollment` WHERE ENROLLMENT_ID = '".$eid."' LIMIT 1");
    $rec = $mydb->loadSingleResult();
    if (!$rec) { return; }

    $sum       = enroll_fee_summary($mydb, $eid);
    $regDone   = ($sum['reg_bal'] <= 0);
    $tuiDone   = ($sum['tui_bal'] <= 0 && $sum['tui_due'] > 0);
    $hasSection = ($rec->SECTION_ID !== null && $rec->SECTION_ID != '');

    if ($hasSection && $regDone && in_array($rec->STATUS, array('Sectioned','Paid'))) {
        $mydb->InsertThis("UPDATE `tblenrollment`
            SET STATUS = 'Enrolled', DATE_ENROLLED = '".date('Y-m-d')."'
            WHERE ENROLLMENT_ID = '".$eid."'");
    } elseif ($tuiDone && $rec->STATUS === 'Sectioned') {
        $mydb->InsertThis("UPDATE `tblenrollment` SET STATUS = 'Paid' WHERE ENROLLMENT_ID = '".$eid."'");
    }
}

/* ---------------------------------------------------------------------
   STAGE 1: ASSIGN SUBJECTS  ->  status Assigned
   --------------------------------------------------------------------- */
function doAssignSubjects() {
    global $mydb;

    $eid = isset($_POST['A_EID']) ? intval($_POST['A_EID']) : 0;
    $subs = (isset($_POST['SUBJECT']) && is_array($_POST['SUBJECT'])) ? $_POST['SUBJECT'] : array();

    if ($eid <= 0) { message("No enrollment record selected.", "error"); redirect('index.php'); return; }

    $mydb->setQuery("SELECT COURSE_ID, YEAR_LEVEL, SEMESTER, STATUS FROM `tblenrollment` WHERE ENROLLMENT_ID = '".$eid."' LIMIT 1");
    $e = $mydb->loadSingleResult();
    if (!$e) { message("That enrollment record no longer exists.", "error"); redirect('index.php'); return; }

    /* Only accept subjects that really are offered for this course/year/sem. */
    $valid = array();
    $mydb->setQuery("SELECT SUBJECT_ID FROM `tblsubjects`
        WHERE COURSE_ID = '".intval($e->COURSE_ID)."'
          AND YEAR_LEVEL = '".$mydb->escape_value($e->YEAR_LEVEL)."'
          AND SEMESTER   = '".$mydb->escape_value($e->SEMESTER)."'");
    foreach ($mydb->loadResultList() as $v) { $valid[intval($v->SUBJECT_ID)] = true; }

    $chosen = array();
    foreach ($subs as $sid) { $sid = intval($sid); if (isset($valid[$sid])) { $chosen[$sid] = true; } }

    if (count($chosen) < 1) {
        message("Please tick at least one subject to assign.", "error");
        redirect('index.php'); return;
    }

    $mydb->InsertThis("DELETE FROM `tblenrollment_details` WHERE ENROLLMENT_ID = '".$eid."'");
    foreach (array_keys($chosen) as $sid) {
        $mydb->InsertThis("INSERT INTO `tblenrollment_details` (ENROLLMENT_ID, SUBJECT_ID)
            VALUES ('".$eid."', '".$sid."')");
    }

    /* Move forward from Registered; never pull an advanced record back. */
    if (in_array($e->STATUS, array('Registered'))) {
        $mydb->InsertThis("UPDATE `tblenrollment` SET STATUS = 'Assigned' WHERE ENROLLMENT_ID = '".$eid."'");
    }

    message(count($chosen)." subject(s) assigned. The student is now ready for Sectioning.", "success");
    redirect('index.php');
}

/* ---------------------------------------------------------------------
   STAGE 2: SECTIONING  ->  status Sectioned
   --------------------------------------------------------------------- */
function doSectioning() {
    global $mydb;

    $EID     = isset($_POST['SEC_EID'])     ? intval($_POST['SEC_EID'])     : 0;
    $SECTION = isset($_POST['SEC_SECTION']) ? intval($_POST['SEC_SECTION']) : 0;

    if ($EID <= 0 || $SECTION <= 0) { message("Please choose a section.", "error"); redirect('index.php'); return; }

    $mydb->setQuery("SELECT COURSE_ID, SY_ID, STATUS FROM `tblenrollment` WHERE ENROLLMENT_ID = '".$EID."' LIMIT 1");
    $rec = $mydb->loadSingleResult();
    if (!$rec) { message("That enrollment record no longer exists.", "error"); redirect('index.php'); return; }

    if (!sectionBelongsTo($SECTION, $rec->COURSE_ID, $rec->SY_ID)) {
        message("That section does not belong to this student's course and academic year.", "error");
        redirect('index.php'); return;
    }

    /* Keep Enrolled/Paid records where they are; otherwise mark Sectioned. */
    $newStatus = in_array($rec->STATUS, array('Enrolled','Paid','Completed')) ? $rec->STATUS : 'Sectioned';

    if ($mydb->InsertThis("UPDATE `tblenrollment`
            SET SECTION_ID = '".$SECTION."', STATUS = '".$mydb->escape_value($newStatus)."'
            WHERE ENROLLMENT_ID = '".$EID."'")) {
        message("Sectioning saved. The student can now proceed to Payment.", "success");
    } else {
        message("Sectioning could not be saved.", "error");
    }
    redirect('index.php');
}

/* ---------------------------------------------------------------------
   STAGE 3: RECORD A PAYMENT (cashier)  -> may unlock Paid / Enrolled
   --------------------------------------------------------------------- */
function doPayment() {
    global $mydb;

    $EID     = isset($_POST['PAY_EID'])     ? intval($_POST['PAY_EID'])   : 0;
    $TYPE    = isset($_POST['PAY_TYPE'])    ? trim($_POST['PAY_TYPE'])    : '';
    $AMOUNT  = isset($_POST['PAY_AMOUNT'])  ? floatval($_POST['PAY_AMOUNT']) : 0;
    $OR      = isset($_POST['PAY_OR'])      ? trim($_POST['PAY_OR'])      : '';
    $CASHIER = isset($_POST['PAY_CASHIER']) ? trim($_POST['PAY_CASHIER']) : '';

    if ($EID <= 0 || ($TYPE !== FEE_REGISTRATION && $TYPE !== FEE_TUITION) || $AMOUNT <= 0) {
        message("Please choose what the payment is for and enter a valid amount.", "error");
        redirect('index.php'); return;
    }

    $mydb->setQuery("SELECT ENROLLMENT_ID FROM `tblenrollment` WHERE ENROLLMENT_ID = '".$EID."' LIMIT 1");
    if ($mydb->num_rows() < 1) { message("That enrollment record no longer exists.", "error"); redirect('index.php'); return; }

    $uid = isset($_SESSION['UID']) ? intval($_SESSION['UID']) : 'NULL';
    $uidSql = ($uid === 'NULL') ? 'NULL' : "'".$uid."'";

    $ok = $mydb->InsertThis("INSERT INTO `tblpayments`
        (ENROLLMENT_ID, FEE_TYPE, AMOUNT, METHOD, STATUS, OR_NUMBER, REF_NO, PROOF, DATE_PAID, RECEIVED_BY, CASHIER)
        VALUES ('".$EID."', '".$mydb->escape_value($TYPE)."', '".$AMOUNT."', 'Cash', 'Approved',
                '".$mydb->escape_value($OR)."', NULL, NULL, '".date('Y-m-d')."', ".$uidSql.",
                '".$mydb->escape_value($CASHIER)."')");

    if ($ok) {
        applyFlowGuards($EID);
        message("Payment of ".peso($AMOUNT)." recorded.", "success");
    } else {
        message("The payment could not be recorded.", "error");
    }
    redirect('index.php');
}

/* ---------------------------------------------------------------------
   Online payment approval / rejection (Pending Online Payments queue)
   --------------------------------------------------------------------- */
function doApprovePayment() {
    global $mydb;
    $pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
    if ($pid <= 0) { message("No payment selected.", "error"); redirect('index.php'); return; }

    $mydb->setQuery("SELECT ENROLLMENT_ID FROM `tblpayments` WHERE PAYMENT_ID = '".$pid."' AND STATUS = 'Pending' LIMIT 1");
    $p = $mydb->loadSingleResult();
    if (!$p) { message("That payment is no longer pending.", "error"); redirect('index.php'); return; }

    if ($mydb->InsertThis("UPDATE `tblpayments` SET STATUS = 'Approved' WHERE PAYMENT_ID = '".$pid."'")) {
        applyFlowGuards($p->ENROLLMENT_ID);
        message("Online payment approved.", "success");
    } else {
        message("The payment could not be approved.", "error");
    }
    redirect('index.php');
}

function doRejectPayment() {
    global $mydb;
    $pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
    if ($pid <= 0) { message("No payment selected.", "error"); redirect('index.php'); return; }

    if ($mydb->InsertThis("UPDATE `tblpayments` SET STATUS = 'Rejected' WHERE PAYMENT_ID = '".$pid."' AND STATUS = 'Pending'")) {
        message("Online payment rejected.", "info");
    } else {
        message("The payment could not be rejected.", "error");
    }
    redirect('index.php');
}

/* ---------------------------------------------------------------------
   EDIT an existing enrollment record (manual override, skips guards)
   --------------------------------------------------------------------- */
function doEdit() {
    global $mydb;

    $EID        = isset($_POST['E_EID'])           ? intval($_POST['E_EID'])      : 0;
    $SY_ID      = isset($_POST['E_SY'])            ? intval($_POST['E_SY'])       : 0;
    $COURSE_ID  = isset($_POST['E_COURSE'])        ? intval($_POST['E_COURSE'])   : 0;
    $SECTION_ID = isset($_POST['E_SECTION'])       ? intval($_POST['E_SECTION'])  : 0;
    $YEAR_LEVEL = isset($_POST['E_YEARLEVEL'])     ? trim($_POST['E_YEARLEVEL'])  : '';
    /* Basic education has no semesters. The field is gone from the form, so
       the value is fixed here rather than trusted from the request. */
    $SEMESTER   = 'Whole Year';
    $CATEGORY   = isset($_POST['E_CATEGORY'])      ? trim($_POST['E_CATEGORY'])   : 'New';
    $CURRICULUM = isset($_POST['E_CURRICULUM'])    ? trim($_POST['E_CURRICULUM']) : '';
    $STATUS     = isset($_POST['E_STATUS'])        ? trim($_POST['E_STATUS'])     : 'Registered';
    $RESERVED   = isset($_POST['E_DATE_RESERVED']) ? trim($_POST['E_DATE_RESERVED']) : '';
    $ENROLLED   = isset($_POST['E_DATE_ENROLLED']) ? trim($_POST['E_DATE_ENROLLED']) : '';

    if ($EID <= 0 || $SY_ID <= 0 || $COURSE_ID <= 0 || $YEAR_LEVEL == '') {
        message("Please complete all the required fields.", "error"); redirect('index.php'); return;
    }
    if ($SECTION_ID > 0 && !sectionBelongsTo($SECTION_ID, $COURSE_ID, $SY_ID)) {
        message("That section does not belong to the selected course and academic year.", "error");
        redirect('index.php'); return;
    }

    $mydb->setQuery("SELECT S_ID FROM `tblenrollment` WHERE ENROLLMENT_ID = '".$EID."' LIMIT 1");
    $owner = $mydb->loadSingleResult();
    if (!$owner) { message("That enrollment record no longer exists.", "error"); redirect('index.php'); return; }
    $S_ID = $owner->S_ID;

    $mydb->setQuery("SELECT ENROLLMENT_ID FROM `tblenrollment`
        WHERE S_ID = '".$S_ID."' AND SY_ID = '".$SY_ID."'
          AND ENROLLMENT_ID <> '".$EID."' LIMIT 1");
    if ($mydb->num_rows() >= 1) {
        message("This learner already has another enrollment record for that school year.", "error");
        redirect('index.php'); return;
    }

    $sectionSql    = ($SECTION_ID > 0) ? "'".$SECTION_ID."'" : "NULL";
    $curriculumSql = ($CURRICULUM == '') ? "NULL" : "'".$mydb->escape_value($CURRICULUM)."'";
    $reservedSql   = ($RESERVED == '')   ? "NULL" : "'".$mydb->escape_value($RESERVED)."'";
    $enrolledSql   = ($ENROLLED == '')   ? "NULL" : "'".$mydb->escape_value($ENROLLED)."'";

    $sql = "UPDATE `tblenrollment` SET
            `COURSE_ID` = '".$COURSE_ID."', `SECTION_ID` = ".$sectionSql.", `SY_ID` = '".$SY_ID."',
            `YEAR_LEVEL` = '".$mydb->escape_value($YEAR_LEVEL)."', `SEMESTER` = '".$mydb->escape_value($SEMESTER)."',
            `CATEGORY` = '".$mydb->escape_value($CATEGORY)."', `CURRICULUM_YR` = ".$curriculumSql.",
            `DATE_RESERVED` = ".$reservedSql.", `DATE_ENROLLED` = ".$enrolledSql.",
            `STATUS` = '".$mydb->escape_value($STATUS)."'
        WHERE `ENROLLMENT_ID` = '".$EID."'";

    if ($mydb->InsertThis($sql)) { message("Enrollment record updated.", "success"); }
    else { message("The enrollment record could not be updated.", "error"); }
    redirect('index.php');
}

function doDelete() {
    global $mydb;
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id <= 0) { message("No enrollment record selected.", "error"); redirect('index.php'); return; }

    $mydb->setQuery("SELECT DETAIL_ID FROM `tblenrollment_details` WHERE ENROLLMENT_ID = '".$id."'");
    $subjects = $mydb->num_rows();
    $mydb->setQuery("SELECT GRADE_ID FROM `tblgrades` WHERE ENROLLMENT_ID = '".$id."'");
    $grades = $mydb->num_rows();

    if ($mydb->InsertThis("DELETE FROM `tblenrollment` WHERE `ENROLLMENT_ID` = '".$id."'")) {
        $extra = ($subjects > 0 || $grades > 0) ? " ".$subjects." subject record(s) and ".$grades." grade record(s) were removed with it." : '';
        message("Enrollment record deleted.".$extra, "info");
    } else {
        message("The enrollment record could not be deleted.", "error");
    }
    redirect('index.php');
}
?>