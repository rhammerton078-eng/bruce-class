<?php

// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC.
// Sign-in controller. There is no login page any more: staff sign in from
// the popup on portals.php. This file only:
//   1. sends an already signed-in user to their dashboard,
//   2. processes the popup's POST,
//   3. forwards every other visit to the portals page.
// No HTML is printed here, so nothing flashes on screen during logout.

require_once("include/initialize.php");

$PORTALS = WEB_ROOT . 'portals.php';

/* Header redirect: instant, and nothing renders on the way. Falls back to
   the JS helper if headers were already sent for any reason. */
function sjcs_go($url) {
    if (!headers_sent()) { header('Location: ' . $url); exit; }
    redirect($url); exit;
}

/* Already signed in -> straight to the right dashboard. */
if (isset($_SESSION['UID'])) {
    sjcs_go(portal_home_for(current_role()));
}

/* Credentials submitted from the sign-in popup. */
if (isset($_POST['btnLogin'])) {

    $email = isset($_POST['username']) ? trim($_POST['username']) : '';
    $upass = isset($_POST['userpass']) ? trim($_POST['userpass']) : '';

    if ($email == '' || $upass == '') {
        sjcs_go($PORTALS . '?err=empty');
    }

    $h_upass = sha1($upass);
    $user = new User();
    $res  = $user::AuthenticateUser($email, $h_upass);

    if ($res == true) {
        sjcs_go(portal_home_for(current_role()));
    }

    sjcs_go($PORTALS . '?err=invalid');
}

/* Anything else (a bookmark, or the redirect after logout) goes to the
   portals page, where the sign-in popup lives. */
sjcs_go($PORTALS);
?>