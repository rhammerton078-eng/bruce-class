<?php
/* DepEd K to 12: grade levels come from tblcourses, and basic education has
   no semesters - a subject runs for the WHOLE SCHOOL YEAR. */
global $mydb;
$sjcsGradeLevels = array();
if (isset($mydb) && $mydb->tableExists('tblcourses')) {
    $mydb->setQuery("SELECT COURSE_NAME FROM `tblcourses` WHERE STATUS='Active' ORDER BY COURSE_ID ASC");
    foreach ($mydb->loadResultList() as $r) { $sjcsGradeLevels[] = $r->COURSE_NAME; }
}
if (empty($sjcsGradeLevels)) {
    $sjcsGradeLevels = array('Nursery 1','Nursery 2','Kindergarten','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10');
}

// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC.
$course = new Course();
$allCourses = $course->listOfCourses();
?>
<section class="content">

      <div class="container-fluid">
         <?php check_message(); ?>
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">List of Subjects</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tblsubject" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th width="5%">#</th>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Units</th>
                    <th>Course</th>
                    <th>Year Level</th>
                    <th>Semester</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>

                  </tbody>
                  <tfoot>

                  </tfoot>
                </table>
                  <div class="btn-group">

                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddNewEntry">Add New</button>

                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>

<!-----START of Add Form---->
     <div class="modal fade" id="AddNewEntry">
        <div class="modal-dialog">
        <form action="controller.php?action=add" method="POST">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add New Subject</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="row">

                    <div class="col-sm-12">
                      <div class="form-group">
                       <label for="SUBJECT_CODE"  class="col-form-label col-form-label-sm">Subject Code</label>
                        <input type="text" class="form-control form-control-sm" name="SUBJECT_CODE"
                        id="SUBJECT_CODE" placeholder="e.g. IT101" required>
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                       <label for="SUBJECT_NAME"  class="col-form-label col-form-label-sm">Subject Name</label>
                        <input type="text" class="form-control form-control-sm" name="SUBJECT_NAME"
                        id="SUBJECT_NAME" placeholder="e.g. Introduction to Computing" required>
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                       <label for="UNITS"  class="col-form-label col-form-label-sm">Units <span class="text-muted">(not used in basic education)</span></label>
                        <input type="number" step="1" min="1" class="form-control form-control-sm" name="UNITS"
                        id="UNITS" placeholder="3" value="3" required>
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                       <label for="COURSE_ID" class="col-form-label col-form-label-sm">Course</label>
                        <select class="form-control form-control-sm" name="COURSE_ID" id="COURSE_ID" required>
                          <option value="">-- Select Course --</option>
                          <?php foreach($allCourses as $c): ?>
                          <option value="<?php echo $c->COURSE_ID; ?>"><?php echo htmlspecialchars($c->COURSE_CODE." - ".$c->COURSE_NAME); ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                       <label for="YEAR_LEVEL" class="col-form-label col-form-label-sm">Grade Level</label>
                        <select class="form-control form-control-sm" name="YEAR_LEVEL" id="YEAR_LEVEL" required>
                          <?php foreach ($sjcsGradeLevels as $gl) { ?>
                          <option value="<?php echo htmlspecialchars($gl); ?>"><?php echo htmlspecialchars($gl); ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                       <label for="SEMESTER" class="col-form-label col-form-label-sm">Term</label>
                        <select class="form-control form-control-sm" name="SEMESTER" id="SEMESTER" required>
                          <option value="Whole Year">Whole Year</option>
                        </select>
                        <small class="text-muted">Basic education subjects run for the whole school year.</small>
                      </div>
                    </div>

                  </div>

            </div>
            <div class="modal-footer justify-content-between">
             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                 <button type="submit" class="btn btn-primary" name="save" type="submit">Save changes</button>

            </div>
          </div>
          </form>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

<!-----End of Add Form---->
<!-----Start of edit Form---->
<div class="modal fade" id="editEntry">
        <div class="modal-dialog">
        <form action="controller.php?action=edit" method="POST">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Modify Subject</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="row">
                    <input type="hidden" name="SUBJECT_ID" id="SUBJECT_ID">
                    <div class="col-sm-12">
                      <div class="form-group">
                       <label for="SUBJECT_CODE1"  class="col-form-label col-form-label-sm">Subject Code</label>
                        <input type="text" class="form-control form-control-sm" name="SUBJECT_CODE1"
                        id="SUBJECT_CODE1" placeholder="Subject Code">
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                       <label for="SUBJECT_NAME1"  class="col-form-label col-form-label-sm">Subject Name</label>
                        <input type="text" class="form-control form-control-sm" name="SUBJECT_NAME1"
                        id="SUBJECT_NAME1" placeholder="Subject Name">
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                       <label for="UNITS1"  class="col-form-label col-form-label-sm">Units <span class="text-muted">(not used in basic education)</span></label>
                        <input type="number" step="1" min="1" class="form-control form-control-sm" name="UNITS1"
                        id="UNITS1" placeholder="Units">
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                       <label for="COURSE_ID1" class="col-form-label col-form-label-sm">Course</label>
                        <select class="form-control form-control-sm" name="COURSE_ID1" id="COURSE_ID1">
                          <option value="">-- Select Course --</option>
                          <?php foreach($allCourses as $c): ?>
                          <option value="<?php echo $c->COURSE_ID; ?>"><?php echo htmlspecialchars($c->COURSE_CODE." - ".$c->COURSE_NAME); ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                       <label for="YEAR_LEVEL1" class="col-form-label col-form-label-sm">Grade Level</label>
                        <select class="form-control form-control-sm" name="YEAR_LEVEL1" id="YEAR_LEVEL1">
                          <?php foreach ($sjcsGradeLevels as $gl) { ?>
                          <option value="<?php echo htmlspecialchars($gl); ?>"><?php echo htmlspecialchars($gl); ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                       <label for="SEMESTER1" class="col-form-label col-form-label-sm">Term</label>
                        <select class="form-control form-control-sm" name="SEMESTER1" id="SEMESTER1">
                          <option value="Whole Year">Whole Year</option>
                        </select>
                        <small class="text-muted">Basic education subjects run for the whole school year.</small>
                      </div>
                    </div>

                  </div>

            </div>
            <div class="modal-footer justify-content-between">
             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                 <button type="submit" class="btn btn-primary" name="edit" type="submit">Save changes</button>

            </div>
          </div>
          </form>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>