<?php
session_start() ;
include('config.php') ;

    $data = $_POST;
    $BUDAT  = $data['BUDAT'];
    $LINENO = $_SESSION['lineNo'] ;
    $SONO   = $data['SONO'];
    $BUYER  = $data['BUYER'];
    $STYLE  = $data['STYLE'];
    $COLOR  = $data['COLOR'];
    $SIZE  = $data['SIZE'];
    $QTY  = $data['QTY'];
    $UID  = $data['UID'];
    $TYPE  = $data['TYPE'];
    $OPID  = $data['OPID'];
    $REJID  = $data['REJID'];


    $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
    $dt = date_format($dt, 'd/m/Y H:i:s');
    $SBUDDAT = $dt ;
 
$sql = "INSERT INTO `outputdetails`
(`BUDAT`, `LINENO`, `SONO`, `BUYER`, 
`STYLE`, `COLOR`, `SIZE`, `QTY`, 
`UID`, `SBUDDAT`, `TYPE`, `OPID`, `REJID`) 
 VALUES 
 ('{$BUDAT}','{$LINENO}','{$SONO}','{$BUYER}',
 '{$STYLE}','{$COLOR}','{$SIZE}','{$QTY}',
 '{$UID}','{$SBUDDAT}','{$TYPE}','{$OPID}','{$REJID}')";

    if ($db->query($sql) === TRUE) {
        echo "<script type='text/javascript'>alert('Data saved successfully.')</script>";
    }
    else 
    {
        echo "failed";
    }

?>