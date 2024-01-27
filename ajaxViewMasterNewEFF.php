<?php
//Include database configuration file
include('server.php');
$data = array();

if( ($_POST["opid"]) == 11 ) {
    //Get all state data
    $opid= $_POST['opid'];
    //$opid= 'L-28';
    $i   = 0 ; // index varibale
    $pFLOOR = $_POST["pFLOOR"];
   
    $budat = '';
    if (isset($_POST["idate"])){
        $idate = $_POST["idate"] ;
        $dt = new DateTime($idate, new DateTimezone('Asia/Dhaka'));
        $dt_day = date_format($dt, 'd-m-Y');
        $budat = $dt_day;
    }else {
        $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
        $dt_day = date_format($dt, 'd-m-Y');
        $budat = $dt_day;
    }


    //get floor & running line
     $query1 = "SELECT  DISTINCT LINENO  FROM outputdetails 
                WHERE DATE_FORMAT(STR_TO_DATE(BUDAT,'%d-%m-%Y'),'%d/%m/%Y') = DATE_FORMAT('$idate','%d/%m/%Y')
                AND LINENO IN (SELECT MNO FROM zpp_machine_mast WHERE FLOOR = '$pFLOOR') 
                ORDER BY  LINENO  " ;
     $result1 = mysqli_query($db, $query1);
     if (mysqli_num_rows($result1)>0) {
        while($row1 = mysqli_fetch_array($result1)){
        $data[$i]["LINENO"]   = $row1["LINENO"];
        //$data[$i]["SONO"]     =  $row1["SONO"];
        //$data[$i]["BUYER"]    =  $row1["BUYER"];
        //$data[$i]["STYLE"]    =  $row1["STYLE"];
        $i = $i + 1;
     }
} //end - get floor & running line
 //get hour wise production - FIT QTY  

// this query added for efficiency new calculation on 06.06.2021
    $query2 = " SELECT LINENO , THOUR , MP AS MANPW , round(SPHOUR,2) AS SPHUR , SMV AS SMVFL , 
                round(TARQTY,2) AS TRQTY , round(TAREFF) AS TREFF , round(ACTEFF) AS PDEFF , PRDQTY AS QTY FROM 
                (SELECT P.LINENO , P.tHour AS THOUR, P.PRDQTY , C.MP , C.SPHOUR , C.SMV , 
                C.EFF AS TAREFF, C.TAR AS TARQTY,
                round(((C.SMV * P.PRDQTY) / (60 * C.MP))*100,2) AS ACTEFF,
                round(((C.SMV * C.TAR ) / (60 * C.MP))*100,2) AS CEFF
                FROM 
                (
                    SELECT o.LNNUM AS LINENO , hour(o.SYSDT) AS tHour, SUM(o.PDQTY) AS PRDQTY 
                    FROM tpfn as o 
                    WHERE DATE_FORMAT(STR_TO_DATE(o.BUDAT,'%d-%m-%Y'),'%d/%m/%Y') =  DATE_FORMAT('$idate','%d/%m/%Y') 
                        AND o.TYPEF = 'FIT'
                        AND o.LNNUM IN (SELECT MNO FROM zpp_machine_mast WHERE FLOOR = '$pFLOOR')
                    GROUP BY hour(o.SYSDT), o.LNNUM , o.TYPEF , o.BUDAT 
                ) AS P
                LEFT JOIN 
                (SELECT B.LINENO,B.tHour,AVG(B.MP) AS MP , AVG(B.SPHOUR) AS SPHOUR , round(AVG(B.SMV),2) AS SMV ,
                AVG(B.EFF) AS EFF , AVG(B.TAR) AS TAR FROM 
                    (
                        SELECT A.LINENO,A.tHour,A.SONO,
                            IFNULL(K.MP,0) AS MP , 
                            IFNULL(round(K.SPHOUR,2),0.00) AS SPHOUR,
                            IFNULL(round(K.SMV,2),0.00) AS SMV, 
                            IFNULL(round(K.EFF,2),0.00) AS EFF, 
                            IFNULL(round(K.TARGET,2),0.00)AS TAR 
                        FROM 
                            (
                                SELECT DISTINCT o.LNNUM AS LINENO ,o.BUDAT,
                                    hour(o.SYSDT)AS tHour, o.SONUM AS SONO 
                                FROM tpfn as o  
                                WHERE DATE_FORMAT(STR_TO_DATE(o.BUDAT,'%d-%m-%Y'),'%d/%m/%Y') = DATE_FORMAT('$idate','%d/%m/%Y') 
                                    AND o.TYPEF = 'FIT' 
                                    AND o.LNNUM IN (SELECT MNO FROM zpp_machine_mast WHERE FLOOR = '$pFLOOR')
                                GROUP BY hour(o.SYSDT), o.LNNUM , o.TYPEF , o.BUDAT , o.SONUM
                            ) AS A
                        LEFT JOIN kpiview AS K ON A.LINENO = K.LINENO AND A.SONO = K.SONO AND A.BUDAT = K.BUDAT
                        ORDER BY A.tHour,A.SONO 
                ) AS B
                GROUP BY B.LINENO , B.tHour) AS C 
                ON P.LINENO = C.LINENO AND P.tHour = C.tHour
                ORDER BY P.LINENO,P.tHour ) AS FINAL
                ORDER BY LINENO , THOUR " ;
// End: this query added for efficiency new calculation on 06.06.2021
    
    //At beginning production qty have taken from "outputdetails" table .
        //  $query2 = " SELECT LINENO , THOUR , MP AS MANPW , SPHOUR AS SPHUR , SMV AS SMVFL , 
        //             TARQTY AS TRQTY , round(TAREFF) AS TREFF , round(ACTEFF) AS PDEFF , PRDQTY AS QTY FROM 
        //             (SELECT P.LINENO , P.tHour AS THOUR, P.PRDQTY , C.MP , C.SPHOUR , C.SMV , 
        //             C.EFF AS TAREFF, C.TAR AS TARQTY,
        //             round(((C.SMV * P.PRDQTY) / (60 * C.MP))*100,2) AS ACTEFF,
        //             round(((C.SMV * C.TAR ) / (60 * C.MP))*100,2) AS CEFF
        //             FROM 
        //             (
        //                 SELECT o.LINENO , LTRIM(LEFT(RTRIM(RIGHT(str_to_date(o.SBUDDAT, '%d/%m/%Y %H'),09)),03)) AS tHour,
        //                 SUM(o.QTY) AS PRDQTY 
        //                 FROM outputdetails as o  
        //                 GROUP BY hour(str_to_date(o.SBUDDAT, '%d/%m/%Y %H')), o.LINENO , o.TYPE , o.BUDAT 
        //                 HAVING DATE_FORMAT(STR_TO_DATE(o.BUDAT,'%d-%m-%Y'),'%d/%m/%Y') = DATE_FORMAT('$idate','%d/%m/%Y') 
        //                 AND o.TYPE = 'FIT' 
        //                 AND o.LINENO IN (SELECT MNO FROM zpp_machine_mast WHERE FLOOR = '$pFLOOR')
        //             ) AS P
        //             LEFT JOIN 
        //             (SELECT B.LINENO,B.tHour,AVG(B.MP) AS MP , AVG(B.SPHOUR) AS SPHOUR , round(AVG(B.SMV),2) AS SMV ,
        //             AVG(B.EFF) AS EFF , AVG(B.TAR) AS TAR FROM 
        //             (SELECT A.LINENO,A.tHour,A.SONO,IFNULL(K.MP,0.00) AS MP , IFNULL(K.SPHOUR,0.00) AS SPHOUR,
        //             IFNULL(round(K.SMV,2),0.00) AS SMV, IFNULL(K.EFF,0.00) AS EFF, IFNULL(K.TARGET,0.00)AS TAR FROM 
        //             (SELECT DISTINCT o.LINENO ,o.BUDAT,
        //             LTRIM(LEFT(RTRIM(RIGHT(str_to_date(o.SBUDDAT, '%d/%m/%Y %H'),09)),03)) AS tHour, o.SONO 
        //             FROM outputdetails as o  
        //             GROUP BY hour(str_to_date(o.SBUDDAT, '%d/%m/%Y %H')), o.LINENO , o.TYPE , o.BUDAT , o.SONO
        //             HAVING DATE_FORMAT(STR_TO_DATE(o.BUDAT,'%d-%m-%Y'),'%d/%m/%Y') = DATE_FORMAT('$idate','%d/%m/%Y') 
        //             AND o.TYPE = 'FIT' 
        //             AND o.LINENO IN (SELECT MNO FROM zpp_machine_mast WHERE FLOOR = '$pFLOOR')) AS A
        //             LEFT JOIN kpiview AS K ON A.LINENO = K.LINENO AND A.SONO = K.SONO AND A.BUDAT = K.BUDAT
        //             ORDER BY A.tHour,SONO ) AS B
        //             GROUP BY B.LINENO , B.tHour) AS C 
        //             ON P.LINENO = C.LINENO AND P.tHour = C.tHour
        //             ORDER BY P.LINENO,P.tHour ) AS FINAL
        //             ORDER BY LINENO , THOUR " ;
     $result2 = mysqli_query($db, $query2);
     if (mysqli_num_rows($result2)>0) {
             while($row2 = mysqli_fetch_array($result2)){
                foreach ($data as $k => $v) {
                    if ( ( $v["LINENO"] == $row2["LINENO"] ) ) {
                         $lvhour = $row2["THOUR"] ;
                         switch ($lvhour) {
                             case "08": $data[$k]["A"] = $row2["PDEFF"] ;  break;
                             case "09": $data[$k]["B"] = $row2["PDEFF"] ; break;
                             case "10": $data[$k]["C"] = $row2["PDEFF"] ; break;  
                             case "11": $data[$k]["D"] = $row2["PDEFF"] ; break;
                             case "12": $data[$k]["E"] = $row2["PDEFF"] ; break;
                             case "13": $data[$k]["F"] = $row2["PDEFF"] ; break; 
                             case "14": $data[$k]["G"] = $row2["PDEFF"] ; break;
                             case "15": $data[$k]["H"] = $row2["PDEFF"] ; break;
                             case "16": $data[$k]["I"] = $row2["PDEFF"] ; break;
                             case "17": $data[$k]["J"] = $row2["PDEFF"] ; break;  
                             case "18": $data[$k]["K"] = $row2["PDEFF"] ; break;
                             case "19": $data[$k]["L"] = $row2["PDEFF"] ; break;
                             case "20": $data[$k]["M"] = $row2["PDEFF"] ; break;
                             case "21": $data[$k]["N"] = $row2["PDEFF"] ; break;
                             case "22": $data[$k]["O"] = $row2["PDEFF"] ; break;  
                             case "23": $data[$k]["P"] = $row2["PDEFF"] ; break;                                   
                             default:
                         }
                         $data[$k]["TARGET"] = $row2["TREFF"]; 
                    }
                }
        }
     } 
//end - get hour wise production - FIT QTY

} // end - main if 

foreach($data as $k => $v) {
    $count = 0 ; 
    if(array_key_exists("A",$v) == false ){ $data[$k]["A"] = 0 ;} else { $count = $count + 1 ;} 
    if(array_key_exists("B",$v) == false ){ $data[$k]["B"] = 0 ;} else { $count = $count + 1 ;} 
    if(array_key_exists("C",$v) == false ){ $data[$k]["C"] = 0 ;} else { $count = $count + 1 ;} 
    if(array_key_exists("D",$v) == false ){ $data[$k]["D"] = 0 ;} else { $count = $count + 1 ;} 
    if(array_key_exists("E",$v) == false ){ $data[$k]["E"] = 0 ;} else { $count = $count + 1 ;} 
    if(array_key_exists("F",$v) == false ){ $data[$k]["F"] = 0 ;} else { $count = $count + 1 ;} 
    if(array_key_exists("G",$v) == false ){ $data[$k]["G"] = 0 ;} else { $count = $count + 1 ;} 
    if(array_key_exists("H",$v) == false ){ $data[$k]["H"] = 0 ;} else { $count = $count + 1 ;} 
    if(array_key_exists("I",$v) == false ){ $data[$k]["I"] = 0 ;} else { $count = $count + 1 ;} 
    if(array_key_exists("J",$v) == false ){ $data[$k]["J"] = 0 ;} else { $count = $count + 1 ;} 
    if(array_key_exists("K",$v) == false ){ $data[$k]["K"] = 0 ;} else { $count = $count + 1 ;} 
    if(array_key_exists("L",$v) == false ){ $data[$k]["L"] = 0 ;} else { $count = $count + 1 ;} 
    if(array_key_exists("M",$v) == false ){ $data[$k]["M"] = 0 ;} else { $count = $count + 1 ;} 
    if(array_key_exists("N",$v) == false ){ $data[$k]["N"] = 0 ;} else { $count = $count + 1 ;} 
    if(array_key_exists("O",$v) == false ){ $data[$k]["O"] = 0 ;} else { $count = $count + 1 ;} 
    if(array_key_exists("P",$v) == false ){ $data[$k]["P"] = 0 ;} else { $count = $count + 1 ;} 
    if ($count == 0 ){ $count = 1 ;}
    $data[$k]["TOTAL"] = round(( $data[$k]["A"] +  $data[$k]["B"] +  $data[$k]["C"] + 
                          $data[$k]["D"] +  $data[$k]["E"] +  $data[$k]["F"] + 
                          $data[$k]["G"] +  $data[$k]["H"] +  $data[$k]["I"] + 
                          $data[$k]["J"] +  $data[$k]["K"] +  $data[$k]["L"] + 
                          $data[$k]["M"] +  $data[$k]["N"] +  $data[$k]["O"] + $data[$k]["P"]) / $count) ;
    
    if(array_key_exists("TARGET",$v) == false ){ $data[$k]["TARGET"] = 0 ;} 

}
 
foreach($data as $dat) {
    ?>
        <tr class="itemD" id="<?php echo ($dat["LINENO"]) ?>">
            <td class="LINENO"><?php echo ($dat["LINENO"]) ?></td>
            <td class="EFF"><?php echo "EFF" ?></td>
            <td class="SONO"  style="display:none;"><?php echo ($dat["SONO"]) ?></td>
            <td class="BUYER" style="display:none;"><?php echo ($dat["BUYER"])  ?></td>
            <td class="STYLE" style="display:none;"><?php echo ($dat["STYLE"])  ?></td>
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
            <td class="STYLE"><?php echo ($dat["N"])  ?></td>
            <td class="STYLE"><?php echo ($dat["O"])  ?></td>
            <td class="STYLE"><?php echo ($dat["P"])  ?></td>
            <td class="STYLE"><?php echo ($dat["TOTAL"])  ?></td>
       </tr>
    <?php
}




?>