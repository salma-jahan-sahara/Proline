<?php
//Include database configuration file
include('server.php');
$data = array();
 
//Start:Production hourwise report for pad sewing
if( ($_POST["opid"]) == 55 ) {
    unset($data);
    $i = 0; // index variable
    $idate = TRIM($_POST["idate"]);
    $dtf = new DateTime($idate, new DateTimezone('Asia/Dhaka'));
    $dtf_day = date_format($dtf, 'd-m-Y');
    $idate = $dtf_day;   
    $pspfl  = trim($_POST['pspfl'],'') ;
    //get floor & running line : considering sewing output for base 
     $query1 = "SELECT DISTINCT LINENO  FROM outputdetails 
                WHERE DATE_FORMAT(STR_TO_DATE(BUDAT,'%d-%m-%Y'),'%d/%m/%Y') = DATE_FORMAT(STR_TO_DATE('$idate','%d-%m-%Y'),'%d/%m/%Y') 
                AND LINENO IN (SELECT MNO FROM zpp_machine_mast WHERE FLOOR = '$pspfl')
                ORDER BY  LINENO ";
     $result1 = mysqli_query($db, $query1);
         if (mysqli_num_rows($result1)>0) {
             while($row1 = mysqli_fetch_array($result1)) {
                     $data[$i]["LINENO"]   = $row1["LINENO"];
                     $i = $i + 1;
                 }
          } 
    //end - get floor & running line : considering sewing output for base
    
    //PAD Sewing production fetch query 
        $query2 = "SELECT LNNUM AS LINENO, THOUR , SUM(PDQTY) AS QTY , 0 AS TRQTY  FROM
                    (
                        SELECT  LNNUM , hour(SYSDT) AS THOUR, PDQTY  
                        FROM `tpfn` 
                        WHERE  BUDAT = '$idate' 
                            AND LNNUM IN (SELECT MNO FROM zpp_machine_mast WHERE FLOOR = '$pspfl')
                            AND TYPEF = 'FIT'
                    ) AS A 
                    GROUP BY LNNUM , THOUR
                    ORDER BY LNNUM , THOUR";
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

        } // end if mysqli_num_rows($result2)
        //make not found column to 0 values 
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
        //end - make not found column to 0 values

        //final table output
        foreach($data as $dat) {
            ?>
                <tr class="itemPS" id="<?php echo ($dat["LINENO"]) ?>">
                    <td class="LINENO"><?php echo ($dat["LINENO"]) ?></td>
                    <td class="PRD"><?php echo "PAD" ?></td>
                    <td class="SONO"  style="display:none;"><?php echo '-' ?></td>
                    <td class="BUYER" style="display:none;"><?php echo '-'  ?></td>
                    <td class="STYLE" style="display:none;"><?php echo '-'  ?></td>
                    <td class="STYLE" style="display:none;"><?php echo '-'  ?></td>
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
        //end - final table output
 }
   

?>