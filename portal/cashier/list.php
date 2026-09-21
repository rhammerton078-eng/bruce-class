<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3><?php echo (int)$pendingPayments; ?></h3>
            <p>Pending Payments</p>
          </div>
          <div class="icon"><i class="fa fa-hourglass-half"></i></div>
          <a href="<?php echo WEB_ROOT; ?>portal/cashier/admission_payments.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h3><?php echo (int)$verifiedToday; ?></h3>
            <p>Verified Today</p>
          </div>
          <div class="icon"><i class="fa fa-check-circle"></i></div>
          <a href="<?php echo WEB_ROOT; ?>portal/cashier/admission_payments.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>&#8369;<?php echo number_format((float)$todaysCollection, 2); ?></h3>
            <p>Today's Collection</p>
          </div>
          <div class="icon"><i class="fa fa-money-bill-wave"></i></div>
          <a href="<?php echo WEB_ROOT; ?>portal/cashier/admission_payments.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h3 class="card-title">Cashier Quick Links</h3></div>
      <div class="card-body">
        <p>Verify admission payments from the Admission Payments link, or record per-enrollment tuition/fee
           payments from the Payments module - both are in the sidebar.</p>
        <p class="text-muted mb-0 mt-2">
          Student-balance lookups across both payment types will appear here in a later phase.
        </p>
      </div>
    </div>
  </div>
</section>
