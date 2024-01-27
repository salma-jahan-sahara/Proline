<?php
//Include database configuration file
include('server.php');
$lineNo = $_SESSION['lineNo'];

//option - 33 server date load
if( ($_POST["opid"]) == 33 ) {
    $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
    $dt_day = date_format($dt, 'd-m-Y');
    $idate = $dt_day;
    echo $idate ;
 }
//option - 11 
if( ($_POST["opid"]) == 11 ) {
    $idate = date('d-m-Y', strtotime($_POST["idate"] ));
    $sql = "SELECT TID , BUDAT , LINENO,  SONO , BUYER , STYLE , MP , SPHOUR , SMV , EFF , TARGET , 
            OPERATOR , HELPER , UPTIME , UPDBTIME FROM `kpiview`  
            WHERE LINENO = '$lineNo' AND BUDAT = '$idate'  ORDER BY TID DESC " ;
    $result = $db->query($sql) ;
    $count = 0;
        if (mysqli_num_rows($result)>0){
                    while ($res = mysqli_fetch_array($result)) {
                        $count =  $count + 1 ;
                    ?>
    <tr id=<?php echo $count ?>>
        <td> <?php echo $count ?></td>
        <td> <?php echo $res['TID'] ?></td>
        <td> <?php echo $res['BUDAT'] ?></td>
        <td> <?php echo $res['LINENO'] ?></td>
        <td> <?php echo $res['SONO'] ?></td>
        <td> <?php echo $res['BUYER'] ?></td>
        <td> <?php echo $res['STYLE'] ?></td>
        <td> <?php echo $res['MP'] ?></td>
        <td> <?php echo $res['SPHOUR'] ?></td>
        <td> <?php echo $res['SMV'] ?></td>
        <td> <?php echo $res['EFF'] ?></td>
        <td> <?php echo $res['TARGET'] ?></td>
        <td> <?php echo $res['OPERATOR'] ?></td>
        <td> <?php echo $res['HELPER'] ?></td>
        <td> <?php echo $res['UPTIME'] ?></td>
        <td> <?php echo $res['UPDBTIME'] ?></td>
    </tr>
    <?php
                }
        }
    }

//option - 12 
if( ($_POST["opid"]) == 12 ) {
    $idate = date('d-m-Y', strtotime($_POST["idate"] ));
    $sql = "SELECT TID , BUDAT , LINENO , DQTY , UPTIME  , UPDBTIME
            FROM `dhu`  WHERE LINENO = '$lineNo' AND BUDAT = '$idate'  ORDER BY TID DESC " ;
    $result = $db->query($sql) ;
    $count = 0;
    if (mysqli_num_rows($result)>0){
                while ($res = mysqli_fetch_array($result)) {
                    $count =  $count + 1 ;
                ?>
    <tr id=<?php echo $count ?>>
        <td> <?php echo $count ?></td>
        <td> <?php echo $res['TID'] ?></td>
        <td> <?php echo $res['BUDAT'] ?></td>
        <td> <?php echo $res['LINENO'] ?></td>
        <td> <?php echo $res['DQTY'] ?></td>
        <td> <?php echo $res['UPTIME'] ?></td>
        <td> <?php echo $res['UPDBTIME'] ?></td>
    </tr>
    <?php
                }
        }
 }
//option - 13
if( ($_POST["opid"]) == 13 ) {
    $sql = "SELECT m.MNO,n.BUDAT,n.LINENO,n.SONO,n.BUYER,n.STYLE, n.MP,n.SPHOUR,n.SMV,n.EFF,n.TARGET,
                n.OPERATOR,n.HELPER,n.UPTIME,n.UPDBTIME FROM zpp_machine_mast as m 
            LEFT JOIN 
            (SELECT o.BUDAT,o.LINENO,o.SONO,o.BUYER,o.STYLE,k.MP,k.SPHOUR,k.SMV,k.EFF, k.TARGET,
                k.OPERATOR,k.HELPER,k.UPTIME,k.UPDBTIME FROM outputdetails  AS o 
            LEFT JOIN  kpiview As k ON o.BUDAT = k.BUDAT AND o.LINENO = k.LINENO AND o.SONO = k.SONO
                    AND CURDATE() = DATE_FORMAT(STR_TO_DATE(k.BUDAT,'%d-%m-%Y'),'%Y-%m-%d')
            WHERE o.TID IN (SELECT MAX(TID) FROM outputdetails WHERE 
                            DATE(SYSDT) = CURDATE() GROUP BY LINENO , DATE(SYSDT))) AS n 
            ON m.MNO = n.LINENO WHERE m.MNO IN ( 'L-01','L-02','L-03','L-04','L-05','L-06','L-07','L-08' , 'L-09' , 'L-10' ,
                      'L-11','L-12','L-13','L-14','L-15','L-16','L-17','L-18' , 'L-19' , 'L-20' ,
                      'L-21','L-22','L-24','L-25','L-26','L-27','L-28','L-29' , 'L-30' , 
                      'L-31','L-32','L-33','L-34','L-35','L-36','L-37','L-38','L-39','L-40' ) 
            ORDER BY m.MNO" ;
    $result = $db->query($sql) ;
    $count = 0;
        if (mysqli_num_rows($result)>0){
                    while ($res = mysqli_fetch_array($result)) {
                        $count =  $count + 1 ;
                    ?>
    <tr id=<?php echo $count ?>>
        <td> <?php echo $count ?></td>
        <td> <?php echo $res['MNO'] ?></td>
        <td> <?php echo $res['BUDAT'] ?></td>
        <td> <?php echo $res['LINENO'] ?></td>
        <td> <?php echo $res['SONO'] ?></td>
        <td> <?php echo $res['BUYER'] ?></td>
        <td> <?php echo $res['STYLE'] ?></td>
        <td> <?php echo $res['MP'] ?></td>
        <td> <?php echo $res['SPHOUR'] ?></td>
        <td> <?php echo $res['SMV'] ?></td>
        <td> <?php echo $res['EFF'] ?></td>
        <td> <?php echo $res['TARGET'] ?></td>
        <td> <?php echo $res['OPERATOR'] ?></td>
        <td> <?php echo $res['HELPER'] ?></td>
        <td> <?php echo $res['UPTIME'] ?></td>
        <td> <?php echo $res['UPDBTIME'] ?></td>
    </tr>
    <?php
                    }
            }
 }
//option - 14
if( ($_POST["opid"]) == 14 ) {
        //START:delete duplicate values based on current date.
        //get current date
        $valDate = new DateTime('now', new DateTimezone('Asia/Dhaka'));
        $valDate = date_format($valDate, 'd-m-Y');
        $curDate = $valDate ;

        // sql to delete a record - ztarget
        $sqlDel = " DELETE t1 FROM kpiview t1
            INNER JOIN kpiview t2 
            WHERE t1.TID < t2.TID  AND t1.LINENO = t2.LINENO AND 
                t1.SONO = t2.SONO AND t1.BUYER = t2.BUYER   AND 
                t1.STYLE = t2.STYLE AND t1.BUDAT = t2.BUDAT AND t1.BUDAT = '$curDate'"; 
        $resDel = $db->query($sqlDel);
        if ($resDel === TRUE) {
            echo "ZTarget: Adjusted successfully.\n";
        } else {
            echo "ZTarget: Data not found.\n";
        }

        // sql to delete a record - dhu
        $sqlDhu = " DELETE t1 FROM dhu t1
            INNER JOIN dhu t2 
            WHERE t1.TID < t2.TID AND t1.LINENO = t2.LINENO AND 
                t1.BUDAT = t2.BUDAT AND t1.BUDAT = '$curDate' "; 
        $resDhu = $db->query($sqlDhu);
        //End:delete duplicate values based on current date.
        if ($resDhu === TRUE) {
            echo "DHU: Adjusted successfully.\n";
        } else {
            echo "DHU: Data not found.\n";
        }
 }

//option 15 - all message load based on current date
if ( ($_POST["opid"]) == 15 ) {
    $sql = "SELECT  * FROM `tmsg` WHERE DATE(SYSDT) = CURDATE()";
    $result = $db->query($sql) ;
    $count = 0;
    if (mysqli_num_rows($result)>0){
        while ($res = mysqli_fetch_array($result)) {
            $count =  $count + 1 ;
        ?>
        <tr id = <?php echo $count ?> class="w3-hover-green">
            <td><?php echo $count ?></td>
            <td><?php echo $res['MSGID'] ?></td>
            <td><?php echo $res['MSGTY'] ?></td>
            <td><?php echo $res['MSGFM'] ?></td>
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