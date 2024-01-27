<?php
session_start(); 
include 'config.php' ; 
$lineNo = $_SESSION['lineNo']; 
$prdty  = $_SESSION['prdty']; 
$data  = $_POST;
$OPPID = $data['OPPID'];

//fetch data - input details
if ($OPPID == 31){
    $sql = "";
    if ( trim($prdty) == 'FINISHING') {

            $sql = "SELECT LINENO , SONO , BUYER , STYLE , COLOR , QTY , NOP
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

        // $sql = " SELECT LINENO , SONO ,BUYER , STYLE , COLOR , QTY , '-' AS NOP FROM 
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
        //             AS FINAL WHERE QTY <> 0 AND 
        //             SONO NOT IN (SELECT VBELN FROM mcrd)
        //             ORDER BY SONO DESC " ;
        
    } else if ( trim($prdty) == 'PACKING'){

            $whCaluse = "( SELECT MNO FROM `zpp_machine_mast` WHERE FLOOR = '$lineNo' )" ;
            $sql = "SELECT  LINENO , SONO , BUYER , STYLE , COLOR , QTY , NOP 
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
                    ) AS M WHERE QTY <> 0 ";

        // $sql = " SELECT SONO ,BUYER , STYLE , COLOR , QTY , '-' AS NOP FROM 
        //         (SELECT A.SONO , A.BUYER , A.STYLE , A.COLOR , (IFNULL(A.QTY,0)- IFNULL(B.PDQTY,0))AS QTY  FROM
        //         (SELECT SONUM AS SONO , BUYER , STYLE , COLOR , SUM(PDQTY) AS QTY  FROM `tpfn` 
        //          WHERE BUDAT NOT IN ('01-05-2021')
        //          GROUP BY SONUM , BUYER , STYLE , COLOR , TYPEF 
        //          HAVING  TYPEF = 'FIT' ORDER BY SONUM ) AS A
        //     LEFT JOIN 
        //         (SELECT SONUM , BUYER , STYLE , COLOR , IFNULL(SUM(PDQTY),0) AS PDQTY  FROM `tppk` 
        //         GROUP BY SONUM , BUYER , STYLE , COLOR , TYPEF 
        //         HAVING  TYPEF = 'FIT' AND PDQTY<> 0 ORDER BY SONUM ) AS B
        //         ON A.SONO = B.SONUM AND A.BUYER = B.BUYER AND A.STYLE = B.STYLE AND A.COLOR = B.COLOR ) 
        //         AS FINAL WHERE QTY <> 0 AND 
        //         SONO NOT IN (SELECT VBELN FROM mcrd)
        //         ORDER BY SONO DESC " ;



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
                N.COLOR = U.COLOR ) AS F  WHERE F.QTY <> 0 AND 
                F.SONO NOT IN (SELECT VBELN FROM mcrd) ORDER BY F.SONO DESC " ; 
     }

    $result = $db->query($sql) ;
    $count = 0;
    if (mysqli_num_rows($result)>0){
        while ($res = mysqli_fetch_array($result)) {
            $count =  $count + 1 ;
        ?>
        <tr id = <?php echo $count ?> class="w3-hover-green">
        <td><?php echo $count ?></td>
        <td><button class="w3-btn w3-blue-grey" type="submit"value="<?php echo $res['SONO'];?>"
                            name="submit"> <?php echo $res['SONO'];?> </button>
        </td>
            <td> <?php echo $res['BUYER'] ?></td>
            <td> <?php echo $res['STYLE'] ?></td>
            <td style="font-weight: bold;"> <?php echo $res['COLOR'] ?></td>
            <td> <?php echo $res['NOP'] ?></td>
            <td> <?php echo $res['QTY'] ?></td>
        </tr>
     <?php
     }
    }
 }

//Grid click - Call Next Page 
if ($OPPID == 32){
    // setting data to session variable
    $_SESSION['sono']  = $data['SONO']; 
    $_SESSION['BUYER'] = $data['BUYER']; 
    $_SESSION['STYLE'] = $data['STYLE']; 
    $_SESSION['COLOR'] = $data['COLOR'];
    $_SESSION['LINE']  = $data['LINE'];  
    $_SESSION['UIDNEW'] = $data['UID']; 
    $_SESSION['PRDTY']  =  $data['PRDTY'] ; 
    
 }


//START:Option-3 & 4 myBOX implementation
if( $OPPID == 3 )
     {
        $BUDAT  = $data['VBUDAT'];
        $LINENOLV =  $lineNo ;
        // sewing output
            $sqlSB = "SELECT LINENO,SONO,BUYER,STYLE,COLOR,SIZE,CNTCC, 
                    IFNULL(SUM(case when col = 'FIT' then VALUE end),0) OK,
                    IFNULL(SUM(case when col = 'DEFECT' then VALUE end),0) DEFECT,
                    IFNULL(SUM(case when col = 'REJECT' then VALUE end),0) REJECT,
                    IFNULL(SUM(VALUE),0) AS TOTALQTY
                    FROM
                    (
                        SELECT LINENO,SONO,BUYER,STYLE,COLOR,SIZE,CNTCC, TYPE AS col,  QTY VALUE 
                        FROM outputdetails
                        WHERE BUDAT = '$BUDAT' AND LINENO = '$LINENOLV' 
                    ) d
                    GROUP BY LINENO,SONO,BUYER,STYLE,COLOR,SIZE,CNTCC ";     
            $resultSB = $db->query($sqlSB) ;
            $dataSB = array();
            while($rowSB = mysqli_fetch_array($resultSB)) {
                $dataSB[] = $rowSB;
            }

            foreach( $dataSB as $res) {
                ?>
                <tr>
                    <td> <?php echo $res['LINENO'] ?></td>
                    <td> <?php echo $res['SONO'] ?></td>
                    <td> <?php echo $res['BUYER'] ?></td>
                    <td> <?php echo $res['STYLE'] ?></td>
                    <td> <?php echo $res['COLOR'] ?></td>
                    <td> <?php echo $res['SIZE'] ?></td>
                    <td> <?php echo $res['CNTCC'] ?></td>
                    <td> <?php echo $res['OK'] ?></td>
                    <td> <?php echo $res['DEFECT'] ?></td>
                    <td> <?php echo $res['REJECT'] ?></td>
                    <td> <?php echo $res['TOTALQTY'] ?></td>
                </tr>
                <?php
             }
         // sewing output
         } elseif ($OPPID == 4) {
            // finishing output
            $BUDAT  = $data['VBUDAT'];
            $LINENOLV =  $lineNo ;
            // sewing output
                $sqlSB = "SELECT LNNUM AS LINENO ,SONUM AS SONO ,BUYER,STYLE,COLOR,SIZEF AS SIZE,CNTCC, 
                        IFNULL(SUM(case when col = 'FIT' then VALUE end),0) OK,
                        IFNULL(SUM(case when col = 'DEF' then VALUE end),0) DEFECT,
                        IFNULL(SUM(case when col = 'REJ' then VALUE end),0) REJECT,
                        IFNULL(SUM(VALUE),0) AS TOTALQTY
                        FROM
                        (
                            SELECT LNNUM,SONUM,BUYER,STYLE,COLOR,SIZEF,CNTCC, TYPEF AS col,  PDQTY VALUE 
                            FROM tpfn
                            WHERE BUDAT = '$BUDAT' AND LNNUM = '$LINENOLV' 
                        ) d
                        GROUP BY LNNUM,SONUM,BUYER,STYLE,COLOR,SIZEF,CNTCC ";    
                $resultSB = $db->query($sqlSB) ;
                $dataSB = array();

                while($rowSB = mysqli_fetch_array($resultSB)) {
                    $dataSB[] = $rowSB;
                }
    
                foreach( $dataSB as $res) {
                    ?>
                    <tr>
                        <td> <?php echo $res['LINENO'] ?></td>
                        <td> <?php echo $res['SONO'] ?></td>
                        <td> <?php echo $res['BUYER'] ?></td>
                        <td> <?php echo $res['STYLE'] ?></td>
                        <td> <?php echo $res['COLOR'] ?></td>
                        <td> <?php echo $res['SIZE'] ?></td>
                        <td> <?php echo $res['CNTCC'] ?></td>
                        <td> <?php echo $res['OK'] ?></td>
                        <td> <?php echo $res['DEFECT'] ?></td>
                        <td> <?php echo $res['REJECT'] ?></td>
                        <td> <?php echo $res['TOTALQTY'] ?></td>
                    </tr>
                    <?php
                }
         // finishing output
         } elseif ($OPPID == 41) {
                    // packing output
                    $BUDAT  = $data['VBUDAT'];
                    $LINENOLV =  $lineNo ;
                    // packing output
                        $sqlSB = "SELECT LNNUM AS LINENO ,SONUM AS SONO ,BUYER,STYLE,COLOR,SIZEF AS SIZE,CNTCC, 
                                IFNULL(SUM(case when col = 'FIT' then VALUE end),0) OK,
                                IFNULL(SUM(case when col = 'DEF' then VALUE end),0) DEFECT,
                                IFNULL(SUM(case when col = 'REJ' then VALUE end),0) REJECT,
                                IFNULL(SUM(VALUE),0) AS TOTALQTY
                                FROM
                                (
                                    SELECT LNNUM,SONUM,BUYER,STYLE,COLOR,SIZEF,CNTCC, TYPEF AS col,  PDQTY VALUE 
                                    FROM tppk
                                    WHERE BUDAT = '$BUDAT' AND LNNUM = '$LINENOLV' 
                                ) d
                                GROUP BY LNNUM,SONUM,BUYER,STYLE,COLOR,SIZEF,CNTCC ";    
                                
                        $resultSB = $db->query($sqlSB) ;
                        $dataSB = array();
                        while($rowSB = mysqli_fetch_array($resultSB)) {
                            $dataSB[] = $rowSB;
                        }
            
                        foreach( $dataSB as $res) {
                            ?>
                            <tr>
                                <td> <?php echo $res['LINENO'] ?></td>
                                <td> <?php echo $res['SONO'] ?></td>
                                <td> <?php echo $res['BUYER'] ?></td>
                                <td> <?php echo $res['STYLE'] ?></td>
                                <td> <?php echo $res['COLOR'] ?></td>
                                <td> <?php echo $res['SIZE'] ?></td>
                                <td> <?php echo $res['CNTCC'] ?></td>
                                <td> <?php echo $res['OK'] ?></td>
                                <td> <?php echo $res['DEFECT'] ?></td>
                                <td> <?php echo $res['REJECT'] ?></td>
                                <td> <?php echo $res['TOTALQTY'] ?></td>
                            </tr>
                            <?php
                        }
         // packing output
         }
//END:Option-3
//myMessage send 
if ( $OPPID == 5 ){
    $MSGTY  = $data['OTYPE'];
    $MSGFM  = $data['LOGID'];
    $MSGTO  = 'PPQ30';
    $MSGDT  = $data['MSGDT'];

         // source reduce and insert 
         $sql = "INSERT INTO `tmsg` (`MSGTY`, `MSGFM`, `MSGTO`, `MSGDT` , `USRID`) 
                 VALUES ('{$MSGTY}','{$MSGFM}','{$MSGTO}','{$MSGDT}','{$MSGFM}')";
         $result = $db->query($sql) ;
         if ($result === TRUE) {
             print_r("Message sent successfully");
         } else {
            print_r("Error.Check internet connection");
         }
 }
// on load current date
if ( $OPPID == 6 ){
    $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
    $dt_day = date_format($dt, 'd-m-Y');
    $idate = $dt_day;
    echo $idate ;
  }
// on load current date  message history
if ( $OPPID == 7 ){
    $MSGFM = $_SESSION['USERIDNEW']; 
    $sql = "SELECT  * FROM `tmsg` WHERE DATE(SYSDT) = CURDATE() AND MSGFM = '$MSGFM'";
    $result = $db->query($sql) ;
    $count = 0;
    if (mysqli_num_rows($result)>0){
        while ($res = mysqli_fetch_array($result)) {
            $count =  $count + 1 ;
        ?>
        <tr id = <?php echo $count ?> class="w3-hover-green">
            <td><?php echo $count ?></td>
            <td><?php echo $res['MSGTY'] ?></td>
            <td><?php echo $res['MSGID'] ?></td>
            <td><?php echo $res['SYSDT'] ?></td>
            <td><?php echo $res['MSGDT'] ?></td>
            <td><?php echo '-' ?></td>
            <td><?php echo '-' ?></td>
        </tr>
     <?php
     }
    }
  }




?>