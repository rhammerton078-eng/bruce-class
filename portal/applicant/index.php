<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Applicant Portal
require_once("../../include/initialize.php");
require_role([ROLE_APPLICANT]);

global $mydb;
$uid = (int)$_SESSION['UID'];

$mydb->setQuery("SELECT a.*, c.COURSE_NAME, c.COURSE_CODE, m.MAJOR_NAME
	FROM tblapplicants a
	LEFT JOIN tblcourses c ON c.COURSE_ID = a.COURSE_ID
	LEFT JOIN tblmajors  m ON m.MAJOR_ID  = a.MAJOR_ID
	WHERE a.UID = ".$uid." LIMIT 1");
$applicant = $mydb->loadSingleResult();

$title   = "Home";
$content = 'list.php';
require_once("../../theme/applicant_template.php");
?>
