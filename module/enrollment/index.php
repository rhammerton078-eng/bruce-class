<?php
// Enrollment module
/* Entry point + client logic for the six-stage enrollment flow:
   Registered -> Assigned -> Sectioning -> Payment -> Paid -> Enrolled */
require_once("../../include/initialize.php");
require_role([ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR]);

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';
$title  = "Enrollment";
$header = $view;
$content = 'list.php';

require_once("../../theme/template.php");
?>

<script type="text/javascript">
var enrollTable;
var currentStatusFilter = '';

function peso(n){ n = parseFloat(n); if (isNaN(n)) n = 0; return 'P' + n.toLocaleString('en-US',{minimumFractionDigits:2, maximumFractionDigits:2}); }

$(document).ready(function() {

  enrollTable = $('#tblenrollmentlist').DataTable({
    "processing": true, "serverSide": true, "scrollX": true, "order": [],
    "ajax": {
      url: "<?php echo WEB_ROOT; ?>module/enrollment/ajax.php",
      type: "POST",
      data: function (d) { d.status_filter = currentStatusFilter; }
    },
    "columnDefs": [ { "orderable": false, "targets": [8, 9, 11] } ]
  });

  $('#statusFilters button').on('click', function(){
    $('#statusFilters button').removeClass('active');
    $(this).addClass('active');
    currentStatusFilter = $(this).data('filter');
    enrollTable.ajax.reload();
  });

  $('#printEnrollment').on('click', function () { window.print(); });

  $('#pendingBtn').on('click', function(){ loadPending(); $('#pendingModal').modal('show'); });
});

/* ---------------- SECTIONS dropdown ---------------- */
function loadSections(targetId, courseId, syId, preselect, allowBlank) {
  var $sel = $('#' + targetId);
  if (!courseId || !syId) { $sel.html('<option value="">Select course and academic year first</option>'); return; }
  $sel.html('<option value="">Loading...</option>');
  $.ajax({
    url: "<?php echo WEB_ROOT; ?>module/enrollment/ajax.php", method: "POST",
    data: { act: 'sections', COURSE_ID: courseId, SY_ID: syId }, dataType: "json",
    success: function(rows) {
      var html = allowBlank ? '<option value="">Not sectioned yet</option>' : '<option value="">Select Section</option>';
      if (!rows || rows.length === 0) { $sel.html('<option value="">No section for this course and academic year</option>'); return; }
      $.each(rows, function(i, r){ html += '<option value="'+r.SECTION_ID+'">'+r.YEAR_LEVEL+' - '+r.SECTION_NAME+'</option>'; });
      $sel.html(html);
      if (preselect) { $sel.val(preselect); }
    },
    error: function() { $sel.html('<option value="">Could not load sections</option>'); }
  });
}

/* ---------------- STAGE 1: ASSIGN SUBJECTS ---------------- */
$(document).on('click', '.doAssign', function(){
  var eid = $(this).attr('EID');
  $.ajax({
    url: "<?php echo WEB_ROOT; ?>module/enrollment/ajax.php", method: "POST",
    data: { act: 'subjects', ENROLLMENT_ID: eid }, dataType: "json",
    success: function(d) {
      $('#A_EID').val(d.header.ENROLLMENT_ID);
      $('#A_IDNO_TEXT').text(d.header.IDNO || '-');
      $('#A_NAME_TEXT').text(d.header.FULLNAME || '-');
      var rows = '';
      if (!d.subjects || d.subjects.length === 0) {
        rows = '<tr><td colspan="5" class="text-center text-muted">No subjects are set up for this course, year level and semester yet.</td></tr>';
      } else {
        $.each(d.subjects, function(i, s){
          rows += '<tr>'
            + '<td class="text-center"><input type="checkbox" class="asub" name="SUBJECT[]" value="'+s.SUBJECT_ID+'" data-units="'+s.UNITS+'" data-amount="'+s.AMOUNT+'" '+(s.CHECKED?'checked':'')+'></td>'
            + '<td>'+s.SUBJECT_CODE+'</td><td>'+s.SUBJECT_NAME+'</td>'
            + '<td>'+s.UNITS+'</td><td>'+peso(s.AMOUNT)+'</td></tr>';
        });
      }
      $('#A_SUBJECTS').html(rows);
      recalcAssign();
      $('#assignModal').modal('show');
    },
    error: function(){ alert('Could not load subjects for that record.'); }
  });
});

function recalcAssign(){
  var units = 0, amount = 0;
  $('#A_SUBJECTS .asub:checked').each(function(){
    units  += parseFloat($(this).data('units'))  || 0;
    amount += parseFloat($(this).data('amount')) || 0;
  });
  $('#A_TOTAL_UNITS').text(units);
  $('#A_TOTAL_AMOUNT').text(peso(amount));
}
$(document).on('change', '.asub', recalcAssign);

/* ---------------- STAGE 2: SECTIONING ---------------- */
$(document).on('click', '.doSectioning', function(){
  var eid = $(this).attr('EID');
  $.ajax({
    url: "<?php echo WEB_ROOT; ?>module/enrollment/ajax.php", method: "POST",
    data: { act: 'row', ENROLLMENT_ID: eid }, dataType: "json",
    success: function(d) {
      $('#SEC_EID').val(d.ENROLLMENT_ID);
      $('#SEC_COURSE').val(d.COURSE_ID);
      $('#SEC_SY').val(d.SY_ID);
      $('#SEC_IDNO_TEXT').text(d.IDNO || '-');
      $('#SEC_NAME_TEXT').text(d.FULLNAME || '-');
      $('#SEC_COURSE_TEXT').text(d.COURSE_TEXT || '-');
      $('#SEC_TERM_TEXT').text((d.SCHOOL_YEAR || '') + ' / ' + (d.SEMESTER || ''));
      $('#SEC_PICTURE').attr('src', d.PICTURE_URL ? d.PICTURE_URL : '<?php echo WEB_ROOT; ?>module/student/image/default.png');
      loadSections('SEC_SECTION', d.COURSE_ID, d.SY_ID, d.SECTION_ID, false);
      $('#sectioningModal').modal('show');
    },
    error: function() { alert('Could not load that enrollment record.'); }
  });
});

/* ---------------- STAGE 3: PAYMENT ---------------- */
$(document).on('click', '.doPayment', function(){
  var eid = $(this).attr('EID');
  $.ajax({
    url: "<?php echo WEB_ROOT; ?>module/enrollment/ajax.php", method: "POST",
    data: { act: 'payinfo', ENROLLMENT_ID: eid }, dataType: "json",
    success: function(d) {
      $('#PAY_EID').val(d.header.ENROLLMENT_ID);
      $('#PAY_IDNO_TEXT').text(d.header.IDNO || '-');
      $('#PAY_NAME_TEXT').text(d.header.FULLNAME || '-');

      var f = d.fees || {};
      $('#PAY_REG_PAID').text(peso(f.reg_paid)); $('#PAY_REG_DUE').text(peso(f.reg_due)); $('#PAY_REG_BAL').text(peso(f.reg_bal));
      $('#PAY_TUI_PAID').text(peso(f.tui_paid)); $('#PAY_TUI_DUE').text(peso(f.tui_due)); $('#PAY_TUI_BAL').text(peso(f.tui_bal));

      var rows = '';
      if (!d.history || d.history.length === 0) {
        rows = '<tr><td colspan="6" class="text-center text-muted">No payments yet.</td></tr>';
      } else {
        $.each(d.history, function(i, p){
          rows += '<tr><td>'+p.DATE+'</td><td>'+p.TYPE+'</td><td>'+peso(p.AMOUNT)+'</td>'
            + '<td>'+(p.OR||'-')+'</td><td>'+(p.CASHIER||'-')+'</td>'
            + '<td><span class="badge badge-'+(p.STATUS==='Approved'?'success':(p.STATUS==='Pending'?'warning':'danger'))+'">'+p.STATUS+'</span></td></tr>';
        });
      }
      $('#PAY_HISTORY').html(rows);
      $('#PAY_AMOUNT').val('');
      $('#paymentModal').modal('show');
    },
    error: function(){ alert('Could not load the payment details.'); }
  });
});

/* ---------------- PENDING ONLINE PAYMENTS ---------------- */
function loadPending(){
  $.ajax({
    url: "<?php echo WEB_ROOT; ?>module/enrollment/ajax.php", method: "POST",
    data: { act: 'pending' }, dataType: "json",
    success: function(d) {
      $('#pendingCount').text(d.count);
      var rows = '';
      if (!d.rows || d.rows.length === 0) {
        rows = '<tr><td colspan="8" class="text-center text-muted">No pending online payments.</td></tr>';
      } else {
        $.each(d.rows, function(i, p){
          var proof = p.PROOF ? '<a href="'+p.PROOF+'" target="_blank">View</a>' : '<span class="text-muted">-</span>';
          rows += '<tr><td>'+p.DATE+'</td><td>'+p.IDNO+'</td><td>'+p.STUDENT+'</td><td>'+p.TYPE+'</td>'
            + '<td>'+peso(p.AMOUNT)+'</td><td>'+(p.REF||'-')+'</td><td>'+proof+'</td>'
            + '<td><a href="controller.php?action=approvepay&pid='+p.PAYMENT_ID+'" onclick="return confirm(\'Approve this payment?\');"><button class="btn btn-success btn-xs">Approve</button></a> '
            + '<a href="controller.php?action=rejectpay&pid='+p.PAYMENT_ID+'" onclick="return confirm(\'Reject this payment?\');"><button class="btn btn-danger btn-xs">Reject</button></a></td></tr>';
        });
      }
      $('#pendingRows').html(rows);
    },
    error: function(){ $('#pendingRows').html('<tr><td colspan="8" class="text-center text-danger">Could not load pending payments.</td></tr>'); }
  });
}

/* ---------------- EDIT ---------------- */
$(document).on('click', '.editEnrollment', function(){
  var eid = $(this).attr('EID');
  $.ajax({
    url: "<?php echo WEB_ROOT; ?>module/enrollment/ajax.php", method: "POST",
    data: { act: 'row', ENROLLMENT_ID: eid }, dataType: "json",
    success: function(d) {
      $('#E_EID').val(d.ENROLLMENT_ID);
      $('#E_IDNO_TEXT').text(d.IDNO || '-');
      $('#E_NAME_TEXT').text(d.FULLNAME || '-');
      $('#E_PICTURE').attr('src', d.PICTURE_URL ? d.PICTURE_URL : '<?php echo WEB_ROOT; ?>module/student/image/default.png');
      $('#E_SY').val(d.SY_ID);
      $('#E_SEMESTER').val(d.SEMESTER);
      $('#E_COURSE').val(d.COURSE_ID);
      $('#E_YEARLEVEL').val(d.YEAR_LEVEL);
      $('#E_CURRICULUM').val(d.CURRICULUM_YR || '');
      $('#E_CATEGORY').val(d.CATEGORY);
      $('#E_STATUS').val(d.STATUS);
      $('#E_DATE_RESERVED').val(d.DATE_RESERVED || '');
      $('#E_DATE_ENROLLED').val(d.DATE_ENROLLED || '');
      loadSections('E_SECTION', d.COURSE_ID, d.SY_ID, d.SECTION_ID, true);
      $('#editEnrollmentModal').modal('show');
    },
    error: function() { alert('Could not load that enrollment record.'); }
  });
});

$(document).on('change', '#E_COURSE, #E_SY', function(){
  loadSections('E_SECTION', $('#E_COURSE').val(), $('#E_SY').val(), '', true);
});
</script>