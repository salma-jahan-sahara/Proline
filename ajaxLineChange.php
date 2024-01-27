<?php
session_start() ;

include('config.php') ;
$lineNo = $_SESSION['lineNo']; 

//sewing input dispaly based on line and date 
if( ($_POST["opid"]) == 1 ) {
    $_SESSION['tblname'] = 'inputdetails'; 
    $idate = date('d-m-Y', strtotime($_POST["idate"] ));
    $DOCNUM  = $_POST['DOCNUM'] ;
    $CHIS  = $_POST['CHIS'] ;
    $sql = '';
    $whClause = '';
    if ( strlen($DOCNUM) > 2 ) { 
        $DOCNUM  = $_POST['DOCNUM'];  
        $whClause = "LINENO = '$lineNo' AND QTY <> 0 AND  MBLNR = '$DOCNUM' AND  CREFID = 0";
       } else {
           if ( $CHIS == 1 ){
               $whClause =  "LINENO = '$lineNo' AND BUDAT = '$idate' AND CREFID != 0";
           } else {
                $whClause =  "LINENO = '$lineNo' AND BUDAT = '$idate' AND  CREFID = 0";
           }
                 
     }
    $sql = "SELECT E.TID , E.SONO , E.BUYER , E.STYLE , E.COLOR , E.SIZE , E.QTY , E.NOP , 
                 E.SYSDT , E.MBLNR , E.MJAHR , E.ZEILE ,  IFNULL(WIP,0) AS WIP , E.CREFID FROM 
                (SELECT TID , LINENO , SONO , BUYER , STYLE , COLOR , SIZE , QTY , NOP , SYSDT , MBLNR , MJAHR , ZEILE , CREFID
                FROM `inputdetails` WHERE $whClause ORDER BY TID DESC ) E
                LEFT JOIN 
                (SELECT LINENO , SONO , BUYER , STYLE , COLOR , SIZE , WIP FROM 
                (SELECT LINENO , SONO , BUYER , STYLE , COLOR , SIZE , QTY AS WIP  FROM 
                ( SELECT T.LINENO , T.SONO , T.BUYER , T.STYLE , T.COLOR , T.SIZE ,T.INQTY , IFNULL((T.INQTY - P.OTQTY ),T.INQTY) AS QTY FROM
                (SELECT LINENO,SONO , BUYER , STYLE , COLOR , SIZE , SUM(INQTY) AS INQTY FROM 
                (SELECT LINENO , SONO , BUYER , STYLE , COLOR , SIZE ,  NOP , QTY , round( QTY / NOP ) AS INQTY
                FROM `inputdetails` 
                WHERE LINENO = '$lineNo' ) INP
                GROUP BY LINENO, SONO , BUYER , STYLE , COLOR , SIZE ) AS T 
                LEFT JOIN 
                ( SELECT LINENO , SONO , BUYER , STYLE , COLOR , SIZE , SUM(QTY) AS  OTQTY
                FROM `outputdetails` 
                WHERE  LINENO = '$lineNo' AND TYPE = 'FIT'
                GROUP BY  LINENO , SONO , BUYER , STYLE , COLOR , SIZE , TYPE ) AS  P 
                ON   T.LINENO  = P.LINENO AND T.SONO  = P.SONO AND T.BUYER = P.BUYER AND T.STYLE = P.STYLE AND T.COLOR = P.COLOR AND  T.SIZE = P.SIZE ) R
                ) AS K) AS F
                ON F.LINENO  = E.LINENO AND F.SONO  = E.SONO AND F.BUYER = E.BUYER AND F.STYLE = E.STYLE AND F.COLOR = E.COLOR AND  F.SIZE = E.SIZE 
                ORDER BY E.TID DESC" ;

    $resultIN = $db->query($sql) ;
    if (mysqli_num_rows($resultIN)>0){ 
         $dataIN = array();
         while($rowIN = mysqli_fetch_array($resultIN)) {
            $dataIN[] = $rowIN;
         }
         $count = 0; 
         foreach( $dataIN as $res) {
                $count =  $count + 1 ;
            ?>
            <tr id = <?php echo $count ?>>
                <td> <?php echo $res['TID'] ?></td>
                <td> <?php echo $res['SONO'] ?></td>
                <td> <?php echo $res['BUYER'] ?></td>
                <td> <?php echo $res['STYLE'] ?></td>
                <td> <?php echo $res['COLOR'] ?></td>
                <td> <?php echo $res['SIZE'] ?></td>
                <?php $pcQty = round($res['QTY'] / $res['NOP'] ) ; ?>
                <td> <?php echo $pcQty ; ?></td>
                <td> <?php echo '-' ?></td>
                <td> <?php echo $res['NOP'] ?></td>
                <td> <?php echo $res['QTY'] ?></td>
                <td> <?php echo $res['SYSDT'] ?></td>
                <td> <?php echo $res['MBLNR'] ?></td>
                <td> <?php echo $res['MJAHR'] ?></td>
                <td> <?php echo $res['ZEILE'] ?></td>
                <td style="font-weight: bold;"> <?php echo $res['WIP'] ?></td>
                <td> <?php echo $res['CREFID'] ?></td>
                <td> <?php echo '-' ?></td>
            </tr>
            <?php
         }
      }

 }
//sewing output dispaly based on line and date 
if (($_POST["opid"]) == 2) {
    $_SESSION['tblname'] = 'outputdetails';
    $idate = date('d-m-Y', strtotime($_POST["idate"] ));
    $CHIS  = $_POST['CHIS'] ;
    $whClause = '';
    if ( $CHIS == 1 ){
        $whClause =  "LINENO = '$lineNo' AND BUDAT = '$idate' AND CREFID != 0 AND QTY <> 0 ";
    } else {
        $whClause =  "LINENO = '$lineNo' AND BUDAT = '$idate' AND  CREFID = 0 AND QTY <> 0 ";
    }

    $sql1 = "SELECT TID , SONO , BUYER , STYLE , COLOR , SIZE , QTY , TYPE , SYSDT ,  CREFID , CNTCC
    FROM `outputdetails`  WHERE $whClause ORDER BY TID DESC" ;
    $result1 = $db->query($sql1) ;
    $count1 = 0;
    if (mysqli_num_rows($result1)>0){
        while ($res1 = mysqli_fetch_array($result1))  {
            $count1 =  $count1 + 1 ;
        ?>
        <tr id = <?php  echo $count1 ?>>
            <td> <?php echo $res1['TID'] ?></td>
            <td> <?php echo $res1['SONO'] ?></td>
            <td> <?php echo $res1['BUYER'] ?></td>
            <td> <?php echo $res1['STYLE'] ?></td>
            <td> <?php echo $res1['COLOR'] ?></td>
            <td> <?php echo $res1['SIZE'] ?></td>
            <td> <?php echo $res1['QTY'] ?></td>
            <td> <?php echo $res1['TYPE'] ?></td>
            <td> <?php echo '-' ?></td>
            <td> <?php echo '-' ?></td>
            <td> <?php echo $res1['SYSDT'] ?></td>
            <td> <?php echo '-' ?></td>
            <td> <?php echo '-' ?></td>
            <td> <?php echo '-' ?></td>
            <td> <?php echo '-' ?></td>
            <td> <?php echo $res1['CREFID'] ?></td>
            <td> <?php echo $res1['CNTCC'] ?></td>
        </tr>
     <?php
        }
     }
 }

//finishing dispaly based on line and date 
if (($_POST["opid"]) == 3) {
    $_SESSION['tblname'] = 'tpfn';
    $idate = date('d-m-Y', strtotime($_POST["idate"] ));
    $CHIS  = $_POST['CHIS'] ;

    $whClause = '';
    if ( $CHIS == 1 ){
        $whClause =  "LNNUM = '$lineNo' AND BUDAT = '$idate' AND REFID != 0 AND PDQTY <> 0 ";
    } else {
        $whClause =  "LNNUM = '$lineNo' AND BUDAT = '$idate' AND REFID = 0 AND PDQTY <> 0 ";
    }

    $sql1 = "SELECT TXNID AS TID , SONUM AS SONO, BUYER , STYLE , COLOR , SIZEF AS SIZE , PDQTY AS QTY , TYPEF AS TYPE , SYSDT , REFID , CNTCC
    FROM `tpfn`  WHERE $whClause ORDER BY TID DESC " ;
    $result1 = $db->query($sql1) ;
    $count1 = 0;

    if (mysqli_num_rows($result1)>0){
        while ($res1 = mysqli_fetch_array($result1))  {
            $count1 =  $count1 + 1 ;
        ?>
        <tr id = <?php  echo $count1 ?>>
            <td> <?php echo $res1['TID'] ?></td>
            <td> <?php echo $res1['SONO'] ?></td>
            <td> <?php echo $res1['BUYER'] ?></td>
            <td> <?php echo $res1['STYLE'] ?></td>
            <td> <?php echo $res1['COLOR'] ?></td>
            <td> <?php echo $res1['SIZE'] ?></td>
            <td> <?php echo $res1['QTY'] ?></td>
            <td> <?php echo $res1['TYPE'] ?></td>
            <td> <?php echo '-' ?></td>
            <td> <?php echo '-' ?></td>
            <td> <?php echo $res1['SYSDT'] ?></td>
            <td> <?php echo '-' ?></td>
            <td> <?php echo '-' ?></td>
            <td> <?php echo '-' ?></td>
            <td> <?php echo '-' ?></td>
            <td><a href="#"> <?php echo $res1['REFID'] ?></a></td>
            <td> <?php echo $res1['CNTCC'] ?></td>

        </tr>
     <?php
        }
     }
 }
// line select
if( ($_POST["opid"]) == 31 ) {
    $sql2 = "SELECT * FROM `zpp_machine_mast`";
    // for method 1
    $result2 = $db->query($sql2) ;
    $options = "";
    if (mysqli_num_rows($result2)>0){
        while ($res2 = mysqli_fetch_array($result2)) {
        ?>
            <option> <?php echo $res2['MNO'] ?> </option>
     <?php
     }
    }
 }
// size select based on so
if( ($_POST["opid"]) == 32 ) {
    $mso = $_POST["sono"];
    $sql3 = " SELECT DISTINCT SIZE FROM `inputdetails` WHERE sono = $mso ";
    $result3 = $db->query($sql3) ;
    if (mysqli_num_rows($result3)>0){
        while ($res2 = mysqli_fetch_array($result3)) {
        ?>
            <option> <?php echo $res2['SIZE'] ?> </option>
     <?php
     }
    }
 }
// color select based on so
if( ($_POST["opid"]) == 34 ) {
    $mso = $_POST["sono"];
    $sql34 = " SELECT DISTINCT COLOR FROM `inputdetails` WHERE sono = $mso ";
    $result34 = $db->query($sql34) ;
    if (mysqli_num_rows($result34)>0){
        while ($res24 = mysqli_fetch_array($result34)) {
        ?>
            <option> <?php echo $res24['COLOR'] ?> </option>
     <?php
     }
    }
 }
//server date
if( ($_POST["opid"]) == 33 ) {

    $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
    $dt_day = date_format($dt, 'd-m-Y');
    $idate = $dt_day;
    echo $idate ;
 }

//all change
if( ($_POST["opid"]) == 21 ) {
        //varaible 
            $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
            $dt_day = date_format($dt, 'd-m-Y');
            $BUDAT  = $dt_day ;
            //original source 
            $OQTY = '-'.$_POST["OQTY"] ;
            $OSZE = $_POST["OSZE"] ;
            $OCLR = $_POST["OCLR"] ;
            $OSTY = $_POST["OSTY"] ;
            $OBUY = $_POST["OBUY"] ;
            $OSNO = $_POST["OSNO"] ;
            $OLIN = $_POST["OLIN"] ;
            $OUID = $_POST["OUID"] ;
            $NOP =  $_POST["ONOP"] ;
            $UPQTY1 = $OQTY ;
            $UPTIME = date_format($dt, 'Ymd').'!'.date_format($dt, 'his'); 
            $OTYP = $_POST["OTYP"] ;
            //to change
            $mtid = intval($_POST["mtid"]);  
            $mlineno = $_POST["mlineno"];
            $mmSO    = $_POST["mmSO"];
            $TOBY    = $_POST["TOBY"];
            $TOSY    = $_POST["TOSY"];
            $mmColor = $_POST["mmColor"];
            $msize   = $_POST["msize"];
            $mqty    =  intval($_POST["mqty"]);
            $UPQTY   = $mqty ;
            $TONP    =  intval($_POST["TONP"]);
        //table variable
         $tablename = $_SESSION['tblname'];
         switch ($tablename) {
            case 'inputdetails':
                // source reduce and insert 
                $sql = "INSERT INTO `inputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                         `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`NOP`,`UPQTY`,`UPTIME`,`CREFID`) 
                         VALUES ('{$BUDAT}','{$OLIN}','{$OSNO}','{$OBUY}',
                         '{$OSTY}','{$OCLR}','{$OSZE}',-'{$mqty}','{$OUID}','{$NOP}','{$UPQTY1}','{$UPTIME}','{$mtid}')";
                $result = $db->query($sql) ;
                if ($result === TRUE) {
                // to insert
                $sqlTO = "INSERT INTO `inputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                            `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`NOP`,`UPQTY`,`UPTIME`,`CREFID`) 
                        VALUES ('{$BUDAT}','{$mlineno}','{$mmSO}','{$TOBY}',
                            '{$TOSY}','{$mmColor}','{$msize}','{$mqty}','{$OUID}','{$TONP}','{$UPQTY}','{$UPTIME}','{$mtid}')";
                $resultTO = $db->query($sqlTO) ;
                    if ( $resultTO === TRUE ) {
                        echo " Data changed successfully <br />\n";
                    } else {
                        echo "Error:at to" ;
                    }
                } else {
                     echo "Error:at source" ;
                }
             break;
            case 'outputdetails':
                $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
                $dt = date_format($dt, 'd/m/Y H:i:s');
                $SBUDDAT = $dt ;

                $TYPE = $OTYP;
                //source date pick up 
                $BUDATOLD = '';
                $SBUDDATOLD = '';
                $sqlold = "SELECT BUDAT , SBUDDAT FROM `outputdetails` WHERE TID = '$mtid' ";
                $resultold = $db->query($sqlold) ;
                if (mysqli_num_rows($resultold)>0){
                    while ($resold = mysqli_fetch_array($resultold)) {
                        $BUDATOLD = $resold['BUDAT'] ;
                        $SBUDDATOLD= $resold['SBUDDAT'] ;
                        } 
                    } else {
                        $BUDATOLD = $BUDAT ;
                        $SBUDDATOLD = $SBUDDAT ; 
                    } 
                // source reduce and insert 
                $sql = "INSERT INTO `outputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                         `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`SBUDDAT`,`TYPE`,`CREFID`) 
                         VALUES ('{$BUDATOLD}','{$OLIN}','{$OSNO}','{$OBUY}',
                         '{$OSTY}','{$OCLR}','{$OSZE}',-'{$mqty}','{$OUID}','{$SBUDDATOLD}','{$TYPE}','{$mtid}')";
                $result = $db->query($sql) ;
                if ($result === TRUE) {
                // to insert
                $sqlTO = "INSERT INTO `outputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                         `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`SBUDDAT`,`TYPE`,`CREFID`) 
                         VALUES ('{$BUDAT}','{$mlineno}','{$mmSO}','{$TOBY}',
                         '{$TOSY}','{$mmColor}','{$msize}','{$mqty}','{$OUID}','{$SBUDDAT}','{$TYPE}','{$mtid}')";
                $resultTO = $db->query($sqlTO) ;
                    if ( $resultTO === TRUE ) {
                        echo "Data changed successfully at sewing output<br />\n";
                    } else {
                        echo "Error:at to sewing output" ;
                    }
                } else {
                        echo "Error:at source sewing output" ;
                }
             break;
            case 'tpfn':
                $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
                $dt = date_format($dt, 'd/m/Y H:i:s');
                $SBUDDAT = $dt ;

                $TYPE = $OTYP;
                //source date pick up 
                $BUDATOLD = '';
                $sqlold = "SELECT BUDAT  FROM `tpfn` WHERE TXNID = '$mtid' ";
                $resultold = $db->query($sqlold) ;
                if (mysqli_num_rows($resultold)>0){
                    while ($resold = mysqli_fetch_array($resultold)) {
                        $BUDATOLD = $resold['BUDAT'] ;
                        } 
                    } else {
                        $BUDATOLD = $BUDAT ;
                    } 
                // source reduce and insert 
                $sql = "INSERT INTO `tpfn` (`BUDAT`, `LNNUM`, `SONUM`, `BUYER`,
                         `STYLE`, `COLOR`, `SIZEF`, `PDQTY`, `USRID`,`TYPEF`,`REFID`) 
                         VALUES ('{$BUDATOLD}','{$OLIN}','{$OSNO}','{$OBUY}',
                         '{$OSTY}','{$OCLR}','{$OSZE}',-'{$mqty}','{$OUID}','{$TYPE}','{$mtid}')";
                $result = $db->query($sql) ;
                if ($result === TRUE) {
                    // to insert
                    $sqlTO = "INSERT INTO `tpfn` (`BUDAT`, `LNNUM`, `SONUM`, `BUYER`,
                            `STYLE`, `COLOR`, `SIZEF`, `PDQTY`, `USRID`,`TYPEF`,`REFID`) 
                            VALUES ('{$BUDAT}','{$mlineno}','{$mmSO}','{$TOBY}',
                            '{$TOSY}','{$mmColor}','{$msize}','{$mqty}','{$OUID}','{$TYPE}','{$mtid}')";
                    $resultTO = $db->query($sqlTO) ;
                        if ( $resultTO === TRUE ) {
                            echo "Data changed successfully at finishing .<br />\n";
                        } else {
                            echo "Error:at to finishing" ;
                        }
                } else {
                        echo "Error:at source finishing" ;
                }
             break;
            default:
              echo ('Error.check internet connection all change.');
          } 
 }

// line change 
if( ($_POST["opid"]) == 24 ) {
            //varaible declation 
                $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
                $dt_day = date_format($dt, 'd-m-Y');
                $BUDAT  = $dt_day ;
                //original source 
                $OQTY = '-'.$_POST["OQTY"] ;
                $OSZE = $_POST["OSZE"] ;
                $OCLR = $_POST["OCLR"] ;
                $OSTY = $_POST["OSTY"] ;
                $OBUY = $_POST["OBUY"] ;
                $OSNO = $_POST["OSNO"] ;
                $OLIN = $_POST["OLIN"] ;
                $OUID = $_POST["OUID"] ;
                $NOP =  $_POST["ONOP"] ;
                $UPQTY1 = $OQTY ;
                $UPTIME = date_format($dt, 'Ymd').'!'.date_format($dt, 'his'); 
                $OTYP = $_POST["OTYP"] ;
                //to change
                $mtid = intval($_POST["mtid"]);  
                $mlineno = $_POST["mlineno"];
                $mqty  = intval($_POST["OQTY"]);
                $UPQTY = $mqty ;

            $tablename = $_SESSION['tblname'];
            switch($tablename){
                case 'inputdetails':
                     // source reduce and insert 
                    $sql = "INSERT INTO `inputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                            `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`NOP`,`UPQTY`,`UPTIME`,`CREFID`) 
                            VALUES ('{$BUDAT}','{$OLIN}','{$OSNO}','{$OBUY}',
                            '{$OSTY}','{$OCLR}','{$OSZE}','{$OQTY}','{$OUID}','{$NOP}','{$UPQTY1}','{$UPTIME}','{$mtid}')";
                    $result = $db->query($sql) ;
                        if ($result === TRUE) {
                            // to insert
                            $sqlTO = "INSERT INTO `inputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                                    `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`NOP`,`UPQTY`,`UPTIME`,`CREFID`) 
                                    VALUES ('{$BUDAT}','{$mlineno}','{$OSNO}','{$OBUY}',
                                    '{$OSTY}','{$OCLR}','{$OSZE}','{$mqty}','{$OUID}','{$NOP}','{$UPQTY}','{$UPTIME}','{$mtid}')";
                            $resultTO = $db->query($sqlTO) ;
                            if ( $resultTO === TRUE ) {
                                echo " LINE : changed successfully at sewing input <br />\n";
                            } else {
                                echo "Error:at to line change at sewing input. " ;
                            }
                        } else {
                            echo "Error:at source line change at sewing input." ;
                        }
                    break;
                case 'outputdetails':
                    $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
                    $dt = date_format($dt, 'd/m/Y H:i:s');
                    $SBUDDAT = $dt ;
    
                    $TYPE = $OTYP;
                    //source date pick up 
                    $BUDATOLD = '';
                    $SBUDDATOLD = '';
                    $sqlold = "SELECT BUDAT , SBUDDAT FROM `outputdetails` WHERE TID = '$mtid' ";
                    $resultold = $db->query($sqlold) ;
                    if (mysqli_num_rows($resultold)>0){
                        while ($resold = mysqli_fetch_array($resultold)) {
                            $BUDATOLD = $resold['BUDAT'] ;
                            $SBUDDATOLD= $resold['SBUDDAT'] ;
                            } 
                        } else {
                            $BUDATOLD = $BUDAT ;
                            $SBUDDATOLD = $SBUDDAT ; 
                        } 
                    // source reduce and insert 
                    $sql = "INSERT INTO `outputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                             `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`SBUDDAT`,`TYPE`,`CREFID`) 
                             VALUES ('{$BUDATOLD}','{$OLIN}','{$OSNO}','{$OBUY}',
                             '{$OSTY}','{$OCLR}','{$OSZE}','{$OQTY}','{$OUID}','{$SBUDDATOLD}','{$TYPE}','{$mtid}')";
                    $result = $db->query($sql) ;
                    if ($result === TRUE) {
                    // to insert
                    $sqlTO = "INSERT INTO `outputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                            `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`SBUDDAT`,`TYPE`,`CREFID`) 
                            VALUES ('{$BUDAT}','{$mlineno}','{$OSNO}','{$OBUY}',
                            '{$OSTY}','{$OCLR}','{$OSZE}','{$mqty}','{$OUID}','{$SBUDDAT}','{$TYPE}','{$mtid}')";
                    $resultTO = $db->query($sqlTO) ;
                        if ( $resultTO === TRUE ) {
                            echo "Line: changed successfully at sewing output<br />\n";
                        } else {
                            echo "Error:at line changed to sewing output" ;
                        }
                    } else {
                            echo "Error:at line changed source sewing output" ;
                    }                   
                 break;
                case 'tpfn':
                    $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
                    $dt = date_format($dt, 'd/m/Y H:i:s');
                    $SBUDDAT = $dt ;
    
                    $TYPE = $OTYP;
                    //source date pick up 
                    $BUDATOLD = '';
                    $sqlold = "SELECT BUDAT  FROM `tpfn` WHERE TXNID = '$mtid' ";
                    $resultold = $db->query($sqlold) ;
                    if (mysqli_num_rows($resultold)>0){
                        while ($resold = mysqli_fetch_array($resultold)) {
                            $BUDATOLD = $resold['BUDAT'] ;
                            } 
                        } else {
                            $BUDATOLD = $BUDAT ;
                        } 
                    // source reduce and insert 
                    $sql = "INSERT INTO `tpfn` (`BUDAT`, `LNNUM`, `SONUM`, `BUYER`,
                             `STYLE`, `COLOR`, `SIZEF`, `PDQTY`, `USRID`,`TYPEF`,`REFID`) 
                             VALUES ('{$BUDATOLD}','{$OLIN}','{$OSNO}','{$OBUY}',
                             '{$OSTY}','{$OCLR}','{$OSZE}','{$OQTY}','{$OUID}','{$TYPE}','{$mtid}')";
                    $result = $db->query($sql) ;
                    if ($result === TRUE) {
                    // to insert
                    $sqlTO = "INSERT INTO `tpfn` (`BUDAT`, `LNNUM`, `SONUM`, `BUYER`,
                            `STYLE`, `COLOR`, `SIZEF`, `PDQTY`, `USRID`,`TYPEF`,`REFID`) 
                            VALUES ('{$BUDAT}','{$mlineno}','{$OSNO}','{$OBUY}',
                            '{$OSTY}','{$OCLR}','{$OSZE}','{$mqty}','{$OUID}','{$TYPE}','{$mtid}')";
                    $resultTO = $db->query($sqlTO) ;
                        if ( $resultTO === TRUE ) {
                            echo "Line changed successfully at finishing.<br />\n";
                        } else {
                            echo "Error:at line changedd to finishing." ;
                        }
                    } else {
                            echo "Error:at line changed source finishing." ;
                    }
                 break;
                default:
                  echo 'Error.Check internet connection.';
            }
 }
// so , color & size change
if( ($_POST["opid"]) == 26 ) {
    //variable declartion
        $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
        $dt_day = date_format($dt, 'd-m-Y');
        $BUDAT  = $dt_day ;
        //original source 
        $OQTY = '-'.$_POST["OQTY"] ;
        $OSZE = $_POST["OSZE"] ;
        $OCLR = $_POST["OCLR"] ;
        $OSTY = $_POST["OSTY"] ;
        $OBUY = $_POST["OBUY"] ;
        $OSNO = $_POST["OSNO"] ;
        $OLIN = $_POST["OLIN"] ;
        $OUID = $_POST["OUID"] ;
        $NOP =  $_POST["ONOP"] ;
        $UPQTY1 = $OQTY ;
        $UPTIME = date_format($dt, 'Ymd').'!'.date_format($dt, 'his');
        $OTYP = $_POST["OTYP"] ; 
        //to change
        $mtid = intval($_POST["mtid"]);
        $mmSO = $_POST["mmSO"];  
        $TOBY = $_POST["TOBY"];
        $TOSY = $_POST["TOSY"];
        $mmColor = $_POST["mmColor"];
        $msize   = $_POST["msize"];
        $TONP = intval($_POST["TONP"]);
        $mqty = intval($_POST["OQTY"]);
        $UPQTY = $mqty ;

    $tablename = $_SESSION['tblname'];
    switch($tablename){
        case 'inputdetails':
            // source reduce and insert 
            $sql = "INSERT INTO `inputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                    `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`NOP`,`UPQTY`,`UPTIME`,`CREFID`) 
                    VALUES ('{$BUDAT}','{$OLIN}','{$OSNO}','{$OBUY}',
                    '{$OSTY}','{$OCLR}','{$OSZE}','{$OQTY}','{$OUID}','{$NOP}','{$UPQTY1}','{$UPTIME}','{$mtid}')";
            $result = $db->query($sql) ;
            if ($result === TRUE) {
                // to insert
                $sqlTO = "INSERT INTO `inputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                        `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`NOP`,`UPQTY`,`UPTIME`,`CREFID`) 
                        VALUES ('{$BUDAT}','{$OLIN}','{$mmSO}','{$TOBY}',
                        '{$TOSY}','{$mmColor}','{$msize}','{$mqty}','{$OUID}','{$TONP}','{$UPQTY}','{$UPTIME}','{$mtid}')";
                $resultTO = $db->query($sqlTO) ;
                if ( $resultTO === TRUE ) {
                    echo " SO(color&size) : changed successfully at sewing input. <br />\n";
                } else {
                    echo "Error:at to so(color&size) change at sewing input." ;
                }
            } else {
                echo "Error:at source so(color&size) change at sewing input." ;
            }
             break;
        case 'outputdetails':
            $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
            $dt = date_format($dt, 'd/m/Y H:i:s');
            $SBUDDAT = $dt ;

            $TYPE = $OTYP;
            //source date pick up 
            $BUDATOLD = '';
            $SBUDDATOLD = '';
            $sqlold = "SELECT BUDAT , SBUDDAT FROM `outputdetails` WHERE TID = '$mtid' ";
            $resultold = $db->query($sqlold) ;
               if (mysqli_num_rows($resultold)>0){
                   while ($resold = mysqli_fetch_array($resultold)) {
                      $BUDATOLD = $resold['BUDAT'] ;
                      $SBUDDATOLD= $resold['SBUDDAT'] ;
                    } 
                } else {
                    $BUDATOLD = $BUDAT ;
                    $SBUDDATOLD = $SBUDDAT ; 
                } 
            // source reduce and insert 
            $sql = "INSERT INTO `outputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                     `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`SBUDDAT`,`TYPE`,`CREFID`) 
                     VALUES ('{$BUDATOLD}','{$OLIN}','{$OSNO}','{$OBUY}',
                     '{$OSTY}','{$OCLR}','{$OSZE}','{$OQTY}','{$OUID}','{$SBUDDATOLD}','{$TYPE}','{$mtid}')";
            $result = $db->query($sql) ;
            if ($result === TRUE) {
            // to insert
            $sqlTO = "INSERT INTO `outputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                     `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`SBUDDAT`,`TYPE`,`CREFID`) 
                     VALUES ('{$BUDAT}','{$OLIN}','{$mmSO}','{$TOBY}',
                     '{$TOSY}','{$mmColor}','{$msize}','{$mqty}','{$OUID}','{$SBUDDAT}','{$TYPE}','{$mtid}')";
            $resultTO = $db->query($sqlTO) ;
                if ( $resultTO === TRUE ) {
                    echo "SO(color&size) : changed successfully at sewing output.<br />\n";
                } else {
                    echo "Error:at to so(color&size) change at sewing output." ;
                }
            } else {
                    echo "Error:at source so(color&size) change at sewing output." ;
            }
         break;
         break;
        case 'tpfn':
            $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
            $dt = date_format($dt, 'd/m/Y H:i:s');
            $SBUDDAT = $dt ;

            $TYPE = $OTYP;
            //source date pick up 
             $BUDATOLD = '';
             $sqlold = "SELECT BUDAT  FROM `tpfn` WHERE TXNID = '$mtid' ";
             $resultold = $db->query($sqlold) ;
                    if (mysqli_num_rows($resultold)>0){
                        while ($resold = mysqli_fetch_array($resultold)) {
                            $BUDATOLD = $resold['BUDAT'] ;
                            } 
                        } else {
                            $BUDATOLD = $BUDAT ;
                        } 
            // source reduce and insert 
            $sql = "INSERT INTO `tpfn` (`BUDAT`, `LNNUM`, `SONUM`, `BUYER`,
                     `STYLE`, `COLOR`, `SIZEF`, `PDQTY`, `USRID`,`TYPEF`,`REFID`) 
                     VALUES ('{$BUDATOLD}','{$OLIN}','{$OSNO}','{$OBUY}',
                     '{$OSTY}','{$OCLR}','{$OSZE}','{$OQTY}','{$OUID}','{$TYPE}','{$mtid}')";
            $result = $db->query($sql) ;
            if ($result === TRUE) {
            // to insert
            $sqlTO = "INSERT INTO `tpfn` (`BUDAT`, `LNNUM`, `SONUM`, `BUYER`,
                     `STYLE`, `COLOR`, `SIZEF`, `PDQTY`, `USRID`,`TYPEF`,`REFID`) 
                     VALUES ('{$BUDAT}','{$OLIN}','{$mmSO}','{$TOBY}',
                     '{$TOSY}','{$mmColor}','{$msize}','{$mqty}','{$OUID}','{$TYPE}','{$mtid}')";
            $resultTO = $db->query($sqlTO) ;
                if ( $resultTO === TRUE ) {
                    echo "SO(color&size) : changed successfully at finishing.<br />\n";
                } else {
                    echo "Error:at to so(color&size) change at finishing." ;
                }
            } else {
                    echo "Error:at source so(color&size) change at finishing." ;
            }
          break;
        default:
            echo 'Error.Check internet connection.';
    }
 }
//color change 
if( ($_POST["opid"]) == 25 ) {
    //variable declation 
        $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
        $dt_day = date_format($dt, 'd-m-Y');
        $BUDAT  = $dt_day ;
        //original source 
        $OQTY = '-'.$_POST["OQTY"] ;
        $OSZE = $_POST["OSZE"] ;
        $OCLR = $_POST["OCLR"] ;
        $OSTY = $_POST["OSTY"] ;
        $OBUY = $_POST["OBUY"] ;
        $OSNO = $_POST["OSNO"] ;
        $OLIN = $_POST["OLIN"] ;
        $OUID = $_POST["OUID"] ;
        $NOP =  $_POST["ONOP"] ;
        $UPQTY1 = $OQTY ;
        $UPTIME = date_format($dt, 'Ymd').'!'.date_format($dt, 'his'); 
        $OTYP = $_POST["OTYP"] ;
        //to change
        $mtid = intval($_POST["mtid"]);
        $mmColor = $_POST["mmColor"];
        $mqty = intval($_POST["OQTY"]);
        $UPQTY = $mqty ;

    $tablename = $_SESSION['tblname'];
    switch($tablename){
        case 'inputdetails':
            // source reduce and insert 
            $sql = "INSERT INTO `inputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                     `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`NOP`,`UPQTY`,`UPTIME`,`CREFID`) 
                     VALUES ('{$BUDAT}','{$OLIN}','{$OSNO}','{$OBUY}',
                     '{$OSTY}','{$OCLR}','{$OSZE}','{$OQTY}','{$OUID}','{$NOP}','{$UPQTY1}','{$UPTIME}','{$mtid}')";
            $result = $db->query($sql) ;
            if ($result === TRUE) {
            // to insert
            $sqlTO = "INSERT INTO `inputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                    `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`NOP`,`UPQTY`,`UPTIME`,`CREFID`) 
                    VALUES ('{$BUDAT}','{$OLIN}','{$OSNO}','{$OBUY}',
                    '{$OSTY}','{$mmColor}','{$OSZE}','{$mqty}','{$OUID}','{$NOP}','{$UPQTY}','{$UPTIME}','{$mtid}')";
            $resultTO = $db->query($sqlTO) ;
            if ( $resultTO === TRUE ) {
                echo " color : changed successfully <br />\n";
            } else {
                echo "Error:at to color change" ;
            }
            } else {
            echo "Error:at source color change." ;
            }
         break;
        case 'outputdetails':
            $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
            $dt = date_format($dt, 'd/m/Y H:i:s');
            $SBUDDAT = $dt ;

            $TYPE = $OTYP;

            //source date pick up 
            $BUDATOLD = '';
            $SBUDDATOLD = '';
            $sqlold = "SELECT BUDAT , SBUDDAT FROM `outputdetails` WHERE TID = '$mtid' ";
            $resultold = $db->query($sqlold) ;
               if (mysqli_num_rows($resultold)>0){
                   while ($resold = mysqli_fetch_array($resultold)) {
                      $BUDATOLD = $resold['BUDAT'] ;
                      $SBUDDATOLD= $resold['SBUDDAT'] ;
                    } 
                } else {
                    $BUDATOLD = $BUDAT ;
                    $SBUDDATOLD = $SBUDDAT ; 
                } 
            // source reduce and insert 
            $sql = "INSERT INTO `outputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                     `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`SBUDDAT`,`TYPE`,`CREFID`) 
                     VALUES ('{$BUDATOLD}','{$OLIN}','{$OSNO}','{$OBUY}',
                     '{$OSTY}','{$OCLR}','{$OSZE}','{$OQTY}','{$OUID}','{$SBUDDATOLD}','{$TYPE}','{$mtid}')";
            $result = $db->query($sql) ;
            if ($result === TRUE) {
            // to insert
            $sqlTO = "INSERT INTO `outputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                     `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`SBUDDAT`,`TYPE`,`CREFID`) 
                     VALUES ('{$BUDAT}','{$OLIN}','{$OSNO}','{$OBUY}',
                     '{$OSTY}','{$mmColor}','{$OSZE}','{$mqty}','{$OUID}','{$SBUDDAT}','{$TYPE}','{$mtid}')";
            $resultTO = $db->query($sqlTO) ;
                if ( $resultTO === TRUE ) {
                    echo "Color : changed successfully at sewing output.<br />\n";
                } else {
                    echo "Error:at to color change at sewing output." ;
                }
            } else {
                    echo "Error:at source color change at sewing output." ;
            }
         break;
        case 'tpfn':
            $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
            $dt = date_format($dt, 'd/m/Y H:i:s');
            $SBUDDAT = $dt ;

            $TYPE = $OTYP;
                        //source date pick up 
                        $BUDATOLD = '';
                        $sqlold = "SELECT BUDAT  FROM `tpfn` WHERE TXNID = '$mtid' ";
                        $resultold = $db->query($sqlold) ;
                               if (mysqli_num_rows($resultold)>0){
                                   while ($resold = mysqli_fetch_array($resultold)) {
                                       $BUDATOLD = $resold['BUDAT'] ;
                                       } 
                                   } else {
                                       $BUDATOLD = $BUDAT ;
                                   }
            // source reduce and insert 
            $sql = "INSERT INTO `tpfn` (`BUDAT`, `LNNUM`, `SONUM`, `BUYER`,
                     `STYLE`, `COLOR`, `SIZEF`, `PDQTY`, `USRID`,`TYPEF`,`REFID`) 
                     VALUES ('{$BUDATOLD}','{$OLIN}','{$OSNO}','{$OBUY}',
                     '{$OSTY}','{$OCLR}','{$OSZE}','{$OQTY}','{$OUID}','{$TYPE}','{$mtid}')";
            $result = $db->query($sql) ;
            if ($result === TRUE) {
            // to insert
            $sqlTO = "INSERT INTO `tpfn` (`BUDAT`, `LNNUM`, `SONUM`, `BUYER`,
                     `STYLE`, `COLOR`, `SIZEF`, `PDQTY`, `USRID`,`TYPEF`,`REFID`) 
                     VALUES ('{$BUDAT}','{$OLIN}','{$OSNO}','{$OBUY}',
                     '{$OSTY}','{$mmColor}','{$OSZE}','{$mqty}','{$OUID}','{$TYPE}','{$mtid}')";
            $resultTO = $db->query($sqlTO) ;
                if ( $resultTO === TRUE ) {
                    echo "Color : changed successfully at finishing.<br />\n";
                } else {
                    echo "Error:at to color change at finishing." ;
                }
            } else {
                    echo "Error:at source color change at finishing." ;
            }   
         break;
        default:
          echo 'Error.Check internet connection.';
    }
 }
//size change 
if( ($_POST["opid"]) == 23 ) {
    //varaible declaration 
        $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
        $dt_day = date_format($dt, 'd-m-Y');
        $BUDAT  = $dt_day ;
        //original source 
        $OQTY = '-'.$_POST["OQTY"] ;
        $OSZE = $_POST["OSZE"] ;
        $OCLR = $_POST["OCLR"] ;
        $OSTY = $_POST["OSTY"] ;
        $OBUY = $_POST["OBUY"] ;
        $OSNO = $_POST["OSNO"] ;
        $OLIN = $_POST["OLIN"] ;
        $OUID = $_POST["OUID"] ;
        $NOP =  $_POST["ONOP"] ;
        $UPQTY1 = $OQTY ;
        $UPTIME = date_format($dt, 'Ymd').'!'.date_format($dt, 'his'); 
        $OTYP = $_POST["OTYP"] ;
        //to change
        $mtid = intval($_POST["mtid"]);
        $msize   = $_POST["msize"];
        $mqty = intval($_POST["OQTY"]);
        $UPQTY = $mqty ;

    $tablename = $_SESSION['tblname'];
    switch($tablename){
        case 'inputdetails':
             // source reduce and insert 
            $sql = "INSERT INTO `inputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                    `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`NOP`,`UPQTY`,`UPTIME`,`CREFID`) 
                    VALUES ('{$BUDAT}','{$OLIN}','{$OSNO}','{$OBUY}',
                    '{$OSTY}','{$OCLR}','{$OSZE}','{$OQTY}','{$OUID}','{$NOP}','{$UPQTY1}','{$UPTIME}','{$mtid}')";
            $result = $db->query($sql) ;
            if ($result === TRUE) {
                // to insert
                $sqlTO = "INSERT INTO `inputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                        `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`NOP`,`UPQTY`,`UPTIME`,`CREFID`) 
                        VALUES ('{$BUDAT}','{$OLIN}','{$OSNO}','{$OBUY}',
                        '{$OSTY}','{$OCLR}','{$msize}','{$mqty}','{$OUID}','{$NOP}','{$UPQTY}','{$UPTIME}','{$mtid}')";
                $resultTO = $db->query($sqlTO) ;
                if ( $resultTO === TRUE ) {
                    echo " size : changed successfully <br />\n";
                } else {
                    echo "Error:at to size change" ;
                }
            } else {
                    echo "Error:at source size change." ;
            }
         break;
        case 'outputdetails':
            $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
            $dt = date_format($dt, 'd/m/Y H:i:s');
            $SBUDDAT = $dt ;

            $TYPE = $OTYP;
            //source date pick up 
            $BUDATOLD = '';
            $SBUDDATOLD = '';
            $sqlold = "SELECT BUDAT , SBUDDAT FROM `outputdetails` WHERE TID = '$mtid' ";
            $resultold = $db->query($sqlold) ;
               if (mysqli_num_rows($resultold)>0){
                   while ($resold = mysqli_fetch_array($resultold)) {
                      $BUDATOLD = $resold['BUDAT'] ;
                      $SBUDDATOLD= $resold['SBUDDAT'] ;
                    } 
                } else {
                    $BUDATOLD = $BUDAT ;
                    $SBUDDATOLD = $SBUDDAT ; 
                } 
            // source reduce and insert 
            $sql = "INSERT INTO `outputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                     `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`SBUDDAT`,`TYPE`,`CREFID`) 
                     VALUES ('{$BUDATOLD}','{$OLIN}','{$OSNO}','{$OBUY}',
                     '{$OSTY}','{$OCLR}','{$OSZE}','{$OQTY}','{$OUID}','{$SBUDDATOLD}','{$TYPE}','{$mtid}')";
            $result = $db->query($sql) ;
            if ($result === TRUE) {
            // to insert
            $sqlTO = "INSERT INTO `outputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                     `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`SBUDDAT`,`TYPE`,`CREFID`) 
                     VALUES ('{$BUDAT}','{$OLIN}','{$OSNO}','{$OBUY}',
                     '{$OSTY}','{$OCLR}','{$msize}','{$mqty}','{$OUID}','{$SBUDDAT}','{$TYPE}','{$mtid}')";
            $resultTO = $db->query($sqlTO) ;
                if ( $resultTO === TRUE ) {
                    echo "Size : changed successfully at sewing output.";
                } else {
                    echo "Error:at to size change at sewing output." ;
                }
            } else {
                    echo "Error:at source size change at sewing output." ;
            }
            //todo
         break;
        case 'tpfn':
            $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
            $dt = date_format($dt, 'd/m/Y H:i:s');
            $SBUDDAT = $dt ;

            $TYPE = $OTYP;
                        //source date pick up 
                        $BUDATOLD = '';
                        $sqlold = "SELECT BUDAT  FROM `tpfn` WHERE TXNID = '$mtid' ";
                        $resultold = $db->query($sqlold) ;
                               if (mysqli_num_rows($resultold)>0){
                                   while ($resold = mysqli_fetch_array($resultold)) {
                                       $BUDATOLD = $resold['BUDAT'] ;
                                       } 
                                   } else {
                                       $BUDATOLD = $BUDAT ;
                                   }
            // source reduce and insert 
            $sql = "INSERT INTO `tpfn` (`BUDAT`, `LNNUM`, `SONUM`, `BUYER`,
                     `STYLE`, `COLOR`, `SIZEF`, `PDQTY`, `USRID`,`TYPEF`,`REFID`) 
                     VALUES ('{$BUDATOLD}','{$OLIN}','{$OSNO}','{$OBUY}',
                     '{$OSTY}','{$OCLR}','{$OSZE}','{$OQTY}','{$OUID}','{$TYPE}','{$mtid}')";
            $result = $db->query($sql) ;
            if ($result === TRUE) {
            // to insert
            $sqlTO = "INSERT INTO `tpfn` (`BUDAT`, `LNNUM`, `SONUM`, `BUYER`,
                     `STYLE`, `COLOR`, `SIZEF`, `PDQTY`, `USRID`,`TYPEF`,`REFID`) 
                     VALUES ('{$BUDAT}','{$OLIN}','{$OSNO}','{$OBUY}',
                     '{$OSTY}','{$OCLR}','{$msize}','{$mqty}','{$OUID}','{$TYPE}','{$mtid}')";
            $resultTO = $db->query($sqlTO) ;
                if ( $resultTO === TRUE ) {
                    echo "Size : changed successfully at finishing.<br />\n";
                } else {
                    echo "Error:at to size change at finishing." ;
                }
            } else {
                    echo "Error:at source size change at finishing." ;
            }
         break;
        default:
          echo 'Error.Check internet connection.';
    }
 }
// qty change                                                                                            
if( ($_POST["opid"]) == 22 ) {
        //varaible declaration
            $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
            $dt_day = date_format($dt, 'd-m-Y');
            $BUDAT  = $dt_day ;
            //original source 
            $OQTY = '-'.$_POST["OQTY"] ;
            $OSZE = $_POST["OSZE"] ;
            $OCLR = $_POST["OCLR"] ;
            $OSTY = $_POST["OSTY"] ;
            $OBUY = $_POST["OBUY"] ;
            $OSNO = $_POST["OSNO"] ;
            $OLIN = $_POST["OLIN"] ;
            $OUID = $_POST["OUID"] ;
            $NOP =  $_POST["ONOP"] ;
            $UPQTY1 = $OQTY ;
            $UPTIME = date_format($dt, 'Ymd').'!'.date_format($dt, 'his'); 
            $OTYP = $_POST["OTYP"] ;
            //to change
            $mtid = intval($_POST["mtid"]);
            $mqty = intval($_POST["mqty"]);
            $UPQTY = $mqty ;

     $tablename = $_SESSION['tblname'];
     switch($tablename){
         case 'inputdetails':
             // source reduce and insert 
            $sql = "INSERT INTO `inputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                    `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`NOP`,`UPQTY`,`UPTIME`,`CREFID`) 
                    VALUES ('{$BUDAT}','{$OLIN}','{$OSNO}','{$OBUY}',
                    '{$OSTY}','{$OCLR}','{$OSZE}','{$mqty}','{$OUID}','{$NOP}','{$mqty}','{$UPTIME}','{$mtid}')";
            $result = $db->query($sql) ;
            if ($result === TRUE) {
                 echo " Quantity : changed successfully at sewing input.<br />\n";
            } else {
                 echo "Error:at source quantity change at sewing input." ;
            }
          break;
         case 'outputdetails':
            $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
            $dt = date_format($dt, 'd/m/Y H:i:s');
            $SBUDDAT = $dt ;

            $TYPE = $OTYP;
            // source reduce and insert 
            $sql = "INSERT INTO `outputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                     `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`SBUDDAT`,`TYPE`,`CREFID`) 
                     VALUES ('{$BUDAT}','{$OLIN}','{$OSNO}','{$OBUY}',
                     '{$OSTY}','{$OCLR}','{$OSZE}','{$mqty}','{$OUID}','{$SBUDDAT}','{$TYPE}','{$mtid}')";
            $result = $db->query($sql) ;
            if ($result === TRUE) {
                echo " Quantity : changed successfully at sewing output.<br />\n";
           } else {
                echo "Error:at source quantity change at sewing output." ;
           }
          break;
         case 'tpfn':
            $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
            $dt = date_format($dt, 'd/m/Y H:i:s');
            $SBUDDAT = $dt ;

            $TYPE = $OTYP;
            // source reduce and insert 
            $sql = "INSERT INTO `tpfn` (`BUDAT`, `LNNUM`, `SONUM`, `BUYER`,
                     `STYLE`, `COLOR`, `SIZEF`, `PDQTY`, `USRID`,`TYPEF`,`REFID`) 
                     VALUES ('{$BUDAT}','{$OLIN}','{$OSNO}','{$OBUY}',
                     '{$OSTY}','{$OCLR}','{$OSZE}','{$mqty}','{$OUID}','{$TYPE}','{$mtid}')";
            $result = $db->query($sql) ;
            if ($result === TRUE) {
                echo " Quantity : changed successfully at finishing.<br />\n";
           } else {
                echo "Error:at source quantity change at finishing." ;
           }
          break;
         default:
          echo 'Error.Check internet connection.';
     }
 } 

//reject details - select
if( ($_POST["opid"]) == 35 ) {
    if (isset($_POST["outid"])){ 
         $pOUTID = $_POST["outid"] ; 
         $sql = "SELECT * FROM `rejectdetails` WHERE OUTID = '$pOUTID' ORDER BY DFQTY DESC";
         $result = $db->query($sql) ;
         $count = 0;
            if (mysqli_num_rows($result)>0){
                while ($res = mysqli_fetch_array($result)) {
                    $count =  $count + 1 ;
                ?>
                <tr id = <?php echo $count ?>>
                    <td style="display: none;"> <?php echo $res['TID'] ?></td>
                    <td style="display: none;"> <?php echo $res['ID'] ?></td>
                    <td> <?php echo $res['NAME'] ?></td>
                    <td class="defCount"> <?php echo $res['DFQTY'] ?></td>
                    <td style="display: none;"> <?php echo $res['OUTID'] ?></td>
                </tr>
            <?php
            }
        } // number of rwos
    } //outid
 } // 35 - condition
//reject details sum qty
if( ($_POST["opid"]) == 36 ) {
    if (isset($_POST["outid"])){ 
         $pOUTID = $_POST["outid"] ; 
         $sql = "SELECT SUM(DFQTY) AS DFQTY FROM `rejectdetails` WHERE OUTID = '$pOUTID' ";
         $result = $db->query($sql) ;
         $count = 0;
            if (mysqli_num_rows($result)>0){
                while ($res = mysqli_fetch_array($result)) {
                    $count =  $count + 1 ;
                    echo $res['DFQTY'] ;
            }
        } // number of rwos
    } //outid
 } // 36 - condition
//condition for change check so
if( ($_POST["opid"]) == 37 ) {
         $PSO = $_POST["PSO"] ; 
         $sql = "SELECT DISTINCT CONCAT (BUYER,'|',STYLE,'|', NOP) AS SOFND FROM `inputdetails` WHERE SONO = '$PSO' ";
         $result = $db->query($sql) ;
            if (mysqli_num_rows($result)>0){
                while ($res = mysqli_fetch_array($result)) {
                    print_r($res['SOFND']) ;
                 } 
             } else {
                 echo '||' ;
             } // number of rwos
 } // 37 

//whole documentno transfer to new line.
if( ($_POST["opid"]) == 38 ) {

    $TDOC  = trim($_POST['TDOC'],'') ;
    $TDYR  = trim($_POST['TDYR'],'') ;
    $TLNE  = trim($_POST['TLNE'],'') ;
    $TUID  = trim($_POST['TUID'],'') ;


    $sql = " SELECT * FROM `inputdetails` WHERE MBLNR = '$TDOC' AND MJAHR = '$TDYR' " ;
    $result = $db->query($sql) ;
    if (mysqli_num_rows($result)>0){ 
        $doc = array();
        while($row = mysqli_fetch_array($result)) {
            $doc[] = $row;
        } // end while
         $count = 0;
         if ( sizeof($doc) > 0){
             //loop and insert for transfer to new line
            foreach( $doc as $v) {
                $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
                $dt_day = date_format($dt, 'd-m-Y');
                $BUDAT  = $dt_day ;
                $UPTIME = date_format($dt, 'Ymd').'!'.date_format($dt, 'his');
                //orginal 
                $TTID = $v['TID'];
                $TLIN = $v['LINENO'];
                $TSNO = $v['SONO'];
                $TBUY = $v['BUYER'];
                $TSTY = $v['STYLE'];
                $TCLR = $v['COLOR'];
                $TSZE = $v['SIZE'];
                $TQTY = $v['QTY'];
                $TNOP = $v['NOP'];

                // source reduce and insert 
                $sql1 = "INSERT INTO `inputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                         `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`NOP`,`UPQTY`,`UPTIME`,`CREFID`) 
                         VALUES ('{$BUDAT}','{$TLIN}','{$TSNO}','{$TBUY}',
                         '{$TSTY}','{$TCLR}','{$TSZE}',-'{$TQTY}','{$TUID}','{$TNOP}',-'{$TQTY}',
                         '{$UPTIME}','{$TTID}')";
                $result1 = $db->query($sql1) ;
                if ($result1 === TRUE) {
                    $sql2 = "INSERT INTO `inputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                            `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`NOP`,`UPQTY`,`UPTIME`,`CREFID`) 
                            VALUES ('{$BUDAT}','{$TLNE}','{$TSNO}','{$TBUY}',
                            '{$TSTY}','{$TCLR}','{$TSZE}','{$TQTY}','{$TUID}','{$TNOP}','{$TQTY}',
                            '{$UPTIME}','{$TTID}')";
                    $result2 = $db->query($sql2) ;
                    if ($result2 === TRUE) {
                        $count = $count + 1;
                    }
                }
            }
         }
        print_r('Total no. of line transfer of this documnet:'.$count) ;
     }// end if

 }

//condition for fetch date based on tid.
if( ($_POST["opid"]) == 39 ) {
    $tid = $_POST["TID"] ; 
    $tablename = $_SESSION['tblname'];
    switch ($tablename) {
            case 'outputdetails':
                $whClause = " `outputdetails` WHERE TID = '$tid' ";
            break;
            case 'tpfn':
                $whClause = " `tpfn` WHERE TXNID = '$tid' ";
            break;
            default:
                echo ('Error.check internet connection all change.');
     }
    $sql = "SELECT BUDAT FROM $whClause ";
    $result = $db->query($sql) ;
       if (mysqli_num_rows($result)>0){
           while ($res = mysqli_fetch_array($result)) {
               print_r($res['BUDAT']) ;
            } 
        } else {
            echo '' ;
        } // number of rwos
 } // 39

//Date change for sewing output and finishing .
if( ($_POST["opid"]) == 40 ) {

         $CDT  = trim($_POST['CDT'],'') ;
         $TCDT = $CDT;
         $TCDT = str_replace("-", "/", $TCDT);
         $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
         $dt = date_format($dt, 'H:i:s');
         $SBUDDAT = $TCDT.' '.$dt ;


         $TID  = trim($_POST['TID'],'') ;
         $IDT  = trim($_POST['IDT'],'') ;
         $USR  = trim($_POST['USR'],'') ;

         $tablename = $_SESSION['tblname'];
         switch ($tablename) {
             case 'outputdetails':

                $sql = "INSERT INTO `outputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`, `STYLE`, `COLOR`, `SIZE`, `TYPE` , `QTY`, `SBUDDAT` , `CREFID` ,`UID` ) 
                SELECT BUDAT , LINENO , SONO , BUYER , STYLE , COLOR , SIZE , TYPE , -QTY , SBUDDAT , '{$TID}' , '{$USR}'
                FROM `outputdetails` WHERE  TID = '{$TID}'";

                $result = $db->query($sql) ;

                if ($result === TRUE) {
                    // to insert
                    $sqlTO = "INSERT INTO `outputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`, `STYLE`, `COLOR`, `SIZE`, `TYPE` , `QTY`, `SBUDDAT` , `CREFID` ,`UID` ) 
                    SELECT '{$CDT}' , LINENO , SONO , BUYER , STYLE , COLOR , SIZE , TYPE , QTY , '{$SBUDDAT}' , '{$TID}' , '{$USR}'
                    FROM `outputdetails` WHERE  TID = '{$TID}'";
                    $resultTO = $db->query($sqlTO) ;
                        if ( $resultTO === TRUE ) {
                            echo "Date changed successfully at sewing output<br />\n";
                        } else {
                            echo "Error date changed:at to sewing output" ;
                        }
                } else {
                            echo "Error date changed:at source sewing output" ;
                }
              break;
             case 'tpfn':
                $sql = "INSERT INTO `tpfn` (`BUDAT`, `LNNUM`, `SONUM`, `BUYER`, `STYLE`, `COLOR`, `SIZEF`, `TYPEF` , `PDQTY` , `REFID` ,`USRID` ) 
                SELECT BUDAT , LNNUM , SONUM , BUYER , STYLE , COLOR , SIZEF , TYPEF , -PDQTY , '{$TID}' , '{$USR}'
                FROM `tpfn` WHERE  TXNID = '{$TID}'";
                $result = $db->query($sql) ;
                if ($result === TRUE) {
                    // to insert
                    $sqlTO = "INSERT INTO `tpfn` (`BUDAT`, `LNNUM`, `SONUM`, `BUYER`, `STYLE`, `COLOR`, `SIZEF`, `TYPEF` , `PDQTY` , `REFID` ,`USRID` ) 
                    SELECT '{$CDT}' , LNNUM , SONUM , BUYER , STYLE , COLOR , SIZEF , TYPEF , PDQTY  , '{$TID}' , '{$USR}'
                    FROM `tpfn` WHERE  TXNID = '{$TID}'";
                    $resultTO = $db->query($sqlTO) ;
                        if ( $resultTO === TRUE ) {
                            echo "Date changed successfully at Finishing.\n";
                        } else {
                            echo "Error date changed:at to Finishing." ;
                        }
                } else {
                            echo "Error date changed:at source Finishing." ;
                }
               break;

             default:
                echo ('Error.check internet connection all change.');

         }
 }

//Country change : country load option for sewing output and finishing .
if( ($_POST["opid"]) == 41 ) {
    //$TID  = trim($_POST['TID'],'') ;
    //$FCC  = trim($_POST['TID'],'') ;
    //$tablename = $_SESSION['tblname'];
    $sql = "SELECT CONCAT(CNTCC,'|',CNTNM) AS CNTCC FROM `mcnt` ORDER BY CNTOA ASC" ;
    if ( $result =  mysqli_query($db,$sql)) {
        while($row = mysqli_fetch_array($result))
        {
            ?>
                <option><?php echo $row['CNTCC'] ?></option>
            <?php
        }
    }

 }

//Country change : sewing output and finishing 
if( ($_POST["opid"]) == 42 ) {
    $MDTID  = trim($_POST['MDTID'],'') ;
    $MDFCC  = trim($_POST['MDFCC'],'') ;
    $MDTCC  = substr(trim($_POST['MDTCC'],''),0,2) ;
    $tablename = $_SESSION['tblname'];

    switch($tablename){
            case 'outputdetails':
                  $sql = "UPDATE `outputdetails` SET `CNTCC`= '$MDTCC' WHERE TID = '$MDTID' AND CNTCC = '$MDFCC'" ;
                  $result = $db->query($sql) ;
                  if ($result === TRUE) {
                      echo "Sewing output: country change successfully";
                  } else {
                      echo "Error: check connection at country change sewing output ";
                  }
                break;
            case 'tpfn':
                $sql = "UPDATE `tpfn` SET `CNTCC`= '$MDTCC' WHERE TXNID = '$MDTID' AND CNTCC = '$MDFCC'" ;
                $result = $db->query($sql) ;
                if ($result === TRUE) {
                    echo "PAD Sewing: country change successfully";
                } else {
                    echo "Error: check connection at country change pad sewing ";
                }
              break;
                break;
            default:
                echo 'Error.Check internet connection.';
        }
        
 }

//Defect move change : sewing output and finishing
if( ($_POST["opid"]) == 43 ) {

    $MOVEF  = trim($_POST['movef'],'') ;
    $MOVET  = trim($_POST['movet'],'') ;

    $tablename = $_SESSION['tblname'];
    switch($tablename){
        case 'outputdetails':
            $sql = "SELECT * FROM `rejectdetails` WHERE OUTID = '$MOVEF'" ;
            $result = mysqli_query($db, $sql); 
            $rows = mysqli_num_rows($result);
            if ($rows > 0){
                $sql1 = "UPDATE `rejectdetails` SET `OUTID`= '$MOVET' WHERE OUTID = '$MOVEF'" ;
                $result1 = mysqli_query($db, $sql1); 
                if ($result1){
                    echo 'Defects move to new tid :: '.$MOVET;
                }
            }else{
                  echo 'No defects found for moved' ;                
            }
        break;
        case 'tpfn':
            echo 'PAD Sewing not entered any defect quantity';
            break;
        default:
            echo 'Error.Check internet connection.';
     }
 }
 
?>