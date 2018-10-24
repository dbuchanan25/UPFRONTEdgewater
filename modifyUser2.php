<?php

/*
 * Version 01_01
 * Page to determine access of the user and send them to the correct user page.
 *
 * Last Revised:  2012-05-24
 */
 
session_start();
echo'
    <link rel="stylesheet" href="style.css">
';
echo '<TITLE>Modify User</TITLE>';




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
//var_dump($_SESSION);

    $loc = 0;

    foreach($_POST['locations'] as $l)
    {
        $loc += (pow(2,$l-1));
    }

    $q =   "UPDATE  users
            SET
            first = '{$_POST['firstName']}',
            last =  '{$_POST['lastName']}',
            access = {$_POST['access']},
            bitLoc =  $loc
            WHERE indexx = {$_SESSION['usernumber']}";

    if(mysql_query($q))
    {
        echo '<center><h2>'.
                $_POST['firstName'].' '.$_POST['lastName'].
                ' information has been updated into the User database.
                </center></h2><br><br>';
    }
    else
    {
        echo '<center><h2>
            The user was not entered correctly.</h2></center><br>';
    }
    include ('includes/startover.html');
    include ('includes/logoutDirect.php');
}

?>
