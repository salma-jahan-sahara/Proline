<?php
//Include database configuration file
include('server.php');

if(isset($_POST["opid"])){
    //Get all state data
    $opid= $_POST['opid'];

    $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
    $dt_day = date_format($dt, 'd-m-Y');
    $dt_time = date_format($dt, 'h:i a');
    $idate = $dt_day;


    // get total minutes
    $starhour = date_format(new DateTime('08:00:00') , 'H');
    $currhour = date_format($dt, 'H'); 
    $currmin = date_format($dt, 'i'); 
    $tminu = (abs($starhour - $currhour ) * 60 ) + $currmin ;
    // end-get total minutes

    $sql = " SELECT a.LINENO,a.BUYER,a.STYLE,a.MP,a.SPHOUR,a.SMV,a.TARGET,b.QTY,ABS(a.TARGET - b.QTY) AS VAR 
             FROM kpiview as a inner join 
            ( SELECT  BUDAT,LINENO,BUYER,STYLE, SUM(QTY) AS QTY
              FROM `outputdetails` GROUP BY BUDAT,LINENO,BUYER,STYLE  Having BUDAT = '$idate' ) 
              as b on  
              a.LINENO = b.LINENO and
              a.BUYER  = b.BUYER  and
              a.STYLE  = b.STYLE  and 
              a.BUDAT  = '$idate' " ;
    $result = $db->query($sql) ;

    $count = 0;
    if (mysqli_num_rows($result)>0){
    while ($res = mysqli_fetch_array($result)) {
        $count =  $count + 1 ;
        //trend 
            $lvtnd = ($res["QTY"] / $tminu ) * $res["SPHOUR"];
            $trend = round($lvtnd);
        //end - trend 

        //effiency
            $lvtar = round( ($res["MP"] * $res["SPHOUR"])/  $res["SMV"]);
            $lveff = strval( number_format((( $res["QTY"] / $lvtar ) * 100) , 2));
            $eff = $lveff.'%';
        //end-effiency
    ?>
    <tr id = <?php echo $count ?>>
        <td> <?php echo $res['LINENO'] ?></td>
        <td> <?php echo $res['BUYER']  ?></td>
        <td> <?php echo $res['STYLE']  ?></td>
        <td> <?php echo '' ?></td>
        <td> <?php echo $res['MP'] ?></td>
        <td> <?php echo $res['SMV'] ?></td>
        <td> <?php echo $res['TARGET'] ?></td>
        <td> <?php echo $res['QTY'] ?></td>
        <td> <span><?php echo $res['VAR'] ?> <span>▼</span> </span>   </td>
        <td> <?php echo $trend ?></td>
        <td> <?php echo '' ?></td>
        <td> <?php echo $eff ?></td>
        <td> <?php echo '' ?></td>
    </tr>
 <?php
 }
}

    echo  json_encode($result) ;

}
          
?>