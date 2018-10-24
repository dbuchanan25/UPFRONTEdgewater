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
echo '<TITLE>Modify Insurance</TITLE>';




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


    if (
            ((double)$_POST['timeUnit']==0 || (double)$_POST['minuteUnit']==0)
            &&
            (double)$_POST['fraction']<=1.0
       )
    {
        $q =   "UPDATE  insurance
                SET
                baseUnitFee =       {$_POST['baseUnit']},
                timeUnitFee =       {$_POST['timeUnit']},
                minuteFee =         {$_POST['minuteUnit']},
                fractionBilled =    {$_POST['fraction']}
                WHERE insnumber =   {$_SESSION['insurancenumber']}";
        //echo $q;

        if(mysql_query($q))
        {
            echo '<center><h2>'.
                    ' Information has been updated into the Insurance database.
                    </center></h2><br><br>';
        }
        else
        {
            echo '<center><h2>
                The insurance information was not entered correctly.
                </h2></center><br>';
        }
    
        include ('includes/startover.html');
        include ('includes/logoutDirect.php');
    }
    else
        include('choose1.php');
    
}

?>
