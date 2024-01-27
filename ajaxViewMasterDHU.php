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
    $j   = 0 ; // index varibale
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


     //get Target DHU
     $query1A = " SELECT DISTINCT LINENO , DQTY FROM dhu WHERE BUDAT = '$budat'  " ;
     $result1A = mysqli_query($db, $query1A);
     if (mysqli_num_rows($result1A)>0) {
             while($row1A = mysqli_fetch_array($result1A)){
                foreach ($data as $k => $v) {
                if ( $v["LINENO"] == $row1A["LINENO"] )  {
                    $data[$k]["TDHU"] = $row1A["DQTY"] ;
                }
            }
        }
   } //end - get Target DHU

     //get hour wise data 
     $query2 = " SELECT T1.LINENO , T1.SONO , T1.BUYER , T1.STYLE , T1.AQTY , T2.FQTY , T1.tHour ,
                  ROUND(( T2.FQTY /  T1.AQTY) * 100 ,2) AS QTY FROM
                 (SELECT LINENO , SONO , BUYER , STYLE ,  str_to_date( SBUDDAT, '%d/%m/%Y %H' ) AS tHour  , 
                  SUM(QTY) AS AQTY FROM outputdetails WHERE BUDAT = '$budat'
                  AND LINENO IN (SELECT MNO FROM zpp_machine_mast WHERE FLOOR = '$pFLOOR' )
                  GROUP BY hour( str_to_date( SBUDDAT, '%d/%m/%Y %H' ) ) , LINENO , SONO , BUYER , STYLE) T1
                  INNER JOIN 
                  (SELECT T1.LINENO , T1.SONO , T1.BUYER , T1.STYLE , 
                  str_to_date( T1.SBUDDAT, '%d/%m/%Y %H' ) AS tHour, COUNT(T1.NAME) AS FQTY FROM 
                  (SELECT T.LINENO , T.SONO , T.BUYER , T.STYLE ,T.SBUDDAT , r.OUTID , r.NAME  
                  FROM rejectdetails as r INNER JOIN
                  (SELECT LINENO , SONO , BUYER , STYLE , TID , SBUDDAT 
                  FROM outputdetails WHERE BUDAT = '$budat' AND TYPE<>'FIT' 
                  AND LINENO IN (SELECT MNO FROM zpp_machine_mast WHERE FLOOR = '$pFLOOR')) T
                  ON T.TID = r.OUTID) T1 
                  GROUP BY hour( str_to_date( T1.SBUDDAT, '%d/%m/%Y %H' ) ) , LINENO , SONO , BUYER , STYLE) T2 
                  ON T1.LINENO =  T2.LINENO  AND T1.SONO =  T2.SONO AND T1.BUYER =  T2.BUYER 
                  AND T1.STYLE =  T2.STYLE   AND T1.tHour =  T2.tHour " ;
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
                                $data[$k]["A"] =  $row2["QTY"] ; 
                                break;
                             case "09": 
                                $data[$k]["B"] =  $row2["QTY"] ; 
                                break;
                             case "10": 
                                $data[$k]["C"] = $row2["QTY"] ; 
                                break;
                             case "11": 
                                $data[$k]["D"] = $row2["QTY"] ; 
                                break;
                             case "12": 
                                $data[$k]["E"] = $row2["QTY"] ; 
                                break;
                             case "13": 
                                $data[$k]["F"] = $row2["QTY"] ; 
                                break;
                             case "14": 
                                 $data[$k]["EXT"] = $row2["QTY"] ; 
                                 break;
                             case "15": 
                                $data[$k]["G"] = $row2["QTY"] ; 
                                break;
                             case "16": 
                                $data[$k]["H"] = $row2["QTY"] ; 
                                break;
                             case "17": 
                                $data[$k]["I"] = $row2["QTY"] ; 
                                break;  
                             case "18": 
                                $data[$k]["J"] = $row2["QTY"] ; 
                                break;
                             case "19": 
                                $data[$k]["K"] = $row2["QTY"] ; 
                                break;
                             case "20": 
                                $data[$k]["L"] = $row2["QTY"] ; 
                                break;
                             case "21": 
                                $data[$k]["M"] = $row2["QTY"] ; 
                                break;                                    
                             default:
                         } 
                    }
                }
        }
   } //end - get floor & running line


     //get total DHU
     $query3 = "SELECT  t2.FLOOR , t2.MNO , t2. ALLQTY , t3.FQTY  , round((t3.FQTY/t2. ALLQTY)*100,2) AS QTY FROM 
               ( SELECT m.FLOOR , m.MNO, SUM(QTY) AS ALLQTY FROM outputdetails as o 
               INNER JOIN zpp_machine_mast as m on o.LINENO = m.MNO AND o.BUDAT = '$budat' 
               GROUP BY m.FLOOR , m.MNO ) as t2 
               LEFT JOIN
               (SELECT m.FLOOR , m.MNO , SUM(QTY) AS FQTY FROM zpp_machine_mast as m 
               INNER JOIN 
               (SELECT  o.LINENO,COUNT(r.TID) AS QTY FROM `rejectdetails` as r  
                  INNER JOIN  `outputdetails` as o  ON r.OUTID = o.TID AND o.BUDAT = '$budat'
                  AND o.TYPE <> 'FIT' GROUP BY o.LINENO ) as t ON m.MNO = t.LINENO GROUP BY m.FLOOR) as t3 
                  ON t2.FLOOR = t3.FLOOR  AND t2.MNO = t3.MNO " ;
     $result3 = mysqli_query($db, $query3);
     if (mysqli_num_rows($result3)>0) {
             while($row3 = mysqli_fetch_array($result3)){
                foreach ($data as $k => $v) {
                if ( $v["LINENO"] == $row3["MNO"] )  {
                    $data[$k]["TOTAL"] = $row3["QTY"] ;
                }
            }
        }
   } //end - get total DHU

$ik = 0 ; 
foreach($data as $k => $v) {
    if(array_key_exists("A",$v) == false ){ $data[$k]["A"] = 0 ; $ik = $ik + 1 ;} 
    if(array_key_exists("B",$v) == false ){ $data[$k]["B"] = 0 ; $ik = $ik + 1 ;} 
    if(array_key_exists("C",$v) == false ){ $data[$k]["C"] = 0 ; $ik = $ik + 1 ;} 
    if(array_key_exists("D",$v) == false ){ $data[$k]["D"] = 0 ; $ik = $ik + 1 ;} 
    if(array_key_exists("E",$v) == false ){ $data[$k]["E"] = 0 ; $ik = $ik + 1 ;} 
    if(array_key_exists("F",$v) == false ){ $data[$k]["F"] = 0 ; $ik = $ik + 1 ;} 
    if(array_key_exists("G",$v) == false ){ $data[$k]["G"] = 0 ; $ik = $ik + 1 ;} 
    if(array_key_exists("H",$v) == false ){ $data[$k]["H"] = 0 ; $ik = $ik + 1 ;} 
    if(array_key_exists("I",$v) == false ){ $data[$k]["I"] = 0 ; $ik = $ik + 1 ;} 
    if(array_key_exists("J",$v) == false ){ $data[$k]["J"] = 0 ; $ik = $ik + 1 ;} 
    if(array_key_exists("K",$v) == false ){ $data[$k]["K"] = 0 ; $ik = $ik + 1 ;} 
    if(array_key_exists("L",$v) == false ){ $data[$k]["L"] = 0 ; $ik = $ik + 1 ;} 
    if(array_key_exists("M",$v) == false ){ $data[$k]["M"] = 0 ; $ik = $ik + 1 ;} 
    if(array_key_exists("TOTAL",$v) == false ){ $data[$k]["TOTAL"] = 0 ; } 
    if(array_key_exists("EXT",$v) == false )
    { $data[$k]["EXT"] = 0 ; $ik = $ik + 1; 
    } else { $data[$k]["F"] = round((($data[$k]["F"] + $data[$k]["EXT"])/2),2); }
   //  $data[$k]["TOTAL"] = round((( $data[$k]["A"] +  $data[$k]["B"] +  $data[$k]["C"] + 
   //                        $data[$k]["D"] +  $data[$k]["E"] +  $data[$k]["F"] + 
   //                        $data[$k]["G"] +  $data[$k]["H"] +  $data[$k]["I"] + 
   //                        $data[$k]["J"] +  $data[$k]["K"] +  $data[$k]["L"] + 
   //                        $data[$k]["M"] ) / (14-$ik)),2);
    if(array_key_exists("TDHU",$v) == false ){ $data[$k]["TDHU"] = 0 ;} 
   }

foreach($data as $dat) {
    ?>
        <tr class="itemD" id="<?php echo ($dat["LINENO"]) ?>">
            <td class="LINENO"><?php echo ($dat["LINENO"]) ?></td>
            <td class="SONO" style="display:none;"><?php echo ($dat["SONO"]) ?></td>
            <td class="BUYER"><?php echo ($dat["BUYER"])  ?></td>
            <td class="STYLE"><?php echo ($dat["STYLE"])  ?></td>
            <td class="STYLE"><?php echo ($dat["TDHU"])  ?></td>
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
    <?php }  ?>
<?php
} // end -  if $_POST["opid"]) == 11


if( ($_POST["opid"]) == 31 ) {

   if (isset($_POST["pDate"])){ $pDate = $_POST["pDate"] ; } 
   else { $pDate = '' ; }
   if (isset($_POST["pLine"])){ $pLine = $_POST["pLine"] ; } 
   else { $pLine = '' ; }

        //get group by DHU CODE
        $query31A = "SELECT T.BUDAT , T.LINENO ,  T.BUYER , T.STYLE, T.ID , T.NAME , T.TOTAL FROM
                     (SELECT T.BUDAT , T.LINENO ,  T.BUYER , T.STYLE, T.ID , T.NAME , SUM(T.DFQTY) AS TOTAL FROM
                     (SELECT T.BUDAT , T.LINENO , T.SONO , T.BUYER , T.STYLE, r.ID , r.NAME , r.DFQTY FROM rejectdetails AS r 
                     INNER JOIN 
                     (SELECT TID , BUDAT , LINENO , SONO , BUYER , STYLE FROM outputdetails
                     WHERE DATE_FORMAT(STR_TO_DATE(BUDAT,'%d-%m-%Y'),'%d/%m/%Y') = DATE_FORMAT('$pDate','%d/%m/%Y') AND TYPE <> 'FIT' AND 
                     LINENO IN ('$pLine')) T 
                     ON T.TID = r.OUTID) T GROUP BY T.BUDAT , T.LINENO ,  T.BUYER , T.STYLE, T.ID , T.NAME ) T 
                     ORDER BY T.TOTAL DESC" ;
         $result31A = mysqli_query($db, $query31A);
         if (mysqli_num_rows($result31A)>0) {
               while($row31A = mysqli_fetch_array($result31A)){
               $dataa[$j]["BUYER"]    =  $row31A["BUYER"];
               $dataa[$j]["STYLE"]    =  $row31A["STYLE"];
               $dataa[$j]["LINENO"]   =  $row31A["LINENO"];
               $dataa[$j]["BUDAT"]    =  $row31A["BUDAT"];
               $dataa[$j]["ID"]       =  $row31A["ID"];
               $dataa[$j]["NAME"]     =  $row31A["NAME"];
               $dataa[$j]["TOTAL"]    =  $row31A["TOTAL"];
               $j = $j + 1;
         }
   } //end - get group by DHU CODE

   //get group By Total QTy
           $query31B = "SELECT T.BUDAT ,T.LINENO , T.BUYER,T.STYLE, SUM(T.TOTAL) AS QTY FROM 
                        ( SELECT T.BUDAT , T.LINENO ,  T.BUYER , T.STYLE, T.ID , T.NAME , T.TOTAL FROM
                        (SELECT T.BUDAT , T.LINENO ,  T.BUYER , T.STYLE, T.ID , T.NAME , SUM(T.DFQTY) AS TOTAL FROM
                        (SELECT T.BUDAT , T.LINENO , T.SONO , T.BUYER , T.STYLE, r.ID , r.NAME , r.DFQTY FROM rejectdetails AS r 
                        INNER JOIN 
                        (SELECT TID , BUDAT , LINENO , SONO , BUYER , STYLE FROM outputdetails
                        WHERE DATE_FORMAT(STR_TO_DATE(BUDAT,'%d-%m-%Y'),'%d/%m/%Y') = DATE_FORMAT('$pDate','%d/%m/%Y') AND TYPE <> 'FIT' AND 
                        LINENO IN ('$pLine')) T 
                        ON T.TID = r.OUTID) T GROUP BY T.BUDAT , T.LINENO ,  T.BUYER , T.STYLE, T.ID , T.NAME ) T 
                        ORDER BY T.TOTAL DESC ) AS T 
                        GROUP BY T.BUDAT , T.LINENO ,  T.BUYER , T.STYLE " ;
            $result31B = mysqli_query($db, $query31B);
            if (mysqli_num_rows($result31B)>0) {
               while($row31B = mysqli_fetch_array($result31B)){
                  foreach ($dataa as $k => $v) {
                     if ( ( $v["LINENO"] == $row31B["LINENO"] ) 
                          AND ( $v["BUYER"] == $row31B["BUYER"] ) 
                          AND ( $v["STYLE"] == $row31B["STYLE"])
                          AND ( $v["BUDAT"] == $row31B["BUDAT"])) {

                           $lvdh = round (($v["TOTAL"] / $row31B["QTY"])*100,2);
                           $dataa[$k]["PDHU"] = $lvdh ;

                          }
                  }
            }
    } //end - get group by DHU CODE

   foreach($dataa as $datee) {
      ?>
          <tr class="itemD" id="<?php echo ($datee["LINENO"])?>">
              <td class="STYLE"><?php echo ($datee["BUDAT"]) ?></td>
              <td class="LINENO"><?php echo ($datee["LINENO"])?></td>
              <td class="BUYER"><?php echo ($datee["BUYER"])  ?></td>
              <td class="STYLE"><?php echo ($datee["STYLE"])  ?></td>
              <td class="STYLE" style="display:none;"><?php echo ($datee["ID"])  ?></td>
              <td class="STYLE"><?php echo ($datee["NAME"])  ?></td>
              <td class="STYLE"><?php echo ($datee["TOTAL"])  ?></td>
              <td class="STYLE"><?php echo ($datee["PDHU"])  ?></td>
         </tr>
      <?php }  ?>
  <?php

} // end -  if $_POST["opid"]) == 31





// end php page
?> 
