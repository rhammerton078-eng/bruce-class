<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Cashier: verify admission payments
require_once("../../include/initialize.php");
require_role([ROLE_ADMIN, ROLE_STAFF, ROLE_CASHIER]);

global $mydb;

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($action === 'verify' && $id > 0) {

	$mydb->setQuery("SELECT p.*, a.APPLICANT_ID, a.APPLICANT_TYPE FROM tbladmission_payments p
		JOIN tblapplicants a ON a.APPLICANT_ID = p.APPLICANT_ID
		WHERE p.PAYMENT_ID = ".$id." LIMIT 1");
	$payment = $mydb->loadSingleResult();

	if ($payment && $payment->STATUS === 'Pending') {
		$verifiedBy = (int)$_SESSION['UID'];
		$mydb->InsertThis("UPDATE tbladmission_payments SET STATUS = 'Verified', DATE_VERIFIED = NOW(), VERIFIED_BY = ".$verifiedBy." WHERE PAYMENT_ID = ".$id);

		$receiptNo = 'OR-'.str_pad($id, 6, '0', STR_PAD_LEFT);
		$mydb->InsertThis("INSERT INTO tbladmission_receipts (PAYMENT_ID, RECEIPT_NO, ISSUED_BY) VALUES (".$id.", '".$receiptNo."', ".$verifiedBy.")");

		$nextStatus = ($payment->APPLICANT_TYPE === 'Incoming First Year') ? 'For Entrance Examination' : 'For Registrar Review';
		$mydb->InsertThis("UPDATE tblapplicants SET STATUS = '".$nextStatus."' WHERE APPLICANT_ID = ".(int)$payment->APPLICANT_ID);

		message("Payment verified. Receipt ".$receiptNo." issued.", "success");
	}
	redirect(WEB_ROOT."portal/cashier/admission_payments.php");
	exit;
}

if ($action === 'reject' && $id > 0) {
	$mydb->setQuery("SELECT * FROM tbladmission_payments WHERE PAYMENT_ID = ".$id." LIMIT 1");
	$payment = $mydb->loadSingleResult();
	if ($payment && $payment->STATUS === 'Pending') {
		$mydb->InsertThis("UPDATE tbladmission_payments SET STATUS = 'Rejected' WHERE PAYMENT_ID = ".$id);
		$mydb->InsertThis("UPDATE tblapplicants SET STATUS = 'Form Completed' WHERE APPLICANT_ID = ".(int)$payment->APPLICANT_ID);
		message("Payment rejected. The applicant can resubmit a payment.", "info");
	}
	redirect(WEB_ROOT."portal/cashier/admission_payments.php");
	exit;
}

$mydb->setQuery("SELECT p.*, a.FNAME, a.LNAME, a.APPLICANT_TYPE
	FROM tbladmission_payments p
	JOIN tblapplicants a ON a.APPLICANT_ID = p.APPLICANT_ID
	ORDER BY (p.STATUS = 'Pending') DESC, p.PAYMENT_ID DESC");
$payments = $mydb->loadResultList();

$title   = "Admission Payments";
$content = 'admission_payments_view.php';
require_once("../../theme/template.php");
?>
