<?php session_start();
include 'config.php'; ?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="css/w3.css">
</link>
<link rel="stylesheet" type="text/css" href="css/mycss.css">
</link>

<head>
    <title> DetailsReport </title>
    <meta charset="UTF-8">
    <script src="jquery.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        onInputFilter();
        $("#optLine").change(function() {
            onClear();
        });
        $("#optSO").change(function() {
            document.getElementById("tblInputBody").innerHTML = "";
            document.getElementById("tblOutputBody").innerHTML = "";
        });
        $("#tblColorWise tbody").click(function(e) {
            $(this).toggleClass("highlighted");
            var opid = 3;
            var $tr = $(e.target).closest('tr'),
                rowId = ($tr).attr("id"); // Here you can capture the row id of clicked cell.
            var Row = document.getElementById(rowId);
            var Cells = Row.getElementsByTagName("td");
            var LINENO = Cells[0].innerText;
            var SONO = Cells[1].innerText;
            var BUYER = Cells[2].innerText;
            var STYLE = Cells[3].innerText;
            var COLOR = Cells[4].innerText;
            $.ajax({
                type: 'POST',
                url: 'ajaxViewQtyCheck.php',
                data: {
                    opid: opid,
                    LINENO: LINENO,
                    SONO: SONO,
                    BUYER: BUYER,
                    STYLE: STYLE,
                    COLOR: COLOR
                },
                success: function(html) {
                    $('#tblSizeModalBody').html(html);
                }
            });
            document.getElementById('id01').style.display = 'block';
        });
     });

    function onInputFilter() {
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#tblLineWiseBody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        $("#myInputColor").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#tblColorWiseBody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        $("#myInputCMonth").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#tblCMonthWiseBody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        $("#myInputCMonthFin").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#tblCMonthWiseBodyFin tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        $("#myInputPacking").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#tblPackingReportBody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
            
        });

     };

    function onZero(val) {
        $filterValue = "0.00";
        if (val == 1) {
            $('#myInpChk').change(function() {
                if ($(this).is(':checked')) {
                    $("#tblLineWise td.colBalance:contains('" + $filterValue + "')").parent().show();
                } else {
                    $("#tblLineWise td.colBalance:contains('" + $filterValue + "')").parent().hide();
                }
            });
        } else if (val == 2) {
            $('#myInpChkColor').change(function() {
                if ($(this).is(':checked')) {
                    $("#tblColorWise td.colBalColor:contains('" + $filterValue + "')").parent().show();
                } else {
                    $("#tblColorWise td.colBalColor:contains('" + $filterValue + "')").parent().hide();
                }
            });

        }
     };

    function onLoadDisplay() {
        var opid = 1;
        $.ajax({
            type: 'POST',
            url: 'ajaxViewQtyCheck.php',
            data: {
                opid: opid
            },
            success: function(html) {
                $('#tblLineWiseBody').html(html);
            }
        });
        opid = 2;
        $.ajax({
            type: 'POST',
            url: 'ajaxViewQtyCheck.php',
            data: {
                opid: opid
            },
            success: function(html) {
                $('#tblColorWiseBody').html(html);
            }
        });
        onGetLine();
        onCMonth();
        onCMonthFin();
        onGetFLOOR();
        onLoadDate();
        onGetLINEE();
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
     }

    function onGetLine() {
        var opid = 4;
        $.ajax({
            type: 'POST',
            url: 'ajaxViewQtyCheck.php',
            data: {
                opid: opid
            },
            success: function(html) {
                document.getElementById("optLine").innerHTML = "";
                document.getElementById("optLine").innerHTML = html;
            }
        });
     }
    function onCMonth() {
        var opid = 8;
        $.ajax({
            type: 'POST',
            url: 'ajaxViewQtyCheck.php',
            data: {
                opid: opid
            },
            success: function(html) {
                $('#tblCMonthWiseBody').html(html);
            }
        });
     }
    function onCMonthFin() {
        var opid = 9;
        $.ajax({
            type: 'POST',
            url: 'ajaxViewQtyCheck.php',
            data: {
                opid: opid
            },
            success: function(html) {
                $('#tblCMonthWiseBodyFin').html(html);
            }
        });
     }

    function onGetSO() {
        var opid = 5;
        var LINENO = document.getElementById("optLine").value;
        var lenLine = LINENO.length;
        if (lenLine < 10) {
            $.ajax({
                type: 'POST',
                url: 'ajaxViewQtyCheck.php',
                data: {
                    opid: opid,
                    LINENO: LINENO
                },
                success: function(html) {
                    document.getElementById("optSO").innerHTML = "";
                    document.getElementById("optSO").innerHTML = html;
                }
            });
        } else {
            alert("Plz select Line number");
        }
     }

    function onDisplaySO() {
        var LINENO = document.getElementById("optLine").value;
        var lenLine = LINENO.length;
        var SONO = document.getElementById("optSO").value;
        var lenSO = SONO.length;
        if (lenLine < 10 && lenSO == 10) {
            var opid = 6;
            $.ajax({
                type: 'POST',
                url: 'ajaxViewQtyCheck.php',
                data: {
                    opid: opid,
                    LINENO: LINENO,
                    SONO: SONO
                },
                success: function(html) {
                    $('#tblInputBody').html(html);
                }
            });
            var opid = 7;
            $.ajax({
                type: 'POST',
                url: 'ajaxViewQtyCheck.php',
                data: {
                    opid: opid,
                    LINENO: LINENO,
                    SONO: SONO
                },
                success: function(html) {
                    $('#tblOutputBody').html(html);
                }
            });
        } else {
            alert("Plz select Line number");
        }
     }

    function onClear() {
        document.getElementById("optSO").innerHTML = "";
        document.getElementById("tblInputBody").innerHTML = "";
        document.getElementById("tblOutputBody").innerHTML = "";
        onClearPacking();
     }

    // for master report
        function onGetFLOOR() {
            var opid = 1;
            $.ajax({
                type: 'POST',
                url: 'ajaxViewMasterReport.php',
                data: {
                    opid: opid
                },
                success: function(html) {
                    document.getElementById("optFLOOR").innerHTML = "";
                    document.getElementById("optFLOOR").innerHTML = html;

                    document.getElementById("optFLOOR1").innerHTML = "";
                    document.getElementById("optFLOOR1").innerHTML = html;
                }
            });
         }

        function onLoadDate(){
            var opid = 2;
                $.ajax({
                    type: 'POST',
                    url: 'ajaxViewMasterReport.php',
                    data: {
                        opid: opid
                    },
                    success: function(rdate) {
                        var myDate = rdate;
                        var chunks = myDate.split('-');
                        var formattedDate = chunks[2] + '-' + chunks[1] + '-' + chunks[0];
                        document.getElementById("fDate").value = formattedDate;
                        document.getElementById("tDate").value = formattedDate;
                        document.getElementById("pfDate").value = formattedDate;
                        document.getElementById("ptDate").value = formattedDate;
                    }
                });
         }

        function onMasterReport(param) {
             var opid = 3;
             var pFLOOR = '';
             if ( param === 1){
                 pFLOOR = document.getElementById("optFLOOR").value;
             } else if(param === 2) {
                 pFLOOR = document.getElementById("optLINEE").value;
             }
            var fDate = document.getElementById('fDate').value;
            var tDate = document.getElementById('tDate').value;
            $.ajax({
                type: 'POST',
                url: 'ajaxViewMasterReport.php',
                data: {
                    opid: opid,
                    pFLOOR: pFLOOR,
                    fDate: fDate,
                    tDate: tDate
                },
                success: function(html) {
                    $('#tblMasterReportBody').html(html);
                }
            });
         }

        function onGetLINEE() {
            var PFLOOR = document.getElementById("optFLOOR").value;
            var lenFLOOR = PFLOOR.length;
            if (lenFLOOR === 18 ) {
                PFLOOR = 'FLOOR-01'
            }
            var opid = 4;
            $.ajax({
                type: 'POST',
                url: 'ajaxViewMasterReport.php',
                data: {
                     opid: opid,
                     PFLOOR: PFLOOR
                 },
                 success: function(html) {
                     document.getElementById("optLINEE").innerHTML = "";
                     document.getElementById("optLINEE").innerHTML = html;
                 }
             });
         }

        
        function onPackingDisplay(){
             //packing report 
             var opid = 5;
             pFLOOR = document.getElementById("optFLOOR1").value;
             var pfDate = document.getElementById('pfDate').value;
             var ptDate = document.getElementById('ptDate').value;
             $.ajax({
                type: 'POST',
                url: 'ajaxViewMasterReport.php',
                data: {
                    opid: opid,
                    pFLOOR: pFLOOR,
                    pfDate: pfDate,
                    ptDate: ptDate
                },
                success: function(html) {
                    $('#tblPackingReportBody').html(html);
                }
            });
         }
        
    // end for master report
    function onTotalWIPModal(){
            var FTQTY = 0; var DFQTY = 0 ; var RJQTY = 0 ;var ADQTY = 0 ;var TOQTY = 0 ;
             $("#tblPackingReportBody tr.pITEM").each(function() {
                     var lvFTQTY = parseInt($(this).find("td.FTQTY").text());
                     var lvDFQTY = parseInt($(this).find("td.DFQTY").text());
                     var lvRJQTY = parseInt($(this).find("td.RJQTY").text());
                     var lvADQTY = parseInt($(this).find("td.ADQTY").text());
                     var lvTOQTY = parseInt($(this).find("td.TOQTY").text());

                     if (lvFTQTY != 0) {
                         FTQTY = FTQTY + lvFTQTY;
                      }
                     if (lvDFQTY != 0) {
                         DFQTY = DFQTY + lvDFQTY;
                      }
                     if (lvRJQTY != 0) {
                         RJQTY = RJQTY + lvRJQTY;
                      }
                     if (lvADQTY != 0) {
                         ADQTY = ADQTY + lvADQTY;
                      }
                     if (lvTOQTY != 0) {
                         TOQTY = TOQTY + lvTOQTY;
                      }
             })
             document.getElementById("MFTQTY").innerText ="";
             document.getElementById("MFTQTY").innerText = FTQTY;

             document.getElementById("MDFQTY").innerText ="";
             document.getElementById("MDFQTY").innerText = DFQTY;

             document.getElementById("MRJQTY").innerText ="";
             document.getElementById("MRJQTY").innerText = RJQTY;

             document.getElementById("MADQTY").innerText ="";
             document.getElementById("MADQTY").innerText = ADQTY;

             document.getElementById("MTOQTY").innerText ="";
             document.getElementById("MTOQTY").innerText = TOQTY;

          }
    function onClearPacking(){
             document.getElementById("MFTQTY").innerText ="";
             document.getElementById("MDFQTY").innerText ="";
             document.getElementById("MRJQTY").innerText ="";
             document.getElementById("MADQTY").innerText ="";
             document.getElementById("MTOQTY").innerText ="";
     }
    </script>
</head>

<!--START:MainBody   -->

<body class="w3-small" onload="onLoadDisplay()">
    <!-- mainBodyHeaderDiv -->
    <div class="w3-row w3-blue-grey">
        <div class="w3-col s2  w3-center w3-border-right">
            <a href="initialPage.php">
                <p id="SONO"> BACK </p>
            </a>
        </div>
        <div class="w3-col s4  w3-center w3-border-right">
            <p id="BUYER"> INPUT & OUTPUT BALANCE CHECK</p>
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
            <button class="w3-bar-item w3-button tablink w3-red" onclick="openCity(event,'TABAA')">Sewing:Color wise </button>
            <button class="w3-bar-item w3-button tablink " onclick="openCity(event,'TABAB')">Sewing:All details </button>
            <button class="w3-bar-item w3-button tablink " onclick="openCity(event,'TABAC')">Sewing:EntryDate </button>
            <button class="w3-bar-item w3-button tablink " onclick="openCity(event,'TABAD')">Sewing:Month(current) </button>
            <button class="w3-bar-item w3-button tablink " onclick="openCity(event,'TABAE')">Finishing:Month(current)</button>
            <button class="w3-bar-item w3-button tablink " onclick="openCity(event,'TABAF')">Master Report</button>
            <button class="w3-bar-item w3-button tablink " onclick="openCity(event,'TABAG')">Packing Report</button>
        </div>

        <div id="TABAA" class="w3-container w3-border city">
            <div class="w3-row">
                <input id="myInputColor" type="text" placeholder="Search..">
                <input id="myInpChkColor" class="w3-check" type="checkbox" checked="checked" onclick="onZero(2)">
                <label>With Balance 0.00 QTY</label>
            </div>
            <!--START:Container div dataGrid for Change Input -->
            <div class="w3-row" style="overflow-y:scroll;">
                <!--START:DataGrid LineWise -->
                <table class="w3-table-all  w3-striped w3-hoverable" id="tblColorWise">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>Line</th>
                            <th>SO</th>
                            <th>Buyer</th>
                            <th>Style</th>
                            <th>Color</th>
                            <th>Input QTY</th>
                            <th>Output QTY</th>
                            <th>Balance QTY</th>
                        </tr>
                    </thead>
                    <tbody id="tblColorWiseBody">
                    </tbody>
                </table>
                <!--END:DataGrid LineWise -->
            </div>
            <!--End:Container div dataGrid for Change Input  -->
            <br>
            <!--START:SizeModal -->
            <div id="id01" class="w3-modal">
                <div class="w3-modal-content">
                    <header class="w3-container w3-white">
                        <p>size display:</p>
                    </header>
                    <div class="w3-container">
                        <span onclick="document.getElementById('id01').style.display='none'"
                            class="w3-button w3-display-topright">&times;</span>
                        <!--START:Container div dataGrid for Change Input -->
                        <div class="w3-row" style="overflow-y:scroll;">
                            <!--START:DataGrid LineWise -->
                            <table class="w3-table-all  w3-striped w3-hoverable" id="tblSizeModal">
                                <thead>
                                    <tr class="w3-light-grey">
                                        <th>Line</th>
                                        <th>SO</th>
                                        <th>Buyer</th>
                                        <th>Style</th>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <th>Input QTY</th>
                                        <th>Output QTY</th>
                                        <th>Balance QTY</th>
                                    </tr>
                                </thead>
                                <tbody id="tblSizeModalBody">
                                </tbody>
                            </table>
                            <!--END:DataGrid LineWise -->
                        </div>
                        <!--End:Container div dataGrid for Change Input  -->
                    </div>
                    <footer class="w3-container w3-white">@purbani IT</footer>
                </div>
            </div>
            <!--END:SizeModal -->
        </div>
        <!--End:TABAA-->

        <div id="TABAB" class="w3-container w3-border city" style="display:none">
            <div class="w3-row">
                <input id="myInput" type="text" placeholder="Search..">
                <input id="myInpChk" class="w3-check" type="checkbox" checked="checked" onclick="onZero(1)">
                <label>With Balance 0.00 QTY</label>
            </div>
            <!--START:Container div dataGrid for Change Input -->
            <div class="w3-row" style="overflow-y:scroll;">
                <!--START:DataGrid LineWise -->
                <table class="w3-table-all  w3-striped w3-hoverable" id="tblLineWise">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>Line</th>
                            <th>SO</th>
                            <th>Buyer</th>
                            <th>Style</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Input QTY</th>
                            <th>Output QTY</th>
                            <th>Balance QTY</th>
                        </tr>
                    </thead>
                    <tbody id="tblLineWiseBody">
                    </tbody>
                </table>
                <!--END:DataGrid LineWise -->
            </div>
            <!--End:Container div dataGrid for Change Input  -->
            <br>
        </div>
        <!--End:TABAB-->

        <div id="TABAC" class="w3-container w3-border city w3-small" style="display:none">
            <p>Entry date check for input & output</p>
            <div class="w3-row">
                <select id="optLine" class="w3-select w3-border" style="width:25%;">
                    <option>-- Select Line --</option>
                </select>
                <br>
                <select id="optSO" class="w3-select w3-border" style="width:25%;">
                    <option>-- Select SO --</option>
                </select>
                <button class="w3-button w3-teal" onclick="onGetSO()">Get SalesOrder</button>

                <button class="w3-button w3-teal" onclick="onDisplaySO()" style="margin-left:5px">Display
                    SalesOrder Details</button>
            </div>
            <br>
            <p>Input Details</p>
            <!--START:Container div dataGrid for Change Input -->
            <div class="w3-row" style="overflow-y:scroll;">
                <!--START:DataGrid LineWise -->
                <table class="w3-table-all  w3-striped w3-hoverable" id="tblInput">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>NO</th>
                            <th>Date</th>
                            <th>Color</th>
                            <th>NOP</th>
                            <th>QTY(part)</th>
                            <th>QTY(pc-INPUUT)</th>
                        </tr>
                    </thead>
                    <tbody id="tblInputBody">
                    </tbody>
                </table>
                <!--END:DataGrid LineWise -->
            </div>
            <!--End:Container div dataGrid for Change Input  -->
            <p>Output Details</p>
            <!--START:Container div dataGrid for Change Input -->
            <div class="w3-row" style="overflow-y:scroll;">
                <!--START:DataGrid LineWise -->
                <table class="w3-table-all  w3-striped w3-hoverable" id="tblOutput">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>NO</th>
                            <th>Date</th>
                            <th>Color</th>
                            <th>NOP</th>
                            <th>QTY(part)</th>
                            <th>QTY(pc-OUTPUT)</th>
                        </tr>
                    </thead>
                    <tbody id="tblOutputBody">
                    </tbody>
                </table>
                <!--END:DataGrid LineWise -->
            </div>
            <!--End:Container div dataGrid for Change Input  -->
            <br>
        </div>
        <!--End:TABAC-->

        <div id="TABAD" class="w3-container w3-border city" style="display:none">
            <div class="w3-row">
                <p>Current Month daily production details:</p>
                <input id="myInputCMonth" type="text" placeholder="Search..">
            </div>
            <!--START:Container div dataGrid for Change Input -->
            <div class="w3-row" style="overflow-y:scroll;">
                <!--START:DataGrid LineWise -->
                <table class="w3-table-all  w3-striped w3-hoverable" id="tblCMonthWise">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>No</th>
                            <th>Date</th>
                            <th>LineNo</th>
                            <th>Checked</th>
                            <th>OK</th>
                            <th>Defect(def.count)</th>
                            <th>Reject</th>
                        </tr>
                    </thead>
                    <tbody id="tblCMonthWiseBody">
                    </tbody>
                </table>
                <!--END:DataGrid LineWise -->
            </div>
            <!--End:Container div dataGrid for Change Input  -->
            <br>
        </div>
        <!--End:TABAD-->

        <div id="TABAE" class="w3-container w3-border city" style="display:none">
            <div class="w3-row">
                <p>Finishing:Current Month daily production details:</p>
                <input id="myInputCMonthFin" type="text" placeholder="Search..">
            </div>
            <!--START:Container div dataGrid for Change Input -->
            <div class="w3-row" style="overflow-y:scroll;">
                <!--START:DataGrid LineWise -->
                <table class="w3-table-all  w3-striped w3-hoverable" id="tblCMonthWiseFin">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>No</th>
                            <th>Date</th>
                            <th>LineNo</th>
                            <th>Checked</th>
                            <th>OK</th>
                            <th>Defect(def.count)</th>
                            <th>Reject</th>
                        </tr>
                    </thead>
                    <tbody id="tblCMonthWiseBodyFin">
                    </tbody>
                </table>
                <!--END:DataGrid LineWise -->
            </div>
            <!--End:Container div dataGrid for Change Input  -->
            <br>
        </div>
        <!--End:TABAE-->

        <div id="TABAF" class="w3-container w3-border city" style="display:none">
            <div class="w3-row">
            <p>Master Report(sewing to finishing)</p>
                <input type="date" id="fDate" name="fDate">
                <label>TO</label><input type="date" id="tDate" name="tDate"><br>
                <select id="optFLOOR" class="w3-select w3-border" style="width:25%;" onchange="onGetLINEE()">
                    <option>-- Select FLOOR --</option>
                </select>
                <button class="w3-button w3-teal" onclick="onMasterReport(1)">FLOOR:Display Report</button><br>
                <select id="optLINEE" class="w3-select w3-border" style="width:25%;">
                    <option>-- Select LINE --</option>
                </select>
                <button class="w3-button w3-teal" onclick="onMasterReport(2)">LINES: Display Report</button>
            </div>
            <br>
            <!--START:Container div dataGrid for Change Input -->
            <div class="w3-row" style="overflow-y:scroll;">
                <!--START:DataGrid LineWise -->
                <table class="w3-table-all  w3-striped w3-hoverable" id="tblMasterReport">
                    <thead>
                        <tr class="w3-light-grey">
                            <th style="display: none;">CKEY</th>
                            <th>LINE</th>
                            <th>SALES ORDER</th>
                            <th>BUYER</th>
                            <th>STYLE</th>
                            <th>COLOR</th>
                            <th>Sewing Input</th>
                            <th>Sewing QC Pass</th>
                            <th>Finishing Prod</th>
                        </tr>
                    </thead>
                    <tbody id="tblMasterReportBody">
                    </tbody>
                </table>
                <!--END:DataGrid LineWise -->
            </div>
            <!--End:Container div dataGrid for Change Input  -->
            <br>
        </div>
        <!--End:TABAF-->

        <div id="TABAG" class="w3-container w3-border city" style="display:none">
            <p>Packing report</p>
            <input type="date" id="pfDate" name="pfDate">
            <label>TO</label><input type="date" id="ptDate" name="ptDate"><br>
            <select id="optFLOOR1" class="w3-select w3-border" style="width:25%;">
                <option>-- Select FLOOR --</option>
            </select>
            <button class="w3-button w3-teal" onclick="onPackingDisplay()">FLOOR:Display Report</button>
            <input id="myInputPacking" type="text" placeholder="Search.."><br><br>
            
            <!--START:Container div dataGrid for packing report -->
            <div class="w3-row" style="overflow-y:scroll;height: 372px;">
                <!--START:DataGrid packing report -->
                <table class="w3-table-all  w3-striped w3-hoverable" id="tblPackingReport">
                    <thead>
                        <tr class="w3-light-grey">
                            <th style="display: none;">CKEY</th>
                            <th>FLOOR</th>
                            <th>SALES ORDER</th>
                            <th>BUYER</th>
                            <th>STYLE</th>
                            <th>COLOR</th>
                            <th>SIZE</th>
                            <th>PRD QTY</th>
                            <th>DEF QTY</th>
                            <th>REJ QTY</th>
                            <th>ADJ QTY</th>
                            <th>TOT QTY</th>
                        </tr> 
                    </thead>
                    <tbody id="tblPackingReportBody">
                    </tbody>
                    <tfoot> 
                         <tr class="w3-light-grey" style="font-weight: bold;">
                             <td style="display: none;">-</td>
                             <td></td>
                             <td></td>
                             <td></td>
                             <td></td>
                             <td></td>
                             <td><button class="w3-button w3-teal" onclick="onTotalWIPModal()">Get Total</button></td>
                             <td id="MFTQTY"></td>
                             <td id="MDFQTY"></td>
                             <td id="MRJQTY"></td>
                             <td id="MADQTY"></td>
                             <td id="MTOQTY"></td>
                         </tr>
                </table>
                <!--END:DataGrid packing report -->
            </div>
            <!--End:Container div dataGrid for packing report  -->
        </div>
        <!--End:TABAG-->


    </div>
    <!--END:Tab Div -->
    <footer class="w3-container w3-white">@purbani IT</footer>
</body>
<!--End:MainBody -->

</html>