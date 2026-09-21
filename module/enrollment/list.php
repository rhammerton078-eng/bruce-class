<?php
/* Enrollment module - list + all flow modals.
   $mydb is set up by include/initialize.php, loaded by template.php. */
global $mydb;
require_once(dirname(__FILE__)."/fees.php");
enroll_ensure_schema($mydb); // adds the payment columns if they are missing

$regSchoolYears = array();
$mydb->setQuery("SELECT SY_ID, SCHOOL_YEAR, STATUS FROM `tblschoolyear` ORDER BY SCHOOL_YEAR DESC");
foreach ($mydb->loadResultList() as $r) { $regSchoolYears[] = $r; }

$regCourses = array();
$mydb->setQuery("SELECT COURSE_ID, COURSE_CODE, COURSE_NAME FROM `tblcourses` WHERE STATUS = 'Active' ORDER BY COURSE_CODE ASC");
foreach ($mydb->loadResultList() as $r) { $regCourses[] = $r; }

/* Count for the Pending Online Payments badge */
$pendingCount = 0;
$mydb->setQuery("SELECT PAYMENT_ID FROM `tblpayments` WHERE STATUS = 'Pending'");
$pendingCount = $mydb->num_rows();

$regYearLevels = array();
$mydb->setQuery("SELECT COURSE_NAME FROM `tblcourses` WHERE STATUS='Active' ORDER BY COURSE_ID ASC");
foreach ($mydb->loadResultList() as $r) { $regYearLevels[] = $r->COURSE_NAME; }
if (empty($regYearLevels)) { $regYearLevels = array('Nursery 1','Nursery 2','Kindergarten','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10'); }
/* DepEd K to 12: no semesters in basic education. A learner enrolls once
   for the whole school year. */
$regSemesters  = array('Whole Year');
$regCategories = array('New', 'Old', 'Transferee', 'Returnee', 'Shiftee');
$regStatuses   = array('Registered', 'Assigned', 'Sectioned', 'Paid', 'Enrolled', 'Dropped', 'Completed');
?>
<style>
  .flowbar .badge { font-size: 90%; }
  .flowbar .sep { color:#adb5bd; margin:0 4px; }
  @media print {
    .main-header, .main-sidebar, .content-header, .main-footer,
    #statusFilters, #printEnrollment, #pendingBtn, .flowbar, td:last-child, th:last-child, .brand-link { display: none !important; }
    .content-wrapper { margin-left: 0 !important; }
  }
</style>
<section class="content">
  <div class="container-fluid">
    <?php check_message(); ?>

    <div class="callout callout-secondary flowbar py-2 mb-3">
      <strong>Enrollment flow:</strong> Student &gt;
      <span class="badge badge-secondary">Registered</span> <span class="sep">&rarr;</span>
      <span class="badge badge-info">Assigned</span> <span class="sep">&rarr;</span>
      <span class="badge badge-primary">Sectioning</span> <span class="sep">&rarr;</span>
      <span class="badge badge-success">Payment</span> <span class="sep">&rarr;</span>
      <span class="badge badge-warning">Paid</span> <span class="sep">&rarr;</span>
      <span class="badge badge-success">Enrolled</span>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Enrollment Records</h3>
            <div class="card-tools">
              <button type="button" id="pendingBtn" class="btn btn-sm btn-warning mr-2">
                <i class="fa fa-clock"></i> Pending Online Payments
                <span class="badge badge-light" id="pendingCount"><?php echo (int)$pendingCount; ?></span>
              </button>
              <div class="btn-group" id="statusFilters">
                <button type="button" class="btn btn-sm btn-outline-secondary active" data-filter="">All</button>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-filter="Registered">Registered</button>
                <button type="button" class="btn btn-sm btn-outline-info" data-filter="Assigned">Assigned</button>
                <button type="button" class="btn btn-sm btn-outline-primary" data-filter="Sectioned">Sectioned</button>
                <button type="button" class="btn btn-sm btn-outline-warning" data-filter="Paid">Paid</button>
                <button type="button" class="btn btn-sm btn-outline-success" data-filter="Enrolled">Enrolled</button>
              </div>
              <button type="button" id="printEnrollment" class="btn btn-sm btn-default ml-2">
                <i class="fa fa-print"></i> Print
              </button>
            </div>
          </div>
          <div class="card-body">
            <table id="tblenrollmentlist" class="table table-bordered table-striped" style="width:100%">
              <thead>
                <tr>
                  <th>#</th>
                  <th>ID No.</th>
                  <th>Student Name</th>
                  <th>Course</th>
                  <th>Academic Year</th>
                  <th>Year Level</th>
                  <th>Section</th>
                  <th>Amount Due</th>
                  <th>Amount Paid</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <small class="text-muted">New records start from <strong>Student &gt; Reg</strong>. This screen completes them.</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== PENDING ONLINE PAYMENTS ===================== -->
<div class="modal fade" id="pendingModal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h4 class="modal-title"><i class="fa fa-clock"></i>&nbsp; Pending Online Payments</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <table class="table table-sm table-bordered">
          <thead>
            <tr><th>Date</th><th>ID No.</th><th>Student</th><th>Type</th><th>Amount</th><th>Ref #</th><th>Proof</th><th>Action</th></tr>
          </thead>
          <tbody id="pendingRows">
            <tr><td colspan="8" class="text-center text-muted">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- ===================== ASSIGN SUBJECTS ===================== -->
<div class="modal fade" id="assignModal">
  <div class="modal-dialog modal-lg">
    <form action="controller.php?action=assign" method="POST">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title"><i class="fa fa-book"></i>&nbsp; Assign Subjects</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="A_EID" id="A_EID" value="">
          <div class="row mb-2">
            <div class="col-sm-4"><small class="text-muted d-block">ID No.</small><strong id="A_IDNO_TEXT">-</strong></div>
            <div class="col-sm-8"><small class="text-muted d-block">Student Name</small><strong id="A_NAME_TEXT">-</strong></div>
          </div>
          <table class="table table-sm table-bordered">
            <thead>
              <tr><th style="width:60px">Take</th><th>Code</th><th>Description</th><th style="width:70px">Units</th><th style="width:120px">Amount</th></tr>
            </thead>
            <tbody id="A_SUBJECTS">
              <tr><td colspan="5" class="text-center text-muted">Loading...</td></tr>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="3" class="text-right">Total</th>
                <th id="A_TOTAL_UNITS">0</th>
                <th id="A_TOTAL_AMOUNT">P0.00</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-info"><i class="fa fa-check"></i> Save Subjects</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- ===================== PAYMENT ===================== -->
<div class="modal fade" id="paymentModal">
  <div class="modal-dialog modal-lg">
    <form action="controller.php?action=pay" method="POST">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h4 class="modal-title"><i class="fa fa-cash-register"></i>&nbsp; Payment</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="PAY_EID" id="PAY_EID" value="">
          <div class="row mb-2">
            <div class="col-sm-4"><small class="text-muted d-block">ID No.</small><strong id="PAY_IDNO_TEXT">-</strong></div>
            <div class="col-sm-8"><small class="text-muted d-block">Student Name</small><strong id="PAY_NAME_TEXT">-</strong></div>
          </div>

          <div class="row">
            <div class="col-sm-6">
              <div class="callout callout-info py-2">
                <small class="text-muted d-block">Registration Fee (flat, unlocks enrollment)</small>
                <table class="w-100"><tr>
                  <td><small class="text-muted">Paid</small><br><strong id="PAY_REG_PAID">-</strong></td>
                  <td><small class="text-muted">Due</small><br><strong id="PAY_REG_DUE">-</strong></td>
                  <td><small class="text-muted">Balance</small><br><strong id="PAY_REG_BAL" class="text-danger">-</strong></td>
                </tr></table>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="callout callout-info py-2">
                <small class="text-muted d-block">Tuition / Units (payable any time)</small>
                <table class="w-100"><tr>
                  <td><small class="text-muted">Paid</small><br><strong id="PAY_TUI_PAID">-</strong></td>
                  <td><small class="text-muted">Due</small><br><strong id="PAY_TUI_DUE">-</strong></td>
                  <td><small class="text-muted">Balance</small><br><strong id="PAY_TUI_BAL" class="text-danger">-</strong></td>
                </tr></table>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="col-form-label-sm">This payment is for</label>
                <select class="form-control form-control-sm" name="PAY_TYPE" id="PAY_TYPE">
                  <option value="Registration">Registration</option>
                  <option value="Tuition">Tuition / Units</option>
                </select>
                <small class="text-muted">Registration and tuition are tracked separately - a student can be enrolled as soon as the registration fee is paid, then settle tuition later.</small>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="col-form-label-sm">Amount to Pay</label>
                <input type="number" step="0.01" min="0" class="form-control form-control-sm" name="PAY_AMOUNT" id="PAY_AMOUNT" placeholder="0.00" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="col-form-label-sm">OR Number</label>
                <input type="text" class="form-control form-control-sm" name="PAY_OR" id="PAY_OR" placeholder="e.g. OR-00123">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="col-form-label-sm">Cashier</label>
                <input type="text" class="form-control form-control-sm" name="PAY_CASHIER" id="PAY_CASHIER" placeholder="Enter cashier name">
              </div>
            </div>
          </div>

          <small class="text-muted">Payment History</small>
          <table class="table table-sm table-bordered mt-1">
            <thead><tr><th>Date</th><th>Type</th><th>Amount</th><th>OR #</th><th>Cashier</th><th>Status</th></tr></thead>
            <tbody id="PAY_HISTORY"><tr><td colspan="6" class="text-center text-muted">No payments yet.</td></tr></tbody>
          </table>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> Record Payment</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- ===================== SECTIONING ===================== -->
<div class="modal fade" id="sectioningModal">
  <div class="modal-dialog">
    <form action="controller.php?action=section" method="POST">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title"><i class="fa fa-chalkboard"></i>&nbsp; Sectioning</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="SEC_EID" id="SEC_EID" value="">
          <input type="hidden" name="SEC_COURSE" id="SEC_COURSE" value="">
          <input type="hidden" name="SEC_SY" id="SEC_SY" value="">
          <div class="row mb-3">
            <div class="col-sm-3 text-center">
              <img id="SEC_PICTURE" src="" class="img-circle" style="width:80px; height:80px; object-fit:cover;" alt="Student photo">
            </div>
            <div class="col-sm-9">
              <small class="text-muted d-block">ID No.</small><strong id="SEC_IDNO_TEXT">-</strong>
              <small class="text-muted d-block mt-2">Student Name</small><strong id="SEC_NAME_TEXT">-</strong>
            </div>
          </div>
          <div class="callout callout-info py-2 mb-3">
            <small class="text-muted d-block">Course</small><strong id="SEC_COURSE_TEXT">-</strong>
            <small class="text-muted d-block mt-2">Academic Year</small><strong id="SEC_TERM_TEXT">-</strong>
          </div>
          <div class="form-group">
            <label for="SEC_SECTION" class="col-form-label col-form-label-sm">Section</label>
            <select class="form-control form-control-sm" name="SEC_SECTION" id="SEC_SECTION" required>
              <option value="">Select course and academic year first</option>
            </select>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Sectioning</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- ===================== EDIT ENROLLMENT ===================== -->
<div class="modal fade" id="editEnrollmentModal">
  <div class="modal-dialog modal-lg">
    <form action="controller.php?action=edit" method="POST">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Enrollment</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="E_EID" id="E_EID" value="">
          <div class="row mb-3">
            <div class="col-sm-2 text-center">
              <img id="E_PICTURE" src="" class="img-circle" style="width:70px; height:70px; object-fit:cover;" alt="Student photo">
            </div>
            <div class="col-sm-10">
              <small class="text-muted d-block">ID No.</small><strong id="E_IDNO_TEXT">-</strong>
              <small class="text-muted d-block mt-2">Student Name</small><strong id="E_NAME_TEXT">-</strong>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6"><div class="form-group">
              <label for="E_SY" class="col-form-label col-form-label-sm">Academic Year</label>
              <select class="form-control form-control-sm" name="E_SY" id="E_SY" required>
                <option value="">Select Academic Year</option>
                <?php foreach ($regSchoolYears as $sy) { ?>
                <option value="<?php echo $sy->SY_ID; ?>"><?php echo htmlspecialchars($sy->SCHOOL_YEAR); ?><?php echo ($sy->STATUS == 'Active') ? ' (Active)' : ''; ?></option>
                <?php } ?>
              </select>
            </div></div>
            <?php /* Semester dropdown removed: DepEd K to 12 basic education has no
                     semesters, a learner enrols once for the whole school year. The
                     value is set to 'Whole Year' server-side in controller.php, and
                     the database column is kept so existing records stay valid. */ ?>
            <input type="hidden" name="E_SEMESTER" id="E_SEMESTER" value="Whole Year">
            <div class="col-sm-12"><div class="form-group">
              <label for="E_COURSE" class="col-form-label col-form-label-sm">Course</label>
              <select class="form-control form-control-sm" name="E_COURSE" id="E_COURSE" required>
                <option value="">Select Course</option>
                <?php foreach ($regCourses as $c) { ?>
                <option value="<?php echo $c->COURSE_ID; ?>"><?php echo htmlspecialchars($c->COURSE_CODE.' - '.$c->COURSE_NAME); ?></option>
                <?php } ?>
              </select>
            </div></div>
            <div class="col-sm-6"><div class="form-group">
              <label for="E_SECTION" class="col-form-label col-form-label-sm">Section</label>
              <select class="form-control form-control-sm" name="E_SECTION" id="E_SECTION">
                <option value="">Select course and academic year first</option>
              </select>
            </div></div>
            <div class="col-sm-6"><div class="form-group">
              <label for="E_YEARLEVEL" class="col-form-label col-form-label-sm">Year Level</label>
              <select class="form-control form-control-sm" name="E_YEARLEVEL" id="E_YEARLEVEL" required>
                <option value="">Select Year Level</option>
                <?php foreach ($regYearLevels as $yl) { ?>
                <option value="<?php echo $yl; ?>"><?php echo $yl; ?></option>
                <?php } ?>
              </select>
            </div></div>
            <div class="col-sm-6"><div class="form-group">
              <label for="E_CURRICULUM" class="col-form-label col-form-label-sm">Curriculum Yr</label>
              <input type="text" class="form-control form-control-sm" name="E_CURRICULUM" id="E_CURRICULUM" placeholder="e.g. 2023-2024">
            </div></div>
            <div class="col-sm-6"><div class="form-group">
              <label for="E_CATEGORY" class="col-form-label col-form-label-sm">Category</label>
              <select class="form-control form-control-sm" name="E_CATEGORY" id="E_CATEGORY">
                <?php foreach ($regCategories as $cat) { ?>
                <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
                <?php } ?>
              </select>
            </div></div>
            <div class="col-sm-6"><div class="form-group">
              <label for="E_STATUS" class="col-form-label col-form-label-sm">Status</label>
              <select class="form-control form-control-sm" name="E_STATUS" id="E_STATUS">
                <?php foreach ($regStatuses as $st) { ?>
                <option value="<?php echo $st; ?>"><?php echo $st; ?></option>
                <?php } ?>
              </select>
              <small class="text-muted">Manually overriding this skips the normal flow guards.</small>
            </div></div>
            <div class="col-sm-6"><div class="form-group">
              <label for="E_DATE_RESERVED" class="col-form-label col-form-label-sm">Date Registered</label>
              <input type="date" class="form-control form-control-sm" name="E_DATE_RESERVED" id="E_DATE_RESERVED">
            </div></div>
            <div class="col-sm-6"><div class="form-group">
              <label for="E_DATE_ENROLLED" class="col-form-label col-form-label-sm">Date Enrolled</label>
              <input type="date" class="form-control form-control-sm" name="E_DATE_ENROLLED" id="E_DATE_ENROLLED">
            </div></div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
        </div>
      </div>
    </form>
  </div>
</div>