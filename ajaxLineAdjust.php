<?php
session_start() ;
include('config.php') ;
$lineNo = $_SESSION['lineNo'];  
$USERIDNEW = $_SESSION['USERIDNEW']; 
$prdty = 'SEW';

// floor select
if( ($_POST["opid"]) == 01 ) {
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

// line select
if( ($_POST["opid"]) == 02 ) {
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

//fetch wip  details
if (($_POST["opid"]) == 03){
    $sql = "";
    $lineNo = trim($_POST["lineNo"]); 
    $prdty = trim($_POST["prdty"]);
    if ( $prdty == 'FINISHING') {
        $sql = " SELECT LINENO , SONO , BUYER , STYLE , COLOR , QTY , NOP
                 FROM 
                 ( SELECT  '$lineNo' AS LINENO , F.SONO , F.BUYER , F.STYLE , F.COLOR ,
                            (IFNULL(F.IQTY,0) -  IFNULL(K.OQTY,0)) AS QTY , '-' AS NOP
                   FROM 
                    (
                    SELECT SONO , BUYER , STYLE , COLOR , IFNULL(SUM(QTY),0) AS IQTY 
                    FROM
                        (
                            SELECT SONO , BUYER , STYLE , COLOR , QTY
                            FROM `outputdetails` 
                            WHERE STR_TO_DATE(BUDAT,'%d-%m-%Y') > STR_TO_DATE('01-05-2020','%d-%m-%Y') 
                            AND LINENO IN ('$lineNo')
                            AND SONO NOT IN (SELECT VBELN FROM mcrd)
                            AND TYPE IN ('FIT','ADJ') 
                        ) A GROUP BY SONO , BUYER , STYLE , COLOR ORDER BY SONO DESC
                    ) AS F 
                    LEFT JOIN 
                    (
                    SELECT SONUM AS SONO , BUYER , STYLE , COLOR , IFNULL(SUM(PDQTY),0) AS OQTY 
                    FROM
                            (
                            SELECT SONUM , BUYER , STYLE , COLOR , PDQTY
                            FROM `tpfn` 
                            WHERE STR_TO_DATE(BUDAT,'%d-%m-%Y') > STR_TO_DATE('01-05-2020','%d-%m-%Y') 
                            AND LNNUM IN ('$lineNo')
                            AND TYPEF IN ('FIT','ADJ') 
                            ) A GROUP BY SONUM , BUYER , STYLE , COLOR ORDER BY SONUM DESC 
                    ) AS K ON F.SONO = K.SONO  AND F.BUYER = K.BUYER 
                         AND F.STYLE = K.STYLE AND F.COLOR = K.COLOR
                ) AS M WHERE QTY <> 0 " ;

        // $sql = "SELECT LINENO , SONO ,BUYER , STYLE , COLOR , QTY , '-' AS NOP FROM 
        //             (SELECT A.LINENO , A.SONO , A.BUYER , A.STYLE , A.COLOR , (IFNULL(A.QTY,0)- IFNULL(B.PDQTY,0))AS QTY  FROM
        //             ( SELECT LINENO , SONO , BUYER , STYLE , COLOR , SUM(QTY) AS QTY  FROM `outputdetails` 
        //             WHERE BUDAT NOT IN ('01-01-1900')
        //             GROUP BY LINENO , SONO , BUYER , STYLE , COLOR , TYPE 
        //             HAVING LINENO = '$lineNo' AND TYPE IN ('FIT','ADJ') ORDER BY SONO ) AS A
        //          LEFT JOIN 
        //             (SELECT LNNUM , SONUM , BUYER , STYLE , COLOR , IFNULL(SUM(PDQTY),0) AS PDQTY  FROM `tpfn` 
        //             GROUP BY LNNUM , SONUM , BUYER , STYLE , COLOR , TYPEF 
        //             HAVING LNNUM = '$lineNo' AND TYPEF IN ('FIT','ADJ') AND PDQTY<> 0 ORDER BY SONUM ) AS B
        //             ON A.LINENO = B.LNNUM AND A.SONO = B.SONUM AND A.BUYER = B.BUYER AND A.STYLE = B.STYLE AND A.COLOR = B.COLOR ) 
        //             AS FINAL WHERE QTY <> 0 ORDER BY SONO , STYLE , COLOR" ;



    } elseif ($prdty == 'PACKING'){

        $sql = " SELECT  LINENO , SONO , BUYER , STYLE , COLOR , QTY , NOP 
                 FROM 
                 ( SELECT '$lineNo' AS LINENO , F.SONO , F.BUYER , F.STYLE , F.COLOR ,
                        (IFNULL(F.IQTY,0) -  IFNULL(K.OQTY,0)) AS QTY , '-' AS NOP
                    FROM 
                        (
                        SELECT SONUM AS SONO , BUYER , STYLE , COLOR , IFNULL(SUM(PDQTY),0) AS IQTY 
                        FROM
                        (
                            SELECT SONUM , BUYER , STYLE , COLOR , PDQTY
                            FROM `tpfn` 
                            WHERE STR_TO_DATE(BUDAT,'%d-%m-%Y') > STR_TO_DATE('01-05-2021','%d-%m-%Y') 
                            AND LNNUM IN (SELECT MNO FROM `zpp_machine_mast` WHERE FLOOR = '$lineNo')
                            AND SONUM NOT IN (SELECT VBELN FROM mcrd)
                            AND TYPEF IN ('FIT','ADJ') 
                        ) A GROUP BY SONUM , BUYER , STYLE , COLOR ORDER BY SONUM DESC
                        ) AS F 
                        LEFT JOIN 
                        (
                        SELECT SONUM AS SONO , BUYER , STYLE , COLOR , IFNULL(SUM(PDQTY),0) AS OQTY 
                        FROM
                        (
                            SELECT SONUM , BUYER , STYLE , COLOR , PDQTY
                            FROM `tppk` 
                            WHERE STR_TO_DATE(BUDAT,'%d-%m-%Y') > STR_TO_DATE('01-05-2021','%d-%m-%Y') 
                            AND LNNUM IN ('$lineNo')
                            AND TYPEF IN ('FIT','ADJ') 
                        ) A GROUP BY SONUM , BUYER , STYLE , COLOR ORDER BY SONUM DESC 
                        ) AS K ON F.SONO = K.SONO  AND F.BUYER = K.BUYER 
                        AND F.STYLE = K.STYLE AND F.COLOR = K.COLOR 
                    ) AS M WHERE  QTY <> 0  ";
    } else {
        $sql = " SELECT F.LINENO , F.SONO , F.BUYER , F.STYLE , F.COLOR , F.NOP , F.QTY  FROM
                (SELECT N.LINENO , N.SONO , N.BUYER , N.STYLE , N.COLOR , N.NOP , IFNULL( IFNULL(N.IQTY,0) - IFNULL(U.PQTY,0),0)  AS QTY FROM
                (SELECT I.LINENO , I.SONO , I.BUYER , I.STYLE , I.COLOR , I.NOP ,  SUM(I.INQTY) AS IQTY FROM
                (SELECT LINENO , SONO , BUYER , STYLE , COLOR , QTY , NOP  , 
                    IFNULL(ROUND(QTY / NOP),0) AS INQTY 
                FROM `inputdetails` 
                WHERE LINENO IN ('$lineNo') 
                ORDER BY LINENO , SONO , BUYER , STYLE , COLOR  ) I 
                GROUP BY I.LINENO , I.SONO , I.BUYER , I.STYLE , I.COLOR
                ORDER BY I.LINENO , I.SONO , I.BUYER , I.STYLE , I.COLOR ) AS N 
                LEFT JOIN 
                (SELECT T.LINENO , T.SONO , T.BUYER , T.STYLE , T.COLOR , SUM(T.QTY) AS PQTY FROM
                (SELECT LINENO , SONO , BUYER , STYLE , COLOR ,TYPE, IFNULL(SUM(QTY),0) AS QTY FROM `outputdetails` 
                WHERE LINENO IN ('$lineNo')  AND TYPE IN ('FIT','REJ','ADJ')
                GROUP BY LINENO , SONO , BUYER , STYLE , COLOR , TYPE 
                ORDER BY LINENO , SONO , BUYER , STYLE , COLOR ) AS T
                GROUP BY T.LINENO , T.SONO , T.BUYER , T.STYLE , T.COLOR
                ORDER BY T.LINENO , T.SONO , T.BUYER , T.STYLE , T.COLOR ) AS U  
                ON N.LINENO = U.LINENO AND 
                N.SONO = U.SONO AND 
                N.BUYER = U.BUYER AND 
                N.STYLE = U.STYLE AND 
                N.COLOR = U.COLOR ) AS F  WHERE F.QTY <> 0 " ; 
     }
     $result = $db->query($sql) ;
     $count = 0;
    if (mysqli_num_rows($result)>0){
        while ($res = mysqli_fetch_array($result)) {
            $count =  $count + 1 ;
        ?>
        <tr id = <?php echo $count ?> class ="item">
            <td> <?php echo $count;?></td>
            <td> <?php echo $res['LINENO'];?></td>
            <td> <?php echo $res['SONO'];?></td>
            <td> <?php echo $res['BUYER'] ?></td>
            <td> <?php echo $res['STYLE'] ?></td>
            <td> <?php echo $res['COLOR'] ?></td>
            <td> <?php echo $res['NOP'] ?></td>
            <td class="WIP"> <?php echo $res['QTY'] ?></td>
        </tr>
     <?php
     }
    }
 }

//fetch size wise input & output
if (($_POST["opid"]) == 04){
    $pLINE = $_POST["pLINE"]; 
    $pSONO = $_POST["pSONO"]; 
    $pBYER = $_POST["pBYER"]; 
    $pSTYL = $_POST["pSTYL"]; 
    $pCLOR = $_POST["pCLOR"]; 
    $count = 0 ;
    $sql = "SELECT  I.SIZE , IFNULL(I.QTY,0) AS IQTY , IFNULL(O.QTY,0) AS OQTY , 
            (IFNULL(I.QTY,0)-IFNULL(O.QTY,0)) AS WIPQTY 
            FROM 
            (SELECT P.SIZE , SUM(P.QTY) QTY  FROM 
            ( SELECT SIZE , round(QTY/NOP) AS QTY FROM `inputdetails`  
            WHERE LINENO = '$pLINE' AND SONO = '$pSONO' AND BUYER = '$pBYER' AND STYLE = '$pSTYL' AND COLOR = '$pCLOR' ORDER BY SIZE) AS P
            GROUP BY P.SIZE 
            ORDER BY P.SIZE) AS I
            LEFT JOIN 
            (SELECT SIZE , IFNULL(SUM(QTY),0) AS QTY FROM `outputdetails`
            WHERE LINENO = '$pLINE' AND SONO = '$pSONO' AND BUYER = '$pBYER' AND STYLE = '$pSTYL' AND COLOR = '$pCLOR'
            AND TYPE IN ('FIT','REJ','ADJ')
            GROUP BY SIZE) AS O 
            ON I.SIZE = O.SIZE";
    $result = $db->query($sql) ;
    if (mysqli_num_rows($result)>0){
        while ($res = mysqli_fetch_array($result)) {
            $count =  $count + 1 ;
        ?>
        <tr id = <?php echo $count ?> class ="mITEM">
            <td> <?php echo $count;?></td>
            <td> <?php echo $res['SIZE'];?></td>
            <td class="IQTY"> <?php echo $res['IQTY'];?></td>
            <td class="OQTY"> <?php echo $res['OQTY']; ?></td>
            <td class="WQTY"> <?php echo $res['WIPQTY']; ?></td>
            <td><input class="w3-input w3-border" type="text" value=""></td>
            <td><input class="w3-input w3-border" type="checkbox"></td>
        </tr>
     <?php
     }
    }
 }
//fetch size wise input & output for finishing
if (($_POST["opid"]) == 06){
    $pLINE = $_POST["pLINE"]; 
    $pSONO = $_POST["pSONO"]; 
    $pBYER = $_POST["pBYER"]; 
    $pSTYL = $_POST["pSTYL"]; 
    $pCLOR = $_POST["pCLOR"]; 
    $count = 0 ;
    $sql = "SELECT F.SIZE , IFNULL(F.IQTY,0) AS IQTY , IFNULL(K.OQTY,0) AS OQTY  , 
            (IFNULL(F.IQTY,0) -  IFNULL(K.OQTY,0)) AS WIPQTY FROM 
                        (
                            SELECT SIZE , IFNULL(SUM(QTY),0) AS IQTY FROM `outputdetails`  
                            WHERE LINENO IN ('$pLINE')  
                                AND TYPE IN ('FIT','ADJ')
                                AND SONO = '$pSONO' AND BUYER = '$pBYER' AND STYLE = '$pSTYL' AND COLOR = '$pCLOR'
                            GROUP BY SIZE
                            ORDER BY IQTY DESC
                        ) AS F 
                        LEFT JOIN 
                        (
                            SELECT SIZEF , IFNULL(SUM(PDQTY),0) AS OQTY FROM `tpfn`  
                            WHERE LNNUM IN ('$pLINE')  
                                AND TYPEF IN ('FIT','ADJ')
                                AND SONUM = '$pSONO' AND BUYER = '$pBYER' AND STYLE = '$pSTYL' AND COLOR = '$pCLOR'
                            GROUP BY SIZEF
                            ORDER BY OQTY DESC  
                        ) AS K ON F.SIZE = K.SIZEF";
    print_r($sql);
    $result = $db->query($sql) ;
    if (mysqli_num_rows($result)>0){
        while ($res = mysqli_fetch_array($result)) {
            $count =  $count + 1 ;
        ?>
        <tr id = <?php echo $count ?> class ="mITEM">
            <td> <?php echo $count;?></td>
            <td> <?php echo $res['SIZE'];?></td>
            <td class="IQTY"> <?php echo $res['IQTY'];?></td>
            <td class="OQTY"> <?php echo $res['OQTY']; ?></td>
            <td class="WQTY"> <?php echo $res['WIPQTY']; ?></td>
            <td><input class="w3-input w3-border" type="text" value=""></td>
            <td><input class="w3-input w3-border" type="checkbox"></td>
        </tr>
     <?php
     }
    }
 }

//fetch size wise input & output for packing
if (($_POST["opid"]) == 07){
    $pLINE = $_POST["pLINE"]; 
    $pSONO = $_POST["pSONO"]; 
    $pBYER = $_POST["pBYER"]; 
    $pSTYL = $_POST["pSTYL"]; 
    $pCLOR = $_POST["pCLOR"]; 
    $count = 0 ;
    $sql = "SELECT F.SIZEF AS SIZE , IFNULL(F.IQTY,0) AS IQTY , IFNULL(K.OQTY,0) AS OQTY  , 
            (IFNULL(F.IQTY,0) -  IFNULL(K.OQTY,0)) AS WIPQTY FROM 
                    (
                        SELECT SIZEF , IFNULL(SUM(PDQTY),0) AS IQTY FROM `tpfn`  
                        WHERE LNNUM IN (SELECT MNO FROM `zpp_machine_mast` WHERE FLOOR = '$pLINE' )  
                              AND TYPEF IN ('FIT','ADJ')
                              AND SONUM = '$pSONO' AND BUYER = '$pBYER' AND STYLE = '$pSTYL' AND COLOR = '$pCLOR'
                        GROUP BY SIZEF
                        ORDER BY IQTY DESC
                    ) AS F 
                    LEFT JOIN 
                    (
                        SELECT SIZEF , IFNULL(SUM(PDQTY),0) AS OQTY FROM `tppk`  
                        WHERE LNNUM IN ('$pLINE')  
                              AND TYPEF IN ('FIT','ADJ')
                              AND SONUM = '$pSONO' AND BUYER = '$pBYER' AND STYLE = '$pSTYL' AND COLOR = '$pCLOR'
                        GROUP BY SIZEF
                        ORDER BY OQTY DESC  
                    ) AS K ON F.SIZEF = K.SIZEF ";
    print_r($sql);
    $result = $db->query($sql) ;
    if (mysqli_num_rows($result)>0){
        while ($res = mysqli_fetch_array($result)) {
            $count =  $count + 1 ;
        ?>
        <tr id = <?php echo $count ?> class ="mITEM">
            <td> <?php echo $count;?></td>
            <td> <?php echo $res['SIZE'];?></td>
            <td class="IQTY"> <?php echo $res['IQTY'];?></td>
            <td class="OQTY"> <?php echo $res['OQTY']; ?></td>
            <td class="WQTY"> <?php echo $res['WIPQTY']; ?></td>
            <td><input class="w3-input w3-border" type="text" value=""></td>
            <td><input class="w3-input w3-border" type="checkbox"></td>
        </tr>
     <?php
     }
    }
 }

//save adjust value 
if (($_POST["opid"]) == 05){
     $pLINE = $_POST["pLINE"]; 
     $pSONO = $_POST["pSONO"]; 
     $pBYER = $_POST["pBYER"]; 
     $pSTYL = $_POST["pSTYL"]; 
     $pCLOR = $_POST["pCLOR"]; 
     $pVADJ = $_POST["pVADJ"];  
     //BUDAT & SBUDAT
     $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
     $dt_day = date_format($dt, 'd-m-Y');
     $dtt = date_format($dt, 'd/m/Y H:i:s');
     $BUDAT  = $dt_day ;
     $SBUDDAT = $dtt ;
     $UIDD =  $_POST["usrid"] ; 
     $TYPE = 'ADJ';
     $count = 0;
     foreach ($pVADJ as $value) {
        $val = explode("|", $value);
            if(count($val) > 0 ) {
                $vrSZE = strval($val[0]);
                $vrQTY = $val[1];
                $sql = "INSERT INTO `outputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`, 
                                `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`, `SBUDDAT`, `TYPE`, `OPID`, `REJID`) 
                         VALUES ('{$BUDAT}','{$pLINE}','{$pSONO}','{$pBYER}',
                                '{$pSTYL}','{$pCLOR}','{$vrSZE}','{$vrQTY}','{$UIDD}','{$SBUDDAT}','{$TYPE}','1','1');" ; 
                 $result = $db->query($sql) ;
                 if ( $result === TRUE ){
                      $count = $count + 1;
                 }
            }
      }
 
        if ( $count > 0){
            echo "Total row: ".$count." :Adjusted successfully.\n";
        } else {
            echo "Error connection problem.\n";
        }      
 }
//save adjust value for packing 
if (($_POST["opid"]) == 10) {
    $pLINE = $_POST["pLINE"]; 
    $pSONO = $_POST["pSONO"]; 
    $pBYER = $_POST["pBYER"]; 
    $pSTYL = $_POST["pSTYL"]; 
    $pCLOR = $_POST["pCLOR"]; 
    $pVADJ = $_POST["pVADJ"];  
    //BUDAT & SBUDAT
    $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
    $dt_day = date_format($dt, 'd-m-Y');
    $dtt = date_format($dt, 'd/m/Y H:i:s');
    $BUDAT  = $dt_day ;
    $SBUDDAT = $dtt ;
    $UIDD =  $_POST["usrid"] ; 
    $TYPE = 'ADJ';
    $count = 0;
    foreach ($pVADJ as $value) {
       $val = explode("|", $value);
           if(count($val) > 0 ) {
               $vrSZE = strval($val[0]);
               $vrQTY = $val[1];
               $sql = "INSERT INTO `tpfn` (`BUDAT`, `LNNUM`, `SONUM`, `BUYER`, 
                               `STYLE`, `COLOR`, `SIZEF`, `PDQTY`, `USRID`,`TYPEF`) 
                        VALUES ('{$BUDAT}','{$pLINE}','{$pSONO}','{$pBYER}',
                               '{$pSTYL}','{$pCLOR}','{$vrSZE}','{$vrQTY}','{$UIDD}','{$TYPE}');" ;
                $result = $db->query($sql) ;
                if ( $result === TRUE ){
                     $count = $count + 1;
                }
           }
     }

       if ( $count > 0){
           echo "Total row: ".$count." :Adjusted successfully for Finishing.\n";
       } else {
           echo "Error connection problem at Finishing.\n";
       }       
 }
//save adjust value for packing 
if (($_POST["opid"]) == 11) {
    $pLINE = $_POST["pLINE"]; 
    $pSONO = $_POST["pSONO"]; 
    $pBYER = $_POST["pBYER"]; 
    $pSTYL = $_POST["pSTYL"]; 
    $pCLOR = $_POST["pCLOR"]; 
    $pVADJ = $_POST["pVADJ"];  
    //BUDAT & SBUDAT
    $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
    $dt_day = date_format($dt, 'd-m-Y');
    $dtt = date_format($dt, 'd/m/Y H:i:s');
    $BUDAT  = $dt_day ;
    $SBUDDAT = $dtt ;
    $UIDD =  $_POST["usrid"] ; 
    $TYPE = 'ADJ';
    $count = 0;
    foreach ($pVADJ as $value) {
       $val = explode("|", $value);
           if(count($val) > 0 ) {
               $vrSZE = strval($val[0]);
               $vrQTY = $val[1];
               $sql = "INSERT INTO `tppk` (`BUDAT`, `LNNUM`, `SONUM`, `BUYER`, 
                               `STYLE`, `COLOR`, `SIZEF`, `PDQTY`, `USRID`,`TYPEF`) 
                        VALUES ('{$BUDAT}','{$pLINE}','{$pSONO}','{$pBYER}',
                               '{$pSTYL}','{$pCLOR}','{$vrSZE}','{$vrQTY}','{$UIDD}','{$TYPE}');" ;
                $result = $db->query($sql) ;
                if ( $result === TRUE ){
                     $count = $count + 1;
                }
           }
     }

       if ( $count > 0){
           echo "Total row: ".$count." :Adjusted successfully for Packing.\n";
       } else {
           echo "Error connection problem at Packing.\n";
       }       
 }




?>