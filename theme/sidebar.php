<?php
// DESTINATION: C:\xampp\htdocs\bruce-class\theme\sidebar.php
// ACTION:      OVERWRITE (back up the old file first)
?>
<?php $__role = current_role(); ?>
<nav class="mt-2">

 <!-- ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. -->
 <!-- Role-aware sidebar, grouped by workflow: Admissions and Enrollment
      first (the daily work), then Academics setup, Finance, Records, and
      System. Registrar, Cashier and Teacher only see their own sections;
      the server-side require_role() checks in each module remain the real
      enforcement.
      NOTE: this is a basic-education school (Nursery to Grade 10), so the
      "Course" module is presented as GRADE LEVELS. The underlying table is
      still tblcourses and COURSE_ID, so nothing in enrollment breaks. -->

        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <li class="nav-item">
            <a href='<?php echo WEB_ROOT . portal_home_for($__role); ?>' class="nav-link <?php echo ($title=='Home') ? "active" : 'na'; ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

<?php if (has_role([ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR])): ?>
          <li class="nav-header">Admissions &amp; Enrollment</li>

          <li class="nav-item">
            <a href='<?php echo WEB_ROOT; ?>portal/registrar/applicants.php' class="nav-link <?php echo ($title=='Applicants') ? "active" : 'na'; ?>">
              <i class="nav-icon fa fa-file-signature"></i><p>Applicants</p>
            </a>
          </li>

          <li class="nav-item">
            <a href='<?php echo WEB_ROOT; ?>module/enrollment/index.php' class="nav-link <?php echo ($title=='Enrollment') ? "active" : 'na'; ?>">
              <i class="nav-icon fa fa-clipboard-list"></i><p>Enrollment</p>
            </a>
          </li>

          <li class="nav-item">
            <a href='<?php echo WEB_ROOT; ?>module/student' class="nav-link <?php echo ($title=='Student Module') ? "active" : 'na'; ?>">
              <i class="nav-icon fa fa-user-graduate"></i><p>Learners</p>
            </a>
          </li>
<?php endif; ?>

<?php if (has_role([ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR])): ?>
          <li class="nav-header">Academics</li>

          <li class="nav-item">
            <a href='<?php echo WEB_ROOT; ?>module/course' class="nav-link <?php echo ($title=='Grade Level Module') ? "active" : 'na'; ?>">
              <i class="nav-icon fa fa-layer-group"></i><p>Grade Levels</p>
            </a>
          </li>

          <li class="nav-item">
            <a href='<?php echo WEB_ROOT; ?>module/subject' class="nav-link <?php echo ($title=='Subject Module') ? "active" : 'na'; ?>">
              <i class="nav-icon fa fa-book-open"></i><p>Subjects</p>
            </a>
          </li>

          <li class="nav-item">
            <a href='<?php echo WEB_ROOT; ?>module/generic/index.php?t=tblsections' class="nav-link <?php echo ($title=='Sections') ? "active" : 'na'; ?>">
              <i class="nav-icon fa fa-chalkboard"></i><p>Sections</p>
            </a>
          </li>

          <li class="nav-item">
            <a href='<?php echo WEB_ROOT; ?>module/generic/index.php?t=tblschoolyear' class="nav-link <?php echo ($title=='School Year') ? "active" : 'na'; ?>">
              <i class="nav-icon fa fa-calendar-alt"></i><p>School Year</p>
            </a>
          </li>

          <?php /* Class Scheduling groups the four scheduling screens under
                   one collapsible parent, so the repeated word "Schedule"
                   is not shown four times down the sidebar. The parent opens
                   pre-expanded (menu-open) whenever the current page is one
                   of its children. */ ?>
          <?php $__inSched = in_array($title, array('Schedule Time','Schedule Day','Classroom','Set Schedule'), true); ?>
          <li class="nav-item has-treeview <?php echo $__inSched ? 'menu-open' : ''; ?>">
            <a href="#" class="nav-link <?php echo $__inSched ? 'active' : 'na'; ?>">
              <i class="nav-icon fa fa-calendar-week"></i>
              <p>Class Scheduling <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href='<?php echo WEB_ROOT; ?>module/setschedule/index.php' class="nav-link <?php echo ($title=='Set Schedule') ? "active" : ''; ?>">
                  <i class="nav-icon fa fa-table"></i><p>Set Schedule</p>
                </a>
              </li>
              <li class="nav-item">
                <a href='<?php echo WEB_ROOT; ?>module/generic/index.php?t=tblschedule_time' class="nav-link <?php echo ($title=='Schedule Time') ? "active" : ''; ?>">
                  <i class="nav-icon fa fa-clock"></i><p>Schedule Time</p>
                </a>
              </li>
              <li class="nav-item">
                <a href='<?php echo WEB_ROOT; ?>module/generic/index.php?t=tblschedule_day' class="nav-link <?php echo ($title=='Schedule Day') ? "active" : ''; ?>">
                  <i class="nav-icon fa fa-calendar-day"></i><p>Schedule Day</p>
                </a>
              </li>
              <li class="nav-item">
                <a href='<?php echo WEB_ROOT; ?>module/generic/index.php?t=tblclassroom' class="nav-link <?php echo ($title=='Classroom') ? "active" : ''; ?>">
                  <i class="nav-icon fa fa-door-open"></i><p>Classroom</p>
                </a>
              </li>
            </ul>
          </li>
<?php endif; ?>

<?php if (has_role([ROLE_ADMIN, ROLE_STAFF, ROLE_CASHIER])): ?>
          <li class="nav-header">Finance</li>

          <li class="nav-item">
            <a href='<?php echo WEB_ROOT; ?>module/payments/index.php' class="nav-link <?php echo ($title=='Payments') ? "active" : 'na'; ?>">
              <i class="nav-icon fa fa-money-bill-wave"></i><p>Payments</p>
            </a>
          </li>

          <li class="nav-item">
            <a href='<?php echo WEB_ROOT; ?>portal/cashier/admission_payments.php' class="nav-link <?php echo ($title=='Admission Payments') ? "active" : 'na'; ?>">
              <i class="nav-icon fa fa-file-invoice-dollar"></i><p>Admission Payments</p>
            </a>
          </li>
<?php endif; ?>

<?php if (has_role([ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR, ROLE_TEACHER])): ?>
          <li class="nav-header">Records</li>
<?php endif; ?>

<?php if (has_role([ROLE_ADMIN, ROLE_STAFF, ROLE_TEACHER])): ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php echo ($title=='Attendance') ? "active" : 'na'; ?>">
              <i class="nav-icon fa fa-id-card"></i>
              <p>Attendance <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href='<?php echo WEB_ROOT; ?>module/attendance/index.php?view=scan' class="nav-link">
                  <i class="nav-icon fa fa-address-card"></i><p>Scan Kiosk</p>
                </a>
              </li>
              <li class="nav-item">
                <a href='<?php echo WEB_ROOT; ?>module/attendance/index.php' class="nav-link">
                  <i class="nav-icon fa fa-list-alt"></i><p>Attendance Report</p>
                </a>
              </li>
            </ul>
          </li>
<?php endif; ?>

<?php if (has_role([ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR, ROLE_TEACHER])): ?>
          <li class="nav-item">
            <a href='<?php echo WEB_ROOT; ?>module/generic/index.php?t=tblgrades' class="nav-link <?php echo ($title=='Grades') ? "active" : 'na'; ?>">
              <i class="nav-icon fa fa-graduation-cap"></i><p>Grades</p>
            </a>
          </li>
<?php endif; ?>

<?php if (has_role([ROLE_ADMIN, ROLE_STAFF, ROLE_REGISTRAR])): ?>
          <li class="nav-item">
            <a href='<?php echo WEB_ROOT; ?>module/generic/index.php?t=tblenrollment_details' class="nav-link <?php echo ($title=='Enrollment Details') ? "active" : 'na'; ?>">
              <i class="nav-icon fa fa-list-alt"></i><p>Enrollment Details</p>
            </a>
          </li>
<?php endif; ?>

          <li class="nav-header">System</li>

<?php if (has_role([ROLE_ADMIN, ROLE_STAFF])): ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php echo ($title=='User Module' || $title=='User Type') ? "active" : 'na'; ?>">
              <i class="nav-icon fas fa-cog"></i>
              <p>Account Settings <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href='<?php echo WEB_ROOT; ?>module/user/' class="nav-link">
                  <i class="nav-icon fa fa-users"></i><p>Manage User Accounts</p>
                </a>
              </li>
              <li class="nav-item">
                <a href='<?php echo WEB_ROOT; ?>module/usertype/' class="nav-link">
                  <i class="nav-icon fa fa-key"></i><p>Manage User Type</p>
                </a>
              </li>
            </ul>
          </li>
<?php endif; ?>

          <li class="nav-item">
            <a href='<?php echo WEB_ROOT; ?>module/about/index.php' class="nav-link <?php echo ($title=='About') ? "active" : 'na'; ?>">
              <i class="nav-icon fa fa-info-circle"></i><p>About</p>
            </a>
          </li>

        </ul>
      </nav>