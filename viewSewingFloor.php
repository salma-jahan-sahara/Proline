<?php session_start();  include 'config.php' ; ?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="css/w3.css"> </link>
<link rel="stylesheet" type="text/css" href="css/mycss.css"> </link>
<head>
    <title> SELECT LINE NO </title>
    <meta charset="UTF-8">
    <script src="jquery.min.js"></script>
    <script type="text/javascript">
     setInterval('onViewSewing()', 1000);

     function onViewSewing(){
        var opid = 1 ;
        //var idate = document.getElementById("idate").value;
        $.ajax({
                type:'POST',
                url:'ajaxViewSewingFloor.php',
                data: {opid: opid},
                success:function(html){
                        $('#cinput').html(html);
                            } }) ; 
    } 

    </script>
</head>

<!-- MainBody -->
<body style="background-color: black;"> 
    <!-- header -->
        <div class="w3-row  w3-border-bottom w3-large " style="font-weight: 900;"> 
                <div class="w3-col s2 w3-black w3-center w3-border-right ">
                    <p id="LINENO" style="color: rgb(167,167,167);font-style:italic;">PURBANI</p>  
                </div>
                <div class="w3-col s4 w3-black w3-center w3-border-right ">
                    <p>Floor wise Sewing Dashboard</p>
                </div>
                <div class="w3-col s4 w3-black w3-center w3-border-right ">
                    <p id="LINENO" style="color: rgb(167,167,167);">FLOOR 01</p>  
                </div>
                <div class="w3-col s2 w3-black w3-center ">
                    <p id="DTIME">5.40 PM</p>
                </div>
        </div>
     <!-- End-header -->
     <!-- Div Body part -->
     <div class="w3-row"> 

     <!-- Container div dataGrid for Change Input -->
     <div class="w3-row" style="overflow-y:scroll;"> 
            <!-- dataGrid -->
            <table  class="w3-table-all  w3-striped w3-hoverable" id="cinnputtbl"> 
                <thead>
                        <tr class="w3-light-grey">
                            <th>Line No</th>
                            <th>Buyer</th>
                            <th>Style</th>
                            <th>Order No</th>
                            <th>MP</th>
                            <th>SMV</th>
                            <th>TARGET</th>
                            <th>ACT</th>
                            <th>VAR</th>
                            <th>TREND</th>
                            <th>T.EFF%</th>
                            <th>EFF%</th>
                            <th>DHU%</th>
                        </tr>
                </thead>
                <tbody id="cinput">
                </tbody>
            </table> 
            <!--End-dataGrid -->
    </div> 
    <!--End-Container div dataGrid for Change Input  -->
    
    </div>
     <!-- End- Div Body part -->
</body> 
<!-- End-MainBody -->
</html>
