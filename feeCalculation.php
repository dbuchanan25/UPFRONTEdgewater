<?php
session_start();

/*
 * Version 02_02
 * Page present the information to get fees for the physicians going to the 
 * designated facilities and CPT codes.
 *
 * Last Revise:  2014-04-20
 * Revised:  2012-05-20
 * 
 */

function array_mode($array,$justMode=0) 
{
    $count = array();
    foreach ($array as $item) 
    {
        if ( isset($count[$item]) ) 
        {
          $count[$item]++;
        }
        else
        {
          $count[$item] = 1;
        }
    }
  
    $mostcommon = '';
    $iter = 0;
  
    foreach ( $count as $k => $v ) 
    {
        if ( $v > $iter ) 
        {
            $mostcommon = $k;
            $iter = $v;
        };
    };
    
    if ( $justMode==0 ) 
    {
        return $mostcommon;
    } 
    else 
    {
        return array("mode" => $mostcommon, "count" => $iter);
    }
}

function calculateBlockAmnt()
{    
//get the most common block number of the array by calling 
//the function "array_mode" 
    $block1price=0.0;
    $block2price=0.0;
    $na = func_num_args();
    $blocktypes = func_get_args();
    
    $blocktype = $blocktypes[0];
    $blocks1 = array_mode($blocktype);
    
    $q = "SELECT amount ".
         "FROM blockinsurance ".
         "WHERE insurancenumber = '{$_SESSION['insNum']}' ".
         "AND blocknumber = {$blocks1}";
    $r = mysql_query($q);
    

    if (mysql_num_rows($r)==1)
    {
        $b = mysql_fetch_row($r);
        $block1price=$b[0];
    }

    if ($na==2)
    {  
        $blocktype2 = $blocktypes[1];
        $blocks2 = array_mode($blocktype2);

        $q = "SELECT amount ".
             "FROM blockinsurance ".
             "WHERE insurancenumber = '{$_SESSION['insNum']}' ".
             "AND blocknumber = {$blocks2}";
        $r = mysql_query($q);
        if (mysql_num_rows($r)==1)
        {
            $b = mysql_fetch_row($r);
            $block2price=$b[0];
        }
    }
    $_SESSION['blockprice'] = $block1price +  $block2price;
    return $block1price +  $block2price;
}


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
   /*
    * If there is no CPT match or for some reason there are no
    * base units assigned, then the program goes back to "choose1.php"
    */
   if (!isset($_SESSION['baseUnits'])||($_SESSION['baseUnits']===0))
   {
       include ('choose1.php');
   }
   
   /*
    * Otherwise, the program comes here and calculates the appropriate fee.
    */
   else
   {
   $_SESSION['block'] = false;
   echo '<TITLE>Fee Calculation</TITLE>';
   require_once ('includes/connect2.php');
   include ('includes/header.php');
   
   $blocktotal = 0.0;
   $block = 0;
   
   
   
// Get type of insurance
// $_SESSION['insNum']    - insurance number
// $_SESSION['insurance'] - insurance text description
   $_SESSION['insNum'] = $_POST['ins'];
   
   $q = "SELECT insurance ".
        "FROM insurance ".
        "WHERE insnumber = {$_SESSION['insNum']}";
   $r = mysql_query($q);
   $a = mysql_fetch_row($r);
   $_SESSION['insurance']=$a[0];
   
   $opTime[0] = 0;
   
   
    $block = 0;
    $block2 = 0;

    
// Get information about a particular procedure (cpt) 
// for a surgeon and location
// This information now includes whether a block is involved
   $q = "SELECT * ".
        "FROM procedures ".
        "WHERE surgeonID  = '{$_SESSION['surgeonID']}' ".
        "AND cpt LIKE  '{$_SESSION['cpt']}' ".
        "AND location LIKE '{$_SESSION['location']}'";

   $r = mysql_query($q);



// If the query returns more than 5 results, base display information on
// the above particulars.
   if (mysql_num_rows($r)>4)
   {
       $_SESSION['rows'] = mysql_num_rows($r);
       $index = 0;
       
       
       while($row = mysql_fetch_array($r))
       {
           
//$opTime is an array with all the op times.
           $opTime[$index] = $row["time"];

//checking to see if the 3 most recent cases involved blocks
//If so, then $block gets incremented.  If 2 or 3 out of 3 had
//blocks, then this gets added to the price
//The variable $blocktype gets the type of block most recently used.
           if ($index > $_SESSION['rows']-4 && $row["block"] > 0)
           {
               $blocktype[$block] = $row["block"];
               $block++;               
           }
           
//see if the most recent 3 cases involved more than one block
//if so, $block2 gets incremented as above
           if ($index > $_SESSION['rows']-4 && $row["block2"] > 0)
           {
               $blocktype2[$block2] = $row["block2"];
               $block2++;
           }
           
           $index++;         
       }
       

// sort the operating room times and then pick the time that is 3/4'ths
// longest
       sort($opTime);

       $_SESSION['minutes'] = $opTime[$index*3/4];
       $_SESSION['reliability'] = 1;
       
       
       
//deal with the block situation
       
       if ($block > 1 && $block2 > 1)
       {
            $blocktotal = calculateBlockAmnt($blocktype, $blocktype2);
            $_SESSION['block'] = true;
       }
       else if ($block > 1)
       {
           $blocktotal = calculateBlockAmnt($blocktype);
           $_SESSION['block'] = true;
       }
   }
   
   
   

// This means 5 results were not obtained for the facility, surgeon, cpt
   else
   {
       /*
        * same as above except forget confining to the specific facility
        */
       $q = "SELECT * ".
            "FROM procedures ".
            "WHERE surgeonID  = '{$_SESSION['surgeonID']}' ".
            "AND cpt LIKE  '{$_SESSION['cpt']}'";
       $r = mysql_query($q);
       if (mysql_num_rows($r)>5)
       {
            $_SESSION['rows'] = mysql_num_rows($r);
            $index = 0;
            while($row = mysql_fetch_array($r))
            {
                
                $opTime[$index] = $row['time'];
                
                if ($index > $_SESSION['rows']-4 && $row[7] > 0)
                {
                    $block++;
                    $blocktype = $row[8];
                }
                
                $index++;
            }
            
            sort($opTime);
            $_SESSION['minutes'] = $opTime[$index*3/4];
           
            $_SESSION['reliability'] = 2;
 
            
//checking to see if the 3 most recent cases involved blocks
//If so, then $block gets incremented.  If 2 or 3 out of 3 had
//blocks, then this gets added to the price
//The variable $blocktype gets the type of block most recently used.
           if ($index > $_SESSION['rows']-4 && $row["block"] > 0)
           {
               $blocktype[$block] = $row["block"];
               $block++;               
           }
           
//see if the most recent 3 cases involved more than one block
//if so, $block2 gets incremented as above
           if ($index > $_SESSION['rows']-4 && $row["block2"] > 0)
           {
               $blocktype2[$block2] = $row["block2"];
               $block2++;
           }
           
//deal with the block situation
           if ($block > 1 && $block2 > 1)
           {
                $blocktotal = calculateBlockAmnt($block, $block2);
           }
           else if ($block > 1)
           {
               $blocktotal = calculateBlockAmnt($block);
           }
       }
       else
       {
           /*
            * same as above but forget the particular surgeon and facility
            */
           $q = "SELECT * ".
                "FROM procedures ".
                "WHERE cpt LIKE  '{$_SESSION['cpt']}'";  
           $r = mysql_query($q);

           if (mysql_num_rows($r)>5)
           {
                $_SESSION['rows'] = mysql_num_rows($r);
                $index = 0;
                while($row = mysql_fetch_array($r))
                {
                    $opTime[$index] = $row['time'];

                    if ($index > $_SESSION['rows']-4 && $row[7] > 0)
                    {
                        $block++;
                        $blocktype = $row[8];
                    }

                    $index++;
                }

                sort($opTime);
                $_SESSION['minutes'] = $opTime[$index*3/4];
                $_SESSION['reliability'] = 3;

                //checking to see if the 3 most recent cases involved blocks
//If so, then $block gets incremented.  If 2 or 3 out of 3 had
//blocks, then this gets added to the price
//The variable $blocktype gets the type of block most recently used.
                if ($index > $_SESSION['rows']-4 && $row["block"] > 0)
                {
                   $blocktype[$block] = $row["block"];
                   $block++;               
                }

//see if the most recent 4 cases involved more than one block
//if so, $block2 gets incremented as above
               if ($index > $_SESSION['rows']-4 && $row["block2"] > 0)
               {
                   $blocktype2[$block2] = $row["block2"];
                   $block2++;
               }

//deal with the block situation
               if ($block > 1 && $block2 > 1)
               {
                    $blocktotal = calculateBlockAmnt($blocktype, $blocktype2);
               }
               else if ($block > 1)
               {
                   $blocktotal = calculateBlockAmnt($blocktype);
               }                
           }
           else if (mysql_num_rows($r)>0)
           {
               $_SESSION['rows'] = mysql_num_rows($r);
                $index = 0;
                while($row = mysql_fetch_array($r))
                {
                    $opTime[$index] = $row['time'];
                    $index++;
                }

                $s = array_sum($opTime);
                $_SESSION['minutes'] = $s/mysql_num_rows($r);
                $_SESSION['reliability'] = 4;
           }
       }       
   }
   
   
   if ($opTime[0]!==0)
   {
       $q = "SELECT *
             FROM insurance
             WHERE insnumber = {$_SESSION['insNum']}";

       $r = mysql_query($q);
       $a = mysql_fetch_row($r);
       $baseUFee    = $a[1];
       $timeUFee    = $a[2];
       $minUFee     = $a[3];
       $fraction    = $a[4];
       
       if ($_SESSION['baseUnits']==0)
       {
           $_SESSION['reliability']=0;
       }
       else
       {
        $baseUnits = $_SESSION['baseUnits'];

        $f = (string)(
                       (
                            $baseUnits*$baseUFee 
                            + 
                            (int)($_SESSION['minutes']/15) * $timeUFee 
                            + 
                            $minUFee * $_SESSION['minutes']
                        )
                        * $fraction + $blocktotal
                      );
       }
       $df = (string)($f * $_SESSION['discount']);
       
       echo '<table align="center" class="content" 
                width=%100 bordercolor="#000000">
             <tr>
            <td width=14% align="right">Surgeon:
                </td>
                <td width=2%>
                </td>
                <td width=84% align="left">'.$_SESSION['surgeon'].'
                </td>
            </tr>
            <tr><td></td></tr>
            <tr>
            <td width=14% align="right">Procedure CPT:
                </td>
                <td width=2%>
                </td>
                <td width=84% align="left">'.$_SESSION['cpt'].'
                </td>
            </tr>
            
            <tr>
            <td width=14% align="right">CPT Description:
                </td>
                <td width=2%>
                </td>
                <td width=84% align="left">'.$_SESSION['cptDescriptor'].'
                </td>
            </tr>
            
            <tr><td></td></tr>
            <tr>
            <td width=14% align="right">Procedure ASA:
                </td>
                <td width=2%>
                </td>
                <td width=84% align="left">'.$_SESSION['asa'].'
                </td>
            </tr>
            <tr>
            <td width=14% align="right">ASA Description:
                </td>
                <td width=2%>
                </td>
                <td width=84% align="left">'.$_SESSION['asaDescription'].'
                </td>
            </tr>
            
            <tr><td></td></tr>
            <tr>
            <td width=14% align="right">Insurance:
                </td>
                <td width=2%>
                </td>
                <td width=84% align="left">'.$_SESSION['insurance'].'
                </td>
            </tr>
            <tr>
            <td width=14% align="right">Location:
                </td>
                <td width=2%>
                </td>
                <td width=84% align="left">'.$_SESSION['location'].'
                </td>
            </tr>
            <tr height=50><td></td>
            </tr>
            <tr>
            </table>
            
            <table width=100% bgcolor="#E5E5E5">
                <tr>';

            $findme   = '.';
            $pos = strpos($f, $findme);
            $dpos = strpos($df, $findme);
            $lgth = strlen($f);
            $dlgth = strlen($df);
            
            
            if ($_SESSION['insurance']!='PATIENT')
            {
                if ($pos == false) 
                {
                    echo'       <th align="center" width=50%>FEE: $'.$f.'.00
                                </th>';
                    if ($_SESSION['block'] == true && $_SESSION['blockprice']>0)
                    {
                        echo'   <th align="center">Block Price Included</th>';
                    }
                    else if ($_SESSION['block'] == true)
                    {
                        echo'   <th align="center" width=50%>There is a regional block associated with this procedure,<br> but the price is not included.</th>';
                    }
                    echo'
                            </tr>';
                }
                else
                {
                    if (($lgth-$pos) == 2)
                    {
                        echo'       <th align="center">FEE: $'.$f.'0
                                    </th>';
                        if ($_SESSION['block'] == true && $_SESSION['blockprice']>0)
                        {
                            echo'   <th align="center" width=50%>Block Price Included</th>';
                        }
                        else if ($_SESSION['block'] == true)
                        {
                            echo'   <th align="center" width=50%>There is a regional block associated with this procedure,<br> but the price is not included.</th>';
                        }
                        echo'
                                    </tr>';
                    }
                    else if (($lgth-$pos) == 3)
                    {
                        echo'       <th align="center"  width=50%>FEE: $'.$f.'
                                    </th>';
                        if ($_SESSION['block'] == true && $_SESSION['blockprice']>0)
                        {
                            echo'   <th align="center" width=50%>Block Price Included</th>';
                        }
                        else if ($_SESSION['block'] == true)
                        {
                            echo'   <th align="center" width=50%>There is a regional block associated with this procedure,<br> but the price is not included.</th>';
                        }
                        echo'
                                    </tr>';
                    }
                    else
                    {
                        echo'       <th align="center">FEE: $'.number_format($f, 2).'
                                    </th>';
                        if ($_SESSION['block'] == true && $_SESSION['blockprice']>0)
                        {
                            echo'   <th align="center" width=50%>Block Price Included</th>';
                        }
                        else if ($_SESSION['block'] == true)
                        {
                            echo'   <th align="center" width=50%>There is a regional block associated with this procedure,<br> but the price is not included.</th>';
                        }
                        echo'
                                </tr>';
                    }
                }
            }
            else
            {
                if ($pos == false) 
                {
                    echo'       <th align="center">FEE: $'.$f.'.00
                                </th>';
                    if ($_SESSION['block'] == true && $_SESSION['blockprice']>0)
                    {
                        echo'   <th align="center" width=50%>Block Price Included</th>';
                    }
                    else if ($_SESSION['block'] == true)
                    {
                        echo'   <th align="center" width=50%>There is a regional block associated with this procedure,<br> but the price is not included.</th>';
                    }
                    echo'
                            </tr>';
                }
                else
                {
                    if (($lgth-$pos) == 2)
                    {
                        echo'       <th align="center" width=50%>FEE: $'.$f.'0
                                    </th>';
                        if ($_SESSION['block'] == true && $_SESSION['blockprice']>0)
                        {
                            echo'   <th align="center" width=50%>Block Price Included</th>';
                        }
                        else if ($_SESSION['block'] == true)
                        {
                            echo'   <th align="center" width=50%>There is a regional block associated with this procedure,<br> but the price is not included.</th>';
                        }
                        echo'
                                </tr>';
                    }
                    else if (($lgth-$pos) == 3)
                    {
                        echo'       <th align="center" width=50%>FEE: $'.$f.'
                                    </th>';
                        if ($_SESSION['block'] == true && $_SESSION['blockprice']>0)
                        {
                            echo'   <th align="center" width=50%>Block Price Included</th>';
                        }
                        else if ($_SESSION['block'] == true)
                        {
                            echo'   <th align="center" width=50%>There is a regional block associated with this procedure,<br> but the price is not included.</th>';
                        }
                        echo'
                                </tr>';
                    }
                    else
                    {
                        echo'       <th align="center" width=50%>FEE: $'.number_format($f, 2).'
                                    </th>';
                        if ($_SESSION['block'] == true && $_SESSION['blockprice']>0)
                        {
                            echo'   <th align="center" width=50%>Block Price Included</th>';
                        }
                        else if ($_SESSION['block'] == true)
                        {
                            echo'   <th align="center" width=50%>There is a regional block associated with this procedure,<br> but the price is not included.</th>';
                        }
                        echo'
                                </tr>';
                    }
                }
                if ($dpos == false) 
                {
                    echo'   <tr><td></td></tr>      
                                <th align="center">
                         DISCOUNTED FEE ('.$_SESSION['discount'].'): $'.$df.'.00
                                </th>';
                    if ($_SESSION['block'] == true && $_SESSION['blockprice']>0)
                    {
                        echo'   <th align="center" width=50%>Block Price Included</th>';
                    }
                    else if ($_SESSION['block'] == true)
                    {
                        echo'   <th align="center" width=50%>There is a regional block associated with this procedure,<br> but the price is not included.</th>';
                    }
                    echo'
                            </tr>';
                }
                else
                {
                    if (($dlgth-$dpos) == 2)
                    {
                        echo'   <tr><td></td></tr>
                                    <th align="center" width=50%>
                          DISCOUNTED FEE ('.$_SESSION['discount'].'): $'.$df.'0
                                    </th>';
                        if ($_SESSION['block'] == true && $_SESSION['blockprice']>0)
                        {
                            echo'   <th align="center" width=50%>Block Price Included</th>';
                        }
                        else if ($_SESSION['block'] == true)
                        {
                            echo'   <th align="center" width=50%>There is a regional block associated with this procedure,<br> but the price is not included.</th>';
                        }
                        echo'
                                </tr>';
                    }
                    else if (($dlgth-$dpos) == 3)
                    {
                        echo'   <tr><td></td></tr>
                                    <th align="center">
                           DISCOUNTED FEE ('.$_SESSION['discount'].'): $'.$df.'
                                    </th>';
                        if ($_SESSION['block'] == true && $_SESSION['blockprice']>0)
                        {
                            echo'   <th align="center" width=50%>Block Price Included</th>';
                        }
                        else if ($_SESSION['block'] == true)
                        {
                            echo'   <th align="center" width=50%>There is a regional block associated with this procedure,<br> but the price is not included.</th>';
                        }
                        echo'
                                </tr>';
                    }
                    else{
                        echo'   <tr><td></td></tr>
                                    <th align="center" width=50%>
                           DISCOUNTED FEE ('.$_SESSION['discount'].'): $'.number_format($df, 2).'
                                    </th>';
                        if ($_SESSION['block'] == true && $_SESSION['blockprice']>0)
                        {
                            echo'   <th align="center" width=50%>Block Price Included</th>';
                        }
                        else if ($_SESSION['block'] == true)
                        {
                            echo'   <th align="center" width=50%>There is a regional block associated with this procedure,<br> but the price is not included.</th>';
                        }
                        echo'
                                </tr>';
                    }
                }
                
            }   
          
            
       echo '   </table>
                <br>
                <br>';
       
       
       echo '   <table width=100%>';
       
       if ($_SESSION['reliability'] == 1)
       {
       echo '   <tr>
                        <td align="center"> Data reliable for Surgeon, 
                            Location and Procedure.
                        </td>
                </tr>';
       }
       else if ($_SESSION['reliability'] == 2)
       {
       echo '   <tr>
                    <td align="center"> Data reliable for Surgeon and Procedure  
                    (But NOT location).
                    </td>
                </tr>';
       }   
       else if ($_SESSION['reliability'] == 3)
       {
       echo '   <tr>
                    <td align="center"> Data reliable for Procedure ONLY.
                    </td>
                </tr>';
       } 
       else
       {
       echo '   <tr>
                    <td align="center"> Data NOT RELIABLE.
                    </td>
                </tr>';
       }
       
       echo'
	 </table><br><br><br>';
   }
   else
   {
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
            </table>
            <table width = 100% align="center">
            <tr>
                <th align="center">There is no reliable data for this procedure.
                </th>
            </tr>';
       
       echo'
	 </table><br><br><br>';
   }
    include ('includes/startover.html');
    
    unset($_SESSION['minutes']);
    unset($_SESSION['surgeonID']);
    unset($_SESSION['surgeon']);
    unset($_SESSION['cpt']);
    unset($_SESSION['procedure']);
    unset($_SESSION['locDescription']);
    unset($_SESSION['first']);
    unset($_SESSION['last']);
    unset($_SESSION['rows']);
    unset($_SESSION['reliability']);
    unset($_SESSION['insurance']);
    unset($_SESSION['insNum']);
    unset($_SESSION['baseUnits']);
    unset($_SESSION['asa']);
    unset($_SESSION['asaDescription']);
    unset($_SESSION['location']);
    unset($_SESSION['cptDescriptor']);
    unset($_SESSION['block']);
    unset($_SESSION['blockprice']);


    include ('includes/logoutDirect.php');
    }
}
?>
