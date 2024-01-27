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
                $("#tblPRDModal > tbody").empty();
                $("#tblPRDModalExt > tbody").empty();
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
                $("#tblDHUModal > tbody").empty();
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

        function onLoadDate() {
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
                    onTotalFooter();
                }
            });
           
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
                    onTotalFooter();
                }
            });
           
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
                    onProductionTotalFooter();
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
                    onEfficiencyTotalFooter();
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
                    onDHUTotalFooter();
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

        function onProductionTotalFooter() {
            var TTarget = 0;
            var Line8 = 0;
            var Line9 = 0;
            var Line10 = 0;
            var Line11 = 0;
            var Line12 = 0;
            var Line13 = 0;
            var Line14 = 0;
            var Line15 = 0;
            var Line16 = 0;
            var Line17 = 0;
            var Line18 = 0;
            var Line19 = 0;
            var Line20 = 0;
            var Line21 = 0;
            var Line22 = 0;
            var Line23 = 0;
            var LineTotal = 0;

            // Iterate through each row in the table
            $("#tblProduction tbody tr").each(function() {
                // Extract values from specific columns
                var lvTTarget = parseInt($(this).find("td:nth-child(6)").text());
                var lvLine8 = parseInt($(this).find("td:nth-child(7)").text());
                var lvLine9 = parseInt($(this).find("td:nth-child(8)").text());
                var lvLine10 = parseInt($(this).find("td:nth-child(9)").text());
                var lvLine11 = parseInt($(this).find("td:nth-child(10)").text());
                var lvLine12 = parseInt($(this).find("td:nth-child(11)").text());
                var lvLine13 = parseInt($(this).find("td:nth-child(12)").text());
                var lvLine14 = parseInt($(this).find("td:nth-child(13)").text());
                var lvLine15 = parseInt($(this).find("td:nth-child(14)").text());
                var lvLine16 = parseInt($(this).find("td:nth-child(15)").text());
                var lvLine17 = parseInt($(this).find("td:nth-child(16)").text());
                var lvLine18 = parseInt($(this).find("td:nth-child(17)").text());
                var lvLine19 = parseInt($(this).find("td:nth-child(18)").text());
                var lvLine20 = parseInt($(this).find("td:nth-child(19)").text());
                var lvLine21 = parseInt($(this).find("td:nth-child(20)").text());
                var lvLine22 = parseInt($(this).find("td:nth-child(21)").text());
                var lvLine23 = parseInt($(this).find("td:nth-child(22)").text());
                var lvLineTotal = parseInt($(this).find("td:nth-child(23)").text());

                // Add values to totals
                if (!isNaN(lvTTarget)) {
                    TTarget += lvTTarget;
                }
                if (!isNaN(lvLine8)) {
                    Line8 += lvLine8;
                }
                if (!isNaN(lvLine9)) {
                    Line9 += lvLine9;
                }
                if (!isNaN(lvLine10)) {
                    Line10 += lvLine10;
                }
                if (!isNaN(lvLine11)) {
                    Line11 += lvLine11;
                }
                if (!isNaN(lvLine12)) {
                    Line12 += lvLine12;
                }
                if (!isNaN(lvLine13)) {
                    Line13 += lvLine13;
                }
                if (!isNaN(lvLine14)) {
                    Line14 += lvLine14;
                }
                if (!isNaN(lvLine15)) {
                    Line15 += lvLine15;
                }
                if (!isNaN(lvLine16)) {
                    Line16 += lvLine16;
                }
                if (!isNaN(lvLine17)) {
                    Line17 += lvLine17;
                }
                if (!isNaN(lvLine18)) {
                    Line18 += lvLine18;
                }
                if (!isNaN(lvLine19)) {
                    Line19 += lvLine19;
                }
                if (!isNaN(lvLine20)) {
                    Line20 += lvLine20;
                }
                if (!isNaN(lvLine21)) {
                    Line21 += lvLine21;
                }
                if (!isNaN(lvLine22)) {
                    Line22 += lvLine22;
                }
                if (!isNaN(lvLine23)) {
                    Line23 += lvLine23;
                }

                if (!isNaN(lvLineTotal)) {
                    LineTotal += lvLineTotal;
                }
            });

            // Update the corresponding cells in the table footer
            document.getElementById('TTarget').innerText = TTarget;
            document.getElementById('Line8').innerText = Line8;
            document.getElementById('Line9').innerText = Line9;
            document.getElementById('Line10').innerText = Line10;
            document.getElementById('Line11').innerText = Line11;
            document.getElementById('Line12').innerText = Line12;
            document.getElementById('Line13').innerText = Line13;
            document.getElementById('Line14').innerText = Line14;
            document.getElementById('Line15').innerText = Line15;
            document.getElementById('Line16').innerText = Line16;
            document.getElementById('Line17').innerText = Line17;
            document.getElementById('Line18').innerText = Line18;
            document.getElementById('Line19').innerText = Line19;
            document.getElementById('Line20').innerText = Line20;
            document.getElementById('Line21').innerText = Line21;
            document.getElementById('Line22').innerText = Line22;
            document.getElementById('Line23').innerText = Line23;
            document.getElementById('LineTotal').innerText = LineTotal;

        }

        function onEfficiencyTotalFooter() {
            var TargetTotal = 0;
            var Line8Total = 0;
            var Line9Total = 0;
            var Line10Total = 0;
            var Line11Total = 0;
            var Line12Total = 0;
            var Line13Total = 0;
            var Line14Total = 0;
            var Line15Total = 0;
            var Line16Total = 0;
            var Line17Total = 0;
            var Line18Total = 0;
            var Line19Total = 0;
            var Line20Total = 0;
            var Line21Total = 0;
            var Line22Total = 0;
            var Line23Total = 0;
            var LineLastTotal = 0;

            // Iterate through each row in the table
            $("#tblEfficiency tbody tr").each(function() {
                // Extract values from specific columns
                var lvTarget = parseInt($(this).find("td:nth-child(6)").text());
                var lvLine8 = parseInt($(this).find("td:nth-child(7)").text());
                var lvLine9 = parseInt($(this).find("td:nth-child(8)").text());
                var lvLine10 = parseInt($(this).find("td:nth-child(9)").text());
                var lvLine11 = parseInt($(this).find("td:nth-child(10)").text());
                var lvLine12 = parseInt($(this).find("td:nth-child(11)").text());
                var lvLine13 = parseInt($(this).find("td:nth-child(12)").text());
                var lvLine14 = parseInt($(this).find("td:nth-child(13)").text());
                var lvLine15 = parseInt($(this).find("td:nth-child(14)").text());
                var lvLine16 = parseInt($(this).find("td:nth-child(15)").text());
                var lvLine17 = parseInt($(this).find("td:nth-child(16)").text());
                var lvLine18 = parseInt($(this).find("td:nth-child(17)").text());
                var lvLine19 = parseInt($(this).find("td:nth-child(18)").text());
                var lvLine20 = parseInt($(this).find("td:nth-child(19)").text());
                var lvLine21 = parseInt($(this).find("td:nth-child(20)").text());
                var lvLine22 = parseInt($(this).find("td:nth-child(21)").text());
                var lvLine23 = parseInt($(this).find("td:nth-child(22)").text());
                var lvLineLast = parseInt($(this).find("td:nth-child(23)").text());


                // Add values to totals
                if (!isNaN(lvTarget)) {
                    TargetTotal += lvTarget;
                }
                if (!isNaN(lvLine8)) {
                    Line8Total += lvLine8;
                }
                if (!isNaN(lvLine9)) {
                    Line9Total += lvLine9;
                }
                if (!isNaN(lvLine10)) {
                    Line10Total += lvLine10;
                }
                if (!isNaN(lvLine11)) {
                    Line11Total += lvLine11;
                }
                if (!isNaN(lvLine12)) {
                    Line12Total += lvLine12;
                }
                if (!isNaN(lvLine13)) {
                    Line13Total += lvLine13;
                }
                if (!isNaN(lvLine14)) {
                    Line14Total += lvLine14;
                }
                if (!isNaN(lvLine15)) {
                    Line15Total += lvLine15;
                }
                if (!isNaN(lvLine16)) {
                    Line16Total += lvLine16;
                }
                if (!isNaN(lvLine16)) {
                    Line16Total += lvLine16;
                }
                if (!isNaN(lvLine17)) {
                    Line17Total += lvLine17;
                }
                if (!isNaN(lvLine18)) {
                    Line18Total += lvLine18;
                }
                if (!isNaN(lvLine19)) {
                    Line19Total += lvLine19;
                }
                if (!isNaN(lvLine20)) {
                    Line20Total += lvLine20;
                }
                if (!isNaN(lvLine21)) {
                    Line21Total += lvLine21;
                }
                if (!isNaN(lvLine22)) {
                    Line22Total += lvLine22;
                }
                if (!isNaN(lvLine23)) {
                    Line23Total += lvLine23;
                }
                if (!isNaN(lvLineLast)) {
                    LineLastTotal += lvLineLast;
                }


            });

            // Update the corresponding cells in the table footer
            document.getElementById('TTargetEff').innerText = TargetTotal;
            document.getElementById('Line8Eff').innerText = Line8Total;
            document.getElementById('Line9Eff').innerText = Line9Total;
            document.getElementById('Line10Eff').innerText = Line10Total;
            document.getElementById('Line11Eff').innerText = Line11Total;
            document.getElementById('Line12Eff').innerText = Line12Total;
            document.getElementById('Line13Eff').innerText = Line13Total;
            document.getElementById('Line14Eff').innerText = Line14Total;
            document.getElementById('Line15Eff').innerText = Line15Total;
            document.getElementById('Line16Eff').innerText = Line16Total;
            document.getElementById('Line17Eff').innerText = Line17Total;
            document.getElementById('Line18Eff').innerText = Line18Total;
            document.getElementById('Line19Eff').innerText = Line19Total;
            document.getElementById('Line20Eff').innerText = Line20Total;
            document.getElementById('Line21Eff').innerText = Line21Total;
            document.getElementById('Line22Eff').innerText = Line22Total;
            document.getElementById('Line23Eff').innerText = Line23Total;
            document.getElementById('LineTotalEff').innerText = LineLastTotal;

        }

        function onDHUTotalFooter() {
            var TTargetDHU = 0;
            var Line8DHU = 0;
            var Line9DHU = 0;
            var Line10DHU = 0;
            var Line11DHU = 0;
            var Line12DHU = 0;
            var Line13DHU = 0;
            var Line14DHU = 0;
            var Line15DHU = 0;
            var Line16DHU = 0;
            var Line17DHU = 0;
            var Line18DHU = 0;
            var Line19DHU = 0;
            var Line20DHU = 0;
            var Line21DHU = 0;
            var Line22DHU = 0;
            var Line23DHU = 0;
            var LineTotalDHU = 0;

            // Iterate through each row in the table
            $("#tblDHU tbody tr").each(function() {
                // Extract values from specific columns
                var lvTTargetDHU = parseInt($(this).find("td:nth-child(6)").text());
                var lvLine8DHU = parseInt($(this).find("td:nth-child(7)").text());
                var lvLine9DHU = parseInt($(this).find("td:nth-child(8)").text());
                var lvLine10DHU = parseInt($(this).find("td:nth-child(9)").text());
                var lvLine11DHU = parseInt($(this).find("td:nth-child(10)").text());
                var lvLine12DHU = parseInt($(this).find("td:nth-child(11)").text());
                var lvLine13DHU = parseInt($(this).find("td:nth-child(12)").text());
                var lvLine14DHU = parseInt($(this).find("td:nth-child(13)").text());
                var lvLine15DHU = parseInt($(this).find("td:nth-child(14)").text());
                var lvLine16DHU = parseInt($(this).find("td:nth-child(15)").text());
                var lvLine17DHU = parseInt($(this).find("td:nth-child(16)").text());
                var lvLine18DHU = parseInt($(this).find("td:nth-child(17)").text());
                var lvLine19DHU = parseInt($(this).find("td:nth-child(18)").text());
                var lvLine20DHU = parseInt($(this).find("td:nth-child(19)").text());
                var lvLine21DHU = parseInt($(this).find("td:nth-child(20)").text());
                var lvLine22DHU = parseInt($(this).find("td:nth-child(21)").text());
                var lvLine23DHU = parseInt($(this).find("td:nth-child(22)").text());
                var lvLineTotalDHU = parseInt($(this).find("td:nth-child(23)").text());

                // Add values to totals
                if (!isNaN(lvTTargetDHU)) {
                    TTargetDHU += lvTTargetDHU;
                }
                if (!isNaN(lvLine8DHU)) {
                    Line8DHU += lvLine8DHU;
                }
                if (!isNaN(lvLine9DHU)) {
                    Line9DHU += lvLine9DHU;
                }
                if (!isNaN(lvLine10DHU)) {
                    Line10DHU += lvLine10DHU;
                }
                if (!isNaN(lvLine11DHU)) {
                    Line11DHU += lvLine11DHU;
                }
                if (!isNaN(lvLine12DHU)) {
                    Line12DHU += lvLine12DHU;
                }
                if (!isNaN(lvLine13DHU)) {
                    Line13DHU += lvLine13DHU;
                }
                if (!isNaN(lvLine14DHU)) {
                    Line14DHU += lvLine14DHU;
                }
                if (!isNaN(lvLine15DHU)) {
                    Line15DHU += lvLine15DHU;
                }
                if (!isNaN(lvLine16DHU)) {
                    Line16DHU += lvLine16DHU;
                }
                if (!isNaN(lvLine17DHU)) {
                    Line17DHU += lvLine17DHU;
                }
                if (!isNaN(lvLine18DHU)) {
                    Line18DHU += lvLine18DHU;
                }
                if (!isNaN(lvLine19DHU)) {
                    Line19DHU += lvLine19DHU;
                }
                if (!isNaN(lvLine20DHU)) {
                    Line20DHU += lvLine20DHU;
                }
                if (!isNaN(lvLine21DHU)) {
                    Line21DHU += lvLine21DHU;
                }
                if (!isNaN(lvLine22DHU)) {
                    Line22DHU += lvLine22DHU;
                }
                if (!isNaN(lvLine23DHU)) {
                    Line23DHU += lvLine23DHU;
                }

                if (!isNaN(lvLineTotalDHU)) {
                    LineTotalDHU += lvLineTotalDHU;
                }
            });

            // Update the corresponding cells in the table footer
            document.getElementById('TTargetDHU').innerText = TTargetDHU;
            document.getElementById('Line8DHU').innerText = Line8DHU;
            document.getElementById('Line9DHU').innerText = Line9DHU;
            document.getElementById('Line10DHU').innerText = Line10DHU;
            document.getElementById('Line11DHU').innerText = Line11DHU;
            document.getElementById('Line12DHU').innerText = Line12DHU;
            document.getElementById('Line13DHU').innerText = Line13DHU;
            document.getElementById('Line14DHU').innerText = Line14DHU;
            document.getElementById('Line15DHU').innerText = Line15DHU;
            document.getElementById('Line16DHU').innerText = Line16DHU;
            document.getElementById('Line17DHU').innerText = Line17DHU;
            document.getElementById('Line18DHU').innerText = Line18DHU;
            document.getElementById('Line19DHU').innerText = Line19DHU;
            document.getElementById('Line20DHU').innerText = Line20DHU;
            document.getElementById('Line21DHU').innerText = Line21DHU;
            document.getElementById('Line22DHU').innerText = Line22DHU;
            document.getElementById('Line23DHU').innerText = Line23DHU;
            document.getElementById('LineTotalDHU').innerText = LineTotalDHU;
        }

        function onClear() {
            document.getElementById('idModalDhu').style.display = 'none';
            document.getElementById('idModalEff').style.display = 'none';
            document.getElementById('idModalPRD').style.display = 'none';
            document.getElementById('idModalCOM').style.display = 'none';
            document.getElementById('idModalPSEW').style.display = 'none';
        }

        function onCombine() {

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

        function rowColor() {

            var tbl = document.getElementById("trCombine");
            rows = tbl.rows;
            var colours = ['#FFFFFF', '#ffcccc'];
            var group = 3;
            var k = colours.length * group;
            for (var j = 0, jLen = rows.length; j < jLen; j++) {
                rows[j].style.backgroundColor = colours[(j % k) / group | 0];
            }
        }

        function onPadsewing() {
            var opid = 55;
            var idt = document.getElementById('idate').value;
            var pspfl = document.getElementById('mFLOOR').innerHTML;
            pspfl = pspfl.trim();
            if (pspfl.length < 2) {
                alert("Please select floor");
                return;
            }
            $.ajax({
                type: 'POST',
                url: 'ajaxViewMasterPSPRD.php',
                data: {
                    opid: opid,
                    idate: idt,
                    pspfl: pspfl
                },
                success: function(mHTML) {
                    $('#tbdPSEW').html(mHTML);

                }
            });
            document.getElementById('idModalPSEW').style.display = 'block';
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
                <input type="submit" onclick="onPadsewing()" value="PAD SEWING">
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
        <!-- Div for details id : idDetails -->
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
                    <tfoot>
                        <tr style="color:red">
                            <th></th>
                            <th>Total:</th>
                            <th id="TTarget"></th>
                            <th id="Line8"></th>
                            <th id="Line9"></th>
                            <th id="Line10"></th>
                            <th id="Line11"></th>
                            <th id="Line12"></th>
                            <th id="Line13"></th>
                            <th id="Line14"></th>
                            <th id="Line15"></th>
                            <th id="Line16"></th>
                            <th id="Line17"></th>
                            <th id="Line18"></th>
                            <th id="Line19"></th>
                            <th id="Line20"></th>
                            <th id="Line21"></th>
                            <th id="Line22"></th>
                            <th id="Line23"></th>
                            <th id="LineTotal"></th>
                        </tr>
                    </tfoot>
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
                    <tfoot>
                        <tr style="color:red">
                            <th></th>
                            <th>Total:</th>
                            <th id="TTargetEff"></th>
                            <th id="Line8Eff"></th>
                            <th id="Line9Eff"></th>
                            <th id="Line10Eff"></th>
                            <th id="Line11Eff"></th>
                            <th id="Line12Eff"></th>
                            <th id="Line13Eff"></th>
                            <th id="Line14Eff"></th>
                            <th id="Line15Eff"></th>
                            <th id="Line16Eff"></th>
                            <th id="Line17Eff"></th>
                            <th id="Line18Eff"></th>
                            <th id="Line19Eff"></th>
                            <th id="Line20Eff"></th>
                            <th id="Line21Eff"></th>
                            <th id="Line22Eff"></th>
                            <th id="Line23Eff"></th>
                            <th id="LineTotalEff"></th>
                        </tr>
                    </tfoot>
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
                    <tfoot>
                        <tr style="color:red">
                            <th></th>
                            <th>Total:</th>
                            <th id="TTargetDHU"></th>
                            <th id="Line8DHU"></th>
                            <th id="Line9DHU"></th>
                            <th id="Line10DHU"></th>
                            <th id="Line11DHU"></th>
                            <th id="Line12DHU"></th>
                            <th id="Line13DHU"></th>
                            <th id="Line14DHU"></th>
                            <th id="Line15DHU"></th>
                            <th id="Line16DHU"></th>
                            <th id="Line17DHU"></th>
                            <th id="Line18DHU"></th>
                            <th id="Line19DHU"></th>
                            <th id="Line20DHU"></th>
                            <th id="Line21DHU"></th>
                            <th id="Line22DHU"></th>
                            <th id="Line23DHU"></th>
                            <th id="LineTotalDHU"></th>
                        </tr>
                    </tfoot>
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
                    <div class="w3-row">
                        <div class="w3-col s8 "> <span id="mIdModalEffLine" class="w3-display-topleft" style="font-weight:bold;color:Blue;"> </span> </div>
                        <div class="w3-col s4 "> <span onclick="onClear()" class="w3-button w3-display-topright">&times;</span> <br><br>
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
                        <div class="w3-col s8 "> <span id="mIdModalPRDLine" class="w3-display-topleft" style="font-weight:bold;color:Blue;"> </span> </div>
                        <div class="w3-col s4 "> <span onclick="onClear()" class="w3-button w3-display-topright">&times;</span> <br><br>
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
                        <div class="w3-col s8 w3-display-topleft w3-text-blue">
                            <p> Production Report:</p>
                        </div>
                        <div class="w3-col s4 "> <span onclick="onClear()" class="w3-button w3-display-topright" style="font-weight:bold;color:Red;">&times;</span> <br><br>
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

            <!--double PAD Sewing report -->
            <div id="idModalPSEW" class="w3-modal">
                <div class="w3-modal-content">
                    <!-- Container PRD Hyperlink -->
                    <div class="w3-row">
                        <div class="w3-col s8 w3-display-topleft w3-text-blue">
                            <p>PAD Sewing Report:</p>
                        </div>
                        <div class="w3-col s4 "> <span onclick="onClear()" class="w3-button w3-display-topright" style="font-weight:bold;color:Red;">&times;</span> <br><br>
                        </div>
                    </div>
                    <!-- dataGrid -->
                    <div class="w3-row" style="overflow-y:scroll;">
                        <table class="w3-table-all  w3-striped w3-hoverable" id="tblPSEW">
                            <thead>
                                <tr class="w3-light-grey">
                                    <th>LineNo</th>
                                    <th>Process</th>
                                    <th style="display:none;">Sono</th>
                                    <th style="display:none;">Buyer</th>
                                    <th style="display:none;">Style</th>
                                    <th style="display:none;">Target</th>
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
                            <tbody id="tbdPSEW">
                            </tbody>
                        </table>
                    </div>
                    <!--End-dataGrid -->
                </div>
            </div>
            <!--double PAD Sewing report -->

        </div>
        <!-- End- Div for details id : idDetails -->
    </div>
    <!-- Div Body part -->
</body>
<!-- End-MainBody -->

</html>