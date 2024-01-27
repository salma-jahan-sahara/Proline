<!DOCTYPE html>
<html>

<head>
     <link rel="stylesheet" type="text/css" href="css/message.css"></link>
     <link rel="stylesheet" type="text/css" href="css/w3.css"></link>
     <script src="jquery.min.js"></script>
     <script language="javascript" type="text/javascript">

        $(function() {
            onLoadSessionVar();
            onLoadCounty();
        });

        function onLoadSessionVar() {
            var opid = 1;
            $.ajax({
                type: 'POST',
                url: 'ajaxlineOutType.php',
                data: {
                    opid: opid
                },
                dataType: "json",
                success: function(data) {
                    document.getElementById('idPRDTY').innerText = data['PRDTY'];
                    document.getElementById('idSONO').innerText =  data['sono'];
                    document.getElementById('idBUYER').innerText = data['BUYER'];
                    document.getElementById('idSTYLE').innerText = data['STYLE'];
                    document.getElementById('idCOLOR').innerText = data['COLOR'];
                    document.getElementById('idLINE').innerText  = data['LINE'];
                    document.getElementById('idUIDNEW').innerText = data['UIDNEW'];
                    document.getElementById('idDate').innerText =  data['DATE'];
                    onLoadPADSEW(data['PRDTY'])
                }
            })
         }

        function onLoadCounty() {
            var opid = 2;
            $.ajax({
                type: 'POST',
                url: 'ajaxlineOutType.php',
                data: {
                    opid: opid
                },
                success: function(html) {
                    $('#idModal').html(html);
                }
            })
         }

        function onCountryDisplay() {
            document.getElementById('idCountry').innerText = '-';
            document.getElementById('id01').style.display='block'
         }

        function onCountryClick(param){
            document.getElementById('idCountry').innerText = param;
            document.getElementById('id01').style.display='none';
         }

        function onNextPage(param){
            var LNNEW = document.getElementById('idLINE').innerText;
            var IDNEW = document.getElementById('idUIDNEW').innerText;
            var PTNEW = document.getElementById('idPRDTY').innerText;
            var CNTRY = document.getElementById('idCountry').innerText;
            var lvBuyer = document.getElementById('idBUYER').innerText;
            var FITTY = param;
            if ( lvBuyer == 'H&M' && CNTRY == '-' && FITTY == 'FIT'){ ToastMessage("COUNTRY :PLEASE SELECT."); return ;}
            var opid = 3;
            $.ajax({
                type: 'POST',
                url: 'ajaxlineOutType.php',
                data: {
                    opid: opid,
                    PTNEW: PTNEW,
                    LNNEW: LNNEW,
                    IDNEW: IDNEW,
                    FITTY: FITTY,
                    CNTRY: CNTRY
                },
                success: function(data1) {
                    window.location.href = 'lineOut.php'
                }
            })
         }
        function onPreviousPage(param){
            if(param == 1){
              window.location.href = 'lineIn.php';
            } else if (param == 2){
              window.location.href = 'login.php';
            }
         }

         function onMYBOX(){
            
            var VTYP = document.getElementById('idpty').innerText;
            var VSNO = document.getElementById('SONO').innerText;
            var VUID = document.getElementById('UID').innerText;
            var VBUDAT = document.getElementById("BUDAT").innerText;
            var VCOLOR = document.getElementById("COLOR").innerText;
            var opid = '';
            if ( VTYP == 'SEWING') {
                 opid = 3;
            } else if ( VTYP == 'FINISHING' ) {
                 opid = 4;
            }
         
            $.ajax({
                type: 'POST',
                url: 'ajaxLineOut.php',
                data: { OPPID: opid ,
                        VSNO: VSNO ,
                        VBUDAT: VBUDAT,
                        VCOLOR: VCOLOR
                    },
                success: function(html) {
                    $('#tblmyBOXBody').html(html);
                }
            });
            document.getElementById('id02').style.display='block' ;
         }
        function ToastMessage(msg) {
                document.getElementById("snackbar").innerHTML = msg;
                var x = document.getElementById("snackbar");
                x.className = "show";
                setTimeout(function() {
                    x.className = x.className.replace("show", "");
                }, 2000);
            }
        function onCountryNew(param) {
            var OPT = param;
            var opid = 4;
            $.ajax({
                type: 'POST',
                url: 'ajaxlineOutType.php',
                data: {
                    opid: opid,
                    opt: OPT
                },
                success: function(html) {
                    $('#idModal').html(html);
                }
            })
         }

        function onMYBOX(){

            var VTYP = (document.getElementById('idPRDTY').innerText).trim();
            var VSNO = document.getElementById('idSONO').innerText;
            var VUID = document.getElementById('idUIDNEW').innerText;
            var VBUDAT = document.getElementById("idDate").innerText;
            var VLINE = document.getElementById("idLINE").innerText;
            var opid = '';
            if ( VTYP == 'SEWING') {
                 opid = 5;
            } else if ( VTYP == 'FINISHING' ) {
                 opid = 6;
            } else if ( VTYP == 'PACKING' ) {
                 opid = 61;
            }
         
            $.ajax({
                type: 'POST',
                url: 'ajaxlineOutType.php',
                data: { opid: opid ,
                        VSNO: VSNO ,
                        VBUDAT: VBUDAT,
                        VLINE: VLINE
                    },
                success: function(html) {
                    $('#tblmyBOXBody').html(html);
                }
            });
            document.getElementById('id02').style.display='block' ;
         }
        function onLoadPADSEW(pPRDTYP){
            //this function incorporate dut to name change FINISHING to PAD SEWING
            // finsing condition uncahnge & display hide ,just new span added for new PAD SEWING
            var vPAD = pPRDTYP.replace(/\s/g,'') ; 
            if ( vPAD === "FINISHING"){
                document.getElementById("idPADSEW").innerText = "PAD SEWING"
            } else {
                document.getElementById("idPADSEW").innerText = vPAD ; 
            }
         }
         

         
     </script>
</head>

<body >
    <center>
        <!-- Start: div head part -->
        <div class="w3-row w3-blue-grey">
           <div class="w3-col s2 w3-blue-grey w3-center w3-border-right">
                <!-- <p id="idPRDTY">-</p> -->
                <p>
                    <span id="idPRDTY" style="display: none;">-</span>
                    <span id="idPADSEW"></span>
                </p>

            </div>
            <div class="w3-col s2 w3-blue-grey w3-center w3-border-right">
                <p id="idSONO">-</p>
            </div>
            <div class="w3-col s2 w3-blue-grey w3-center w3-border-right">
                <p id="idBUYER">-</p>
            </div>
            <div class="w3-col s3 w3-blue-grey w3-center w3-border-right">
                <p id="idSTYLE">-</p>
            </div>
            <div class="w3-col s3 w3-blue-grey w3-center">
                <p id="idCOLOR">-</p>
            </div>
         </div>
        <!-- End: div head part -->
        
        <div style="height:10px"></div> <!-- spacer -->

        <!-- div body country -->
        <div class="w3-row w3-blue-grey">
            <div class="w3-col s2 w3-teal w3-center w3-border-right w3-card-4">
                <p style="font-weight: bold;" id="idCountry">-</p>
            </div>
             <div onClick="onCountryDisplay()" class="w3-col s10 w3-teal w3-center w3-border-right w3-hover-dark-grey w3-card-4" style="cursor: pointer">
                   <p style="font-weight: bold;">PRESS FOR COUNTRY</p>
             </div>
        </div>
        <br>
        <!-- div body country -->

        <!-- div body container -->
        <div class="w3-row">
            <!-- left -->
            <div class="w3-col s5 w3-center">
                 <div  onclick="onNextPage('FIT')" class="w3-panel w3-green w3-xxlarge w3-left-align w3-text-black  w3-card-4 w3-hover-dark-grey" style="cursor: pointer">
                     <p>PRODUCTION</p>
                 </div>

                 <div  onclick="onNextPage('DEFECT')" class="w3-panel w3-yellow w3-xxlarge w3-left-align w3-text-black w3-card-4 w3-hover-dark-grey" style="cursor: pointer">
                     <p>DEFECTIVES</p>
                 </div>
            </div>
            <!--END-left -->

            <!-- middle -->
            <div class="w3-col s1 w3-white w3-center">
                <p></p>
            </div>
            <!--END-middle -->

            <!-- right -->
            <div class="w3-col s6 w3-center">
                 <div onclick="onNextPage('REJECT')" class="w3-panel w3-red w3-xxlarge w3-left-align w3-text-black w3-card-4 w3-hover-dark-grey" style="cursor: pointer">
                     <p>REJECT</p>
                 </div>
                <a href="lineOut.php?type=<?php echo 'RECENT DEFECTS'; ?>" style="text-decoration:none;">
                    <div class="w3-panel w3-yellow  w3-xlarge w3-left-align w3-card-2" style="display: none;">
                        <p>RECENT DEFECTS</p>
                    </div>
                </a>
                <a href="lineOut.php?type=<?php echo 'ALTER'; ?>" style="text-decoration:none;">
                    <div class="w3-panel w3-blue  w3-large w3-left-align w3-text-black w3-card-2"
                        style="display: none;">
                        <p>ALTER</p>
                    </div>
                </a>
            </div>
            <!--END-right -->
        </div>
        <!--END-div body container -->

         <div style="height:10px"></div> <!-- spacer -->

        <!--footer -->
         <div class="w3-row w3-bottom w3-blue-grey">
             <div onclick="onPreviousPage(1)" class="w3-col s2 w3-blue-grey w3-center w3-border-right w3-hover-dark-grey" style="cursor: pointer">
                     <p> < BACK </p>
             </div>
            <div onclick="onMYBOX()" class="w3-col s2  w3-center w3-hover-dark-grey w3-border-right" style="cursor: pointer ;">
               <p>MY-BOX</p>
             </div>
            <div class="w3-col s2 w3-blue-grey w3-center w3-border-right">
                     <p>LINENO:<span id="idLINE">-</span></p>
            </div>
            <div class="w3-col s2 w3-blue-grey w3-center w3-border-right">
                     <p>USERID:<span id="idUIDNEW">-</span></p>
            </div>
            <div class="w3-col s2 w3-blue-grey w3-center w3-border-right">
                     <p id="idDate">-</p>
            </div>
            <div onclick="onPreviousPage(2)" class="w3-col s2  w3-center w3-hover-dark-grey" style="cursor: pointer;background-image: url('image/backArrow.png');">
             <p>EXIT</p>
            </div>
         </div>
        <!--footer -->

         <!--modal country -->
         <div id="id01" class="w3-modal" >
             <div class="w3-modal-content">
                <header class="w3-container w3-blue-grey">
                     <span onclick="document.getElementById('id01').style.display='none'"
                     class="w3-button w3-display-topright"style="margin-top: 5px;">&times;</span>

                         <div onclick="onCountryNew('6')" class="w3-col s1 w3-blue-grey w3-center w3-border-right w3-hover-dark-grey" style="cursor: pointer">
                            <p>ASIA</p>
                        </div>
                        <div onclick="onCountryNew('7')" class="w3-col s2 w3-blue-grey w3-center w3-border-right w3-hover-dark-grey" style="cursor: pointer">
                            <p>EUROPE</p>
                        </div>
                        <div onclick="onCountryNew('8')" class="w3-col s2 w3-teal w3-center w3-border-right w3-hover-dark-grey" style="cursor: pointer">
                            <p>US|USA</p>
                        </div>
                        <div onclick="onCountryNew('9')" class="w3-col s2 w3-blue-grey w3-center w3-border-right w3-hover-dark-grey" style="cursor: pointer">
                            <p>AFRICA</p>
                        </div>
                        <div onclick="onCountryNew('10')" class="w3-col s2 w3-teal w3-center w3-border-right w3-hover-dark-grey" style="cursor: pointer">
                            <p>NOR|NORMAL</p>
                        </div>
                        <div onclick="onCountryNew('11')" class="w3-col s2 w3-blue-grey w3-center w3-border-right w3-hover-dark-grey" style="cursor: pointer">
                            <p>ALL</p>
                        </div>

                </header>
                 <div class="w3-row">
                        <!-- <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-display-topright w3-red" style="margin-top: 5px;">&times;</span> -->
                     <div class="w3-row" id="idModal">
                     </div>
                 </div>
                <footer class="w3-container w3-blue-grey">

                     <div onclick="onCountryNew('1')" class="w3-col s2 w3-blue-grey w3-center w3-border-right w3-hover-dark-grey" style="cursor: pointer">
                         <p>A-E</p>
                     </div>
                     <div onclick="onCountryNew('2')" class="w3-col s2 w3-blue-grey w3-center w3-border-right w3-hover-dark-grey" style="cursor: pointer">
                         <p>F-J</p>
                     </div>
                     <div onclick="onCountryNew('3')" class="w3-col s2 w3-blue-grey w3-center w3-border-right w3-hover-dark-grey" style="cursor: pointer">
                         <p>K-O</p>
                     </div>
                     <div onclick="onCountryNew('4')" class="w3-col s2 w3-blue-grey w3-center w3-border-right w3-hover-dark-grey" style="cursor: pointer">
                         <p>P-T</p>
                     </div>
                     <div onclick="onCountryNew('5')" class="w3-col s2 w3-blue-grey w3-center w3-border-right w3-hover-dark-grey" style="cursor: pointer">
                         <p>U-Z</p>
                     </div>

                </footer>
             </div>
         </div>
         <!--modal country -->

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
                    <table class="w3-table-all  w3-striped w3-hoverable" id="tblmyBOX">
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

         <!--For toast message-->
         <div id="snackbar"></div>

    </center>
</body>

</html>