<?php
$a = session_id();
if(empty($a)) session_start();
error_reporting(E_ERROR);
/*
 * 
 */
 
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
    require_once ('includes/connect.php');
    include ('includes/header.php');
    $qd = "TRUNCATE TABLE surgeons";
    mysqli_query($dbc, $qd);
    $q = "SELECT DISTINCT procedures.surgeonLast, procedures.surgeonFirst, procedures.surgeonID ".
         "FROM procedures ".
         "WHERE NOT EXISTS ".
         "(SELECT * ".
         "FROM surgeons ".
         "WHERE surgeons.surgeonID = procedures.surgeonID) ".
         "ORDER BY procedures.surgeonLast, procedures.surgeonFirst";
    $r = mysqli_query($dbc, $q);
    while ($a = mysqli_fetch_row($r))
    {
       $qq = "INSERT INTO surgeons VALUES ('$a[0]', '$a[1]', $a[2], NULL)";
       mysqli_query($dbc, $qq);
    }

            echo "<center><h2>Success!!<br><br>
                Finished<br><br>
                </center></h2>";

            echo ' <form name="input" action="choose1.php" method="post">
            <table align="center" width=100%>
                <tr>
                    <td align="center">
                    <input type="submit" name="submit" class="btn" 
                        value="Continue" 
                        style="width:150px; height:50px; valign:center">
                    </td>
                </tr>
            </table>
            <br>
            <br>
            <br>
            </form>';        
    include ('includes/footer.html');
}
?>
