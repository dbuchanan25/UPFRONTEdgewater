<?php
session_start();

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