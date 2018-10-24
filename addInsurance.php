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
echo '<TITLE>Add Insurance</TITLE>';




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
    $unique = true;
    $q = "SELECT insurance FROM insurance WHERE 1";
    $r = mysql_query($q);
    while($row = mysql_fetch_row($r))
    {
        if ($row[0] == $_POST['insurance'])
        {
            $unique = false;
        }
    }

    if (
        strstr($_POST['insurance'],'enter insurance plan name here')==false
        &&  
        strstr($_POST['baseUnit'],'enter base unit amount here')==false
        &&
        strstr($_POST['timeUnit'],'enter time unit amount here')==false
        &&
        strstr($_POST['minuteUnit'],'enter minute unit amount here')==false
        &&
        strstr($_POST['fraction'],'enter fraction of amount billed here')==false
        &&
        ((double)$_POST['timeUnit']==0 || (double)$_POST['minuteUnit']==0)
        &&
        ((double)$_POST['fraction']<=1.0)
       )
     {
          $q = "INSERT INTO insurance
                VALUES
                (
                '{$_POST['insurance']}',
                {$_POST['baseUnit']},
                {$_POST['timeUnit']},
                {$_POST['minuteUnit']},
                {$_POST['fraction']},
                NULL   
                )";
        if(mysql_query($q))
        {
            echo '<center><h2>'.
                $_POST['insurance'].
                ' has been entered into the Insurance database.
                </center></h2><br><br>';
        }
        else
        {
            echo '<center><h2>The insurance information was not entered correctly.
                </h2></center><br>';
        }     
      }
      else if ($unique==false)
      {
          echo '<center><h2>That username is already being used.<br>
                Please try again.</h2></center><br>';
      }
      else
      {
          echo '<center><h2>There was missing or incomplete data.<br>
                Please try again.</h2></center><br>';
      }
    include ('includes/startover.html');
    include ('includes/logoutDirect.php');
}
?>
