<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3><?php echo (int)$assignedClasses; ?></h3>
            <p>Assigned Classes</p>
          </div>
          <div class="icon"><i class="fa fa-chalkboard-teacher"></i></div>
          <span class="small-box-footer">Class assignment UI not yet built &nbsp;</span>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h3 class="card-title">Teacher Quick Links</h3></div>
      <div class="card-body">
        <p>Attendance and Grades are available from the sidebar.</p>
        <p class="text-muted mb-0">
          <strong>Known limitation:</strong> those screens currently show every student's records, not just
          your own assigned classes. Scoping them to a teacher's specific sections (via the new
          <code>tblteacher_subjects</code> table) is real backend work planned for a follow-up phase, not yet done.
        </p>
      </div>
    </div>
  </div>
</section>
