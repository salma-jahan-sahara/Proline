<?php
//Include database configuration file
include('server.php');

//START:Option-1
    //start -get floor 
        if( ($_POST["opid"]) == 1 )
        {
        $queryFL = "SELECT DISTINCT FLOOR  FROM `zpp_machine_mast` WHERE LENGTH (FLOOR) > 1 " ;
        $resultFL = mysqli_query($db, $queryFL);
        $options = "";
        while($rowFL = mysqli_fetch_array($resultFL))
        {
            $options .=  "<option value='".$rowFL[0]."' >".$rowFL[0]."</option>";
        }
        echo $options;
        }
    //end - get floor
//END:Option-1

//START:Option-2
    //start - get server date
        if( ($_POST["opid"]) == 2 ) {
            $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
            $dt_day = date_format($dt, 'd-m-Y');
            $idate = $dt_day;
            echo $idate ;
        }
    //end - get server date
//END:Option-2

//START:Option-3
     //start - sewing input 
    if( ($_POST["opid"]) == 3 )
    {
        $FLOOR = TRIM($_POST["pFLOOR"]);

        $FDATE = TRIM($_POST["fDate"]);
        $dtf = new DateTime($FDATE, new DateTimezone('Asia/Dhaka'));
        $dtf_day = date_format($dtf, 'd-m-Y');
        $FDATE = $dtf_day;

        $TDATE = TRIM($_POST["tDate"]) ;
        $dtt = new DateTime($TDATE, new DateTimezone('Asia/Dhaka'));
        $dtt_day = date_format($dtt, 'd-m-Y');
        $TDATE = $dtt_day;

        

        $whClause = '';
        if ( strpos($FLOOR,'F') === 0){
            $whClause = "SELECT MNO FROM zpp_machine_mast WHERE FLOOR = '$FLOOR'" ;
        } else {
            $whClause = "SELECT MNO FROM zpp_machine_mast WHERE MNO = '$FLOOR'" ;
        }
        // sewing input
            $sql1 = " SELECT A.LINENO , A.SONO , A.BUYER , A.STYLE , A.COLOR , SUM(A.SINPQ) AS SIQTY , 0 AS SOQTY , 0 AS FNQTY ,
                      TRIM(CONCAT(A.LINENO,A.SONO,A.BUYER,A.STYLE,A.COLOR)) AS CKEY
            FROM 
            ( SELECT BUDAT, LINENO , SONO , BUYER , STYLE , COLOR , CEILING(UPQTY/NOP) AS SINPQ 
            FROM `inputdetails` WHERE CONVERT(DATE_FORMAT(STR_TO_DATE(BUDAT,'%d-%m-%Y'),'%Y-%m-%d'),DATE) BETWEEN 
            CONVERT(DATE_FORMAT(STR_TO_DATE('$FDATE','%d-%m-%Y'),'%Y/%m/%d') , DATE )
            AND CONVERT(DATE_FORMAT(STR_TO_DATE('$TDATE','%d-%m-%Y'),'%Y-%m-%d') , DATE)
            AND LINENO IN ( $whClause ) ) AS A
            GROUP BY A.LINENO , A.SONO , A.BUYER , A.STYLE , A.COLOR 
            ORDER BY A.LINENO , A.SONO , A.BUYER , A.STYLE , A.COLOR "; 
            
            $result1 = $db->query($sql1) ;
            $data1 = array();
            while($row1 = mysqli_fetch_array($result1)) {
                $data1[] = $row1;
            }
        // sewing input
        // sewing output
            $sql2 = " SELECT A.LINENO , A.SONO , A.BUYER , A.STYLE , A.COLOR , 0 AS SIQTY , SUM(QTY) AS SOQTY , 0 AS FNQTY , 
            TRIM(CONCAT(A.LINENO,A.SONO,A.BUYER,A.STYLE,A.COLOR)) AS CKEY
            FROM 
            ( SELECT BUDAT, LINENO , SONO , BUYER , STYLE , COLOR , QTY  
            FROM `outputdetails` WHERE CONVERT(DATE_FORMAT(STR_TO_DATE(BUDAT,'%d-%m-%Y'),'%Y-%m-%d'),DATE) BETWEEN 
            CONVERT(DATE_FORMAT(STR_TO_DATE('$FDATE','%d-%m-%Y'),'%Y/%m/%d') , DATE )
            AND CONVERT(DATE_FORMAT(STR_TO_DATE('$TDATE','%d-%m-%Y'),'%Y-%m-%d') , DATE)
            AND LINENO IN ( $whClause ) AND TYPE IN ('FIT') ) AS A
            GROUP BY A.LINENO , A.SONO , A.BUYER , A.STYLE , A.COLOR  
            ORDER BY A.LINENO , A.SONO , A.BUYER , A.STYLE , A.COLOR "; 
    
            $result2 = $db->query($sql2) ;
            $data2 = array();
            while($row2 = mysqli_fetch_array($result2)) {
                $data2[] = $row2;
            }
        // sewing output
        // finishing production
            $sql3 = " SELECT A.LNNUM AS LINENO , A.SONUM AS SONO, A.BUYER , A.STYLE , A.COLOR , 0 AS SIQTY, 0 AS SOQTY, SUM(A.PDQTY) AS FNQTY ,
            TRIM(CONCAT(A.LNNUM,A.SONUM,A.BUYER,A.STYLE,A.COLOR)) AS CKEY
             FROM 
            ( SELECT BUDAT, LNNUM , SONUM , BUYER , STYLE , COLOR , PDQTY
            FROM `tpfn` WHERE CONVERT(DATE_FORMAT(STR_TO_DATE(BUDAT,'%d-%m-%Y'),'%Y-%m-%d'),DATE) BETWEEN 
            CONVERT(DATE_FORMAT(STR_TO_DATE('$FDATE','%d-%m-%Y'),'%Y/%m/%d') , DATE )
            AND CONVERT(DATE_FORMAT(STR_TO_DATE('$TDATE','%d-%m-%Y'),'%Y-%m-%d') , DATE)
            AND LNNUM IN ( $whClause ) AND TYPEF IN ('FIT') ) AS A
            GROUP BY A.LNNUM , A.SONUM , A.BUYER , A.STYLE , A.COLOR 
            ORDER BY A.LNNUM , A.SONUM , A.BUYER , A.STYLE , A.COLOR "; 
    
             $result3 = $db->query($sql3) ;
             $data3 = array();
             while($row3 = mysqli_fetch_array($result3)) {
                    $data3[] = $row3;
             }
        // finishing production
            // final calculation
            $dataCombine = array_merge($data1,$data2,$data3);
            $CKEY = array_column($dataCombine, 'CKEY');
            array_multisort($CKEY,SORT_ASC,$dataCombine);
                foreach ($dataCombine as $k => $v) {
                    $dataUnique[$k]['CKEY'] =  $v['CKEY'];
                }
                $dataUnique = array_unique($dataUnique,SORT_REGULAR);
                foreach($dataUnique as $k => $v){
                    $lvSIQTY = 0;
                    $lvSOQTY = 0;
                    $lvFNQTY = 0;
                    foreach($dataCombine as $k1 => $v1){
                         if ($v['CKEY'] == $v1['CKEY']){
                            $lvSIQTY = $lvSIQTY + $dataCombine[$k1]['SIQTY'];  
                            $lvSOQTY = $lvSOQTY + $dataCombine[$k1]['SOQTY'];  
                            $lvFNQTY = $lvFNQTY + $dataCombine[$k1]['FNQTY']; 
                            $dataNew[$k]['LINENO'] = $dataCombine[$k1]['LINENO'] ;
                            $dataNew[$k]['SONO']  = $dataCombine[$k1]['SONO'] ;
                            $dataNew[$k]['BUYER'] = $dataCombine[$k1]['BUYER'] ;
                            $dataNew[$k]['STYLE'] = $dataCombine[$k1]['STYLE'] ;
                            $dataNew[$k]['COLOR'] = $dataCombine[$k1]['COLOR'] ;
                            $dataNew[$k]['SIQTY'] =  $lvSIQTY ;
                            $dataNew[$k]['SOQTY'] =  $lvSOQTY ;
                            $dataNew[$k]['FNQTY'] =  $lvFNQTY ;
                            $dataNew[$k]['CKEY']  = $dataCombine[$k1]['CKEY'] ;
                         }
                    }
                }
             //final output
             foreach( $dataNew as $res) {
                 ?>
                    <tr id="<?php echo $res['CKEY'] ?>">
                        <td style="display: none;"> <?php echo $res['CKEY'] ?></td>
                        <td> <?php echo $res['LINENO'] ?></td>
                        <td> <?php echo $res['SONO'] ?></td>
                        <td> <?php echo $res['BUYER'] ?></td>
                        <td> <?php echo $res['STYLE'] ?></td>
                        <td> <?php echo $res['COLOR'] ?></td>
                        <td> <?php echo $res['SIQTY'] ?></td>
                        <td> <?php echo $res['SOQTY'] ?></td>
                        <td> <?php echo $res['FNQTY'] ?></td>
                    </tr>
                 <?php
             }
            //------------------------
            unset($data1);
            unset($data2);
            unset($data3);
            unset($dataCombine);
            unset($dataUnique);
            unset($dataNew);
    }
     //end - sewing input
//END:Option-3

//START:Option-4
    //start -get line depend on floor
        if( ($_POST["opid"]) == 4 )
        {
            $FLOOR = RTRIM(LTRIM($_POST["PFLOOR"]));
            $queryLN = "SELECT DISTINCT MNO  FROM `zpp_machine_mast` WHERE FLOOR IN ('$FLOOR') " ;
            $resultLN = mysqli_query($db, $queryLN);
            $options = "";
            while($rowLN = mysqli_fetch_array($resultLN))
            {
                $options .=  "<option value='".$rowLN[0]."' >".$rowLN[0]."</option>";
            }
            echo $options;
        }
    //end - get line depend on floor
//END:Option-4

//START:Option-5
         if( ($_POST["opid"]) == 5 ) {
             //packing report display

            $FLOOR = TRIM($_POST["pFLOOR"]);

            $FDATE = TRIM($_POST["pfDate"]);
            $dtf = new DateTime($FDATE, new DateTimezone('Asia/Dhaka'));
            $dtf_day = date_format($dtf, 'd-m-Y');
            $FDATE = $dtf_day;
    
            $TDATE = TRIM($_POST["ptDate"]) ;
            $dtt = new DateTime($TDATE, new DateTimezone('Asia/Dhaka'));
            $dtt_day = date_format($dtt, 'd-m-Y');
            $TDATE = $dtt_day;

            $sql = " SELECT LNNUM , SONUM , BUYER , STYLE , COLOR, SIZEF ,
                     SUM(CHECKED) AS TOTQT , SUM(FIT) AS FITQT  , SUM(DEF) AS DEFQT , 
                     SUM(REJ) AS REJQT , SUM(ADJ) AS ADJQT
                    FROM 
                        ( SELECT LNNUM , SONUM , BUYER , STYLE , COLOR , SIZEF , IFNULL(SUM(PDQTY),0) AS CHECKED,
                                IFNULL(SUM(CASE WHEN TYPEF ='FIT' THEN PDQTY END),0) AS FIT,  
                                IFNULL(SUM(CASE WHEN TYPEF ='DEF' THEN PDQTY END),0) AS DEF,
                                IFNULL(SUM(CASE WHEN TYPEF ='REJ' THEN PDQTY END),0) AS REJ,
                                IFNULL(SUM(CASE WHEN TYPEF ='ADJ' THEN PDQTY END),0) AS ADJ
                        FROM `tppk`
                        WHERE LNNUM IN ('$FLOOR') AND STR_TO_DATE(BUDAT,'%d-%m-%Y') 
                        BETWEEN STR_TO_DATE('$FDATE','%d-%m-%Y') AND STR_TO_DATE('$TDATE','%d-%m-%Y')
                        GROUP BY LNNUM , SONUM , BUYER , STYLE , COLOR , SIZEF , TYPEF
                        ) AS I GROUP BY LNNUM , SONUM , BUYER , STYLE , COLOR , SIZEF 
                        ORDER BY SONUM DESC "; 
            
            $result = $db->query($sql) ;
            $data = array();
            while($row = mysqli_fetch_array($result)) {
                $data[] = $row;
            }

            foreach( $data as $res) {
                ?>
                   <tr class="pITEM">
                       <td> <?php echo $res['LNNUM'] ?></td>
                       <td> <?php echo $res['SONUM'] ?></td>
                       <td> <?php echo $res['BUYER'] ?></td>
                       <td> <?php echo $res['STYLE'] ?></td>
                       <td> <?php echo $res['COLOR'] ?></td>
                       <td> <?php echo $res['SIZEF'] ?></td>
                       <td class="FTQTY"> <?php echo $res['FITQT'] ?></td>
                       <td class="DFQTY"> <?php echo $res['DEFQT'] ?></td>
                       <td class="RJQTY"> <?php echo $res['REJQT'] ?></td>
                       <td class="ADQTY"> <?php echo $res['ADJQT'] ?></td>
                       <td class="TOQTY"> <?php echo $res['TOTQT'] ?></td>
                   </tr>
                <?php
            }

          }
//END:Option-5
 







?>