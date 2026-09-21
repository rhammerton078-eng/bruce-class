<style>
.sjcs-af-card { background:#fff; border-radius:18px; padding:28px; box-shadow:0 4px 16px rgba(0,0,0,0.06); margin-bottom:20px; }
.sjcs-af-card h3 { margin-top:0; color:var(--sjcs-dark-green); }
.sjcs-af-row { display:flex; gap:14px; flex-wrap:wrap; margin-bottom:12px; }
.sjcs-af-row > div { flex:1; min-width:160px; }
.sjcs-af-card label { display:block; font-weight:600; margin-bottom:5px; font-size:0.88rem; }
.sjcs-af-card input, .sjcs-af-card select, .sjcs-af-card textarea {
  width:100%; padding:9px 12px; border-radius:8px; border:1px solid #d7ddd9; font-size:0.92rem; font-family:inherit;
}
.sjcs-errors { background:#fdecea; border:1px solid #f5c2c0; color:#9b2226; border-radius:10px; padding:14px 16px; margin-bottom:16px; }
.sjcs-success { background:#e6f4ea; border:1px solid #a9d7b8; color:#1e6b3a; border-radius:10px; padding:14px 16px; margin-bottom:16px; }
</style>

<?php if ($saved): ?>
  <div class="sjcs-success">Your admission form has been saved.</div>
<?php endif; ?>
<?php if (!empty($errors)): ?>
  <div class="sjcs-errors"><ul style="margin:0; padding-left:18px;"><?php foreach ($errors as $e) { echo '<li>'.htmlspecialchars($e).'</li>'; } ?></ul></div>
<?php endif; ?>

<form method="post" id="admissionForm">

  <div class="sjcs-af-card">
    <h3>Program</h3>
    <div class="sjcs-af-row">
      <div>
        <label>Program</label>
        <select name="COURSE_ID" id="courseSelect" required>
          <option value="">-- Select Program --</option>
          <?php foreach ($programs as $p): ?>
            <option value="<?php echo $p->COURSE_ID; ?>" <?php echo ((int)($applicant->COURSE_ID ?? 0) === (int)$p->COURSE_ID) ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($p->COURSE_CODE.' - '.$p->COURSE_NAME); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label>Major (if applicable)</label>
        <select name="MAJOR_ID" id="majorSelect">
          <option value="">-- None --</option>
          <?php foreach ($majors as $m): ?>
            <option value="<?php echo $m->MAJOR_ID; ?>" data-course="<?php echo $m->COURSE_ID; ?>"
              <?php echo ((int)($applicant->MAJOR_ID ?? 0) === (int)$m->MAJOR_ID) ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($m->MAJOR_NAME); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>

  <div class="sjcs-af-card">
    <h3>Student Information</h3>
    <div class="sjcs-af-row">
      <div><label>Last Name</label><input type="text" name="LNAME" value="<?php echo htmlspecialchars($applicant->LNAME ?? ''); ?>" required></div>
      <div><label>First Name</label><input type="text" name="FNAME" value="<?php echo htmlspecialchars($applicant->FNAME ?? ''); ?>" required></div>
      <div><label>Middle Name</label><input type="text" name="MNAME" value="<?php echo htmlspecialchars($applicant->MNAME ?? ''); ?>"></div>
      <div><label>Suffix</label><input type="text" name="SUFFIX" value="<?php echo htmlspecialchars($applicant->SUFFIX ?? ''); ?>"></div>
    </div>
    <div class="sjcs-af-row">
      <div>
        <label>Sex</label>
        <select name="SEX">
          <option value="">-- Select --</option>
          <?php foreach (array('Male','Female') as $s): ?>
            <option value="<?php echo $s; ?>" <?php echo (($applicant->SEX ?? '') === $s) ? 'selected' : ''; ?>><?php echo $s; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label>Civil Status</label>
        <select name="CIVIL_STATUS">
          <option value="">-- Select --</option>
          <?php foreach (array('Single','Married','Widowed','Separated') as $s): ?>
            <option value="<?php echo $s; ?>" <?php echo (($applicant->CIVIL_STATUS ?? '') === $s) ? 'selected' : ''; ?>><?php echo $s; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div><label>Birthday</label><input type="date" name="BDAY" value="<?php echo htmlspecialchars($applicant->BDAY ?? ''); ?>"></div>
      <div><label>Birthplace</label><input type="text" name="BPLACE" value="<?php echo htmlspecialchars($applicant->BPLACE ?? ''); ?>"></div>
    </div>
    <div class="sjcs-af-row">
      <div><label>Nationality</label><input type="text" name="NATIONALITY" value="<?php echo htmlspecialchars($applicant->NATIONALITY ?? ''); ?>"></div>
      <div><label>Religion</label><input type="text" name="RELIGION" value="<?php echo htmlspecialchars($applicant->RELIGION ?? ''); ?>"></div>
      <div><label>LRN</label><input type="text" name="LRNNO" value="<?php echo htmlspecialchars($applicant->LRNNO ?? ''); ?>"></div>
    </div>
    <div class="sjcs-af-row">
      <div><label>Email Address</label><input type="email" name="EMAIL" value="<?php echo htmlspecialchars($applicant->EMAIL ?? ''); ?>"></div>
      <div><label>Mobile Number</label><input type="text" name="MOBILE_NO" value="<?php echo htmlspecialchars($applicant->MOBILE_NO ?? ''); ?>"></div>
    </div>
    <label>Address</label>
    <textarea name="ADDRESS" rows="2"><?php echo htmlspecialchars($applicant->ADDRESS ?? ''); ?></textarea>
  </div>

  <?php foreach (array('Father' => 'Father', 'Mother' => 'Mother', 'Guardian' => 'Guardian') as $role => $label):
    $p = $parents[$role]; $prefix = strtoupper($role); ?>
  <div class="sjcs-af-card">
    <h3><?php echo $label; ?>'s Information</h3>
    <div class="sjcs-af-row">
      <div><label><?php echo $label; ?>'s Name</label><input type="text" name="<?php echo $prefix; ?>_NAME" value="<?php echo htmlspecialchars($p->FULL_NAME ?? ''); ?>"></div>
      <div><label>Contact Number</label><input type="text" name="<?php echo $prefix; ?>_CONTACT" value="<?php echo htmlspecialchars($p->CONTACT_NO ?? ''); ?>"></div>
      <div><label>Email</label><input type="email" name="<?php echo $prefix; ?>_EMAIL" value="<?php echo htmlspecialchars($p->EMAIL ?? ''); ?>"></div>
    </div>
    <div class="sjcs-af-row">
      <div><label>Occupation</label><input type="text" name="<?php echo $prefix; ?>_OCCUPATION" value="<?php echo htmlspecialchars($p->OCCUPATION ?? ''); ?>"></div>
      <?php if ($role !== 'Guardian'): ?>
        <div style="display:flex; align-items:center; gap:8px; margin-top:22px;">
          <input type="checkbox" name="<?php echo $prefix; ?>_DECEASED" style="width:auto;" <?php echo (($p->DECEASED ?? 'No') === 'Yes') ? 'checked' : ''; ?>>
          <label style="margin:0;">Deceased</label>
        </div>
      <?php else: ?>
        <div><label>Relationship to Applicant</label><input type="text" name="GUARDIAN_RELATIONSHIP" value="<?php echo htmlspecialchars($p->RELATIONSHIP ?? ''); ?>"></div>
      <?php endif; ?>
    </div>
    <label>Address</label>
    <textarea name="<?php echo $prefix; ?>_ADDRESS" rows="2"><?php echo htmlspecialchars($p->ADDRESS ?? ''); ?></textarea>
  </div>
  <?php endforeach; ?>

  <button type="submit" class="sjcs-btn sjcs-btn-primary" style="border:none; cursor:pointer; padding:14px 40px;">Save Admission Form</button>
</form>

<script>
// Filter the Major dropdown to only the selected Program's majors.
(function() {
  var courseSelect = document.getElementById('courseSelect');
  var majorSelect  = document.getElementById('majorSelect');
  var allOptions   = Array.prototype.slice.call(majorSelect.options);

  function filterMajors() {
    var courseId = courseSelect.value;
    majorSelect.innerHTML = '';
    var none = document.createElement('option');
    none.value = ''; none.text = '-- None --';
    majorSelect.appendChild(none);
    allOptions.forEach(function(opt) {
      if (opt.value === '' ) { return; }
      if (opt.getAttribute('data-course') === courseId) {
        majorSelect.appendChild(opt.cloneNode(true));
      }
    });
  }
  courseSelect.addEventListener('change', filterMajors);
  filterMajors();
  // restore the originally-selected major (filterMajors resets selection)
  <?php if (!empty($applicant->MAJOR_ID)): ?>
  majorSelect.value = "<?php echo (int)$applicant->MAJOR_ID; ?>";
  <?php endif; ?>
})();
</script>
