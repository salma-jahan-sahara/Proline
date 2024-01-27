<?php
//Include database configuration file
include('server.php');
$data = array();
 
//Start:Production summary.(Option 11)
if( ($_POST["opid"]) == 11 ) {
    unset($data);
    //Get all state data
    $opid= $_POST['opid'];
    //$opid= 'L-28';
    $i   = 0 ; // index varibale
    $k   = 0 ; // index varibale
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
     $query1 = "SELECT DISTINCT LINENO  FROM outputdetails 
                WHERE DATE_FORMAT(STR_TO_DATE(BUDAT,'%d-%m-%Y'),'%d/%m/%Y') = DATE_FORMAT('$idate','%d/%m/%Y') 
                AND LINENO IN (SELECT MNO FROM zpp_machine_mast WHERE FLOOR = '$pFLOOR')
                ORDER BY  LINENO ";
               
     $result1 = mysqli_query($db, $query1);
         if (mysqli_num_rows($result1)>0) {
             while($row1 = mysqli_fetch_array($result1)) {
                 $data[$i]["LINENO"]   = $row1["LINENO"];
                 $i = $i + 1;
             }
         } //end - get floor & running line

     //get hour wise production - FIT QTY  
     $query2 = " SELECT LINENO , THOUR , MP AS MANPW , SPHOUR AS SPHUR , SMV AS SMVFL , 
                round(TARQTY) AS TRQTY , TAREFF AS TREFF , ACTEFF AS PDEFF , PRDQTY AS QTY FROM 
                (SELECT P.LINENO , P.tHour AS THOUR, P.PRDQTY , C.MP , C.SPHOUR , C.SMV , 
                C.EFF AS TAREFF, C.TAR AS TARQTY,
                round(((C.SMV * P.PRDQTY) / (60 * C.MP))*100,2) AS ACTEFF,
                round(((C.SMV * C.TAR ) / (60 * C.MP))*100,2) AS CEFF
                FROM 
                (SELECT o.LINENO , LTRIM(LEFT(RTRIM(RIGHT(str_to_date(o.SBUDDAT, '%d/%m/%Y %H'),09)),03)) AS tHour,
                SUM(o.QTY) AS PRDQTY 
                FROM outputdetails as o  
                GROUP BY hour(str_to_date(o.SBUDDAT, '%d/%m/%Y %H')), o.LINENO , o.TYPE , o.BUDAT 
                HAVING DATE_FORMAT(STR_TO_DATE(o.BUDAT,'%d-%m-%Y'),'%d/%m/%Y') = DATE_FORMAT('$idate','%d/%m/%Y') 
                AND o.TYPE = 'FIT' 
                AND o.LINENO IN (SELECT MNO FROM zpp_machine_mast WHERE FLOOR = '$pFLOOR')) AS P
                LEFT JOIN 
                (SELECT B.LINENO,B.tHour,AVG(B.MP) AS MP , AVG(B.SPHOUR) AS SPHOUR , round(AVG(B.SMV),2) AS SMV ,
                AVG(B.EFF) AS EFF , AVG(B.TAR) AS TAR FROM 
                (SELECT A.LINENO,A.tHour,A.SONO,IFNULL(K.MP,0.00) AS MP , IFNULL(K.SPHOUR,0.00) AS SPHOUR,
                IFNULL(round(K.SMV,2),0.00) AS SMV, IFNULL(K.EFF,0.00) AS EFF, IFNULL(K.TARGET,0.00)AS TAR FROM 
                (SELECT DISTINCT o.LINENO ,o.BUDAT,
                LTRIM(LEFT(RTRIM(RIGHT(str_to_date(o.SBUDDAT, '%d/%m/%Y %H'),09)),03)) AS tHour, o.SONO 
                FROM outputdetails as o  
                GROUP BY hour(str_to_date(o.SBUDDAT, '%d/%m/%Y %H')), o.LINENO , o.TYPE , o.BUDAT , o.SONO
                HAVING DATE_FORMAT(STR_TO_DATE(o.BUDAT,'%d-%m-%Y'),'%d/%m/%Y') = DATE_FORMAT('$idate','%d/%m/%Y') 
                AND o.TYPE = 'FIT' 
                AND o.LINENO IN (SELECT MNO FROM zpp_machine_mast WHERE FLOOR = '$pFLOOR')) AS A
                LEFT JOIN kpiview AS K ON A.LINENO = K.LINENO AND A.SONO = K.SONO AND A.BUDAT = K.BUDAT
                ORDER BY A.tHour,SONO ) AS B
                GROUP BY B.LINENO , B.tHour) AS C 
                ON P.LINENO = C.LINENO AND P.tHour = C.tHour
                ORDER BY P.LINENO,P.tHour ) AS FINAL ORDER BY LINENO , THOUR " ;
     $result2 = mysqli_query($db, $query2);
         if (mysqli_num_rows($result2)>0) {
             while($row2 = mysqli_fetch_array($result2)){
                foreach ($data as $k => $v) {
                    if ( ( $v["LINENO"] == $row2["LINENO"] ) ) {
                         $lvhour = $row2["THOUR"] ;
                         switch ($lvhour) {
                             case "08": $data[$k]["A"] = $row2["QTY"] ; break;
                             case "09": $data[$k]["B"] = $row2["QTY"] ; break;
                             case "10": $data[$k]["C"] = $row2["QTY"] ; break;  
                             case "11": $data[$k]["D"] = $row2["QTY"] ; break;
                             case "12": $data[$k]["E"] = $row2["QTY"] ; break;
                             case "13": $data[$k]["F"] = $row2["QTY"] ; break; 
                             case "14": $data[$k]["G"] = $row2["QTY"] ; break;
                             case "15": $data[$k]["H"] = $row2["QTY"] ; break;
                             case "16": $data[$k]["I"] = $row2["QTY"] ; break;
                             case "17": $data[$k]["J"] = $row2["QTY"] ; break;  
                             case "18": $data[$k]["K"] = $row2["QTY"] ; break;
                             case "19": $data[$k]["L"] = $row2["QTY"] ; break;
                             case "20": $data[$k]["M"] = $row2["QTY"] ; break;
                             case "21": $data[$k]["N"] = $row2["QTY"] ; break;
                             case "22": $data[$k]["O"] = $row2["QTY"] ; break; 
                             case "23": $data[$k]["P"] = $row2["QTY"] ; break;                                   
                             default:
                         }
                         $data[$k]["TARGET"] = $row2["TRQTY"]; 
                    }
                }
            }
         } //end - get hour wise production - FIT QTY
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
            if(array_key_exists("N",$v) == false ){ $data[$k]["N"] = 0 ;} 
            if(array_key_exists("O",$v) == false ){ $data[$k]["O"] = 0 ;} 
            if(array_key_exists("P",$v) == false ){ $data[$k]["P"] = 0 ;} 
            $data[$k]["TOTAL"] = ($data[$k]["A"] +  $data[$k]["B"] +  $data[$k]["C"] + 
                                  $data[$k]["D"] +  $data[$k]["E"] +  $data[$k]["F"] + 
                                  $data[$k]["G"] +  $data[$k]["H"] +  $data[$k]["I"] + 
                                  $data[$k]["J"] +  $data[$k]["K"] +  $data[$k]["L"] + 
                                  $data[$k]["M"] +  $data[$k]["N"] +  $data[$k]["O"] + $data[$k]["P"]);
            if(array_key_exists("TARGET",$v) == false ){ $data[$k]["TARGET"] = 0 ;} 
        }
            foreach($data as $dat) {
                    ?>
                        <tr class="itemD" id="<?php echo ($dat["LINENO"]) ?>">
                            <td class="LINENO"><?php echo ($dat["LINENO"]) ?></td>
                            <td class="PRD"><?php echo "PRD" ?></td>
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
} //End:Production summary.(Option 11)

//Start:Production details line and so wiese.(Option 12)
if( ($_POST["opid"]) == 12 ) {
    unset($data);
    $i = 0; // index variable
    if (isset($_POST["pDate"])){ $pDate = $_POST["pDate"] ; } 
    else { $pDate = '' ; }
    if (isset($_POST["pLine"])){ $pLine = $_POST["pLine"] ; } 
    else { $pLine = '' ; }
    //Start:get line wise sono and style on selected date
     $qryCD = "SELECT AB.BUDAT,AB.LINENO,AB.SONO,AB.BUYER,AB.STYLE,AB.COLOR,AB.CHECKED,AB.OK,
                CONCAT(AB.DEFECT,'(',CD.DCOUNT,')') AS DEFECT , AB.REJECT FROM 
                (SELECT BUDAT,LINENO,SONO,BUYER,STYLE,COLOR,IFNULL(SUM(QTY),0) AS CHECKED,
                IFNULL(SUM(CASE WHEN TYPE ='FIT' THEN QTY END),0) AS OK ,  
                IFNULL(SUM(CASE WHEN TYPE ='DEF' THEN QTY END),0) AS DEFECT ,
                IFNULL(SUM(CASE WHEN TYPE ='REJ' THEN QTY END),0) AS REJECT 
                FROM `outputdetails` GROUP BY BUDAT , LINENO , SONO , BUYER , STYLE , COLOR
                HAVING 
                DATE_FORMAT(STR_TO_DATE(BUDAT,'%d-%m-%Y'),'%d/%m/%Y') = DATE_FORMAT('$pDate','%d/%m/%Y')
                AND  LINENO = '$pLine' ORDER BY LINENO , SONO ) AS AB 
                LEFT JOIN 
                (SELECT o.BUDAT,  o.LINENO ,o.SONO,o.BUYER,o.STYLE,o.COLOR ,SUM(r.DFQTY) AS DCOUNT
                FROM `rejectdetails` as r  
                INNER JOIN  `outputdetails` as o ON r.OUTID = o.TID 
                AND o.TYPE <> 'FIT'
                GROUP BY  o.BUDAT , o.LINENO, o.BUDAT , o.LINENO , o.SONO , o.BUYER , o.STYLE , o.COLOR
                HAVING 
                DATE_FORMAT(STR_TO_DATE(o.BUDAT,'%d-%m-%Y'),'%d/%m/%Y') = DATE_FORMAT('$pDate','%d/%m/%Y')
                AND o.LINENO = '$pLine' ORDER BY LINENO , SONO ) AS CD 
                ON AB.BUDAT = CD.BUDAT AND AB.LINENO = CD.LINENO 
                AND AB.SONO = CD.SONO  AND AB.BUYER  = CD.BUYER
                AND AB.STYLE = CD.STYLE AND AB.COLOR = CD.COLOR ";
     $resCD = mysqli_query($db, $qryCD);
     if (mysqli_num_rows($resCD)>0) {
         while($rowCD = mysqli_fetch_array($resCD)) {
             $data[$i]["BUDAT"]   = $rowCD["BUDAT"];
             $data[$i]["LINENO"]  = $rowCD["LINENO"];
             $data[$i]["SONO"]    = $rowCD["SONO"];
             $data[$i]["BUYER"]   = $rowCD["BUYER"];
             $data[$i]["STYLE"]   = $rowCD["STYLE"];
             $data[$i]["COLOR"]   = $rowCD["COLOR"];
             $data[$i]["CHECKED"] = $rowCD["CHECKED"];
             $data[$i]["OK"]      = $rowCD["OK"];
             $data[$i]["DEFECT"]  = $rowCD["DEFECT"];
             $data[$i]["REJECT"]  = $rowCD["REJECT"];
             $i = $i + 1;
         }
    } //end - get line wise sono and style on selected date

    foreach($data as $dat) {
        ?>
            <tr class="itemD" id="<?php echo ($dat["LINENO"]) ?>">
                <td class="LINENO"><?php echo ($dat["BUDAT"]) ?></td>
                <td class="LINENO"><?php echo ($dat["LINENO"]) ?></td>
                <td class="SONO"> <?php  echo ($dat["SONO"]) ?></td>
                <td class="BUYER"><?php echo ($dat["BUYER"])?></td>
                <td class="STYLE"><?php echo ($dat["STYLE"])?></td>
                <td class="STYLE"><?php echo ($dat["COLOR"])?></td>
                <td class="STYLE"><?php echo ($dat["CHECKED"])?></td>
                <td class="STYLE"><?php echo ($dat["OK"])  ?></td>
                <td class="STYLE"><?php echo ($dat["DEFECT"])?></td>
                <td class="STYLE"><?php echo ($dat["REJECT"])?></td>
           </tr>
        <?php
    }



}//End:Production details line and so wiese.(Option 12)


//Start:Production details line and so wiese with size.(Option 13)
if( ($_POST["opid"]) == 13 ) {
    unset($data);
    $i = 0; // index variable
    if (isset($_POST["pDate"])){ $pDate = $_POST["pDate"] ; } 
    else { $pDate = '' ; }
    if (isset($_POST["pLine"])){ $pLine = $_POST["pLine"] ; } 
    else { $pLine = '' ; }
    //Start:get line wise sono and style on selected date
     $qryAB = "SELECT AB.BUDAT,AB.LINENO,AB.SONO,AB.BUYER,AB.STYLE,AB.COLOR,AB.SIZE,AB.CHECKED,AB.OK,
                CONCAT(AB.DEFECT,'(',CD.DCOUNT,')') AS DEFECT , AB.REJECT FROM 
                (SELECT BUDAT,LINENO,SONO,BUYER,STYLE,COLOR,SIZE,IFNULL(SUM(QTY),0) AS CHECKED,
                IFNULL(SUM(CASE WHEN TYPE ='FIT' THEN QTY END),0) AS OK ,  
                IFNULL(SUM(CASE WHEN TYPE ='DEF' THEN QTY END),0) AS DEFECT ,
                IFNULL(SUM(CASE WHEN TYPE ='REJ' THEN QTY END),0) AS REJECT 
                FROM `outputdetails` GROUP BY BUDAT , LINENO , SONO , BUYER , STYLE , COLOR , SIZE 
                HAVING 
                DATE_FORMAT(STR_TO_DATE(BUDAT,'%d-%m-%Y'),'%d/%m/%Y') = DATE_FORMAT('$pDate','%d/%m/%Y')
                AND  LINENO = '$pLine' ORDER BY LINENO , SONO ) AS AB 
                LEFT JOIN 
                (SELECT o.BUDAT,  o.LINENO ,o.SONO,o.BUYER,o.STYLE,o.COLOR,o.SIZE,SUM(r.DFQTY) AS DCOUNT
                FROM `rejectdetails` as r  
                INNER JOIN  `outputdetails` as o ON r.OUTID = o.TID 
                AND o.TYPE <> 'FIT'
                GROUP BY  o.BUDAT , o.LINENO, o.BUDAT , o.LINENO , o.SONO , o.BUYER , o.STYLE , o.COLOR ,o.SIZE
                HAVING 
                DATE_FORMAT(STR_TO_DATE(o.BUDAT,'%d-%m-%Y'),'%d/%m/%Y') = DATE_FORMAT('$pDate','%d/%m/%Y')
                AND o.LINENO = '$pLine' ORDER BY LINENO , SONO ) AS CD 
                ON AB.BUDAT = CD.BUDAT AND AB.LINENO = CD.LINENO 
                AND AB.SONO = CD.SONO  AND AB.BUYER  = CD.BUYER
                AND AB.STYLE = CD.STYLE AND AB.COLOR = CD.COLOR AND AB.SIZE = CD.SIZE";
     $resAB = mysqli_query($db, $qryAB);
     if (mysqli_num_rows($resAB)>0) {
         while($rowAB = mysqli_fetch_array($resAB)) {
             $data[$i]["BUDAT"]   = $rowAB["BUDAT"];
             $data[$i]["LINENO"]  = $rowAB["LINENO"];
             $data[$i]["SONO"]    = $rowAB["SONO"];
             $data[$i]["BUYER"]   = $rowAB["BUYER"];
             $data[$i]["STYLE"]   = $rowAB["STYLE"];
             $data[$i]["COLOR"]   = $rowAB["COLOR"];
             $data[$i]["SIZE"]    = $rowAB["SIZE"];
             $data[$i]["CHECKED"] = $rowAB["CHECKED"];
             $data[$i]["OK"]      = $rowAB["OK"];
             $data[$i]["DEFECT"]  = $rowAB["DEFECT"];
             $data[$i]["REJECT"]  = $rowAB["REJECT"];
             $i = $i + 1;
         }
    } //end - get line wise sono and style on selected date

    foreach($data as $dat) {
        ?>
            <tr class="itemD" id="<?php echo ($dat["LINENO"]) ?>">
                <td class="LINENO"><?php echo ($dat["BUDAT"]) ?></td>
                <td class="LINENO"><?php echo ($dat["LINENO"]) ?></td>
                <td class="SONO"> <?php  echo ($dat["SONO"]) ?></td>
                <td class="BUYER"><?php echo ($dat["BUYER"])?></td>
                <td class="STYLE"><?php echo ($dat["STYLE"])?></td>
                <td class="STYLE"><?php echo ($dat["COLOR"])?></td>
                <td class="STYLE w3-deep-orange"><?php echo ($dat["SIZE"])?></td>
                <td class="STYLE"><?php echo ($dat["CHECKED"])?></td>
                <td class="STYLE"><?php echo ($dat["OK"])  ?></td>
                <td class="STYLE"><?php echo ($dat["DEFECT"])?></td>
                <td class="STYLE"><?php echo ($dat["REJECT"])?></td>
           </tr>
        <?php
    }
}//End:Production details line and so wiese with size.(Option 13)


?>