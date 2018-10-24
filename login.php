<?php
$a = session_id();
if(empty($a)) session_start();
error_reporting(E_ERROR);
/*
 * Beginning of UPFRONT web site to calculate an estimate of how much someone
 * will owe in anesthesia fees based on information obtained from Athena 
 * regarding length of specific operation, surgeron performing the operation,
 * type of insurance, and unit rate.
 * 
 * Last revised 2014-01-10
 * 
 * This page is forwarded to "choose1.php" if the user is successful logging in.
 * 
 * Dependencies are /includes/login_functions.inc.php and /connect.php
 */

if (isset($_POST['submitted']))
{
   require_once ('includes/login_functions.inc.php');
   require_once ('includes/connect.php');
   
   list ($check, $data) = check_login($dbc, $_POST['username'], $_POST['passw']);

   if ($check)
   {
	  $_SESSION['username'] = $data['username'];
	  $_SESSION['passw'] = $data['passw'];
	  $url = absolute_url('choose1.php');
	  header("Location: $url");
	  exit();
   }
   else
   {
      $errors = $data;
   }
   mysqli_close($dbc);
}



include ('includes/login_page.inc.php');
?>