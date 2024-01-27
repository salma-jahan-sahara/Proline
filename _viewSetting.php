<?php 
    session_start();  
    include 'config.php'; 
    $lineNo = $_SESSION['lineNo']; 
 ?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="css/w3.css"> </link>
<link rel="stylesheet" type="text/css" href="css/mycss.css"> </link>

<head>
    <title> DetailsReport </title>
    <meta charset="UTF-8">
    <script src="jquery.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        onLoadMessage();
        var opid = 33;
        $.ajax({
            type: 'POST',
            url: 'ajaxViewSetting.php',
            data: {
                opid: opid
            },
            success: function(rdate) {
                var myDate = rdate;
                var chunks = myDate.split('-');
                var formattedDate = chunks[2] + '-' + chunks[1] + '-' + chunks[0];

                document.getElementById("line").value = formattedDate;
            }
         });
     });
    function onZDispaly() {
        var opid = 11;
        var idate = document.getElementById("line").value;
        $.ajax({
            type: 'POST',
            url: 'ajaxViewSetting.php',
            data: {
                opid: opid,
                idate: idate
            },
            success: function(html) {
                $('#tKPIVIEW').html(html);
            }
        });
        var opid = 12;
        var idate = document.getElementById("line").value;
        $.ajax({
            type: 'POST',
            url: 'ajaxViewSetting.php',
            data: {
                opid: opid,
                idate: idate
            },
            success: function(html) {
                $('#tDHU').html(html);
            }
        });
     };

    function onLoadDisplay() {
        var opid = 13;
        $.ajax({
            type: 'POST',
            url: 'ajaxViewSetting.php',
            data: {
                opid: opid
            },
            success: function(html) {
                $('#tblRunBody').html(html);
            }
        });
     };

    function onZTargetAdjust() {
        var pwd = prompt("Please enter your password", "*****");
        if (pwd == "apps") {
            var r = confirm("Ztarget Adjust will start now!");
            if (r == true) {
                var opid = 14;
                $.ajax({
                    type: 'POST',
                    url: 'ajaxViewSetting.php',
                    data: {
                        opid: opid
                    },
                    success: function(msg) {
                        alert(msg);
                    }
                });
            }
        } else {
            alert("Wrong password")
        }
     };

    function onArchiveData() {
        var pwd = prompt("Please enter your password", "*****");
        if (pwd == "apps") {
            var r = confirm("Archive will start now!");
            if (r == true) {
                $.ajax({
                    type: 'POST',
                    url: 'ajaxViewArchive.php',
                    data: {},
                    success: function(done) {
                        alert("Archive Completed");
                    }
                });
            }
        } else {
            alert("Wrong password")
        }
     };

    function openCity(evt, cityName) {
        var i, x, tablinks;
        x = document.getElementsByClassName("city");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < x.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" w3-red", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " w3-red";
     };
    function onLoadMessage(){
            var opid = 15;
            $.ajax({
                type: 'POST',
                url: 'ajaxViewSetting.php',
                data: 'opid=' + opid,
                success: function(msg) {
                    $('#tblmHistoryBody').html(msg);
                }
            });

      }
    </script>
 </head>

 <!-- MainBody -->
<body class="w3-small" onload="onLoadDisplay()">
    <!-- mainBodyHeaderDiv -->
    <div class="w3-row w3-blue-grey">
        <div class="w3-col s2  w3-center w3-border-right">
            <a href="initialPage.php">
                <p id="SONO"> BACK </p>
            </a>
        </div>
        <div class="w3-col s4  w3-center w3-border-right">
            <p id="BUYER"> RUNNING SO & ZTARGET CHECK</p>
        </div>
        <div class="w3-col s3  w3-center w3-border-right">
            <p id="STYLE">REPORT</p>
        </div>
        <div class="w3-col s3  w3-center">
            <p id="COLOR">DISPLAY</p>
        </div>
     </div>
    <!-- END-mainBodyHeaderDiv -->
    <!--START:Tab Div -->
    <div class="w3-container">
        <p>Report will be display on each tab</p>
        <div class="w3-bar w3-black">
            <button class="w3-bar-item w3-button tablink w3-red" onclick="openCity(event,'TABAA')">Running SO</button>
            <button class="w3-bar-item w3-button tablink " onclick="openCity(event,'TABAB')">ZTarget</button>
            <button class="w3-bar-item w3-button tablink " onclick="openCity(event,'TABAC')">All Message History</button>
         </div>

        <div id="TABAA" class="w3-container w3-border city">
            <div class="w3-row">
                <input id="myInputColor" type="text" placeholder="Search..">
            </div>
            <!-- Container div for dataGrid -->
            <div class="w3-row" style="overflow-y:scroll;">
                <!-- dataGrid -->
                <table class="w3-table-all  w3-striped w3-hoverable" id="tblRun">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>NO</th>
                            <th>LineNo</th>
                            <th>Date</th>
                            <th>LineNo(Running)</th>
                            <th>SO(Running)</th>
                            <th>BUYER</th>
                            <th>STYLE</th>
                            <th>MP</th>
                            <th>SPHOUR</th>
                            <th>SMV</th>
                            <th>EFF</th>
                            <th>TARGET</th>
                            <th>OPERATOR</th>
                            <th>HELPER</th>
                            <th>UPTIME</th>
                            <th>DBUPTIME</th>
                        </tr>
                    </thead>
                    <tbody id="tblRunBody">
                    </tbody>
                </table>
                <!--End-dataGrid -->
            </div>
            <!-- Container div for dataGrid -->
            <br>
         </div>
        <!--End:TABAA-->
        <div id="TABAB" class="w3-container w3-border city" style="display:none">
            <div class="w3-row">
                <label for="line"> Line No: <?php echo $lineNo ; ?></label>
                <input type="date" id="line" name="line">
                <button class="w3-button w3-teal" onclick="onZDispaly()">Display</button>
                <button class="w3-button w3-teal" onclick="onZTargetAdjust()">ZTarget Adjust</button>
                <button class="w3-button w3-teal" onclick="onArchiveData()">Archive Data</button>
            </div>
            <p>Ztarget:</p>
            <!-- Container div for dataGrid -->
            <div class="w3-row" style="overflow-y:scroll;">
                <!-- dataGrid -->
                <table class="w3-table-all  w3-striped w3-hoverable" id="tblTARGET">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>NO</th>
                            <th>TID</th>
                            <th>DATE(dmy)</th>
                            <th>LINE NO</th>
                            <th>SO</th>
                            <th>BUYER</th>
                            <th>STYLE</th>
                            <th>MP</th>
                            <th>SPHOUR</th>
                            <th>SMV</th>
                            <th>EFF</th>
                            <th>TARGET</th>
                            <th>OPERATOR</th>
                            <th>HELPER</th>
                            <th>UPTIME</th>
                            <th>DBUPTIME</th>
                        </tr>
                    </thead>
                    <tbody id="tKPIVIEW">
                    </tbody>
                </table>
                <!--End-dataGrid -->
            </div>
            <!-- Container div for dataGrid -->
            <br>
            <!-- Container div for dataGrid -->
            <p>DHU:</p>
            <div class="w3-row" style="overflow-y:scroll;">
                <!-- dataGrid -->
                <table class="w3-table-all  w3-striped w3-hoverable" id="tblDHU">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>NO</th>
                            <th>TID</th>
                            <th>DATE(dmy)</th>
                            <th>LINE NO</th>
                            <th>DHU</th>
                            <th>UPTIME</th>
                            <th>DBUPTIME</th>
                        </tr>
                    </thead>
                    <tbody id="tDHU">
                    </tbody>
                </table>
                <!--End-dataGrid -->
            </div>
            <!-- Container div for dataGrid -->
         </div>
        <!--End:TABAB-->
        <div id="TABAC" class="w3-container w3-border city" style="display:none">
             <p> All Message History Showing. </p>
             <br>
             <!-- dataGrid -->
             <table class="w3-table-all  w3-striped w3-hoverable" id="tblmHistory">
                <thead>
                 <tr class="w3-light-grey">
                     <th>No</th>
                     <th>TID</th>
                     <th>Department</th>
                     <th>LineNo/User</th>
                     <th>Date</th>
                     <th>Message</th>
                     <th>Reply</th>
                     <th>Reply Date</th>
                 </tr>
                </thead>
                 <tbody id="tblmHistoryBody">
                 </tbody>
             </table>
             <!--End-dataGrid -->
             <br>
         </div>
     </div>
    <!--END:Tab Div -->
 </body>
 <!-- End-MainBody -->

</html>