<?php

// Start: session variable 
    session_start() ;
    include('config.php') ;
    $lineNo = $_SESSION['lineNo']; 
    $SONO   = $_SESSION['sono'] ; 
    $data   = $_POST;
    $OPPID  = $data['OPPID'];
    $UID    = $_SESSION['username']; 

// sewing or finishing type variable
        if (isset($data['PRDTY'])) {
            $PRDTY  = trim($data['PRDTY']); 
        } else {
            $PRDTY  = "";
        }
// End: session variable

//START:Option-1-get session variable
    if ($OPPID == 1 )
    {
        print_r(json_encode($_SESSION)); 
    }
//END:Option-1

//START:Option-2 -get reject list
    if( $OPPID == 2 )
    {
       $queryRJ  = "SELECT r.name FROM `rejectlist` as r " ;
       $resultRJ = mysqli_query($db, $queryRJ);
       if (mysqli_num_rows($resultRJ)>0){
            while($row = mysqli_fetch_array($resultFL)){
                ?>
                 <tr class="w3-hover-green" onclick="onReject(this)">
                     <td><input type="checkbox"></td>
                     <td><?php echo $row['name']; ?></td>
                 </tr>
            <?php
            }
       }
    }
//END:Option-2
 

//START:Option-3 myBOX implementation
    if( $OPPID == 3 )
    {
         $BUDAT  = $data['VBUDAT'];
         $SONOLV = $data['VSNO'];
         $COLOR  = $data['VCOLOR'];
         $LINENOLV =  $lineNo ;
        // sewing output
            $sqlSB = "SELECT LINENO,SONO,BUYER,STYLE,COLOR,SIZE, 
                    IFNULL(SUM(case when col = 'FIT' then VALUE end),0) OK,
                    IFNULL(SUM(case when col = 'DEF' then VALUE end),0) DEFECT,
                    IFNULL(SUM(case when col = 'REJ' then VALUE end),0) REJECT,
                    IFNULL(SUM(VALUE),0) AS TOTALQTY
                    FROM
                    (
                        SELECT LINENO,SONO,BUYER,STYLE,COLOR,SIZE, TYPE AS col,  QTY VALUE 
                        FROM outputdetails
                        WHERE BUDAT = '$BUDAT' AND LINENO = '$LINENOLV' AND SONO ='$SONOLV' AND COLOR = '$COLOR'
                    ) d
                    GROUP BY LINENO,SONO,BUYER,STYLE,COLOR,SIZE "; 
                     
            $resultSB = $db->query($sqlSB) ;
            $dataSB = array();
            while($rowSB = mysqli_fetch_array($resultSB)) {
                $dataSB[] = $rowSB;
            }

            foreach( $dataSB as $res) {
                ?>
                   <tr>
                       <td> <?php echo $res['LINENO'] ?></td>
                       <td> <?php echo $res['SONO'] ?></td>
                       <td> <?php echo $res['BUYER'] ?></td>
                       <td> <?php echo $res['STYLE'] ?></td>
                       <td> <?php echo $res['COLOR'] ?></td>
                       <td> <?php echo $res['SIZE'] ?></td>
                       <td> <?php echo $res['OK'] ?></td>
                       <td> <?php echo $res['DEFECT'] ?></td>
                       <td> <?php echo $res['REJECT'] ?></td>
                       <td> <?php echo $res['TOTALQTY'] ?></td>
                   </tr>
                <?php
            }
        // sewing output
     } elseif ($OPPID == 4) {
        // finishing output
        $BUDAT  = $data['VBUDAT'];
        $SONOLV = $data['VSNO'];
        $COLOR  = $data['VCOLOR'];
        $LINENOLV =  $lineNo ;
           $sqlSB = "SELECT LNNUM AS LINENO,SONUM AS SONO,BUYER,STYLE,COLOR,SIZEF AS SIZE, 
                   IFNULL(SUM(case when col = 'FIT' then VALUE end),0) OK,
                   IFNULL(SUM(case when col = 'DEF' then VALUE end),0) DEFECT,
                   IFNULL(SUM(case when col = 'REJ' then VALUE end),0) REJECT,
                   IFNULL(SUM(VALUE),0) AS TOTALQTY
                   FROM
                   (
                       SELECT LNNUM,SONUM,BUYER,STYLE,COLOR,SIZEF, TYPEF AS col,  PDQTY VALUE 
                       FROM tpfn
                       WHERE BUDAT = '$BUDAT' AND LNNUM = '$LINENOLV' AND SONUM ='$SONOLV' AND COLOR = '$COLOR'
                   ) d
                   GROUP BY LNNUM,SONUM,BUYER,STYLE,COLOR,SIZEF ";         
           $resultSB = $db->query($sqlSB) ;
           $dataSB = array();
           while($rowSB = mysqli_fetch_array($resultSB)) {
               $dataSB[] = $rowSB;
           }

           foreach( $dataSB as $res) {
               ?>
                  <tr>
                      <td> <?php echo $res['LINENO'] ?></td>
                      <td> <?php echo $res['SONO'] ?></td>
                      <td> <?php echo $res['BUYER'] ?></td>
                      <td> <?php echo $res['STYLE'] ?></td>
                      <td> <?php echo $res['COLOR'] ?></td>
                      <td> <?php echo $res['SIZE'] ?></td>
                      <td> <?php echo $res['OK'] ?></td>
                      <td> <?php echo $res['DEFECT'] ?></td>
                      <td> <?php echo $res['REJECT'] ?></td>
                      <td> <?php echo $res['TOTALQTY'] ?></td>
                  </tr>
               <?php
           }
        // finishing output
     } elseif ($OPPID == 41) {
        // packing output
        $BUDAT  = $data['VBUDAT'];
        $SONOLV = $data['VSNO'];
        $COLOR  = $data['VCOLOR'];
        $LINENOLV =  $lineNo ;
           $sqlSB = "SELECT LNNUM AS LINENO,SONUM AS SONO,BUYER,STYLE,COLOR,SIZEF AS SIZE, 
                   IFNULL(SUM(case when col = 'FIT' then VALUE end),0) OK,
                   IFNULL(SUM(case when col = 'DEF' then VALUE end),0) DEFECT,
                   IFNULL(SUM(case when col = 'REJ' then VALUE end),0) REJECT,
                   IFNULL(SUM(VALUE),0) AS TOTALQTY
                   FROM
                   (
                       SELECT LNNUM,SONUM,BUYER,STYLE,COLOR,SIZEF, TYPEF AS col,  PDQTY VALUE 
                       FROM tppk
                       WHERE BUDAT = '$BUDAT' AND LNNUM = '$LINENOLV' AND SONUM ='$SONOLV' AND COLOR = '$COLOR'
                   ) d
                   GROUP BY LNNUM,SONUM,BUYER,STYLE,COLOR,SIZEF ";         
           $resultSB = $db->query($sqlSB) ;
           $dataSB = array();
           while($rowSB = mysqli_fetch_array($resultSB)) {
               $dataSB[] = $rowSB;
           }

           foreach( $dataSB as $res) {
               ?>
                  <tr>
                      <td> <?php echo $res['LINENO'] ?></td>
                      <td> <?php echo $res['SONO'] ?></td>
                      <td> <?php echo $res['BUYER'] ?></td>
                      <td> <?php echo $res['STYLE'] ?></td>
                      <td> <?php echo $res['COLOR'] ?></td>
                      <td> <?php echo $res['SIZE'] ?></td>
                      <td> <?php echo $res['OK'] ?></td>
                      <td> <?php echo $res['DEFECT'] ?></td>
                      <td> <?php echo $res['REJECT'] ?></td>
                      <td> <?php echo $res['TOTALQTY'] ?></td>
                  </tr>
               <?php
           }
        // packing output

     }
//END:Option-3

//START:Option-11 save trans data
    if ($OPPID == 11) {
            $BUDAT    = $data['BUDAT'];
            $LINENOLV = $lineNo ;
            $SONOLV   = $data['SONO'];
            $BUYER  = $data['BUYER'];
            $STYLE  = $data['STYLE'];
            $COLOR  = $data['COLOR'];
            $SIZE   = $data['SIZE'];
            $QTY    = $data['QTY'];
            $UIDD   = $data['UID'];
            $TYPE   = $data['TYPE'];
            $OPID   = $data['OPID'];
            $REJID  = $data['REJID'];
            $pLINE  = $data['LINE'];
            $pUID   = $data['UID'];
            $CC     = $data['CC'];
            $LINENOLV = $pLINE ;
            $UID      = $pUID ;

            if ( $TYPE == "DEFECT"){
                 $TYPE = 'DEF';
            } else if ($TYPE == "REJECT"){
                $TYPE = 'REJ';
            }

            $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
            $dt = date_format($dt, 'd/m/Y H:i:s');
            $SBUDDAT = $dt ;

            if ( ($pLINE == "-") || ($UIDD == "-") || ($QTY == 0)){
                echo "Connection Problem.Again LogIn";
                return;
             }

            if ( ($pLINE == "undefined") || ($UIDD == "undefined") || ($QTY == 0)){
                echo "Connection Problem.Again LogIn";
                return;
             }
        
            if (empty($pLINE) && empty($UIDD) && $QTY == 0) {
                echo "Connection Problem.Again LogIn";
             } else {
                    if ( trim($PRDTY) == 'FINISHING') {
                        saveTPFN($BUDAT,$LINENOLV,$SONOLV,$BUYER,$STYLE,$COLOR,$SIZE,$QTY,$UIDD,$TYPE,$db,$REJID,$CC);
                    } else if(trim($PRDTY) == 'PACKING'){
                        saveTPPK($BUDAT,$LINENOLV,$SONOLV,$BUYER,$STYLE,$COLOR,$SIZE,$QTY,$UIDD,$TYPE,$db,$REJID,$CC);
                    }else {
                        saveOUTPUTDETAILS($BUDAT,$LINENOLV,$SONOLV,$BUYER,$STYLE,$COLOR,$SIZE,$QTY,$UIDD,$SBUDDAT,$TYPE,$REJID,$db,$CC);
                    }
                } // end - empty else part
     } 
//END:Option-11

//START:Option-31 - fetch data - size
    if ($OPPID == 31){
        $sqll = "";
        $vCOLOR = $_SESSION['COLOR'];
        $vLINE = $_SESSION['LNNEW'];
        if ( $PRDTY == 'FINISHING') {
            $sqll = " SELECT DISTINCT SIZE FROM `outputdetails` WHERE sono = '$SONO' AND color = '$vCOLOR' AND LINENO = '$vLINE'" ;

        } else if ( $PRDTY == 'PACKING' ) {
            $sqll = " SELECT DISTINCT SIZEF AS SIZE FROM `tpfn` WHERE SONUM = '$SONO' AND COLOR = '$vCOLOR'
                             AND LNNUM IN (SELECT MNO FROM `zpp_machine_mast` WHERE FLOOR = '$vLINE') " ;

        }
        else {
            $sqll = " SELECT DISTINCT SIZE FROM `inputdetails` WHERE sono = '$SONO' AND color = '$vCOLOR' AND LINENO = '$vLINE'";
        }
        //$result = $db->query($sqll) ;
        if($resultt = mysqli_query($db , $sqll)){
        // if($resultt = $db->query($sqll) ){
            $str = '';
            while($row = mysqli_fetch_array($resultt)){
                // generate array from comma delimited list
                $rooms = explode(',', $row['SIZE']);
                foreach ( $rooms as $k=>$v ) {
                    $str .= '<input class="w3-button w3-blue w3-border w3-xxlarge" 
                              style="width:23%;height:100px; margin: 6px 5px 7px 0px;overflow: auto"
                              type="submit"   onclick="onSizeClick(this.value)"name="btn_'.$k.'" 
                              value="'.$v.'" id="btn_'.$k.'"/>';
                }
            }
            echo $str;
        }  else {
            echo " ERROR: Could not able to execute $sqll. " . mysqli_error($db);
        }
     }
//END:Option-31

//START:Option-32 - fetch data - reject operation name
        if ($OPPID == 32){
            if ( $PRDTY == 'FINISHING') {
                getMFDT($db);
            } else {
            $sqlll = " SELECT  DISTINCT name FROM rejectoperation ";
            if($resulttt = mysqli_query($db, $sqlll)){
                $strr = '';
                while($roww = mysqli_fetch_array($resulttt)){
                    // generate array from comma delimited list
                    $rooms = explode(',', $roww['name']);
                    foreach ( $rooms as $k=>$v ) {
                        $strr .= '<input class="w3-bar-item w3-button tablink w3-border-bottom " 
                                type="submit"   onclick="onRjectList(this.value)" name="btn_'.$k.'" 
                                value="'.$v.'" id="btn_'.$k.'" />';
                    }
                }
                echo $strr;
            } else {
                echo "ERROR: Could not able to execute $sqlll. " . mysqli_error($db);
            }
         }
        }
//START:Option-32


//START:Option-33 - fetch data - reject operation name by cat id
        if ($OPPID == 33){
            $catid  = $data['CATID'];
            if ( $PRDTY == 'FINISHING') {
                getMFDT_OTH($db,$catid);
            } else {
            
            $sqllll = " SELECT  DISTINCT name FROM rejectoperation WHERE catid = $catid";
            if($resultttt = mysqli_query($db, $sqllll)){
                $strrr = '';
                while($rowww = mysqli_fetch_array($resultttt)){
                    // generate array from comma delimited list
                    $rooms = explode(',', $rowww['name']);
                    foreach ( $rooms as $k=>$v ) {
                        $strrr .= '<input class="w3-bar-item w3-button tablink w3-border-bottom " 
                                type="submit"   onclick="onRjectList(this.value)" name="btn_'.$k.'" 
                                value="'.$v.'" id="btn_'.$k.'" />';
                    }
                }
                echo $strrr;
            } else {
                echo "ERROR: Could not able to execute $sqllll. " . mysqli_error($db);
            }
        }
    }
//START:Option-33

// Start:function part
    //save sewing production.
    function  saveOUTPUTDETAILS($BUDAT,$LINENOLV,$SONOLV,$BUYER,$STYLE,$COLOR,$SIZE,$QTY,$UIDD,$SBUDDAT,$TYPE,$REJID,$db,$CC){

        $sql = "INSERT INTO `outputdetails` (`BUDAT`, `LINENO`, `SONO`, `BUYER`, 
                    `STYLE`, `COLOR`, `SIZE`, `QTY`, `UID`, `SBUDDAT`, `TYPE`, `OPID`, `REJID`,`CNTCC`) 
                VALUES ('{$BUDAT}','{$LINENOLV}','{$SONOLV}','{$BUYER}',
                    '{$STYLE}','{$COLOR}','{$SIZE}','{$QTY}','{$UIDD}','{$SBUDDAT}','{$TYPE}','1','1','{$CC}')";
                    
        if ($db->query($sql) === TRUE) {
            $lvvid='';
            $lvvname = '';
            $sqld1 = '';
            $last_id = $db->insert_id;
            if ($TYPE === 'DEFECT' || $TYPE === 'DEF') {
                foreach ($REJID as $val) {
                    $lvvid   = implode(",", $val["id"]);
                    $lvvname =  $val['name'] ;
                    $lvdqty =  $val['dqty'] ;
                    $sqld1  .= "INSERT INTO `rejectdetails`( `ID`, `NAME`, `DFQTY`,`OUTID`) 
                    VALUES ('{$lvvid}','{$lvvname}','{$lvdqty}','{$last_id}');" ;
                }
                if ($db->multi_query($sqld1) === TRUE) {
                    echo "Sewing:Defect ".$QTY." pc save successfully. ";
                } else { 
                    echo "Sewing:Error: " . $sql . "<br>" . $db->error;
                } 
            } else { 
                echo "Sewing:".$QTY." pc save successfully. ";
            }
        } else { 
            echo "Sewing:Error: " . $sql . "<br>" . $db->error;
        }
     }

    //save finishing production.
    function saveTPFN($BUDAT,$LINENOLV,$SONOLV,$BUYER,$STYLE,$COLOR,$SIZE,$QTY,$UIDD,$TYPE,$db,$REJID,$CC){
    
        IF($TYPE === 'DEFECT') { $TYPE = 'DEF' ;}
        IF($TYPE === 'REJECT') { $TYPE = 'REJ' ;}

        $sql = "INSERT INTO `tpfn` (`BUDAT`, `LNNUM`, `SONUM`, `BUYER`, 
                `STYLE`, `COLOR`, `SIZEF`, `TYPEF`,`PDQTY`, `USRID`, `CNTCC`) 
                VALUES ('{$BUDAT}','{$LINENOLV}','{$SONOLV}','{$BUYER}',
                '{$STYLE}','{$COLOR}','{$SIZE}','{$TYPE}','{$QTY}', '{$UIDD}','{$CC}')" ;

        if ($db->query($sql) === TRUE) {
            $lvvid='';
            $lvvname = '';
            $sqld1 = '';
            $last_id = $db->insert_id;
            if ($TYPE === 'DEF') {
                foreach ($REJID as $val) {
                    $lvvid   = implode(",", $val["id"]);
                    $lvvname =  $val['name'] ;
                    $sqld1  .= "INSERT INTO `tpfd`( `TYPID`, `NAMEF`, `TPFNX`) 
                    VALUES ('{$lvvid}','{$lvvname}','{$last_id}');" ;
                }
                if ($db->multi_query($sqld1) === TRUE) {
                    echo "Finishing:Defect ".$QTY." pc save successfully. ";
                } else { 
                    echo "Finishing:Error: " . $sql . "<br>" . $db->error;
                } 
            } else { 
                echo "Finishing:".$QTY." pc save successfully. ";
            }
        } else { 
            echo "Finishing:Error: " . $sql . "<br>" . $db->error;
        }
    
     }

    //save finishing production.
    function saveTPPK($BUDAT,$LINENOLV,$SONOLV,$BUYER,$STYLE,$COLOR,$SIZE,$QTY,$UIDD,$TYPE,$db,$REJID,$CC){
    
            IF($TYPE === 'DEFECT') { $TYPE = 'DEF' ;}
            IF($TYPE === 'REJECT') { $TYPE = 'REJ' ;}
    
            $sql = "INSERT INTO `tppk` (`BUDAT`, `LNNUM`, `SONUM`, `BUYER`, 
                    `STYLE`, `COLOR`, `SIZEF`, `TYPEF`,`PDQTY`, `USRID`, `CNTCC`) 
                    VALUES ('{$BUDAT}','{$LINENOLV}','{$SONOLV}','{$BUYER}',
                    '{$STYLE}','{$COLOR}','{$SIZE}','{$TYPE}','{$QTY}', '{$UIDD}','{$CC}')" ;
    
            if ($db->query($sql) === TRUE) {
                $lvvid='';
                $lvvname = '';
                $sqld1 = '';
                $last_id = $db->insert_id;
                if ($TYPE === 'DEF') {
                    foreach ($REJID as $val) {
                        $lvvid   = implode(",", $val["id"]);
                        $lvvname =  $val['name'] ;
                        $sqld1  .= "INSERT INTO `tppd`( `TYPID`, `NAMEF`, `TPFNX`) 
                        VALUES ('{$lvvid}','{$lvvname}','{$last_id}');" ;
                    }
                    if ($db->multi_query($sqld1) === TRUE) {
                        echo "Packing:Defect ".$QTY." pc save successfully. ";
                    } else { 
                        echo "Packing:Error: " . $sql . "<br>" . $db->error;
                    } 
                } else { 
                    echo "Packing:".$QTY." pc save successfully. ";
                }
            } else { 
                echo "Packing:Error: " . $sql . "<br>" . $db->error;
            }
        
         }

    //get finishing defect type.
    function getMFDT($db){
        $sql = " SELECT  DISTINCT NAMEF FROM mfdt ";
        if($result = mysqli_query($db, $sql)){
            $str = '';
            while($row = mysqli_fetch_array($result)){
                // generate array from comma delimited list
                $rooms = explode(',', $row['NAMEF']);
                foreach ( $rooms as $k=>$v ) {
                    $str .= '<input class="w3-bar-item w3-button tablink w3-border-bottom " 
                            type="submit"   onclick="onRjectList(this.value)" name="btn_'.$k.'" 
                            value="'.$v.'" id="btn_'.$k.'" />';
                }
            }
            echo $str;
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
        }
     }

    //get finishing defect type.
    function getMFDT_OTH($db,$catid){
        $sql = " SELECT  DISTINCT NAMEF FROM mfdt WHERE CATID='$catid' ";
        if($result = mysqli_query($db, $sql)){
            $str = '';
            while($row = mysqli_fetch_array($result)){
                // generate array from comma delimited list
                $rooms = explode(',', $row['NAMEF']);
                foreach ( $rooms as $k=>$v ) {
                    $str .= '<input class="w3-bar-item w3-button tablink w3-border-bottom " 
                            type="submit"   onclick="onRjectList(this.value)" name="btn_'.$k.'" 
                            value="'.$v.'" id="btn_'.$k.'" />';
                }
            }
            echo $str;
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
        }
     }

// End:function part

?>