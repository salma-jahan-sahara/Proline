<?php
//Include database configuration file
//include('server.php');
include('config.php') ;
$row = 0;
$headers = [];
$filepath = "/sapshare/input.csv";
//$filepath = "C:\wamp64\www\input.csv";
//main if 
if (file_exists($filepath)) {

    if (($handle = fopen($filepath, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000000, ",")) !== FALSE) {
            if (++$row == 1) {
            $headers = array_flip($data); // Get the column names from the header.
            continue;
            } else {

            $col1 = strtoupper($data[$headers['TABLESTS']]); // Read row by the column name.
            // $col2 = $data[$headers['STYLE_NO']];
                if ( $col1 == 'ZMM_PANEL_ISSUE' ) 
                {
                        $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
                        $dt_day = date_format($dt, 'd-m-Y');
                        $BUDAT  = $dt_day ;
                        $LINENO = $data[$headers['SEWLINEISS']]; 
                        $SONO   = $data[$headers['SONO']]; 
                        $BUYER  = $data[$headers['BUYERNAME']];  
                        $STYLE  = $data[$headers['STYLENO']]; 
                        $COLOR  = $data[$headers['COLOR']]; 
                        $SIZE   = $data[$headers['SIZES']];
                        $QTY    = round($data[$headers['ISSUEQTYISSUE']]);
                        $UID    = 'sa';
                        $NOP    = $data[$headers['NOOPISSUE']];
                        $UPQTY  = round($data[$headers['ISSUEQTYISSUE']]);
                        $UPTIME = $data[$headers['TIMESTAMP']];
                        $MBLNR  = $data[$headers['MBLNR']];
                        $MJAHR  = $data[$headers['MJAHR']];
                        $ZEILE  = $data[$headers['ZEILE']];

                        //check data for insert.(never delete this block code - any time checking can be require)
                            // $sqll = "SELECT * FROM `inputdetails` 
                            //          WHERE LINENO = '$LINENO'  
                            //          AND   SONO = '$SONO' AND COLOR = '$COLOR' AND SIZE = '$SIZE' "; 

                            // if ( $resultt =  mysqli_query($db,$sqll) ) 
                            // {
                            //     $rowCount = mysqli_num_rows($resultt);
                            //     if ( $rowCount > 1 )
                            //     {
                            //       // sql to delete a record
                            //        $sqlll = "DELETE FROM `inputdetails` 
                            //                WHERE LINENO = '$LINENO'  
                            //                AND   SONO = '$SONO' AND COLOR = '$COLOR' AND SIZE = '$SIZE' "; 
                            //         $db->query($sqlll);
                            //     }
                            //     // Free result set
                            //     mysqli_free_result($resultt);
                            // } 
                        //end-check data for insert.(never delete this block code - any time checking can be require)
                        
                        //echo "Row $row: $col1, $col2<br />\n";
                        $sql = "INSERT INTO `inputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`,
                        `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`,`NOP`,`UPQTY`,`UPTIME`,`MBLNR`,`MJAHR`,`ZEILE`) 
                        VALUES ('{$BUDAT}','{$LINENO}','{$SONO}','{$BUYER}',
                        '{$STYLE}','{$COLOR}','{$SIZE}','{$QTY}' , '{$UID}', '{$NOP}','{$UPQTY}','{$UPTIME}','{$MBLNR}','{$MJAHR}','{$ZEILE}')";

                        $result = $db->query($sql) ;
                        
                        if ($result === TRUE) {
                            echo "Row $row: Input: Data Inserted successfully <br />\n";
                        }
                        else 
                        {
                            echo "failed";
                            $msg = 'Input : Error updating record: ' . $db->error;
                            echo $msg;
                        }
                } //end - ZMM_PANEL_ISSUE

                if ( $col1 == 'DAILYLINEWISESEWINGTARGET' ) 
                {
                    //Date Convert 
                    $BUDAT = $data[$headers['BUDAT']];  
                    $date  = new DateTime($BUDAT);
                    $BUDAT = $date->format('d-m-Y'); // 31-07-2012
                    //end - Date Convert 
                    $LINENO     = $data[$headers['MCNO']]; 
                    $SONO       = $data[$headers['VBELN']]; 
                    $BUYER      = $data[$headers['BUYERNAME']]; 
                    $STYLE      = $data[$headers['STYLENO']];
                    $MP         = $data[$headers['MP']]; 
                    $SPHOUR     = $data[$headers['SPHOUR']];
                    $SMV        = $data[$headers['SMV']];
                    $EFF        = $data[$headers['EFFICIENCY']];
                    $TARGET     = $data[$headers['TARGET']];
                    $OPERATOR   = $data[$headers['OPERATOR']];
                    $HELPER     = $data[$headers['HELPER']] ;
                    $UPTIME1    = $data[$headers['TIMESTAMP']];

                    // sql to delete a record
                     $sqllll = " DELETE FROM `kpiview`  
                                 WHERE LINENO = '$LINENO'  
                                 AND   SONO = '$SONO' AND BUDAT = '$BUDAT' "; 

                     $db->query($sqllll);

                    //check data for insert.
                        // $sqlll = "SELECT * FROM `kpiview` 
                        //           WHERE LINENO = '$LINENO'  
                        //           AND   SONO = '$SONO' AND BUDAT = '$BUDAT'  "; 

                        // if ( $resulttt =  mysqli_query($db,$sqlll) ) 
                        // {
                        //     $rowCountt = mysqli_num_rows($resulttt);
                        //     print_r($rowCountt);
                        //     if ( $rowCountt >= 1 )
                        //     {
                        //         // sql to delete a record
                        //         $sqllll = "DELETE FROM `kpiview`  
                        //                 WHERE LINENO = '$LINENO'  
                        //                 AND   SONO = '$SONO' AND BUDAT = '$BUDAT' "; 

                        //                 $db->query($sqllll);
                        //     }

                        //     // Free result set
                        //     mysqli_free_result($resulttt);
                        // } 
                    //end-check data for insert.
                
                    $sql1 = "INSERT INTO `kpiview`(`BUDAT`, `LINENO`, `SONO`, `BUYER`, `STYLE`, 
                                          `MP`, `SPHOUR`, `SMV`, `EFF`, `TARGET`, `OPERATOR`, `HELPER`,`UPTIME`) 
                             VALUES ('{$BUDAT}','{$LINENO}','{$SONO}','{$BUYER}','{$STYLE}',
                                    '{$MP}','{$SPHOUR}','{$SMV}','{$EFF}','{$TARGET}','{$OPERATOR}',
                                    '{$HELPER}','{$UPTIME1}')";
            
                    $result1 = $db->query($sql1) ;

                    //$result1 = TRUE;
                        
                    if ($result1 === TRUE) {
                            echo "Row $row: KPI : Data Inserted successfully For dashboard <br />\n";
                    }
                    else {
                            echo "failed";
                            $msg = 'KPI: Error updating record: For dashboard' . $db->error;
                            echo $msg;
                    }

                }// end - DAILYLINEWISESEWINGTARGET

                // DHU
                if ( $col1 == 'PP_DHU' )
                {
                    //Date Convert 
                    $BUDAT = $data[$headers['BUDATDHU']];  
                    $date  = new DateTime($BUDAT);
                    $BUDAT = $date->format('d-m-Y'); // 31-07-2012
                    //end - Date Convert 
                    $LINENO     = $data[$headers['MCNODHU']]; 
                    $FLOORNO    = $data[$headers['FLOOR']]; 
                    $DQTY       = $data[$headers['EFFICIENCYDHU']];
                    $TIMESTAMP  = $data[$headers['TIMESTAMP']];

                    $sql2 = "INSERT INTO `dhu` (`BUDAT`, `LINENO`, `FLOORNO`, `DQTY`,`LASTTIME`,`UPTIME`) 
                    VALUES ('{$BUDAT}','{$LINENO}','{$FLOORNO}','{$DQTY}','{$TIMESTAMP}','{$TIMESTAMP}')";

                    $result2 = $db->query($sql2) ;
                    if ($result2 === TRUE) {
                        echo "Row $row: DHU: Data Inserted successfully <br />\n";
                    }
                    else {
                        echo "failed";
                        $msg = ' DHU : Error updating record: ' . $db->error;
                        echo $msg;
                    }
                            
                }//end - DHU

                //closeorder 
                if ( $col1 == 'CLOSEORDER' ){

                     //Date Convert 
                     $CDATE = $data[$headers['CDATE']];  
                     $dateN = new DateTime($CDATE);
                     $CDATE = $dateN->format('d-m-Y'); // 31-07-2012
                     //end - Date Convert 
                     $CPLNT  = $data[$headers['CPLNT']]; 
                     $CSONO  = $data[$headers['CSONO']]; 

                     $sql3 = "INSERT INTO `mcrd` (`BUDAT`, `WERKS`, `VBELN`) 
                     VALUES ('{$CDATE}','{$CPLNT}','{$CSONO}')";
 
                     $result3 = $db->query($sql3) ;
                     if ($result3 === TRUE) {
                         echo "Row $row: CloseOrder: Data Inserted successfully <br />\n";
                     }
                     else {
                         echo "failed";
                         $msg = ' CloseOrder : Error updating record: ' . $db->error;
                         echo $msg;
                     }


                }// end - closeorder

            } // end 3nd if - else part.
        }
        fclose($handle);
    }
    unlink($filepath);

} // main if.


?>
