<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Cashier Portal
require_once("../../include/initialize.php");
require_role([ROLE_ADMIN, ROLE_STAFF, ROLE_CASHIER]);

global $mydb;

function cashier_safe_count($sql) {
	global $mydb;
	$mydb->setQuery($sql);
	return $mydb->num_rows();
}

$hasPayments = $mydb->tableExists('tbladmission_payments');

$pendingPayments  = $hasPayments ? cashier_safe_count("SELECT * FROM tbladmission_payments WHERE STATUS = 'Pending'") : 0;
$verifiedToday    = $hasPayments ? cashier_safe_count("SELECT * FROM tbladmission_payments WHERE STATUS = 'Verified' AND DATE(DATE_VERIFIED) = CURDATE()") : 0;
$todaysCollection = 0;
if ($hasPayments) {
	$mydb->setQuery("SELECT COALESCE(SUM(AMOUNT),0) AS TOTAL FROM tbladmission_payments WHERE STATUS = 'Verified' AND DATE(DATE_VERIFIED) = CURDATE()");
	$row = $mydb->loadSingleResult();
	$todaysCollection = $row ? $row->TOTAL : 0;
}

$title   = "Home";
$content = 'list.php';
require_once("../../theme/template.php");
?>
