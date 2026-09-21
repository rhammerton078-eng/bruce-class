<?php /* Registrar > Applicants, grouped by pipeline stage. */ ?>
<style>
  /* -------------------------------------------------------------------
     ONE SOURCE OF TRUTH PER STAGE
     Each stage sets two custom properties on its wrapper: --t (the accent)
     and --tbg (the tint). The bar, the count badge, the header wash, the
     owner label and the chip dot all read from those, so a stage can never
     end up half-green half-grey again, and adding a stage later means
     adding one rule instead of six.
     ------------------------------------------------------------------- */
  /* Accents are the school hues darkened to the point where white badge
     text and accent-on-tint text both clear 4.5:1 (WCAG AA). The lighter
     originals looked right but failed: gold on white was only 2.75:1.
     Tints are deliberately strong enough to read as colour on a white
     page (>=1.13 against white); the pale first attempt looked blank. */
  .apx .t-wait   { --t:#606D68; --tbg:#E9EEEC; --tln:#CDD8D3; }
  .apx .t-money  { --t:#8B651E; --tbg:#FBF0D8; --tln:#E6D3A8; }
  .apx .t-action { --t:#007759; --tbg:#DDEFE6; --tln:#B6DBC7; }
  .apx .t-done   { --t:#4D7432; --tbg:#E6F0D9; --tln:#C8DCAF; }
  .apx .t-closed { --t:#AF443B; --tbg:#F8E4E1; --tln:#E7C2BC; }

  /* ---- filter chips ---- */
  .apx .sum{display:flex;flex-wrap:wrap;gap:9px;margin-bottom:18px;}
  .apx .chip{display:flex;align-items:center;gap:8px;background:#fff;border:1px solid var(--sj-ln);
    border-radius:9px;padding:9px 13px;cursor:pointer;font-size:.8rem;color:var(--sj-soft);
    font-weight:600;transition:.15s;text-decoration:none;}
  .apx .chip:hover{border-color:var(--t,var(--sj-g));color:var(--sj-d);}
  .apx .chip b{font-size:.95rem;color:var(--t,var(--sj-d));margin-left:2px;}
  .apx .chip i.dot{width:9px;height:9px;border-radius:50%;display:inline-block;background:var(--t);}
  /* an active chip takes its own stage colour, not a generic green */
  .apx .chip.on{background:var(--t,var(--sj-d));border-color:var(--t,var(--sj-d));color:#fff;}
  .apx .chip.on b{color:#fff;}
  .apx .chip.on i.dot{background:rgba(255,255,255,.85);}
  .apx .chip-all{--t:var(--sj-d);}

  /* ---- stage group ---- */
  .apx .grp{background:#fff;border:1px solid var(--sj-ln);border-radius:11px;margin-bottom:16px;
    overflow:hidden;border-left:4px solid var(--t);}
  .apx .grp-h{display:flex;align-items:center;gap:11px;padding:13px 17px;flex-wrap:wrap;
    background:var(--tbg);border-bottom:1px solid var(--tln);}
  .apx .grp-h h4{margin:0;font-size:.98rem;font-weight:700;color:var(--sj-d);}
  .apx .grp-h .cnt{background:var(--t);color:#fff;border-radius:99px;font-size:.72rem;
    font-weight:700;padding:2px 10px;min-width:26px;text-align:center;}
  .apx .grp-h .own{margin-left:auto;font-size:.72rem;font-weight:700;text-transform:uppercase;
    letter-spacing:.07em;color:var(--t);}
  .apx .grp-h .hint{flex-basis:100%;font-size:.79rem;color:var(--sj-mu);margin:0;}

  /* ---- table ---- */
  .apx table{width:100%;margin:0;font-size:.87rem;}
  .apx th{background:#F7FAF8;color:var(--sj-mu);font-size:.7rem;text-transform:uppercase;
    letter-spacing:.08em;padding:9px 14px;border-bottom:1px solid var(--sj-ln);text-align:left;font-weight:700;}
  .apx td{padding:11px 14px;border-bottom:1px solid #EFF3F0;vertical-align:middle;color:var(--sj-ink);}
  .apx tr:last-child td{border-bottom:none;}
  .apx tbody tr:hover{background:var(--tbg);}
  .apx .nm{font-weight:600;color:var(--sj-d);}
  /* grade-level pill also follows the stage tone, tinted not saturated */
  .apx .lvl{display:inline-block;background:var(--tbg);color:var(--t);border:1px solid var(--tln);
    border-radius:5px;padding:2px 8px;font-size:.76rem;font-weight:700;}
  .apx .none{color:var(--sj-mu);font-size:.82rem;font-style:italic;}

  /* ---- action buttons ---- */
  .apx .btn-ok,.apx .btn-no{border:none;border-radius:6px;color:#fff;font-weight:700;
    font-size:.74rem;padding:6px 13px;text-decoration:none;display:inline-block;margin-right:4px;
    white-space:nowrap;}
  .apx .btn-ok{background:var(--sj-g);} .apx .btn-ok:hover{background:var(--sj-d);color:#fff;}
  .apx .btn-no{background:#B3453C;}     .apx .btn-no:hover{background:#8E352E;color:#fff;}

  /* ---- dark mode: same tones, darker tints ---- */
  html.dark-mode-custom .apx .t-wait   { --tbg:#182924; --tln:#22352F; --t:#80928A; }
  html.dark-mode-custom .apx .t-money  { --tbg:#2A2417; --tln:#3E3520; --t:#D8A63F; }
  html.dark-mode-custom .apx .t-action { --tbg:#132E26; --tln:#1D4034; --t:#22A67F; }
  html.dark-mode-custom .apx .t-done   { --tbg:#1B2A17; --tln:#2A3E22; --t:#8CBB5C; }
  html.dark-mode-custom .apx .t-closed { --tbg:#2C1B19; --tln:#412724; --t:#D9675C; }
  html.dark-mode-custom .apx .grp{background:#12211D;border-color:#20342E;border-left-color:var(--t);}
  html.dark-mode-custom .apx .grp-h h4{color:#FFF;}
  html.dark-mode-custom .apx th{background:#182E28;color:#8FA097;border-bottom-color:#20342E;}
  html.dark-mode-custom .apx td{color:#E6EDE8;border-bottom-color:#20342E;}
  html.dark-mode-custom .apx .nm{color:#FFF;}
  html.dark-mode-custom .apx .chip{background:#12211D;border-color:#20342E;color:#C2CFC7;}
  html.dark-mode-custom .apx .chip.on{color:#0B1A16;}
  html.dark-mode-custom .apx .chip.on b{color:#0B1A16;}
</style>

<section class="content apx">
  <div class="container-fluid">
    <?php check_message(); ?>

    <?php /* Jump chips: one per stage that actually has records, so the
             registrar can see the whole pipeline at a glance and filter to
             a single stage without a page reload. */ ?>
    <div class="sum">
      <a href="#" class="chip chip-all on" data-f="all">All applicants <b><?php echo (int)$totalApplicants; ?></b></a>
      <?php foreach ($APPLICANT_STAGES as $st => $meta):
              $n = isset($grouped[$st]) ? count($grouped[$st]) : 0;
              if ($n < 1) continue; ?>
        <a href="#" class="chip t-<?php echo $meta['tone']; ?>" data-f="<?php echo md5($st); ?>">
          <i class="dot d-<?php echo $meta['tone']; ?>"></i>
          <?php echo htmlspecialchars($st); ?> <b><?php echo $n; ?></b>
        </a>
      <?php endforeach; ?>
    </div>

    <?php if ($totalApplicants < 1): ?>
      <div class="grp"><div class="grp-h"><h4>No applicants yet</h4></div></div>
    <?php else: ?>

      <?php foreach ($APPLICANT_STAGES as $st => $meta):
              $rows = isset($grouped[$st]) ? $grouped[$st] : array();
              if (empty($rows)) continue; ?>
      <div class="grp t-<?php echo $meta['tone']; ?>" data-g="<?php echo md5($st); ?>">
        <div class="grp-h">
          <h4><?php echo htmlspecialchars($st); ?></h4>
          <span class="cnt"><?php echo count($rows); ?></span>
          <span class="own"><?php echo htmlspecialchars($meta['owner']); ?></span>
          <p class="hint"><?php echo htmlspecialchars($meta['hint']); ?></p>
        </div>
        <table>
          <thead>
            <tr>
              <th style="width:8%">#</th>
              <th style="width:28%">Name</th>
              <th style="width:15%">Type</th>
              <th style="width:17%">Grade Level</th>
              <th style="width:15%">Date Applied</th>
              <th style="width:17%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $a): ?>
            <tr>
              <td><?php echo (int)$a->APPLICANT_ID; ?></td>
              <td class="nm"><?php echo htmlspecialchars(trim($a->FNAME.' '.$a->LNAME)); ?></td>
              <td><?php echo htmlspecialchars($a->APPLICANT_TYPE); ?></td>
              <td>
                <?php /* Readable grade level, falling back to the code. */ ?>
                <span class="lvl"><?php
                  echo htmlspecialchars($a->COURSE_NAME ? $a->COURSE_NAME : ($a->COURSE_CODE ? $a->COURSE_CODE : 'Not set'));
                ?></span>
              </td>
              <td><?php echo $a->DATE_APPLIED ? date('M j, Y', strtotime($a->DATE_APPLIED)) : '-'; ?></td>
              <td>
                <?php if ($st === 'For Registrar Review'): ?>
                  <a href="?action=approve&id=<?php echo (int)$a->APPLICANT_ID; ?>"
                     class="btn-ok" onclick="return confirm('Approve this applicant?');">Approve</a>
                  <a href="?action=reject&id=<?php echo (int)$a->APPLICANT_ID; ?>"
                     class="btn-no" onclick="return confirm('Reject this applicant?');">Reject</a>
                <?php else: ?>
                  <span class="none"><?php echo htmlspecialchars($meta['owner']); ?></span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endforeach; ?>

    <?php endif; ?>

  </div>
</section>

<script>
(function () {
  var chips  = document.querySelectorAll('.apx .chip');
  var groups = document.querySelectorAll('.apx .grp[data-g]');
  for (var i = 0; i < chips.length; i++) {
    chips[i].addEventListener('click', function (e) {
      e.preventDefault();
      var f = this.getAttribute('data-f');
      for (var c = 0; c < chips.length; c++) { chips[c].classList.remove('on'); }
      this.classList.add('on');
      for (var g = 0; g < groups.length; g++) {
        groups[g].style.display = (f === 'all' || groups[g].getAttribute('data-g') === f) ? '' : 'none';
      }
    });
  }
})();
</script>