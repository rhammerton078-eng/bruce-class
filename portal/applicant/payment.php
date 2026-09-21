<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Applicant fee payment
require_once("../../include/initialize.php");
require_role([ROLE_APPLICANT]);

global $mydb;
$uid = (int)$_SESSION['UID'];
$errors = array();
$saved  = false;

$mydb->setQuery("SELECT * FROM tblapplicants WHERE UID = ".$uid." LIMIT 1");
$applicant = $mydb->loadSingleResult();
if (!$applicant) { redirect(WEB_ROOT."portal/applicant/index.php"); exit; }

/* Required fees for this applicant: everything that applies to 'All',
   plus anything whose APPLIES_TO matches this applicant's type exactly
   (this is what keeps the Entrance Exam fee off a Transferee's bill -
   section 19 of the brief). Tuition-per-unit is excluded here on
   purpose - units aren't selected until subject enrollment, which
   happens after Registrar approval, not at the applicant stage. */
$mydb->setQuery("SELECT * FROM tblfee_types
	WHERE STATUS = 'Active' AND IS_PER_UNIT = 0
	AND (APPLIES_TO = 'All' OR APPLIES_TO = '".$mydb->escape_value($applicant->APPLICANT_TYPE)."')");
$requiredFees = $mydb->loadResultList();
$total = 0;
foreach ($requiredFees as $f) { $total += (float)$f->DEFAULT_AMOUNT; }

$formIsReady = !empty($applicant->COURSE_ID) && !empty($applicant->FNAME);
$alreadyPaid = !in_array($applicant->STATUS, array('Application Started', 'Form Incomplete', 'Form Completed'), true);

$mydb->setQuery("SELECT * FROM tbladmission_payments WHERE APPLICANT_ID = ".(int)$applicant->APPLICANT_ID." ORDER BY PAYMENT_ID DESC");
$existingPayments = $mydb->loadResultList();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $formIsReady && !$alreadyPaid) {

	$method    = trim($_POST['METHOD'] ?? '');
	$reference = trim($_POST['REFERENCE_NO'] ?? '');

	if (!in_array($method, array('Cash', 'GCash', 'Other'), true)) { $errors[] = "Please select a payment method."; }
	if ($method !== 'Cash' && $reference === '') { $errors[] = "Please provide a reference number for a non-cash payment."; }
	if ($total <= 0) { $errors[] = "There is no fee to pay right now."; }

	if (empty($errors)) {
		$mydb->InsertThis("INSERT INTO tbladmission_payments
			(APPLICANT_ID, AMOUNT, METHOD, REFERENCE_NO, STATUS, DATE_PAID)
			VALUES
			(".(int)$applicant->APPLICANT_ID.", ".$total.", '".$mydb->escape_value($method)."',
			 ".($reference !== '' ? "'".$mydb->escape_value($reference)."'" : "NULL").", 'Pending', NOW())");
		$paymentId = $mydb->insert_id();

		foreach ($requiredFees as $f) {
			$mydb->InsertThis("INSERT INTO tbladmission_payment_items
				(PAYMENT_ID, FEE_TYPE_ID, DESCRIPTION, AMOUNT)
				VALUES
				(".$paymentId.", ".(int)$f->FEE_TYPE_ID.", '".$mydb->escape_value($f->FEE_NAME)."', ".(float)$f->DEFAULT_AMOUNT.")");
		}

		$mydb->InsertThis("UPDATE tblapplicants SET STATUS = 'Payment Pending' WHERE APPLICANT_ID = ".(int)$applicant->APPLICANT_ID);

		$saved = true;
		$alreadyPaid = true;
		$mydb->setQuery("SELECT * FROM tbladmission_payments WHERE APPLICANT_ID = ".(int)$applicant->APPLICANT_ID." ORDER BY PAYMENT_ID DESC");
		$existingPayments = $mydb->loadResultList();
	}
}

$title   = "Payment";
$content = 'payment_view.php';
require_once("../../theme/applicant_template.php");
?>
