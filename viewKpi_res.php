<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DashBoard</title>
    <link rel="stylesheet" type="text/css" href="css/w3.css"></link>
    <link rel="stylesheet" type="text/css" href="css/mycss.css"></link>
    <!-- Not to save previous code Browser  -->
    <link rel="stylesheet" href="css/viewKpi.css?v=<?= time(); ?>">
    <!-- Bootstrap CDN link --> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Jquery Link -->
    <script src="jquery.min.js"></script>
    <!-- Javascript -->
    <script type="text/javascript">
        //setInterval('onKpiView("L-28")', 7000);
        $(document).ready(function() {
            // Load initial data when the page is first loaded
            onKpiView("L-28");
            // Update data every 7 seconds
            setInterval(function() {
                onKpiView("L-28");
            }, 7000);
        });

        function onKpiView(param) {
            var opid = param;
            $.ajax({
                type: 'POST',
                url: 'ajaxKpiView.php',
                data: 'opid=' + opid,
                success: function(data) {
                    var myObj = JSON.parse(data);
                    document.getElementById("LINENO").innerText = myObj.LINENO;
                    document.getElementById("BUYER").innerText = myObj.BUYER;
                    document.getElementById("STYLE").innerText = myObj.STYLE;
                    document.getElementById("TARGET").innerText = myObj.TARGET;
                    document.getElementById("DAYTARGET").innerText = myObj.DAYTARGET;
                    document.getElementById("OPERATOR").innerText = myObj.OPERATOR;
                    document.getElementById("HELPER").innerText = myObj.HELPER;
                    document.getElementById("ACTUAL").innerText = myObj.ACTUAL;
                    document.getElementById("DTIME").innerText = myObj.DTIME;
                    document.getElementById("TEFF").innerText = myObj.TEFF;
                    document.getElementById("EFF").innerText = myObj.EFF;
                    document.getElementById("TREND").innerText = myObj.TREND;
                    document.getElementById("DHU").innerText = myObj.DHU;

                    var dhuValue = parseFloat(myObj.DHU);
                    if (dhuValue > 5) {
                        // Set the font color to red
                        document.getElementById("DHU").style.color = "#f44336";
                    } else {
                        // Set the default font color
                        document.getElementById("DHU").style.color = ""; // or use any other default color
                    }

                    document.getElementById("WIPTOTAL").innerText = myObj.WIPTOTAL;
                    var tar = parseInt(document.getElementById("TARGET").innerText);
                    var act = parseInt(document.getElementById("ACTUAL").innerText);
                    var vac = Math.abs(tar - act)
                    document.getElementById("VARIANCE").innerText = vac;
                    if (tar > act) {
                        //document.getElementById("ACTUAL_S").style.backgroundImage = "url('image/down.png')"; 
                        document.getElementById('ACTUAL_S').innerText = '▼';
                        document.getElementById('VARIANCE_S').innerText = '▼';
                        document.getElementById('ACTUAL_S').style.color = "red";
                        document.getElementById('VARIANCE_S').style.color = "red";
                    } else if (act > tar) {
                        document.getElementById('ACTUAL_S').innerText = '▲';
                        document.getElementById('VARIANCE_S').innerText = '▲';
                        document.getElementById('ACTUAL_S').style.color = "green";
                        document.getElementById('VARIANCE_S').style.color = "green";
                    }
                    $lvE = parseInt((myObj.EFF).slice(0, -1));
                    $lvT = parseInt((myObj.TEFF).slice(0, -1));
                    if ($lvE >= $lvT) {
                        if ($('#EFF').hasClass('w3-text-red')) {
                            $('#EFF').removeClass('w3-text-red');
                            $('#EFF').addClass('w3-text-green');
                        } else {
                            $('#EFF').addClass('w3-text-green');
                        }
                    } else {
                        if ($('#EFF').hasClass('w3-text-green')) {
                            $('#EFF').removeClass('w3-text-green');
                            $('#EFF').addClass('w3-text-red');
                        } else {
                            $('#EFF').addClass('w3-text-red');
                        }
                    }
                }
            });
        }
    </script>
</head>
<body style="background-color:rgb(24,24,24);">
    <!-- MainBody starts-->
        <div class="container-fluid px-0">
            <!-- header starts -->
            <!--Responsive Fontsize fs-5 fs-sm-6 fs-md-4 fs-lg-3 fs-xl-2 fs-xxl-1 
            margin-top: 0.4rem;
            margin-bottom: 0.4rem;
        -->
            <div class="row mx-0 fw-bold w3-pink border-bottom"> 
                <div class="col-1 border-end text-center">
                    <a href="initialPage.php" class="text-white">
                        <p class="custom-text my-3" >KPI</p>
                    </a>
                </div>
                <div class="col-2 border-end text-center">
                    <p id="LINENO" class="custom-text my-3">TEST</p> 
                </div>
                <div class="col-3 border-end text-center">
                    <p id="BUYER" class="custom-text my-3">TEST</p>
                </div>
                <div class="col-4 border-end text-center">
                    <p id="STYLE" class="custom-text my-3">TEST</p>
                </div>
                <div class="col-2 border-end text-center">
                    <p id="DTIME" class="custom-text my-3 ">0.00 AM</p> 
                </div>
            </div>
            <!-- header ends -->
        </div>
        <!-- Div Body part starts-->
        <div class="row shadow">
            <!--left d-grid gap-2-->
            <div class="col-4 bg-dark border-end text-light d-grid gap-2">
                <!--left-PART 1-->
                <div class="row ps-3" style="background-color:rgb(24,24,24);">
                    <!-- Production row -->
                    <div class="row text-left">
                        <span class="p-text">PRODUCTION</span>
                    </div>
                    <!-- Target row -->
                    <div class="row">
                        <div class="col-4 text-start">
                            <span class="p-text">Target</span>
                        </div>
                        <div class="col-8 text-end">
                            <span id="TARGET" class="w3-tag production-value" style="background-color:rgb(24,24,24);">
                                000</span><span class="icon-text" style="color: rgb(24,24,24);">0
                            </span>
                        </div>
                    </div>
                    <!-- Actual row -->
                    <div class="row">
                        <div class="col-5 text-start">
                            <span class="p-text">Actual</span>
                        </div>
                        <div class="col-7 text-end">
                            <span id="ACTUAL" class="w3-tag production-value" style="background-color:rgb(24,24,24);">
                                610</span><span id="ACTUAL_S" class="icon-text" style="height: 100%">▲</span>
                        </div>
                    </div>
                    <!-- Variance row -->
                    <div class="row">
                        <div class="col-5 text-start">
                            <span class="p-text">Variance</span>
                        </div>
                        <div class="col-7 text-end">
                            <span id="VARIANCE" class="w3-tag production-value" style="background-color:rgb(24,24,24);">
                                1</span><span id="VARIANCE_S" class="icon-text">▲</span>
                        </div>
                    </div>
                </div>   
                <!--End-left-PART 1-->
                <!--left-PART 2-->
                <div class="row  ps-3" style="background-color:rgb(24,24,24);">
                    <div class="row text-left">
                        <span class="p-text">DAY TARGET</span>
                    </div>
                    <div class="row">
                        <span id="DAYTARGET" class="production-value text-center">1000</span>
                    </div>
                </div>
                <!--left-PART 2-->
                <!--left-PART 3-->
                <div class="row  ps-3" style="background-color:rgb(24,24,24);">
                    <div class="row">
                        <div class="row px-2 text-left">
                            <span class="p-text">MAN POWER</span>
                        </div>
                        <div class="col-6 text-center">
                            <span id="OPERATOR" class="w3-tag production-value" style="background-color:rgb(24,24,24);">
                                20</span>
                        </div>
                        <div class="col-6 text-center">
                            <span id="HELPER" class="w3-tag production-value" style="background-color:rgb(24,24,24);">
                                8</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 text-center ">
                            <span class="manpower" style="background-color:rgb(24,24,24);">
                                Operators</span>
                        </div>
                        <div class="col-6 text-center">
                            <span class="manpower" style="background-color:rgb(24,24,24);">
                                Helpers</span>
                        </div>
                    </div>
                </div>
            <!--left-PART 3-->
            </div>
           <!--End-left-->
           <!--middle-->
            <div class="col-4 bg-dark border-end  text-light d-grid gap-2">
                <div class="row px-2" style="background-color:rgb(24,24,24);">
                    <div class="row text-left">
                        <span class="p-text">PRODUCTION TREND</span>
                    </div>
                    <div class="row">
                        <span id="TREND" class="text-center custom-font ">980</span>
                    </div>
                </div>
                <div class="row px-2 " style="background-color:rgb(24,24,24);">
                    <div class="row text-left">
                        <span class="p-text">TARGET EFFICIENCY</span>
                    </div>
                    <div class="row">
                        <span id="TEFF" class="text-center custom-font" >00.0%</span>
                    </div>
                </div>
                <div class="row px-2" style="background-color:rgb(24,24,24);">
                    <div class="row  text-left">
                        <span class="p-text">ACTUAL EFFICIENCY</span>
                    </div>
                    <div class="row">
                        <span id="EFF" class="text-center custom-font" >00.0%</span>
                    </div>
                </div>
            </div>
            <!--End-middle-->
            <!--right-->
            <div class="col-4 border-end bg-dark text-light d-grid gap-2">
                <div class="row" style="background-color:rgb(24,24,24);">
                    <div class="row">
                        <span class="p-text">DHU%</span>
                    </div>
                    <div class="row">
                        <span id="DHU" class="text-center custom-font ">00.0%</span>
                    </div>
                </div>
                <div class="row" style="background-color:rgb(24,24,24);">
                    <div class="row">
                        <span class="p-text">WIP TOTAL</span>
                    </div>
                    <div class="row">
                        <span id="WIPTOTAL" class="text-center custom-font ">0000</span>
                    </div>
                </div>
                <div class="row" style="background-color:rgb(24,24,24);">
                    <div class=" row px-2 custom-gutter-row bg-secondary fw-bold">
                        <div class="col-6 ">
                            <span class="wip-font ">WIP STYLE</span>
                        </div>
                        <div class="col-6 ">
                            <span class="wip-font ">WIP</span>
                        </div>
                    </div>
                    <div class="row fs-2 ">
                        <div class="col-6 border-bottom ">
                            <span class="wip-font">Style A</span>
                        </div>
                        <div class="col-6 border-bottom ">
                            <span class="wip-font">0000</span>
                        </div>
                    </div>
                    <div class="row fs-2 text-secondary">
                        <div class="col-6 border-bottom ">
                            <span class="wip-font">Style A</span>
                        </div>
                        <div class="col-6 border-bottom ">
                            <span class="wip-font">0000</span>
                        </div>
                    </div>
                    <div class="row fs-2 text-secondary">
                        <div class="col-6 ">
                            <span class="wip-font">Style A</span>
                        </div>
                        <div class="col-6 ">
                            <span class="wip-font">0000</span>
                        </div>
                    </div>
                </div>
            </div>
            <!--End-right--> 
        </div>
    <!-- Div Body part ends-->
    <!-- MainBody ends-->
    <!-- Bootstrap CDN link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>