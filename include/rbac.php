<?php
// ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC. - Role-based access control
//
// Central place for role checks so a permission rule lives in exactly one
// spot instead of being copy-pasted (or forgotten) in every module. Session
// TYPE holds the human-readable role name from tblusers.TYPE (e.g.
// "Administrator", "Registrar") - the ROLE_* constants below are the
// canonical keys the rest of the app should compare against, so a future
// rename in tblusertype doesn't have to be chased through every file.
//
// IMPORTANT: this checks $_SESSION on the server on every request. It is
// not a "hide the menu item" trick - a signed-in Teacher who types
// module/user/index.php into the address bar directly gets bounced server
// side, same as one who never saw the link.

defined('ROLE_ADMIN')     ? null : define('ROLE_ADMIN',     'Administrator');
defined('ROLE_STAFF')     ? null : define('ROLE_STAFF',     'Staff'); // legacy type from the original fork, left alone
defined('ROLE_REGISTRAR') ? null : define('ROLE_REGISTRAR', 'Registrar');
defined('ROLE_CASHIER')   ? null : define('ROLE_CASHIER',   'Cashier');
defined('ROLE_TEACHER')   ? null : define('ROLE_TEACHER',   'Teacher');
defined('ROLE_STUDENT')   ? null : define('ROLE_STUDENT',   'Student');
defined('ROLE_APPLICANT') ? null : define('ROLE_APPLICANT', 'Applicant');

if (!function_exists('current_role')) {
    function current_role() {
        return isset($_SESSION['TYPE']) ? $_SESSION['TYPE'] : null;
    }
}

if (!function_exists('has_role')) {
    function has_role($allowed_roles) {
        if (!is_array($allowed_roles)) { $allowed_roles = array($allowed_roles); }
        return in_array(current_role(), $allowed_roles, true);
    }
}

// Where a given role's own portal lives. Centralized so login.php and
// require_role() never disagree about it.
if (!function_exists('portal_home_for')) {
    function portal_home_for($role) {
        switch ($role) {
            case ROLE_REGISTRAR: return "portal/registrar/index.php";
            case ROLE_CASHIER:   return "portal/cashier/index.php";
            case ROLE_TEACHER:   return "portal/teacher/index.php";
            case ROLE_STUDENT:   return "portal/student/index.php";
            case ROLE_APPLICANT: return "portal/applicant/index.php";
            case ROLE_ADMIN:
            case ROLE_STAFF:
            default:
                return "index.php";
        }
    }
}

// Call this as literally the first line of logic in any module or portal
// entry point that must be restricted (right after include/initialize.php
// is required, before anything else runs). Not logged in -> login.php.
// Logged in but wrong role -> bounced to their own portal home, not left
// on a page that then errors trying to render data they can't see.
if (!function_exists('require_role')) {
    function require_role($allowed_roles) {
        if (!isset($_SESSION['UID'])) {
            redirect_to(WEB_ROOT . "login.php");
        }
        if (!has_role($allowed_roles)) {
            redirect_to(WEB_ROOT . portal_home_for(current_role()));
        }
    }
}
?>
