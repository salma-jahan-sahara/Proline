<?php
session_start() ;
include('config.php') ;
//START:Option-1
    if( ($_POST["opid"]) == 1 )
    {
        $sql = "SELECT LINENO, SONO , BUYER , STYLE , COLOR , SIZE , 
                    INPUTQTY AS INQTY , OUTPUTQTY AS OUTQTY , BALANCEQTY AS QTY FROM 
                (SELECT B.LINENO, B.SONO , B.BUYER , B.STYLE , B.COLOR , B.SIZE, 
                IFNULL(B.INPUTQTY,0.00) INPUTQTY , IFNULL(C.OUTPUTQTY,0.00)OUTPUTQTY , 
                (IFNULL(B.INPUTQTY,0.00) - IFNULL(C.OUTPUTQTY,0.00)) AS BALANCEQTY FROM 
                (SELECT LINENO , SONO , BUYER , STYLE , COLOR , SIZE, SUM(QTYY) AS INPUTQTY FROM 
                (SELECT LINENO , SONO , BUYER , STYLE , COLOR , SIZE ,round(( QTY / NOP ),2) AS QTYY FROM `inputdetails` ) AS A 
                 GROUP BY LINENO , SONO , BUYER , STYLE , COLOR , SIZE 
                HAVING LINENO IN ( SELECT MNO FROM `zpp_machine_mast` WHERE FLOOR IN ('FLOOR-01','FLOOR-02','FLOOR-03','FLOOR-04') )  ORDER BY LINENO , SONO , BUYER ) AS B 
                LEFT JOIN 
                (SELECT LINENO , SONO , BUYER , STYLE , COLOR , SIZE , SUM(QTY) AS OUTPUTQTY  FROM outputdetails 
                GROUP BY LINENO , SONO , BUYER , STYLE , COLOR , TYPE , SIZE , ACTIVEID  
                HAVING LINENO IN ( SELECT MNO FROM `zpp_machine_mast` WHERE FLOOR IN ('FLOOR-01','FLOOR-02','FLOOR-03','FLOOR-04') ) AND TYPE = 'FIT' AND ACTIVEID = 1
                ORDER BY LINENO , SONO , BUYER ) AS C 
                ON B.LINENO = C.LINENO AND B.SONO = C.SONO 
                AND B.BUYER = C.BUYER AND B.STYLE = C.STYLE 
                AND B.COLOR = C.COLOR AND B.SIZE = C.SIZE ) AS D    " ;
        $result = $db->query($sql) ;
        $count = 0;
        if (mysqli_num_rows($result)>0){
                while ($res = mysqli_fetch_array($result)) {
                    $count =  $count + 1 ;
                ?>
        <tr id=<?php echo $count ?>>
            <td> <?php echo $res['LINENO'] ?></td>
            <td> <?php echo $res['SONO'] ?></td>
            <td> <?php echo $res['BUYER'] ?></td>
            <td> <?php echo $res['STYLE'] ?></td>
            <td> <?php echo $res['COLOR'] ?></td>
            <td> <?php echo $res['SIZE'] ?></td>
            <td> <?php echo $res['INQTY'] ?></td>
            <td> <?php echo $res['OUTQTY']?></td>
            <td class="colBalance"> <?php echo $res['QTY'] ?></td>
        </tr>
        <?php
            }
        }
    }
//END:Option-1 
//START:Option-2
    if( ($_POST["opid"]) == 2 )
    {
        $sql = "SELECT LINENO, SONO , BUYER , STYLE , COLOR , 
                INPUTQTY AS INQTY , OUTPUTQTY AS OUTQTY , BALANCEQTY AS QTY FROM 
            (SELECT B.LINENO, B.SONO , B.BUYER , B.STYLE , B.COLOR , 
            IFNULL(B.INPUTQTY,0.00) INPUTQTY , IFNULL(C.OUTPUTQTY,0.00)OUTPUTQTY , 
            (IFNULL(B.INPUTQTY,0.00) - IFNULL(C.OUTPUTQTY,0.00)) AS BALANCEQTY FROM 
            (SELECT LINENO , SONO , BUYER , STYLE , COLOR ,  SUM(QTYY) AS INPUTQTY FROM 
            (SELECT LINENO , SONO , BUYER , STYLE , COLOR , round(( QTY / NOP )) AS QTYY FROM `inputdetails` ) AS A 
            GROUP BY LINENO , SONO , BUYER , STYLE , COLOR 
            HAVING LINENO IN ( SELECT MNO FROM `zpp_machine_mast` WHERE FLOOR IN ('FLOOR-01','FLOOR-02','FLOOR-03','FLOOR-04') )  ORDER BY LINENO , SONO , BUYER ) AS B 
            LEFT JOIN 
            (SELECT LINENO , SONO , BUYER , STYLE , COLOR , SUM(QTY) AS OUTPUTQTY  FROM outputdetails 
            GROUP BY LINENO , SONO , BUYER , STYLE , COLOR , TYPE , ACTIVEID   
            HAVING LINENO IN ( SELECT MNO FROM `zpp_machine_mast` WHERE FLOOR IN ('FLOOR-01','FLOOR-02','FLOOR-03','FLOOR-04') ) AND TYPE = 'FIT' AND ACTIVEID = 1
            ORDER BY LINENO , SONO , BUYER) AS C 
            ON B.LINENO = C.LINENO AND B.SONO = C.SONO 
            AND B.BUYER = C.BUYER AND B.STYLE = C.STYLE 
            AND B.COLOR = C.COLOR ) AS D   " ;
        $result = $db->query($sql) ;
        $count = 0;
        if (mysqli_num_rows($result)>0){
                while ($res = mysqli_fetch_array($result)) {
                    $count =  $count + 1 ;
                ?>
        <tr id=<?php echo $count ?>>
            <td> <?php echo $res['LINENO'] ?></td>
            <td> <?php echo $res['SONO'] ?></td>
            <td> <?php echo $res['BUYER'] ?></td>
            <td> <?php echo $res['STYLE'] ?></td>
            <td> <?php echo $res['COLOR'] ?></td>
            <td> <?php echo $res['INQTY'] ?></td>
            <td> <?php echo $res['OUTQTY']?></td>
            <td class="colBalColor"> <?php echo $res['QTY'] ?></td>
        </tr>
        <?php
            }
        }
    }
//END:Option-2
//START:Option-3
    if( ($_POST["opid"]) == 3 )
    {
        $LINENO = TRIM($_POST["LINENO"]);
        $SONO   = TRIM($_POST["SONO"]);
        $BUYER  = TRIM($_POST["BUYER"]);
        $STYLE  = TRIM($_POST["STYLE"]);
        $COLOR  = TRIM($_POST["COLOR"]);
        
        $sqlSize = " SELECT L.LINENO , L.SONO , L.BUYER , L.STYLE , L.COLOR , L.SIZE , 
        IFNULL(L.INPUTQTY,0) AS INQTY , IFNULL(R.OUTPUTQTY,0) AS OUTQTY ,
        IFNULL( IFNULL(L.INPUTQTY,0) - IFNULL(R.OUTPUTQTY,0) ,0) AS QTY FROM
       (SELECT A.LINENO , A.SONO , A.BUYER , A.STYLE , A.COLOR , A.SIZE , SUM(A.QTYY) AS INPUTQTY FROM 
       (SELECT LINENO , SONO , BUYER , STYLE , COLOR , SIZE , round(QTY/NOP) AS QTYY FROM `inputdetails` WHERE 
       LINENO = '$LINENO' AND SONO = '$SONO' AND BUYER = '$BUYER' AND  STYLE = '$STYLE' AND COLOR = '$COLOR') 
       AS A 
       GROUP BY  A.LINENO , A.SONO , A.BUYER , A.STYLE , A.COLOR , A.SIZE
       ORDER BY  A.LINENO , A.SONO , A.BUYER , A.STYLE , A.COLOR , A.SIZE) AS L  
       LEFT JOIN 
       (SELECT P.LINENO , P.SONO , P.BUYER , P.STYLE , P.COLOR ,  P.SIZE ,  IFNULL(SUM(P.QTY),0) AS OUTPUTQTY FROM 
       (SELECT LINENO , SONO , BUYER , STYLE , COLOR , TYPE , SIZE , IFNULL(SUM(QTY),0) AS QTY FROM `outputdetails` WHERE 
       LINENO = '$LINENO' AND SONO = '$SONO' AND BUYER = '$BUYER' AND  STYLE = '$STYLE' AND COLOR = '$COLOR'
       AND TYPE IN ('FIT','REJ','ADJ') AND ACTIVEID = 1 
       GROUP BY LINENO , SONO , BUYER , STYLE , COLOR , TYPE , SIZE ) P
       GROUP BY P.LINENO , P.SONO , P.BUYER , P.STYLE , P.COLOR , P.SIZE 
       ORDER BY P.LINENO , P.SONO , P.BUYER , P.STYLE , P.COLOR , P.SIZE ) AS R
       ON L.LINENO = R.LINENO AND L.SONO = R.SONO AND L.BUYER = R.BUYER AND L.STYLE = R.STYLE 
          AND L.COLOR = R.COLOR AND L.SIZE = R.SIZE  " ;
        print_r($sqlSize);
        $resultSize = $db->query($sqlSize) ;
        print_r($sqlSize);
        $count = 0;
        if (mysqli_num_rows($resultSize)>0){
                while ($rSize = mysqli_fetch_array($resultSize)) {
                    $count =  $count + 1 ;
                ?>
        <tr id=<?php echo $count ?> ondblclick="onSizeDisplay()">
            <td> <?php echo $rSize['LINENO'] ?></td>
            <td> <?php echo $rSize['SONO'] ?></td>
            <td> <?php echo $rSize['BUYER'] ?></td>
            <td> <?php echo $rSize['STYLE'] ?></td>
            <td> <?php echo $rSize['COLOR'] ?></td>
            <td> <?php echo $rSize['SIZE'] ?></td>
            <td> <?php echo $rSize['INQTY'] ?></td>
            <td> <?php echo $rSize['OUTQTY']?></td>
            <td> <?php echo $rSize['QTY'] ?></td>
        </tr>
        <?php
            }
        }
    }
//END:Option-3
//START:Option-4
    if( ($_POST["opid"]) == 4 )
    {
     //get line number.
     $queryLINE = "SELECT * FROM `zpp_machine_mast` " ;
     $resultLINE = mysqli_query($db, $queryLINE);
     $options = "";
     while($row2LINE = mysqli_fetch_array($resultLINE))
     {
        $options .=  "<option value='".$row2LINE[1]."' >".$row2LINE[1]."</option>";
     }
     echo $options;
    }
//END:Option-4
//START:Option-5
    if( ($_POST["opid"]) == 5 )
    {
        $line = $_POST["LINENO"];
        //get so number.
        $querySO = "SELECT DISTINCT SONO FROM inputdetails WHERE LINENO =  '$line' ORDER BY SONO" ;
        $resultSO = mysqli_query($db, $querySO);
        $options = "";
        while($row2SO = mysqli_fetch_array($resultSO))
        {
            $options .=  "<option value='".$row2SO[0]."' >".$row2SO[0]."</option>";
        }
            echo $options;
     }
//END:Option-5
//START:Option-6
    if( ($_POST["opid"]) == 6 )
    {
         $line = $_POST["LINENO"];
         $Sono = $_POST["SONO"];
    //get so number.
     $queryDis = "SELECT BUDAT , COLOR , NOP , round(SUM(QTY),2) AS QTY , 
                    round((SUM(QTY) / NOP),2) AS QTYPC FROM inputdetails
                    GROUP BY BUDAT , LINENO , SONO , COLOR , NOP 
                    HAVING LINENO = '$line' AND SONO = '$Sono'
                    ORDER BY BUDAT , COLOR" ;
        $resultDis = $db->query($queryDis) ;
        $count = 0;
        if (mysqli_num_rows($resultDis)>0){
                while ($rDis = mysqli_fetch_array($resultDis)) {
                    $count =  $count + 1 ;
                ?>
        <tr id=<?php echo $count ?>>
            <td> <?php echo $count ?></td>
            <td> <?php echo $rDis['BUDAT'] ?></td>
            <td> <?php echo $rDis['COLOR'] ?></td>
            <td> <?php echo $rDis['NOP'] ?></td>
            <td> <?php echo $rDis['QTY'] ?></td>
            <td> <?php echo $rDis['QTYPC']?></td>
        </tr>
        <?php
            }
        }
    }
//END:Option-6
//START:Option-7
        if( ($_POST["opid"]) == 7 )
        {
         $line = $_POST["LINENO"];
         $Sono = $_POST["SONO"];
        //get so number.
         $queryDis = "SELECT BUDAT ,COLOR ,round(SUM(QTY),2) AS QTY FROM outputdetails
                        GROUP BY BUDAT , LINENO , SONO , COLOR , TYPE
                        HAVING LINENO = '$line' AND TYPE = 'FIT' AND SONO = '$Sono' 
                        ORDER BY BUDAT , COLOR " ;
            $resultDis = $db->query($queryDis) ;
            $count = 0;
            if (mysqli_num_rows($resultDis)>0){
                    while ($rDis = mysqli_fetch_array($resultDis)) {
                        $count =  $count + 1 ;
                    ?>
            <tr id=<?php echo $count ?>>
                <td> <?php echo $count ?></td>
                <td> <?php echo $rDis['BUDAT'] ?></td>
                <td> <?php echo $rDis['COLOR'] ?></td>
                <td> <?php echo  '' ?></td>
                <td> <?php echo  '' ?></td>
                <td> <?php echo $rDis['QTY']?></td>
            </tr>
            <?php
                }
            }
        }
//END:Option-7
//START:Option-8
        if( ($_POST["opid"]) == 8 )
        {
        //get so number.
         $queryCM = "SELECT AB.BUDAT,AB.LINENO,AB.CHECKED,AB.OK,
                    CONCAT(AB.DEFECT,'(',CD.DCOUNT,')') AS DEFECT , AB.REJECT FROM 
                    (SELECT BUDAT,LINENO,IFNULL(SUM(QTY),0) AS CHECKED,
                    IFNULL(SUM(CASE WHEN TYPE ='FIT' THEN QTY END),0) AS OK ,  
                    IFNULL(SUM(CASE WHEN TYPE ='DEF' THEN QTY END),0) AS DEFECT ,
                    IFNULL(SUM(CASE WHEN TYPE ='REJ' THEN QTY END),0) AS REJECT 
                    FROM `outputdetails` GROUP BY BUDAT , LINENO 
                    HAVING MONTH(STR_TO_DATE(BUDAT,'%d-%m-%Y')) = MONTH(CURRENT_TIMESTAMP())
                    AND YEAR(STR_TO_DATE(BUDAT,'%d-%m-%Y')) = YEAR(CURRENT_TIMESTAMP())
                    ORDER BY  BUDAT DESC , LINENO ) AS AB 
                    LEFT JOIN 
                    (SELECT o.BUDAT,  o.LINENO  , SUM(r.DFQTY ) AS DCOUNT
                    FROM `rejectdetails` as r  
                    INNER JOIN  `outputdetails` as o ON r.OUTID = o.TID 
                    AND o.TYPE <> 'FIT'
                    GROUP BY  o.BUDAT , o.LINENO
                    HAVING MONTH(STR_TO_DATE(o.BUDAT,'%d-%m-%Y')) = MONTH(CURRENT_TIMESTAMP())
                    AND YEAR(STR_TO_DATE(o.BUDAT,'%d-%m-%Y')) = YEAR(CURRENT_TIMESTAMP())
                    ORDER BY   BUDAT DESC , LINENO) AS CD 
                    ON AB.BUDAT = CD.BUDAT AND AB.LINENO = CD.LINENO " ;
            $resultCM = $db->query($queryCM) ;
            $count = 0;
            if (mysqli_num_rows($resultCM)>0){
                    while ($rCM = mysqli_fetch_array($resultCM)) {
                        $count =  $count + 1 ;
                    ?>
            <tr id=<?php echo $count ?>>
                <td> <?php echo $count ?></td>
                <td> <?php echo $rCM['BUDAT'] ?></td>
                <td> <?php echo $rCM['LINENO'] ?></td>
                <td> <?php echo $rCM['CHECKED'] ?></td>
                <td> <?php echo $rCM['OK'] ?></td>
                <td> <?php echo $rCM['DEFECT']?></td>
                <td> <?php echo $rCM['REJECT']?></td>
            </tr>
            <?php
                }
            }
        }
//END:Option-8
//START:Option-9
         if( ($_POST["opid"]) == 9 )
         {
          //finishing current month.
             $queryCM = "SELECT AB.BUDAT,AB.LNNUM,AB.CHECKED,AB.OK,
                             IFNULL(CONCAT(AB.DEFECT,'(',CD.DCOUNT,')'),0) AS DEFECT , AB.REJECT FROM 
                             (SELECT BUDAT,LNNUM ,IFNULL(SUM(PDQTY),0) AS CHECKED,
                              IFNULL(SUM(CASE WHEN TYPEF ='FIT' THEN PDQTY END),0) AS OK ,  
                              IFNULL(SUM(CASE WHEN TYPEF ='DEF' THEN PDQTY END),0) AS DEFECT ,
                              IFNULL(SUM(CASE WHEN TYPEF ='REJ' THEN PDQTY END),0) AS REJECT 
                              FROM `tpfn` GROUP BY BUDAT , LNNUM
                              HAVING MONTH(STR_TO_DATE(BUDAT,'%d-%m-%Y')) = MONTH(CURRENT_TIMESTAMP())
                              ORDER BY  BUDAT DESC , LNNUM ) AS AB 
                              LEFT JOIN 
                              (SELECT o.BUDAT,  o.LNNUM  , COUNT(r.NAMEF ) AS DCOUNT
                               FROM `tpfd` as r  
                               INNER JOIN  `tpfn` as o ON r.TPFNX = o.TXNID 
                               AND o.TYPEF <> 'FIT'
                               GROUP BY  o.BUDAT , o.LNNUM
                               HAVING MONTH(STR_TO_DATE(o.BUDAT,'%d-%m-%Y')) = MONTH(CURRENT_TIMESTAMP())
                               ORDER BY BUDAT DESC , LNNUM) AS CD 
                               ON AB.BUDAT = CD.BUDAT AND AB.LNNUM = CD.LNNUM " ;
                    $resultCM = $db->query($queryCM) ;
                    $count = 0;
                    if (mysqli_num_rows($resultCM)>0){
                            while ($rCM = mysqli_fetch_array($resultCM)) {
                                $count =  $count + 1 ;
                            ?>
                    <tr id=<?php echo $count ?>>
                        <td> <?php echo $count ?></td>
                        <td> <?php echo $rCM['BUDAT'] ?></td>
                        <td> <?php echo $rCM['LNNUM'] ?></td>
                        <td> <?php echo $rCM['CHECKED'] ?></td>
                        <td> <?php echo $rCM['OK'] ?></td>
                        <td> <?php echo $rCM['DEFECT']?></td>
                        <td> <?php echo $rCM['REJECT']?></td>
                    </tr>
                    <?php
                        }
                    }
         }
//END:Option-9

?>