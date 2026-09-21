<section class="content">
  <div class="container-fluid">
    <?php check_message(); ?>
    <div class="card">
      <div class="card-header"><h3 class="card-title">Admission Payments</h3></div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Applicant</th>
              <th>Type</th>
              <th>Amount</th>
              <th>Method</th>
              <th>Reference</th>
              <th>Date Paid</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($payments)): ?>
              <tr><td colspan="9" class="text-center text-muted">No admission payments submitted yet.</td></tr>
            <?php else: foreach ($payments as $p): ?>
              <tr>
                <td><?php echo $p->PAYMENT_ID; ?></td>
                <td><?php echo htmlspecialchars($p->FNAME.' '.$p->LNAME); ?></td>
                <td><?php echo htmlspecialchars($p->APPLICANT_TYPE); ?></td>
                <td>&#8369;<?php echo number_format((float)$p->AMOUNT, 2); ?></td>
                <td><?php echo htmlspecialchars($p->METHOD); ?></td>
                <td><?php echo htmlspecialchars($p->REFERENCE_NO ?? '-'); ?></td>
                <td><?php echo date('M j, Y', strtotime($p->DATE_PAID)); ?></td>
                <td>
                  <?php
                    $cls = $p->STATUS === 'Verified' ? 'success' : ($p->STATUS === 'Rejected' ? 'danger' : 'warning');
                  ?>
                  <span class="badge badge-<?php echo $cls; ?>"><?php echo htmlspecialchars($p->STATUS); ?></span>
                </td>
                <td>
                  <?php if ($p->STATUS === 'Pending'): ?>
                    <a href="?action=verify&id=<?php echo $p->PAYMENT_ID; ?>" class="btn btn-success btn-xs" onclick="return confirm('Verify this payment?');">Verify</a>
                    <a href="?action=reject&id=<?php echo $p->PAYMENT_ID; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Reject this payment?');">Reject</a>
                  <?php else: ?>
                    <span class="text-muted">-</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
