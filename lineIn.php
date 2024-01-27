<?php
    session_start(); 
    $lineNo = $_SESSION['lineNo']; 
    $USERIDNEW = $_SESSION['USERIDNEW']; 
    $prdty = $_SESSION['prdty']; 
 ?>
 <?php
       // $result = $db->query($sql) ;
        $date = date('d-m-Y');
        if(isset($_POST['submit']))
            {
                $_SESSION['sono'] = $_POST['submit'] ;  
            }
    ?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="css/w3.css">
    </link>
    <script type="text/javascript" src="jquery.min.js"></script>
    <script language="javascript" type="text/javascript">
     $(function() {
        onDateLoad();
        onGridLoad();
        onLoadMessage()
        $("#lineInTbl tbody").click(function(e) {
            var $tr = $(e.target).closest('tr');
            rowId = ($tr).attr("id"); // Here you can capture the row id of clicked cell.
            var Row = document.getElementById(rowId);
            var Cells = Row.getElementsByTagName("td");
            var mSONO = Cells[1].innerText;
            var mBUYER = Cells[2].innerText;
            var mSTYLE = Cells[3].innerText;
            var mCOLOR = Cells[4].innerText;
            var OPPID = 32;
            var mLINE = document.getElementById("idLINENUM").innerText;
            var mUID = document.getElementById("idUSERIDNEW").innerText;
            var PRDTY = document.getElementById("idpty").innerText;
            $.ajax({
                type: 'POST',
                url: 'ajaxLineIn.php',
                data: {
                    OPPID: OPPID,
                    SONO: mSONO,
                    BUYER: mBUYER,
                    STYLE: mSTYLE,
                    COLOR: mCOLOR,
                    LINE: mLINE,
                    UID: mUID,
                    PRDTY:PRDTY
                },
                success: function(html) {
                    window.location.href = "lineOutType.php";
                }
            });

         });

      });


     function onGridLoad() {
        var OPPID = 31;
        $.ajax({
            type: 'POST',
            url: 'ajaxLineIn.php',
            data: 'OPPID=' + OPPID,
            success: function(html) {
                $('#tLineIn').html(html);
            }
        });
      }
     function onMYBOX(){
            var VTYP = (document.getElementById('idpty').innerText).trim();
            var VBUDAT = document.getElementById("BUDAT").innerText;
            var VLINE = document.getElementById("idLINENUM").innerText;
            var opid = '';
            if ( VTYP == 'SEWING') {
                 opid = 3;
            } else if ( VTYP == 'FINISHING' ) {
                 opid = 4;
            } else if ( VTYP == 'PACKING' ) {
                 opid = 41;
            }
          
            $.ajax({
                type: 'POST',
                url: 'ajaxLineIn.php',
                data: { OPPID: opid ,
                        VBUDAT: VBUDAT,
                        VLINE: VLINE
                    },
                success: function(html) {
                    $('#tblmyBOXBody').html(html);
                }
            });
            document.getElementById('id02').style.display='block' ;
      }
     function onMSG(){
             //alert('unsderConstruction');
             document.getElementById('id03').style.display='block' ;
      }
     function openCity(cityName) {
            var i;
            var x = document.getElementsByClassName("city");
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";  
            }
            document.getElementById(cityName).style.display = "block";  
      }
     function onSendMessage(){
          var optyp = document.getElementById("idpty").innerText;
          var usrid = document.getElementById("idUSERIDNEW").innerText;
          var msgbd = document.getElementById("idMsgBody").value;
          var msglen = msgbd.length;
          var opid = 5;
          //blank message retrun .
          if ( msglen == 0){
              alert("Message box is empty.");
              return;
           }
          $.ajax({
                type: 'POST',
                url: 'ajaxLineIn.php',
                data: { 
                    OPPID: opid,
                    OTYPE: optyp,
                    LOGID: usrid,
                    MSGDT: msgbd
                 },
                success: function(msg) {
                     alert(msg);
                     onLoadMessage();
                }
            });
      }
     function onMsgHistory(){
          alert('Underconstruction');
      }
     function onDateLoad(){
        var opid = 6;
        $.ajax({
            type: 'POST',
            url: 'ajaxLineIn.php',
            data: {
                opid: opid
            },
            success: function(rdate) {
                var myDate = rdate;
                var chunks = myDate.split('-');
                var formattedDate = chunks[2] + '-' + chunks[1] + '-' + chunks[0];
                gvCurrDate = chunks[1] + '/' + chunks[0] + '/' + chunks[2] ;
                document.getElementById("idate").value = formattedDate;
            }
         });
      }
     function onLoadMessage(){
            var OPPID = 7;
            $.ajax({
                type: 'POST',
                url: 'ajaxLineIn.php',
                data: 'OPPID=' + OPPID,
                success: function(msg) {
                    $('#tblmHistoryBody').html(msg);
                }
            });

      }
    </script>
 </head>

 
<body class="w3-small" style="background-color: rgb(241, 241, 241);">
    <!--main body -->

    <!-- header -->
     <div class="w3-row w3-blue-grey w3-top">
        <div class="w3-col s2 w3-blue-grey w3-center w3-border-right">
            <!-- <p id="idpty"> <?php echo $prdty ; ?> </p> -->
            <p>
             <span id="idpty" style="display: none;"> <?php echo $prdty ; ?>  </span>
             <span>
                 <?php if ($prdty == "FINISHING") { echo "PAD SEWING" ;} else { echo $prdty ; }  ?>  
            </span>
            </p>
         </div>
        <div class="w3-col s10 w3-blue-grey w3-center">
            <p id="idLINENUM"><?php echo $lineNo ; ?> </p>
         </div>
      </div>
    <!-- header -->

    <br><br>

    <!-- Container div for dataGrid -->
     <div class="w3-row" style="overflow-y:scroll;"> 
        <!-- dataGrid -->
        <table class="w3-table-all  w3-striped w3-hoverable" id="lineInTbl">
            <thead>
                <tr class="w3-light-grey">
                    <th>No</th>
                    <th>Sono</th>
                    <th>Buyer</th>
                    <th>Style</th>
                    <th>Color</th>
                    <th>NOP</th>
                    <th>WIP.Qty</th>
                </tr>
            </thead>
            <tbody id="tLineIn">
            </tbody>
        </table>
        <!--End-dataGrid -->
      </div>
     <br><br><br>
    <!--End-Container div for dataGrid -->

    <!--footer -->
     <div class="w3-row w3-container w3-bottom w3-blue-grey">
        <a href="initialPage.php">
            <div class="w3-col s2 w3-blue-grey w3-center w3-border-right w3-hover-dark-grey " style="cursor: pointer ;">
                <p>
                    < BACK </p>
            </div>
        </a>
        <div onclick="onMSG()" class="w3-col s2 w3-blue-grey w3-center w3-border-right w3-hover-dark-grey" style="cursor: pointer ;"> 
            <p>MESSAGE</p>
         </div>
        <div onclick="onMYBOX()" class="w3-col s2  w3-center w3-hover-dark-grey w3-border-right" style="cursor: pointer ;">
               <p>MY-BOX</p>
        </div>
        <div class="w3-col s3 w3-blue-grey w3-center w3-border-right">
            <p>USERID:<span id="idUSERIDNEW"><?php echo $USERIDNEW ; ?></span></p>
        </div>
        <div class="w3-col s3 w3-blue-grey w3-center ">
            <p id="BUDAT"> <?php echo $date ; ?> </p>
        </div>
     </div>
    <!--End-footer -->

     <!--Modal myBOX-->
         <div id="id02" class="w3-modal" >
             <div class="w3-modal-content">
                <header class="w3-container w3-blue-grey">
                     <span onclick="document.getElementById('id02').style.display='none'"
                     class="w3-button w3-display-topright"style="margin-top: 5px;">&times;</span>
                     <p>MY-BOX Details.</p>
                </header>
                 <div class="w3-row">
                    <!--START:DataGrid LineWise -->
                    <table class="w3-table-all  w3-striped w3-hoverable w3-small" id="tblmyBOX">
                        <thead>
                            <tr class="w3-light-grey">
                                <th>Line</th>
                                <th>SO</th>
                                <th>Buyer</th>
                                <th>Style</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Country</th>
                                <th>OK</th>
                                <th>Defect</th>
                                <th>Reject</th>
                                <th>TotalQty</th>
                            </tr>
                        </thead>
                        <tbody id="tblmyBOXBody">
                        </tbody>
                    </table>
                    <!--END:DataGrid LineWise -->
                 </div>
                <footer class="w3-container w3-blue-grey">
                    <p>-</p>
                </footer>
             </div>
         </div>
    <!--END-Modal myBOX-->

     <!--Modal myBOX message part-->
         <div id="id03" class="w3-modal" >
             <div class="w3-modal-content">
                <header class="w3-container w3-blue-grey">
                     <span onclick="document.getElementById('id03').style.display='none'"
                     class="w3-button w3-display-topright"style="margin-top: 5px;">&times;</span>
                     <p>Message</p>
                 </header>
                <div class="w3-row">
                     <div class="w3-bar w3-black">
                      <button class="w3-bar-item w3-button" onclick="openCity('SEND')">SEND</button>
                      <button class="w3-bar-item w3-button" onclick="openCity('HISTORY')">HISTORY</button>
                      </div>
                     <div id="SEND" class="w3-container city">
                         <br>
                         <textarea id="idMsgBody" class="w3-input w3-border" style="resize:none" spellcheck="false"></textarea>
                        <br>
                        <button onclick="onSendMessage()" class="w3-button w3-teal w3-mobile" id="idBMESS">SEND MESSAGE</button>
                        <br>
                        <br>
                      </div>
                     <div id="HISTORY" class="w3-container city" style="display:none">
                         <input type="date" id="idate" name="hDate" style="display:none">
                         <button onclick="onMsgHistory()" class="w3-button w3-teal w3-mobile" id="idBHistory" style="display:none">Message History</button>
                         <br>
                         <!-- dataGrid -->
                            <table class="w3-table-all  w3-striped w3-hoverable" id="tblmHistory">
                                <thead>
                                    <tr class="w3-light-grey">
                                        <th>No</th>
                                        <th>Department</th>
                                        <th>TID</th>
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
                <footer class="w3-container w3-blue-grey">
                    <p>-</p>
                 </footer>
             </div>
          </div>
     <!--END-Modal myBOX message part-->

 </body>
<!--End-main body -->

</html>