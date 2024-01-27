<?php
    session_start(); 
    $lineNo = $_SESSION['lineNo']; 
    $USERIDNEW = $_SESSION['USERIDNEW']; 
    $date = date('d-m-Y');
 ?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="css/w3.css">
     </link>
    <script type="text/javascript" src="jquery.min.js"></script>
    <script language="javascript" type="text/javascript">
         $(document).ready(function() {
             onLoadServerDate();
             onLoadFloor();
             onLoadBuyer();
          });
         function onLoadServerDate(){
            var opid = 31;
            $.ajax({
                type: 'POST',
                url: 'ajaxViewPRDMIS.php',
                data: {
                    opid: opid
                },
                success: function(srvDate) {
                 document.getElementById("fDate").value = srvDate;
                 document.getElementById("tDate").value = srvDate;
                }
             });
           }
         function onLoadFloor(){
            var opid = 32;
                $.ajax({
                    type: 'POST',
                    url: 'ajaxViewPRDMIS.php',
                    data: {
                        opid: opid
                    },
                    success: function(html) {
                      document.getElementById("optFloor").innerHTML = "";
                      document.getElementById("optFloor").innerHTML = html;
                      document.getElementById("optFloor").value = -1;
                      onLoadLine();
                    }
                });
          }
         function onLoadLine() {
            var opid = 33;
            var FLOORNO = document.getElementById("optFloor").value;
            var lenFLOOR = FLOORNO.length;
            if (lenFLOOR > 2) {
                $.ajax({
                    type: 'POST',
                    url: 'ajaxViewPRDMIS.php',
                    data: {
                        opid: opid,
                        FLOORNO: FLOORNO
                    },
                    success: function(html) {
                        document.getElementById("optLine").innerHTML = "";
                        document.getElementById("optLine").innerHTML = html;
                        document.getElementById("optLine").value = -1;
                    }
                });
            }
          }
         function onLoadBuyer() {
            var opid = 34;
                $.ajax({
                    type: 'POST',
                    url: 'ajaxViewPRDMIS.php',
                    data: {
                        opid: opid
                    },
                    success: function(html) {
                        document.getElementById("BUYER").innerHTML = "";
                        document.getElementById("BUYER").innerHTML = html;
                        document.getElementById("BUYER").value = -1; 
                    }
                });
          }


         function onDisplayReport(){
                 var FLOORNO = document.getElementById("optFloor").value;
                 var lenFLOOR = FLOORNO.length;
                 var LINENO = document.getElementById("optLine").value;
                 var lenLINENO = LINENO.length;
                 if(lenFLOOR < 2){
                    //TO DO
                    //alert("Please select Floor No");
                    //return;
                  }
                 if(lenLINENO < 2){
                    LINENO = '';
                  }

                 var FMDATE = document.getElementById("fDate").value;
                 var TODATE = document.getElementById("tDate").value;
                 var dynSR="";
                 var elements = document.getElementsByClassName("input");
                    for (var i = 0; i < elements.length; i++) {
                        if(elements[i].value != "") {
                            var inpID = elements[i].id ;
                            var inpVU = elements[i].value ;
                            dynSR = dynSR.concat(inpID,' LIKE ',"'%",inpVU,"%'",' AND ');
                        } 
                    }
                 dynSR = dynSR.substring(0, dynSR.length-5); 
                 var opid = 01;
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxViewPRDMIS.php',
                        data: {
                             opid: opid,
                             flno: FLOORNO,
                             line: LINENO,
                             fdat: FMDATE,
                             tdat: TODATE,
                             dcon: dynSR
                        },
                        success: function(html) {
                             $('#tblMISBody').html(html);
                             document.getElementById("INFTOTAL").innerText ="";
                             document.getElementById("IDFTOTAL").innerText ="";
                        }
                     });
                 onClearFooter();
          }
         function onRefreash(){
            location.reload(); 
          }
         function onRuntimeSelection(){
                 var dynSR="";
                 var elements = document.getElementsByClassName("input");
                    for (var i = 0; i < elements.length; i++) {
                        if(elements[i].value != "") {
                            var inpID = elements[i].id ;
                            var inpVU = elements[i].value ;
                            dynSR = dynSR.concat(inpID,'=',"'",inpVU,"'",' AND ');
                        } 
                    }
                    dynSR = dynSR.substring(0, dynSR.length-5);       
          }
         function onTotalFooter(param){
            //main summary footer total 
            if (param == 1){
                var INTOTAL = 0;
                var IDTOTAL = 0;
                var IDOA = 0 ;var IDOB = 0 ;var IDOC = 0 ;var IDOD = 0 ;
                var IDOE = 0 ;var IDOF = 0 ;var IDOG = 0 ;var IDOH = 0 ;

                var IDFA = 0 ;var IDFB = 0 ;var IDFC = 0 ;var IDFD = 0 ;
                var IDFE = 0 ;var IDFF = 0 ;var IDFG = 0 ;var IDFH = 0 ;

                var IDPA = 0 ;var IDPB = 0 ;var IDPC = 0 ;var IDPD = 0 ;
                var IDPE = 0 ;var IDPF = 0 ;var IDPG = 0 ;var IDPH = 0 ;

                $("#tblMISBody tr").each(function() {
                        var lvINQTY = parseInt($(this).find("span.INQTY").text());
                        if (lvINQTY != 0) {
                            INTOTAL = INTOTAL + lvINQTY;
                        };
                        var lvIDQTY = parseInt($(this).find("span.IDQTY").text());
                        if (lvIDQTY != 0) {
                            IDTOTAL = IDTOTAL + lvIDQTY;
                        };
                        var lvOA = parseInt($(this).find("span.OA").text());
                        if (lvOA != 0) { IDOA = IDOA + lvOA; };
                        var lvOB = parseInt($(this).find("span.OB").text());
                        if (lvOB != 0) { IDOB = IDOB + lvOB; };
                        var lvOC = parseInt($(this).find("span.OC").text());
                        if (lvOC != 0) { IDOC = IDOC + lvOC; };
                        var lvOD = parseInt($(this).find("span.OD").text());
                        if (lvOD != 0) { IDOD = IDOD + lvOD; };
                        var lvOE = parseInt($(this).find("span.OE").text());
                        if (lvOE != 0) { IDOE = IDOE + lvOE; };
                        var lvOF = parseInt($(this).find("span.OF").text());
                        if (lvOF != 0) { IDOF = IDOF + lvOF; };
                        var lvOG = parseInt($(this).find("span.OG").text());
                        if (lvOG != 0) { IDOG = IDOG + lvOG; };
                        var lvOH = parseInt($(this).find("span.OH").text());
                        if (lvOH != 0) { IDOH = IDOH + lvOH; };

                        var lvFA = parseInt($(this).find("span.FA").text());
                        if (lvFA != 0) { IDFA = IDFA + lvFA; };
                        var lvFB = parseInt($(this).find("span.FB").text());
                        if (lvFB != 0) { IDFB = IDFB + lvFB; };
                        var lvFC = parseInt($(this).find("span.FC").text());
                        if (lvFC != 0) { IDFC = IDFC + lvFC; };
                        var lvFD = parseInt($(this).find("span.FD").text());
                        if (lvFD != 0) { IDFD = IDFD + lvFD; };
                        var lvFE = parseInt($(this).find("span.FE").text());
                        if (lvFE != 0) { IDFE = IDFE + lvFE; };
                        var lvFF = parseInt($(this).find("span.FF").text());
                        if (lvFF != 0) { IDFF = IDFF + lvFF; };
                        var lvFG = parseInt($(this).find("span.FG").text());
                        if (lvFG != 0) { IDFG = IDFG + lvFG; };
                        var lvFH = parseInt($(this).find("span.FH").text());
                        if (lvFH != 0) { IDFH = IDFH + lvFH; };

                        var lvPA = parseInt($(this).find("span.PA").text());
                        if (lvPA != 0) { IDPA = IDPA + lvPA; };
                        var lvPB = parseInt($(this).find("span.PB").text());
                        if (lvPB != 0) { IDPB = IDPB + lvPB; };
                        var lvPC = parseInt($(this).find("span.PC").text());
                        if (lvPC != 0) { IDPC = IDPC + lvPC; };
                        var lvPD = parseInt($(this).find("span.PD").text());
                        if (lvPD != 0) { IDPD = IDPD + lvPD; };
                        var lvPE = parseInt($(this).find("span.PE").text());
                        if (lvPE != 0) { IDPE = IDPE + lvPE; };
                        var lvPF = parseInt($(this).find("span.PF").text());
                        if (lvPF != 0) { IDPF = IDPF + lvPF; };
                        var lvPG = parseInt($(this).find("span.PG").text());
                        if (lvPG != 0) { IDPG = IDPG + lvPG; };
                        var lvPH = parseInt($(this).find("span.PH").text());
                        if (lvPH != 0) { IDPH = IDPH + lvPH; };

                })
                
                document.getElementById("INFTOTAL").innerText ="";
                document.getElementById("INFTOTAL").innerText = INTOTAL;
                document.getElementById("IDFTOTAL").innerText ="";
                document.getElementById("IDFTOTAL").innerText = IDTOTAL;
                document.getElementById("FOA").innerText ="";
                document.getElementById("FOA").innerText = IDOA;
                document.getElementById("FOB").innerText ="";
                document.getElementById("FOB").innerText = IDOB;
                document.getElementById("FOC").innerText ="";
                document.getElementById("FOC").innerText = IDOC;
                document.getElementById("FOD").innerText ="";
                document.getElementById("FOD").innerText = IDOD;
                document.getElementById("FOE").innerText ="";
                document.getElementById("FOE").innerText = IDOE;
                document.getElementById("FOF").innerText ="";
                document.getElementById("FOF").innerText = IDOF;
                document.getElementById("FOG").innerText ="";
                document.getElementById("FOG").innerText = IDOG;
                document.getElementById("FOH").innerText ="";
                document.getElementById("FOH").innerText = IDOH;

                document.getElementById("FFA").innerText ="";
                document.getElementById("FFA").innerText = IDFA;
                document.getElementById("FFB").innerText ="";
                document.getElementById("FFB").innerText = IDFB;
                document.getElementById("FFC").innerText ="";
                document.getElementById("FFC").innerText = IDFC;
                document.getElementById("FFD").innerText ="";
                document.getElementById("FFD").innerText = IDFD;
                document.getElementById("FFE").innerText ="";
                document.getElementById("FFE").innerText = IDFE;
                document.getElementById("FFF").innerText ="";
                document.getElementById("FFF").innerText = IDFF;
                document.getElementById("FFG").innerText ="";
                document.getElementById("FFG").innerText = IDFG;
                document.getElementById("FFH").innerText ="";
                document.getElementById("FFH").innerText = IDFH;

                document.getElementById("FPA").innerText ="";
                document.getElementById("FPA").innerText = IDPA;
                document.getElementById("FPB").innerText ="";
                document.getElementById("FPB").innerText = IDPB;
                document.getElementById("FPC").innerText ="";
                document.getElementById("FPC").innerText = IDPC;
                document.getElementById("FPD").innerText ="";
                document.getElementById("FPD").innerText = IDPD;
                document.getElementById("FPE").innerText ="";
                document.getElementById("FPE").innerText = IDPE;
                document.getElementById("FPF").innerText ="";
                document.getElementById("FPF").innerText = IDPF;
                document.getElementById("FPG").innerText ="";
                document.getElementById("FPG").innerText = IDPG;
                document.getElementById("FPH").innerText ="";
                document.getElementById("FPH").innerText = IDPH;
             };
            if (param == 2){
                var INTOTALD = 0;
                $("#tblMISDetailsBody tr").each(function() {
                    var lvINQTYD = parseInt($(this).find("td.INQTYD").text());
                        if (lvINQTYD != 0) {
                            INTOTALD = INTOTALD + lvINQTYD;
                        };
                })
                document.getElementById("idMDetailFooter").innerText ="";
                document.getElementById("idMDetailFooter").innerText = INTOTALD;
             }
            if (param == 3){
                var INTOTALD = 0;
                $("#tblMISDetailsSizeBody tr").each(function() {
                    var lvINQTYD = parseInt($(this).find("td.INQTYDSIZE").text());
                        if (lvINQTYD != 0) {
                            INTOTALD = INTOTALD + lvINQTYD;
                        };
                })
                document.getElementById("idMDetailSizeFooter").innerText ="";
                document.getElementById("idMDetailSizeFooter").innerText = INTOTALD;
             }
            if (param == 4){
                var INTOTALD = 0;
                $("#tblMISDetailsSizeMoreBody tr").each(function() {
                    var lvINQTYD = parseInt($(this).find("td.INQTYDSIZEMORE").text());
                        if (lvINQTYD != 0) {
                            INTOTALD = INTOTALD + lvINQTYD;
                        };
                })
                document.getElementById("idMDetailSizeMoreFooter").innerText ="";
                document.getElementById("idMDetailSizeMoreFooter").innerText = INTOTALD;
             }
          }
         function onClickTblMIS(element){
            document.getElementById("idMDetailFooter").innerText ="";
            $("#tblMISDetails > tbody"). empty();
             var rIndex = element.closest('tr').rowIndex-1;
             var cIndex = element.closest('td').cellIndex ;
             var Row = document.getElementById(rIndex);
             var Cells = Row.getElementsByTagName("td");
             var vLINE = Cells[2].innerText;
             var vBUYR = Cells[3].innerText;
             var vSONO = Cells[4].innerText;
             var vSTYL = Cells[5].innerText;
             var vCOLR = Cells[6].innerText;
             var vALQT = Cells[7].innerText;
             var bSPCE = " | ";
             var opid = 02;
                $.ajax({
                    type: 'POST',
                    url: 'ajaxViewPRDMIS.php',
                    data: {
                        opid: opid,
                        LINE: vLINE,
                        SONO: vSONO,
                        BUYR: vBUYR,
                        STYL: vSTYL,
                        COLR: vCOLR,
                    },
                    success: function(rHMTL) {
                        $('#tblMISDetailsBody').html(rHMTL);
                    }
                });
             var selectRow= vLINE.concat(bSPCE,vBUYR,bSPCE,vSONO,bSPCE,vSTYL,bSPCE,vCOLR,bSPCE,'TOTAL QTY:',vALQT);
             document.getElementById("idModalHead").innerHTML = selectRow;
             document.getElementById('id01').style.display = 'block';
             document.getElementById('idDetailsSize').style.display = 'none';
             document.getElementById('idDetailsSizeMore').style.display = 'none';
          }
         function onClickTblMISDetails(element){
            document.getElementById('idDetailsSize').style.display = 'block';
            document.getElementById("idMDetailSizeFooter").innerText ="";
            var rIndex = element.closest('tr').id;
            var Row = document.getElementById(rIndex);
            var Cells = Row.getElementsByTagName("td");
            var vDATE = Cells[1].innerText;
            var vMAIN = document.getElementById('idModalHead').innerText;
            var opid = 03;
                $.ajax({
                    type: 'POST',
                    url: 'ajaxViewPRDMIS.php',
                    data: {
                        opid: opid,
                        vDATE: vDATE,
                        vMAIN: vMAIN,
                    },
                    success: function(rHMTL) {
                        $('#tblMISDetailsSizeBody').html(rHMTL);
                    }
                });

          } 
         function onClickTblMISDetailsMore(element){
            document.getElementById('idDetailsSizeMore').style.display = 'block';
            document.getElementById("idMDetailSizeMoreFooter").innerText ="";
            var rIndex = element.closest('tr').id;
            var Row = document.getElementById(rIndex);
            var Cells = Row.getElementsByTagName("td");
            var vDATE = Cells[1].innerText;
            var vSIZE = Cells[2].innerText;
            var vMAIN = document.getElementById('idModalHead').innerText;
            var opid = 04;
                $.ajax({
                    type: 'POST',
                    url: 'ajaxViewPRDMIS.php',
                    data: {
                        opid: opid,
                        vDATE: vDATE,
                        vSIZE: vSIZE,
                        vMAIN: vMAIN,
                    },
                    success: function(rHMTL) {
                        $('#tblMISDetailsSizeMoreBody').html(rHMTL);
                    }
                });

          } 
         function onClear() {
              document.getElementById('id01').style.display = 'none';
              document.getElementById('idDetailsSize').style.display = 'none';
              document.getElementById('idDetailsSizeMore').style.display = 'none';
           }
         function onClearFooter() {
                document.getElementById("INFTOTAL").innerText ="";
                document.getElementById("IDFTOTAL").innerText ="";
                document.getElementById("FOA").innerText ="";
                document.getElementById("FOB").innerText ="";
                document.getElementById("FOC").innerText ="";
                document.getElementById("FOD").innerText ="";
                document.getElementById("FOE").innerText ="";
                document.getElementById("FOF").innerText ="";
                document.getElementById("FOG").innerText ="";
                document.getElementById("FOH").innerText ="";
                document.getElementById("FFA").innerText ="";
                document.getElementById("FFB").innerText ="";
                document.getElementById("FFC").innerText ="";
                document.getElementById("FFD").innerText ="";
                document.getElementById("FFE").innerText ="";
                document.getElementById("FFF").innerText ="";
                document.getElementById("FFG").innerText ="";
                document.getElementById("FFH").innerText ="";
                document.getElementById("FPA").innerText ="";
                document.getElementById("FPB").innerText ="";
                document.getElementById("FPC").innerText ="";
                document.getElementById("FPD").innerText ="";
                document.getElementById("FPE").innerText ="";
                document.getElementById("FPF").innerText ="";
                document.getElementById("FPG").innerText ="";
                document.getElementById("FPH").innerText ="";
          }
     </script>
 </head>


<body class="w3-small" style="background-color: rgb(241, 241, 241);">
    
    <!-- header -->
     <div class="w3-row w3-blue-grey w3-top">
        <div class="w3-col s3 w3-blue-grey w3-center w3-border-right">
            <p>PRODUCTION MIS</p>
         </div>
        <div class="w3-col s9 w3-blue-grey w3-center">
            <p>PRODUCTION MIS REPORT</p>
         </div>
      </div>
      <br><br>
    <!-- header -->

    <!--main body -->
        <div class="w3-row" style="overflow-y:scroll;"> 
            <div class="w3-container">
                <select id="optFloor" class="w3-select w3-border" style="width:25%;"   onchange="onLoadLine()">
                    <option>-- Select Floor --</option>
                </select>
                <input type="date" id="fDate" name="fDate">
                <label>-</label>
                <input type="date" id="tDate" name="tDate">
                <select id="BUYER" class="input">
                    <option>-- Select Buyer --</option>
                </select>
                <input id="SONO"  class="input" type="text" placeholder="SalesOrderNo..">
                <input id="STYLE" class="input" type="text" placeholder="Style..">
                <input id="COLOR" class="input" type="text" placeholder="Color..">
                <br>
                <select id="optLine" class="w3-select w3-border" style="width:25%;">
                    <option></option>
                </select>
                <button class="w3-button w3-teal" onclick="onDisplayReport()">DISPLAY REPORT</button>
                <button class="w3-button w3-teal" onclick="onRuntimeSelection()">REFREASH</button>
             </div>
            </div>
         <br>    
        <!-- dataGrid -->
             <div class="w3-container" style="overflow-y:scroll;"> 
                <table style="overflow-x:scroll;" class="w3-table-all  w3-striped w3-hoverable" id="tblMIS" align="center">
                <thead>
                        <tr >
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th colspan="2"  valign="top" class="w3-border-left w3-center">INPUT</th>
                            <th colspan="2"  valign="top" class="w3-border-left w3-center">OUTPUT</th>
                            <th colspan="2"  valign="top" class="w3-border-left w3-center">PAD SEWING</th>
                            <th colspan="2"  valign="top" class="w3-border-left w3-center">PACKING</th>
                        </tr>
                        <tr>
                            <th>No</th>
                            <th>Floor</th>
                            <th>LineNo</th>
                            <th>Buyer</th>
                            <th>Sono</th>
                            <th>Style</th>
                            <th>Color</th>
                            <th class="w3-border-left">Up to Date</th>
                            <th>As on Date</th>
                            <th class="w3-border-left">
                                <span>Up to Date</span><br>
                                <span style="font-size: 9px;font-weight: normal;">Tot:Ok|Def|Rej</span>
                            </th>
                            <th>
                                 <span>As on Date</span><br>
                                 <span style="font-size: 9px;font-weight: normal;">Tot:Ok|Def|Rej</span>
                            </th>
                            <th class="w3-border-left">
                                <span>Up to Date</span><br>
                                <span style="font-size: 9px;font-weight: normal;">Tot:Ok|Def|Rej</span>
                            </th>
                            <th>
                                 <span>As on Date</span><br>
                                 <span style="font-size: 9px;font-weight: normal;">Tot:Ok|Def|Rej</span>
                            </th>
                            <th class="w3-border-left">
                                <span>Up to Date</span><br>
                                <span style="font-size: 9px;font-weight: normal;">Tot:Ok|Def|Rej</span>
                            </th>
                            <th>
                                 <span>As on Date</span><br>
                                 <span style="font-size: 9px;font-weight: normal;">Tot:Ok|Def|Rej</span>
                            </th>
                        </tr>
                 </thead>
                    <tbody id="tblMISBody">
                    </tbody>
                    <tfoot>
                    <tr class="w3-light-grey">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="font-weight: bold;"><button class="w3-button w3-teal" onclick="onTotalFooter(1)">Get Total</button></td>
                            <td class="w3-border-left">
                                 <span id="INFTOTAL"style="font-weight: bold;">-</span>
                            </td>
                            <td>
                                 <span id="IDFTOTAL"style="font-weight: bold;">-</span>
                            </td>
                            <td class="w3-border-left">
                                <span id="FOA" style="font-weight: bold;">-</span>:
                                <span id="FOB" style="font-weight: bold; color: green;">-</span>|
                                <span id="FOC" style="font-weight: bold; color: #cccc33;">-</span>|
                                <span id="FOD" style="font-weight: bold; color: red;">-</span>
                            </td>
                            <td>
                                <span id="FOE" style="font-weight: bold;">-</span>:
                                <span id="FOF" style="font-weight: bold; color: green;">-</span>|
                                <span id="FOG" style="font-weight: bold; color: #cccc33;">-</span>|
                                <span id="FOH" style="font-weight: bold; color: red;">-</span>
                            </td>
                            <td class="w3-border-left">
                                <span id="FFA" style="font-weight: bold;">-</span>:
                                <span id="FFB" style="font-weight: bold; color: green;">-</span>|
                                <span id="FFC" style="font-weight: bold; color: #cccc33;">-</span>|
                                <span id="FFD" style="font-weight: bold; color: red;">-</span>
                            </td>
                            <td>
                                <span id="FFE" style="font-weight: bold;">-</span>:
                                <span id="FFF" style="font-weight: bold; color: green;">-</span>|
                                <span id="FFG" style="font-weight: bold; color: #cccc33;">-</span>|
                                <span id="FFH" style="font-weight: bold; color: red;">-</span>
                            </td>
                            <td class="w3-border-left">
                                <span id="FPA" style="font-weight: bold;">-</span>:
                                <span id="FPB" style="font-weight: bold; color: green;">-</span>|
                                <span id="FPC" style="font-weight: bold; color: #cccc33;">-</span>|
                                <span id="FPD" style="font-weight: bold; color: red;">-</span>
                            </td>
                            <td>
                                <span id="FPE" style="font-weight: bold;">-</span>:
                                <span id="FPF" style="font-weight: bold; color: green;">-</span>|
                                <span id="FPG" style="font-weight: bold; color: #cccc33;">-</span>|
                                <span id="FPH" style="font-weight: bold; color: red;">-</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
             </div>
        <!--End-dataGrid -->
        <br><br><br>
    <!--End-main body -->

    <!--footer -->
     <div class="w3-row w3-container w3-bottom w3-blue-grey">
        <a href="initialPage.php">
            <div class="w3-col s3 w3-blue-grey w3-center w3-border-right w3-hover-dark-grey " style="cursor: pointer ;">
                <p>
                    < BACK </p>
            </div>
        </a>
        <div class="w3-col s6 w3-blue-grey w3-center w3-border-right">
            <p>USERID:<span id="idUSERIDNEW"><?php echo $USERIDNEW ; ?></span></p>
        </div>
        <div class="w3-col s3 w3-blue-grey w3-center ">
            <p id="BUDAT"> <?php echo $date ; ?> </p>
        </div>
     </div>
    <!--End-footer -->

     <!--double click modal -->
    <div id="id01" class="w3-modal">
         <div class="w3-modal-content">
             <!--Head -->
             <div class="w3-container  w3-teal">
                    <div class="w3-col s11">
                        <p id="idModalHead" style="font-weight: bold;"></p>
                    </div>
                    <div class="w3-col s1 ">
                        <span onclick="onClear()" class="w3-button w3-display-topright w3-red">&times;</span>
                    </div>
             </div>
             <!--Body -->
                <!-- dataGrid -->
                <br>
                <div class="w3-container" style="overflow-y:scroll;"> 
                    <table class="w3-table-all  w3-striped w3-hoverable" id="tblMISDetails" align="center">
                        <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>INPUT DATE</th>
                                    <th>QTY</th>
                                </tr>
                        </thead>
                        <tbody id="tblMISDetailsBody">
                        </tbody>
                        <tfoot>
                            <tr class="w3-light-grey">
                                <td></td>
                                <td style="font-weight: bold;"><button class="w3-button w3-teal" onclick="onTotalFooter(2)">Get Total</button></td>
                                <td id="idMDetailFooter" style="font-weight: bold;"></td>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                    <br>
                    <div id="idDetailsSize" class="w3-container" style="overflow-y:scroll;" style="display: none;"> 
                        <table class="w3-table-all  w3-striped w3-hoverable" id="tblMISDetailsSize" align="center">
                            <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>INPUT DATE</th>
                                        <th>SIZE</th>
                                        <th>QTY</th>
                                    </tr>
                            </thead>
                            <tbody id="tblMISDetailsSizeBody">
                            </tbody>
                            <tfoot>
                                <tr class="w3-light-grey">
                                    <td></td>
                                    <td></td>
                                    <td style="font-weight: bold;"><button class="w3-button w3-teal" onclick="onTotalFooter(3)">Get Total</button></td>
                                    <td id="idMDetailSizeFooter" style="font-weight: bold;"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <br>
                    <div id="idDetailsSizeMore" class="w3-container" style="overflow-y:scroll;" style="display: none;"> 
                        <table class="w3-table-all  w3-striped w3-hoverable" id="tblMISDetailsSizeMore" align="center">
                            <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>INPUT DATE</th>
                                        <th>TID</th>
                                        <th>DOC NO</th>
                                        <th>DOWN TIME</th>
                                        <th>SYSTEM UPTIME</th>
                                        <th>SIZE</th>
                                        <th>UP QTY</th>
                                        <th>NOP</th>
                                        <th>QTY</th>
                                    </tr>
                            </thead>
                            <tbody id="tblMISDetailsSizeMoreBody">
                            </tbody>
                            <tfoot>
                                <tr class="w3-light-grey">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="font-weight: bold;"><button class="w3-button w3-teal" onclick="onTotalFooter(4)">Get Total</button></td>
                                    <td id="idMDetailSizeMoreFooter" style="font-weight: bold;"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <br>
                <!--End-dataGrid -->
            
             <!--Footer -->
             <div class="w3-container  w3-teal">
                    <div class="w3-col s12 ">
                        <p>@purbaniIT</p>
                    </div>
             </div>
         </div>
    </div>

    

 </body>


</html>