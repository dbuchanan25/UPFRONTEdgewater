<?php
$a = session_id();
if(empty($a)) session_start();
error_reporting(E_ERROR);
/*
 * Version 01_02
 * Page to determine access of the user and send them to the correct user page.
 * All users get forwarded to "choose2.php"
 *
 * Last Revised:  2014-01-10
 * Revised:  2012-05-10
 */
 

/*
 * Check to see if the user is logged in.
 * If not send them to the login page.
 */
if (!isset($_SESSION['username']))
{
    require_once ('includes/login_functions.inc.php');
    $url = absolute_url();
    header("Location: $url");
    exit();
}
else
{   
    require_once ('includes/connect2.php');
    echo '<title>User Menu</title>';
    echo'
        <link rel="stylesheet" href="style.css">
        ';
    include ('includes/header.php');
       
   
    $qs = "SELECT access ".
          "FROM users ".
          "WHERE username ".
          "LIKE '{$_SESSION['username']}%'";

    $q = mysql_query($qs);
    $a = mysql_fetch_row($q);

    $_SESSION['userLevel'] = $a[0];


    if ($_SESSION['userLevel']==1)
    {
        echo '  <center>
                <h2>
                CABS <br />
                User Menu
                </center>
                </h2>
                <br>
                <br>';
        
        echo '<table align="center" width="100%">
		  <form method="post" action="choose2.php" class="input">
                    <tr>
                        <td align="center">
			<input type="submit" name="fee" 
                        value="Fee Lookup" class="btn">
			</td>
                    </tr>
                  </form>
              </table>';	   
        echo   '<br>
                <br>
                <br>
                <br>';
    }
    else if ($_SESSION['userLevel']==2)
    {
        echo '  <center>
                <h2>
                CABS <br />
                High User Menu
                </center>
                </h2>
                <br>
                <br>';
        
        echo '<table align="center" width="100%">
		  <form method="post" action="choose2.php" class="input">
                    <tr>
                        <td align="center">
			<input type="submit" name="user" 
                        value="Edit Users" class="btn">
			</td>
                    </tr>
                    <tr height=30px>
                        <td></td>
                    </tr>
                  </form>
                  <form method="post" action="choose2.php" class="input">
                    <tr>
                        <td align="center">
			<input type="submit" name="fee" 
                        value="Fee Lookup" class="btn">
			</td>
                    </tr>
                  </form>
              </table>';	   
        echo   '<br>
                <br>
                <br>
                <br>';
    }
    else if ($_SESSION['userLevel']==3)
    {
        echo '  <center>
                <h2>
                CABS <br />
                Super User Menu
                </center>
                </h2>
                <br>
                <br>';
        
        echo '<table align="center" width="100%">
		  <form method="post" action="choose2.php" class="input">
                    <tr>
                        <td align="center">
			<input type="submit" name="fee" 
                        value="Fee Lookup" class="btn">
			</td>
                    </tr>
                    <tr height=30px>
                        <td></td>
                    </tr>
                    <tr>
                        <td align="center">
			<input type="submit" name="user" 
                        value="Edit Users" class="btn">
			</td>
                    </tr>
                    <tr height=30px>
                        <td></td>
                    </tr>
                    <tr>
                        <td align="center">
			<input type="submit" name="insurance" 
                        value="Edit Insurance Data" 
                            class="btn">
			</td>
                    </tr>
                    <tr height=30px>
                        <td></td>
                    </tr>
                    <tr>
                        <td align="center">
			<input type="submit" name="data" 
                            value="Update Surgeon Data" class="btn">
			</td>
                    </tr>
                    <tr height=10px>
                        <td>
                        </td>
                    </tr>                        
                    <tr>
                        <td align="center">
                        Make sure the file data.csv is loaded into the same
                        directory as the .php files.
                        </td>
                    </tr>
                    <tr height=30px>
                        <td></td>
                    <tr>
                        <td align="center">
			<input type="submit" name="discount" 
                        value="Change Discount Multiplier" class="btn">
			</td>
                    </tr>
                   </form>
              </table>';	   
        echo   '<br>
                <br>
                <br>'; 
    }
        include ('includes/logoutDirect.php');
        include ('includes/footer.html');
}