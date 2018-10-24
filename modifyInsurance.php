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
    $q = "SELECT * FROM insurance WHERE insnumber = {$_POST['insurancenumber']}";
    $r = mysql_query($q);
    $a = mysql_fetch_row($r);
    
    $_SESSION['insurancenumber'] = $_POST['insurancenumber'];
    
    echo '<center><h3>
          Make any necessary changes and click on the SUBMIT button.
          </center></h3><br><br>';
    
    echo '<div class="table">
            <table align="center" width=100%>
                <tr>
                    <td width=40% align="right"> <b>
                    (cannot be changed) Insurance:</b> 
                    </td>
                    <td width=10%>
                    </td>
                    <td width=50% align="left">'.
                    $a[0].'
                    </td>
                </tr>
            </table>
            </div>
            <br>';

    echo'<form method="post" action="modifyInsurance2.php">
            <div class="table">
            <table align="center" width=100%>
                <tr>
                    <td width=40% align="right"> <b>Base Unit Rate:</b> 
                    </td>
                    <td width=10%>
                    </td>
                    <td width=50% align="left">
                    <input type="text" NAME="baseUnit" size="50" value="'.$a[1].'" >
                    </td>
                </tr>
            </table>
            </div>
            <br>
            <div class="table">
            <table align="center" width=100%>
                <tr>
                    <td width=40% align="right"> <b>Time Unit Rate:</b> 
                    </td>
                    <td width=10%>
                    </td>
                    <td width=50% align="left">
                    <input type="text" NAME="timeUnit" size="50" value="'.$a[2].'" >
                    </td>
                </tr>
            </table>
            </div>
            <br>
            <div class="table">
            <table align="center" width=100%>
                <tr>
                    <td width=40% align="right"> <b>Minute Unit Rate:</b>
                    </td>
                    <td width=10%>
                    </td>
                    <td width=50% align="left">
                    <input type="text" NAME="minuteUnit" size="50" value="'.$a[3].'" >
                    </td>
                </tr>
            </table>
            </div>
            <br>
            <div class="table">
            <table align="center" width=100%>
                <tr>
                    <td width=40% align="right"> <b>Fraction Multiplier:</b>
                    </td>
                    <td width=10%>
                    </td>
                    <td width=50% align="left">
                    <input type="text" NAME="fraction" size="50" value="'.$a[4].'" >
                    </td>
                </tr>
            </table>
            </div>
            <br>';
            
    
    echo '<table align="center" class="content" border="0" 
        width=100% bordercolor="#000000">
        <tr>
	</tr>
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
