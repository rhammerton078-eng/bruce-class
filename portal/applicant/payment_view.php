<style>
.sjcs-pay-card { background:#fff; border-radius:18px; padding:28px; box-shadow:0 4px 16px rgba(0,0,0,0.06); margin-bottom:20px; }
.sjcs-fee-row { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #eef1ef; }
.sjcs-fee-total { display:flex; justify-content:space-between; padding:14px 0 0; font-weight:800; font-size:1.1rem; color:var(--sjcs-dark-green); }
.sjcs-pay-card label { display:block; font-weight:600; margin:14px 0 6px; font-size:0.9rem; }
.sjcs-pay-card select, .sjcs-pay-card input { width:100%; padding:10px 13px; border-radius:8px; border:1px solid #d7ddd9; }
.sjcs-errors { background:#fdecea; border:1px solid #f5c2c0; color:#9b2226; border-radius:10px; padding:14px 16px; margin-bottom:16px; }
.sjcs-success { background:#e6f4ea; border:1px solid #a9d7b8; color:#1e6b3a; border-radius:10px; padding:14px 16px; margin-bottom:16px; }
.sjcs-badge { display:inline-block; padding:3px 12px; border-radius:999px; font-size:0.78rem; font-weight:700; }
.sjcs-badge-pending { background:#fff3cd; color:#8a6d00; }
.sjcs-badge-verified { background:#e6f4ea; color:#1e6b3a; }
.sjcs-badge-rejected { background:#fdecea; color:#9b2226; }
</style>

<?php if (!$formIsReady): ?>

  <div class="sjcs-pay-card">
    <h3 style="margin-top:0;">Complete your Admission Form first</h3>
    <p>You need to select a program and fill in your basic information before fees can be calculated.</p>
    <a href="<?php echo WEB_ROOT; ?>portal/applicant/admission_form.php" class="sjcs-btn sjcs-btn-primary">Go to Admission Form</a>
  </div>

<?php else: ?>

  <?php if ($saved): ?>
    <div class="sjcs-success">Your payment has been submitted and is awaiting verification by the Cashier.</div>
  <?php endif; ?>
  <?php if (!empty($errors)): ?>
    <div class="sjcs-errors"><ul style="margin:0; padding-left:18px;"><?php foreach ($errors as $e) { echo '<li>'.htmlspecialchars($e).'</li>'; } ?></ul></div>
  <?php endif; ?>

  <div class="sjcs-pay-card">
    <h3 style="margin-top:0;">Fees for a <?php echo htmlspecialchars($applicant->APPLICANT_TYPE); ?></h3>
    <?php foreach ($requiredFees as $f): ?>
      <div class="sjcs-fee-row">
        <span><?php echo htmlspecialchars($f->FEE_NAME); ?></span>
        <span>&#8369;<?php echo number_format((float)$f->DEFAULT_AMOUNT, 2); ?></span>
      </div>
    <?php endforeach; ?>
    <div class="sjcs-fee-total">
      <span>Total</span>
      <span>&#8369;<?php echo number_format($total, 2); ?></span>
    </div>
  </div>

  <?php if ($alreadyPaid): ?>

    <div class="sjcs-pay-card">
      <h3 style="margin-top:0;">Payment Submitted</h3>
      <?php foreach ($existingPayments as $p): ?>
        <div class="sjcs-fee-row">
          <span>
            &#8369;<?php echo number_format((float)$p->AMOUNT, 2); ?> via <?php echo htmlspecialchars($p->METHOD); ?>
            <?php if (!empty($p->REFERENCE_NO)): ?> (Ref: <?php echo htmlspecialchars($p->REFERENCE_NO); ?>)<?php endif; ?>
          </span>
          <span class="sjcs-badge sjcs-badge-<?php echo strtolower($p->STATUS); ?>"><?php echo htmlspecialchars($p->STATUS); ?></span>
        </div>
      <?php endforeach; ?>
    </div>

  <?php else: ?>

    <div class="sjcs-pay-card">
      <h3 style="margin-top:0;">Submit Payment</h3>
      <form method="post">
        <label>Payment Method</label>
        <select name="METHOD" required>
          <option value="">-- Select --</option>
          <option value="Cash">Cash (pay at the Cashier's Office)</option>
          <option value="GCash">GCash</option>
          <option value="Other">Other</option>
        </select>
        <label>Reference Number <span style="font-weight:400; color:#8a938c;">(required for GCash/Other)</span></label>
        <input type="text" name="REFERENCE_NO">
        <button type="submit" class="sjcs-btn sjcs-btn-primary" style="margin-top:20px; border:none; cursor:pointer;">Submit Payment for Verification</button>
      </form>
    </div>

  <?php endif; ?>

<?php endif; ?>
