<?php

//ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC.

 if (!isset($_SESSION['UID'])){
      redirect(WEB_ROOT."login.php");
 //   header("Location: login.php");

     }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>St. Joseph Catholic School of Sagay Inc.</title>
    <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Apply saved dark-mode preference before paint, so there is no flash of the wrong theme -->
  <script>
    if (localStorage.getItem('sjcsTheme') === 'dark') {
      document.documentElement.classList.add('dark-mode-custom');
    }
  </script>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo  WEB_ROOT;?>plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="<?php echo  WEB_ROOT;?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo  WEB_ROOT;?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?php echo  WEB_ROOT;?>plugins/jqvmap/jqvmap.min.css">
  <!-- Leaflet: powers the Philippine province map on the dashboard -->
  <link rel="stylesheet" href="<?php echo  WEB_ROOT;?>plugins/leaflet/leaflet.css">
   <!-- SweetAlert2 -->
  <link rel="stylesheet" href="<?php echo  WEB_ROOT;?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo  WEB_ROOT;?>plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo  WEB_ROOT;?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
   <!-- Toastr -->
  <link rel="stylesheet" href="<?php echo  WEB_ROOT;?>plugins/toastr/toastr.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo  WEB_ROOT;?>dist/css/adminlte.min.css">
  <!-- Custom theme (Hark palette + dark mode). Cache-busted with filemtime
       so a browser that cached an older version of this file (a common
       issue with static assets) always fetches the current one. -->
  <link rel="stylesheet" href="<?php echo WEB_ROOT; ?>theme/custom.css?v=<?php echo @filemtime(__DIR__.'/custom.css'); ?>">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo  WEB_ROOT;?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo  WEB_ROOT;?>plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="<?php echo  WEB_ROOT;?>plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo  WEB_ROOT;?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo  WEB_ROOT;?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

  <!-- Dark mode: embedded on purpose so it always applies, even if
       theme/custom.css has not been copied over. Loads after AdminLTE,
       so these rules win. Toggled by the moon/sun button in the navbar. -->
  <style>
    html.dark-mode-custom body,
    html.dark-mode-custom .content-wrapper,
    html.dark-mode-custom .wrapper { background:#0B1714 !important; color:#E6EDE8 !important; }

    html.dark-mode-custom .main-header.navbar { background:#12211D !important; border-bottom-color:#20342E !important; }
    html.dark-mode-custom .main-header .nav-link,
    html.dark-mode-custom .main-header .navbar-nav .nav-link { color:#C2CFC7 !important; }
    html.dark-mode-custom .main-sidebar { background:#07120F !important; }
    html.dark-mode-custom .brand-link { background:rgba(0,0,0,.35) !important; }

    html.dark-mode-custom .content-header h1,
    html.dark-mode-custom .card-title,
    html.dark-mode-custom h1, html.dark-mode-custom h2, html.dark-mode-custom h3,
    html.dark-mode-custom h4, html.dark-mode-custom h5 { color:#FFFFFF !important; }
    html.dark-mode-custom .breadcrumb-item.active,
    html.dark-mode-custom .text-muted,
    html.dark-mode-custom small { color:#8FA097 !important; }

    html.dark-mode-custom .card,
    html.dark-mode-custom .modal-content,
    html.dark-mode-custom .card-header,
    html.dark-mode-custom .card-footer,
    html.dark-mode-custom .sjd .sj-card,
    html.dark-mode-custom .sjd .sj-info,
    html.dark-mode-custom .sjd .sj-q { background:#12211D !important; border-color:#20342E !important; color:#E6EDE8 !important; }

    html.dark-mode-custom .table,
    html.dark-mode-custom .sjd table.sj-tbl { color:#E6EDE8 !important; }
    html.dark-mode-custom .table thead th,
    html.dark-mode-custom .sjd table.sj-tbl thead th { background:#182E28 !important; color:#8FA097 !important; border-color:#20342E !important; }
    html.dark-mode-custom .table td,
    html.dark-mode-custom .table th,
    html.dark-mode-custom .sjd table.sj-tbl tbody td { border-color:#20342E !important; }
    html.dark-mode-custom .table-hover tbody tr:hover,
    html.dark-mode-custom .sjd table.sj-tbl tbody tr:hover { background:#172723 !important; color:#FFFFFF !important; }
    html.dark-mode-custom .table-striped tbody tr:nth-of-type(odd) { background:#101E1A !important; }

    html.dark-mode-custom .form-control,
    html.dark-mode-custom .custom-select,
    html.dark-mode-custom select,
    html.dark-mode-custom textarea,
    html.dark-mode-custom input[type=text],
    html.dark-mode-custom input[type=password],
    html.dark-mode-custom input[type=number],
    html.dark-mode-custom input[type=date] { background:#0F1D19 !important; border-color:#20342E !important; color:#E6EDE8 !important; }
    html.dark-mode-custom .form-control::placeholder { color:#6E7F77 !important; }
    html.dark-mode-custom .input-group-text { background:#182E28 !important; border-color:#20342E !important; color:#8FA097 !important; }
    html.dark-mode-custom label, html.dark-mode-custom .col-form-label { color:#C2CFC7 !important; }

    html.dark-mode-custom .btn-default { background:#182E28 !important; border-color:#20342E !important; color:#C2CFC7 !important; }
    html.dark-mode-custom .main-footer { background:#12211D !important; border-top-color:#20342E !important; color:#8FA097 !important; }
    html.dark-mode-custom .dataTables_wrapper,
    html.dark-mode-custom .dataTables_wrapper label,
    html.dark-mode-custom .dataTables_info { color:#C2CFC7 !important; }
    html.dark-mode-custom .page-link { background:#12211D !important; border-color:#20342E !important; }
    html.dark-mode-custom .dropdown-menu { background:#12211D !important; border-color:#20342E !important; }
    html.dark-mode-custom .dropdown-item { color:#C2CFC7 !important; }
    html.dark-mode-custom .dropdown-item:hover { background:#182E28 !important; color:#fff !important; }

    /* dashboard bits */
    html.dark-mode-custom .sjd .sj-info .v,
    html.dark-mode-custom .sjd .sj-strip b,
    html.dark-mode-custom .sjd .sj-goal-t b { color:#FFFFFF !important; }
    html.dark-mode-custom .sjd .sj-info .t,
    html.dark-mode-custom .sjd .sj-strip span,
    html.dark-mode-custom .sjd .sj-card-h .meta { color:#8FA097 !important; }
    html.dark-mode-custom .sjd .sj-track { background:#1C2E29 !important; }
    html.dark-mode-custom .sjd .sj-strip,
    html.dark-mode-custom .sjd .sj-strip div,
    html.dark-mode-custom .sjd .sj-card-h { border-color:#20342E !important; }

    /* the toggle itself: filled circle so the state is obvious */
    #darkModeToggle { border-radius:50%; padding:.5rem .62rem; transition:background .15s ease,color .15s ease; }
    #darkModeToggle:hover { background:rgba(0,128,96,.12); }
    html.dark-mode-custom #darkModeToggle { background:rgba(149,191,71,.18); }
    html.dark-mode-custom #darkModeIcon { color:#95BF47 !important; }
  </style>

  <!-- Sidebar: school green with white text. Embedded here (not only in
       theme/custom.css) so it always applies. Contrast checked: white on
       #013A2E is about 11:1, well past the 4.5:1 readability minimum. -->
  <style>
    .main-sidebar,
    .main-sidebar.sidebar-dark-primary { background:#013A2E !important; }

    .brand-link { background:#002D2D !important; border-bottom:1px solid rgba(255,255,255,.12) !important; }
    .brand-link .brand-text,
    .brand-link span { color:#FFFFFF !important; font-weight:600; }

    /* every menu label is full white, so nothing is washed out */
    .nav-sidebar .nav-link,
    .nav-sidebar .nav-link p,
    .nav-sidebar > .nav-item > .nav-link { color:#FFFFFF !important; }
    .nav-sidebar .nav-link .nav-icon { color:rgba(255,255,255,.75) !important; }

    .nav-sidebar .nav-link { border-left:none !important; border-radius:0; padding:.62rem 1rem; }
    .nav-sidebar .nav-link:hover { background:rgba(255,255,255,.10) !important; }
    .nav-sidebar .nav-link:hover .nav-icon { color:#95BF47 !important; }

    /* active item: lime bar + tint, white text stays readable */
    .nav-sidebar .nav-link.active,
    .nav-sidebar .nav-link.active:hover,
    .nav-sidebar .nav-item > .nav-link.active {
      background:rgba(149,191,71,.20) !important;
      color:#FFFFFF !important;
      border-left:none !important;
      /* inset bar instead of a border: no layout shift, so the icon
         keeps its full box and cannot be clipped */
      box-shadow:inset 3px 0 0 0 #95BF47 !important;
    }
    .nav-sidebar .nav-link.active .nav-icon,
    .nav-sidebar .nav-link.active p { color:#FFFFFF !important; }
    .nav-sidebar .nav-link.active .nav-icon { color:#95BF47 !important; }

    /* section headings: lime, slightly larger than AdminLTE's default */
    .nav-sidebar .nav-header,
    .main-sidebar .nav-header {
      color:#95BF47 !important; font-size:.7rem !important; font-weight:700;
      text-transform:uppercase; letter-spacing:.14em; padding:1.05rem 1rem .4rem;
    }

    /* submenus sit slightly darker so the grouping reads */
    .nav-sidebar .nav-treeview { background:rgba(0,0,0,.18); }
    .nav-sidebar .nav-treeview .nav-link { padding-left:2.6rem; color:rgba(255,255,255,.88) !important; }
    .nav-sidebar .nav-treeview .nav-link:hover { background:rgba(255,255,255,.10) !important; color:#fff !important; }
    .nav-sidebar .nav-link .right { color:rgba(255,255,255,.7) !important; }

    /* dark mode: deepen the same green rather than switch colour */
    html.dark-mode-custom .main-sidebar,
    html.dark-mode-custom .main-sidebar.sidebar-dark-primary { background:#01241D !important; }
    html.dark-mode-custom .brand-link { background:#01120F !important; }
  </style>

  <!-- Shared admin design system. Embedded so every module page matches
       the public site, whether or not theme/custom.css is present. -->
  <style>
    :root{ --sj-d:#002D2D; --sj-g:#008060; --sj-l:#95BF47; --sj-o:#5E8E3E; --sj-a:#C9922B;
           --sj-ink:#12201C; --sj-soft:#42504A; --sj-mu:#79857E; --sj-ln:#E3E9E4; --sj-bg:#F5F7F6; }

    body, .content-wrapper, .wrapper { background:var(--sj-bg); }
    .content-wrapper { padding-bottom:24px; }

    /* ---------- TOP BAR: white, not grey ---------- */
    .main-header.navbar.sjcs-topnav{
      background:#FFFFFF !important; border-bottom:1px solid var(--sj-ln) !important;
      box-shadow:0 1px 3px rgba(0,45,45,.06); min-height:56px; padding:0 .6rem;
    }
    .sjcs-topnav .nav-link{ color:var(--sj-soft) !important; font-weight:600; font-size:.9rem; }
    .sjcs-topnav .nav-link:hover{ color:var(--sj-g) !important; }
    .sjcs-iconbtn{ width:38px; height:38px; display:inline-flex !important; align-items:center;
      justify-content:center; border-radius:50%; }
    .sjcs-iconbtn:hover{ background:rgba(0,128,96,.10); }
    html.dark-mode-custom .sjcs-iconbtn{ background:rgba(149,191,71,.18); }
    html.dark-mode-custom #darkModeIcon{ color:var(--sj-l) !important; }

    /* avatar + user block */
    .sjcs-avatar{ width:34px; height:34px; border-radius:50%; background:var(--sj-g); color:#fff;
      display:inline-flex; align-items:center; justify-content:center; font-weight:700; font-size:.9rem; }
    .sjcs-avatar.lg{ width:44px; height:44px; font-size:1.05rem; }
    .sjcs-user-meta{ line-height:1.15; margin-left:10px; text-align:left; }
    .sjcs-user-meta .nm{ display:block; font-weight:700; font-size:.88rem; color:var(--sj-ink); }
    .sjcs-user-meta .rl{ display:block; font-size:.72rem; color:var(--sj-mu); text-transform:uppercase; letter-spacing:.06em; }
    html.dark-mode-custom .sjcs-user-meta .nm{ color:#fff; }
    .sjcs-usermenu{ border:1px solid var(--sj-ln); border-radius:10px; padding:0; min-width:250px;
      box-shadow:0 14px 34px rgba(0,45,45,.16); overflow:hidden; }
    .sjcs-usermenu-head{ display:flex; align-items:center; gap:12px; padding:16px;
      background:linear-gradient(120deg,var(--sj-d),var(--sj-g)); color:#fff; }
    .sjcs-usermenu-head strong{ display:block; font-size:.95rem; }
    .sjcs-usermenu-head span{ font-size:.74rem; opacity:.85; text-transform:uppercase; letter-spacing:.07em; }
    .sjcs-usermenu .dropdown-item{ padding:11px 16px; font-size:.9rem; color:var(--sj-soft); }
    .sjcs-usermenu .dropdown-item i{ width:18px; color:var(--sj-g); margin-right:8px; }
    .sjcs-usermenu .dropdown-item:hover{ background:#F3F8F5; color:var(--sj-ink); }
    .sjcs-logout-link{ color:#B3261E !important; }
    .sjcs-logout-link i{ color:#B3261E !important; }
    .sjcs-logout-link:hover{ background:#FDF3F1 !important; }

    /* ---------- sidebar icons: stop the clipping ---------- */
    .nav-sidebar .nav-link .nav-icon{
      width:1.9rem; min-width:1.9rem; max-width:none;
      font-size:1.02rem; line-height:1; text-align:center;
      margin:0 .55rem 0 0; padding:0; overflow:visible; flex:0 0 auto;
      display:inline-flex; align-items:center; justify-content:center;
    }
    .nav-sidebar .nav-link{ display:flex; align-items:center; overflow:visible; }
    .nav-sidebar .nav-link p{ margin:0; overflow:hidden; text-overflow:ellipsis; flex:1; }
    /* AdminLTE clips icons on some builds - make sure nothing hides them */
    .nav-sidebar .nav-link .nav-icon,
    .nav-sidebar .nav-link .nav-icon::before{ overflow:visible !important; text-overflow:clip; }
    .nav-sidebar .nav-treeview .nav-link .nav-icon{ width:1.6rem; min-width:1.6rem; font-size:.92rem; }

    /* ---------- page header ---------- */
    .content-header{ padding:20px .5rem 6px; }
    .content-header h1{ font-weight:700; font-size:1.6rem; color:var(--sj-d); }
    .breadcrumb{ background:transparent; margin-bottom:0; }
    .breadcrumb-item a{ color:var(--sj-g); }
    .breadcrumb-item.active{ color:var(--sj-mu); }

    /* ---------- cards on every module page ---------- */
    .card{ border:1px solid var(--sj-ln); border-radius:10px; box-shadow:none; margin-bottom:20px; }
    .card-header{ background:#fff; border-bottom:1px solid var(--sj-ln); padding:15px 18px; border-radius:10px 10px 0 0; }
    .card-title{ font-weight:700; color:var(--sj-d); font-size:1.02rem; }
    .card-body{ padding:18px; }
    .card-primary:not(.card-outline)>.card-header,
    .card-info:not(.card-outline)>.card-header{ background:var(--sj-d); color:#fff; }
    .card-success:not(.card-outline)>.card-header{ background:var(--sj-o); color:#fff; }

    /* ---------- buttons ---------- */
    .btn{ border-radius:6px; font-weight:600; font-size:.86rem; }
    .btn-primary{ background:var(--sj-g); border-color:var(--sj-g); }
    .btn-primary:hover,.btn-primary:focus,.btn-primary:active{ background:#00694f !important; border-color:#00694f !important; }
    .btn-success{ background:var(--sj-o); border-color:var(--sj-o); }
    .btn-success:hover{ background:#527d35; border-color:#527d35; }
    .btn-info{ background:var(--sj-g); border-color:var(--sj-g); }
    .btn-warning{ background:var(--sj-a); border-color:var(--sj-a); color:#fff; }
    .btn-danger{ background:#B3261E; border-color:#B3261E; }
    .btn-default{ background:#fff; border-color:var(--sj-ln); color:var(--sj-soft); }
    .btn-xs{ padding:.2rem .45rem; font-size:.75rem; }

    /* ---------- tables ---------- */
    .table{ margin-bottom:0; }
    .table thead th{ background:#F7FAF8; border-top:none; border-bottom:1px solid var(--sj-ln) !important;
      font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; color:var(--sj-mu);
      font-weight:700; padding:12px 14px; }
    .table td{ padding:12px 14px; border-top:1px solid #EFF3F0; vertical-align:middle; font-size:.9rem; }
    .table-hover tbody tr:hover{ background:#F9FBFA; }
    .table-bordered, .table-bordered td, .table-bordered th{ border-color:var(--sj-ln); }

    /* ---------- forms ---------- */
    .form-control, .custom-select{ border:1px solid var(--sj-ln); border-radius:6px; font-size:.9rem; }
    .form-control:focus, .custom-select:focus{ border-color:var(--sj-g); box-shadow:0 0 0 3px rgba(0,128,96,.13); }
    label, .col-form-label{ font-weight:600; color:var(--sj-soft); font-size:.86rem; }
    .input-group-text{ background:#F5F8F6; border-color:var(--sj-ln); color:var(--sj-mu); }

    /* ---------- badges, alerts, datatables, modals ---------- */
    .badge{ font-weight:700; padding:.4em .65em; border-radius:4px; font-size:.72rem; }
    .badge-success{ background:var(--sj-o); } .badge-primary{ background:var(--sj-g); }
    .badge-info{ background:#4FA98C; } .badge-warning{ background:var(--sj-a); color:#fff; }
    .badge-secondary{ background:#9AA8A1; }
    .alert-success{ background:#EEF6EA; border-color:#CDE3BF; color:#3C5B2A; }
    .alert-info{ background:#E9F5F1; border-color:#BCDDD2; color:#0A5240; }
    .dataTables_wrapper .dataTables_filter input,
    .dataTables_wrapper .dataTables_length select{ border:1px solid var(--sj-ln); border-radius:6px; padding:5px 9px; }
    .page-item.active .page-link{ background:var(--sj-g); border-color:var(--sj-g); }
    .page-link{ color:var(--sj-g); }
    .modal-content{ border:none; border-radius:10px; }
    .modal-header{ border-bottom:1px solid var(--sj-ln); }
    .modal-header .modal-title{ font-weight:700; color:var(--sj-d); }
    .modal-footer{ border-top:1px solid var(--sj-ln); }
    /* =================================================================
       DARK MODE: DATATABLES AND FORM CONTROLS
       DataTables injects its own chrome (length select, search box,
       processing overlay, info line, pagination) and AdminLTE's dark theme
       does not touch any of it, so those stayed white on a dark page. The
       table header and the outline filter buttons had the same problem.
       ================================================================= */
    html.dark-mode-custom .dataTables_wrapper,
    html.dark-mode-custom .dataTables_info,
    html.dark-mode-custom .dataTables_length,
    html.dark-mode-custom .dataTables_filter,
    html.dark-mode-custom .dataTables_length label,
    html.dark-mode-custom .dataTables_filter label { color:#C2CFC7 !important; }

    html.dark-mode-custom .dataTables_processing {
      background:#12211D !important; color:#E6EDE8 !important;
      border:1px solid #20342E !important; box-shadow:0 6px 18px rgba(0,0,0,.5) !important; }

    html.dark-mode-custom table.dataTable thead th,
    html.dark-mode-custom table.dataTable thead td,
    html.dark-mode-custom .table thead th {
      background:#182E28 !important; color:#9FB0A7 !important; border-color:#20342E !important; }
    html.dark-mode-custom table.dataTable tbody td,
    html.dark-mode-custom table.dataTable tbody th {
      background:#12211D !important; color:#E6EDE8 !important; border-color:#20342E !important; }
    html.dark-mode-custom table.dataTable tbody tr { background:#12211D !important; }
    html.dark-mode-custom table.dataTable.stripe tbody tr.odd,
    html.dark-mode-custom table.dataTable tbody tr.odd { background:#152722 !important; }
    html.dark-mode-custom table.dataTable tbody tr:hover > td { background:#1B302A !important; }
    html.dark-mode-custom table.dataTable.no-footer { border-bottom-color:#20342E !important; }

    html.dark-mode-custom .dataTables_paginate .paginate_button,
    html.dark-mode-custom .page-link {
      background:#12211D !important; color:#C2CFC7 !important; border-color:#20342E !important; }
    html.dark-mode-custom .dataTables_paginate .paginate_button.current,
    html.dark-mode-custom .page-item.active .page-link {
      background:var(--sj-g) !important; color:#fff !important; border-color:var(--sj-g) !important; }
    html.dark-mode-custom .dataTables_paginate .paginate_button.disabled { color:#5C6B64 !important; }

    /* every text input / select / textarea on a dark page */
    html.dark-mode-custom .form-control,
    html.dark-mode-custom .custom-select,
    html.dark-mode-custom select,
    html.dark-mode-custom textarea,
    html.dark-mode-custom input[type="text"],
    html.dark-mode-custom input[type="number"],
    html.dark-mode-custom input[type="date"],
    html.dark-mode-custom input[type="search"],
    html.dark-mode-custom input[type="email"],
    html.dark-mode-custom input[type="password"] {
      background:#0F1D19 !important; color:#E6EDE8 !important; border-color:#20342E !important; }
    html.dark-mode-custom .form-control::placeholder { color:#6E7E76 !important; }
    html.dark-mode-custom .input-group-text {
      background:#182E28 !important; color:#9FB0A7 !important; border-color:#20342E !important; }

    /* outline filter buttons (All / Registered / Assigned / ...) */
    html.dark-mode-custom .btn-outline-secondary,
    html.dark-mode-custom .btn-outline-dark,
    html.dark-mode-custom .btn-default,
    html.dark-mode-custom .btn-light {
      background:#12211D !important; color:#C2CFC7 !important; border-color:#20342E !important; }
    html.dark-mode-custom .btn-outline-secondary:hover,
    html.dark-mode-custom .btn-default:hover,
    html.dark-mode-custom .btn-light:hover {
      background:#1B302A !important; color:#FFFFFF !important; }
    html.dark-mode-custom .btn-group .btn.active {
      background:var(--sj-g) !important; color:#fff !important; border-color:var(--sj-g) !important; }

    html.dark-mode-custom .modal-content { background:#12211D !important; color:#E6EDE8 !important; }
    html.dark-mode-custom .modal-header,
    html.dark-mode-custom .modal-footer { border-color:#20342E !important; }
    html.dark-mode-custom .card { background:#12211D; }
    html.dark-mode-custom .card-header { border-bottom-color:#20342E; }

    /* =================================================================
       DARK MODE GAPS
       Bootstrap components that carry their own light background were not
       covered, so in dark mode they kept a white panel while the text went
       light - unreadable. The profile summary on the Learner page
       (.list-group-item) was the visible case; the rest are the same class
       of problem and are fixed here so it does not resurface elsewhere.
       ================================================================= */
    html.dark-mode-custom .list-group-item,
    html.dark-mode-custom .list-group-item-action,
    html.dark-mode-custom .list-group-unbordered > .list-group-item {
      background:#12211D !important; color:#E6EDE8 !important; border-color:#20342E !important; }
    html.dark-mode-custom .list-group-item b,
    html.dark-mode-custom .list-group-item strong { color:#FFFFFF !important; }

    html.dark-mode-custom .bg-white,
    html.dark-mode-custom .bg-light   { background:#12211D !important; color:#E6EDE8 !important; }
    html.dark-mode-custom .text-dark,
    html.dark-mode-custom .text-body  { color:#E6EDE8 !important; }
    html.dark-mode-custom .text-muted,
    html.dark-mode-custom small.text-muted { color:#8FA097 !important; }

    html.dark-mode-custom .table-bordered,
    html.dark-mode-custom .table-bordered td,
    html.dark-mode-custom .table-bordered th { border-color:#20342E !important; }
    html.dark-mode-custom .table-striped tbody tr:nth-of-type(odd) { background:#152722 !important; }
    html.dark-mode-custom .table-hover tbody tr:hover { background:#1B302A !important; color:#FFFFFF !important; }

    html.dark-mode-custom .nav-tabs { border-bottom-color:#20342E !important; }
    html.dark-mode-custom .nav-tabs .nav-link { color:#8FA097 !important; }
    html.dark-mode-custom .nav-tabs .nav-link.active {
      background:#12211D !important; color:#FFFFFF !important; border-color:#20342E #20342E #12211D !important; }
    html.dark-mode-custom .nav-pills .nav-link:not(.active) { color:#8FA097 !important; }
    html.dark-mode-custom .nav-pills .nav-link:hover:not(.active) { background:#1B302A !important; color:#FFFFFF !important; }

    html.dark-mode-custom .close, html.dark-mode-custom .close:hover { color:#E6EDE8 !important; opacity:.9; }
    html.dark-mode-custom hr { border-top-color:#20342E !important; }
    html.dark-mode-custom .badge-light { background:#20342E !important; color:#E6EDE8 !important; }
    html.dark-mode-custom .progress { background:#20342E !important; }
    html.dark-mode-custom .img-circle, html.dark-mode-custom .profile-user-img {
      border-color:#20342E !important; background:#12211D; }
    html.dark-mode-custom .select2-container--default .select2-selection--single,
    html.dark-mode-custom .select2-dropdown, html.dark-mode-custom .select2-search__field {
      background:#12211D !important; color:#E6EDE8 !important; border-color:#20342E !important; }
    html.dark-mode-custom .select2-container--default .select2-selection--single .select2-selection__rendered {
      color:#E6EDE8 !important; }

    /* =================================================================
       ADMINLTE PALETTE OVERRIDE
       The modules were built with stock AdminLTE classes (btn-primary,
       card-primary, bg-primary, nav-pills, btn-info), which render in
       Bootstrap blue and clash with the school green. Overriding them here
       recolours every module at once - Student, Enrollment, Payments,
       Subjects, Users, Attendance - instead of editing each file, and any
       module added later inherits the school colours automatically.
       ================================================================= */
    .btn-primary, .btn-primary:not(:disabled):not(.disabled).active,
    .btn-primary:not(:disabled):not(.disabled):active {
      background-color: var(--sj-g) !important; border-color: var(--sj-g) !important; color:#fff !important; }
    .btn-primary:hover, .btn-primary:focus {
      background-color: var(--sj-d) !important; border-color: var(--sj-d) !important;
      box-shadow: 0 0 0 .2rem rgba(0,128,96,.25) !important; }
    .btn-outline-primary {
      color: var(--sj-g) !important; border-color: var(--sj-g) !important; background: transparent !important; }
    .btn-outline-primary:hover { background: var(--sj-g) !important; color:#fff !important; }

    .btn-info { background-color: var(--sj-o) !important; border-color: var(--sj-o) !important; color:#fff !important; }
    .btn-info:hover, .btn-info:focus { background-color: var(--sj-g) !important; border-color: var(--sj-g) !important; }

    .bg-primary, .badge-primary { background-color: var(--sj-g) !important; color:#fff !important; }
    .bg-info,    .badge-info    { background-color: var(--sj-o) !important; color:#fff !important; }
    .text-primary { color: var(--sj-g) !important; }
    .text-info    { color: var(--sj-o) !important; }

    /* card headers and the coloured top border on card-outline */
    .card-primary:not(.card-outline) > .card-header { background-color: var(--sj-d) !important; border-color: var(--sj-d) !important; }
    .card-primary.card-outline { border-top: 3px solid var(--sj-g) !important; }
    .card-info:not(.card-outline)    > .card-header { background-color: var(--sj-g) !important; border-color: var(--sj-g) !important; }
    .card-info.card-outline    { border-top: 3px solid var(--sj-o) !important; }

    /* tabs and pills (the Profile Info / Contact Info switcher) */
    .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
      background-color: var(--sj-g) !important; color:#fff !important; }
    .nav-pills .nav-link { color: var(--sj-soft); border-radius:7px; font-weight:600; }
    .nav-pills .nav-link:hover:not(.active) { background:#EEF3EF; color: var(--sj-d); }
    .nav-tabs .nav-link.active { border-top-color: var(--sj-g) !important; color: var(--sj-d) !important; }

    /* form controls, checkboxes and DataTables paging */
    .form-control:focus, .custom-select:focus, .select2-container--default .select2-selection--single:focus {
      border-color: var(--sj-g) !important; box-shadow: 0 0 0 .2rem rgba(0,128,96,.15) !important; }
    .custom-control-input:checked ~ .custom-control-label::before {
      background-color: var(--sj-g) !important; border-color: var(--sj-g) !important; }
    .page-item.active .page-link { background-color: var(--sj-g) !important; border-color: var(--sj-g) !important; }
    .page-link { color: var(--sj-g); }
    .select2-container--default .select2-results__option--highlighted[aria-selected] { background-color: var(--sj-g) !important; }

    /* =================================================================
       PRINT LAYOUT
       Paper should carry the school's document, not a screenshot of the
       app. Everything that is navigation, chrome or decoration is dropped,
       and a proper letterhead is swapped in.
       ================================================================= */
    .sj-letterhead{ display:none; }
    @media print {
      @page { size: A4 portrait; margin: 14mm 12mm; }

      /* the application shell never goes on paper */
      .main-header, .main-sidebar, .main-footer, .control-sidebar,
      .content-header, .breadcrumb, .no-print, .noprint,
      .sj-mapfoot, .leaflet-control-container, .btn, button {
        display: none !important;
      }

      html, body, .wrapper, .content-wrapper {
        background: #fff !important;
        margin: 0 !important;
        padding: 0 !important;
        min-height: 0 !important;
      }
      /* AdminLTE pushes content right to clear the fixed sidebar */
      .content-wrapper { margin-left: 0 !important; }

      body {
        font-family: 'Times New Roman', Times, serif !important;
        color: #000 !important;
        font-size: 11pt;
      }

      /* the letterhead is print-only */
      .sj-letterhead { display: block !important; text-align: center;
        border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 14px; }
      .sj-letterhead img { height: 68px; margin-bottom: 4px; }
      .sj-letterhead h1 { font-size: 15pt; margin: 2px 0 0; letter-spacing: .5px; font-weight: bold; }
      .sj-letterhead .addr { font-size: 9pt; margin-top: 2px; }
      .sj-letterhead .doc { margin-top: 9px; font-size: 11pt; font-weight: bold; letter-spacing: 1px; }
      .sj-letterhead .meta { font-size: 8.5pt; margin-top: 4px; }

      /* keep tables readable and never split a row across pages */
      table { border-collapse: collapse !important; width: 100% !important; }
      tr, img { page-break-inside: avoid; }
      thead { display: table-header-group; }

      /* force real ink: browsers drop backgrounds by default anyway */
      * { box-shadow: none !important; text-shadow: none !important; }
      a[href]:after { content: ""; }
    }

    .main-footer{ background:#fff; border-top:1px solid var(--sj-ln); color:var(--sj-mu); font-size:.85rem; }
    .main-footer a{ color:var(--sj-g) !important; text-decoration:none; font-weight:700; }
    .main-footer a:hover{ color:var(--sj-d) !important; text-decoration:underline; }
    .main-footer strong, .main-footer b{ color:var(--sj-soft); }
    html.dark-mode-custom .main-footer a{ color:var(--sj-l) !important; }
    html.dark-mode-custom .main-footer strong,
    html.dark-mode-custom .main-footer b{ color:#C2CFC7; }

    /* ---------- confirm dialog (sign out) ---------- */
    .sjcs-confirm{ position:fixed; inset:0; z-index:9700; display:flex; align-items:center; justify-content:center; padding:24px; }
    .sjcs-confirm[hidden]{ display:none; }
    .sjcs-confirm-backdrop{ position:absolute; inset:0; background:rgba(0,20,18,.66); backdrop-filter:blur(2px); }
    .sjcs-confirm-box{ position:relative; z-index:1; width:100%; max-width:400px; background:#fff;
      border-radius:12px; padding:28px 26px 22px; text-align:center; box-shadow:0 26px 60px rgba(0,0,0,.4); }
    .sjcs-confirm-ic{ width:60px; height:60px; border-radius:50%; background:#FDF3F1; color:#B3261E;
      display:inline-flex; align-items:center; justify-content:center; font-size:1.5rem; margin-bottom:14px; }
    .sjcs-confirm-box h3{ font-size:1.2rem; font-weight:700; color:var(--sj-d); margin:0 0 8px; }
    .sjcs-confirm-box p{ font-size:.9rem; color:var(--sj-mu); margin:0 0 20px; line-height:1.55; }
    .sjcs-confirm-actions{ display:flex; gap:10px; }
    .sjcs-cbtn{ flex:1; display:inline-flex; align-items:center; justify-content:center; gap:7px;
      padding:11px 14px; border-radius:7px; border:none; cursor:pointer; font-weight:700; font-size:.84rem;
      text-decoration:none; transition:background .15s ease; }
    .sjcs-cbtn.ghost{ background:#F1F5F2; color:var(--sj-soft); }
    .sjcs-cbtn.ghost:hover{ background:#E4EBE6; }
    .sjcs-cbtn.danger{ background:#B3261E; color:#fff; }
    .sjcs-cbtn.danger:hover{ background:#8F1E17; color:#fff; }
    html.dark-mode-custom .sjcs-confirm-box{ background:#12211D; }
    html.dark-mode-custom .sjcs-confirm-box h3{ color:#fff; }
    html.dark-mode-custom .sjcs-cbtn.ghost{ background:#1C2E29; color:#C2CFC7; }
    html.dark-mode-custom .main-header.navbar.sjcs-topnav{ background:#12211D !important; border-bottom-color:#20342E !important; }
    html.dark-mode-custom .sjcs-topnav .nav-link{ color:#C2CFC7 !important; }
    html.dark-mode-custom .sjcs-usermenu{ background:#12211D; border-color:#20342E; }
    html.dark-mode-custom .sjcs-usermenu .dropdown-item{ color:#C2CFC7; }
    html.dark-mode-custom .sjcs-usermenu .dropdown-item:hover{ background:#182E28; color:#fff; }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <!--Insert Header Here-->
 <?php require_once("header.php") ; ?> 
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo  WEB_ROOT;?>" class="brand-link">
      <img src="<?php echo  WEB_ROOT;?>csr-scc.png" class="brand-image-fixed" alt="Logo"
           style="width:33px; height:33px; object-fit:contain; float:left; margin-left:.8rem; margin-top:-3px;">
      <span class="brand-text font-weight-light">St. Joseph CSSI</span>
    </a>

    <!-- Sidebar -->
  <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      

      <!-- Sidebar Menu -->
   <?php require_once("sidebar.php") ; ?> 
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

   <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <?php /* Print-only letterhead. Hidden on screen, printed on every page,
             so any module that gets printed carries the school identity. */ ?>
    <div class="sj-letterhead">
      <img src="<?php echo WEB_ROOT; ?>csr-scc.png" alt="School Seal">
      <h1>ST. JOSEPH CATHOLIC SCHOOL OF SAGAY, INC.</h1>
      <div class="addr">Sitio Palanas, Brgy. Poblacion II, Sagay City, Negros Occidental</div>
      <div class="addr">Diocese of San Carlos</div>
      <div class="doc"><?php echo strtoupper($title == 'Home' ? 'Administrative Summary Report' : $title); ?></div>
      <div class="meta">
        Printed <?php echo date('F j, Y \a\t g:i A'); ?>
        <?php if (!empty($_SESSION['DISPLAYNAME'])): ?>
          &nbsp;&middot;&nbsp; Printed by <?php echo htmlspecialchars($_SESSION['DISPLAYNAME']); ?>
          <?php echo !empty($_SESSION['TYPE']) ? '('.htmlspecialchars($_SESSION['TYPE']).')' : ''; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?php echo $title; ?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <?php /* The Home crumb now points at the signed-in role's own
                       dashboard instead of "#", and it is not repeated when
                       the page already IS Home - that is what produced the
                       "Home / Home" on the dashboard. */ ?>
              <?php if ($title == 'Home'): ?>
                <li class="breadcrumb-item active">Dashboard</li>
              <?php else: ?>
                <li class="breadcrumb-item">
                  <a href="<?php echo WEB_ROOT . portal_home_for(current_role()); ?>">Home</a>
                </li>
                <li class="breadcrumb-item active"><?php echo $title; ?></li>
              <?php endif; ?>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
     
       <!-- Main content -->
      <?php require_once $content; ?> 
    
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer no-print">
    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="<?php echo WEB_ROOT; ?>" target="_blank">St. Joseph Catholic School of Sagay, Inc.</a></strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.0.5
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?php echo  WEB_ROOT;?>plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo  WEB_ROOT;?>plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?php echo  WEB_ROOT;?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo  WEB_ROOT;?>plugins/select2/js/select2.full.min.js"></script>
<!-- DataTables -->
<script src="<?php echo  WEB_ROOT;?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo  WEB_ROOT;?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo  WEB_ROOT;?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo  WEB_ROOT;?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- ChartJS -->
<script src="<?php echo  WEB_ROOT;?>plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo  WEB_ROOT;?>plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?php echo  WEB_ROOT;?>plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?php echo  WEB_ROOT;?>plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- Leaflet -->
<script src="<?php echo  WEB_ROOT;?>plugins/leaflet/leaflet.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo  WEB_ROOT;?>plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?php echo  WEB_ROOT;?>plugins/moment/moment.min.js"></script>
<script src="<?php echo  WEB_ROOT;?>plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo  WEB_ROOT;?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- InputMask -->
<script src="<?php echo  WEB_ROOT;?>plugins/moment/moment.min.js"></script>
<script src="<?php echo  WEB_ROOT;?>plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
<!-- date-range-picker -->
<script src="<?php echo  WEB_ROOT;?>plugins/daterangepicker/daterangepicker.js"></script>
<!-- Summernote -->
<script src="<?php echo  WEB_ROOT;?>plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo  WEB_ROOT;?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- bs-custom-file-input -->
<script src="<?php echo  WEB_ROOT;?>plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo  WEB_ROOT;?>dist/js/adminlte.js"></script>
<!-- SweetAlert2 -->
<script src="<?php echo  WEB_ROOT;?>plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo  WEB_ROOT;?>dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo  WEB_ROOT;?>dist/js/demo.js"></script>
<script type="text/javascript">
// $(document).ready(function () {
 
//   $('[data-widget="pushmenu"]').PushMenu('toggle');

//   bsCustomFileInput.init();
// });
</script>
<script>
  $(function () {
   /* $("#example1").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });*/

    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
     $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });
  });
  
</script>



<script type="text/javascript">
  $(function() {
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });

  $('.swalDefaultSuccess').load(function() {
      Toast.fire({
        icon: 'success',
        title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.swalDefaultSuccess').click(function() {
      Toast.fire({
        icon: 'success',
        title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.swalDefaultInfo').click(function() {
      Toast.fire({
        icon: 'info',
        title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.swalDefaultError').click(function() {
      Toast.fire({
        icon: 'error',
        title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.swalDefaultWarning').click(function() {
      Toast.fire({
        icon: 'warning',
        title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.swalDefaultQuestion').click(function() {
      Toast.fire({
        icon: 'question',
        title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });

    $('.toastrDefaultSuccess').click(function() {
      toastr.success('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });
    $('.toastrDefaultInfo').click(function() {
      toastr.info('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });
    $('.toastrDefaultError').click(function() {
      toastr.error('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });
    $('.toastrDefaultWarning').click(function() {
      toastr.warning('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });

    $('.toastsDefaultDefault').click(function() {
      $(document).Toasts('create', {
        title: 'Toast Title',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultTopLeft').click(function() {
      $(document).Toasts('create', {
        title: 'Toast Title',
        position: 'topLeft',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultBottomRight').click(function() {
      $(document).Toasts('create', {
        title: 'Toast Title',
        position: 'bottomRight',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultBottomLeft').click(function() {
      $(document).Toasts('create', {
        title: 'Toast Title',
        position: 'bottomLeft',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultAutohide').click(function() {
      $(document).Toasts('create', {
        title: 'Toast Title',
        autohide: true,
        delay: 750,
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultNotFixed').click(function() {
      $(document).Toasts('create', {
        title: 'Toast Title',
        fixed: false,
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultFull').click(function() {
      $(document).Toasts('create', {
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.',
        title: 'Toast Title',
        subtitle: 'Subtitle',
        icon: 'fas fa-envelope fa-lg',
      })
    });
    $('.toastsDefaultFullImage').click(function() {
      $(document).Toasts('create', {
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.',
        title: 'Toast Title',
        subtitle: 'Subtitle',
        image: '../../dist/img/user3-128x128.jpg',
        imageAlt: 'User Picture',
      })
    });
    $('.toastsDefaultSuccess').click(function() {
      $(document).Toasts('create', {
        class: 'bg-success', 
        title: 'Toast Title',
        subtitle: 'Subtitle',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultInfo').click(function() {
      $(document).Toasts('create', {
        class: 'bg-info', 
        title: 'Toast Title',
        subtitle: 'Subtitle',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultWarning').click(function() {
      $(document).Toasts('create', {
        class: 'bg-warning', 
        title: 'Toast Title',
        subtitle: 'Subtitle',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultDanger').click(function() {
      $(document).Toasts('create', {
        class: 'bg-danger', 
        title: 'Toast Title',
        subtitle: 'Subtitle',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
    $('.toastsDefaultMaroon').click(function() {
      $(document).Toasts('create', {
        class: 'bg-maroon', 
        title: 'Toast Title',
        subtitle: 'Subtitle',
        body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });
  });
 

</script>
</body>
</html>