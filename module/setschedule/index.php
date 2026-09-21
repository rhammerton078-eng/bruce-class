<?php
// DESTINATION: C:\xampp\htdocs\bruce-class\module\setschedule\index.php
// ACTION:      NEW FILE (create folder module\setschedule if missing)
?>
<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC.
// Set Schedule - builds a section's timetable (subject / day / time / room /
// instructor) from the Scheduling lookups, and prints it as an official
// Schedule of Classes.

require_once("../../include/initialize.php");
require_role([ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR]);

$title   = "Set Schedule";
$content = 'list.php';

require_once("../../theme/template.php");
?>

<script type="text/javascript">
$(function () {
  $('#tblschedule').DataTable({
    "paging": false,
    "info": false,
    "searching": false,
    "ordering": false,
    "autoWidth": false
  });
});

/* Jump to another section without a submit button. */
function sjcsGoSection(sel) {
  var id = sel.value;
  window.location = "<?php echo WEB_ROOT; ?>module/setschedule/index.php" + (id ? ("?section_id=" + id) : "");
}

/* Edit: pull one row and fill the edit modal. */
$(document).on('click', '.editEntry', function () {
  var id = $(this).attr("SCHEDULE_ID");
  $.ajax({
    url: "<?php echo WEB_ROOT; ?>module/setschedule/ajax.php",
    method: "POST",
    data: { SCHEDULE_ID: id },
    dataType: "json",
    success: function (d) {
      $('#edit_SCHEDULE_ID').val(d.SCHEDULE_ID);
      $('#edit_SUBJECT_ID').val(d.SUBJECT_ID);
      $('#edit_DAY_ID').val(d.DAY_ID);
      $('#edit_TIME_ID').val(d.TIME_ID);
      $('#edit_ROOM_ID').val(d.ROOM_ID);
      $('#edit_TEACHER_UID').val(d.TEACHER_UID);
      $('#edit_INSTRUCTOR_NAME').val(d.INSTRUCTOR_NAME);
      $('#editEntry').modal('show');
    }
  });
});

$(document).on('click', '.deleteEntry', function () {
  var id = $(this).attr("SCHEDULE_ID");
  var sec = $(this).attr("SECTION_ID");
  Swal.fire({
    title: 'Remove this class from the schedule?',
    text: "This only removes the timetable entry, not the subject itself.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, remove it',
    cancelButtonText: 'Cancel'
  }).then(function (r) {
    if (r.value) {
      window.location.href = "<?php echo WEB_ROOT; ?>module/setschedule/controller.php?action=delete&id=" + id + "&section_id=" + sec;
    }
  });
});
</script>