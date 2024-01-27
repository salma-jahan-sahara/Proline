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
            onFloorLoad(); 
            $("#tblWIP tbody").click(function(e) {
                 var $tr = $(e.target).closest('tr'),
                 rowId = ($tr).attr("id"); // Here you can capture the row id of clicked cell.
                 var Row = document.getElementById(rowId);
                 var Cells = Row.getElementsByTagName("td");
                 var pLINE = Cells[1].innerText;
                 var pSONO = Cells[2].innerText;
                 var pBYER = Cells[3].innerText;
                 var pSTYL = Cells[4].innerText;
                 var pCLOR = Cells[5].innerText;
                 var opid = 00;
                 var pCatg = $('input[name="PTYPE"]:checked').val();
                 if ( pCatg == "FINISHING"){
                    opid = 06 ;
                 } else if ( pCatg == "PACKING"){
                    opid = 07 ;
                 } else {
                    opid = 04 ; 
                 }

                 $.ajax({
                    type: 'POST',
                    url: 'ajaxLineAdjust.php',
                    data: {
                        opid: opid,
                        pLINE: pLINE,
                        pSONO: pSONO,
                        pBYER: pBYER,
                        pSTYL: pSTYL,
                        pCLOR: pCLOR
                    },
                    success: function(html) {
                        $('#tblWIPBodyModal').html(html);
                        document.getElementById("wipTOTAL").innerText ="";
                    }
                 });
                 
                 document.getElementById("idMCat").innerText = pCatg ;
                 document.getElementById("idMLine").innerText = pLINE ;
                 document.getElementById("idMSono").innerText = pSONO ;
                 document.getElementById("idMBuyer").innerText = pBYER ;
                 document.getElementById("idMStyle").innerText = pSTYL ;
                 document.getElementById("idMColor").innerText = pCLOR ;
                 document.getElementById('idModalWIP').style.display = 'block';
              });
          });

         function onFloorLoad(){
            var opid = 01;
                $.ajax({
                    type: 'POST',
                    url: 'ajaxLineAdjust.php',
                    data: {
                        opid: opid
                    },
                    success: function(html) {
                      document.getElementById("optFloor").innerHTML = "";
                      document.getElementById("optFloor").innerHTML = html;
                      onGetLine();
                    }
                });
          }
         function onGetLine() {
            var opid = 02;
            var FLOORNO = document.getElementById("optFloor").value;
            var lenFLOOR = FLOORNO.length;
            if (lenFLOOR > 2) {
                $.ajax({
                    type: 'POST',
                    url: 'ajaxLineAdjust.php',
                    data: {
                        opid: opid,
                        FLOORNO: FLOORNO
                    },
                    success: function(html) {
                        document.getElementById("optLine").innerHTML = "";
                        document.getElementById("optLine").innerHTML = html;
                    }
                });
            } else {
                alert("Please select floor.");
            }
          }
         function onDisplayWIP(){
                var prdty =  $('input[name="PTYPE"]:checked').val();
                var opid = 03;
                var lineNo =  document.getElementById("optLine").value;
                var lenlineNo = lineNo.length;

                if (prdty == "PACKING"){
                    //implement later 
                    lineNo = document.getElementById("optFloor").value; 
                } else {
                        if (lenlineNo < 0 ){
                            alert('Please select line No');
                            return;
                    }
                }
                $.ajax({
                        type: 'POST',
                        url: 'ajaxLineAdjust.php',
                        data: {
                            opid: opid,
                            lineNo: lineNo,
                            prdty: prdty
                        },
                        success: function(html) {
                            $('#tblWIPBody').html(html);
                            document.getElementById("wipTOTAL").innerText ="";
                        }
                    });
                onTotalWIP();
          }

         function onTotalWIP(){
            var WIPTOTAL = 0;
             $("#tblWIP tr.item").each(function() {
                     var lvWIP = parseInt($(this).find("td.WIP").text());
                     if (lvWIP != 0) {
                         WIPTOTAL = WIPTOTAL + lvWIP;
                      }
             })
             document.getElementById("wipTOTAL").innerText ="";
             document.getElementById("wipTOTAL").innerText = WIPTOTAL;
          }
         function onTotalWIPModal(){
            var IQTY = 0; var OQTY = 0 ; var WQTY = 0 ;
             $("#tblWIPModal tr.mITEM").each(function() {
                     var lvIQTY = parseInt($(this).find("td.IQTY").text());
                     var lvOQTY = parseInt($(this).find("td.OQTY").text());
                     var lvWQTY = parseInt($(this).find("td.WQTY").text());

                     if (lvIQTY != 0) {
                          IQTY = IQTY + lvIQTY;
                      }
                     if (lvOQTY != 0) {
                          OQTY = OQTY + lvOQTY;
                      }
                     if (lvWQTY != 0) {
                          WQTY = WQTY + lvWQTY;
                      }
             })
             document.getElementById("IQTYT").innerText ="";
             document.getElementById("IQTYT").innerText = IQTY;

             document.getElementById("OQTYT").innerText ="";
             document.getElementById("OQTYT").innerText = OQTY;

             document.getElementById("WQTYT").innerText ="";
             document.getElementById("WQTYT").innerText = WQTY;

          }
         function onClear() {
             onDisplayWIP();
             document.getElementById("idMLine").innerText = "" ;
             document.getElementById("idMSono").innerText = "" ;
             document.getElementById("idMBuyer").innerText = "" ;
             document.getElementById("idMStyle").innerText = "" ;
             document.getElementById("idMColor").innerText = "" ;
             document.getElementById("IQTYT").innerText ="";
             document.getElementById("OQTYT").innerText ="";
             document.getElementById("WQTYT").innerText ="";
             document.getElementById('idModalWIP').style.display = 'none';
          }
         function onSaveAdjust(){
                var usrid = document.getElementById("idUSERIDNEW").innerText;
                if (usrid.length < 0){
                    alert("Login Again,connnection problem.");
                    return;
                }
                var valueADJ = new Array();
                 $("#tblWIPBodyModal tr").each(function(i , row) {
                 var count = 0;
                 var Cells = row.getElementsByTagName("td");
                 var mCHKD = Cells[6].querySelector('input').checked;
                  if ( mCHKD == true && (Cells[5].firstChild.value).length > 0 ){
                     count = count + 1;
                     var mSIZE = Cells[1].innerText;
                     var mAQTY = Cells[5].firstChild.value;
                     var selVal = mSIZE+'|'+mAQTY ;
                     valueADJ.push(selVal);
                   }
                 });
                 if ( valueADJ.length > 0 ){
                         var opid = 00;
                         var pCatg = $('input[name="PTYPE"]:checked').val();
                            if ( pCatg == "FINISHING"){
                                opid = 10 ;
                            } else if ( pCatg == "PACKING"){
                                opid = 11 ;
                            } else {
                                opid = 05 ; 
                            }

                         pLINE = document.getElementById("idMLine").innerText  ;
                         pSONO = document.getElementById("idMSono").innerText  ;
                         pBYER = document.getElementById("idMBuyer").innerText ;
                         pSTYL = document.getElementById("idMStyle").innerText ;
                         pCLOR = document.getElementById("idMColor").innerText ;

                        $.ajax({
                            type: 'POST',
                            url: 'ajaxLineAdjust.php',
                            data: {
                                opid: opid,
                                usrid: usrid,
                                pLINE: pLINE,
                                pSONO: pSONO,
                                pBYER: pBYER,
                                pSTYL: pSTYL,
                                pCLOR: pCLOR,
                                pVADJ: valueADJ
                            },
                            success: function(msg) {
                               alert(msg);
                            }
                        });

                 } 
          }
     </script>
 </head>


<body class="w3-small" style="background-color: rgb(241, 241, 241);">
    
    <!-- header -->
     <div class="w3-row w3-blue-grey w3-top">
        <div class="w3-col s3 w3-blue-grey w3-center w3-border-right">
            <p>ADJUST</p>
         </div>
        <div class="w3-col s9 w3-blue-grey w3-center">
            <p>ADJUST DATA ON SEWING ,FINISHING , & PACKING </p>
         </div>
      </div>
      <br><br>
    <!-- header -->

    <!--main body -->
        <div class="w3-row" style="overflow-y:scroll;"> 
            <div class="w3-container">
                <select id="optFloor" class="w3-select w3-border" style="width:25%;" onchange="onGetLine()">
                    <option>-- Select Floor --</option>
                </select>
                <br>
                <select id="optLine" class="w3-select w3-border" style="width:25%;">
                    <option>-- Select Line --</option>
                </select>
                <button class="w3-button w3-teal" onclick="onDisplayWIP()">Display WIP</button>
                <input class="w3-radio" id="rbsew" type="radio" name="PTYPE" value="SEWING" checked>
                <label>Sewing</label>
                <input class="w3-radio" id="rbfin" type="radio" name="PTYPE" value="FINISHING">
                <label>Finishing</label>
                <input class="w3-radio" id="rbpak" type="radio" name="PTYPE" value="PACKING">
                <label>PACKING</label>
             </div>
           </div>
         <br>    
        <!-- dataGrid -->
             <div class="w3-container" style="overflow-y:scroll;"> 
                <table class="w3-table-all  w3-striped w3-hoverable" id="tblWIP">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>slno</th>
                            <th>LineNo</th>
                            <th>SO</th>
                            <th>Buyer</th>
                            <th>Style</th>
                            <th>Color</th>
                            <th>NOP</th>
                            <th>WIP.Qty</th>
                        </tr>
                    </thead>
                    <tbody id="tblWIPBody">
                    </tbody>
                    <tfoot>
                    <tr class="w3-light-grey">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="font-weight: bold;"><button class="w3-button w3-teal" onclick="onTotalWIP()">Get Total</button></td>
                            <td id="wipTOTAL"style="font-weight: bold;">00000</td>
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

    <!--double click modal For DHU -->
        <div id="idModalWIP" class="w3-modal">
             <div class="w3-modal-content">
                <header class="w3-row w3-red">
                        <span onclick="onClear()" class="w3-button w3-red w3-hover-dark-grey w3-display-topright">&times;</span>
                        <div class="w3-col s1 w3-blue-grey w3-center w3-border-right">
                            <p id="idMCat">-</p>
                        </div>
                        <div class="w3-col s2 w3-blue-grey w3-center w3-border-right">
                            <p id="idMLine">-</p>
                        </div>
                        <div class="w3-col s2 w3-blue-grey w3-center w3-border-right">
                            <p id="idMSono">-</p>
                        </div>
                        <div class="w3-col s2 w3-blue-grey w3-center w3-border-right">
                            <p id="idMBuyer">-</p>
                        </div>
                        <div class="w3-col s2 w3-blue-grey w3-center w3-border-right">
                            <p id="idMStyle">-</p>
                        </div>
                        <div class="w3-col s2 w3-blue-grey w3-center w3-border-right">
                            <p id="idMColor">-</p>
                        </div>
                        <div onclick="onClear()" class="w3-col s1 w3-red w3-center w3-border-right w3-hover-dark-grey">
                            <p>X</p>
                        </div>
                 </header>
                <div class="w3-row" style="overflow-y:scroll;">
                     <table class="w3-table-all  w3-striped w3-hoverable" id="tblWIPModal">
                            <thead>
                                <tr class="w3-light-grey">
                                    <th>NO</th>
                                    <th>SIZE</th>
                                    <th>INPUT</th>
                                    <th>OUTPUT</th>
                                    <th>WIP</th>
                                    <th>ADJUST</th>
                                    <th>SELECT</th>
                                </tr>
                            </thead>
                            <tbody id="tblWIPBodyModal">
                            </tbody>
                            <tfoot>
                            <tr class="w3-light-grey" style="font-weight: bold;">
                                    <td></td>
                                    <td><button class="w3-button w3-teal" onclick="onTotalWIPModal()">Get Total</button></td>
                                    <td id="IQTYT">0000</td>
                                    <td id="OQTYT">0000</td>
                                    <td id="WQTYT">0000</td>
                                    <td>-</td>
                                    <td><button class="w3-button w3-teal" onclick="onSaveAdjust()">SAVE</button></td>
                                </tr>
                            </tfoot>
                     </table>
                 </div>
              </div>
         </div>
    <!--END - double click modal For DHU -->

 </body>


</html>