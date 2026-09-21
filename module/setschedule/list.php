<?php
// DESTINATION: C:\xampp\htdocs\bruce-class\module\setschedule\list.php
// ACTION:      NEW FILE (create folder module\setschedule if missing)
?>
<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Set Schedule list + modals.
// Included by index.php through theme/template.php, so $mydb and WEB_ROOT
// already exist.
global $mydb;

function ss_e($v)  { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function ss_t($t)  { return $t ? date('g:i A', strtotime($t)) : ''; }

$sectionId = isset($_GET['section_id']) ? (int)$_GET['section_id'] : 0;

/* Sections for the selector, active school year first, then grade ladder. */
$mydb->setQuery("SELECT s.SECTION_ID, s.SECTION_NAME, s.COURSE_ID, s.SY_ID,
                        c.COURSE_NAME, sy.SCHOOL_YEAR, sy.STATUS AS SY_STATUS
                 FROM tblsections s
                 LEFT JOIN tblcourses    c  ON c.COURSE_ID = s.COURSE_ID
                 LEFT JOIN tblschoolyear sy ON sy.SY_ID    = s.SY_ID
                 ORDER BY (sy.STATUS='Active') DESC, COALESCE(c.LEVEL_ORDER,99), s.SECTION_NAME");
$sections = $mydb->loadResultList();

/* Details of the chosen section. */
$section = null;
foreach ($sections as $s) { if ((int)$s->SECTION_ID === $sectionId) { $section = $s; break; } }

/* Lookups + this section's subjects, loaded only once a section is chosen. */
$subjects = array(); $days = array(); $times = array(); $rooms = array(); $teachers = array(); $rows = array();
if ($section) {
    $mydb->setQuery("SELECT SUBJECT_ID, SUBJECT_CODE, SUBJECT_NAME, UNITS
                     FROM tblsubjects WHERE COURSE_ID = ".(int)$section->COURSE_ID."
                     ORDER BY SUBJECT_CODE");
    $subjects = $mydb->loadResultList();

    $mydb->setQuery("SELECT DAY_ID, DAY_NAME FROM tblschedule_day ORDER BY DAY_ID");
    $days = $mydb->loadResultList();

    $mydb->setQuery("SELECT TIME_ID, TIME_START, TIME_END FROM tblschedule_time ORDER BY TIME_START");
    $times = $mydb->loadResultList();

    $mydb->setQuery("SELECT ROOM_ID, ROOM_NAME FROM tblclassroom ORDER BY ROOM_NAME");
    $rooms = $mydb->loadResultList();

    $mydb->setQuery("SELECT UID, DISPLAYNAME FROM tblusers
                     WHERE TYPE IN ('Teacher','Staff','Administrator') AND STATUSACTIVE = 1
                     ORDER BY DISPLAYNAME");
    $teachers = $mydb->loadResultList();

    $mydb->setQuery("SELECT cs.SCHEDULE_ID, cs.SUBJECT_ID, cs.DAY_ID, cs.TIME_ID, cs.ROOM_ID,
                            cs.TEACHER_UID, cs.INSTRUCTOR_NAME,
                            sub.SUBJECT_CODE, sub.SUBJECT_NAME, sub.UNITS,
                            d.DAY_NAME, t.TIME_START, t.TIME_END, r.ROOM_NAME, u.DISPLAYNAME AS TEACHER
                     FROM tblclass_schedules cs
                     LEFT JOIN tblsubjects     sub ON sub.SUBJECT_ID = cs.SUBJECT_ID
                     LEFT JOIN tblschedule_day d   ON d.DAY_ID       = cs.DAY_ID
                     LEFT JOIN tblschedule_time t  ON t.TIME_ID      = cs.TIME_ID
                     LEFT JOIN tblclassroom    r   ON r.ROOM_ID      = cs.ROOM_ID
                     LEFT JOIN tblusers        u   ON u.UID          = cs.TEACHER_UID
                     WHERE cs.SECTION_ID = ".$sectionId."
                     ORDER BY t.TIME_START, d.DAY_NAME");
    $rows = $mydb->loadResultList();
}

$totalUnits = 0;
foreach ($rows as $r) { $totalUnits += (int)$r->UNITS; }

check_message();
?>

<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">Set Schedule</h3>
        <?php if ($section): ?>
          <div class="card-tools">
            <a href="<?php echo WEB_ROOT; ?>module/setschedule/print.php?section_id=<?php echo $sectionId; ?>"
               target="_blank" class="btn btn-default btn-sm">
              <i class="fa fa-print"></i> Print Schedule of Classes
            </a>
          </div>
        <?php endif; ?>
      </div>

      <div class="card-body">
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Section</label>
          <div class="col-sm-6">
            <select class="form-control" onchange="sjcsGoSection(this)">
              <option value="">-- Choose a section --</option>
              <?php foreach ($sections as $s): ?>
                <option value="<?php echo (int)$s->SECTION_ID; ?>" <?php echo ($sectionId === (int)$s->SECTION_ID) ? 'selected' : ''; ?>>
                  <?php echo ss_e($s->COURSE_NAME.' - '.$s->SECTION_NAME.'  ('.$s->SCHOOL_YEAR.')'); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php if ($section): ?>
            <div class="col-sm-4 text-right">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddNewEntry">
                <i class="fa fa-plus"></i> Add Class
              </button>
            </div>
          <?php endif; ?>
        </div>

        <?php if (!$section): ?>
          <p class="text-muted mb-0">Choose a section above to build its class schedule.</p>
        <?php else: ?>

          <?php if (empty($days) || empty($times)): ?>
            <div class="alert alert-info">
              Add at least one <a href="<?php echo WEB_ROOT; ?>module/generic/index.php?t=tblschedule_day">Schedule Day</a>
              and one <a href="<?php echo WEB_ROOT; ?>module/generic/index.php?t=tblschedule_time">Schedule Time</a>
              first, then you can assign classes here.
            </div>
          <?php endif; ?>

          <div class="table-responsive">
            <table id="tblschedule" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th style="width:60px;">#</th>
                  <th>Time</th>
                  <th>Day</th>
                  <th>Code</th>
                  <th>Subject Description</th>
                  <th class="text-center" style="width:70px;">Units</th>
                  <th>Room</th>
                  <th>Instructor</th>
                  <th style="width:110px;">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($rows)): ?>
                  <tr><td colspan="9" class="text-center text-muted">No classes scheduled for this section yet.</td></tr>
                <?php else: $i = 1; foreach ($rows as $r):
                        $inst = trim((string)$r->INSTRUCTOR_NAME) !== '' ? $r->INSTRUCTOR_NAME : $r->TEACHER; ?>
                  <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo ss_e(ss_t($r->TIME_START).' - '.ss_t($r->TIME_END)); ?></td>
                    <td><?php echo ss_e($r->DAY_NAME); ?></td>
                    <td><?php echo ss_e($r->SUBJECT_CODE); ?></td>
                    <td><?php echo ss_e($r->SUBJECT_NAME); ?></td>
                    <td class="text-center"><?php echo (int)$r->UNITS; ?></td>
                    <td><?php echo ss_e($r->ROOM_NAME); ?></td>
                    <td><?php echo ss_e($inst); ?></td>
                    <td>
                      <button type="button" class="btn btn-warning btn-xs editEntry" SCHEDULE_ID="<?php echo (int)$r->SCHEDULE_ID; ?>"><span class="fa fa-edit"></span></button>
                      <button type="button" class="btn btn-danger btn-xs deleteEntry" SCHEDULE_ID="<?php echo (int)$r->SCHEDULE_ID; ?>" SECTION_ID="<?php echo $sectionId; ?>"><span class="fa fa-trash"></span></button>
                    </td>
                  </tr>
                <?php endforeach; endif; ?>
              </tbody>
              <?php if (!empty($rows)): ?>
              <tfoot>
                <tr>
                  <th colspan="5" class="text-right">Total Units</th>
                  <th class="text-center"><?php echo $totalUnits; ?></th>
                  <th colspan="3"></th>
                </tr>
              </tfoot>
              <?php endif; ?>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php if ($section): ?>
<?php
/* Reusable field markup for both modals. $p is the id prefix ('' for add,
   'edit_' for edit) so the two modals never share DOM ids. */
function ss_fields($p, $subjects, $days, $times, $rooms, $teachers) {
  echo '<div class="form-group"><label>Subject</label><select class="form-control" name="SUBJECT_ID" id="'.$p.'SUBJECT_ID" required>';
  echo '<option value="">-- Select subject --</option>';
  foreach ($subjects as $s) {
    echo '<option value="'.(int)$s->SUBJECT_ID.'">'.ss_e($s->SUBJECT_CODE.' - '.$s->SUBJECT_NAME.' ('.(int)$s->UNITS.'u)').'</option>';
  }
  echo '</select></div>';

  echo '<div class="form-row">';
  echo '<div class="form-group col-md-6"><label>Day</label><select class="form-control" name="DAY_ID" id="'.$p.'DAY_ID" required>';
  echo '<option value="">-- Day --</option>';
  foreach ($days as $d) { echo '<option value="'.(int)$d->DAY_ID.'">'.ss_e($d->DAY_NAME).'</option>'; }
  echo '</select></div>';

  echo '<div class="form-group col-md-6"><label>Time</label><select class="form-control" name="TIME_ID" id="'.$p.'TIME_ID" required>';
  echo '<option value="">-- Time --</option>';
  foreach ($times as $t) { echo '<option value="'.(int)$t->TIME_ID.'">'.ss_e(ss_t($t->TIME_START).' - '.ss_t($t->TIME_END)).'</option>'; }
  echo '</select></div>';
  echo '</div>';

  echo '<div class="form-group"><label>Room</label><select class="form-control" name="ROOM_ID" id="'.$p.'ROOM_ID">';
  echo '<option value="">-- Room (optional) --</option>';
  foreach ($rooms as $r) { echo '<option value="'.(int)$r->ROOM_ID.'">'.ss_e($r->ROOM_NAME).'</option>'; }
  echo '</select></div>';

  echo '<div class="form-group"><label>Instructor</label><select class="form-control" name="TEACHER_UID" id="'.$p.'TEACHER_UID">';
  echo '<option value="">-- Registered teacher (optional) --</option>';
  foreach ($teachers as $u) { echo '<option value="'.(int)$u->UID.'">'.ss_e($u->DISPLAYNAME).'</option>'; }
  echo '</select></div>';

  echo '<div class="form-group"><label>Instructor name override</label>';
  echo '<input type="text" class="form-control" name="INSTRUCTOR_NAME" id="'.$p.'INSTRUCTOR_NAME" placeholder="Type a name if the teacher has no account">';
  echo '<small class="text-muted">If filled, this name is printed instead of the selected teacher.</small></div>';
}
?>

<!-- Add modal -->
<div class="modal fade" id="AddNewEntry">
  <div class="modal-dialog">
    <form action="<?php echo WEB_ROOT; ?>module/setschedule/controller.php?action=add" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Class to <?php echo ss_e($section->COURSE_NAME.' - '.$section->SECTION_NAME); ?></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="SECTION_ID" value="<?php echo $sectionId; ?>">
          <?php ss_fields('', $subjects, $days, $times, $rooms, $teachers); ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" name="save" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit modal -->
<div class="modal fade" id="editEntry">
  <div class="modal-dialog">
    <form action="<?php echo WEB_ROOT; ?>module/setschedule/controller.php?action=edit" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Modify Class</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="SECTION_ID" value="<?php echo $sectionId; ?>">
          <input type="hidden" name="SCHEDULE_ID" id="edit_SCHEDULE_ID" value="">
          <?php ss_fields('edit_', $subjects, $days, $times, $rooms, $teachers); ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" name="edit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>