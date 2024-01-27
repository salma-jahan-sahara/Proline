<?php
//Include database configuration file
include('server.php');
$data = array();
$lvpmin =0.00; 
$lvsmin = 0.00;
$lveff = 0.00;

    //Get all state data
    $opid= $_POST['opid'];
    $opid= 'L-28';
    $i   = 0 ; // index varibale
    $pFLOOR = $_POST["pFLOOR"];
   
    $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
    $dt_day = date_format($dt, 'd-m-Y');
    $dt_time = date_format($dt, 'h:i a');
    $dt_timeNew = date_format($dt, 'h:i');
    $budat  = $dt_day;

    // get total minutes
    $starhour = date_format(new DateTime('08:00:00') , 'H');
    $currhour = date_format($dt, 'H'); 
    $currmin  = date_format($dt, 'i'); 
    $tminu = (abs($starhour - $currhour ) * 60 ) + $currmin ;

    if ( $tminu > 359) {
        $tminu = $tminu - 60 ;
    }
// end-get total minutes

if( ($_POST["opid"]) == 11 ) {
     //get floor & running line
     $query1 = "SELECT LINENO , SONO ,	BUYER , STYLE FROM outputdetails WHERE LINENO IN 
               (SELECT MNO FROM zpp_machine_mast WHERE FLOOR = '$pFLOOR') AND BUDAT = '$budat' 
               GROUP BY  LINENO , SONO ,	BUYER , STYLE  " ;
     $result1 = mysqli_query($db, $query1);
     if (mysqli_num_rows($result1)>0) {
             while($row1 = mysqli_fetch_array($result1)){
             $data[$i]["LINENO"]   = $row1["LINENO"];
             $data[$i]["SONO"]     =  $row1["SONO"];
             $data[$i]["BUYER"]    =  $row1["BUYER"];
             $data[$i]["STYLE"]    =  $row1["STYLE"];
             $i = $i + 1;
        }
} //end - get floor & running line

     //get hour wise data 
     $query2 = " SELECT k.LINENO , k.SONO , k.BUYER , k.STYLE,t.tHour ,k.MP ,t.QTY,round(k.SMV * t.QTY) as lvpmin 
                 FROM kpiview as k  INNER JOIN 
                 (SELECT LINENO , SONO , BUYER , STYLE , str_to_date( SBUDDAT, '%d/%m/%Y %H' ) AS tHour, 
                 sum(QTY) AS QTY FROM outputdetails WHERE BUDAT = '$budat' AND TYPE='FIT' 
                 AND LINENO IN (SELECT MNO FROM zpp_machine_mast WHERE FLOOR = '$pFLOOR') 
                 GROUP BY hour( str_to_date( SBUDDAT, '%d/%m/%Y %H' ) ) , LINENO , SONO , BUYER , STYLE 
                 ORDER BY LINENO , SONO , BUYER , STYLE , hour( str_to_date( SBUDDAT, '%d/%m/%Y %H' ) ) ASC ) as t 
                 ON k.LINENO = t.LINENO AND k.SONO = t.SONO AND k.BUYER = t.BUYER  AND k.STYLE = t.STYLE 
                 AND k.BUDAT = '$budat' ORDER BY k.LINENO , k.SONO , k.BUYER , k.STYLE, t.tHour" ;
     $result2 = mysqli_query($db, $query2);
     if (mysqli_num_rows($result2)>0) {
             while($row2 = mysqli_fetch_array($result2)){
                foreach ($data as $k => $v) {
                    if ( ( $v["LINENO"] == $row2["LINENO"] ) 
                         AND ( $v["SONO"] == $row2["SONO"] ) 
                         AND ( $v["STYLE"] == $row2["STYLE"])) {
                         $lvhour = substr(substr($row2["tHour"],11),0,-6);
                         switch ($lvhour) {
                             case "08": 
                                $lvpmin =0.00; $lvsmin = 0.00;$lveff = 0.00;
                                $lvpmin = $row2["lvpmin"];
                                $lvsmin = 60 * ($row2["MP"]);
                                $lveff  = round((( $lvpmin / $lvsmin ) * 100 ));
                                $data[$k]["A"] = $lveff ; 
                                break;
                             case "09": 
                                $lvpmin =0.00; $lvsmin = 0.00;$lveff = 0.00;
                                $lvpmin = $row2["lvpmin"];
                                $lvsmin = 60 * ($row2["MP"]);
                                $lveff  = round((( $lvpmin / $lvsmin ) * 100 ));
                                $data[$k]["B"] = $lveff ; 
                                break;
                             case "10": 
                                $lvpmin =0.00; $lvsmin = 0.00;$lveff = 0.00;
                                $lvpmin = $row2["lvpmin"];
                                $lvsmin = 60 * ($row2["MP"]);
                                $lveff  = round((( $lvpmin / $lvsmin ) * 100 ));
                                $data[$k]["C"] = $lveff ; 
                                break;
                             case "11": 
                                $lvpmin =0.00; $lvsmin = 0.00;$lveff = 0.00;
                                $lvpmin = $row2["lvpmin"];
                                $lvsmin = 60 * ($row2["MP"]);
                                $lveff  = round((( $lvpmin / $lvsmin ) * 100 ));
                                $data[$k]["D"] = $lveff ; 
                                break;
                             case "12": 
                                $lvpmin =0.00; $lvsmin = 0.00;$lveff = 0.00;
                                $lvpmin = $row2["lvpmin"];
                                $lvsmin = 60 * ($row2["MP"]);
                                $lveff  = round((( $lvpmin / $lvsmin ) * 100 ));
                                $data[$k]["E"] = $lveff ; 
                                break;
                             case "13": 
                                $lvpmin =0.00; $lvsmin = 0.00;$lveff = 0.00;
                                $lvpmin = $row2["lvpmin"];
                                $lvsmin = 60 * ($row2["MP"]);
                                $lveff  = round((( $lvpmin / $lvsmin ) * 100 ));
                                $data[$k]["F"] = $lveff ; 
                                break;
                             case "14": 
                                 $lvpmin =0.00; $lvsmin = 0.00;$lveff = 0.00;
                                 $lvpmin = $row2["lvpmin"];
                                 $lvsmin = 60 * ($row2["MP"]);
                                 $lveff  = round((( $lvpmin / $lvsmin ) * 100 ));
                                 $data[$k]["EXT"] = $lveff ; 
                                 break;
                             case "15": 
                                $lvpmin =0.00; $lvsmin = 0.00;$lveff = 0.00;
                                $lvpmin = $row2["lvpmin"];
                                $lvsmin = 60 * ($row2["MP"]);
                                $lveff  = round((( $lvpmin / $lvsmin ) * 100 ));
                                $data[$k]["G"] = $lveff ; 
                                break;
                             case "16": 
                                $lvpmin =0.00; $lvsmin = 0.00;$lveff = 0.00;
                                $lvpmin = $row2["lvpmin"];
                                $lvsmin = 60 * ($row2["MP"]);
                                $lveff  = round((( $lvpmin / $lvsmin ) * 100 ));
                                $data[$k]["H"] = $lveff ; 
                                break;
                             case "17": 
                                $lvpmin =0.00; $lvsmin = 0.00;$lveff = 0.00;
                                $lvpmin = $row2["lvpmin"];
                                $lvsmin = 60 * ($row2["MP"]);
                                $lveff  = round((( $lvpmin / $lvsmin ) * 100 ));
                                $data[$k]["I"] = $lveff ; 
                                break;  
                             case "18": 
                                $lvpmin =0.00; $lvsmin = 0.00;$lveff = 0.00;
                                $lvpmin = $row2["lvpmin"];
                                $lvsmin = 60 * ($row2["MP"]);
                                $lveff  = round((( $lvpmin / $lvsmin ) * 100 ));
                                $data[$k]["J"] = $lveff ; 
                                break;
                             case "19": 
                                $lvpmin =0.00; $lvsmin = 0.00;$lveff = 0.00;
                                $lvpmin = $row2["lvpmin"];
                                $lvsmin = 60 * ($row2["MP"]);
                                $lveff  = round((( $lvpmin / $lvsmin ) * 100 ));
                                $data[$k]["K"] = $lveff ; 
                                break;
                             case "20": 
                                $lvpmin =0.00; $lvsmin = 0.00;$lveff = 0.00;
                                $lvpmin = $row2["lvpmin"];
                                $lvsmin = 60 * ($row2["MP"]);
                                $lveff  = round((( $lvpmin / $lvsmin ) * 100 ));
                                $data[$k]["L"] = $lveff ; 
                                break;
                             case "21": 
                                $lvpmin =0.00; $lvsmin = 0.00;$lveff = 0.00;
                                $lvpmin = $row2["lvpmin"];
                                $lvsmin = 60 * ($row2["MP"]);
                                $lveff  = round((( $lvpmin / $lvsmin ) * 100 ));
                                $data[$k]["M"] = $lveff ; 
                                break;                                    
                             default:
                         } 
                    }
                }
        }
} //end - get hour wise data 

     //get given efficicency
     $query3 = "SELECT k.LINENO,k.SONO,k.BUYER,k.STYLE,k.EFF AS QTY FROM kpiview as k
                INNER JOIN (SELECT LINENO , SONO ,	BUYER , STYLE FROM outputdetails WHERE LINENO IN 
                (SELECT MNO FROM zpp_machine_mast WHERE FLOOR = '$pFLOOR') AND BUDAT = '$budat' 
                GROUP BY  LINENO , SONO ,BUYER , STYLE) as t on 
                k.LINENO = t.LINENO AND k.SONO = t.SONO AND k.BUYER = t.BUYER 
                AND k.STYLE = t.STYLE AND k.BUDAT = '$budat' " ;
     $result3 = mysqli_query($db, $query3);
     if (mysqli_num_rows($result3)>0) {
             while($row3 = mysqli_fetch_array($result3)){
                foreach ($data as $k => $v) {
                if ( ( $v["LINENO"] == $row3["LINENO"] ) 
                AND ( $v["SONO"] == $row3["SONO"] ) 
                AND ( $v["STYLE"] == $row3["STYLE"])) {
                    $data[$k]["EFF"] = $row3["QTY"] ;
                }
            }
        }
} //end - given efficicency


     //get total efficiency 
     $query4 = "SELECT k.LINENO , k.SONO , k.BUYER , k.STYLE , t.QTY , k.SPHOUR , k.MP , 
               (t.QTY * k.SMV ) as lvpmin,
               ((k.SPHOUR / k.MP ) * 60 ) as pTrend FROM kpiview as k 
               INNER JOIN 
               (SELECT LINENO , SONO , BUYER , STYLE , SUM(QTY) AS QTY  FROM `outputdetails` 
               WHERE BUDAT = '$budat' AND TYPE = 'FIT' GROUP BY LINENO , SONO , BUYER , STYLE) as t 
               ON k.LINENO = t.LINENO AND k.SONO = t.SONO AND k.BUYER = t.BUYER AND k.STYLE = t.STYLE 
               AND k.BUDAT = '$budat'  " ;
     $result4 = mysqli_query($db, $query4);
     if (mysqli_num_rows($result4)>0) {
             while($row4 = mysqli_fetch_array($result4)){
                foreach ($data as $k => $v) {
                if ( ( $v["LINENO"] == $row4["LINENO"] ) 
                AND ( $v["SONO"] == $row4["SONO"] ) 
                AND ( $v["STYLE"] == $row4["STYLE"])) {
                    $lvsm = $row4["MP"] * $tminu ;
                    $data[$k]["TOTAL"] = round (( $row4["lvpmin"] / $lvsm ) * 100);
                }
            }
        }
} //end - get total efficiency 


$ik = 0 ; 
foreach($data as $k => $v) {
    if(array_key_exists("A",$v) == false ){ $data[$k]["A"] = 0 ;$ik = $ik + 1 ;} 
    if(array_key_exists("B",$v) == false ){ $data[$k]["B"] = 0 ;$ik = $ik + 1 ;} 
    if(array_key_exists("C",$v) == false ){ $data[$k]["C"] = 0 ;$ik = $ik + 1 ;} 
    if(array_key_exists("D",$v) == false ){ $data[$k]["D"] = 0 ;$ik = $ik + 1 ;} 
    if(array_key_exists("E",$v) == false ){ $data[$k]["E"] = 0 ;$ik = $ik + 1 ;} 
    if(array_key_exists("F",$v) == false ){ $data[$k]["F"] = 0 ;$ik = $ik + 1 ;} 
    if(array_key_exists("G",$v) == false ){ $data[$k]["G"] = 0 ;$ik = $ik + 1 ;} 
    if(array_key_exists("H",$v) == false ){ $data[$k]["H"] = 0 ;$ik = $ik + 1 ;} 
    if(array_key_exists("I",$v) == false ){ $data[$k]["I"] = 0 ;$ik = $ik + 1 ;} 
    if(array_key_exists("J",$v) == false ){ $data[$k]["J"] = 0 ;$ik = $ik + 1 ;} 
    if(array_key_exists("K",$v) == false ){ $data[$k]["K"] = 0 ;$ik = $ik + 1 ;} 
    if(array_key_exists("L",$v) == false ){ $data[$k]["L"] = 0 ;$ik = $ik + 1 ;} 
    if(array_key_exists("M",$v) == false ){ $data[$k]["M"] = 0 ;$ik = $ik + 1 ;} 
    if(array_key_exists("EXT",$v) == false ){ $data[$k]["EXT"] = 0 ;$ik = $ik + 1 ;} 
    if(array_key_exists("TOTAL",$v) == false ){ $data[$k]["TOTAL"] = 0 ; } 
    $data[$k]["F"] = $data[$k]["F"] + $data[$k]["EXT"];
   //  $data[$k]["TOTAL"] = round((( $data[$k]["A"] +  $data[$k]["B"] +  $data[$k]["C"] + 
   //                        $data[$k]["D"] +  $data[$k]["E"] +  $data[$k]["F"] + 
   //                        $data[$k]["G"] +  $data[$k]["H"] +  $data[$k]["I"] + 
   //                        $data[$k]["J"] +  $data[$k]["K"] +  $data[$k]["L"] + 
   //                        $data[$k]["M"] ) / (14-$ik)));
    if(array_key_exists("EFF",$v) == false ){ $data[$k]["EFF"] = 0 ;} 
}
foreach($data as $dat) {
    ?>
        <tr class="itemD" id="<?php echo ($dat["LINENO"]) ?>">
            <td class="LINENO"><?php echo ($dat["LINENO"]) ?></td>
            <td class="SONO" style="display:none;"><?php echo ($dat["SONO"]) ?></td>
            <td class="BUYER"><?php echo ($dat["BUYER"])  ?></td>
            <td class="STYLE"><?php echo ($dat["STYLE"])  ?></td>
            <td class="STYLE"><?php echo ($dat["EFF"])  ?></td>
            <td class="STYLE"><?php echo ($dat["A"])  ?></td>
            <td class="STYLE"><?php echo ($dat["B"])  ?></td>
            <td class="STYLE"><?php echo ($dat["C"])  ?></td>
            <td class="STYLE"><?php echo ($dat["D"])  ?></td>
            <td class="STYLE"><?php echo ($dat["E"])  ?></td>
            <td class="STYLE"><?php echo ($dat["F"])  ?></td>
            <td class="STYLE"><?php echo ($dat["G"])  ?></td>
            <td class="STYLE"><?php echo ($dat["H"])  ?></td>
            <td class="STYLE"><?php echo ($dat["I"])  ?></td>
            <td class="STYLE"><?php echo ($dat["J"])  ?></td>
            <td class="STYLE"><?php echo ($dat["K"])  ?></td>
            <td class="STYLE"><?php echo ($dat["L"])  ?></td>
            <td class="STYLE"><?php echo ($dat["M"])  ?></td>
            <td class="STYLE"><?php echo ($dat["TOTAL"])  ?></td>
       </tr>
    <?php } ?>
<?php
} // end -if( ($_POST["opid"]) == 11 ) 

//-----------------------------------------------------------------------------------------------------------
if( ($_POST["opid"]) == 31 ) {
       
       $pLINENO= $_POST['pLINENO'];
       $pBUYER= $_POST['pBUYER'];
       $pSTYLE= $_POST['pSTYLE'];
       $k = 1 ;

       //get line ,buyer , style ,date , SMV ,MP 
         $query1A = "SELECT DISTINCT  BUDAT , LINENO ,  BUYER , STYLE  , SMV , MP 
                     FROM `kpiview` WHERE STR_TO_DATE(BUDAT,'%d-%m-%Y') 
                     BETWEEN STR_TO_DATE('04-03-2020','%d-%m-%Y') AND STR_TO_DATE('$budat','%d-%m-%Y')
                     AND LINENO = '$pLINENO' AND BUYER = '$pBUYER' AND STYLE = '$pSTYLE' 
                     GROUP BY BUDAT , LINENO ,  BUYER , STYLE
                     ORDER BY BUDAT DESC " ;
         $result1A = mysqli_query($db, $query1A);
         if (mysqli_num_rows($result1A)>0) {
            while($row1A = mysqli_fetch_array($result1A)) {
               $data[$k]["LINENO"]   =  $row1A["LINENO"];
               $data[$k]["BUYER"]    =  $row1A["BUYER"];
               $data[$k]["STYLE"]    =  $row1A["STYLE"];
               $data[$k]["BUDAT"]    =  $row1A["BUDAT"];
               $data[$k]["SMV"]       = $row1A["SMV"];
               $data[$k]["MP"]       =  $row1A["MP"];
               $k = $k + 1;
             }
         }
       //end - get line ,buyer , style ,date , SMV ,MP 


        //get hour wise data with below 30 min hour work
        $query2 = "SELECT LINENO ,  BUYER , STYLE , BUDAT, str_to_date( SBUDDAT, '%d/%m/%Y %H' ) AS tHour, 
                     SUM(QTY) AS QTY FROM outputdetails  
                     WHERE STR_TO_DATE(BUDAT,'%d-%m-%Y') 
                     BETWEEN STR_TO_DATE('04-03-2020','%d-%m-%Y') AND STR_TO_DATE('$budat','%d-%m-%Y') 
                     AND TYPE='FIT' AND LINENO = '$pLINENO' AND BUYER = '$pBUYER' AND STYLE ='$pSTYLE'
                     GROUP BY hour( str_to_date( SBUDDAT, '%d/%m/%Y %H' ) ) ,BUDAT , LINENO ,  BUYER , STYLE 
                     ORDER BY LINENO , BUYER , STYLE , BUDAT , hour( str_to_date( SBUDDAT, '%d/%m/%Y %H' ) ) ASC  " ;
        $result2 = mysqli_query($db, $query2);
         if (mysqli_num_rows($result2)>0) {
            while($row2 = mysqli_fetch_array($result2)){
               foreach ($data as $k => $v) {
                  if ( ( $v["LINENO"] == $row2["LINENO"] ) 
                       AND ( $v["BUYER"] == $row2["BUYER"] ) 
                       AND ( $v["STYLE"] == $row2["STYLE"])
                       AND ( $v["BUDAT"] == $row2["BUDAT"])) {
                        $lvhour = substr(substr($row2["tHour"],11),0,-6);
                        $lveff = $row2["QTY"] ; 
                        switch ($lvhour) {
                           case "08": $data[$k]["A"] = $lveff ; break;
                           case "09": $data[$k]["B"] = $lveff ; break;
                           case "10": $data[$k]["C"] = $lveff ; break;
                           case "11": $data[$k]["D"] = $lveff ; break;
                           case "12": $data[$k]["E"] = $lveff ; break;
                           case "13": $data[$k]["F"] = $lveff ; break;
                           case "14": $data[$k]["EXT"] = $lveff; break;
                           case "15": $data[$k]["G"] = $lveff ; break;
                           case "16": $data[$k]["H"] = $lveff ; break;
                           case "17": $data[$k]["I"] = $lveff ; break;  
                           case "18": $data[$k]["J"] = $lveff ; break;
                           case "19": $data[$k]["K"] = $lveff ; break;
                           case "20": $data[$k]["L"] = $lveff ; break;
                           case "21": $data[$k]["M"] = $lveff ; break;                            
                           default:
                  } 
               }
            }
         }
      } //end - get hour wise data with below 30 min hour work

              //get below 30-min-hour & add qty with previous hour 
              $query2A = "SELECT LINENO , BUYER , STYLE , BUDAT , tTime , tHour , tmin FROM 
                           (SELECT LINENO , BUYER , STYLE , BUDAT , MAX(str_to_date( SBUDDAT, '%d/%m/%Y %T' )) AS tTime ,
                           SUBSTRING(MAX(str_to_date( SBUDDAT, '%d/%m/%Y %T' )), 12,2) AS tHour,
                           SUBSTRING(MAX(str_to_date( SBUDDAT, '%d/%m/%Y %T' )), 15,2) AS tmin
                           FROM outputdetails  WHERE STR_TO_DATE(BUDAT,'%d-%m-%Y') BETWEEN 
                           STR_TO_DATE('04-03-2020','%d-%m-%Y') AND STR_TO_DATE('$budat','%d-%m-%Y')
                           AND TYPE='FIT' AND LINENO = '$pLINENO' AND BUYER = '$pBUYER' AND STYLE ='$pSTYLE'
                           GROUP BY  LINENO , BUYER , STYLE , BUDAT DESC) t WHERE tmin !=0 AND tmin<=30  " ;
               $result2A = mysqli_query($db, $query2A);
               $resultMIN = mysqli_query($db, $query2A); // for total min calculation
               if (mysqli_num_rows($result2A)>0) {
                  while($row2A = mysqli_fetch_array($result2A)){
                     foreach ($data as $k => $v) {
                        if ( ( $v["LINENO"] == $row2A["LINENO"] ) 
                              AND ( $v["BUYER"] == $row2A["BUYER"] ) 
                              AND ( $v["STYLE"] == $row2A["STYLE"])
                              AND ( $v["BUDAT"] == $row2A["BUDAT"])) {
                              $lvhour = $row2A["tHour"] ; 
                              $lvhour1  = $row2A["tHour"] ;
                              $lvhour = round($lvhour) - 1 ;
                              switch ($lvhour) {
                                 //adding qty 30 min hour data with previous hour
                                 case "08": $data[$k]["A"] = $data[$k]["A"] + $data[$k]["B"] ;  break;
                                 case "09": $data[$k]["B"] = $data[$k]["B"] + $data[$k]["C"] ;  break;
                                 case "10": $data[$k]["C"] = $data[$k]["C"] + $data[$k]["D"] ;  break;
                                 case "11": $data[$k]["D"] = $data[$k]["D"] + $data[$k]["E"] ;  break;
                                 case "12": $data[$k]["E"] = $data[$k]["E"] + $data[$k]["F"] ;  break;
                                 case "13": $data[$k]["F"] = $data[$k]["F"] + $data[$k]["EXT"]; break;
                                 case "14": $data[$k]["EXT"] = $data[$k]["EXT"] + $data[$k]["G"];  break;
                                 case "15": $data[$k]["G"] = $data[$k]["G"] + $data[$k]["H"] ;  break;
                                 case "16": $data[$k]["H"] = $data[$k]["H"] + $data[$k]["I"] ;  break;
                                 case "17": $data[$k]["I"] = $data[$k]["I"] + $data[$k]["J"] ;  break;  
                                 case "18": $data[$k]["J"] = $data[$k]["J"] + $data[$k]["K"] ;  break;
                                 case "19": $data[$k]["K"] = $data[$k]["K"] + $data[$k]["L"] ;  break;
                                 case "20": $data[$k]["L"] = $data[$k]["L"] + $data[$k]["M"] ;  break;
                                 case "21": $data[$k]["M"] = 0 ; break;                            
                                 default:
                              }
                              switch ($lvhour1) {
                                 //30 min hour qty 0 
                                 //case "08": $data[$k]["A"] = 0 ;     break;
                                 case "09": $data[$k]["B"] = 0 ;  break;
                                 case "10": $data[$k]["C"] = 0 ;  break;
                                 case "11": $data[$k]["D"] = 0 ;  break;
                                 case "12": $data[$k]["E"] = 0 ;  break;
                                 case "13": $data[$k]["F"] = 0 ;  break;
                                 case "14": $data[$k]["EXT"] = 0; break;
                                 case "15": $data[$k]["G"] = 0 ;  break;
                                 case "16": $data[$k]["H"] = 0 ;  break;
                                 case "17": $data[$k]["I"] = 0 ;  break;  
                                 case "18": $data[$k]["J"] = 0 ;  break;
                                 case "19": $data[$k]["K"] = 0 ;  break;
                                 case "20": $data[$k]["L"] = 0 ;  break;
                                 case "21": $data[$k]["M"] = 0 ;  break;                            
                                 default:
                              } 
                     }
                  }
               }
            } //end - get below 30-min-hour & add qty with previous hour
          // putting 0 value 
         foreach($data as $k => $v) {
            if(array_key_exists("A",$v) == false ){ $data[$k]["A"] = 0 ;} 
            if(array_key_exists("B",$v) == false ){ $data[$k]["B"] = 0 ;} 
            if(array_key_exists("C",$v) == false ){ $data[$k]["C"] = 0 ;} 
            if(array_key_exists("D",$v) == false ){ $data[$k]["D"] = 0 ;} 
            if(array_key_exists("E",$v) == false ){ $data[$k]["E"] = 0 ;} 
            if(array_key_exists("F",$v) == false ){ $data[$k]["F"] = 0 ;} 
            if(array_key_exists("G",$v) == false ){ $data[$k]["G"] = 0 ;} 
            if(array_key_exists("H",$v) == false ){ $data[$k]["H"] = 0 ;} 
            if(array_key_exists("I",$v) == false ){ $data[$k]["I"] = 0 ;} 
            if(array_key_exists("J",$v) == false ){ $data[$k]["J"] = 0 ;} 
            if(array_key_exists("K",$v) == false ){ $data[$k]["K"] = 0 ;} 
            if(array_key_exists("L",$v) == false ){ $data[$k]["L"] = 0 ;} 
            if(array_key_exists("M",$v) == false ){ $data[$k]["M"] = 0 ;} 
            if(array_key_exists("EXT",$v) == false ){ $data[$k]["EXT"] = 0 ;} 
            $data[$k]["F"] = $data[$k]["F"] + $data[$k]["EXT"];
            if(array_key_exists("EFF",$v) == false ){ $data[$k]["EFF"] = 0 ;} 
        } // end - putting 0 value 

        // final hour wise efficiency calculation
        foreach ($data as $k => $v) {
               $lvsmv = $data[$k]["SMV"] ; 
               $lvmp  = $data[$k]["MP"] ; 
               $data[$k]["A"] = round ((round($data[$k]["A"] * $lvsmv) / round(60 * $data[$k]["MP"])) * 100 ) ;
               $data[$k]["B"] = round ((round($data[$k]["B"] * $lvsmv) / round(60 * $data[$k]["MP"])) * 100 ) ;
               $data[$k]["C"] = round ((round($data[$k]["C"] * $lvsmv) / round(60 * $data[$k]["MP"])) * 100 ) ;
               $data[$k]["D"] = round ((round($data[$k]["D"] * $lvsmv) / round(60 * $data[$k]["MP"])) * 100 ) ;
               $data[$k]["E"] = round ((round($data[$k]["E"] * $lvsmv) / round(60 * $data[$k]["MP"])) * 100 ) ;
               $data[$k]["F"] = round ((round($data[$k]["F"] * $lvsmv) / round(60 * $data[$k]["MP"])) * 100 ) ;
               $data[$k]["G"] = round ((round($data[$k]["G"] * $lvsmv) / round(60 * $data[$k]["MP"])) * 100 ) ;
               $data[$k]["H"] = round ((round($data[$k]["H"] * $lvsmv) / round(60 * $data[$k]["MP"])) * 100 ) ;
               $data[$k]["I"] = round ((round($data[$k]["I"] * $lvsmv) / round(60 * $data[$k]["MP"])) * 100 ) ;
               $data[$k]["J"] = round ((round($data[$k]["J"] * $lvsmv) / round(60 * $data[$k]["MP"])) * 100 ) ;
               $data[$k]["K"] = round ((round($data[$k]["K"] * $lvsmv) / round(60 * $data[$k]["MP"])) * 100 ) ;
               $data[$k]["L"] = round ((round($data[$k]["L"] * $lvsmv) / round(60 * $data[$k]["MP"])) * 100 ) ;
               $data[$k]["M"] = round ((round($data[$k]["M"] * $lvsmv) / round(60 * $data[$k]["MP"])) * 100 ) ;
               $data[$k]["M"] = round ((round($data[$k]["M"] * $lvsmv) / round(60 * $data[$k]["MP"])) * 100 ) ;
        } // end - final hour wise efficiency calculation

        //total find
             // total find - sub part actual qty 
            $queryT1 = "SELECT LINENO , BUYER , STYLE , BUDAT, SUM(QTY) AS QTY 
                        FROM outputdetails WHERE STR_TO_DATE(BUDAT,'%d-%m-%Y') 
                        BETWEEN STR_TO_DATE('04-03-2020','%d-%m-%Y') AND STR_TO_DATE('$budat','%d-%m-%Y')
                        AND TYPE='FIT' AND LINENO = '$pLINENO' AND BUYER = '$pBUYER' AND STYLE ='$pSTYLE'
                        GROUP BY  LINENO , BUYER , STYLE , BUDAT
                        ORDER BY  LINENO , BUYER , STYLE , BUDAT DESC " ;
               $resultT1 = mysqli_query($db, $queryT1);
               if (mysqli_num_rows($resultT1)>0) {
                  while($rowT1 = mysqli_fetch_array($resultT1)){
                     foreach ($data as $k => $v) {
                        if ( ( $v["LINENO"] == $rowT1["LINENO"] ) 
                           AND ( $v["BUYER"] == $rowT1["BUYER"] ) 
                           AND ( $v["STYLE"] == $rowT1["STYLE"])
                           AND ( $v["BUDAT"] == $rowT1["BUDAT"])) {
                                 $data[$k]["AQTY"] = $rowT1["QTY"];
                           }
                        }
                     }
               }
            //end- total find - sub part actual qty
          // total find - sub part Maximum hour for given day 
         $queryT2 = " SELECT DISTINCT LINENO ,  BUYER , STYLE , BUDAT, 
                      MAX(str_to_date( SBUDDAT , '%d/%m/%Y %H' ) ) AS tHour FROM outputdetails
                      WHERE STR_TO_DATE(BUDAT,'%d-%m-%Y') BETWEEN STR_TO_DATE('04-03-2020','%d-%m-%Y') AND STR_TO_DATE('$budat','%d-%m-%Y') 
                      AND LINENO = '$pLINENO' AND BUYER = '$pBUYER' AND STYLE ='$pSTYLE'
                      GROUP BY  LINENO ,  BUYER , STYLE , BUDAT ORDER BY BUDAT DESC " ;
         $resultT2 = mysqli_query($db, $queryT2);
         if (mysqli_num_rows($resultT2)>0) {
            while($rowT2 = mysqli_fetch_array($resultT2)){
               foreach ($data as $k => $v) {
                  if ( ( $v["LINENO"] == $rowT2["LINENO"] ) 
                       AND ( $v["BUYER"] == $rowT2["BUYER"] ) 
                       AND ( $v["STYLE"] == $rowT2["STYLE"])
                       AND ( $v["BUDAT"] == $rowT2["BUDAT"])) {
                             $lvhour = substr(substr($rowT2["tHour"],11),0,-6);
                             switch ($lvhour) {
                                case "08": $data[$k]["MINU"] =  60 * 1;  break;
                                case "09": $data[$k]["MINU"] =  60 * 2;  break;
                                case "10": $data[$k]["MINU"] =  60 * 3;  break;
                                case "11": $data[$k]["MINU"] =  60 * 4;  break;
                                case "12": $data[$k]["MINU"] =  60 * 5;  break;
                                case "13": $data[$k]["MINU"] =  60 * 6;  break;
                                case "14": $data[$k]["MINU"] =  60 * 6;  break;
                                case "15": $data[$k]["MINU"] =  60 * 7;  break;
                                case "16": $data[$k]["MINU"] =  60 * 8;  break;
                                case "17": $data[$k]["MINU"] =  60 * 9;  break;
                                case "18": $data[$k]["MINU"] =  60 * 10; break;
                                case "19": $data[$k]["MINU"] =  60 * 11; break;
                                case "20": $data[$k]["MINU"] =  60 * 12; break;
                                case "21": $data[$k]["MINU"] =  60 * 13; break;                                
                                default:
                            } 
                     }
                  }
               }
         }
         //end -  total find - sub part Maximum hour for given day 

         // minus 30-min-hour from total minute - sub part Maximum hour for given day 
         if (mysqli_num_rows($resultMIN)>0) {
            while($rowMIN = mysqli_fetch_array($resultMIN)){
                  foreach ($data as $k => $v) {
                           if ( ( $v["LINENO"] == $rowMIN["LINENO"] ) 
                           AND ( $v["BUYER"] == $rowMIN["BUYER"] ) 
                           AND ( $v["STYLE"] == $rowMIN["STYLE"])
                           AND ( $v["BUDAT"] == $rowMIN["BUDAT"])){ 
                              $lvhour = $rowMIN["tHour"];
                              switch ($lvhour) {
                                 //case "08": $data[$k]["MINU"] =  60 * 1;  break;
                                 case "09": $data[$k]["MINU"] =  60 * 1;  break;
                                 case "10": $data[$k]["MINU"] =  60 * 2;  break;
                                 case "11": $data[$k]["MINU"] =  60 * 3;  break;
                                 case "12": $data[$k]["MINU"] =  60 * 4;  break;
                                 case "13": $data[$k]["MINU"] =  60 * 5;  break;
                                 case "14": $data[$k]["MINU"] =  60 * 5;  break;
                                 case "15": $data[$k]["MINU"] =  60 * 6;  break;
                                 case "16": $data[$k]["MINU"] =  60 * 7;  break;
                                 case "17": $data[$k]["MINU"] =  60 * 8;  break;
                                 case "18": $data[$k]["MINU"] =  60 * 9;  break;
                                 case "19": $data[$k]["MINU"] =  60 * 10; break;
                                 case "20": $data[$k]["MINU"] =  60 * 11; break;
                                 case "21": $data[$k]["MINU"] =  60 * 12; break;                                
                                 default:
                             } 
                         }
                  }
            }
         }
         // end - minus 30-min-hour from total minute - sub part Maximum hour for given day 
         foreach($data as $k => $v) {
            if(array_key_exists("AQTY",$v) == false ){ $data[$k]["AQTY"] = 1 ;}
            if(array_key_exists("AQTY",$v) == false ){ $data[$k]["MINU"] = 1 ;}
         }
         foreach($data as $k => $v) {
            if ($data[$k]["BUDAT"] == $budat) {
               $lvpm = round($data[$k]["AQTY"] * $data[$k]["SMV"]) ;
               $lvsm = round( $tminu * $data[$k]["MP"]) ;
               $data[$k]["TOTAL"] = round(($lvpm / $lvsm) * 100 );
               $data[$k]["MINU"]  = $tminu;
            } else {
               $lvpm = round($data[$k]["AQTY"] * $data[$k]["SMV"]) ;
               $lvsm = round($data[$k]["MINU"] * $data[$k]["MP"]) ;
               $data[$k]["TOTAL"] = round(($lvpm / $lvsm) * 100 );
            }
      }
      //end - total find

         foreach($data as $dat) {
            ?>
                <tr class="itemD" id="<?php echo ($dat["LINENO"]) ?>">
                    <td class="LINENO" style="display:none;"><?php echo ($dat["LINENO"]) ?></td>
                    <td class="BUYER"  style="display:none;"><?php echo ($dat["BUYER"])  ?></td>
                    <td class="STYLE"  style="display:none;"><?php echo ($dat["STYLE"])  ?></td>
                    <td class="STYLE" style="font-weight:bold;color:Blue;"><?php echo ($dat["BUDAT"])  ?></td>
                    <td class="STYLE" ><?php echo ($dat["A"])  ?></td>
                    <td class="STYLE" ><?php echo ($dat["B"])  ?></td>
                    <td class="STYLE" ><?php echo ($dat["C"])  ?></td>
                    <td class="STYLE" ><?php echo ($dat["D"])  ?></td>
                    <td class="STYLE" ><?php echo ($dat["E"])  ?></td>
                    <td class="STYLE" ><?php echo ($dat["F"])  ?></td>
                    <td class="STYLE" ><?php echo ($dat["G"])  ?></td>
                    <td class="STYLE" ><?php echo ($dat["H"])  ?></td>
                    <td class="STYLE" ><?php echo ($dat["I"])  ?></td>
                    <td class="STYLE" ><?php echo ($dat["J"])  ?></td>
                    <td class="STYLE" ><?php echo ($dat["K"])  ?></td>
                    <td class="STYLE" ><?php echo ($dat["L"])  ?></td>
                    <td class="STYLE" ><?php echo ($dat["M"])  ?></td>
                    <td class="STYLE" style="font-weight:bold;color:Blue;" ><?php echo ($dat["TOTAL"]) ?></td>
                    <td class="STYLE"><?php echo ($dat["AQTY"])  ?></td>
                    <td class="STYLE"><?php echo ($dat["MINU"])  ?></td>
                    <td class="STYLE"><?php echo ($dat["MP"])  ?></td>
                    <td class="STYLE"><?php echo ($dat["SMV"])  ?></td>
               </tr>
            <?php } ?>
        <?php
   } // end - if( ($_POST["opid"]) == 31 ) 

//main page end
?> 