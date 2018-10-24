<?php
$a = session_id();
if(empty($a)) session_start();
error_reporting(E_ERROR);
/*
 * Version 02_03
 * Page to determine access of the user and send them to the correct user page.
 *
 * Last Revised:  2014-12-11 
 *      Changed the Athena report to better capture the appropriate times,
 *      especially when there are multiple anesthesiologists for the same 
 *      case.  This necessitates changing the code where data is entered from
 *      printcsvreports.csv along with the function "setVariables".
 * Revised:  2014-04-19 
 * Revised:  2014-04-08
 * Revised:  2014-01-10
 * Revised:  2012-05-24
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
    require_once ('includes/connect2.php');
    require_once ('includes/connect.php');
    $countLoc = 0;
    
    unset($_SESSION['location']);
    unset($_SESSION['locDescription']);
    unset($_SESSION['minutes']);
    unset($_SESSION['surgnumber']);
    unset($_SESSION['surgeon']);
    unset($_SESSION['cpt']);
    unset($_SESSION['procedure']);
    unset($_SESSION['first']);
    unset($_SESSION['last']);
    unset($_SESSION['rows']);
    unset($_SESSION['reliability']);
    unset($_SESSION['insurance']);
    unset($_SESSION['insNum']);
    unset($_SESSION['baseUnits']);
    unset($_SESSION['asa']);
    unset($_SESSION['asaDescription']);
    
    
    for ($x = 0; $x < 16; $x++)
    { 
        /*
         * bit compares
         */
        $s = "SELECT (bitLoc>>$x & b'1') 
              FROM users 
              WHERE username 
              LIKE '{$_SESSION['username']}%'";
        $q = mysql_query($s);
        $r = mysql_fetch_row($q);
        if ($r[0] == 1)
        {
            $countLoc++;
            switch ($x)
            {
                case 0:
                    $_SESSION['location'] = 'The Surgery Center of Edgewater';
                    break;
            }
        }
    }
    if (isset($_POST['fee']))
    {
        if ($countLoc == 1)
        {
            require_once('fee.php');
        }
        else
        {
            include ('includes/header.php');
            unset($_SESSION['location']);

            echo '<br><br>
                    <form method="post" action="fee.php" class="input">';

            echo '<table width="100%" bgcolor="#E5E5E5">
                    <tr>
                    <th align="center" width=45%>Choose Location:</th>
                        <td align="left" width=55%>';

            for ($x = 0; $x < 16; $x++)
            {   
                $s = "SELECT (bitLoc>>$x & b'1') 
                      FROM users 
                      WHERE username 
                      LIKE '{$_SESSION['username']}%'";
                $q = mysql_query($s);
                $r = mysql_fetch_row($q);
                if ($r[0] == 1)
                {
                    switch ($x)
                    {
                        case 0:
                            echo'
                            <input name="fee_location" type="radio" value="The Surgery Center at Edgewater"><br>';
                            break;                       
                    }
                }
            }

            echo '
                            </td>
                        </tr>
                    </table>
                    <br><br>
                    <table align="center" width="100%">
                        <tr>
                        </tr>
                        <tr>
                            <td align="center">
                            <input type="submit" name="submit" class="btn" 
                            value="SUBMIT" style="width:250px; height:60px;">
                            </td>
                        </tr>
                        </form>
                    </table>
                    <br><br>
                ';

            include ('includes/startover.html');
            include ('includes/logoutDirect.php');         
            include ('includes/footer.html');
            }
        }
        else if (isset($_POST['user']))
        {
            include ('includes/header.php');
            echo '<br><br>
                    <form method="post" action="user1.php" class="input">';
            echo '<table width="100%" bgcolor="#E5E5E5">
                    <tr>
                    <th align="center" width=45%>Choose User Action:</th>
                        <td align="left" width=55%>';
            echo'
                        <input name="user_action" type="radio" 
                            value="add">
                        Add User<br>';
            echo'
                        <input name="user_action" type="radio" 
                            value="delete">
                        Delete User<br>';
            echo'
                        <input name="user_action" type="radio" 
                            value="modify">
                        Modify User<br>';
            echo '
                            </td>
                        </tr>
                    </table>
                    <br><br>
                    <table align="center" width="100%">
                        <tr>
                        </tr>
                        <tr>
                            <td align="center">
                            <input type="submit" name="submit" class="btn" 
                            value="SUBMIT" style="width:250px; height:60px;">
                            </td>
                        </tr>
                        </form>
                    </table>
                    <br><br>
                ';
            include ('includes/startover.html');
            include ('includes/logoutDirect.php');       
            include ('includes/footer.html');
    }
    else if (isset($_POST['insurance']))
    { 
        include ('includes/header.php');
            echo '<br><br>
                    <form method="post" action="insurance1.php" class="input">';
            echo '<table width="100%" bgcolor="#E5E5E5">
                    <tr>
                    <th align="center" width=45%>Choose User Action:</th>
                        <td align="left" width=55%>';
            echo'
                        <input name="user_action" type="radio" 
                            value="add">
                        Add Insurance<br>';
            echo'
                        <input name="user_action" type="radio" 
                            value="delete">
                        Delete Insurance<br>';
            echo'
                        <input name="user_action" type="radio" 
                            value="modify">
                        Modify Insurance<br>';
            echo '
                            </td>
                        </tr>
                    </table>
                    <br><br>
                    <table align="center" width="100%">
                        <tr>
                        </tr>
                        <tr>
                            <td align="center">
                            <input type="submit" name="submit" class="btn" 
                            value="SUBMIT" style="width:250px; height:60px;">
                            </td>
                        </tr>
                        </form>
                    </table>
                    <br><br>
                ';
        include ('includes/startover.html');
        include ('includes/logoutDirect.php');       
        include ('includes/footer.html');
    }
    else if (isset($_POST['discount']))
    {
        include ('includes/header.php');
        
        echo '<form method="post" action="discount.php">';
        
        echo '<center><h2>Change Discount Multiplier For Self Pay
                </h2></center><br>';
        
        $q = "SELECT discount FROM selfdiscount WHERE 1";
        $r = mysql_query($q);
        $a = mysql_fetch_row($r);
        
        echo '<div class="table">
                <table align="center" width=100%>
                    <tr>
                        <td width=40% align="right"> 
                        <b>Discount Multiplier:</b>
                        </td>
                        <td width=10%>
                        </td>
                        <td width=50% align="left">
                        <input type="text" NAME="discount" 
                            size="50" value="'.$a[0].'">
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
        include ('includes/footer.html');
    }
    
  
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
     /*
     * Use the report "CPT/Time NEW 20141211" From Athena
     * in the "NC - Presbyterian Anesthesia Associates - 1485".
     * In the Report
     * Library section "Other" -> "Practice Reports" -> "CPT/Time NEW 20141211"
     * 
     * This report must be run and then placed into the folder where the other
     * files are stored on the HostMonster - PAAPA.US site for Providence as 
     * "data.csv"
     */
    else if (isset($_POST['data']))
    {
        echo '<TITLE>Updating Procedure Information</TITLE>';
        echo'
        <link rel="stylesheet" href="style.css">
        ';
        include ('includes/header.php');
        
        
        
        set_time_limit(0);
   
        $myFile = "data.csv";
        $fh = @fopen($myFile, 'r');
        
        $finished = false;
        $errorr = false;
        
        if ($fh == false)
        {
            echo '<center><h2> 
                  There was a problem opening the correct file.
                  <br>
                  Please make sure the file data.csv is loaded in the web site
                  <br>
                  prior to trying again.
                  <br>
                  <br>
                  Click on CONTINUE.
                  <br><br><br></center></h2>';

            echo ' <form name="input" action="choose1.php" method="post">
            <table align="center" width=100%>
                <tr>
                    <td align="center">
                    <input type="submit" name="submit" class="btn" 
                        value="Continue" 
                        style="width:250px; height:60px; valign:center">
                    </td>
                </tr>
            </table>
            <br>
            <br>
            <br>
            </form>';
        }
        else
        {      
            /*
             * This code loads the new information into the MySQL database
             * regarding the operating times for each of the surgeons along 
             * with their preference for regional blocks for ortho cases.
             */
            
            $q = "TRUNCATE TABLE patientinfo";
            mysqli_query($dbc, $q);
            
            $q = "TRUNCATE TABLE procedures";
            mysqli_query($dbc, $q);

            /*
             * actually, $theFile is one line (string) of the file
             */
            
            $theFileLine = fgets($fh);
            if ($theFileLine[0] === 's')
            {
                $theFileLine = fgets($fh);
            }
            $theFileLine = fgets($fh);
            $theFileLine = fgets($fh);
            
            
             /*
             * [0] ID
             * [1] ServiceDt
             * [2] cpt
             * [3] modifier
             * [4] asa
             * [5] surgeonID
             * [6] surgeonFirst
             * [7] surgeonLast
             * [8] location
             * [9] units
             * [10] anesthesiaminutes
             * [11] renderingproviderid
             * [12] cptID
             */
            
            $linex = 0;
            while ($theLine = fgets($fh))
            {
                $array = str_getcsv($theLine);
                $qq = "INSERT INTO patientinfo VALUES ('".$array[0]."', '".$array[1]."', '".$array[2]."', '".$array[3]."', '".$array[4]."', ".
                      $array[5].", '".$array[6]."', '".$array[7]."', '".$array[8]."', ".$array[9].", ".$array[10].", ".$array[11].", ".$array[12].", ".$linex.")";
                mysqli_query($dbc, $qq);
               
                for ($y = 0; $y < 13; $y++)
                {
                   $linearray[$linex][$y] = $array[$y]; 
                }
                unset($array);
                $linex++;
            }
            
            
            for ($x = 0; $x < $linex; $x++)
            {
                $ptid = $linearray[$x][0];
                $num_rows = 1;

                for ($w = 1; $w <= 5; $w++)
                {
                    if ($linearray[$x+$w][0] === $ptid)
                        $num_rows++;
                }
                
              
                if ($num_rows === 1)
                {                  
                    $r = "INSERT INTO procedures VALUES ('".$linearray[$x][7]."', '".$linearray[$x][6]."', '".$linearray[$x][5]."', '".
                          $linearray[$x][2]."', '".$linearray[$x][4]."', ".$linearray[$x][10].", '".$linearray[$x][8]."', 0, 0, ".$linearray[$x][12].", ".$x.")";
                    $rr = mysqli_query($dbc, $r);
                }
                else if ($num_rows === 2)
                {
                    $r = "INSERT INTO procedures VALUES ('".$linearray[$x][7]."', '".$linearray[$x][6]."', '".$linearray[$x][5]."', '".
                          $linearray[$x][2]."', '".$linearray[$x][4]."', ".$linearray[$x][10].", '".$linearray[$x][8]."', 0, 0, ".$linearray[$x][12].", ".$x.")";
                    $rr = mysqli_query($dbc, $r);
                    
                    if (strlen(strstr(trim($linearray[$x+1][2]),"644"))>0 && strlen(strstr(trim($linearray[$x+1][3]),"59"))>0)       
                    {
                        $getBlockNumS = "SELECT blocknumber FROM blocks WHERE blockcpt LIKE '".$linearray[$x+1][2]."'";
                        $getBlockNumQ = mysqli_query($dbc, $getBlockNumS);
                        $getBlockNumR = mysqli_fetch_array($getBlockNumQ);
                        $getBlockNum  = $getBlockNumR[0];
                        
                        $s = "UPDATE procedures SET block=".$getBlockNum." WHERE indexx = ".$x;
                        $ss = mysqli_query($dbc, $s);
                    } 
                    $x = $x + $num_rows - 1;
                }
                else if ($num_rows == 3)
                {  
                    $r = "INSERT INTO procedures VALUES ('".$linearray[$x][7]."', '".$linearray[$x][6]."', '".$linearray[$x][5]."', '".
                          $linearray[$x][2]."', '".$linearray[$x][4]."', ".$linearray[$x][10].", '".$linearray[$x][8]."', 0, 0, ".$linearray[$x][12].", ".$x.")";
                    $rr = mysqli_query($dbc, $r);
                    
                   
                    if (strlen(strstr(trim($linearray[$x+1][2]),"644"))>0 && strlen(strstr(trim($linearray[$x+1][3]),"59"))>0)       
                    {
                        $getBlockNumS = "SELECT blocknumber FROM blocks WHERE blockcpt LIKE '".$linearray[$x+1][2]."'";
                        $getBlockNumQ = mysqli_query($dbc, $getBlockNumS);
                        $getBlockNumR = mysqli_fetch_array($getBlockNumQ);
                        $getBlockNum  = $getBlockNumR[0];
                        
                        $s = "UPDATE procedures SET block=".$getBlockNum." WHERE indexx = ".$x;
                        $ss = mysqli_query($dbc, $s);
                    }                   
                                       
                    if (strlen(strstr(trim($linearray[$x+2][2]),"644"))>0 && strlen(strstr(trim($linearray[$x+1][3]),"59"))>0)       
                    {
                        $getBlockNumS = "SELECT blocknumber FROM blocks WHERE blockcpt LIKE '".$linearray[$x+2][2]."'";
                        $getBlockNumQ = mysqli_query($dbc, $getBlockNumS);
                        $getBlockNumR = mysqli_fetch_array($getBlockNumQ);
                        $getBlockNum  = $getBlockNumR[0];
                        
                        $s = "UPDATE procedures SET block2=".$getBlockNum." WHERE indexx = ".$x;
                        $ss = mysqli_query($dbc, $s);
                    }                    
                                       
                    $x = $x + $num_rows - 1;
                }
                else
                {  
                   echo $x.'<br>';
                   $x = $x + $num_rows - 1;
                }
            }
             echo "<center><h2>Success!!<br><br>
                </center></h2>";

            echo ' <form name="input" action="updateSurgeonList.php" method="post">
            <table align="center" width=100%>
                <tr>
                    <td align="center">
                    <input type="submit" name="submit" class="btn" 
                        value="Update Surgeon List" 
                        style="width:250px; height:60px; valign:center">
                    </td>
                </tr>
            </table>
            <br>
            <br>
            <br>
            </form>';
        }        
        include ('includes/footer.html');
    }
}