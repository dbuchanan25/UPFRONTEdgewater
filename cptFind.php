<?php

session_start();
echo'
    <link rel="stylesheet" href="style.css">
';
echo '<TITLE>Locations</TITLE>';

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
    $q = "SELECT DISTINCT asa FROM procedures ORDER BY asa";
    $r = mysql_query($q);
    while ($s = mysql_fetch_array($r))
    {
        $qq = "SELECT asaCode FROM asa WHERE asaCode LIKE '%".$s[0]."%'";
        //echo $qq;
        $rr = mysql_query($qq);
        if (mysql_num_rows($rr) === 0)
        {
            echo $s[0].'<br>';
        }           
    }
}
?>
