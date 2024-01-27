<?php
//Include database configuration file
include('server.php');
$data = array();

if( ($_POST["opid"]) == 11 ) {
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

     //get hour wise production - FIT QTY  
     $query2 = " SELECT o.LINENO ,  o.SONO ,	o.BUYER , o.STYLE , str_to_date( o.SBUDDAT, '%d/%m/%Y %H' ) AS tHour, 
                 sum(o.QTY) AS QTY FROM outputdetails as o WHERE o.BUDAT = '$budat' AND o.TYPE ='FIT'AND
                 LINENO IN  (SELECT MNO FROM zpp_machine_mast WHERE FLOOR = '$pFLOOR') 
                 GROUP BY hour( str_to_date( o.SBUDDAT, '%d/%m/%Y %H' ) ) , o.LINENO , o.SONO ,	o.BUYER , o.STYLE  
                 ORDER BY o.LINENO , o.SONO ,	o.BUYER , o.STYLE , hour( str_to_date( o.SBUDDAT, '%d/%m/%Y %H' ) ) ASC " ;
     $result2 = mysqli_query($db, $query2);
     if (mysqli_num_rows($result2)>0) {
             while($row2 = mysqli_fetch_array($result2)){
                foreach ($data as $k => $v) {
                    if ( ( $v["LINENO"] == $row2["LINENO"] ) 
                         AND ( $v["SONO"] == $row2["SONO"] ) 
                         AND ( $v["STYLE"] == $row2["STYLE"])) {
                         $lvhour = substr(substr($row2["tHour"],11),0,-6);
                         switch ($lvhour) {
                             case "08": $data[$k]["A"] = $row2["QTY"] ; break;
                             case "09": $data[$k]["B"] = $row2["QTY"] ; break;
                             case "10": $data[$k]["C"] = $row2["QTY"] ; break;  
                             case "11": $data[$k]["D"] = $row2["QTY"] ; break;
                             case "12": $data[$k]["E"] = $row2["QTY"] ; break;
                             case "13": $data[$k]["F"] = $row2["QTY"] ; break; 
                             case "14": $data[$k]["EXT"] = $row2["QTY"] ; break;
                             case "15": $data[$k]["G"] = $row2["QTY"] ; break;
                             case "16": $data[$k]["H"] = $row2["QTY"] ; break;
                             case "17": $data[$k]["I"] = $row2["QTY"] ; break;  
                             case "18": $data[$k]["J"] = $row2["QTY"] ; break;
                             case "19": $data[$k]["K"] = $row2["QTY"] ; break;
                             case "20": $data[$k]["L"] = $row2["QTY"] ; break;
                             case "21": $data[$k]["M"] = $row2["QTY"] ; break;                                    
                             default:
                         } 
                    }
                }
        }
} //end - get hour wise production - FIT QTY

     //get TARGET QTY
     $query3 = "SELECT k.LINENO,k.SONO,k.BUYER,k.STYLE,(k.SPHOUR/k.MP)*k.TARGET AS QTYY , k.TARGET AS QTY FROM kpiview as k
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
                    $data[$k]["TARGET"] = $row3["QTY"] ;
                }
            }
        }
} //end - get TARGET QTY

} // end - main if 

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
    $data[$k]["F"] = $data[$k]["F"] + $data[$k]["EXT"] ; 
    $data[$k]["TOTAL"] = ( $data[$k]["A"] +  $data[$k]["B"] +  $data[$k]["C"] + 
                          $data[$k]["D"] +  $data[$k]["E"] +  $data[$k]["F"] + 
                          $data[$k]["G"] +  $data[$k]["H"] +  $data[$k]["I"] + 
                          $data[$k]["J"] +  $data[$k]["K"] +  $data[$k]["L"] + 
                          $data[$k]["M"] );
    if(array_key_exists("TARGET",$v) == false ){ $data[$k]["TARGET"] = 0 ;} 

}

foreach($data as $dat) {
    ?>
        <tr class="itemD" id="<?php echo ($dat["LINENO"]) ?>">
            <td class="LINENO"><?php echo ($dat["LINENO"]) ?></td>
            <td class="SONO" style="display:none;"><?php echo ($dat["SONO"]) ?></td>
            <td class="BUYER"><?php echo ($dat["BUYER"])  ?></td>
            <td class="STYLE"><?php echo ($dat["STYLE"])  ?></td>
            <td class="STYLE"><?php echo ($dat["TARGET"])  ?></td>
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
    <?php
}




?>