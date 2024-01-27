<?php session_start(); include 'config.php' ; $lineNo = $_SESSION['lineNo']; ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/w3.css"> </link>
<script src="jquery.min.js"></script>
<script src="tableToExcel.js"></script>
<script type="text/javascript">
$(document).ready( function() {
        var opid = 33 ;
                $.ajax({
                        type:'POST',
                        url:'ajaxExportData.php',
                        data: {opid: opid },
                        success:function(rdate){
                                var myDate = rdate;
                                var chunks = myDate.split('-');
                                var formattedDate = chunks[2]+'-'+chunks[1]+'-'+chunks[0];
                                document.getElementById("idate").value = formattedDate ;
                                    } }) ;  
} );
function onDataDisplay(){
    onClear();
    var chk = document.getElementById("chkAll").checked;
    var opid = 2 ;
    var idate = document.getElementById("idate").value;
    $.ajax({
            type:'POST',
            url:'ajaxExportData.php',
            data: {opid: opid, idate: idate , chk: chk},
            success:function(html){
                    $('#cinput').html(html);
                        } }) ;                   
} ;
function onExportData(elem){
        TableToExcel.convert(document.getElementById("tblDisplay"));
} ;
function onClear(){
        $('#msg > p').html("");
}

</script>

</head>

<body class="w3-small" style="background-color: rgb(241, 241, 241);"> <!--main body -->
     <!-- header -->
    <div class="w3-row w3-blue-grey w3-top"> 
            <div class="w3-col s2 w3-blue-grey w3-center w3-border-right">
                <p> Export Data </p>
            </div>
            <div id="lineno"class="w3-col s2 w3-blue-grey w3-center w3-border-right">
                <p>
                  ALL
                  <input id="chkAll"class="w3-check" type="checkbox">
                </p>
            </div>
            <div id="lineno"class="w3-col s8 w3-blue-grey w3-center ">
                <p> <?php echo $lineNo ; ?> </p>
            </div>
    </div> 
    <!-- header -->
    <br><br> <br><br> <!-- double br for stop div overlapping -->

    <!-- Container Input div for dataGrid -->
    <div class="w3-row-padding" id="inpDiv"> 
            <div class="w3-third">
               <input id="idate" class="w3-input w3-border" type="date" placeholder="One" value="">
            </div>
            <div class="w3-third">
                <input id="output" class="w3-input w3-border " type="button" placeholder="Two" 
                name="coutput" value="DataDisplay" onclick="onDataDisplay()" style="background-color: white;">
            </div>
             <div class="w3-third">
                <input id="input1" class="w3-input w3-yellow w3-border"  type="submit" placeholder="Three" 
                name="cinput" value="Export"  onclick="onExportData(this)" style="background-color: white;">
            </div> 
    </div> 
    <!--End-Container input div for dataGrid -->
    <br>
    <!-- Container div dataGrid for Change Input -->
    <div class="w3-row-padding" style="overflow-y:scroll;" > 
            <!-- dataGrid -->
            <table  class="w3-table-all  w3-striped w3-hoverable" id="tblDisplay"> 
            <thead>
                <tr class="w3-light-grey">
                    <th style="display:none;">BUDAT</th>
                    <th>LINENO</th>
                    <th>SO</th>
                    <th>Buyer</th>
                    <th>Style</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Qty(PC)</th>
                    <th>Type</th>
                    <th>SL</th>
                </tr>
            </thead>
            <tbody id="cinput">
            </tbody>
            </table> 
            <!--End-dataGrid -->
    </div> 
    <!--End-Container div dataGrid for Change Input  -->
    <br> <br><br>
    <!--footer -->
        <div class="w3-row w3-container w3-bottom w3-blue-grey"> 
                <a href="initialPage.php">
                    <div class="w3-col s3 w3-blue-grey w3-center w3-border-right" >
                         <p>  < BACK </p>
                    </div>
                </a>
                <div class="w3-col s6 w3-blue-grey w3-center w3-border-right">
                    <p> <?php echo $_SESSION['username'] ; ?> </p>
                </div>
                <div class="w3-col s3 w3-blue-grey w3-center ">
                    <p> TEST </p>
                </div>
        </div> 
    <!--End-footer -->
</body> <!--End-main body -->
</html>