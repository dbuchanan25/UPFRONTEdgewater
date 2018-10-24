<?php
$a = session_id();
if(empty($a)) session_start();


/*Check to see is the user is logged in*/
if (!isset($_SESSION['username']))
{
   require_once ('includes/login_functions.inc.php');
   $url = absolute_url();
   header("Location: $url");
   exit();
}
else
{
    echo '<TITLE>Logout</TITLE>';
    include ('includes/header.php');
    echo '
    <center><h2>
    You have logged out.
    </h2></center><br><br>';
	
    include ('includes/footer.html');
    $_SESSION = array();
    session_destroy();
}
		
