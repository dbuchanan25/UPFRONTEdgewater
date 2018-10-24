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
echo '<TITLE>Modify Discount Multiplier</TITLE>';




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
    

//var_dump($_POST);
//var_dump($_SESSION);


    if (
            (double)$_POST['discount']<=1.0
       )
    {
        include ('includes/header.php');
        $q =   "UPDATE  selfdiscount
                SET
                discount =       {$_POST['discount']}";
        //echo $q;

        if(mysql_query($q))
        {
            echo '<center><h2>
                    Discount multiplier has been updated.
                    </center></h2><br><br>';
        }
        else
        {
            echo '<center><h2>
                The discount information was not entered correctly.
                </h2></center><br>';
        }
    
        include ('includes/startover.html');
        include ('includes/logoutDirect.php');
    }
    else
        include('choose1.php');    
}

?>
