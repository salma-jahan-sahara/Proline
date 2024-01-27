<?php session_start();  include 'config.php' ; ?>
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
    // setInterval('onViewSewing()', 1000);
    $(document).ready(function() {
        onInputFilter();
        onViewSewing();
        onLoadDate();
        $("#tblMaster tbody").click(function(e) {
            onTotalFooter();
            var $tr = $(e.target).closest('tr'),
                rowId = ($tr).attr("id"); // Here you can capture the row id of clicked cell.
            var Row = document.getElementById(rowId);
            var Cells = Row.getElementsByTagName("td");
            var mFLOOR = Cells[0].innerText;
            document.getElementById("mFLOOR").innerText = mFLOOR;
            document.getElementById("meFLOOR").innerText = mFLOOR;
            document.getElementById("mdFLOOR").innerText = mFLOOR;
            onDetails(mFLOOR);
            document.getElementById('idDetails').style.display = 'block';
        });


        $("#tblProduction tbody").click(function(e) {
            $("#tblPRDModal > tbody"). empty();
            $("#tblPRDModalExt > tbody"). empty();
            var $tr = $(e.target).closest('tr'),
                rowId = ($tr).attr("id"); // Here you can capture the row id of clicked cell.
            var Row = document.getElementById(rowId);
            var Cells = Row.getElementsByTagName("td");
            var pLine = Cells[0].innerText;
            var pDate = document.getElementById('idate').value;
            var opid = 12;
            $.ajax({
                type: 'POST',
                url: 'ajaxViewMasterNewPRD.php',
                data: {
                    opid: opid,
                    pLine: pLine,
                    pDate: pDate
                },
                success: function(html) {
                    $('#trPRDModal').html(html);
                }
            });
            var opid = 13;
            $.ajax({
                type: 'POST',
                url: 'ajaxViewMasterNewPRD.php',
                data: {
                    opid: opid,
                    pLine: pLine,
                    pDate: pDate
                },
                success: function(html) {
                    $('#trPRDModalExt').html(html);
                }
            });
            document.getElementById('idModalPRD').style.display = 'block';
        });


        $("#tblDHU tbody").click(function(e) {
            $("#tblDHUModal > tbody"). empty();
            var $tr = $(e.target).closest('tr'),
                rowId = ($tr).attr("id"); // Here you can capture the row id of clicked cell.
            var Row = document.getElementById(rowId);
            var Cells = Row.getElementsByTagName("td");
            var pLine = Cells[0].innerText;
            var pDate = document.getElementById('idate').value;

            var pFLOOR = document.getElementById("mdFLOOR").innerText;
            var opid = 31;
            $.ajax({
                type: 'POST',
                url: 'ajaxViewMasterDHU.php',
                data: {
                    opid: opid,
                    pFLOOR: pFLOOR,
                    pLine: pLine,
                    pDate: pDate
                },
                success: function(html) {
                    $('#trDHUModal').html(html);
                }
            });
            document.getElementById('idModalDhu').style.display = 'block';
        });

        $("#tblEfficiency tbody").click(function(e) {
            alert('underconstruction');
            //block for present time not require.
            // var opid = 31
            // var $tr = $(e.target).closest('tr'),
            //     rowId = ($tr).attr("id"); // Here you can capture the row id of clicked cell.
            // var Row = document.getElementById(rowId);
            // var Cells = Row.getElementsByTagName("td");
            // var pLINENO = Cells[0].innerText;
            // var pBUYER = Cells[2].innerText;
            // var pSTYLE = Cells[3].innerText;
            // var pFLOOR = '0';

            // document.getElementById("mIdModalEffLine").innerText = 'Line:' + pLINENO + ",Buyer:" +
            //     pBUYER + ",Style:" + pSTYLE;

            // $.ajax({
            //     type: 'POST',
            //     url: 'ajaxViewMasterEfficiency.php',
            //     data: {
            //         opid: opid,
            //         pFLOOR: pFLOOR,
            //         pLINENO: pLINENO,
            //         pBUYER: pBUYER,
            //         pSTYLE: pSTYLE
            //     },
            //     success: function(html) {
            //         $('#trEffModal').html(html);
            //     }
            // });
            // document.getElementById('idModalEff').style.display = 'block';
        });

    });

    function onInputFilter() {
        $("#myInputSize").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#trPRDModalExt tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    };

    function onLoadDate(){
        var opid = 33;
        $.ajax({
            type: 'POST',
            url: 'ajaxLineChange.php',
            data: {
                opid: opid
            },
            success: function(rdate) {
                var myDate = rdate;
                var chunks = myDate.split('-');
                var formattedDate = chunks[2] + '-' + chunks[1] + '-' + chunks[0];
                document.getElementById("idate").value = formattedDate;
            }
        });
    }
    
    function onViewSewing() {
        var opid = 1;
        //var idate = document.getElementById("idate").value;
        $.ajax({
            type: 'POST',
            url: 'ajaxViewMaster.php',
            data: {
                opid: opid
            },
            success: function(html) {
                $('#cinput').html(html);
            }
        });
        onTotalFooter();
    }

    function onDateSelectSewing() {
        document.getElementById('idDetails').style.display = 'none';
        $('#tblMaster tbody').empty();
        $('#tblProduction tbody').empty();
        $('#tblEfficiency tbody').empty();
        $('#tblDHU tbody').empty();
        var idate = document.getElementById('idate').value;
        var opid = 1;
        $.ajax({
            type: 'POST',
            url: 'ajaxViewMaster.php',
            data: {
                opid: opid,
                idate: idate
            },
            success: function(html) {
                $('#cinput').html(html);
            }
        });
        onTotalFooter();
    }

    function onDetails(param) {
        var idate = document.getElementById('idate').value;
        var pFLOOR = param;
        var opid = 11;
        $.ajax({
            type: 'POST',
            url: 'ajaxViewMasterNewPRD.php',
            data: {
                opid: opid,
                pFLOOR: pFLOOR,
                idate: idate
            },
            success: function(html) {
                $('#trProduction').html(html);
            }
        });
        $.ajax({
            type: 'POST',
            url: 'ajaxViewMasterNewEFF.php',
            data: {
                opid: opid,
                pFLOOR: pFLOOR,
                idate: idate
            },
            success: function(html) {
                $('#trEfficiency').html(html);
            }
        });
        $.ajax({
            type: 'POST',
            url: 'ajaxViewMasterNewDHU.php',
            data: {
                opid: opid,
                pFLOOR: pFLOOR,
                idate: idate
            },
            success: function(html) {
                $('#trDHU').html(html);
            }
        });
    }

    function onTotalFooter() {
        var OPERATOR = 0;
        var LINENO = 0;
        var HELPER = 0;
        var RUNPO = 0;
        var TARGET = 0;
        var FIT = 0;
        var DIFF = 0;
        var WIP = 0;
        var TEFF = 0;
        var CEFF = 0;
        var DEFECT = 0;
        var DEFECTP = 0.00;
        var REJECT = 0;
        var REJECTP = 0.00;
        var CDHU = 0.00;
        var TDHU = 0.00;
        var TREND = 0;
        var iTEFF = 0;
        var iCEFF = 0;
        var iCDHU = 0;
        var iTDHU = 0;
        var iDEFECTP = 0;
        var iREJECTP = 0;

        $("#tblMaster tr.item").each(function() {
            var lvOPERATOR = parseInt($(this).find("td.OPERATOR").text());
            var lvLINENO = parseInt($(this).find("td.LINENO").text());
            var lvHELPER = parseInt($(this).find("td.HELPER").text());
            var lvRUNPO = parseInt($(this).find("td.RUNPO").text());
            var lvTARGET = parseInt($(this).find("td.TARGET").text());
            var lvFIT = parseInt($(this).find("td.FIT").text());
            var lvDIFF = parseInt($(this).find("td.DIFF").text());
            var lvWIP = parseInt($(this).find("td.WIP").text());
            var lvTEFF = parseInt($(this).find("td.TEFF").text());
            var lvCEFF = parseInt($(this).find("td.CEFF").text());
            var lvDEFECT = parseInt($(this).find("td.DEFECT").text());
            var lvDEFECTP = parseFloat($(this).find("td.DEFECTP").text());
            var lvREJECT = parseInt($(this).find("td.REJECT").text());
            var lvREJECTP = parseFloat($(this).find("td.REJECTP").text());
            var lvCDHU = parseFloat($(this).find("td.CDHU").text());
            var lvTDHU = parseFloat($(this).find("td.TDHU").text());
            var lvTREND = parseFloat($(this).find("td.TREND").text());
            if (lvOPERATOR != 0) {
                OPERATOR = OPERATOR + lvOPERATOR;
            };
            if (lvLINENO != 0) {
                LINENO = LINENO + lvLINENO;
            };
            if (lvHELPER != 0) {
                HELPER = HELPER + lvHELPER;
            };
            if (lvRUNPO != 0) {
                RUNPO = RUNPO + lvRUNPO;
            };
            if (lvTARGET != 0) {
                TARGET = TARGET + lvTARGET;
            };
            if (lvFIT != 0) {
                FIT = FIT + lvFIT;
            };
            if (lvDIFF != 0) {
                DIFF = DIFF + lvDIFF;
            };
            if (lvWIP != 0) {
                WIP = WIP + lvWIP;
            };
            if (lvTEFF != 0) {
                TEFF = TEFF + lvTEFF;
                iTEFF = iTEFF + 1;
            };
            if (lvCEFF != 0) {
                CEFF = CEFF + lvCEFF;
                iCEFF = iCEFF + 1;
            };
            if (lvDEFECT != 0) {
                DEFECT = DEFECT + lvDEFECT;
            };
            if (lvDEFECTP != 0) {
                DEFECTP = DEFECTP + lvDEFECTP;
                iDEFECTP = iDEFECTP + 1;
            };
            if (lvREJECT != 0) {
                REJECT = REJECT + lvREJECT;
            };
            if (lvREJECTP != 0) {
                REJECTP = REJECTP + lvREJECTP;
                iREJECTP = iREJECTP + 1;
            };
            if (lvCDHU != 0) {
                CDHU = CDHU + lvCDHU;
                iCDHU = iCDHU + 1;
            };
            if (lvTDHU != 0) {
                TDHU = TDHU + lvTDHU;
                iTDHU = iTDHU + 1;
            };
            if (lvTREND != 0) {
                TREND = TREND + lvTREND;
            };
        });
        document.getElementById('OPERATOR').innerText = OPERATOR;
        document.getElementById('LINENO').innerText = LINENO;
        document.getElementById('HELPER').innerText = HELPER;
        document.getElementById('RUNPO').innerText = RUNPO;
        document.getElementById('TARGET').innerText = TARGET;
        document.getElementById('FIT').innerText = FIT;
        document.getElementById('DIFF').innerText = DIFF;
        document.getElementById('WIP').innerText = WIP;
        document.getElementById('TEFF').innerText = (TEFF / iTEFF).toFixed(2);
        document.getElementById('CEFF').innerText = (CEFF / iCEFF).toFixed(2);
        document.getElementById('DEFECT').innerText = DEFECT;
        document.getElementById('DEFECTP').innerText = (DEFECTP / iDEFECTP).toFixed(2);
        document.getElementById('REJECT').innerText = REJECT;
        document.getElementById('REJECTP').innerText = (REJECTP / iREJECTP).toFixed(2);
        document.getElementById('CDHU').innerText = (CDHU / iCDHU).toFixed(2);
        document.getElementById('TDHU').innerText = (TDHU / iTDHU).toFixed(2);
        document.getElementById('TREND').innerText = TREND;
    }

    function onClear() {
        document.getElementById('idModalDhu').style.display = 'none';
        document.getElementById('idModalEff').style.display = 'none';
        document.getElementById('idModalPRD').style.display = 'none';
        document.getElementById('idModalCOM').style.display = 'none';
    }

    function onCombine(){

        document.getElementById('idModalCOM').style.display = 'block';

        var rowCount = $('#tblCombine tr').length;
        if (rowCount > 1) {
            $("#trCombine tr").remove(); 
        }

        var new_row = $("#tblProduction tbody").clone();
        $("#tblCombine tbody").append(new_row.html());

        var new_row = $("#tblEfficiency tbody").clone();
        $("#tblCombine tbody").append(new_row.html());

        var new_row = $("#tblDHU tbody").clone();
        $("#tblCombine tbody").append(new_row.html());

       
        sortTable();
        rowColor();

    }

    function sortTable() {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById("tblCombine");
            switching = true;
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[0];
                y = rows[i + 1].getElementsByTagName("TD")[0];
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
                }
                if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                }
            }
    }

    function rowColor(){

        var tbl = document.getElementById("trCombine");
        rows = tbl.rows;
        var colours = ['#FFFFFF','#ffcccc'];
        var group = 3;
        var k = colours.length * group;
        for(var j=0, jLen = rows.length; j<jLen; j++){
            rows[j].style.backgroundColor = colours[(j%k)/group | 0];
        }
    }


    </script>
</head>

<!-- MainBody -->

<body class="w3-small">
    <!-- header -->
    <div class="w3-row  w3-border-bottom w3-pale-red">
        <div class="w3-col s2  w3-center w3-border-right ">
            <a href="initialPage.php">
                <p id="LINENOO">BACK</p>
            </a>
        </div>
        <div class="w3-col s5  w3-center w3-border-right ">
            <p>Daily Sewing Production Status</p>
        </div>
        <div class="w3-col s5  w3-center w3-border-right ">
            <p id="LINENOOO">
                <input type="date" id="idate" name="idate">
                <input type="submit" onclick="onDateSelectSewing()" value="Display">
                <input type="submit" onclick="onCombine()" value="Combine">
            </p>
        </div>
    </div>
    <!-- End-header -->
    <!-- Div Body part -->
    <div class="w3-row">

        <!-- Container div dataGrid :Start:floor summary-->
        <div class="w3-row" style="overflow-y:scroll;">
            <!-- dataGrid -->
            <table class="w3-table-all  w3-striped w3-hoverable" id="tblMaster">
                <thead>
                    <tr class="w3-light-grey">
                        <th>Floor(no)</th>
                        <th>Ruing(L)</th>
                        <th>Operator</th>
                        <th>Helper</th>
                        <th>Ruing(po)</th>
                        <th>TAR(pc)</th>
                        <th>PRD(pc)</th>
                        <th>Trend</th>
                        <th>Diff</th>
                        <th>WIP</th>
                        <th>Effi(t)%</th>
                        <th>Effi(c)%</th>
                        <th>Defect</th>
                        <th>Defect(%)</th>
                        <th>Reject</th>
                        <th>Reject(%)</th>
                        <th>DHU(t)%</th>
                        <th>DHU(c)%</th>
                    </tr>
                </thead>
                <tbody id="cinput">
                </tbody>
                <tfoot>
                    <tr style="color:red;">
                        <th>Total</th>
                        <th id="LINENO"></th>
                        <th id="OPERATOR"></th>
                        <th id="HELPER"></th>
                        <th id="RUNPO"></th>
                        <th id="TARGET"></th>
                        <th id="FIT"></th>
                        <th id="TREND"></th>
                        <th id="DIFF"></th>
                        <th id="WIP"></th>
                        <th id="TEFF"></th>
                        <th id="CEFF"></th>
                        <th id="DEFECT"></th>
                        <th id="DEFECTP"></th>
                        <th id="REJECT"></th>
                        <th id="REJECTP"></th>
                        <th id="TDHU"></th>
                        <th id="CDHU"></th>
                    </tr>
                </tfoot>
            </table>
            <!--End-dataGrid -->
        </div>
        <!--Container div dataGrid :End:floor summary  -->

        <br>

        <div class="w3-row" style="display:none;" id="idDetails">

            <!-- Container div dataGrid :Start:line-hour wise production -->
            <div class="w3-row" style="overflow-y:scroll;">
                <!-- dataGrid -->
                <span class="w3-indigo w3-large"> PRODUCTION : <span id="mFLOOR"></span> 
                </span>
                <table class="w3-table-all  w3-striped w3-hoverable" id="tblProduction">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>LineNo</th>
                            <th>Process</th>
                            <th style="display:none;">Sono</th>
                            <th style="display:none;">Buyer</th>
                            <th style="display:none;">Style</th>
                            <th>Target</th>
                            <th>08</th>
                            <th>09</th>
                            <th>10</th>
                            <th>11</th>
                            <th>12</th>
                            <th>13</th>
                            <th>14</th>
                            <th>15</th>
                            <th>16</th>
                            <th>17</th>
                            <th>18</th>
                            <th>19</th>
                            <th>20</th>
                            <th>21</th>
                            <th>22</th>
                            <th>23</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="trProduction">
                    </tbody>
                </table>
                <!--End-dataGrid -->
            </div>
            <!--Container div dataGrid :Start:line-hour wise production  -->
            <br>

            <!-- Container div dataGrid :Start:line-hour wise efficiency -->
            <div class="w3-row" style="overflow-y:scroll;">
                <!-- dataGrid -->
                <span class="w3-blue w3-large"> EFFICIENCY : <span id="meFLOOR"></span> </span>
                <table class="w3-table-all  w3-striped w3-hoverable" id="tblEfficiency">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>LineNo</th>
                            <th>Process</th>
                            <th style="display:none;">Sono</th>
                            <th style="display:none;">Buyer</th>
                            <th style="display:none;">Style</th>
                            <th>Target</th>
                            <th>08</th>
                            <th>09</th>
                            <th>10</th>
                            <th>11</th>
                            <th>12</th>
                            <th>13</th>
                            <th>14</th>
                            <th>15</th>
                            <th>16</th>
                            <th>17</th>
                            <th>18</th>
                            <th>19</th>
                            <th>20</th>
                            <th>21</th>
                            <th>22</th>
                            <th>23</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="trEfficiency">
                    </tbody>
                </table>
                <!--End-dataGrid -->
            </div>
            <!--Container div dataGrid :Start:line-hour wise efficiency  -->

            <br>
            <!-- Container div dataGrid :Start:line-hour wise DHU -->
            <div class="w3-row" style="overflow-y:scroll;">
                <!-- dataGrid -->
                <span class="w3-blue w3-large"> DHU : <span id="mdFLOOR"></span> </span>
                <table class="w3-table-all  w3-striped w3-hoverable" id="tblDHU">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>LineNo</th>
                            <th>Process</th>
                            <th style="display:none;">Sono</th>
                            <th style="display:none;">Buyer</th>
                            <th style="display:none;">Style</th>
                            <th>Target</th>
                            <th>08</th>
                            <th>09</th>
                            <th>10</th>
                            <th>11</th>
                            <th>12</th>
                            <th>13</th>
                            <th>14</th>
                            <th>15</th>
                            <th>16</th>
                            <th>17</th>
                            <th>18</th>
                            <th>19</th>
                            <th>20</th>
                            <th>21</th>
                            <th>22</th>
                            <th>23</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="trDHU">
                    </tbody>
                </table>
                <!--End-dataGrid -->
            </div>
            <!--Container div dataGrid :Start:line-hour wise DHU -->

            <br>
            <!--double click modal For DHU -->
            <div id="idModalDhu" class="w3-modal">
                <div class="w3-modal-content">
                    <!-- Container DHU Hyperlink -->
                    <span onclick="onClear()" class="w3-button w3-display-topright">&times;</span> <br><br>
                    <div class="w3-row" style="overflow-y:scroll;overflow-x:scroll;">
                        <table class="w3-table-all  w3-striped w3-small w3-hoverable" id="tblDHUModal">
                            <thead>
                                <tr class="w3-light-grey">
                                    <th>Date</th>
                                    <th>LineNo</th>
                                    <th>Buyer</th>
                                    <th>Style</th>
                                    <th style="display:none;">Defect Code</th>
                                    <th>Defect Name</th>
                                    <th>Total</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody id="trDHUModal">
                            </tbody>
                            <tfoot>
                                <tr style="color:red;">
                                    <th id=""></th>
                                    <th id=""></th>
                                    <th id=""></th>
                                    <th id=""></th>
                                    <th id="" style="display:none;"></th>
                                    <th id=""></th>
                                    <th id="DFTOT"></th>
                                    <th id=""></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!--End-dataGrid -->
                </div>
            </div>
            <!--END - double click modal For DHU -->

            <br>
            <!--double click modal For EFF -->
            <div id="idModalEff" class="w3-modal">
                <div class="w3-modal-content">
                    <!-- Container EFF Hyperlink -->
                    <!-- <span class="w3-center">
                <span id="mIdModalEffLine"> </span>
                <span onclick="onClear()" 
                  class="w3-button w3-display-topright">&times;</span> </span><br><br> -->
                    <!-- Container Efficiency Hyperlink -->
                    <div class="w3-row">
                        <div class="w3-col s8 "> <span id="mIdModalEffLine" class="w3-display-topleft"
                                style="font-weight:bold;color:Blue;"> </span> </div>
                        <div class="w3-col s4 "> <span onclick="onClear()"
                                class="w3-button w3-display-topright">&times;</span> <br><br>
                        </div>
                    </div>

                    <div class="w3-row" style="overflow-y:scroll;">
                        <!-- dataGrid -->
                        <table class="w3-table-all w3-striped w3-hoverable w3-small w3-bordered " id="tblEffModal">
                            <thead>
                                <tr class="w3-light-grey">
                                    <th style="display:none;">LineNo</th>
                                    <th style="display:none;">Buyer</th>
                                    <th style="display:none;">Style</th>
                                    <th>Date(dmy)</th>
                                    <th>09</th>
                                    <th>10</th>
                                    <th>11</th>
                                    <th>12</th>
                                    <th>13</th>
                                    <th>15</th>
                                    <th>16</th>
                                    <th>17</th>
                                    <th>18</th>
                                    <th>19</th>
                                    <th>20</th>
                                    <th>21</th>
                                    <th>22</th>
                                    <th>23</th>
                                    <th>Total%</th>
                                    <th>Qty</th>
                                    <th>Min</th>
                                    <th>MP</th>
                                    <th>SMV</th>
                                </tr>
                            </thead>
                            <tbody id="trEffModal">
                            </tbody>
                        </table>
                        <!--End-dataGrid -->
                    </div>
                    <!--End-Container Efficiency Hyperlink  -->
                </div>
            </div>
            <!--double click modal For EFF -->

            <br>
            <!--double click modal For PRD -->
            <div id="idModalPRD" class="w3-modal">
                <div class="w3-modal-content">
                    <!-- Container PRD Hyperlink -->
                    <div class="w3-row">
                        <div class="w3-col s8 "> <span id="mIdModalPRDLine" class="w3-display-topleft"
                                style="font-weight:bold;color:Blue;"> </span> </div>
                        <div class="w3-col s4 "> <span onclick="onClear()"
                                class="w3-button w3-display-topright">&times;</span> <br><br>
                        </div>
                    </div>

                    <div class="w3-row" style="overflow-y:scroll;">
                        <!-- dataGrid -->
                        <table class="w3-table-all w3-striped w3-hoverable w3-small w3-bordered " id="tblPRDModal">
                            <thead>
                            <tr class="w3-light-grey">
                                <th>Date</th>
                                <th>LineNo</th>
                                <th>Sono</th>
                                <th>Buyer</th>
                                <th>Style</th>
                                <th>Color</th>
                                <th>Checked</th>
                                <th>Ok</th>
                                <th>Defect</th>
                                <th>Reject</th>
                             </tr>
                            </thead>
                            <tbody id="trPRDModal">
                            </tbody>
                        </table>
                        <!--End-dataGrid -->
                    </div>
                    <br><br>
                    <div class="w3-row" style="overflow-y:scroll;">
                    <span class="w3-small">Size:Wise Details:</span>
                    <input id="myInputSize" type="text" placeholder="Search..">
                        <!-- dataGrid -->
                        <table class="w3-table-all w3-striped w3-hoverable w3-small w3-bordered " id="tblPRDModalExt">
                            <thead>
                            <tr class="w3-light-blue">
                                <th>Date</th>
                                <th>LineNo</th>
                                <th>Sono</th>
                                <th>Buyer</th>
                                <th>Style</th>
                                <th>Color</th>
                                <th class="w3-deep-orange">Size</th>
                                <th>Checked</th>
                                <th>Ok</th>
                                <th>Defect</th>
                                <th>Reject</th>
                             </tr>
                            </thead>
                            <tbody id="trPRDModalExt">
                            </tbody>
                        </table>
                        <!--End-dataGrid -->
                    </div>
                    <!--End-Container PRD Hyperlink  -->
                </div>
            </div>
            <!--double click modal For PRD -->
              
             <!--double Combine report --> 
            <div id="idModalCOM" class="w3-modal">
             <div class="w3-modal-content">
                    <!-- Container PRD Hyperlink -->
                    <div class="w3-row">
                        <!-- <div class="w3-col s8 w3-display-topleft"> <span 
                                style="font-weight:bold;color:Blue;"> COMBIN</span> </div> -->
                        <div class="w3-col s8 w3-display-topleft w3-text-blue"> <p> Production Report:</p></div>
                        <div class="w3-col s4 "> <span onclick="onClear()"
                                class="w3-button w3-display-topright" 
                                style="font-weight:bold;color:Red;">&times;</span> <br><br>
                        </div>
                    </div> 
                    <!-- dataGrid -->
                    <div class="w3-row" style="overflow-y:scroll;">
                        <table class="w3-table-all  w3-striped w3-hoverable" id="tblCombine">
                            <thead>
                                <tr class="w3-light-grey">
                                    <th>LineNo</th>
                                    <th>Process</th>
                                    <th style="display:none;">Sono</th>
                                    <th style="display:none;">Buyer</th>
                                    <th style="display:none;">Style</th>
                                    <th>Target</th>
                                    <th>08</th> 
                                    <th>09</th>
                                    <th>10</th>
                                    <th>11</th>
                                    <th>12</th>
                                    <th>13</th>
                                    <th>14</th>
                                    <th>15</th>
                                    <th>16</th>
                                    <th>17</th>
                                    <th>18</th>
                                    <th>19</th>
                                    <th>20</th>
                                    <th>21</th>
                                    <th>22</th>
                                    <th>23</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="trCombine">
                            </tbody>
                        </table>
                     </div>
                     <!--End-dataGrid -->
             </div>
            </div>
            <!--double Combine report --> 
            <!-- End- Div Body part -->
</body>
<!-- End-MainBody -->

</html>