<?php
session_start();

/*
 * Version 01_01
 * Page present the information to get fees for the physicians going to the 
 * designated facilities and CPT codes.
 *
 * Last Revised:  2012-05-17
 * 
 */

/*Check to see is the user is logged in*/
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
   echo '<TITLE>Choose Insurance</TITLE>';
   include ('includes/header.php');
   
   $_SESSION['cpt']=$_POST['proc'];
   
   $q = "SELECT asa.asaCode, asa.asaDescription, asa.baseUnits 
         FROM asa
         INNER JOIN procedures ON asa.asaCode=procedures.asa
         WHERE procedures.cpt = {$_SESSION['cpt']}
         LIMIT 1";
   $r = mysql_query($q);
   $a = mysql_fetch_row($r);
   $_SESSION['asa'] = $a[0];
   $_SESSION['asaDescription'] = $a[1];
   $_SESSION['baseUnits'] = $a[2];
   $q = "SELECT cptDescriptor
         FROM cpt
         WHERE {$_SESSION['cpt']}=cptNumber
         LIMIT 1";
   $r = mysql_query($q);
   $b = mysql_fetch_row($r);
   $_SESSION['cptDescriptor'] = $b[0];
   
if ($a[2]==0)
{
    echo '<center><h2>
            Base Units are not entered in the database for this procedure.
            <br><br>
            Please notify Emily McCradden at the CABS office (704.749.5800 X104)
            <br>
            of this oversight.
            </center></h2><br><br>';
    echo  '<form name="input" action="choose2.php" method="post">
    <table align="center">
        <tr>
            <td align="center">
            <input type="submit" name="submit" class="btn" 
                value="Continue" style="width:150px; height:50px;">
            </td>
        </tr>
    </table><br><br><br>
    </form>';
}
else
{
   $sqlins = "SELECT DISTINCT insurance, insnumber
              FROM insurance";
   $sqlinsq = mysql_query($sqlins);
   
   echo '<table align="center" class="content" border="0" 
            width=100% bordercolor="#000000">
            <tr>
            <td width=10% align="right">Surgeon:
                </td>
                <td width=5%>
                </td>
                <td width=85% align="left">'.$_SESSION['surgeon'].'
            </td>
            </tr>
            
            <tr>            
            <td width=10% align="right">Procedure CPT:
                </td>
                <td width=5%>
                </td>
                <td width=85% align="left">'.$_SESSION['cpt'].'
            </td>
            </tr>
   
            <tr>
                <td width=10% align="right">CPT Description:
                </td>
                <td width=5%>
                </td>
                <td width=85% align="left">'.$_SESSION['cptDescriptor'].'
                </td>
            </tr>
            
            <tr>
            <td width=10% align="right">Procedure ASA:
                </td>
                <td width=5%>
                </td>
                <td width=85% align="left">'.$_SESSION['asa'].'
                </td>
            </tr>
            
            <tr>
                <td width=10% align="right">ASA Description:
                </td>
                <td width=5%>
                </td>
                <td width=85% align="left">'.$_SESSION['asaDescription'].'
                </td>
            </tr>';

   echo'
	 </table><br><br><br>';
  
   echo '<form method="post" action="feeCalculation.php">
         <div class="content">
	 <table align="center" class="content" border="0" width=100% 
            bordercolor="#000000"  bgcolor="#E5E5E5">
            <tr>
		<td width=40% align="right"> Choose Insurance: 
		</td>
                <td width=10%>
		</td>
		<td width=50% align="left">
		<select name="ins">';
   while($row = mysql_fetch_row($sqlinsq))
   {  
   echo "       <option value=$row[1]>$row[0]</option>\n";
   }
   echo'        </select>
                </td>
		<td width="250">
		</td>
            </tr>
	</table><br><br>';
		
		
 echo  '<table align="center" class="content" border="0" width=100% bordercolor="#000000">
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
 echo  '<form name="input" action="choose2.php" method="post">
        <table align="center" class="content" border="0" width=100% bordercolor="#000000">
            <tr>
                <td align="center">
                <input type="submit" name="submit" class="btn" 
                    value="Start Over" style="width:250px; height:60px;">
                </td>
            </tr>
        </table><br><br><br>
        </form>';
}	   
include ('includes/footer.html');
}
?>