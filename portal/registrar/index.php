<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Registrar Portal
require_once("../../include/initialize.php");
require_role([ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR]);

global $mydb;

function registrar_safe_count($sql) {
	global $mydb;
	$mydb->setQuery($sql);
	return $mydb->num_rows();
}

$totalStudents         = registrar_safe_count("SELECT * FROM tblstudent");
$pendingApplications    = $mydb->tableExists('tblapplicants')
	? registrar_safe_count("SELECT * FROM tblapplicants WHERE STATUS NOT IN ('Approved','Rejected','Enrolled')")
	: 0;
$approvedApplications   = $mydb->tableExists('tblapplicants')
	? registrar_safe_count("SELECT * FROM tblapplicants WHERE STATUS = 'Approved'")
	: 0;
$recentEnrollments      = registrar_safe_count("SELECT * FROM tblenrollment ORDER BY ENROLLMENT_ID DESC LIMIT 10");

$title   = "Home";
$content = 'list.php';
require_once("../../theme/template.php");
?>
