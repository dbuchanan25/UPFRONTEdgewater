<?php
$a = session_id();
if(empty($a)) session_start();
error_reporting(E_ERROR);
/*
 * Version 01_01
 * Page presents the information to get fees for the physicians going to the 
 * designated facilities and CPT codes.
 *
 * Last Revised:  2012-05-17
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
   echo '<TITLE>Choose Procedure</TITLE>';
   require_once ('includes/connect2.php');
   require_once ('includes/connect.php');
   include ('includes/header.php');
   
   $_SESSION['surgeonID']=$_POST['surgnumber'];
   
   $sqlmd = "SELECT surgeonLast, surgeonFirst
             FROM surgeons
	     WHERE surgeonID= '{$_SESSION['surgeonID']}'";
   $sqlmdq = mysql_query($sqlmd);
   $sqlmdr = mysql_fetch_row($sqlmdq);
   $_SESSION['surgeon']=$sqlmdr[0].', '.$sqlmdr[1];
   $_SESSION['first'] = $sqlmdr[1];
   $_SESSION['last'] = $sqlmdr[0];
   
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
          </table>
          <br><br><br>';

   $sqlprocedure = "SELECT DISTINCT a.cpt, a.asa, b.cptDescriptor ".
                   "FROM procedures AS a ".
                   "INNER JOIN cpt AS b ".
                   "ON a.cptID=b.cptID ".
                   "WHERE a.surgeonID LIKE '".$_SESSION['surgeonID']."' ".
                   "ORDER BY a.cpt";

   $sqlprocedureq = mysqli_query($dbc, $sqlprocedure);   
   $x = 0;
   
   while ($a = mysqli_fetch_row($sqlprocedureq))
   {
       $aa[$x][0] = $a[0];
       $aa[$x][1] = $a[1];
       $aa[$x][2] = $a[2];
       $q = "SELECT count(*) ".
            "FROM procedures ".
            "WHERE cpt = {$aa[$x][0]} ".
            "AND surgeonID LIKE '".$_SESSION['surgeonID']."' ";
       $r = mysql_query($q);
       $aaa = mysql_fetch_row($r);
       $aa[$x][3] = $aaa[0];
       $x++;
   }
   if ($_POST['cpt_method']=='mc')
   {
        $aa = subval_sort($aa, 3);
        //var_dump($aa);
   }
  
   echo'<form method="post" action="insurance.php">
        <div class="content">
	<table align="center" class="content" border="0" 
            width=100% bordercolor="#000000"  bgcolor="#E5E5E5">
            <tr>
                <td width=100% align="center"> Choose Procedure: 
                </td>
            </tr>
        </table>
        <table align="center" class="content"" 
            width=100% bgcolor="#E5E5E5">
            <tr>
                <td width=5% align="right" style="font-size:x-small">CPT:
                </td>
                <td width=5% align="center" style="font-size:x-small">ASA:
                </td>
                <td width=90% align="left" 
                    style="font-size:x-small">CPT Description:
                </td>
            </tr>
        </table>
        <table align="center" class="content" border="0" 
            width=100% bordercolor="#000000"  bgcolor="#E5E5E5">
            <tr>
		<td width=100% align="center"> 
                <select name="proc">';
                foreach ($aa as $value)
    		{  
                   echo "<option value=$value[0]>$value[0]&nbsp;$value[1]
                            &nbsp;$value[2]</option>\n";
    		}
   echo'        </select>
                </td>
		<td width="250">
                </td>
            </tr>
        </table>
	<br><br>';
		
		
 echo '<table align="center" class="content" border="0" width=100% 
            bordercolor="#000000">
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
                    value="Start Over" 
                    style="width:250px; height:60px; valign:center">
                </td>
            </tr>
	</table>
	  <br>
	  <br>
	  <br>
        </form>';


	   
include ('includes/footer.html');
}

function subval_sort($a,$subkey) 
{
    foreach($a as $k=>$v) 
    {
            $b[$k] = strtolower($v[$subkey]);
    }

    asort($b);

    foreach($b as $key=>$val) 
    {
            $c[] = $a[$key];
    }

    return array_reverse($c);
}
?>