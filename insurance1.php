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
echo '<TITLE>Insurance Actions</TITLE>';




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
    echo '<TITLE>Choose Insurance</TITLE>';
    include ('includes/header.php');
    require_once ('includes/connect2.php');
    if (isset($_POST['user_action']))
    {  

        if ($_POST['user_action']=='delete')
        {
            $q = "SELECT *
                  FROM insurance
                  ORDER BY insurance";
            $r = mysql_query($q);
        echo'<form method="post" action="deleteInsurance.php">
            <div class="content">
            <table align="center" width=100%  bgcolor="#E5E5E5">
                <tr>
                    <td width=40% align="right"> Choose Insurance to Delete: 
                    </td>
                    <td width=10%>
                    </td>
                    <td width=50% align="left">
                    <select name="insurancenumber">';
            while($row = mysql_fetch_row($r))
            {   
                echo "<option value=$row[5]>$row[0]</option>\n";
            }
    echo'        </select>
                    </td>
                    <td width=55%>
                    </td>
                </tr>
            </table>
            <br><br>';
    }
    else if ($_POST['user_action']=='add')
    {
        echo'<form method="post" action="addInsurance.php">
                <div class="table">
                <table align="center" width=100%>
                    <tr>
                        <td width=40% align="right"> <b>Insurance:</b> 
                        </td>
                        <td width=10%>
                        </td>
                        <td width=50% align="left">
                        <input type="text" NAME="insurance" size="50" 
                            value="enter insurance plan name here">
                        </td>
                    </tr>
                </table>
                </div>
                <br>

                <div class="table">
                <table align="center" width=100%>
                    <tr>
                        <td width=40% align="right"> <b>Base Unit Fee:</b>
                        </td>
                        <td width=10%>
                        </td>
                        <td width=50% align="left">
                        <input type="text" NAME="baseUnit" 
                            size="50" value="enter base unit amount here">
                        </td>
                    </tr>
                </table>
                </div>
                <br>

                <div class="table">
                <table align="center" width=100%>
                    <tr>
                        <td width=40% align="right"> <b>Time Unit Fee:</b>
                        </td>
                        <td width=10%>
                        </td>
                        <td width=50% align="left">
                        <input type="text" NAME="timeUnit" 
                            size="50" value="enter time unit amount here">
                        </td>
                    </tr>
                </table>
                </div>
                <br>

                <div class="table">
                <table align="center" width=100%>
                    <tr>
                        <td width=40% align="right"> <b>Minute Unit Fee:</b>
                        </td>
                        <td width=10%>
                        </td>
                        <td width=50% align="left">
                        <input type="text" NAME="minuteUnit" 
                            size="50" 
                            value="enter minute unit amount here" >
                        </td>
                    </tr>
                </table>
                </div>
                <br>

                <div class="table">
                <table align="center" width=100%>
                    <tr>
                        <td width=40% align="right"> <b>Fraction:</b>
                        </td>
                        <td width=10%>
                        </td>
                        <td width=50% align="left">
                        <input type="text" NAME="fraction" 
                            size="50" 
                            value="enter fraction of amount billed here">
                        </td>
                    </tr>
                </table>
                </div>
                <br>
                <br>';
    }

    else if ($_POST['user_action']=='modify')
    {

        $q = "SELECT *
            FROM insurance
            ORDER BY insurance";
        $r = mysql_query($q);
        echo'<form method="post" action="modifyInsurance.php">
            <div class="content">
            <table align="center" width=100%  bgcolor="#E5E5E5">
                <tr>
                    <td width=40% align="right"> Choose Insurance to Modify: 
                    </td>
                    <td width=10%>
                    </td>
                    <td width=50% align="left">
                    <select name="insurancenumber">';
            while($row = mysql_fetch_row($r))
            {   
                echo "<option value=$row[5]>$row[0]</option>\n";
            }
    echo'        </select>
                    </td>
                    <td width=55%>
                    </td>
                </tr>
            </table>
            <br><br>';
    }

    echo '<table align="center" class="content" border="0" 
            width=100% bordercolor="#000000">
            <tr>
            </tr>
            <tr>
                <td>
                </td>
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
    else
        include('choose1.php');
}
?>
