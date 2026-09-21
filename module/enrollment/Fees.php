<?php
/* ---------------------------------------------------------------------
   Enrollment fee helpers (shared by ajax.php, controller.php, list.php,
   print.php).

   FILENAME NOTE: this file is fees.php, all lower case. Every caller does
   require_once("fees.php"). The previous copy was named Fees.php, which
   works on Windows/XAMPP but fatals on any Linux host, because Linux file
   names are case sensitive. Delete the old Fees.php after dropping this in.

   FEE MODEL - basic education, not college.
   The old model charged tuition per UNIT (units x rate), which is a
   college idea. A Nursery or Grade 3 pupil has no unit load, so units came
   back 0 and every learner showed Amount Due P0.00 - which is exactly what
   the enrollment list was displaying.

   Basic education charges a flat annual amount per GRADE LEVEL, so the
   amounts now come from tblfeeschedule, one row per grade level:
       TUITION_FEE + MISC_FEE + BOOKS_FEE + OTHER_FEE
   Registration is a flat fee that is part of, not on top of, the total;
   paying it is what unlocks enrollment.

   All rates live in the database and are editable by the admin. Nothing
   here is hard coded except the fallback registration fee.
   --------------------------------------------------------------------- */

if (!defined('ENROLL_REG_FEE'))   define('ENROLL_REG_FEE', 1000.00);  // fallback only

/* LEGACY SHIM - do not delete without grepping first.
   The old college model charged tuition per unit and this constant was
   read in three places in ajax.php. Removing it outright made those lines
   reference an undefined constant, which is a FATAL error in PHP 8: the
   AJAX endpoint returned an error page instead of JSON and the Enrollment
   DataTable hung on "Processing..." forever.
   It is kept at 0.00 so any surviving per-unit arithmetic evaluates to
   zero (correct for basic education, where fees come from the grade-level
   schedule) instead of crashing the page. */
if (!defined('ENROLL_RATE_PER_UNIT')) define('ENROLL_RATE_PER_UNIT', 0.00);

if (!defined('FEE_REGISTRATION')) define('FEE_REGISTRATION', 'Registration');
if (!defined('FEE_TUITION'))      define('FEE_TUITION', 'Tuition');
if (!defined('FEE_MISC'))         define('FEE_MISC', 'Miscellaneous');
if (!defined('FEE_BOOKS'))        define('FEE_BOOKS', 'Books & Modules');
if (!defined('FEE_OTHER'))        define('FEE_OTHER', 'Other Fees');

/* ---------------------------------------------------------------------
   Schema guard. Adds any column the flow needs but the database lacks, so
   the module keeps working on an install where a migration was skipped.
   Each column is added only when missing, so this is safe on every load.
   --------------------------------------------------------------------- */
function enroll_ensure_schema($mydb) {
    $pay = array(
        'METHOD'  => "ALTER TABLE `tblpayments` ADD COLUMN `METHOD`  VARCHAR(30)  NOT NULL DEFAULT 'Cash'     AFTER `AMOUNT`",
        'STATUS'  => "ALTER TABLE `tblpayments` ADD COLUMN `STATUS`  VARCHAR(20)  NOT NULL DEFAULT 'Approved' AFTER `METHOD`",
        'REF_NO'  => "ALTER TABLE `tblpayments` ADD COLUMN `REF_NO`  VARCHAR(80)  DEFAULT NULL AFTER `OR_NUMBER`",
        'PROOF'   => "ALTER TABLE `tblpayments` ADD COLUMN `PROOF`   VARCHAR(255) DEFAULT NULL AFTER `REF_NO`",
        'CASHIER' => "ALTER TABLE `tblpayments` ADD COLUMN `CASHIER` VARCHAR(120) DEFAULT NULL AFTER `RECEIVED_BY`"
    );
    foreach ($pay as $name => $ddl) { enroll_add_col($mydb, 'tblpayments', $name, $ddl); }

    $sched = array(
        'MISC_FEE'  => "ALTER TABLE `tblfeeschedule` ADD COLUMN `MISC_FEE`  DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `TUITION_FEE`",
        'BOOKS_FEE' => "ALTER TABLE `tblfeeschedule` ADD COLUMN `BOOKS_FEE` DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `MISC_FEE`",
        'OTHER_FEE' => "ALTER TABLE `tblfeeschedule` ADD COLUMN `OTHER_FEE` DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `BOOKS_FEE`",
        'REG_FEE'   => "ALTER TABLE `tblfeeschedule` ADD COLUMN `REG_FEE`   DECIMAL(10,2) NOT NULL DEFAULT 1000.00 AFTER `OTHER_FEE`",
        'SY_ID'     => "ALTER TABLE `tblfeeschedule` ADD COLUMN `SY_ID`     INT(11) DEFAULT NULL AFTER `YEAR_LEVEL`"
    );
    foreach ($sched as $name => $ddl) { enroll_add_col($mydb, 'tblfeeschedule', $name, $ddl); }

    $mydb->InsertThis("UPDATE `tblpayments` SET `STATUS` = 'Approved' WHERE `STATUS` IS NULL OR `STATUS` = ''");
    $mydb->InsertThis("UPDATE `tblpayments` SET `METHOD` = 'Cash'     WHERE `METHOD` IS NULL OR `METHOD` = ''");
    $mydb->InsertThis("UPDATE `tblpayments` SET `FEE_TYPE` = 'Registration' WHERE `FEE_TYPE` = 'Admission Fee'");
}

function enroll_add_col($mydb, $table, $col, $ddl) {
    $mydb->setQuery("SELECT COUNT(*) AS C FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '".$table."' AND COLUMN_NAME = '".$col."'");
    $r = $mydb->loadSingleResult();
    if (!$r || intval($r->C) === 0) { $mydb->InsertThis($ddl); }
}

/* ---------------------------------------------------------------------
   The fee schedule row that applies to an enrollment. Looks for a row
   matching the grade level AND the school year first; falls back to a row
   for the grade level with no school year set, so one default schedule can
   cover every year until the school prices a specific one.
   --------------------------------------------------------------------- */
function enroll_schedule($mydb, $courseId, $syId = 0) {
    $courseId = intval($courseId); $syId = intval($syId);
    $mydb->setQuery("SELECT * FROM `tblfeeschedule`
        WHERE COURSE_ID = '".$courseId."'
        ORDER BY (SY_ID = '".$syId."') DESC, SY_ID IS NULL DESC, FEESCHEDULE_ID DESC
        LIMIT 1");
    return $mydb->loadSingleResult();
}

/* How much of a given fee type has been APPROVED for this enrollment. */
function enroll_paid($mydb, $eid, $feeType) {
    $eid = intval($eid);
    $mydb->setQuery("SELECT COALESCE(SUM(AMOUNT),0) AS P
        FROM `tblpayments`
        WHERE ENROLLMENT_ID = '".$eid."'
          AND FEE_TYPE = '".$mydb->escape_value($feeType)."'
          AND STATUS = 'Approved'");
    $row = $mydb->loadSingleResult();
    return $row ? floatval($row->P) : 0;
}

/* Every approved peso against this enrollment, whatever the fee type. */
function enroll_paid_total($mydb, $eid) {
    $eid = intval($eid);
    $mydb->setQuery("SELECT COALESCE(SUM(AMOUNT),0) AS P FROM `tblpayments`
        WHERE ENROLLMENT_ID = '".$eid."' AND STATUS = 'Approved'");
    $row = $mydb->loadSingleResult();
    return $row ? floatval($row->P) : 0;
}

/* ---------------------------------------------------------------------
   One struct carrying every number the Payment modal, the enrollment list
   and the printed assessment need. Computed, never typed in by hand.
   --------------------------------------------------------------------- */
function enroll_fee_summary($mydb, $eid) {
    $eid = intval($eid);
    $mydb->setQuery("SELECT COURSE_ID, SY_ID, YEAR_LEVEL FROM `tblenrollment`
                     WHERE ENROLLMENT_ID = '".$eid."' LIMIT 1");
    $e = $mydb->loadSingleResult();

    $sched = $e ? enroll_schedule($mydb, $e->COURSE_ID, $e->SY_ID) : null;

    $tuition = $sched ? floatval($sched->TUITION_FEE) : 0;
    $misc    = ($sched && isset($sched->MISC_FEE))  ? floatval($sched->MISC_FEE)  : 0;
    $books   = ($sched && isset($sched->BOOKS_FEE)) ? floatval($sched->BOOKS_FEE) : 0;
    $other   = ($sched && isset($sched->OTHER_FEE)) ? floatval($sched->OTHER_FEE) : 0;
    $regFee  = ($sched && isset($sched->REG_FEE) && floatval($sched->REG_FEE) > 0)
                 ? floatval($sched->REG_FEE) : ENROLL_REG_FEE;

    /* Registration is the down payment that unlocks enrollment, so it is
       part of the assessment, not an extra charge on top of it. */
    $gross     = $tuition + $misc + $books + $other;
    $total     = max($gross, $regFee);
    $paidTotal = enroll_paid_total($mydb, $eid);
    $regPaid   = enroll_paid($mydb, $eid, FEE_REGISTRATION);

    return array(
        'has_schedule' => ($sched ? true : false),
        'year_level'   => $e ? $e->YEAR_LEVEL : '',
        'units'        => 0,                 /* kept so older callers do not break */
        'tuition'      => $tuition,
        'misc'         => $misc,
        'books'        => $books,
        'other'        => $other,
        'total_due'    => $total,
        'paid'         => $paidTotal,
        'balance'      => max(0, $total - $paidTotal),
        'reg_due'      => $regFee,
        'reg_paid'     => $regPaid,
        'reg_bal'      => max(0, $regFee - $regPaid),
        /* legacy aliases so ajax.php / controller.php keep working */
        'tui_due'      => $total,
        'tui_paid'     => $paidTotal,
        'tui_bal'      => max(0, $total - $paidTotal)
    );
}

/* True once the registration fee is fully settled (this unlocks enrollment). */
function enroll_registration_paid($mydb, $eid) {
    $sum = enroll_fee_summary($mydb, $eid);
    return $sum['reg_bal'] <= 0;
}

/* ---------------------------------------------------------------------
   Official Receipt numbering. Format OR-YYYY-000123, sequential within the
   calendar year, derived from the highest OR already issued so numbers are
   never reused and never need to be typed by the cashier.
   --------------------------------------------------------------------- */
function enroll_next_or_number($mydb) {
    $year   = date('Y');
    $prefix = 'OR-'.$year.'-';
    $mydb->setQuery("SELECT OR_NUMBER FROM `tblpayments`
        WHERE OR_NUMBER LIKE '".$prefix."%'
        ORDER BY CAST(SUBSTRING(OR_NUMBER, ".(strlen($prefix)+1).") AS UNSIGNED) DESC
        LIMIT 1");
    $row  = $mydb->loadSingleResult();
    $next = 1;
    if ($row && preg_match('/(\d+)$/', $row->OR_NUMBER, $m)) { $next = intval($m[1]) + 1; }
    return $prefix.str_pad($next, 6, '0', STR_PAD_LEFT);
}

function peso($n) { return 'P' . number_format((float)$n, 2); }
?>