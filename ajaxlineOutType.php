<?php
//session_start() ;
//Include database configuration file
include('server.php');
include('config.php'); 
//session_start(); 
//START:Option-1
    //start -get session variable
    if( ($_POST["opid"]) == 1 )
    {
        $_SESSION['DATE'] = date('d-m-Y');;
        print_r(json_encode($_SESSION)); 
    }
    //end - -get session variable
//END:Option-1

//START:Option-2
    //start -get country
        if( ($_POST["opid"]) == 2 )
        {
           $queryFL  = "SELECT DISTINCT CONCAT(CNTCC,'|',CNTNM) AS CNTNM FROM `mcnt`  " ;
           $resultFL = mysqli_query($db, $queryFL);
           $str = '';
           while($row = mysqli_fetch_array($resultFL)){
                   $str .= '<input class="w3-button w3-blue w3-border " 
                             style="width:18%; margin: 5px 0px 5px 5px; float: left;overflow: auto"
                             type="submit"   onclick="onCountryClick(this.value)"name="btn_'.$row['CNTNM'].'" 
                             value="'.$row['CNTNM'].'" id="btn_'.$row['CNTNM'].'" />';
           }
           echo $str;
        }
    //end - get country
//END:Option-2

//START:Option-3
    //start - conntry variable save to session 
    if( ($_POST["opid"]) == 3 )
    {
        $PTNEW = $_POST["PTNEW"];
        $LNNEW = $_POST["LNNEW"];
        $IDNEW = $_POST["IDNEW"];
        $FITTY = $_POST["FITTY"];
        $CNTRY = $_POST["CNTRY"];
        $_SESSION['PTNEW'] =  $PTNEW;
        $_SESSION['LNNEW'] =  $LNNEW;
        $_SESSION['IDNEW'] =  $IDNEW;
        $_SESSION['FITTY'] =  $FITTY;
        $_SESSION['CNTRY'] =  $CNTRY;
        print_r(json_encode($_SESSION));
    }
     //end - conntry variable save to session 
//END:Option-3

//START:Option-4
    //start -get country by where condition
    if( ($_POST["opid"]) == 4 )
    {
       $lvOPT = $_POST["opt"];
       $whClause ='';
       switch ($lvOPT) {
        case "01": $whClause =  'CNTOA BETWEEN "A" AND "E" '; break;
        case "02": $whClause =  'CNTOA BETWEEN "F" AND "J" '; break;
        case "03": $whClause =  'CNTOA BETWEEN "K" AND "O" '; break;
        case "04": $whClause =  'CNTOA BETWEEN "P" AND "T" '; break;
        case "05": $whClause =  'CNTOA BETWEEN "U" AND "Z" '; break;
        case "06": $whClause =  'CNTNA IN ("AS") '; break;
        case "07": $whClause =  'CNTNA IN ("EU") '; break;
        case "08": $whClause =  'CNTNA IN ("NA") '; break;
        case "09": $whClause =  'CNTNA IN ("AF") '; break;
        case "10": $whClause =  'CNTNA NOT IN ("NA") '; break;
        case "11": $whClause =  'CNTNA IN ("AS","EU","NA","AF","ON") '; break;
        default;
       }
       $queryFL  = "SELECT DISTINCT CONCAT(CNTCC,'|',CNTNM) AS CNTNM FROM `mcnt` WHERE + $whClause ORDER BY CNTOA ASC";
       $resultFL = mysqli_query($db, $queryFL);
       $str = '';
         if (mysqli_num_rows($resultFL)>0) {
            while($row = mysqli_fetch_array($resultFL)){
                    $str .= '<input class="w3-button w3-blue w3-border " 
                                style="width:18%; margin: 5px 0px 5px 5px; float: left;overflow: auto"
                                type="submit"   onclick="onCountryClick(this.value)"name="btn_'.$row['CNTNM'].'" 
                                value="'.$row['CNTNM'].'" id="btn_'.$row['CNTNM'].'" />';
            }
            echo $str;
         }
    }
    //end - get country by where condition
//END:Option-4

//START:Option-5 and 6 myBOX implementation
 if(($_POST["opid"]) == 5 )
 {
        $BUDAT  = $_POST['VBUDAT'];
        $SONOLV = $_POST['VSNO'];
        $LINENOLV  = $_POST['VLINE'];
        // sewing output
        $sqlSB = "SELECT LINENO,SONO,BUYER,STYLE,COLOR,SIZE,CNTCC, 
                IFNULL(SUM(case when col = 'FIT' then VALUE end),0) OK,
                IFNULL(SUM(case when col = 'DEFECT' then VALUE end),0) DEFECT,
                IFNULL(SUM(case when col = 'REJECT' then VALUE end),0) REJECT,
                IFNULL(SUM(VALUE),0) AS TOTALQTY
                FROM
                (
                    SELECT LINENO,SONO,BUYER,STYLE,COLOR,SIZE,CNTCC, TYPE AS col,  QTY VALUE 
                    FROM outputdetails
                    WHERE BUDAT = '$BUDAT' AND LINENO = '$LINENOLV' AND SONO ='$SONOLV' 
                ) d
                GROUP BY LINENO,SONO,BUYER,STYLE,COLOR,SIZE,CNTCC "; 

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
                <td> <?php echo $res['CNTCC'] ?></td>
                <td> <?php echo $res['OK'] ?></td>
                <td> <?php echo $res['DEFECT'] ?></td>
                <td> <?php echo $res['REJECT'] ?></td>
                <td> <?php echo $res['TOTALQTY'] ?></td>
            </tr>
            <?php
        }
    // sewing output
    } elseif (($_POST["opid"]) == 6) {
            
            $BUDAT  = $_POST['VBUDAT'];
            $SONOLV = $_POST['VSNO'];
            $LINENOLV  = $_POST['VLINE'];
            // finishing output
            $sqlSB = "SELECT LNNUM AS LINENO,SONUM AS SONO,BUYER,STYLE,COLOR,SIZEF AS SIZE,CNTCC, 
                    IFNULL(SUM(case when col = 'FIT' then VALUE end),0) OK,
                    IFNULL(SUM(case when col = 'DEF' then VALUE end),0) DEFECT,
                    IFNULL(SUM(case when col = 'REJ' then VALUE end),0) REJECT,
                    IFNULL(SUM(VALUE),0) AS TOTALQTY
                    FROM
                    (
                        SELECT LNNUM,SONUM,BUYER,STYLE,COLOR,SIZEF,CNTCC, TYPEF AS col,  PDQTY VALUE 
                        FROM tpfn
                        WHERE BUDAT = '$BUDAT' AND LNNUM = '$LINENOLV' AND SONUM ='$SONOLV' 
                    ) d
                    GROUP BY LNNUM,SONUM,BUYER,STYLE,COLOR,SIZEF,CNTCC "; 

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
                    <td> <?php echo $res['CNTCC'] ?></td>
                    <td> <?php echo $res['OK'] ?></td>
                    <td> <?php echo $res['DEFECT'] ?></td>
                    <td> <?php echo $res['REJECT'] ?></td>
                    <td> <?php echo $res['TOTALQTY'] ?></td>
                </tr>
                <?php
            }
            // finishing output
    } elseif (($_POST["opid"]) == 61) { 

            $BUDAT  = $_POST['VBUDAT'];
            $SONOLV = $_POST['VSNO'];
            $LINENOLV  = $_POST['VLINE'];
            // finishing output
            $sqlSB = "SELECT LNNUM AS LINENO,SONUM AS SONO,BUYER,STYLE,COLOR,SIZEF AS SIZE,CNTCC, 
                    IFNULL(SUM(case when col = 'FIT' then VALUE end),0) OK,
                    IFNULL(SUM(case when col = 'DEF' then VALUE end),0) DEFECT,
                    IFNULL(SUM(case when col = 'REJ' then VALUE end),0) REJECT,
                    IFNULL(SUM(VALUE),0) AS TOTALQTY
                    FROM
                    (
                        SELECT LNNUM,SONUM,BUYER,STYLE,COLOR,SIZEF,CNTCC, TYPEF AS col,  PDQTY VALUE 
                        FROM tppk
                        WHERE BUDAT = '$BUDAT' AND LNNUM = '$LINENOLV' AND SONUM ='$SONOLV' 
                    ) d
                    GROUP BY LNNUM,SONUM,BUYER,STYLE,COLOR,SIZEF,CNTCC "; 

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
                    <td> <?php echo $res['CNTCC'] ?></td>
                    <td> <?php echo $res['OK'] ?></td>
                    <td> <?php echo $res['DEFECT'] ?></td>
                    <td> <?php echo $res['REJECT'] ?></td>
                    <td> <?php echo $res['TOTALQTY'] ?></td>
                </tr>
                <?php
            }
            // finishing output

 }
//END:Option-5



 

?>