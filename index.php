<?php 

//ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC.

require_once("include/initialize.php");
   if (!isset($_SESSION['UID'])){
      // Anonymous visitors get the public marketing homepage, not a bounce
      // straight to the staff login screen. Logged-in behavior below is
      // unchanged.
      require_once("homepage_public.php");
      exit;
     }else{
      
     } 
$title="Home"; 
$content='home.php';
$view = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : '';
switch ($view) {
  case '1' :
         $title="Home"; 
     $content='home.php'; 
    
    break;  
  default :
    $content    = 'home.php'; 
}
require_once("theme/template.php");
?>

