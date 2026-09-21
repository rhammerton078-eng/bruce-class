<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Applicant admission form
require_once("../../include/initialize.php");
require_role([ROLE_APPLICANT]);

global $mydb;
$uid = (int)$_SESSION['UID'];
$errors = array();
$saved  = false;

$mydb->setQuery("SELECT * FROM tblapplicants WHERE UID = ".$uid." LIMIT 1");
$applicant = $mydb->loadSingleResult();

if (!$applicant) {
	redirect(WEB_ROOT."portal/applicant/index.php");
	exit;
}

$mydb->setQuery("SELECT * FROM tblparents WHERE APPLICANT_ID = ".(int)$applicant->APPLICANT_ID);
$parentRows = $mydb->loadResultList();
$parents = array('Father' => null, 'Mother' => null, 'Guardian' => null);
foreach ($parentRows as $p) { $parents[$p->ROLE] = $p; }

$programs = array();
$mydb->setQuery("SELECT COURSE_ID, COURSE_CODE, COURSE_NAME FROM tblcourses WHERE STATUS = 'Active' ORDER BY COURSE_NAME");
$programs = $mydb->loadResultList();

$majors = array();
$mydb->setQuery("SELECT MAJOR_ID, COURSE_ID, MAJOR_NAME FROM tblmajors WHERE STATUS = 'Active'");
$majors = $mydb->loadResultList();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$course_id   = (int)($_POST['COURSE_ID'] ?? 0);
	$major_id    = !empty($_POST['MAJOR_ID']) ? (int)$_POST['MAJOR_ID'] : null;
	$fname       = trim($_POST['FNAME'] ?? '');
	$lname       = trim($_POST['LNAME'] ?? '');
	$mname       = trim($_POST['MNAME'] ?? '');
	$suffix      = trim($_POST['SUFFIX'] ?? '');
	$sex         = trim($_POST['SEX'] ?? '');
	$civil       = trim($_POST['CIVIL_STATUS'] ?? '');
	$bday        = trim($_POST['BDAY'] ?? '');
	$bplace      = trim($_POST['BPLACE'] ?? '');
	$nationality = trim($_POST['NATIONALITY'] ?? '');
	$religion    = trim($_POST['RELIGION'] ?? '');
	$lrn         = trim($_POST['LRNNO'] ?? '');
	$email       = trim($_POST['EMAIL'] ?? '');
	$mobile      = trim($_POST['MOBILE_NO'] ?? '');
	$address     = trim($_POST['ADDRESS'] ?? '');

	if ($fname === '' || $lname === '' || $course_id <= 0) {
		$errors[] = "First name, last name, and Program are required.";
	}

	if (empty($errors)) {
		$mydb->InsertThis("UPDATE tblapplicants SET
			COURSE_ID = ".$course_id.",
			MAJOR_ID = ".($major_id ? $major_id : "NULL").",
			FNAME = '".$mydb->escape_value($fname)."',
			LNAME = '".$mydb->escape_value($lname)."',
			MNAME = ".($mname !== '' ? "'".$mydb->escape_value($mname)."'" : "NULL").",
			SUFFIX = ".($suffix !== '' ? "'".$mydb->escape_value($suffix)."'" : "NULL").",
			SEX = '".$mydb->escape_value($sex)."',
			CIVIL_STATUS = '".$mydb->escape_value($civil)."',
			BDAY = ".($bday !== '' ? "'".$mydb->escape_value($bday)."'" : "NULL").",
			BPLACE = '".$mydb->escape_value($bplace)."',
			NATIONALITY = '".$mydb->escape_value($nationality)."',
			RELIGION = '".$mydb->escape_value($religion)."',
			LRNNO = '".$mydb->escape_value($lrn)."',
			EMAIL = ".($email !== '' ? "'".$mydb->escape_value($email)."'" : "NULL").",
			MOBILE_NO = '".$mydb->escape_value($mobile)."',
			ADDRESS = '".$mydb->escape_value($address)."',
			STATUS = IF(STATUS = 'Application Started' OR STATUS = 'Form Incomplete', 'Form Completed', STATUS)
			WHERE APPLICANT_ID = ".(int)$applicant->APPLICANT_ID);

		$mydb->InsertThis("DELETE FROM tblparents WHERE APPLICANT_ID = ".(int)$applicant->APPLICANT_ID);

		foreach (array('Father', 'Mother', 'Guardian') as $role) {
			$prefix = strtoupper($role);
			$pname  = trim($_POST[$prefix.'_NAME'] ?? '');
			if ($pname === '') { continue; }

			$pcontact = trim($_POST[$prefix.'_CONTACT'] ?? '');
			$pemail   = trim($_POST[$prefix.'_EMAIL'] ?? '');
			$pocc     = trim($_POST[$prefix.'_OCCUPATION'] ?? '');
			$pdec     = ($role !== 'Guardian' && isset($_POST[$prefix.'_DECEASED'])) ? 'Yes' : 'No';
			$prel     = trim($_POST[$prefix.'_RELATIONSHIP'] ?? '');
			$paddr    = trim($_POST[$prefix.'_ADDRESS'] ?? '');

			$mydb->InsertThis("INSERT INTO tblparents
				(APPLICANT_ID, ROLE, FULL_NAME, CONTACT_NO, EMAIL, OCCUPATION, DECEASED, RELATIONSHIP, ADDRESS)
				VALUES
				(".(int)$applicant->APPLICANT_ID.", '".$role."', '".$mydb->escape_value($pname)."',
				 '".$mydb->escape_value($pcontact)."', ".($pemail !== '' ? "'".$mydb->escape_value($pemail)."'" : "NULL").",
				 '".$mydb->escape_value($pocc)."', '".$pdec."', '".$mydb->escape_value($prel)."', '".$mydb->escape_value($paddr)."')");
		}

		$saved = true;
		$mydb->setQuery("SELECT * FROM tblapplicants WHERE UID = ".$uid." LIMIT 1");
		$applicant = $mydb->loadSingleResult();
		$mydb->setQuery("SELECT * FROM tblparents WHERE APPLICANT_ID = ".(int)$applicant->APPLICANT_ID);
		$parentRows = $mydb->loadResultList();
		$parents = array('Father' => null, 'Mother' => null, 'Guardian' => null);
		foreach ($parentRows as $p) { $parents[$p->ROLE] = $p; }
	}
}

$title   = "Admission Form";
$content = 'admission_form_view.php';
require_once("../../theme/applicant_template.php");
?>
