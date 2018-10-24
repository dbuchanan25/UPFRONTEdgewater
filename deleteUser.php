<?php

/*
 * Version 01_01
 * Page to determine access of the user and send them to the correct user page.
 *
 * Last Revised:  2012-05-22
 */
 
session_start();
echo'
    <link rel="stylesheet" href="style.css">
';
echo '<TITLE>Delete User</TITLE>';




/*
 * Check to see if the user is logged in.
 * If not send them to the login page.
 */
if (!isset($_SESSION['username'])||!isset($_SESSION['userLevel']))
{
    require_once ('includes/login_functions.inc.php');
    $url = absolute_url();
    header("Location: $url");
    exit();
}
else
{
    require_once ('includes/connect2.php');
    include ('includes/header.php');
    //var_dump($_POST);
    
    $q = "SELECT *
          FROM users
          WHERE indexx = {$_POST['usernumber']}";
    $r = mysql_query($q);
    $a = mysql_fetch_row($r);
    echo '<center><h2>'.$a[0].' '.$a[1].' has been deleted.</center></h2>';
    
    $q = "DELETE FROM users WHERE indexx = {$_POST['usernumber']}";
    $r = mysql_query($q);
        
    include ('includes/startover.html');
    include ('includes/logoutDirect.php');
}
?>
