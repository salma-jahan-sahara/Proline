<?php
//Include database configuration file
include('server.php');
$data = array();

if(isset($_POST["opid"])){

    //Get all state data
    $opid= $_POST['opid'];
    //$opid= 'L-28';
    $i   = 0 ; // index varibale
    $budat = '';
    if (isset($_POST["idate"])){
        $idate = $_POST["idate"] ;
        $dt = new DateTime($idate, new DateTimezone('Asia/Dhaka'));
        $dt_day = date_format($dt, 'd-m-Y');
        $budat = $dt_day;
    } else {
        $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
        $dt_day = date_format($dt, 'd-m-Y');
        $dt_time = date_format($dt, 'h:i a');
        $dt_timeNew = date_format($dt, 'h:i');
        $budat  = $dt_day;
    }

   
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
    $query1 = "SELECT m.FLOOR,COUNT(m.MNO) AS LINENO FROM zpp_machine_mast AS m WHERE m.MNO IN 
              (SELECT DISTINCT LINENO FROM outputdetails WHERE BUDAT = '$budat') GROUP BY m.FLOOR " ;
    $result1 = mysqli_query($db, $query1);
    if (mysqli_num_rows($result1)>0) {
        while($row1 = mysqli_fetch_array($result1)){
            $data[$i]["FLOOR"]   = $row1["FLOOR"];
            $data[$i]["LINENO"] =  $row1["LINENO"];
            $i = $i + 1;
        }
     } 
//end - get floor & running line

//get total operator  & helper 
    // $query2 = "SELECT t.FLOOR ,SUM(t.OPERATOR) AS OPERATOR,SUM(t.HELPER) AS HELPER FROM 
    //            (SELECT m.FLOOR,m.MNO,r.OPERATOR,r.HELPER  FROM zpp_machine_mast as m
    //             INNER JOIN (SELECT SUM(k.OPERATOR) AS OPERATOR , SUM(k.HELPER) AS HELPER , k.LINENO 
    //             FROM kpiview as k  WHERE k.BUDAT = '$budat' AND k.LINENO IN 
    //             (SELECT DISTINCT LINENO FROM outputdetails WHERE BUDAT = '$budat') GROUP BY k.LINENO ) 
    //              AS r ON m.MNO = r.LINENO ) As t GROUP BY  t.FLOOR "  ;

    $query2 = "SELECT c.FLOOR , SUM(c.OPERATOR) AS OPERATOR , SUM(c.HELPER) AS HELPER FROM 
            (SELECT m.FLOOR , d.LINENO , d.OPERATOR , d.HELPER FROM
            (SELECT f.LINENO , round(AVG(f.OPERATOR)) AS OPERATOR , round(AVG(f.HELPER)) AS HELPER FROM
            (SELECT k.LINENO,k.SONO,k.OPERATOR,k.HELPER FROM
            (SELECT DISTINCT LINENO,SONO,OPERATOR,HELPER FROM `kpiview`  WHERE BUDAT = '$budat' ORDER BY LINENO , SONO) as k
            INNER JOIN
            (SELECT DISTINCT LINENO,SONO FROM `outputdetails` WHERE BUDAT = '$budat' ORDER BY LINENO , SONO) as o 
            ON k.LINENO = o.LINENO AND k.SONO = o.SONO ) as f
            GROUP BY f.LINENO ORDER BY f.LINENO ) as d
            INNER JOIN  `zpp_machine_mast` AS m ON d.LINENO = m.MNO ) As c
            GROUP BY c.FLOOR ORDER BY c.FLOOR "  ;
    $result2 = mysqli_query($db, $query2);
    if (mysqli_num_rows($result2)>0) {
         while($row2 = mysqli_fetch_array($result2)){
            foreach ($data as $k => $v) {
                if ( $v["FLOOR"] == $row2["FLOOR"] ) {
                    $data[$k]["OPERATOR"] = $row2["OPERATOR"] ; 
                    $data[$k]["HELPER"] = $row2["HELPER"] ;            
                }
            }
         }
     } 
//end - //get total operator  & helper

//get total no of Running PO or Style
         $query3 =  "SELECT  k.FLOOR, COUNT(o. STYLE) AS RUNPO FROM zpp_machine_mast AS k 
                     INNER JOIN 
                    (SELECT DISTINCT LINENO , STYLE FROM outputdetails 
                     WHERE BUDAT = '$budat' ORDER BY LINENO ) AS o on o.LINENO = k.MNO 
                     GROUP BY k.FLOOR " ;
         $result3 = mysqli_query($db, $query3);
         if (mysqli_num_rows($result3)>0) {
            while($row3 = mysqli_fetch_array($result3)){
                foreach ($data as $k => $v) {
                    if ( $v["FLOOR"] == $row3["FLOOR"] ) {
                        $data[$k]["RUNPO"] = $row3["RUNPO"] ; 
                    }
                }
          }
       } 
//end - get total no of Running PO or Style

//get total target
        $query4 = "SELECT m.FLOOR , SUM(f.TARGET) AS TARGET FROM zpp_machine_mast as m 
                     INNER JOIN 
                     (SELECT  k.LINENO , k.SONO, CEILING (k.TARGET * (k.SPHOUR / k.MP)) AS TARGET  FROM kpiview AS k
                     INNER JOIN 
                     (SELECT DISTINCT LINENO , SONO FROM outputdetails WHERE BUDAT = '$budat' 
                     ORDER BY LINENO , SONO ) as t on k.LINENO = t.LINENO AND k.SONO = t.SONO AND k.BUDAT = '$budat'
                     ORDER BY k.LINENO , k.SONO ) as f 
                     on m.MNO = f.LINENO GROUP BY m.FLOOR" ;
         $result4 = mysqli_query($db, $query4);
            if (mysqli_num_rows($result4)>0) {
                while($row4 = mysqli_fetch_array($result4)){
                    foreach ($data as $k => $v) {
                        if ( $v["FLOOR"] == $row4["FLOOR"] ) {
                             $data[$k]["TARGET"] = $row4["TARGET"] ; 
                        }
                    }
                }
            } 
//end - get total target

//get production , alter , reject 
        $query5 = "SELECT m.FLOOR , t.TYPE , SUM(t.QTY) AS QTY FROM zpp_machine_mast AS m 
                      INNER JOIN 
                     (SELECT o.LINENO , o.TYPE , SUM(o.QTY) AS QTY FROM outputdetails as o 
                      WHERE  o.BUDAT = '$budat' 
                      GROUP BY o.LINENO , o.TYPE ORDER BY o.LINENO , o.TYPE ) as t 
                      ON m.MNO = t.LINENO GROUP BY m.FLOOR , t.TYPE" ;
         $result5 = mysqli_query($db, $query5);
            if (mysqli_num_rows($result5)>0) {
                while($row5 = mysqli_fetch_array($result5)){
                    foreach ($data as $k => $v) {
                        if ( $v["FLOOR"] == $row5["FLOOR"] ) {
                            if ( $row5["TYPE"] == 'FIT' ){
                                $data[$k]["FIT"] = $row5["QTY"] ; 
                            } else if ( $row5["TYPE"] == 'DEFECT' ||  $row5["TYPE"] == 'DEF') {
                                $data[$k]["DEFECT"] = $row5["QTY"] ; 
                            } else if ( $row5["TYPE"] == 'RECENT DEFECTS' ){
                                $data[$k]["RECENT DEFECTS"] = $row5["QTY"] ; 
                            }else if ($row5["TYPE"] ==  'REJECT' ||  $row5["TYPE"] == 'REJ'){
                                $data[$k]["REJECT"] = $row5["QTY"] ; 
                            }else if ($row5["TYPE"] ==  'ADJ' ){
                                $data[$k]["ADJ"] = $row5["QTY"] ; 
                            }

                        }
                    }
                }
             } 
//end - get production , alter , reject

//get trend & current efficiency 
        $query5A =   "SELECT m.FLOOR , SUM(t1.QTY) AS QTY, SUM(t1.MP) AS MP , 
                      SUM(t1.lvpmin)  AS lvpmin , SUM(t1.pTrend) AS pTrend 
                     FROM zpp_machine_mast as m 
                     INNER JOIN 
                     (SELECT k.LINENO , k.SONO , k.BUYER , k.STYLE , t.QTY , k.SPHOUR , k.MP , 
                     (t.QTY * k.SMV ) as lvpmin,
                     ((k.SPHOUR / k.MP ) * 60 ) as pTrend FROM kpiview as k 
                     INNER JOIN 
                     (SELECT LINENO , SONO , BUYER , STYLE , SUM(QTY) AS QTY  FROM `outputdetails` 
                     WHERE BUDAT = '$budat' AND TYPE = 'FIT' GROUP BY LINENO , SONO , BUYER , STYLE) as t 
                     ON k.LINENO = t.LINENO AND k.SONO = t.SONO AND k.BUYER = t.BUYER AND k.STYLE = t.STYLE 
                     AND k.BUDAT = '$budat' )as t1 ON m.MNO = t1.LINENO GROUP BY m.FLOOR ORDER BY m.FLOOR " ;
         $result5A = mysqli_query($db, $query5A);
            if (mysqli_num_rows($result5A)>0) {
                while($row5A = mysqli_fetch_array($result5A)){
                    foreach ($data as $k => $v) {
                        if ( $v["FLOOR"] == $row5A["FLOOR"] ) {
                            //production trend
                            $data[$k]["TREND"] = round((($row5A["QTY"]/$tminu) * $row5A["pTrend"])) ; 
                            //current efficiency
                            $lvsmin = $tminu * $row5A["MP"] ;
                            $data[$k]["CEFF"] =  round(($row5A["lvpmin"] / $lvsmin) * 100 ) ; 
                        }
                    }
                }
             } 
//end - get trend & current efficiency 
 
//Start - get wip
    // $query6  = "SELECT m.FLOOR , SUM(T3.QTY) AS QTY FROM zpp_machine_mast as m
    //             INNER JOIN 
    //             (SELECT T1.LINENO , T1.IQTY , T2.PQTY , (T1.IQTY-T2.PQTY) AS QTY FROM 
    //             (SELECT T.LINENO , CEILING(SUM(T.IQTY)) AS IQTY FROM 
    //             (SELECT LINENO,SONO,BUYER,STYLE,COLOR,QTY,NOP,(QTY/NOP) AS IQTY 
    //             FROM inputdetails WHERE LINENO IN (SELECT DISTINCT LINENO FROM outputdetails 
    //             WHERE BUDAT = '$budat' ) ) T GROUP BY T.LINENO ) T1 
    //             INNER JOIN
    //             (SELECT T.LINENO , SUM(T.QTY) AS PQTY FROM 
    //             (SELECT LINENO,SONO,BUYER,STYLE,COLOR,QTY 
    //             FROM outputdetails WHERE TYPE = 'FIT' AND 
    //             LINENO IN (SELECT DISTINCT LINENO FROM outputdetails WHERE BUDAT = '$budat' ) ) T 
    //             GROUP BY T.LINENO) T2 
    //             ON T1.LINENO = T2.LINENO) T3
    //             ON m.MNO = T3.LINENO GROUP BY m.FLOOR " ;

    $query6  = "SELECT F.FLOOR , SUM(F.QTY) AS QTY FROM 
            (SELECT M.FLOOR , M.MNO , T.QTY FROM zpp_machine_mast as M
            INNER JOIN 
            (SELECT N.LINENO , N.SONO , N.BUYER , N.STYLE , N.COLOR , N.NOP , IFNULL( IFNULL(N.IQTY,0) - IFNULL(U.PQTY,0),0)  AS QTY FROM
                            (SELECT I.LINENO , I.SONO , I.BUYER , I.STYLE , I.COLOR , I.NOP ,  SUM(I.INQTY) AS IQTY FROM
                            (SELECT LINENO , SONO , BUYER , STYLE , COLOR , QTY , NOP  , 
                                IFNULL(ROUND(QTY / NOP),0) AS INQTY 
                            FROM `inputdetails` 
                            ORDER BY LINENO , SONO , BUYER , STYLE , COLOR  ) I 
                            GROUP BY I.LINENO , I.SONO , I.BUYER , I.STYLE , I.COLOR
                            ORDER BY I.LINENO , I.SONO , I.BUYER , I.STYLE , I.COLOR ) AS N 
                            LEFT JOIN 
                            (SELECT T.LINENO , T.SONO , T.BUYER , T.STYLE , T.COLOR , SUM(T.QTY) AS PQTY FROM
                            (SELECT LINENO , SONO , BUYER , STYLE , COLOR ,TYPE, IFNULL(SUM(QTY),0) AS QTY FROM `outputdetails` 
                            WHERE  TYPE IN ('FIT','REJ','ADJ')
                            GROUP BY LINENO , SONO , BUYER , STYLE , COLOR , TYPE 
                            ORDER BY LINENO , SONO , BUYER , STYLE , COLOR ) AS T
                            GROUP BY T.LINENO , T.SONO , T.BUYER , T.STYLE , T.COLOR
                            ORDER BY T.LINENO , T.SONO , T.BUYER , T.STYLE , T.COLOR) AS U  
                            ON N.LINENO = U.LINENO AND 
                            N.SONO = U.SONO AND 
                            N.BUYER = U.BUYER AND 
                            N.STYLE = U.STYLE AND 
                            N.COLOR = U.COLOR ) AS T ON M.MNO =  T.LINENO ) AS F
                            GROUP BY F.FLOOR " ;

    $result6 = mysqli_query($db, $query6);
        if (mysqli_num_rows($result6)>0) {
             while($row6 = mysqli_fetch_array($result6)){
                 foreach ($data as $k => $v) {
                     if ( $v["FLOOR"] == $row6["FLOOR"] ) {
                          $data[$k]["WIP"] = $row6["QTY"] ; 
                     }
                 }
             }
         } 
//end - get wip

//get target eff
    $query7  = "SELECT m.FLOOR , SUM(EFF) AS QTY FROM zpp_machine_mast as m 
                 INNER JOIN 
                 (SELECT t1.LINENO , SUM(t1.EFF) AS EF, COUNT(t1.LINENO) , CEILING(SUM(t1.EFF)/ COUNT(t1.LINENO)) AS EFF 
                 FROM  (SELECT k.EFF,k.LINENO FROM kpiview as k
                 INNER JOIN 
                 (SELECT o.LINENO , o.SONO FROM outputdetails  as o WHERE o.BUDAT = '$budat' ) as t 
                 on k.LINENO = t.LINENO AND k.BUDAT = '$budat'  ORDER BY k.LINENO ) AS t1 GROUP BY t1.LINENO) 
                 as t2 ON m.MNO = t2.LINENO GROUP BY m.FLOOR " ;
    $result7 = mysqli_query($db, $query7);
        if (mysqli_num_rows($result7)>0) {
             while($row7 = mysqli_fetch_array($result7)){
                 foreach ($data as $k => $v) {
                     if ( $v["FLOOR"] == $row7["FLOOR"] ) {
                          $data[$k]["TEFF"] = $row7["QTY"] ; 
                     }
                 }
             }
         } 
//end - target eff

//get current DHU
    $query9  = "SELECT  t2.FLOOR , t2.ALLQTY , t3.FQTY  , round((t3.FQTY/t2.ALLQTY)*100,2) AS QTY FROM 
                    ( SELECT m.FLOOR ,  SUM(QTY) AS ALLQTY FROM outputdetails as o 
                    INNER JOIN zpp_machine_mast as m on o.LINENO = m.MNO AND o.BUDAT = '$budat' 
                    GROUP BY m.FLOOR ) as t2 
                 LEFT JOIN
                    (SELECT m.FLOOR , SUM(QTY) AS FQTY FROM zpp_machine_mast as m 
                     INNER JOIN 
                     (SELECT  o.LINENO,SUM(r.DFQTY) AS QTY FROM `rejectdetails` as r  
                     INNER JOIN  `outputdetails` as o  ON r.OUTID = o.TID AND o.BUDAT = '$budat' 
                     AND o.TYPE <> 'FIT' GROUP BY o.LINENO ) as t ON m.MNO = t.LINENO GROUP BY m.FLOOR) as t3 
                 ON t2.FLOOR = t3.FLOOR " ;
    $result9 = mysqli_query($db, $query9);
        if (mysqli_num_rows($result9)>0) {
             while($row9 = mysqli_fetch_array($result9)){
                 foreach ($data as $k => $v) {
                     if ( $v["FLOOR"] == $row9["FLOOR"] ) {
                          $data[$k]["CDHU"] = $row9["QTY"] ; 
                     }
                 }
             }
         } 
//end - current DHU


//get target DHU
    $query9A  = "SELECT FLOOR , ( DQTY / LINENO ) AS QTY FROM 
                (SELECT m.FLOOR , COUNT(T.LINENO ) AS LINENO , SUM(T.DQTY) AS DQTY FROM zpp_machine_mast m
                INNER JOIN ( SELECT DISTINCT LINENO , DQTY FROM dhu WHERE BUDAT = '$budat' ) T 
                ON m.MNO = T.LINENO GROUP BY  m.FLOOR) T " ;
    $result9A = mysqli_query($db, $query9A);
        if (mysqli_num_rows($result9A)>0) {
             while($row9A = mysqli_fetch_array($result9A)){
                 foreach ($data as $k => $v) {
                     if ( $v["FLOOR"] == $row9A["FLOOR"] ) {
                          $data[$k]["TDHU"] = $row9A["QTY"] ; 
                    }
                 }
             }
         } 
//end - target DHU

} // end opid if.
 
foreach($data as $k => $v) {
     if(array_key_exists("OPERATOR",$v) == false ){ $data[$k]["OPERATOR"] = 0 ;} 
     if(array_key_exists("HELPER",$v) == false ){ $data[$k]["HELPER"] = 0 ;} 
     if(array_key_exists("TREND",$v) == false ){ $data[$k]["TREND"] = 0 ;} 
     if(array_key_exists("WIP",$v) == false ){ $data[$k]["WIP"] = 0 ;} 
     if(array_key_exists("TEFF",$v) == false ){ $data[$k]["TEFF"] = 0 ;} 
     if(array_key_exists("CEFF",$v) == false ){ $data[$k]["CEFF"] = 0 ;} 
     if(array_key_exists("TARGET",$v) == false ){ $data[$k]["TARGET"] = 0 ;} 
     if(array_key_exists("FIT",$v) == false ){ $data[$k]["FIT"] = 0 ;} 
     if(array_key_exists("DEFECT",$v) == false ){ $data[$k]["DEFECT"] = 0 ;} 
     if(array_key_exists("RECENT DEFECTS",$v) == false ){ $data[$k]["RECENT DEFECTS"] = 0 ;} 
     if(array_key_exists("REJECT",$v) == false ){ $data[$k]["REJECT"] = 0 ;} 
     if(array_key_exists("CDHU",$v) == false ){ $data[$k]["CDHU"] = 0 ;} 
     if(array_key_exists("TDHU",$v) == false ){ $data[$k]["TDHU"] = 0 ;} 
     if(array_key_exists("ADJ",$v) == false ){ $data[$k]["ADJ"] = 0 ;} 
     $data[$k]["DIFF"] = (($data[$k]["TARGET"])-($data[$k]["FIT"]));
 }

foreach($data as $k => $v) {
    $data[$k]["DEFECTP"] = round (($data[$k]["DEFECT"] / ( $data[$k]["DEFECT"] + $data[$k]["ADJ"] + $data[$k]["REJECT"] + 
                                                  $data[$k]["FIT"]))*100 , 2 );

    $data[$k]["REJECTP"] = round (($data[$k]["REJECT"] / ( $data[$k]["DEFECT"] +  $data[$k]["ADJ"] + $data[$k]["REJECT"] + 
                                                  $data[$k]["FIT"]))*100 , 2 );
 }

foreach($data as $dat) {
    ?>
        <tr class="item" id="<?php echo ($dat["FLOOR"]) ?>">
            <td class="FLOOR"><?php echo ($dat["FLOOR"])  ?></td>
            <td class="LINENO"><?php echo ($dat["LINENO"]) ?></td>
            <td class="OPERATOR"><?php echo ($dat["OPERATOR"]) ?></td>
            <td class="HELPER"><?php echo ($dat["HELPER"])   ?></td>
            <td class="RUNPO"><?php echo ($dat["RUNPO"]) ?></td>
            <td class="TARGET"><?php echo ($dat["TARGET"]) ?></td>
            <td class="FIT"><?php echo ($dat["FIT"]) ?></td>
            <td class="TREND"><?php echo ($dat["TREND"]) ?></td>
            <td class="DIFF"><?php echo ($dat["DIFF"]) ?></td>
            <td class="WIP"><?php echo ($dat["WIP"]) ?></td>
            <td class="TEFF"><?php echo ($dat["TEFF"]) ?></td>
            <td class="CEFF"><?php  echo ($dat["CEFF"]) ?></td>
            <td class="DEFECT"><?php echo ($dat["DEFECT"]) ?></td>
            <td class="DEFECTP"><?php echo ($dat["DEFECTP"]) ?></td>
            <td class="REJECT"><?php echo ($dat["REJECT"]) ?></td>
            <td class="REJECTP"><?php echo ($dat["REJECTP"]) ?></td>
            <td class="TDHU"><?php echo ($dat["TDHU"]) ?></td>
            <td class="CDHU"><?php echo ($dat["CDHU"]) ?></td>
       </tr>
    <?php
 }

 


 


 
?>