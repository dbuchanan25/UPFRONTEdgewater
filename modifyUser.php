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
    $q = "SELECT * FROM users WHERE indexx = {$_POST['usernumber']}";
    $r = mysql_query($q);
    $a = mysql_fetch_row($r);
    
    $_SESSION['usernumber'] = $_POST['usernumber'];
    
    echo '<center><h3>
          Make any necessary changes and click on the SUBMIT button.
          </center></h3><br><br>';
    
    echo '<div class="table">
            <table align="center" width=100%>
                <tr>
                    <td width=40% align="right"> <b>
                    (cannot be changed) User Name:</b> 
                    </td>
                    <td width=10%>
                    </td>
                    <td width=50% align="left">'.
                    $a[2].'
                    </td>
                </tr>
            </table>
            </div>
            <br>';

    echo'<form method="post" action="modifyUser2.php">
            <div class="table">
            <table align="center" width=100%>
                <tr>
                    <td width=40% align="right"> <b>First Name:</b> 
                    </td>
                    <td width=10%>
                    </td>
                    <td width=50% align="left">
                    <input type="text" NAME="firstName" size="50" value="'.$a[0].'" >
                    </td>
                </tr>
            </table>
            </div>
            <br>
            <div class="table">
            <table align="center" width=100%>
                <tr>
                    <td width=40% align="right"> <b>Last Name:</b> 
                    </td>
                    <td width=10%>
                    </td>
                    <td width=50% align="left">
                    <input type="text" NAME="lastName" size="50" value="'.$a[1].'" >
                    </td>
                </tr>
            </table>
            </div>
            <br>
            <div class="table">
            <table align="center" width=100%>
                <tr>
                    <td width=40% align="right"> <b>Access Code:</b>
                    </td>
                    <td width=10%>
                    </td>
                    <td width=50% align="left">
                    <input type="text" NAME="access" size="50" value="'.$a[3].'" >
                    </td>
                </tr>
            </table>
            </div>
            <br>
            <div class="table">
            <table align="center" width=100%>
                <tr>
                    <td width=40% align="right">
                    Choose Locations Allowed:
                    </td>
                    <td width=10%></td>
                    <td width=50% align="left">';
    
    if (((binary)$a[4]>>0 & (binary)1) == 1)
    {
        echo'
                    <input name="locations[]" type="checkbox" value="1" checked>
                       The Surgery Center at Edgewater<br>';
    }
    
    echo'
                    </td>
                </tr>
            </table>
            </div>
            <br>
            <br>';
    
    echo '<table align="center" class="content" border="0" 
        width=100% bordercolor="#000000">
        <tr>
            <td align="center">
            <input type="submit" name="submit" class="btn" 
                value="SUBMIT" style="width:250px; height:60px;">
            </td>
        </tr>
       </table>
       </form>
       <br>
       <br>
      ';
    include ('includes/startover.html');
    include ('includes/logoutDirect.php');
}
?>
