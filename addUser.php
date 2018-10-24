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
echo '<TITLE>Add User</TITLE>';




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
    $loc = 0;
    $unique = true;
    $q = "SELECT username FROM users WHERE 1";
    $r = mysql_query($q);
    while($row = mysql_fetch_row($r))
    {
        if ($row[0] == $_POST['userName'])
        {
            $unique = false;
        }
    }
    if (
        strstr($_POST['firstName'],'enter first name here')==false
        &&  
        strstr($_POST['lastName'],'enter last name here')==false
        &&
        strstr($_POST['userName'],'enter user name here')==false
        &&
        isset($_POST['accessLevel']) 
        && 
        ($unique==true)
        &&
        isset($_POST['locations'])
        &&
        strstr($_POST['pass'],'enter password name here')==false
       )
      {
            foreach($_POST['locations'] as $l)
            {
                $loc += (pow(2,$l-1));
            }

            $q = "INSERT INTO users
                    VALUES
                    (
                    '{$_POST['firstName']}',
                    '{$_POST['lastName']}',
                    '{$_POST['userName']}',
                     {$_POST['accessLevel']},
                     $loc,
                     sha1('{$_POST['pass']}'),
                     NULL
                    )";
            if(mysql_query($q))
            {
                echo '<center><h2>'.
                      $_POST['firstName'].' '.$_POST['lastName'].
                      ' has been entered into the User database.
                      </center></h2><br><br>';
            }
            else
            {
                echo '<center><h2>The user was not entered correctly.
                      </h2></center>';
            }
      }
      else if ($unique==false)
      {
          echo '<center><h2>That username is already being used.<br>
                Please try again.</h2></center>';
      }
      else
      {
          echo '<center><h2>There was missing or incomplete data.<br>
                Please try again.</h2></center>';
      }
    include ('includes/startover.html');
    include ('includes/logoutDirect.php');
}
?>
