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
echo '<TITLE>User Actions</TITLE>';

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

    
    if (isset($_POST['user_action']))
    {         
        if ($_POST['user_action']=='delete')
        {
            $q = "SELECT *
                FROM users
                ORDER BY last, first";
            $r = mysql_query($q);
      echo '<form method="post" action="deleteUser.php">
            <div class="content">
            <table align="center" width=100%  bgcolor="#E5E5E5">
                <tr>
                    <td width=40% align="right"> Choose User to Delete: 
                    </td>
                    <td width=10%>
                    </td>
                    <td width=50% align="left">
                    <select name="usernumber">';
            while($row = mysql_fetch_row($r))
            {   
                echo "<option value=$row[6]>$row[1], $row[0]</option>\n";
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
        echo   '<form method="post" action="addUser.php">
                <div class="table">
                <table align="center" width=100%>
                    <tr>
                        <td width=40% align="right"> <b>First Name:</b> 
                        </td>
                        <td width=10%>
                        </td>
                        <td width=50% align="left">
                        <input type="text" NAME="firstName" size="50" 
                        value="enter first name here" >
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
                        <input type="text" NAME="lastName" size="50" 
                        value="enter last name here" >
                        </td>
                    </tr>
                </table>
                </div>
                <br>
                <div class="table">
                <table align="center" width=100%>
                    <tr>
                        <td width=40% align="right"> <b>User Name:</b>
                        </td>
                        <td width=10%>
                        </td>
                        <td width=50% align="left">
                        <input type="text" NAME="userName" size="50" 
                        value="enter user name here" >
                        </td>
                    </tr>
                </table>
                </div>
                <br>
                <div class="table">
                <table align="center" width=100%>
                    <tr>
                        <td width=40% align="right">
                        Choose Access Level:
                        </td>
                        <td width=10%></td>
                        <td width=50% align="left">
                        <input name="accessLevel" type="radio" value="1">
                            1 - General User (allows Fee Lookup)<br>
                        <input name="accessLevel" type="radio" value="2">
                            2 - High User (allows Edit Users and Fee Lookup)<br>
                        <input name="accessLevel" type="radio" value="3">
                            3 - Superuser<br>
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
                        <td width=50% align="left">
                        <input name="locations[]" type="checkbox" value="1">
                            The Surgery Center at Edgewater<br>';

        echo'
                    </tr>
                </table>
                </div>
                <br>
                <div class="table">
                <table align="center" width=100%>
                    <tr>
                        <td width=40% align="right"> <b>Password:</b> 
                        </td>
                        <td width=10%>
                        </td>
                        <td width=50% align="left">
                        <input type="text" NAME="pass" size="50" 
                        value="enter password here" >
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
            FROM users
            ORDER BY last, first";
        $r = mysql_query($q);
        echo'<form method="post" action="modifyUser.php">
            <div class="content">
            <table align="center" width=100%  bgcolor="#E5E5E5">
                <tr>
                    <td width=40% align="right"> Choose User to Modify: 
                    </td>
                    <td width=10%>
                    </td>
                    <td width=50% align="left">
                    <select name="usernumber">';
            while($row = mysql_fetch_row($r))
            {   
                echo "<option value=$row[6]>$row[1], $row[0]</option>\n";
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
