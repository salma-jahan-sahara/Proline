<?php
session_start() ;
include('config.php') ;
$lineNo = $_SESSION['lineNo']; 
if (($_POST["opid"]) == 2) {
$chk = $_POST["chk"];
$_SESSION['tblname'] = 'outputdetails';
$idate = date('d-m-Y', strtotime($_POST["idate"] ));
$sql1 = "";
    if ( $chk == "true" ){
        $sql1 = "SELECT LINENO, SONO , BUYER , STYLE , COLOR , SIZE , SUM(QTY) AS QTY , TYPE
        FROM `outputdetails`  GROUP BY BUDAT,TYPE,LINENO,SONO,BUYER,STYLE,COLOR,SIZE
        HAVING BUDAT = '$idate' AND TYPE = 'FIT' AND  QTY <> 0 
        ORDER BY LINENO, SONO , BUYER , STYLE , COLOR , SIZE , TYPE " ;
    } else if (  $chk == "false" ) {
        $sql1 = "SELECT LINENO, SONO , BUYER , STYLE , COLOR , SIZE , SUM(QTY) AS QTY , TYPE , BUDAT
        FROM `outputdetails`  GROUP BY BUDAT,TYPE,LINENO,SONO,BUYER,STYLE,COLOR,SIZE
        HAVING BUDAT = '$idate' AND TYPE = 'FIT' AND LINENO IN ('$lineNo') AND QTY <> 0 
        ORDER BY LINENO, SONO , BUYER , STYLE , COLOR , SIZE , TYPE " ;
    }
$result1 = $db->query($sql1) ;
$count1 = 0;
    if (mysqli_num_rows($result1)>0){
            while ($res1 = mysqli_fetch_array($result1)) {
                $count1 =  $count1 + 1 ;
            ?>
            <tr id = <?php echo $count1 ?>>
                <td style="display:none;"> <?php echo $res1['BUDAT'] ?></td>
                <td> <?php echo $res1['LINENO'] ?></td>
                <td> <?php echo $res1['SONO'] ?></td>
                <td> <?php echo $res1['BUYER'] ?></td>
                <td> <?php echo $res1['STYLE'] ?></td>
                <td> <?php echo $res1['COLOR'] ?></td>
                <td> <?php echo $res1['SIZE'] ?></td>
                <td> <?php echo $res1['QTY'] ?></td>
                <td> <?php echo $res1['TYPE'] ?></td>
                <td> <?php echo $count1 ?></td>
            </tr>
        <?php
        }
    }
} // end - if condition - 2 part

if( ($_POST["opid"]) == 33 ) {
    $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
    $dt_day = date_format($dt, 'd-m-Y');
    $idate = $dt_day;
    echo $idate ;
    
}

?>