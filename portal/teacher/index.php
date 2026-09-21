<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Teacher Portal
require_once("../../include/initialize.php");
require_role([ROLE_ADMIN, ROLE_STAFF, ROLE_TEACHER]);

global $mydb;
$uid = isset($_SESSION['UID']) ? (int)$_SESSION['UID'] : 0;

$hasAssignments = $mydb->tableExists('tblteacher_subjects');
$assignedClasses = 0;
if ($hasAssignments) {
	$mydb->setQuery("SELECT * FROM tblteacher_subjects WHERE UID = " . $uid);
	$assignedClasses = $mydb->num_rows();
}

$title   = "Home";
$content = 'list.php';
require_once("../../theme/template.php");
?>
