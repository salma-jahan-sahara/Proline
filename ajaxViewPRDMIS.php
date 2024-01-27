<?php
//Include database configuration file
include('server.php');
//parameter value ;
    $fDate='';
    $tDate='';
     if (isset($_POST["fDate"])) {
        $fDate = $_POST["fDate"];
        $fDate = new DateTime($fDate, new DateTimezone('Asia/Dhaka'));
        $fDate = date_format($fDate, 'd-m-Y');
      }
     if (isset($_POST["tDate"])) {
        $tDate = $_POST["tDate"];
        $tDate = new DateTime($tDate, new DateTimezone('Asia/Dhaka'));
        $tDate = date_format($tDate, 'd-m-Y');
      }
//server date
if( $_POST["opid"] == 31 ) {
         $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
         $dt_day = date_format($dt, 'Y-m-d');
         $idate = $dt_day;
         echo($idate);
   }
// floor select
if( ($_POST["opid"]) == 32 ) {
    $sql = "SELECT DISTINCT FLOOR FROM `zpp_machine_mast` WHERE FLOOR <> '' ";
    $result = $db->query($sql) ;
    $options = "";
    if (mysqli_num_rows($result)>0){
         while ($res = mysqli_fetch_array($result)) {
            ?>
                <option> <?php echo $res['FLOOR'] ?> </option>
            <?php
         }   
    }
 }
//line select based on floor
if( ($_POST["opid"]) == 33 ) {
    $FLOORNO = $_POST["FLOORNO"] ;
    $sql = "SELECT DISTINCT MNO FROM `zpp_machine_mast` WHERE FLOOR = '$FLOORNO'";
    $result = $db->query($sql) ;
    $options = "";
    if (mysqli_num_rows($result)>0){
        while ($res = mysqli_fetch_array($result)) {
        ?>
            <option> <?php echo $res['MNO'] ?> </option>
     <?php
     }
    }
 }
//Buyer select
if( ($_POST["opid"]) == 34 ) {
    $sql = "SELECT DISTINCT BUYER FROM `inputdetails` ORDER BY BUYER ASC";
    $result = $db->query($sql) ;
    if (mysqli_num_rows($result)>0){
         while ($res = mysqli_fetch_array($result)) {
            ?>
                <option> <?php echo $res['BUYER'] ?> </option>
            <?php
         }
    }
 }
//Display all summary report 
if (($_POST["opid"]) == 01){
            //param value 
                $floor = $_POST["flno"];
                $lnnum = $_POST["line"];
                $fDate = $_POST["fdat"];
                $tDate = $_POST["tdat"];
                $dCond = $_POST["dcon"];
            //local value 
                $fDate='';
                $tDate='';
                $data;
             if (isset($_POST["fdat"])) {
                $fDate = $_POST["fdat"];
                $fDate = new DateTime($fDate, new DateTimezone('Asia/Dhaka'));
                $fDate = date_format($fDate, 'd-m-Y');
              }
             if (isset($_POST["tdat"])) {
                $tDate = $_POST["tdat"];
                $tDate = new DateTime($tDate, new DateTimezone('Asia/Dhaka'));
                $tDate = date_format($tDate, 'd-m-Y');
              }
            //dynamic condition block 
                $clause = '';
                $clauseA ='';
                $clauseB ='';
                $clauseC ='';
                $clauseA = "STR_TO_DATE(BUDAT,'%d-%m-%Y') BETWEEN STR_TO_DATE('$fDate','%d-%m-%Y') AND STR_TO_DATE('$tDate','%d-%m-%Y')" ;
                $clauseB =  $dCond;
                if (strlen($floor)>1){
                    $clauseC = "LINENO IN ( SELECT MNO FROM `zpp_machine_mast` WHERE FLOOR = '$floor' )";
                 }
                if (strlen($lnnum)>1 && strlen($floor)>1){
                    $clauseC = "LINENO IN ( SELECT MNO FROM `zpp_machine_mast` WHERE FLOOR = '$floor' AND MNO = '$lnnum' )";
                 } elseif(strlen($lnnum)>1){
                    $clauseC = "LINENO IN ( SELECT MNO FROM `zpp_machine_mast` WHERE  MNO = '$lnnum' )";
                 }
                if (strlen($clauseC)>1 && strlen($clauseB)>1){
                    $clause = $clauseA.' AND '.$clauseB.' AND '. $clauseC ;
                 } elseif (strlen($clauseB)>1){
                    $clause = $clauseA.' AND '.$clauseB ;
                 }elseif (strlen($clauseC)>1){
                    $clause = $clauseA.' AND '.$clauseC ;
                 }else {
                    $clause = $clauseA ;
                 }
            //calculation 
                $dataA = get_inputMaster($db,$clauseB,$clauseC);
                        //empty value check for all time input
                        if ($dataA == ''){ echo 'Not found'; return; }
                $dataB = get_inputMasterDate($db,$clause);
                $dataC = get_outputMaster($db,$clauseB,$clauseC);
                $dataD = get_outputMasterDate($db,$clause);
                $dataE = get_finishingMaster($db,$clauseB,$clauseC);
                $dataF = get_finishingMasterDate($db,$clause);
                $dataG = get_packingMaster($db,$clauseB,$clauseC);
                $dataH = get_packingMasterDate($db,$clause);
                //input data combine
                if ( $dataA != '' && $dataB != ''){
                    if (count($dataA)>0 && count($dataB)>0){
                            foreach ($dataB as $k => $v){
                                foreach ($dataA as $k1 => $v1){
                                    if ($v1["CBKEY"] ==  $v["CBKEY"]){
                                        $dataA[$k1]["IDQTY"] = $dataB[$k]["INQTY"] ;
                                    }
                                }
                            }
                        } 
                 } 
                //output data combine 
                if ( $dataC != '' && $dataD != ''){
                    if (count($dataC)>0 && count($dataD)>0){
                            foreach ($dataD as $k => $v){ 
                                foreach ($dataC as $k1 => $v1){
                                    if ($v1["CBKEY"] == $v["CBKEY"]){
                                        $dataC[$k1]["TODQT"] = $dataD[$k]["TOTQT"] ;
                                        $dataC[$k1]["FIDQT"] = $dataD[$k]["FITQT"] ;
                                        $dataC[$k1]["DEDQT"] = $dataD[$k]["DEFQT"] ;
                                        $dataC[$k1]["REDQT"] = $dataD[$k]["REJQT"] ;
                                    }
                                } 
                            }
                        } 
                 }
                foreach($dataC as $k => $v) {
                    if(array_key_exists("TOTQT",$v) == false ){ $data[$k]["TOTQT"] = 0 ;} 
                    if(array_key_exists("FITQT",$v) == false ){ $data[$k]["FITQT"] = 0 ;} 
                    if(array_key_exists("DEFQT",$v) == false ){ $data[$k]["DEFQT"] = 0 ;} 
                    if(array_key_exists("REJQT",$v) == false ){ $data[$k]["REJQT"] = 0 ;} 
                    if(array_key_exists("TODQT",$v) == false ){ $data[$k]["TODQT"] = 0 ;} 
                    if(array_key_exists("FIDQT",$v) == false ){ $data[$k]["FIDQT"] = 0 ;} 
                    if(array_key_exists("DEDQT",$v) == false ){ $data[$k]["DEDQT"] = 0 ;} 
                    if(array_key_exists("REDQT",$v) == false ){ $data[$k]["REDQT"] = 0 ;} 
                 }

                //finishing data combine 
                if ( $dataE != '' && $dataF != ''){
                    if (count($dataE)>0 && count($dataF)>0){
                            foreach ($dataF as $k => $v){ 
                                foreach ($dataE as $k1 => $v1){
                                    if ($v1["CBKEY"] == $v["CBKEY"]){
                                        $dataE[$k1]["FDTQT"] = $dataF[$k]["FDTQT"] ;
                                        $dataE[$k1]["FDFQT"] = $dataF[$k]["FDFQT"] ;
                                        $dataE[$k1]["FDDQT"] = $dataF[$k]["FDDQT"] ;
                                        $dataE[$k1]["FDRQT"] = $dataF[$k]["FDRQT"] ;
                                    }
                                } 
                            }
                        } 
                 }
                foreach($dataE as $k => $v) {
                    if(array_key_exists("FNTQT",$v) == false ){ $dataE[$k]["FNTQT"] = 0 ;} 
                    if(array_key_exists("FNFQT",$v) == false ){ $dataE[$k]["FNFQT"] = 0 ;} 
                    if(array_key_exists("FNDQT",$v) == false ){ $dataE[$k]["FNDQT"] = 0 ;} 
                    if(array_key_exists("FNRQT",$v) == false ){ $dataE[$k]["FNRQT"] = 0 ;} 
                    if(array_key_exists("FDTQT",$v) == false ){ $dataE[$k]["FDTQT"] = 0 ;} 
                    if(array_key_exists("FDFQT",$v) == false ){ $dataE[$k]["FDFQT"] = 0 ;} 
                    if(array_key_exists("FDDQT",$v) == false ){ $dataE[$k]["FDDQT"] = 0 ;} 
                    if(array_key_exists("FDRQT",$v) == false ){ $dataE[$k]["FDRQT"] = 0 ;} 
                 }

                //packing data combine 
                if ( $dataG != '' && $dataH != ''){
                    if (count($dataG)>0 && count($dataH)>0){
                            foreach ($dataH as $k => $v){ 
                                foreach ($dataG as $k1 => $v1){
                                    if ($v1["CBKEY"] == $v["CBKEY"]){
                                        $dataG[$k1]["PDTQT"] = $dataH[$k]["PDTQT"] ;
                                        $dataG[$k1]["PDFQT"] = $dataH[$k]["PDFQT"] ;
                                        $dataG[$k1]["PDDQT"] = $dataH[$k]["PDDQT"] ;
                                        $dataG[$k1]["PDRQT"] = $dataH[$k]["PDRQT"] ;
                                    }
                                } 
                            }
                        } 
                 }
                foreach($dataG as $k => $v) {
                    if(array_key_exists("PKTQT",$v) == false ){ $dataG[$k]["PKTQT"] = 0 ;} 
                    if(array_key_exists("PKFQT",$v) == false ){ $dataG[$k]["PKFQT"] = 0 ;} 
                    if(array_key_exists("PKDQT",$v) == false ){ $dataG[$k]["PKDQT"] = 0 ;} 
                    if(array_key_exists("PKRQT",$v) == false ){ $dataG[$k]["PKRQT"] = 0 ;} 
                    if(array_key_exists("PDTQT",$v) == false ){ $dataG[$k]["PDTQT"] = 0 ;} 
                    if(array_key_exists("PDFQT",$v) == false ){ $dataG[$k]["PDFQT"] = 0 ;} 
                    if(array_key_exists("PDDQT",$v) == false ){ $dataG[$k]["PDDQT"] = 0 ;} 
                    if(array_key_exists("PDRQT",$v) == false ){ $dataG[$k]["PDRQT"] = 0 ;} 
                 }

                //input & output data combine 
                if ( $dataC != ''){
                    if (count($dataC)>0){
                            foreach ($dataC as $k => $v){
                                foreach ($dataA as $k1 => $v1){
                                    if ($v1["CBKEY"] == $v["CBKEY"]){
                                        $dataA[$k1]["TOTQT"] = $dataC[$k]["TOTQT"] ;
                                        $dataA[$k1]["FITQT"] = $dataC[$k]["FITQT"] ;
                                        $dataA[$k1]["DEFQT"] = $dataC[$k]["DEFQT"] ;
                                        $dataA[$k1]["REJQT"] = $dataC[$k]["REJQT"] ;
                                        $dataA[$k1]["TODQT"] = $dataC[$k]["TODQT"] ;
                                        $dataA[$k1]["FIDQT"] = $dataC[$k]["FIDQT"] ;
                                        $dataA[$k1]["DEDQT"] = $dataC[$k]["DEDQT"] ;
                                        $dataA[$k1]["REDQT"] = $dataC[$k]["REDQT"] ;
                                    }
                                }
                            }
                        } 
                 }
                //input,output & finishing data combine 
                if ( $dataE != ''){
                    if (count($dataE)>0){
                            foreach ($dataE as $k => $v){
                                foreach ($dataA as $k1 => $v1){
                                    if ($v1["CBKEY"] == $v["CBKEY"]){
                                        $dataA[$k1]["FNTQT"] = $dataE[$k]["FNTQT"] ;
                                        $dataA[$k1]["FNFQT"] = $dataE[$k]["FNFQT"] ;
                                        $dataA[$k1]["FNDQT"] = $dataE[$k]["FNDQT"] ;
                                        $dataA[$k1]["FNRQT"] = $dataE[$k]["FNRQT"] ;
                                        $dataA[$k1]["FDTQT"] = $dataE[$k]["FDTQT"] ;
                                        $dataA[$k1]["FDFQT"] = $dataE[$k]["FDFQT"] ;
                                        $dataA[$k1]["FDDQT"] = $dataE[$k]["FDDQT"] ;
                                        $dataA[$k1]["FDRQT"] = $dataE[$k]["FDRQT"] ;
                                    }
                                }
                            }
                        } 
                 }
                //input,output,finishing & packing data combine 
                if ( $dataG != ''){
                    if (count($dataG)>0){
                            foreach ($dataG as $k => $v){
                                foreach ($dataA as $k1 => $v1){
                                    if ($v1["CBKEY"] == $v["CBKEY"]){
                                        $dataA[$k1]["PKTQT"] = $dataG[$k]["PKTQT"] ;
                                        $dataA[$k1]["PKFQT"] = $dataG[$k]["PKFQT"] ;
                                        $dataA[$k1]["PKDQT"] = $dataG[$k]["PKDQT"] ;
                                        $dataA[$k1]["PKRQT"] = $dataG[$k]["PKRQT"] ;
                                        $dataA[$k1]["PDTQT"] = $dataG[$k]["PDTQT"] ;
                                        $dataA[$k1]["PDFQT"] = $dataG[$k]["PDFQT"] ;
                                        $dataA[$k1]["PDDQT"] = $dataG[$k]["PDDQT"] ;
                                        $dataA[$k1]["PDRQT"] = $dataG[$k]["PDRQT"] ;
                                    }
                                }
                            }
                        } 
                 }
                //final data assign for input
                $data = $dataA;
                //final table display 
                if (count($data) > 0 ){
                    //null column 0 value
                    foreach($data as $k => $v) {
                        if(array_key_exists("IDQTY",$v) == false ){ $data[$k]["IDQTY"] = 0 ;} 
                        if(array_key_exists("TOTQT",$v) == false ){ $data[$k]["TOTQT"] = 0 ;} 
                        if(array_key_exists("FITQT",$v) == false ){ $data[$k]["FITQT"] = 0 ;} 
                        if(array_key_exists("DEFQT",$v) == false ){ $data[$k]["DEFQT"] = 0 ;} 
                        if(array_key_exists("REJQT",$v) == false ){ $data[$k]["REJQT"] = 0 ;} 
                        if(array_key_exists("TODQT",$v) == false ){ $data[$k]["TODQT"] = 0 ;} 
                        if(array_key_exists("FIDQT",$v) == false ){ $data[$k]["FIDQT"] = 0 ;} 
                        if(array_key_exists("DEDQT",$v) == false ){ $data[$k]["DEDQT"] = 0 ;} 
                        if(array_key_exists("REDQT",$v) == false ){ $data[$k]["REDQT"] = 0 ;} 
                        if(array_key_exists("FNTQT",$v) == false ){ $data[$k]["FNTQT"] = 0 ;} 
                        if(array_key_exists("FNFQT",$v) == false ){ $data[$k]["FNFQT"] = 0 ;} 
                        if(array_key_exists("FNDQT",$v) == false ){ $data[$k]["FNDQT"] = 0 ;} 
                        if(array_key_exists("FNRQT",$v) == false ){ $data[$k]["FNRQT"] = 0 ;} 
                        if(array_key_exists("FDTQT",$v) == false ){ $data[$k]["FDTQT"] = 0 ;} 
                        if(array_key_exists("FDFQT",$v) == false ){ $data[$k]["FDFQT"] = 0 ;} 
                        if(array_key_exists("FDDQT",$v) == false ){ $data[$k]["FDDQT"] = 0 ;} 
                        if(array_key_exists("FDRQT",$v) == false ){ $data[$k]["FDRQT"] = 0 ;} 
                        if(array_key_exists("PKTQT",$v) == false ){ $data[$k]["PKTQT"] = 0 ;} 
                        if(array_key_exists("PKFQT",$v) == false ){ $data[$k]["PKFQT"] = 0 ;} 
                        if(array_key_exists("PKDQT",$v) == false ){ $data[$k]["PKDQT"] = 0 ;} 
                        if(array_key_exists("PKRQT",$v) == false ){ $data[$k]["PKRQT"] = 0 ;} 
                        if(array_key_exists("PDTQT",$v) == false ){ $data[$k]["PDTQT"] = 0 ;} 
                        if(array_key_exists("PDFQT",$v) == false ){ $data[$k]["PDFQT"] = 0 ;} 
                        if(array_key_exists("PDDQT",$v) == false ){ $data[$k]["PDDQT"] = 0 ;} 
                        if(array_key_exists("PDRQT",$v) == false ){ $data[$k]["PDRQT"] = 0 ;} 
                     }

                     $count=0;
                    foreach($data as $dat) {
                                 $count =  $count + 1 ;
                             ?>
                             <tr id = <?php echo $count ?>>
                                 <td><?php echo $count ?></td>
                                 <td><?php echo ($dat["FLOOR"]) ?></td>
                                 <td><?php echo ($dat["LNNUM"]) ?></td>
                                 <td><?php echo ($dat["BUYER"]) ?></td>
                                 <td><?php echo ($dat["SONUM"]) ?></td>
                                 <td><?php echo ($dat["STYLE"]) ?></td>
                                 <td><?php echo ($dat["COLOR"]) ?></td>
                                 <td class="w3-border-left">
                                     <span class="INQTY" style="cursor: pointer; font-weight: bold;" onclick="onClickTblMIS(this);"><?php echo ($dat["INQTY"]) ?></span>
                                 </td>
                                 <td >
                                     <span class="IDQTY"><?php echo ($dat["IDQTY"]) ?></span>
                                 </td>
                                 <td class="w3-border-left">
                                       <span class="OA" style="font-weight: bold;"><?php echo ($dat["TOTQT"]) ?></span><?php echo ' : ' ?>
                                       <span class="OB" style="font-weight: bold; color: green;"><?php echo ($dat["FITQT"]) ; ?></span><?php echo ' | ' ?>
                                       <span class="OC" style="font-weight: bold; color: #cccc33;"><?php echo ($dat["DEFQT"]) ;?></span><?php echo ' | ' ?>
                                       <span class="OD" style="font-weight: bold; color: red;"><?php echo ($dat["REJQT"]) ;?></span>
                                 </td>
                                 <td>
                                       <span class="OE" style="font-weight: bold;"><?php echo ($dat["TODQT"]) ?></span><?php echo ' : ' ?>
                                       <span class="OF" style="font-weight: bold; color: green;"><?php echo ($dat["FIDQT"]) ?></span><?php echo ' | ' ?>
                                       <span class="OG" style="font-weight: bold; color: #cccc33;"><?php echo ($dat["DEDQT"]) ?></span><?php echo ' | ' ?>
                                       <span class="OH" style="font-weight: bold; color: red;"><?php echo ($dat["REDQT"]) ?></span>
                                 </td>
                                 <td class="w3-border-left">
                                       <span class="FA" style="font-weight: bold;"><?php echo ($dat["FNTQT"]) ?></span><?php echo ' : ' ?>
                                       <span class="FB" style="font-weight: bold; color: green;"><?php echo ($dat["FNFQT"]) ?></span><?php echo ' | ' ?>
                                       <span class="FC" style="font-weight: bold; color: #cccc33;"><?php echo ($dat["FNDQT"]) ?></span><?php echo ' | ' ?>
                                       <span class="FD" style="font-weight: bold; color: red;"><?php echo ($dat["FNRQT"]) ?></span>
                                 </td>
                                 <td>
                                       <span class="FE" style="font-weight: bold;"><?php echo ($dat["FDTQT"]) ?></span><?php echo ' : ' ?>
                                       <span class="FF" style="font-weight: bold; color: green;"><?php echo ($dat["FDFQT"]) ?></span><?php echo ' | ' ?>
                                       <span class="FG" style="font-weight: bold; color: #cccc33;"><?php echo ($dat["FDDQT"]) ?></span><?php echo ' | ' ?>
                                       <span class="FH" style="font-weight: bold; color: red;"><?php echo ($dat["FDRQT"]) ?></span>
                                 </td>
                                 <td class="w3-border-left"> 
                                       <span class="PA" style="font-weight: bold;"><?php echo ($dat["PKTQT"]) ?></span><?php echo ' : ' ?>
                                       <span class="PB" style="font-weight: bold; color: green;"><?php echo ($dat["PKFQT"]) ?></span><?php echo ' | ' ?>
                                       <span class="PC" style="font-weight: bold; color: #cccc33;"><?php echo ($dat["PKDQT"]) ?></span><?php echo ' | ' ?>
                                       <span class="PD" style="font-weight: bold; color: red;"><?php echo ($dat["PKRQT"]) ?></span>
                                 </td>
                                 <td>
                                       <span class="PE" style="font-weight: bold;"><?php echo ($dat["PDTQT"]) ?></span><?php echo ' : ' ?>
                                       <span class="PF" style="font-weight: bold; color: green;"><?php echo ($dat["PDFQT"]) ?></span><?php echo ' | ' ?>
                                       <span class="PG" style="font-weight: bold; color: #cccc33;"><?php echo ($dat["PDDQT"]) ?></span><?php echo ' | ' ?>
                                       <span class="PH" style="font-weight: bold; color: red;"><?php echo ($dat["PDRQT"]) ?></span>
                                 </td>
                             </tr>
                             <?php 
                         }
                 } else {
                    echo 'Not found';
                 }
            //End -  calculation  
     } // end 01 condition 
//Display input details datewise
if (($_POST["opid"]) == 02){
                //param value 
                $vLINE = $_POST["LINE"];
                $vSONO = $_POST["SONO"];
                $vBUYR = $_POST["BUYR"];
                $vSTYL = $_POST["STYL"];
                $vCOLR = $_POST["COLR"];
                $clause = '';
                $clause = " LINENO = '$vLINE' AND SONO = '$vSONO' AND BUYER = '$vBUYR' AND STYLE = '$vSTYL' AND COLOR = '$vCOLR'";
                $count=0;
                $data = get_inputDetails($db,$clause);
                if (count($data) > 0 ){
                     foreach($data as $dat) {
                            $count =  $count + 1 ;
                        ?>
                        <tr id = <?php echo 'size'.$count ?> onclick="onClickTblMISDetails(this);" style="cursor: pointer;">
                            <td><?php echo $count ?></td>
                            <td><?php echo ($dat["BUDAT"]) ?></td>
                            <td class="INQTYD"><?php echo ($dat["INQTY"]) ?></td>
                        </tr>
                        <?php 
                      }
                 } else {
                    echo 'Not found';
                 }
 }

//Display input details date & size wise
if (($_POST["opid"]) == 03){
    $tmp = $_POST["vMAIN"] ; 
    $tmp1 =  explode("|",$tmp);
    //param value 
       $vDATE = $_POST["vDATE"];
       $vLINE = trim($tmp1[0]);
       $vBUYR = trim($tmp1[1]);
       $vSONO = trim($tmp1[2]);
       $vSTYL = trim($tmp1[3]);
       $vCOLR = trim($tmp1[4]);
       $clause = '';
       $clause = " BUDAT = '$vDATE' AND LINENO = '$vLINE' AND SONO = '$vSONO' AND BUYER = '$vBUYR' AND STYLE = '$vSTYL' AND COLOR = '$vCOLR'";
       $count=0;
       $data = get_inputDetailsSize($db,$clause);
        if (count($data) > 0 ){
            foreach($data as $dat) {
                    $count =  $count + 1 ;
                ?>
                <tr id = <?php echo 'sizemore'.$count ?> onclick="onClickTblMISDetailsMore(this);" style="cursor: pointer;">
                    <td><?php echo $count ?></td>
                    <td><?php echo ($dat["BUDAT"]) ?></td>
                    <td><?php echo ($dat["SIZE"]) ?></td>
                    <td class="INQTYDSIZE"><?php echo ($dat["INQTY"]) ?></td>
                </tr>
                <?php 
            }
        } else {
            echo 'Not found';
        }
 }
//Display input details date & size wise more details
if (($_POST["opid"]) == 04){
    $tmp = $_POST["vMAIN"] ; 
    $tmp1 =  explode("|",$tmp);
    //param value 
       $vDATE = $_POST["vDATE"];
       $vSIZE = $_POST["vSIZE"];
       $vLINE = trim($tmp1[0]);
       $vBUYR = trim($tmp1[1]);
       $vSONO = trim($tmp1[2]);
       $vSTYL = trim($tmp1[3]);
       $vCOLR = trim($tmp1[4]);
       $clause = '';
       $clause = " BUDAT = '$vDATE' AND LINENO = '$vLINE' AND SONO = '$vSONO' AND BUYER = '$vBUYR' AND STYLE = '$vSTYL' AND COLOR = '$vCOLR' AND SIZE = '$vSIZE'";
       $count=0;
       $data = get_inputDetailsSizeMore($db,$clause);
        if (count($data) > 0 ){
            foreach($data as $dat) {
                    $count =  $count + 1 ;
                ?>
                <tr>
                    <td><?php echo $count ?></td>
                    <td><?php echo ($dat["BUDAT"]) ?></td>
                    <td><?php echo ($dat["TID"]) ?></td>
                    <td><?php echo ($dat["DOCNO"]) ?></td>
                    <td><?php echo ($dat["UPTIME"]) ?></td>
                    <td><?php echo ($dat["SYSDT"]) ?></td>
                    <td><?php echo ($dat["SIZE"]) ?></td>
                    <td><?php echo ($dat["QTY"]) ?></td>
                    <td><?php echo ($dat["NOP"]) ?></td>
                    <td class="INQTYDSIZEMORE"><?php echo ($dat["INQTY"]) ?></td>
                </tr>
                <?php 
            }
        } else {
            echo 'Not found';
        }
 }



// Start:function part
    //Display input data based on floor
     function get_inputMaster($db,$clauseB,$clauseC) {
          $clause = '';
         //server currentDate
          $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
          $tDate = date_format($dt, 'd-m-Y');
         //dynamic clause
         $clauseA = "STR_TO_DATE(BUDAT,'%d-%m-%Y') BETWEEN STR_TO_DATE('01-03-2020','%d-%m-%Y') AND STR_TO_DATE('$tDate','%d-%m-%Y')";
         if (strlen($clauseC) > 0 && strlen($clauseB) > 0){
            $clause = $clauseA.' AND '.$clauseB.' AND '. $clauseC ;
          }else if (strlen($clauseB) > 0){
            $clause = $clauseA.' AND '.$clauseB ;
          }else if (strlen($clauseC) > 0){
            $clause = $clauseA.' AND '.$clauseC ;
          }else {
            $clause = $clauseA;
          }
         $query =  "SELECT p.FLOOR , l.LNNUM, l.SONUM, l.BUYER , l.STYLE , l.COLOR , l.INQTY , l.CBKEY , 
                    0 AS IDQTY 
                    FROM 
                    ( 
                        SELECT LINENO AS LNNUM, SONO AS SONUM, BUYER , STYLE , COLOR , SUM(INQTY) AS INQTY , 
                            CONCAT(LINENO,'|',SONO,'|',BUYER,'|',STYLE,'|',COLOR) AS CBKEY
                        FROM 
                        (
                            SELECT BUDAT ,LINENO , SONO , BUYER , STYLE , COLOR , QTY , NOP , round(QTY/NOP) as INQTY
                            FROM `inputdetails` WHERE $clause 
                        ) AS i
                            GROUP BY LINENO , SONO , BUYER , STYLE , COLOR
                            ORDER BY LINENO , SONO , BUYER , STYLE , COLOR 
                    ) AS l LEFT JOIN zpp_machine_mast AS p ON l.LNNUM = p.MNO 
                    ORDER BY p.FLOOR , l.LNNUM, l.SONUM DESC" ;
       // prepare query statement
           $result = mysqli_query($db, $query);
           if (mysqli_num_rows($result)>0) { 
                while($row = mysqli_fetch_assoc($result)){
                    $inputMaster[]=$row;
                }
                return $inputMaster;
           } else {
                return '';
           }
      }

     function get_inputMasterDate($db,$clause) {
        $query =  "SELECT LINENO AS LNNUM, SONO AS SONUM, BUYER , STYLE , COLOR , SUM(INQTY) AS INQTY ,
                          CONCAT(LINENO,'|',SONO,'|',BUYER,'|',STYLE,'|',COLOR) AS CBKEY
                   FROM 
                    (
                        SELECT BUDAT ,LINENO , SONO , BUYER , STYLE , COLOR , QTY , NOP , round(QTY/NOP) as INQTY
                        FROM `inputdetails` WHERE $clause  
                    ) AS i
                        GROUP BY LINENO , SONO , BUYER , STYLE , COLOR
                        ORDER BY LINENO , SONO , BUYER , STYLE , COLOR " ;
        // prepare query statement
            $result = mysqli_query($db, $query);
            if (mysqli_num_rows($result)>0) { 
                    while($row = mysqli_fetch_assoc($result)){
                        $inputMaster[]=$row;
                    }
                    return $inputMaster;
                 } else {
                    return '';
             } 
       }
     function get_inputDetails($db,$clause){
                 $query =  "SELECT BUDAT , LINENO AS LNNUM, SONO AS SONUM, BUYER , 
                            STYLE , COLOR , SUM(INQTY) AS INQTY 
                 FROM 
                    (
                      SELECT BUDAT ,LINENO , SONO , BUYER , STYLE , COLOR ,  QTY , NOP , round(QTY/NOP) as INQTY
                      FROM `inputdetails` 
                      WHERE $clause
                    ) AS i
                 GROUP BY BUDAT , LINENO , SONO , BUYER , STYLE , COLOR 
                 ORDER BY STR_TO_DATE(BUDAT,'%d-%m-%Y') , LINENO , SONO , BUYER , STYLE , COLOR " ;
        // prepare query statement
            $result = mysqli_query($db, $query);
            if (mysqli_num_rows($result)>0) { 
                    while($row = mysqli_fetch_assoc($result)){
                        $inputDetails[]=$row;
                    }
                    return $inputDetails;
                 } else {
                    return '';
             } 

      }
     function get_inputDetailsSize($db,$clause){
        $query =  "SELECT BUDAT , LINENO AS LNNUM, SONO AS SONUM, BUYER , 
                   STYLE , COLOR , SIZE , SUM(INQTY) AS INQTY 
        FROM 
           (
             SELECT BUDAT ,LINENO , SONO , BUYER , STYLE , COLOR , SIZE , QTY , NOP , round(QTY/NOP) as INQTY
             FROM `inputdetails` 
             WHERE $clause
           ) AS i
        GROUP BY BUDAT , LINENO , SONO , BUYER , STYLE , COLOR , SIZE
        ORDER BY STR_TO_DATE(BUDAT,'%d-%m-%Y') , LINENO , SONO , BUYER , STYLE , COLOR , SIZE " ;
        // prepare query statement
        $result = mysqli_query($db, $query);
        if (mysqli_num_rows($result)>0) { 
                while($row = mysqli_fetch_assoc($result)){
                    $inputDetails[]=$row;
                }
                return $inputDetails;
                } else {
                return '';
            } 

      }
     function get_inputDetailsSizeMore($db,$clause){
        $query =  " SELECT BUDAT ,LINENO , SONO , BUYER , STYLE , COLOR , SIZE , QTY , NOP , round(QTY/NOP) as INQTY,
             TID , CONCAT(MBLNR,'|',MJAHR,'|',ZEILE)AS DOCNO , UPTIME, SYSDT  
             FROM `inputdetails` 
             WHERE $clause
             ORDER BY TID " ;
        // prepare query statement
        $result = mysqli_query($db, $query);
        if (mysqli_num_rows($result)>0) { 
                while($row = mysqli_fetch_assoc($result)){
                    $inputDetails[]=$row;
                }
                return $inputDetails;
                } else {
                return '';
            } 

      }
     function get_outputMaster($db,$clauseB,$clauseC) {
        $clause = '';
       //server currentDate
        $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
        $tDate = date_format($dt, 'd-m-Y');
       //dynamic clause
       $clauseA = "STR_TO_DATE(BUDAT,'%d-%m-%Y') BETWEEN STR_TO_DATE('01-03-2020','%d-%m-%Y') AND STR_TO_DATE('$tDate','%d-%m-%Y')";
       if (strlen($clauseC) > 0 && strlen($clauseB) > 0){
          $clause = $clauseA.' AND '.$clauseB.' AND '. $clauseC ;
        }else if (strlen($clauseB) > 0){
          $clause = $clauseA.' AND '.$clauseB ;
        }else if (strlen($clauseC) > 0){
          $clause = $clauseA.' AND '.$clauseC ;
        }else {
          $clause = $clauseA;
        }
       $query =  "SELECT p.FLOOR , l.LNNUM , l.SONUM , l.BUYER , l.STYLE , l.COLOR , 
                         l.TOTQT , l.FITQT , l.DEFQT , l.REJQT , 
                          0 AS TODQT , 0 AS FIDQT , 0 AS DEDQT , 0 AS REDQT , 
                         TRIM(l.CBKEY) AS CBKEY
                  FROM 
                  ( 
                      SELECT LINENO AS LNNUM , SONO AS SONUM, BUYER , STYLE , COLOR,
                      SUM(CHECKED) AS TOTQT , SUM(FIT) AS FITQT  , SUM(DEF) AS DEFQT , SUM(REJ) AS REJQT , 
                      CONCAT(LINENO,'|',SONO,'|',BUYER,'|',STYLE,'|',COLOR) AS CBKEY
                      FROM 
                        ( SELECT LINENO , SONO , BUYER , STYLE , COLOR,IFNULL(SUM(QTY),0) AS CHECKED,
                                IFNULL(SUM(CASE WHEN TYPE ='FIT' THEN QTY END),0) AS FIT,  
                                IFNULL(SUM(CASE WHEN TYPE ='DEF' THEN QTY END),0) AS DEF,
                                IFNULL(SUM(CASE WHEN TYPE ='REJ' THEN QTY END),0) AS REJ 
                            FROM `outputdetails`
                            WHERE $clause
                            GROUP BY LINENO , SONO , BUYER , STYLE , TYPE , COLOR 
                            ORDER BY LINENO , SONO , BUYER , STYLE , COLOR 
                        ) AS I GROUP BY LINENO , SONO , BUYER , STYLE , COLOR
                  ) AS l LEFT JOIN zpp_machine_mast AS p ON l.LNNUM = p.MNO " ;
         // prepare query statement
         $result = mysqli_query($db, $query);
         if (mysqli_num_rows($result)>0) { 
              while($row = mysqli_fetch_assoc($result)){
                  $outputMaster[]=$row;
              }
              return $outputMaster;
         } else {
              return '';
         }
      }
     function get_outputMasterDate($db,$clause) {
        $query =  "SELECT LINENO AS LNNUM , SONO AS SONUM, BUYER , STYLE , COLOR,
                    SUM(CHECKED) AS TOTQT , SUM(FIT) AS FITQT  , SUM(DEF) AS DEFQT , SUM(REJ) AS REJQT , 
                    TRIM(CONCAT(LINENO,'|',SONO,'|',BUYER,'|',STYLE,'|',COLOR)) AS CBKEY
                    FROM 
                    ( SELECT LINENO , SONO , BUYER , STYLE , COLOR,IFNULL(SUM(QTY),0) AS CHECKED,
                            IFNULL(SUM(CASE WHEN TYPE ='FIT' THEN QTY END),0) AS FIT,  
                            IFNULL(SUM(CASE WHEN TYPE ='DEF' THEN QTY END),0) AS DEF,
                            IFNULL(SUM(CASE WHEN TYPE ='REJ' THEN QTY END),0) AS REJ 
                        FROM `outputdetails`
                        WHERE $clause
                        GROUP BY LINENO , SONO , BUYER , STYLE , TYPE , COLOR 
                        ORDER BY LINENO , SONO , BUYER , STYLE , COLOR 
                    ) AS I GROUP BY LINENO , SONO , BUYER , STYLE , COLOR " ;
        // prepare query statement
            $result = mysqli_query($db, $query);
            if (mysqli_num_rows($result)>0) { 
                    while($row = mysqli_fetch_assoc($result)){
                        $outputMaster[]=$row;
                    }
                    return $outputMaster;
                 } else {
                    return '';
             } 
       }
     function get_finishingMaster($db,$clauseB,$clauseC) {
        $clause = '';
       //server currentDate
        $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
        $tDate = date_format($dt, 'd-m-Y');
       //dynamic clause
       $clauseA = "STR_TO_DATE(BUDAT,'%d-%m-%Y') BETWEEN STR_TO_DATE('01-03-2020','%d-%m-%Y') AND STR_TO_DATE('$tDate','%d-%m-%Y')";
       if (strlen($clauseC) > 0 && strlen($clauseB) > 0){
          $clause = $clauseA.' AND '.$clauseB.' AND '. $clauseC ;
        }else if (strlen($clauseB) > 0){
          $clause = $clauseA.' AND '.$clauseB ;
        }else if (strlen($clauseC) > 0){
          $clause = $clauseA.' AND '.$clauseC ;
        }else {
          $clause = $clauseA;
        }
        $clause = str_replace("SONO","SONUM",$clause);
        $clause = str_replace("LINENO","LNNUM",$clause);
       $query =  "SELECT p.FLOOR , l.LNNUM , l.SONUM , l.BUYER , l.STYLE , l.COLOR , 
                         l.TOTQT AS FNTQT , l.FITQT AS FNFQT , l.DEFQT AS FNDQT, l.REJQT AS FNRQT , 
                          0 AS FDTQT , 0 AS FDFQT , 0 AS FDDQT , 0 AS FDRQT , 
                         TRIM(l.CBKEY) AS CBKEY
                  FROM 
                  ( 
                      SELECT LINENO AS LNNUM , SONO AS SONUM, BUYER , STYLE , COLOR,
                      SUM(CHECKED) AS TOTQT , SUM(FIT) AS FITQT  , SUM(DEF) AS DEFQT , SUM(REJ) AS REJQT , 
                      CONCAT(LINENO,'|',SONO,'|',BUYER,'|',STYLE,'|',COLOR) AS CBKEY
                      FROM 
                        ( SELECT LNNUM AS LINENO , SONUM AS SONO , BUYER , STYLE , COLOR,IFNULL(SUM(PDQTY),0) AS CHECKED,
                                IFNULL(SUM(CASE WHEN TYPEF ='FIT' THEN PDQTY END),0) AS FIT,  
                                IFNULL(SUM(CASE WHEN TYPEF ='DEF' THEN PDQTY END),0) AS DEF,
                                IFNULL(SUM(CASE WHEN TYPEF ='REJ' THEN PDQTY END),0) AS REJ 
                            FROM `tpfn`
                            WHERE $clause
                            GROUP BY LNNUM , SONUM , BUYER , STYLE , TYPEF , COLOR 
                            ORDER BY LNNUM , SONUM , BUYER , STYLE , TYPEF , COLOR 
                        ) AS I GROUP BY LINENO , SONO , BUYER , STYLE , COLOR
                  ) AS l LEFT JOIN zpp_machine_mast AS p ON l.LNNUM = p.MNO " ;
         // prepare query statement
         $result = mysqli_query($db, $query);
         if (mysqli_num_rows($result)>0) { 
              while($row = mysqli_fetch_assoc($result)){
                  $finishingMaster[]=$row;
              }
              return $finishingMaster;
         } else {
              return '';
         }
      }
     function get_finishingMasterDate($db,$clause) {
        $clause = str_replace("SONO","SONUM",$clause);
        $clause = str_replace("LINENO","LNNUM",$clause);

        $query =  "SELECT LINENO AS LNNUM , SONO AS SONUM, BUYER , STYLE , COLOR,
        SUM(CHECKED) AS FDTQT , SUM(FIT) AS FDFQT  , SUM(DEF) AS FDDQT , IFNULL(SUM(REJ),0) AS FDRQT , 
        TRIM(CONCAT(LINENO,'|',SONO,'|',BUYER,'|',STYLE,'|',COLOR)) AS CBKEY
        FROM 
        ( SELECT LNNUM AS LINENO , SONUM AS SONO , BUYER , STYLE , COLOR,IFNULL(SUM(PDQTY),0) AS CHECKED,
                IFNULL(SUM(CASE WHEN TYPEF ='FIT' THEN PDQTY END),0) AS FIT,  
                IFNULL(SUM(CASE WHEN TYPEF ='DEF' THEN PDQTY END),0) AS DEF,
                IFNULL(SUM(CASE WHEN TYPEF ='REJ' THEN PDQTY END),0) AS REJ 
            FROM `tpfn`
            WHERE $clause
            GROUP BY LNNUM , SONUM , BUYER , STYLE , TYPEF , COLOR 
            ORDER BY LNNUM , SONUM , BUYER , STYLE , TYPEF , COLOR  
        ) AS I GROUP BY LINENO , SONO , BUYER , STYLE , COLOR " ;
        // prepare query statement
        $result = mysqli_query($db, $query);
        if (mysqli_num_rows($result)>0) { 
                while($row = mysqli_fetch_assoc($result)){
                    $finishingMaster[]=$row;
                }
                return $finishingMaster;
            } else {
                return '';
        }

      }
     function get_packingMaster($db,$clauseB,$clauseC) {
        $clause = '';
       //server currentDate
        $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
        $tDate = date_format($dt, 'd-m-Y');
       //dynamic clause
       $clauseA = "STR_TO_DATE(BUDAT,'%d-%m-%Y') BETWEEN STR_TO_DATE('01-03-2020','%d-%m-%Y') AND STR_TO_DATE('$tDate','%d-%m-%Y')";
       if (strlen($clauseC) > 0 && strlen($clauseB) > 0){
          $clause = $clauseA.' AND '.$clauseB.' AND '. $clauseC ;
        }else if (strlen($clauseB) > 0){
          $clause = $clauseA.' AND '.$clauseB ;
        }else if (strlen($clauseC) > 0){
          $clause = $clauseA.' AND '.$clauseC ;
        }else {
          $clause = $clauseA;
        }
        $clause = str_replace("SONO","SONUM",$clause);
        $clause = str_replace("LINENO","LNNUM",$clause);
        $query =  "SELECT p.FLOOR , l.LNNUM , l.SONUM , l.BUYER , l.STYLE , l.COLOR , 
                          l.TOTQT AS PKTQT , l.FITQT AS PKFQT , l.DEFQT AS PKDQT, l.REJQT AS PKRQT , 
                          0 AS PDTQT , 0 AS PDFQT , 0 AS PDDQT , 0 AS PDRQT , 
                          TRIM(l.CBKEY) AS CBKEY
                  FROM 
                  ( 
                      SELECT LINENO AS LNNUM , SONO AS SONUM, BUYER , STYLE , COLOR,
                      SUM(CHECKED) AS TOTQT , SUM(FIT) AS FITQT  , SUM(DEF) AS DEFQT , SUM(REJ) AS REJQT , 
                      CONCAT(LINENO,'|',SONO,'|',BUYER,'|',STYLE,'|',COLOR) AS CBKEY
                      FROM 
                        ( SELECT LNNUM AS LINENO , SONUM AS SONO , BUYER , STYLE , COLOR,IFNULL(SUM(PDQTY),0) AS CHECKED,
                                IFNULL(SUM(CASE WHEN TYPEF ='FIT' THEN PDQTY END),0) AS FIT,  
                                IFNULL(SUM(CASE WHEN TYPEF ='DEF' THEN PDQTY END),0) AS DEF,
                                IFNULL(SUM(CASE WHEN TYPEF ='REJ' THEN PDQTY END),0) AS REJ 
                            FROM `tppk`
                            WHERE $clause
                            GROUP BY LNNUM , SONUM , BUYER , STYLE , TYPEF , COLOR 
                            ORDER BY LNNUM , SONUM , BUYER , STYLE , TYPEF , COLOR 
                        ) AS I GROUP BY LINENO , SONO , BUYER , STYLE , COLOR
                  ) AS l LEFT JOIN zpp_machine_mast AS p ON l.LNNUM = p.MNO " ;
         // prepare query statement
         $result = mysqli_query($db, $query);
         if (mysqli_num_rows($result)>0) { 
              while($row = mysqli_fetch_assoc($result)){
                  $finishingMaster[]=$row;
              }
              return $finishingMaster;
         } else {
              return '';
         }
      }
     function get_packingMasterDate($db,$clause) {
        $clause = str_replace("SONO","SONUM",$clause);
        $clause = str_replace("LINENO","LNNUM",$clause);

        $query =  "SELECT LINENO AS LNNUM , SONO AS SONUM, BUYER , STYLE , COLOR,
        SUM(CHECKED) AS PDTQT , SUM(FIT) AS PDFQT  , SUM(DEF) AS PDDQT , IFNULL(SUM(REJ),0) AS PDRQT , 
        TRIM(CONCAT(LINENO,'|',SONO,'|',BUYER,'|',STYLE,'|',COLOR)) AS CBKEY
        FROM 
        ( SELECT LNNUM AS LINENO , SONUM AS SONO , BUYER , STYLE , COLOR,IFNULL(SUM(PDQTY),0) AS CHECKED,
                IFNULL(SUM(CASE WHEN TYPEF ='FIT' THEN PDQTY END),0) AS FIT,  
                IFNULL(SUM(CASE WHEN TYPEF ='DEF' THEN PDQTY END),0) AS DEF,
                IFNULL(SUM(CASE WHEN TYPEF ='REJ' THEN PDQTY END),0) AS REJ 
            FROM `tppk`
            WHERE $clause
            GROUP BY LNNUM , SONUM , BUYER , STYLE , TYPEF , COLOR 
            ORDER BY LNNUM , SONUM , BUYER , STYLE , TYPEF , COLOR  
        ) AS I GROUP BY LINENO , SONO , BUYER , STYLE , COLOR " ;
        
        // prepare query statement
        $result = mysqli_query($db, $query);
        if (mysqli_num_rows($result)>0) { 
                while($row = mysqli_fetch_assoc($result)){
                    $finishingMaster[]=$row;
                }
                return $finishingMaster;
            } else {
                return '';
        }

      }


?>