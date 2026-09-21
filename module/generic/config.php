<?php
// DESTINATION: C:\xampp\htdocs\bruce-class\module\generic\config.php
// ACTION:      OVERWRITE (back up the old file first)
?>
<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC.
// Every table below shows up in the sidebar and gets Add/Edit/Delete +
// a DataTable list, exactly like the Student/Course/Subject modules -
// the columns and inputs are built automatically from whatever is
// really in the database (see include/generic.php).

$GENERIC_TABLES = array(
	'alumni_details' => array(
		'title' => 'Alumni Details',
		'icon'  => 'fa-id-card',
	),
	'tblschoolyear' => array(
		'title' => 'School Year',
		'icon'  => 'fa-calendar-alt',
	),
	'tblsections' => array(
		'title' => 'Sections',
		'icon'  => 'fa-chalkboard',
	),
	/* tblenrollment is NOT listed here on purpose. It now has its own
	   module (module/enrollment) because the two-stage Reserve ->
	   Sectioning flow needs custom screens the generic builder cannot
	   produce. Adding it back here would give you a second, conflicting
	   way to edit the same table. */
	'tblenrollment_details' => array(
		'title' => 'Enrollment Details',
		'icon'  => 'fa-list-alt',
	),
	'tblgrades' => array(
		'title' => 'Grades',
		'icon'  => 'fa-graduation-cap',
	),

	/* Class Scheduling lookups. These are simple name/description (or
	   start/end) tables, so the generic builder gives them full Add/Edit/
	   Delete with no dedicated module. The Set Schedule module
	   (module/setschedule) reads all three to build a section's timetable. */
	'tblschedule_time' => array(
		'title' => 'Schedule Time',
		'icon'  => 'fa-clock',
	),
	'tblschedule_day' => array(
		'title' => 'Schedule Day',
		'icon'  => 'fa-calendar-day',
	),
	'tblclassroom' => array(
		'title' => 'Classroom',
		'icon'  => 'fa-door-open',
	),
);

/* Which roles may open a given generic-builder table. Checked server-side
   in index.php, generic_ajax.php, and controller.php - not just used to
   decide what shows in the sidebar. A table left out of this map falls
   back to Admin/Staff only (the safest default). */
$GENERIC_TABLE_ROLES = array(
	'alumni_details'         => array(ROLE_ADMIN, ROLE_STAFF),
	'tblschoolyear'          => array(ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR),
	'tblsections'            => array(ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR),
	'tblenrollment_details'  => array(ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR),
	'tblgrades'              => array(ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR, ROLE_TEACHER),
	'tblschedule_time'       => array(ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR),
	'tblschedule_day'        => array(ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR),
	'tblclassroom'           => array(ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR),
);

function generic_table_allowed_roles($table) {
	global $GENERIC_TABLE_ROLES;
	return isset($GENERIC_TABLE_ROLES[$table]) ? $GENERIC_TABLE_ROLES[$table] : array(ROLE_ADMIN, ROLE_STAFF);
}

/* column name => [table to pull options from, PK column, SQL expression for the label] */
$GENERIC_FK_MAP = array(
	'COURSE_ID'  => array('table' => 'tblcourses',   'pk' => 'COURSE_ID',  'label' => "CONCAT(COURSE_CODE, ' - ', COURSE_NAME)"),
	'SECTION_ID' => array('table' => 'tblsections',  'pk' => 'SECTION_ID', 'label' => 'SECTION_NAME'),
	'SY_ID'      => array('table' => 'tblschoolyear','pk' => 'SY_ID',      'label' => 'SCHOOL_YEAR'),
	'S_ID'       => array('table' => 'tblstudent',   'pk' => 'S_ID',       'label' => "CONCAT(LNAME, ', ', FNAME)"),
	'SUBJECT_ID' => array('table' => 'tblsubjects',  'pk' => 'SUBJECT_ID', 'label' => 'SUBJECT_NAME'),
	'ENROLLMENT_ID' => array('table' => 'tblenrollment', 'pk' => 'ENROLLMENT_ID', 'label' => 'ENROLLMENT_ID'),
	'UID'        => array('table' => 'tblusers',     'pk' => 'UID',        'label' => 'USERNAME'),
	'TYPEID'     => array('table' => 'tblusertype',  'pk' => 'TYPEID',     'label' => 'USERTYPE'),
	'AddedBy'    => array('table' => 'tblusers',     'pk' => 'UID',        'label' => 'DISPLAYNAME'),
);

/* STATUS means different things in different tables, so the generic form
   must not assume Active/Inactive everywhere. Keyed by table name. */
$GENERIC_STATUS_CHOICES = array(
	'tblenrollment' => array('Enrolled', 'Dropped', 'Completed'),
);

/* column name (exact, case-insensitive) => fixed dropdown choices, used
   instead of a plain text box in the Add/Edit forms. */
/* DepEd K to 12: basic education has no semesters. A learner enrolls once
   for the WHOLE SCHOOL YEAR, and grades are recorded per QUARTER. Grade
   levels are read from tblcourses so the list always matches what the
   school actually offers. */
function generic_grade_levels() {
	global $mydb;
	$levels = array();
	if (isset($mydb) && $mydb->tableExists('tblcourses')) {
		$mydb->setQuery("SELECT COURSE_NAME FROM `tblcourses` WHERE STATUS='Active' ORDER BY COURSE_ID ASC");
		foreach ($mydb->loadResultList() as $r) { $levels[] = $r->COURSE_NAME; }
	}
	if (empty($levels)) {
		$levels = array('Nursery 1','Nursery 2','Kindergarten','Grade 1','Grade 2','Grade 3',
		                'Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10');
	}
	return $levels;
}

$GENERIC_FIELD_CHOICES = array(
	'YEAR_LEVEL' => generic_grade_levels(),
	/* on tblgrades this column holds the grading quarter; on enrollment and
	   subjects it is always 'Whole Year' (see $GENERIC_FIELD_CHOICES_BY_TABLE) */
	'SEMESTER'   => array('Whole Year'),
	'REMARKS'    => array('Passed', 'Failed', 'Incomplete', 'Dropped'),
	'STATUS'     => array('Active', 'Inactive'),
);

/* table-specific overrides: tblgrades uses SEMESTER as the quarter */
$GENERIC_FIELD_CHOICES_BY_TABLE = array(
	'tblgrades' => array(
		'SEMESTER' => array('1st Quarter', '2nd Quarter', '3rd Quarter', '4th Quarter'),
	),
);

/* substring (case-insensitive) => nicer label to show instead of the
   auto-generated one. Used e.g. so an ADVISER column in Sections is
   labeled "Program Head" everywhere in the UI. */
$GENERIC_LABEL_OVERRIDES = array(
	'ADVIS'      => 'Adviser',
	'YEAR_LEVEL' => 'Grade Level',
	'SEMESTER'   => 'Term',
	'COURSE'     => 'Grade Level',
);

/* Returns the config for a table if (and only if) it is whitelisted -
   protects the generic module from being pointed at an arbitrary table
   name via the URL. */
function generic_table_config($tableKey) {
	global $GENERIC_TABLES;
	return isset($GENERIC_TABLES[$tableKey]) ? $GENERIC_TABLES[$tableKey] : null;
}
?>