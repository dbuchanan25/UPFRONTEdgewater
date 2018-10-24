<?php
/*
 * Version 01_02
 * Page present the information to get fees for the physicians going to the 
 * designated facilities and CPT codes.
 *
 * Last Revised:  2014-04-20
 * Revised:  2012-05-10
 * 
 * 
 * Healthcare Facility ID = 83 The Surgery Center of Edgewater
SELECT  a.ID, CONVERT(VARCHAR(10), a.ServiceDt, 120) AS ServiceDt, b.Code, b.Modifier, 
b.ASACode, a.RefProvID, d.FirstName, d.LastName, c.Name, b.Units, a.PrimaryAnesthesiaMinutes, 
b.RenderingProviderID, e.ID AS cptID, e.LongDescription 
FROM Incident AS a 
INNER JOIN ServiceLine AS b 
ON a.ID = b.IncidentID 
INNER JOIN HealthCareFacility AS c 
ON a.FacilityID = c.ID 
INNER JOIN RefProvider AS d 
ON a.RefProvID = d.ID 
INNER JOIN CPTDescription AS e 
ON b.Code=e.Code 
WHERE c.ID = 83
AND ServiceDt >= DATEADD(month,-6,format(getutcdate(),'yyyy-MM-01'))
AND b.Code NOT LIKE ('%F%')
AND b.Code NOT LIKE ('%G%')
AND b.Code<'99000'
AND b.Code NOT LIKE ('01996')
AND b.ASACode NOT LIKE ('01967')
AND NOT (b.ASACode = '' AND b.Code<'63000') 
AND NOT (b.ASACode = '' AND b.Code > '76000') 
ORDER BY a.ID, ASACode DESC
 */
 
session_start();
echo'
    <link rel="stylesheet" href="style.css">
';
echo '<TITLE>Choose Surgeon</TITLE>';




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
    
    $q = "SELECT discount FROM selfdiscount";
    $r = mysql_query($q);
    if (mysql_num_rows($r)==1)
    {
        $a = mysql_fetch_row($r);
        $_SESSION['discount'] = $a[0];
    }
    else
    {
        echo '<center><h2>
              There is an error in obtaining the discount multiplier.
              <br>
              It will be set to 0.
              </center></h2><br>';
        $_SESSION['discount'] = 0;
    }
    
    
    
    if (!isset($_SESSION['location']))
    {
        $_SESSION['location']=$_POST['fee_location'];
    }
    
    $qLoc = "location LIKE '".$_SESSION['location']."%'";
   

    $qString = "SELECT DISTINCT ".
               "surgeonLast, surgeonFirst, surgeonID ". 
               "FROM procedures ". 
               "WHERE ".$qLoc.
               "ORDER BY surgeonLast";

    $r = mysql_query($qString);
    
    
    echo'<form method="post" action="procedure.php">
         <br><br>
                    <form method="post" action="fee.php" class="input">';

         echo '<table width="100%" bgcolor="#E5E5E5">
                    <tr>
                    <th align="right" width=40%>Choose Display Method:</th>
                    <td width = 10%></td>
                        <td align="left" width=50%>
                            <input name="cpt_method" type="radio" value="num" checked>
                            Sort CPT Codes by CPT Number<br>
                            <input name="cpt_method" type="radio" value="mc">
                            Sort CPT Codes by Most Common Used<br>
                        </td>
                    </tr>
               </table>
         <br>
         <br>
                    
         <div class="content">
         <table align="center" class="content" border="0" width=100% 
            bordercolor="#000000" bgcolor="#E5E5E5">
            <tr>
                <th width=40% align="right"> Choose Surgeon: 
		</th>
                <td width=10%>
                </td>
		<td width=50% align="left">
		<select name="surgnumber">';
                while($row = mysql_fetch_row($r))
    		{   
                    echo "<option value=$row[2]>$row[0], $row[1]</option>\n";
    		}
   echo'        </select>
                </td>
		<td width=55%>
		</td>
            </tr>
        </table>
        <br><br>';
		
		
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
 echo ' <form name="input" action="choose1.php" method="post">
        <table align="center" width=100%>
            <tr>
                <td align="center">
                <input type="submit" name="submit" class="btn" 
                    value="Start Over" style="width:250px; height:60px; valign:center">
                </td>
            </tr>
	</table>
	  <br>
	  <br>
	  <br>
        </form>';
}
?>
