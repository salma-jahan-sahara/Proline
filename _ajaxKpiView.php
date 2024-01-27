<?php
//Include database configuration file
include('server.php');

if(isset($_POST["opid"])){
    //Get all state data
    $opid= $_POST['opid'];
    $lineNo = $_SESSION['lineNo'];
    //$opid= 'L-28';
    $opid= $lineNo ;

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
    $query1 = "SELECT sum(a.QTY) AS QTY  FROM outputdetails as a GROUP BY  a.LINENO , a.BUDAT , a.TYPE , a.ACTIVEID 
               HAVING a.LINENO='$opid' and a.BUDAT='$budat' and a.TYPE='FIT' and a.ACTIVEID=1 " ;

    $query2 = "SELECT sum(a.QTY) AS QTY  FROM outputdetails as a GROUP BY  a.LINENO , a.BUDAT ,  a.ACTIVEID
               HAVING a.LINENO='$opid' and a.BUDAT='$budat' and a.ACTIVEID=1 " ;

     //before nop correction 
        // $query4 = " SELECT T1.LINENO , T1.IQTY , T2.PQTY , (T1.IQTY-T2.PQTY) AS QTY FROM 
        //             (SELECT T.LINENO , CEILING(SUM(T.IQTY)) AS IQTY FROM 
        //             (SELECT LINENO,SONO,BUYER,STYLE,COLOR,QTY,NOP,(QTY/NOP) AS IQTY 
        //             FROM inputdetails WHERE LINENO IN ('$opid') ) T
        //             GROUP BY T.LINENO ) T1 
        //             INNER JOIN
        //             (SELECT T.LINENO , SUM(T.QTY) AS PQTY FROM 
        //             (SELECT LINENO,SONO,BUYER,STYLE,COLOR,QTY 
        //             FROM outputdetails WHERE TYPE = 'FIT' AND ACTIVEID=1 AND LINENO IN ('$opid') ) T 
        //             GROUP BY T.LINENO) T2 
        //             ON T1.LINENO = T2.LINENO" ;

    //after nop correction 
        $query4 = "SELECT F.LINENO , SUM(F.QTY) AS QTY FROM 
                (SELECT N.LINENO , N.SONO , N.BUYER , N.STYLE , N.COLOR , N.NOP , IFNULL( IFNULL(N.IQTY,0) - IFNULL(U.PQTY,0),0) AS QTY FROM
                        (SELECT I.LINENO , I.SONO , I.BUYER , I.STYLE , I.COLOR , I.NOP ,  SUM(I.INQTY) AS IQTY FROM
                        (SELECT LINENO , SONO , BUYER , STYLE , COLOR , QTY , NOP  , 
                            IFNULL(ROUND(QTY / NOP),0) AS INQTY 
                        FROM `inputdetails` 
                        WHERE LINENO IN ('$opid') 
                        ORDER BY LINENO , SONO , BUYER , STYLE , COLOR  ) I 
                        GROUP BY I.LINENO , I.SONO , I.BUYER , I.STYLE , I.COLOR
                        ORDER BY I.LINENO , I.SONO , I.BUYER , I.STYLE , I.COLOR ) AS N 
                        LEFT JOIN 
                        (SELECT T.LINENO , T.SONO , T.BUYER , T.STYLE , T.COLOR , SUM(T.QTY) AS PQTY FROM
                        (SELECT LINENO , SONO , BUYER , STYLE , COLOR ,TYPE, IFNULL(SUM(QTY),0) AS QTY FROM `outputdetails` 
                        WHERE LINENO IN ('$opid')  AND TYPE IN ('FIT','REJ','ADJ')
                        GROUP BY LINENO , SONO , BUYER , STYLE , COLOR , TYPE 
                        ORDER BY LINENO , SONO , BUYER , STYLE , COLOR ) AS T
                        GROUP BY T.LINENO , T.SONO , T.BUYER , T.STYLE , T.COLOR
                        ORDER BY T.LINENO , T.SONO , T.BUYER , T.STYLE , T.COLOR ) AS U  
                        ON N.LINENO = U.LINENO AND 
                        N.SONO = U.SONO AND 
                        N.BUYER = U.BUYER AND 
                        N.STYLE = U.STYLE AND 
                        N.COLOR = U.COLOR ) F 
                        GROUP BY F.LINENO" ;


     $query5 = " SELECT * FROM kpiview as k where k.LINENO = '$opid' AND k.BUDAT= '$budat'
     AND k.SONO IN ( SELECT DISTINCT SONO FROM outputdetails WHERE LINENO='$opid' AND BUDAT = '$budat' AND ACTIVEID=1 ) " ;

     $query6 = " SELECT SUM(r.DFQTY) AS QTY FROM `rejectdetails` as r  INNER JOIN  `outputdetails` as o  
     ON r.OUTID = o.TID AND o.LINENO = '$opid' AND o.BUDAT = '$budat' AND o.TYPE <> 'FIT' AND o.ACTIVEID=1 " ;

     $query3  =  "SELECT SONO FROM `outputdetails` WHERE TID = (SELECT MAX(TID) FROM `outputdetails` 
     GROUP BY LINENO , ACTIVEID HAVING LINENO = '$opid' AND ACTIVEID=1 )" ;
     $MAXSONO = mysqli_query($db, $query3);

    while($row3 = mysqli_fetch_array($MAXSONO)){
        $data["MAXSONO"] = $row3["SONO"];
        $MXSONO  = $data["MAXSONO"] ;
    }

    $query  = "SELECT * FROM kpiview WHERE LINENO='$opid' and BUDAT='$budat' and SONO = '$MXSONO'" ;
               
    $result      = mysqli_query($db, $query);
    $qty         = mysqli_query($db, $query1);
    $faultqty    = mysqli_query($db, $query2);
    $WIPTOTAL    = mysqli_query($db, $query4);
    $neweff      = mysqli_query($db, $query5);
    $newfqty     = mysqli_query($db, $query6);
    

    while($row1 = mysqli_fetch_array($qty)){
        $data["ACTUAL"] = $row1["QTY"];
    }

    while($row2 = mysqli_fetch_array($faultqty)){
        $data["ALLQTY"] = $row2["QTY"] ;
    }

    while($row6 = mysqli_fetch_array($newfqty)){
        $data["FAULTQTY"] = $row6["QTY"] ;
    }


    if (mysqli_num_rows($WIPTOTAL)>0) {
        $addqty = 0 ;
        while($row4 = mysqli_fetch_array($WIPTOTAL)){
            $addqty = $addqty + $row4["QTY"] ;
        }
        $data["WIPQTY"] =  round( $addqty);
    }

    while($row = mysqli_fetch_array($result)){

        $data["LINENO"] = $row["LINENO"];
        $data["BUYER"]  = $row["BUYER"];
        $data["STYLE"]  = $row["STYLE"];
      
        //new effiency - with multiple so 
        if (mysqli_num_rows($neweff)>0) {
            $adddaytar   = 0;
            $addtar      = 0;
            $addeff      = 0;
            $addteff     = 0;
            $addtrend    = 0;
            $addcount    = 0;
            while($row5 = mysqli_fetch_array($neweff)){
                
                // counter
                $addcount =  $addcount + 1 ; 
                //target calculation 
                $addtar  = $addtar + round(( $row5["TARGET"] / 60 ) * $tminu ) ; 
                // daytarget calculation
                $adddaytar = $adddaytar + round(( $row5["TARGET"] ) * ($row5["SPHOUR"]/$row5["MP"] ) ); 
                // target efficiency calculation
                $addteff  = $addteff + $row5["EFF"] ; 
                //eff calculation
                $lvpmin = round( $row5["SMV"] * $data["ACTUAL"] ) ;
                $lvsmin =  round ( $tminu * $row5["MP"] );
                $lveff  =  round (( $lvpmin / $lvsmin ) * 100 );
                $addeff =  $addeff + $lveff ; 
                //end - eff calculation
                //trend calculation
                $addtrend = $addtrend + ($data["ACTUAL"] / $tminu ) * ( ($row5["SPHOUR"] / $row5["MP"]) * 60 );
            }

            //target
               $starget = round( $addtar / $addcount );
               $data["TARGET"]  = $starget;
            //end- target
            //day target
               $dtarget    = round($adddaytar / $addcount );
               $data["DAYTARGET"]  = $dtarget;
            //end - day target
            //target eff
               $lvteff = round($addteff / $addcount ) ;
               $data["TEFF"] = $lvteff . '%' ;
            //end - target eff
            //acutal eff
               $lveff = round ($addeff / $addcount );
               $data["EFF"] = $lveff.'%';
            //end-acutal eff
            //trend 
              $lvnewtnd   = round ($addtrend / $addcount);
              $data["TREND"]    = round($lvnewtnd);
            //end-trend 
        }
        //end-new effiency - with multiple so 

        //DHU
        $lvtnd = ( $data["FAULTQTY"] /  $data["ALLQTY"] ) * 100 ;
        $data["DHU"] = number_format((float)$lvtnd, 2, '.', '') . '%';
        //end - DHU

        //WIPTOTAL
         $data["WIPTOTAL"]  = $data["WIPQTY"];
        //end - WIPTOTAL

        $data["OPERATOR"]   = $row["OPERATOR"];
        $data["HELPER"]     = $row["HELPER"];
        $data["DTIME"]      = $dt_time;

    }

    echo  json_encode($data) ;
 
}
          
?>