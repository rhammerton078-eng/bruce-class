<?php
// DESTINATION: C:\xampp\htdocs\bruce-class\module\setschedule\ajax.php
// ACTION:      NEW FILE (create folder module\setschedule if missing)
?>
<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Set Schedule ajax.
// Returns one schedule row as JSON to prefill the edit modal. Gated the same
// way as the page: a JSON error for anyone not allowed, since a redirect
// header is meaningless to an XHR call.
require_once("../../include/initialize.php");
global $mydb;

if (!isset($_SESSION['UID']) || !has_role([ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR])) {
    echo json_encode(array('error' => 'Not authorized'));
    exit;
}

$id = isset($_POST['SCHEDULE_ID']) ? (int)$_POST['SCHEDULE_ID'] : 0;
if ($id <= 0) { echo json_encode(array()); exit; }

$mydb->setQuery("SELECT SCHEDULE_ID, SECTION_ID, SUBJECT_ID, DAY_ID, TIME_ID,
                        ROOM_ID, TEACHER_UID, INSTRUCTOR_NAME
                 FROM tblclass_schedules WHERE SCHEDULE_ID = ".$id." LIMIT 1");
$row = $mydb->loadSingleResult();
echo json_encode($row ? $row : array());
?>