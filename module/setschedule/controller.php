<?php
// DESTINATION: C:\xampp\htdocs\bruce-class\module\setschedule\controller.php
// ACTION:      NEW FILE (create folder module\setschedule if missing)
?>
<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Set Schedule controller.
require_once("../../include/initialize.php");
require_role([ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR]);
global $mydb;

$action    = isset($_GET['action']) ? $_GET['action'] : '';
$sectionId = isset($_POST['SECTION_ID']) ? (int)$_POST['SECTION_ID']
            : (isset($_GET['section_id']) ? (int)$_GET['section_id'] : 0);
$back      = 'index.php'.($sectionId ? '?section_id='.$sectionId : '');

/* Turns an empty select into a real SQL NULL, otherwise a quoted int. */
function ss_int_or_null($key) {
    return (isset($_POST[$key]) && $_POST[$key] !== '') ? (int)$_POST[$key] : null;
}
function ss_sqlval($v) { return ($v === null) ? 'NULL' : (int)$v; }

/* The school year of a schedule row is inherited from its section. */
function ss_section_sy($sectionId) {
    global $mydb;
    $mydb->setQuery("SELECT SY_ID FROM tblsections WHERE SECTION_ID = ".(int)$sectionId." LIMIT 1");
    $r = $mydb->loadSingleResult();
    return $r ? (int)$r->SY_ID : null;
}

/* A subject must belong to the section's grade level, and day/time must
   actually exist. Guards against a hand-crafted POST. */
function ss_subject_ok($subjectId, $sectionId) {
    global $mydb;
    $mydb->setQuery("SELECT sub.SUBJECT_ID FROM tblsubjects sub
                     JOIN tblsections sec ON sec.COURSE_ID = sub.COURSE_ID
                     WHERE sub.SUBJECT_ID = ".(int)$subjectId."
                       AND sec.SECTION_ID = ".(int)$sectionId." LIMIT 1");
    return $mydb->num_rows() > 0;
}

switch ($action) {
    case 'add':    doInsert($sectionId, $back); break;
    case 'edit':   doEdit($sectionId, $back);   break;
    case 'delete': doDelete($back);             break;
    default:       redirect($back);
}

function doInsert($sectionId, $back) {
    global $mydb;
    if (!isset($_POST['save'])) { redirect($back); return; }

    $subjectId = ss_int_or_null('SUBJECT_ID');
    $dayId     = ss_int_or_null('DAY_ID');
    $timeId    = ss_int_or_null('TIME_ID');
    $roomId    = ss_int_or_null('ROOM_ID');
    $teacher   = ss_int_or_null('TEACHER_UID');
    $instName  = trim($_POST['INSTRUCTOR_NAME'] ?? '');
    $syId      = ss_section_sy($sectionId);

    if (!$sectionId || !$subjectId || !$dayId || !$timeId) {
        message("Subject, Day and Time are required.", "error");
        redirect($back); return;
    }
    if (!ss_subject_ok($subjectId, $sectionId)) {
        message("That subject does not belong to this section's grade level.", "error");
        redirect($back); return;
    }

    $ok = $mydb->InsertThis("INSERT INTO tblclass_schedules
        (SY_ID, SECTION_ID, SUBJECT_ID, DAY_ID, TIME_ID, ROOM_ID, TEACHER_UID, INSTRUCTOR_NAME)
        VALUES (".ss_sqlval($syId).", ".$sectionId.", ".$subjectId.", ".$dayId.", ".$timeId.", "
        .ss_sqlval($roomId).", ".ss_sqlval($teacher).", '".$mydb->escape_value($instName)."')");

    message($ok ? "Class added to the schedule." : "The class could not be added.", $ok ? "success" : "error");
    redirect($back);
}

function doEdit($sectionId, $back) {
    global $mydb;
    if (!isset($_POST['edit'])) { redirect($back); return; }

    $id        = (int)($_POST['SCHEDULE_ID'] ?? 0);
    $subjectId = ss_int_or_null('SUBJECT_ID');
    $dayId     = ss_int_or_null('DAY_ID');
    $timeId    = ss_int_or_null('TIME_ID');
    $roomId    = ss_int_or_null('ROOM_ID');
    $teacher   = ss_int_or_null('TEACHER_UID');
    $instName  = trim($_POST['INSTRUCTOR_NAME'] ?? '');

    if (!$id || !$subjectId || !$dayId || !$timeId) {
        message("Subject, Day and Time are required.", "error");
        redirect($back); return;
    }
    if (!ss_subject_ok($subjectId, $sectionId)) {
        message("That subject does not belong to this section's grade level.", "error");
        redirect($back); return;
    }

    $ok = $mydb->InsertThis("UPDATE tblclass_schedules SET
        SUBJECT_ID = ".$subjectId.",
        DAY_ID = ".$dayId.",
        TIME_ID = ".$timeId.",
        ROOM_ID = ".ss_sqlval($roomId).",
        TEACHER_UID = ".ss_sqlval($teacher).",
        INSTRUCTOR_NAME = '".$mydb->escape_value($instName)."'
        WHERE SCHEDULE_ID = ".$id);

    message($ok ? "Class updated." : "The class could not be updated.", $ok ? "success" : "error");
    redirect($back);
}

function doDelete($back) {
    global $mydb;
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id) {
        $mydb->InsertThis("DELETE FROM tblclass_schedules WHERE SCHEDULE_ID = ".$id." LIMIT 1");
        message("Class removed from the schedule.", "success");
    }
    redirect($back);
}
?>