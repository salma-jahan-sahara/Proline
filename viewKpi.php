<?php
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="css/w3.css">
</link>
<link rel="stylesheet" type="text/css" href="css/mycss.css">
</link>

<head>
    <title> DashBoard </title>
    <meta charset="UTF-8">
    <script src="jquery.min.js"></script>
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

<body style="background-color: black;">
    <!-- MainBody -->
    <!-- header -->
    <div class="w3-row  w3-border-bottom " style="font-weight: 900;">
        <div class="w3-col s1 w3-pink w3-center w3-border-right ">
            <a href="initialPage.php">
                <p>KPI</p>
            </a>
        </div>
        <div class="w3-col s1 w3-pink w3-center w3-border-right ">
            <p id="LINENO">TEST</p>
        </div>
        <div class="w3-col s5 w3-pink w3-center w3-border-right ">
            <p id="BUYER">TEST</p>
        </div>
        <div class="w3-col s4 w3-pink w3-center w3-border-right ">
            <p id="STYLE">TEST</p>
        </div>
        <div class="w3-col s1 w3-pink w3-center  ">
            <p id="DTIME">0.00 AM</p>
        </div>
    </div>
    <!-- End-header -->
    <!-- Div Body part -->
    <div class="w3-row">
        <!--left-->
        <div class="w3-col s4 w3-black w3-center w3-border-right">

            <!--left-PART 1-->
            <div class="w3-row w3-row-padding" style="background-color:rgb(24,24,24);">
                <div class="w3-row w3-left-align">
                    <span>PRODUCTION</span>
                </div>
                <div class="w3-row">
                    <div class="w3-third w3-left-align">
                        <span>Target</span>
                    </div>
                    <div class="w3-twothird w3-right-align">
                        <span id="TARGET" class="w3-tag w3-jumbo" style="background-color:rgb(24,24,24);">
                            000</span><span class="w3-xxlarge" style="color: rgb(24,24,24);">0
                        </span>
                    </div>
                </div>
                <div class="w3-row">
                    <div class="w3-third w3-left-align">
                        <span>Actual</span>
                    </div>
                    <div class="w3-twothird w3-right-align">
                        <span id="ACTUAL" class="w3-tag w3-jumbo" style="background-color:rgb(24,24,24);">
                            610</span><span id="ACTUAL_S" class="w3-xxlarge" style="height: 100%">▲</span>
                    </div>
                </div>
                <div class="w3-row">
                    <div class="w3-third w3-left-align">
                        <span>Variance</span>
                    </div>
                    <div class="w3-twothird w3-right-align">
                        <span id="VARIANCE" class="w3-tag w3-jumbo" style="background-color:rgb(24,24,24);">
                            1</span><span id="VARIANCE_S" class="w3-xxlarge">▲</span>
                    </div>
                </div>
            </div>
            <!--End-left-PART 1-->
            <div style="height:9px;float:top;"></div> <!-- spacer -->
            <!--left-PART 2-->
            <div class="w3-row" style="background-color:rgb(24,24,24);">
                <div class="w3-row w3-row-padding w3-left-align">
                    <span>DAY TARGET</span>
                </div>
                <div class="w3-row">
                    <span id="DAYTARGET" class="w3-jumbo">1000</span>
                </div>
            </div>
            <!--left-PART 2-->
            <div style="height:9px;float:top;"></div> <!-- spacer -->
            <!--left-PART 3-->
            <div class="w3-row" style="background-color:rgb(24,24,24);">
                <div class="w3-row">
                    <div class="w3-row w3-row-padding w3-left-align">
                        <span>MAN POWER</span>
                    </div>
                    <div class="w3-half ">
                        <span id="OPERATOR" class="w3-tag w3-jumbo" style="background-color:rgb(24,24,24);">
                            20</span>
                    </div>
                    <div class="w3-half">
                        <span id="HELPER" class="w3-tag w3-jumbo" style="background-color:rgb(24,24,24);">
                            8</span>
                    </div>
                </div>
                <div class="w3-row">
                    <div class="w3-half ">
                        <span class="w3-tag" style="background-color:rgb(24,24,24);">
                            Operators</span>
                    </div>
                    <div class="w3-half">
                        <span class="w3-tag" style="background-color:rgb(24,24,24);">
                            Helpers</span>
                    </div>
                </div>
            </div>
            <!--left-PART 3-->
        </div>
        <!--End-left-->

        <!--middle-->
        <div class="w3-col s4 w3-black w3-center w3-border-right ">
            <div class="w3-row" style="background-color:rgb(24,24,24);">
                <div class="w3-row w3-row-padding w3-left-align">
                    <span>PRODUCTION TREND</span>
                </div>
                <div class="w3-row">
                    <span id="TREND" style="font-size:110px;">980</span>
                </div>
            </div>
            <div style="height:13px;float:top;"></div> <!-- spacer -->
            <div class="w3-row" style="background-color:rgb(24,24,24);">
                <div class="w3-row w3-row-padding w3-left-align">
                    <span>TARGET EFFICIENCY</span>
                </div>
                <div class="w3-row">
                    <span id="TEFF" style="font-size:110px;">00.0%</span>
                </div>
            </div>
            <div style="height:13px;float:top;"></div> <!-- spacer -->
            <div class="w3-row" style="background-color:rgb(24,24,24);">
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
        <div class="w3-col s4 w3-black w3-center ">
            <div class="w3-row " style="background-color:rgb(24,24,24);">
                <div class="w3-row w3-row-padding w3-left-align ">
                    <span>DHU%</span>
                </div>
                <div class="w3-row">
                    <span id="DHU" style="font-size:110px;">00.0%</span>
                </div>
            </div>
            <div style="height:13px;float:top;"></div> <!-- spacer -->
            <div class="w3-row" style="background-color:rgb(24,24,24);">
                <div class="w3-row w3-row-padding w3-left-align">
                    <span>WIP TOTAL</span>
                </div>
                <div class="w3-row">
                    <span id="WIPTOTAL" style="font-size:110px;">0000</span>
                </div>
            </div>
            <div style="height:13px;float:top;"></div> <!-- spacer -->
            <div class="w3-row" style="background-color:rgb(24,24,24);">
                <div class="w3-gray w3-row-padding w3-left-align">
                    <div class="w3-twothird ">
                        <span>WIP STYLE</span>
                    </div>
                    <div class="w3-third">
                        <span>WIP</span>
                    </div>
                </div>
                <div class="w3-row w3-row-padding w3-left-align w3-border-bottom w3-xxlarge">
                    <div class="w3-twothird ">
                        <span>Style A</span>
                    </div>
                    <div class="w3-third">
                        <span>0000</span>
                    </div>
                </div>
                <div class="w3-row w3-row-padding w3-left-align w3-border-bottom w3-xxlarge w3-text-black">
                    <div class="w3-twothird ">
                        <span>Style A</span>
                    </div>
                    <div class="w3-third">
                        <span>0000</span>
                    </div>
                </div>
                <div class="w3-row w3-row-padding w3-left-align  w3-xxlarge w3-text-black">
                    <div class="w3-twothird ">
                        <span>Style A</span>
                    </div>
                    <div class="w3-third">
                        <span>0000</span>
                    </div>
                </div>
            </div>
        </div>
        <!--End-right-->

    </div>
    <!-- End- Div Body part -->

</body> <!-- End-MainBody -->

</html>