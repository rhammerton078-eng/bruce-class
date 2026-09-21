<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Registrar: review applicants
require_once("../../include/initialize.php");
require_role([ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR]);

global $mydb;

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0 && $action !== '') {
	$mydb->setQuery("SELECT * FROM tblapplicants WHERE APPLICANT_ID = ".$id." LIMIT 1");
	$applicant = $mydb->loadSingleResult();

	if ($applicant) {
		/* The entrance-examination step is gone: it was a college admission
		   idea. Basic education admits on requirements and payment, not on
		   an entrance test, so a verified applicant goes straight to
		   registrar review. */
		if ($action === 'approve' && $applicant->STATUS === 'For Registrar Review') {
			$mydb->InsertThis("UPDATE tblapplicants SET STATUS = 'Approved' WHERE APPLICANT_ID = ".$id);
			message("Applicant approved.", "success");
		} elseif ($action === 'reject' && $applicant->STATUS === 'For Registrar Review') {
			$mydb->InsertThis("UPDATE tblapplicants SET STATUS = 'Rejected' WHERE APPLICANT_ID = ".$id);
			message("Applicant rejected.", "info");
		}
	}
	redirect(WEB_ROOT."portal/registrar/applicants.php");
	exit;
}

/* ---------------------------------------------------------------------
   The admission pipeline, in the order an applicant actually moves through
   it. Each stage records who is responsible for the next move, which is
   what makes an empty Action column understandable instead of puzzling:
   a dash means the record is sitting with somebody else, not that it is
   stuck. "tone" maps to the school palette, not Bootstrap defaults.
   --------------------------------------------------------------------- */
$APPLICANT_STAGES = array(
    'Application Started'      => array('owner' => 'Waiting on the applicant',  'tone' => 'wait',   'hint' => 'The family has started but not finished the admission form.'),
    'Form Incomplete'          => array('owner' => 'Waiting on the applicant',  'tone' => 'wait',   'hint' => 'Some required details are still missing from the form.'),
    'Form Completed'           => array('owner' => 'Waiting on the applicant',  'tone' => 'wait',   'hint' => 'Form is complete. The family still has to settle the admission fee.'),
    'Payment Pending'          => array('owner' => 'With the cashier',          'tone' => 'money',  'hint' => 'Proof of payment submitted. The cashier has to verify it.'),
    'Payment Verified'         => array('owner' => 'With the cashier',          'tone' => 'money',  'hint' => 'Payment confirmed and receipted. Ready to move to registrar review.'),
    'For Registrar Review'     => array('owner' => 'Your queue',                'tone' => 'action', 'hint' => 'Ready for your decision: approve or reject.'),
    'Approved'                 => array('owner' => 'Decided',                   'tone' => 'done',   'hint' => 'Approved. The learner record and enrollment come next.'),
    'For Enrollment'           => array('owner' => 'With enrollment',           'tone' => 'done',   'hint' => 'Approved and handed over to the enrollment desk.'),
    'Enrolled'                 => array('owner' => 'Completed',                 'tone' => 'done',   'hint' => 'Fully enrolled for the school year.'),
    'Rejected'                 => array('owner' => 'Closed',                    'tone' => 'closed', 'hint' => 'Application was not accepted.')
);

/* Grade level comes from tblcourses. The old query also joined tblmajors
   and appended a major name, which is a college idea with no meaning in
   basic education, so that join is gone. */
$mydb->setQuery("SELECT a.*, c.COURSE_CODE, c.COURSE_NAME, c.LEVEL_ORDER
	FROM tblapplicants a
	LEFT JOIN tblcourses c ON c.COURSE_ID = a.COURSE_ID
	ORDER BY a.DATE_APPLIED DESC, a.APPLICANT_ID DESC");
$applicants = $mydb->loadResultList();

/* Bucket the rows by status, keeping the pipeline order above. Grouping in
   PHP rather than SQL keeps a single query and lets an unrecognised status
   fall into its own bucket instead of vanishing from the page. */
$grouped = array();
foreach (array_keys($APPLICANT_STAGES) as $st) { $grouped[$st] = array(); }
foreach ($applicants as $a) {
	$st = trim($a->STATUS);
	if (!isset($grouped[$st])) {
		$grouped[$st] = array();
		$APPLICANT_STAGES[$st] = array('owner' => 'Unrecognised status', 'tone' => 'wait',
			'hint' => 'This status is not part of the standard pipeline.');
	}
	$grouped[$st][] = $a;
}

$totalApplicants = count($applicants);
$actionable = count($grouped['For Registrar Review']);

$title   = "Applicants";
$content = 'applicants_view.php';
require_once("../../theme/template.php");
?>