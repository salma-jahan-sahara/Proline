<?php session_start();  include 'config.php' ;  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>DashBoard</title>
    <link rel="stylesheet" type="text/css" href="css/w3.css"> </link>
    <link rel="stylesheet" type="text/css" href="css/mycss.css"> </link>
    <link rel="stylesheet" href="css/viewKpi.css">
    <script src="jquery.min.js"></script>
    <!-- Javascript -->
    <script type="text/javascript">
        setInterval('onKpiView("L-28")', 7000);
        function onKpiView(param) {
            var opid = param;
            $.ajax({
                type:'POST',
                url:'ajaxKpiView.php',
                data:'opid='+opid ,
                success:function(data){
                    var myObj = JSON.parse(data);
                    document.getElementById("LINENO").innerText = myObj.LINENO ;
                    document.getElementById("BUYER").innerText  = myObj.BUYER ;
                    document.getElementById("STYLE").innerText  = myObj.STYLE ;
                    document.getElementById("TARGET").innerText = myObj.TARGET ;
                    document.getElementById("DAYTARGET").innerText = myObj.DAYTARGET ;
                    document.getElementById("OPERATOR").innerText = myObj.OPERATOR ;
                    document.getElementById("HELPER").innerText  = myObj.HELPER ;
                    document.getElementById("ACTUAL").innerText  = myObj.ACTUAL ;
                    document.getElementById("DTIME").innerText  = myObj.DTIME ;
                    document.getElementById("TEFF").innerText  = myObj.TEFF ;
                    document.getElementById("EFF").innerText  = myObj.EFF ;
                    document.getElementById("TREND").innerText  = myObj.TREND ;
                    document.getElementById("DHU").innerText  = myObj.DHU ;
                    document.getElementById("WIPTOTAL").innerText  = myObj.WIPTOTAL ;
                    var tar = parseInt(document.getElementById("TARGET").innerText);
                    var act = parseInt(document.getElementById("ACTUAL").innerText);
                    var vac = Math.abs(tar - act)
                    document.getElementById("VARIANCE").innerText  = vac ;
                    if (tar > act ) {
                        //document.getElementById("ACTUAL_S").style.backgroundImage = "url('image/down.png')"; 
                        document.getElementById('ACTUAL_S').innerText= '▼';
                        document.getElementById('VARIANCE_S').innerText= '▼';
                        document.getElementById('ACTUAL_S').style.color="red";
                        document.getElementById('VARIANCE_S').style.color="red";
                    } else if ( act > tar ) {
                        document.getElementById('ACTUAL_S').innerText= '▲' ;
                        document.getElementById('VARIANCE_S').innerText= '▲' ;
                        document.getElementById('ACTUAL_S').style.color="green"  ;
                        document.getElementById('VARIANCE_S').style.color="green" ;
                    } 
                    $lvE = parseInt((myObj.EFF).slice(0,-1));
                    $lvT = parseInt((myObj.TEFF).slice(0,-1));  
                    if ( $lvE >= $lvT ){
                        if ($('#EFF').hasClass('w3-text-red')) { 
                            $('#EFF').removeClass('w3-text-red') ;
                            $('#EFF').addClass('w3-text-green') ;
                        } else {
                            $('#EFF').addClass('w3-text-green') ;
                        }
                    } else {
                        if ($('#EFF').hasClass('w3-text-green')) { 
                            $('#EFF').removeClass('w3-text-green') ;
                            $('#EFF').addClass('w3-text-red') ;
                        } else {
                            $('#EFF').addClass('w3-text-red') ;
                        }
                    }
                } }) ; 
            } 
    </script>
    <!-- Bootstrap CDN link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body style="background-color: black;"> 
    <!-- MainBody starts-->
    <div class="container-fluid px-0">
        <!-- header starts -->
        <div class="row mx-0 fw-bold w3-pink border-bottom">
            <div class="col-1 w3-center w3-border-right">
                <a href="initialPage.php" class=" text-white">
                    <p class="my-3 custom-text" >KPI</p>
                </a>
            </div>
            <div class="col-2 w3-center w3-border-right">
                <p id="LINENO" class="my-3 custom-text">TEST</p> 
            </div>
            <div class="col-3 w3-center w3-border-right">
                <p id="BUYER" class="my-3 custom-text">TEST</p>
            </div>
            <div class="col-4 w3-center w3-border-right">
                <p id="STYLE" class="my-3 custom-text">TEST</p>
            </div>
            <div class="col-2 w3-center w3-border-right">
                <p id="DTIME" class="my-3 custom-text ">0.00 AM</p> 
            </div>
        </div>
        <!-- header ends -->

        <!-- Div Body part starts-->
        <div class="row m-0 border-bottom">
            <!--left-->
           <div class="col-4">
            <!--left-PART 1-->
                <div class="row w3-row-padding" style="background-color:rgb(24,24,24);">
                    <!-- Production row -->
                    <div class="row w3-left-align">
                        <span class="custom-text">PRODUCTION</span>
                    </div>
                    <!-- Target row -->
                    <div class="w3-row">
                        <div class="w3-third w3-left-align">
                            <span class="custom-text">Target</span>
                        </div>
                        <div class="w3-twothird w3-right-align" >
                            <span id="TARGET" class="w3-tag w3-jumbo" style="background-color:rgb(24,24,24);">
                                000</span><span class="w3-xxlarge" style="background-color: rgb(24,24,24);">0
                            </span>
                        </div>
                    </div>
                    <!-- Actual row -->
                    <div class="w3-row">
                        <div class="w3-third w3-left-align">
                            <span class="custom-text">Actual</span>
                        </div>
                        <div class="w3-twothird w3-right-align">
                        <span id="ACTUAL" class="w3-tag w3-jumbo" style="background-color:rgb(24,24,24);">
                                610</span><span id="ACTUAL_S"class="w3-xxlarge" style="height: 100%" >▲</span>
                        </div>
                    </div>
                    <!-- Variance row -->
                    <div class="w3-row">
                        <div class="w3-third w3-left-align">
                            <span class="custom-text">Variance</span>
                        </div>
                        <div class="w3-twothird w3-right-align">
                            <span id="VARIANCE"class="w3-tag w3-jumbo" style="background-color:rgb(24,24,24);">
                                1</span><span id="VARIANCE_S"class="w3-xxlarge">▲</span>
                        </div>
                    </div>
                     <!--End-left-PART 1-->
                     <!-- <div  style="height:9px;float:top;"></div>  --> <!-- spacer -->
                     <!--left-PART 2-->
                    <div class="w3-row " style="background-color:rgb(24,24,24);">
                        <div class="w3-row w3-left-align"> 
                            <span class="custom-text">DAY TARGET</span>
                        </div>
                        <div class="w3-row w3-center"> 
                            <span id="DAYTARGET" class="w3-jumbo">1000</span>
                        </div>
                    </div>
                    <!--left-PART 2-->
                    <!-- <div  style="height:9px;float:top;"></div> -->
                    <!-- spacer --> 
                    <!--left-PART 3-->
                    <div class="w3-row" style="background-color:rgb(24,24,24);">
                        <div class="w3-row">
                            <div class="w3-row  w3-left-align"> 
                                <span class="custom-text">MAN POWER</span>
                            </div>
                            <div class="w3-half  w3-center">
                                <span id="OPERATOR" class="w3-tag w3-jumbo"style="background-color:rgb(24,24,24);">
                                    20</span>
                            </div>
                            <div class="w3-half w3-center">
                                <span id="HELPER"class="w3-tag w3-jumbo"style="background-color:rgb(24,24,24);">
                                    8</span>
                            </div>
                        </div>
                        <div class="w3-row " >
                            <div class="w3-half  w3-center">
                                <span class="w3-tag custom-text" style="background-color:rgb(24,24,24);">
                                    Operators</span>
                            </div>
                            <div class="w3-half w3-center">
                                <span class="w3-tag custom-text"  style="background-color:rgb(24,24,24);">
                                    Helpers</span>
                            </div>
                        </div>
                    </div>
                    <!--left-PART 3-->
                </div>
           </div>
           <!--End-left-->

            <!--middle-->
           <div class="col-4 g-1">
            <!-- <div class="w3-col s4 w3-black w3-center w3-border-right " > -->
                <div class="row" style="background-color:rgb(24,24,24);">
                    <div class="w3-row w3-row-padding w3-left-align"> 
                            <span>PRODUCTION TREND</span>
                    </div>
                    <div class="w3-row"> 
                            <span  id="TREND" style="font-size:110px;">980</span>
                    </div>
                </div>
                <div class="row" style="background-color:rgb(24,24,24);">
                    <div class="w3-row w3-row-padding w3-left-align"> 
                            <span>TARGET EFFICIENCY</span>
                    </div>
                    <div class="w3-row"> 
                            <span id="TEFF" style="font-size:110px;">00.0%</span>
                    </div>
                </div>
                <div class="row" style="background-color:rgb(24,24,24);">
                    <div class="w3-row w3-row-padding w3-left-align "> 
                            <span>ACTUAL EFFICIENCY</span>
                    </div>
                    <div class="w3-row"> 
                            <span id="EFF" style="font-size:110px;">00.0%</span>
                    </div>
                </div>
           </div>
            <!--End-middle-->
            <!--right-->
           <div class="col-4"></div>
           <!--End-right--> 
        <!-- Div Body part ends-->
    </div>
    <!-- MainBody ends-->

    <!-- Bootstrap CDN link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>