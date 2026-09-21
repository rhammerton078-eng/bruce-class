<?php
$status = $applicant->STATUS ?? 'Application Started';

$nextStepMap = array(
	'Application Started'      => 'Complete your Admission Form to continue.',
	'Form Incomplete'          => 'Finish filling out your Admission Form.',
	'Form Completed'           => 'Proceed to Payment to submit your enrollment fee.',
	'Payment Pending'          => 'Your payment is awaiting verification by the Cashier.',
	'Payment Verified'         => ($applicant->APPLICANT_TYPE === 'Incoming First Year')
		? 'Your payment has been verified. Wait for your Entrance Examination schedule.'
		: 'Your payment has been verified. Your application is now with the Registrar for review.',
	'For Entrance Examination' => 'Wait for your Entrance Examination schedule from the Registrar.',
	'Examination Completed'    => 'Your exam is recorded. Your application is now with the Registrar for review.',
	'For Registrar Review'     => 'Your application is being reviewed by the Registrar.',
	'Approved'                 => 'Congratulations! Your application has been approved. Wait for enrollment instructions.',
	'Rejected'                 => 'Your application was not approved this time. Please contact the Registrar\'s Office.',
	'For Enrollment'           => 'You may now proceed with subject enrollment. Please visit the Registrar\'s Office.',
	'Enrolled'                 => 'You are officially enrolled. The Student Portal will be your next stop once it opens.',
);
?>
<div class="sjcs-card" style="background:#fff; border-radius:18px; padding:28px; box-shadow:0 4px 16px rgba(0,0,0,0.06); margin-bottom:20px;">
  <p style="margin:0 0 8px; color:#5c6a62; font-weight:600;">Application Status</p>
  <span class="sjcs-status-pill"><?php echo htmlspecialchars($status); ?></span>
  <p style="margin:16px 0 0;"><?php echo htmlspecialchars($nextStepMap[$status] ?? ''); ?></p>
</div>

<div class="sjcs-card" style="background:#fff; border-radius:18px; padding:28px; box-shadow:0 4px 16px rgba(0,0,0,0.06);">
  <h3 style="margin-top:0;">Application Summary</h3>
  <table style="width:100%; border-collapse:collapse;">
    <tr><td style="padding:6px 0; color:#5c6a62;">Applicant Type</td><td><strong><?php echo htmlspecialchars($applicant->APPLICANT_TYPE ?? '-'); ?></strong></td></tr>
    <tr><td style="padding:6px 0; color:#5c6a62;">Name</td><td><strong><?php echo htmlspecialchars(trim(($applicant->FNAME ?? '').' '.($applicant->LNAME ?? ''))); ?></strong></td></tr>
    <tr><td style="padding:6px 0; color:#5c6a62;">Program</td><td><strong><?php echo htmlspecialchars($applicant->COURSE_NAME ?? 'Not yet selected'); ?></strong></td></tr>
    <tr><td style="padding:6px 0; color:#5c6a62;">Major</td><td><strong><?php echo htmlspecialchars($applicant->MAJOR_NAME ?? 'N/A'); ?></strong></td></tr>
    <tr><td style="padding:6px 0; color:#5c6a62;">Email</td><td><strong><?php echo htmlspecialchars($applicant->EMAIL ?? '-'); ?></strong></td></tr>
    <tr><td style="padding:6px 0; color:#5c6a62;">Date Applied</td><td><strong><?php echo !empty($applicant->DATE_APPLIED) ? date('M j, Y', strtotime($applicant->DATE_APPLIED)) : '-'; ?></strong></td></tr>
  </table>
</div>
