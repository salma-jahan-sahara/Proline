<?php
//Include database configuration file
include('server.php');
if (isset($_POST["PRDTY"])){
    $PRDTY  = $_POST["PRDTY"]; 
} else {
    $PRDTY  = "";
}

if(isset($_POST["opid"])){
    //Get all state data
    $opid="";
    $opid= $_POST['opid'];
    if ( $PRDTY == 'FINISHING') {
         getMDFI($db,$opid);
    } else {
            $query = "SELECT * FROM rejectlist WHERE opid IN (select id from rejectoperation where name='$opid') 
            ORDER BY opid ASC";
            $run_query = mysqli_query($db, $query);
            
            //Count total number of rows
            $count = mysqli_num_rows($run_query);
            
            //Display states list
            if($count > 0){
            // echo '<option value="">Select state</option>';
                while($row = mysqli_fetch_array($run_query)){
                $id=$row['id'];
                $name=$row['name'];
                echo " <input class='w3-button w3-yellow w3-border w3-hover-red w3-left'
                        style='width:48%;height:100%; margin: 0px 5px 5px 0px;'
                        type='submit' name='$id' value='$name'  onclick='onRejectClick(this.value)'/>";
                }
            }else{
                echo "<p style='margin-left:45%'> No data </p>";
            }
        }
}

//get finishing defect item 
function getMDFI($db,$opid){
    $query = "SELECT * FROM mfdi WHERE TYPID IN (select TYPID from mfdt where namef='$opid') 
    ORDER BY TYPID ASC";
    $run_query = mysqli_query($db, $query);
    //Count total number of rows
    $count = mysqli_num_rows($run_query);
    //Display states list
    if($count > 0){
    // echo '<option value="">Select state</option>';
        while($row = mysqli_fetch_array($run_query)){
        $id=$row['ITMID'];
        $name=$row['NAMEF'];
        echo " <input class='w3-button w3-yellow w3-border w3-hover-red w3-left'
                style='width:48%;height:100%; margin: 0px 5px 5px 0px;'
                type='submit' name='$id' value='$name'  onclick='onRejectClick(this.value)'/>";
        }
    }else{
        echo "<p style='margin-left:45%'> No data </p>";
    }
}
          
?>