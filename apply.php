<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Applicant account creation
require_once("include/initialize.php");

if (isset($_SESSION['UID'])) {
	redirect(portal_home_for(current_role()));
}

global $mydb;
$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$fname          = trim($_POST['FNAME'] ?? '');
	$lname          = trim($_POST['LNAME'] ?? '');
	$email          = trim($_POST['EMAIL'] ?? '');
	$username       = trim($_POST['USERNAME'] ?? '');
	$password       = (string)($_POST['PASSWORD'] ?? '');
	$confirm        = (string)($_POST['CONFIRM_PASSWORD'] ?? '');
	$applicant_type = trim($_POST['APPLICANT_TYPE'] ?? '');

	$validTypes = array('Incoming First Year', 'Transferee', 'Returning');

	if ($fname === '' || $lname === '') { $errors[] = "First and last name are required."; }
	if ($username === '' || strlen($username) < 4) { $errors[] = "Username must be at least 4 characters."; }
	if (strlen($password) < 6) { $errors[] = "Password must be at least 6 characters."; }
	if ($password !== $confirm) { $errors[] = "Passwords do not match."; }
	if (!in_array($applicant_type, $validTypes, true)) { $errors[] = "Please select an applicant type."; }
	if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = "Please enter a valid email address."; }

	if (empty($errors)) {
		$mydb->setQuery("SELECT UID FROM tblusers WHERE USERNAME = '".$mydb->escape_value($username)."' LIMIT 1");
		if ($mydb->num_rows() > 0) {
			$errors[] = "That username is already taken.";
		}
	}

	if (empty($errors)) {
		$mydb->setQuery("SELECT TYPEID FROM tblusertype WHERE USERTYPE = 'Applicant' LIMIT 1");
		$typeRow = $mydb->loadSingleResult();
		$typeId  = $typeRow ? (int)$typeRow->TYPEID : 'NULL';

		$hashed = sha1($password);
		$ok = $mydb->InsertThis("INSERT INTO tblusers
			(DISPLAYNAME, USERNAME, PASSWORD, TYPE, TYPEID, ADDEDBY, DATEADDED, MODIFIEDBY, DATEMODIFIED, STATUSACTIVE)
			VALUES
			('".$mydb->escape_value($fname.' '.$lname)."', '".$mydb->escape_value($username)."', '".$hashed."',
			 'Applicant', ".$typeId.", 0, CURDATE(), 0, CURDATE(), 1)");

		if ($ok) {
			$uid = $mydb->insert_id();
			$mydb->InsertThis("INSERT INTO tblapplicants
				(UID, APPLICANT_TYPE, LNAME, FNAME, EMAIL, STATUS, DATE_APPLIED)
				VALUES
				(".$uid.", '".$mydb->escape_value($applicant_type)."', '".$mydb->escape_value($lname)."',
				 '".$mydb->escape_value($fname)."', ".($email !== '' ? "'".$mydb->escape_value($email)."'" : "NULL").",
				 'Application Started', NOW())");

			User::AuthenticateUser($username, $hashed);
			redirect(WEB_ROOT."portal/applicant/admission_form.php");
			exit;
		} else {
			$errors[] = "Your account could not be created. Please try again.";
		}
	}
}
?>
<?php
$PAGE_TITLE = 'Apply for Admission - St. Joseph Catholic School of Sagay Inc.';
$NAV_MODE   = 'min';
require_once(__DIR__ . '/theme/public_header.php');
?>
<style>
.sjcs-form-wrap { max-width: 520px; margin: 0 auto; background:#fff; border-radius:20px; padding:36px; box-shadow:0 4px 20px rgba(0,0,0,0.08); }
.sjcs-form-wrap label { display:block; font-weight:600; margin:14px 0 6px; font-size:0.9rem; }
.sjcs-form-wrap input, .sjcs-form-wrap select {
  width:100%; padding:11px 14px; border-radius:10px; border:1px solid #d7ddd9; font-size:0.95rem;
}
.sjcs-form-row { display:flex; gap:14px; flex-wrap:wrap; }
.sjcs-form-row > div { flex:1; min-width:180px; }
.sjcs-errors { background:#fdecea; border:1px solid #f5c2c0; color:#9b2226; border-radius:10px; padding:14px 16px; margin-bottom:10px; }
.sjcs-errors ul { margin:0; padding-left:20px; }
</style>


<section class="sjcs-section">
  <div class="sjcs-container">
    <h2 class="sjcs-section-title">Apply for Admission</h2>

    <div class="sjcs-form-wrap">
      <?php if (!empty($errors)): ?>
        <div class="sjcs-errors"><ul>
          <?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?>
        </ul></div>
      <?php endif; ?>

      <form method="post">
        <div class="sjcs-form-row">
          <div>
            <label>First Name</label>
            <input type="text" name="FNAME" value="<?php echo htmlspecialchars($_POST['FNAME'] ?? ''); ?>" required>
          </div>
          <div>
            <label>Last Name</label>
            <input type="text" name="LNAME" value="<?php echo htmlspecialchars($_POST['LNAME'] ?? ''); ?>" required>
          </div>
        </div>

        <label>Email Address</label>
        <input type="email" name="EMAIL" value="<?php echo htmlspecialchars($_POST['EMAIL'] ?? ''); ?>">

        <label>Applicant Type</label>
        <select name="APPLICANT_TYPE" required>
          <option value="">-- Select --</option>
          <?php foreach (array('Incoming First Year','Transferee','Returning') as $t): ?>
            <option value="<?php echo $t; ?>" <?php echo (($_POST['APPLICANT_TYPE'] ?? '') === $t) ? 'selected' : ''; ?>><?php echo $t; ?></option>
          <?php endforeach; ?>
        </select>

        <label>Choose a Username</label>
        <input type="text" name="USERNAME" value="<?php echo htmlspecialchars($_POST['USERNAME'] ?? ''); ?>" required>

        <div class="sjcs-form-row">
          <div>
            <label>Password</label>
            <input type="password" name="PASSWORD" required>
          </div>
          <div>
            <label>Confirm Password</label>
            <input type="password" name="CONFIRM_PASSWORD" required>
          </div>
        </div>

        <button type="submit" class="sjcs-btn sjcs-btn-primary" style="width:100%; margin-top:24px; border:none; cursor:pointer;">Create Applicant Account</button>
      </form>
    </div>
  </div>
</section>

<?php require_once(__DIR__ . '/theme/public_footer.php'); ?>
