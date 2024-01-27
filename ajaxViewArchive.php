<?php
//Include database configuration file
include('config.php') ;
session_start();  
$lineNo = $_SESSION['lineNo'];


//START:get yesterday date
$date = new DateTime();
$date->sub(new DateInterval('P1D'));
$lvToDate =  $date->format('d-m-Y');
$lvFmDate = '01-01-2020';
//END:get yesterday date

//START:Delete archive rows
$sqlDelete = "DELETE FROM `archiveeff`";  
$db->query($sqlDelete);
//START:Delete archive rows


$sql = "  SELECT  a.BUDAT ,m.FLOOR, a.LINENO , a.SONO , a.BUYER , a.STYLE , IFNULL(a.QTY,0.01)QTY , 
          IFNULL(a.TARGET,0.01)TARGET , IFNULL(a.OPERATOR,0.01)OPERATOR , IFNULL(a.HELPER,0.01)HELPER , 
          IFNULL(a.MP,0.01)MP, IFNULL(a.EFF,0.01)EFF , IFNULL(a.SPHOUR,0.01)SPHOUR ,  IFNULL(round(a.SMV,2),0.01) SMV FROM
          (SELECT o.BUDAT , o.LINENO , o.SONO , o.BUYER , o.STYLE , o.QTY , 
          k.TARGET , k.OPERATOR , k.HELPER , k.MP , k.EFF , k.SPHOUR , k.SMV FROM
          (SELECT BUDAT , LINENO , SONO , BUYER , STYLE , SUM(QTY) AS QTY 
          FROM outputdetails  GROUP BY BUDAT , LINENO , SONO , BUYER , STYLE 
          HAVING LINENO IN ('L-21','L-22','L-24','L-25','L-26','L-27','L-28') 
          AND STR_TO_DATE(BUDAT,'%d-%m-%Y') 
          BETWEEN STR_TO_DATE('$lvFmDate','%d-%m-%Y') AND STR_TO_DATE('$lvToDate','%d-%m-%Y') ) AS o 
          LEFT JOIN kpiview AS k on o.BUDAT = k.BUDAT AND o.LINENO = k.LINENO 
          AND o.SONO = k.SONO AND o.BUYER = k.BUYER AND o.STYLE = k.STYLE ) AS a
          LEFT JOIN zpp_machine_mast AS m on a.LINENO = m.MNO " ;
$result = $db->query($sql) ;
if (mysqli_num_rows($result)>0){
    while ($res = mysqli_fetch_array($result)) {

           
           $BUDAT = $res['BUDAT'];
           $FLOOR = $res['FLOOR'];
           $LNNUM = $res['LINENO'];
           $SONUM = $res['SONO'];
           $BUYER = $res['BUYER'];
           $STYLE = $res['STYLE'];
           $OPRTR = $res['OPERATOR'];
           $HLPER = $res['HELPER'];
           $MANPW = $res['MP'];
           $EFFTG = $res['EFF'];
           $SPHUR = $res['SPHOUR'];
           $SMVFL = $res['SMV'];
           $DAYTG = $res['TARGET'];
           $PRDQT = $res['QTY'];

           $TOTTM = 420;
           $EFFAC = 0.01;
           if ($MANPW == 0.01) {  
               $EFFAC = 0.01 ; 
            } else {
              $lv_A = $SMVFL * $PRDQT ;
              $lv_B = $TOTTM * $MANPW ;
              $EFFAC = round(( $lv_A / $lv_B ) * 100 , 2) ;
          }

          $dt = new DateTime($res['BUDAT'], new DateTimezone('Asia/Dhaka'));
          $dt = date_format($dt, 'Y-m-d');
          $DAYFL = $dt ;


            $sqlInsert = "INSERT INTO `archiveeff`
                        ( `BUDAT`, `FLOOR`,`LNNUM` , `SONUM` , `BUYER` , `STYLE` , 
                          `OPRTR`, `HLPER`,`MANPW` , `EFFTG` , `EFFAC` , `SPHUR` , 
                          `SMVFL`, `DAYTG`, `PRDQT`, `TOTTM` ,`DAYFL`) 
                          VALUES
                        ('{$BUDAT}','{$FLOOR}','{$LNNUM}','{$SONUM}','{$BUYER}','{$STYLE}',
                         '{$OPRTR}','{$HLPER}','{$MANPW}','{$EFFTG}','{$EFFAC}','{$SPHUR}',
                         '{$SMVFL}','{$DAYTG}','{$PRDQT}','{$TOTTM}','{$DAYFL}');" ;

            $db->query($sqlInsert);
    }
}

?>