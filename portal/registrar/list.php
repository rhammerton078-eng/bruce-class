<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3><?php echo (int)$totalStudents; ?></h3>
            <p>Total Students</p>
          </div>
          <div class="icon"><i class="fa fa-user-graduate"></i></div>
          <a href="<?php echo WEB_ROOT; ?>module/student" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3><?php echo (int)$pendingApplications; ?></h3>
            <p>Pending Applications</p>
          </div>
          <div class="icon"><i class="fa fa-file-alt"></i></div>
          <a href="<?php echo WEB_ROOT; ?>portal/registrar/applicants.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h3><?php echo (int)$approvedApplications; ?></h3>
            <p>Approved Applications</p>
          </div>
          <div class="icon"><i class="fa fa-check-circle"></i></div>
          <a href="<?php echo WEB_ROOT; ?>portal/registrar/applicants.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
          <div class="inner">
            <h3><?php echo (int)$recentEnrollments; ?></h3>
            <p>Recent Enrollments</p>
          </div>
          <div class="icon"><i class="fa fa-clipboard-list"></i></div>
          <a href="<?php echo WEB_ROOT; ?>module/enrollment/index.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h3 class="card-title">Registrar Quick Links</h3></div>
      <div class="card-body">
        <p>Manage students, programs, subjects, sections, school years, and enrollment from the sidebar.</p>
        <p class="text-muted mb-0">
          Applicant review, entrance exam scheduling, and document verification will appear here once the
          Applicant Portal and admission pipeline are now live - see the Applicants link in the sidebar.
        </p>
      </div>
    </div>
  </div>
</section>
