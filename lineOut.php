<?PHP session_start();  
    $vPTYP  = $_SESSION['PRDTY'];
    $vSONO  = $_SESSION['sono'];
    $vBUYER = $_SESSION['BUYER'];
    $vSTYLE = $_SESSION['STYLE'];
    $vCOLOR = $_SESSION['COLOR'];
    $vFIT  = $_SESSION['FITTY'];
    $vCNTY = $_SESSION['CNTRY'];
    $vLINE = $_SESSION['LNNEW'];
    $vUID  = $_SESSION['IDNEW'];
    $vDATE = $_SESSION['DATE'];
?>
<!DOCTYPE html>
<html>
<head>
     <link rel="stylesheet" type="text/css" href="css/message.css"></link>
     <link rel="stylesheet" type="text/css" href="css/w3.css"></link>
     <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css"></link>
     <script src="jquery.min.js"></script>
     <script language="javascript" type="text/javascript">
        $(function() {
            onLoadUrlVar();
            onLoadSessionVar();
            onSizeLoad();
            onRejectOperationLoad();
            onLoadRejectOld();
            onBGColorChange();
         });
        function onLoadSessionVar() {
                var opid = 1;
                $.ajax({
                    type: 'POST',
                    url: 'ajaxlineOut.php',
                    data: {
                        OPPID: opid
                    },
                    dataType: "json",
                    success: function(data) {
                        document.getElementById('idpty').innerText = data['PTNEW'];
                        document.getElementById('SONO').innerText =  data['sono'];
                        document.getElementById('BUYER').innerText = data['BUYER'];
                        document.getElementById('STYLE').innerText = data['STYLE'];
                        document.getElementById('COLOR').innerText = data['COLOR'];
                        document.getElementById('FITTY').innerText = data['FITTY'];
                        document.getElementById('idLINE').innerText  = data['LNNEW'];
                        document.getElementById('UID').innerText = data['IDNEW'];
                        document.getElementById('BUDAT').innerText =  data['DATE'];
                        document.getElementById('CC').innerText =  data['CNTRY'];
                    }
                })
         }
        function onSizeLoad() {
                var PRDTY = document.getElementById("idpty").innerText;
                var OPPID = 31;
                $.ajax({
                    type: 'POST',
                    url: 'ajaxLineOut.php',
                    data:{
                        OPPID: OPPID,
                        PRDTY: PRDTY
                    },
                    success: function(html) {
                        $('#idSizeDiv').html(html);
                    }
                });
                
         }
        function onSave() {
            if (document.getElementById("qty").innerText == 0) {
                ToastMessage("Wrong: 0 - QTY");
                return;
            }
            var objdef = [];
            if (($("button.defitem").length) >= 1) {
                $('.defitem').each(function(i, obj) {
                    var dItem = obj.innerHTML;
                    var localVar = String(dItem.trim());
                    localVar = localVar.replace(/<\/?span[^>]*>/g,"");
                    localVar = localVar.substr(0,localVar.indexOf(')')+1);                
                    //defect qty.
                    var defQty = parseInt(document.getElementById(localVar).innerText);
                    //defect name.
                    var defName = localVar;
                    //defect id.
                    var defId = localVar.match(/\d+/);
                    tmp = {
                        'id': defId,
                        'name': defName.trim(),
                        'dqty': defQty
                    };
                    objdef.push(tmp);
                });
            } else {
                objdef = ['']
            };
            
            var OPPID = 11;
            var BUDAT = document.getElementById("BUDAT").innerText;
            var SONO = document.getElementById("SONO").innerText;
            var BUYER = document.getElementById("BUYER").innerText;
            var STYLE = document.getElementById("STYLE").innerText;
            var COLOR = document.getElementById("COLOR").innerText;
            var SIZE = document.getElementById("size").innerText;
            var QTY = document.getElementById("qty").innerText;
            var UID = document.getElementById("UID").innerText;
            var FITTY = document.getElementById("FITTY").innerText;
            var OPID = document.getElementById("OPID").innerText;
            var LINE = document.getElementById("idLINE").innerText;
            var idUID = document.getElementById("UID").innerText;
            var PRDTY = document.getElementById("idpty").innerText;
            var varCC = document.getElementById('CC').innerText;
            if ( CC != '-'){
                 varCC = varCC.substring(0,2);
             }
            $.ajax({
                type: 'POST',
                url: 'ajaxLineOut.php',
                data: {
                    OPPID: OPPID,
                    BUDAT: BUDAT,
                    SONO: SONO,
                    BUYER: BUYER,
                    STYLE: STYLE,
                    COLOR: COLOR,
                    SIZE: SIZE,
                    QTY: QTY,
                    UID: UID,
                    TYPE: FITTY,
                    OPID: OPID,
                    REJID: objdef,
                    LINE: LINE,
                    UID: idUID,
                    PRDTY: PRDTY,
                    CC: varCC
                },
                success: function(msg) {
                    ToastMessage(msg);
                    if (FITTY == 'DEFECT'){
                        setTimeout(function(){
                        $(location).attr('href', 'lineOutType.php')
                        }, 1000)
                    }
                }
             });

            document.getElementById("OPID").innerText = '';
            $("button.defitem").remove();
            document.getElementById("qty").innerText = 1;
            document.getElementById("size").innerText = 'SIZE';
            document.getElementById('idSizeDiv').style.display = 'block';
            document.getElementById('rejOpDev').style.display = 'none';
            document.getElementById('rejListDev').style.display = 'none';
         }
        function ToastMessage(msg) {
                document.getElementById("snackbar").innerHTML = msg;
                var x = document.getElementById("snackbar");
                x.className = "show";
                setTimeout(function() {
                    x.className = x.className.replace("show", "");
                }, 2000);
            }
        function onRejectOperationLoad() {
                var PRDTY = document.getElementById("idpty").innerText;
                var OPPID = 32;
                $.ajax({
                    type: 'POST',
                    url: 'ajaxLineOut.php',
                    data: {OPPID: OPPID,
                        PRDTY: PRDTY
                    },
                    success: function(html) {
                        $('#rejOpDiv').html(html);
                    }
                });
         }
        function onRjectList(param) {
            var opid = param;
            var PRDTY = document.getElementById("idpty").innerText;
            $.ajax({
                type: 'POST',
                url: 'ajaxRejectList.php',
                data: { opid: opid,
                        PRDTY: PRDTY 
                    },
                success: function(html) {
                    $('#opid1').html(html);
                }
            });
            document.getElementById("OPID").innerText = opid;
            //document.getElementById("REJID").innerText ='';
         }
        function onRejectClick(param) {
            // new code for multiple defect enable
            var defclicked = param.trim();
            var lendef = $("button.defitem").length;
            var iddef = lendef;
            var lv2 = "";
            var divdef = "<button name= '" + defclicked + "' onclick='onAddDefQty(this.name)' style='float:left;margin-right:10px;margin-top:5px;'class='w3-yellow w3-border w3-hover-red defitem'> " + defclicked  + " <span id= '" + defclicked  + "' class='w3-badge w3-margin-left w3-green defspanitem'>1</span></button>" ;
            if (lendef >= 1) {
                var oldval = '';
                var lvdefflag = 0;
                $('.defitem').each(function(i, obj) {
                    oldval = obj.innerHTML;
                    var lv1 = String(oldval.trim());
                    lv1 = lv1.replace(/<\/?span[^>]*>/g,"");
                    lv2 = String(defclicked.trim());
                    var lv2len = lv2.length ;
                    lv1 = lv1.substr(0,lv2len);
                    if (lv1 === lv2) {
                        lvdefflag = 1;
                    }
                });
                if (lvdefflag == 0) {
                    $(".defcontain").append(divdef);
                } else {
                var lv3 = parseInt(document.getElementById(lv2).innerText);
                lv3 = lv3 + 1;
                document.getElementById(lv2).innerHTML = lv3;
                }
            } else if (lendef == 0) {
                $(".defcontain").append(divdef);
            }
         }
        function onAddDefQty(param){
            var val = parseInt(document.getElementById(param).innerText);
            if ( val > 1 ){
                document.getElementById(param).innerText = val-1 ;
            }else {
                var btnName = document.getElementsByName(param);
                $(btnName).remove();
            }
         }
        function onSizeClick(param) {
            document.getElementById("size").innerText = param;
            document.getElementById("size").style.fontWeight = "900";
         }
        function onAddQty(param) {
            var val = parseInt(document.getElementById("qty").innerText);
            if (param == "+1") {
                val = val + 1;
            } else if (param == "-1") {
                val = val - 1;
            } else if (param == "+5") {
                val = val + 5;
            } else if (param == "-5") {
                val = val - 5;
            }
            if (val <= 0) {
                document.getElementById("qty").innerText = 1;
            } else {
                document.getElementById("qty").innerText = val;
            }
         }
        function onModalReject() {
             var vPROCESS = document.getElementById("idpty").innerText;
             var vSONO  = document.getElementById("SONO").innerText;
             var vBUYER  = document.getElementById("BUYER").innerText;
             var vSTYLE  = document.getElementById("STYLE").innerText;
             var vCOLOR  = document.getElementById("COLOR").innerText;
             var vUID = document.getElementById("UID").innerText;
             var vLINE = document.getElementById("idLINE").innerText;
             if ( vPROCESS == '' || vSONO == '' || vBUYER == '' || vSTYLE == '' || vCOLOR == ''  || vUID == '' || vLINE == ''){
                 setTimeout(function(){ onPreviousPage(2); }, 2000);
                 ToastMessage("Timeout.Login again");
                 return;
             }
             var FITTY = document.getElementById("FITTY").innerText;
             var val = document.getElementById("size").innerText;
            if (FITTY == 'DEFECT') {
                    if (val != 'SIZE') {
                        document.getElementById('idSizeDiv').style.display = 'none';
                        document.getElementById('rejOpDev').style.display = 'block';
                        document.getElementById('rejListDev').style.display = 'block';
                        var rejid = $("button.defitem").length;
                        if (rejid >= 1) {
                            onSave();
                        } else {}
                    } else {
                        ToastMessage("Please select:'SIZE'");
                    }
                } else {
                    if (val != 'SIZE') {
                        onSave();
                    } else {
                        ToastMessage("Please select:'SIZE'");
                    }
             }
         }
        function onDefectCat(param) {
            var PRDTY = document.getElementById("idpty").innerText;
            var OPPID = 33;
            var CATID = param;
            $.ajax({
                type: 'POST',
                url: 'ajaxLineOut.php',
                data: {
                    OPPID: OPPID,
                    CATID: CATID,
                    PRDTY: PRDTY
                },
                success: function(html) {
                    $('#rejOpDiv').html(html);
                }
            });
         }
        function onBGColorChange() {
            var FITTY = document.getElementById("FITTY").innerText;
            var div = document.getElementById('idSapce');
            var btn = document.getElementById('btnSave');
            var btnA = document.getElementById('btnAddFiveA');
            var btnB = document.getElementById('btnAddFiveB');
            var btnC = document.getElementById('btnAddOneA');
            var btnD = document.getElementById('btnAddOneB');
            if (FITTY == 'FIT') {
                div.className = 'w3-green';
                btn.className = "w3-button w3-green";
            } else if (FITTY == 'DEFECT') {
                div.className = 'w3-yellow';
                btn.className = "w3-button w3-yellow";
            } else if (FITTY == 'REJECT') {
                div.className = 'w3-red';
                btn.className = "w3-button w3-red";
                btnA.className = "w3-disabled";
                btnA.onclick = null;
                btnB.className = "w3-disabled";
                btnB.onclick = null;
                btnC.className = "w3-disabled";
                btnC.onclick = null;
                btnD.className = "w3-disabled";
                btnD.onclick = null;
            }
         }

        function onPreviousPage(param){
            if (param == 1){
            window.location.href = 'lineOutType.php';
            } else if (param == 2){
            window.location.href = 'login.php';
            } 
         }

        function onLoadUrlVar(){
            // var url_string = window.location.href ;
            // var url = new URL(url_string);
            // var urlTY = url.searchParams.get("type");
            // var urlLN = url.searchParams.get("line");
            // var urlID = url.searchParams.get("emp");
            // var urlPT = url.searchParams.get("prdty");
            // var urlCC = url.searchParams.get("cc");
            // document.getElementById('TYPE').innerText = urlTY ;
            // document.getElementById('CC').innerText = urlCC ;
            // document.getElementById('idLINE').innerText = urlLN ;
            // document.getElementById('UID').innerText = urlID ;
         }

        
        function onMYBOX(){
            
            var VTYP = (document.getElementById('idpty').innerText).trim();
            var VSNO = document.getElementById('SONO').innerText;
            var VUID = document.getElementById('UID').innerText;
            var VBUDAT = document.getElementById("BUDAT").innerText;
            var VCOLOR = document.getElementById("COLOR").innerText;
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

        function onLoadRejectOld(){
            var opid = 2;
            $.ajax({
                type: 'POST',
                url: 'ajaxLineOut.php',
                data: { OPPID: opid
                    },
                success: function(html) {
                    $('#Table1Body').html(html);
                }
            });
         }
    
    
    </script> 
</head>
  
<body>

    <!-- mainBodyHeaderDiv -->
    <div class="w3-row w3-blue-grey">
        <div class="w3-col s2  w3-center w3-border-right">
            <!-- <p id="idpty"><?PHP echo $vPTYP ?></p> -->
            <p>
                <span id="idpty" style="display: none;"><?PHP echo $vPTYP ?></span>
                <span>
                     <?php if ( trim($vPTYP) == "FINISHING") { echo "PAD SEWING" ;} else { echo $vPTYP ; }  ?>
                </span>
            </p>
        </div>
        <div class="w3-col s2  w3-center w3-border-right">
            <p id="SONO"><?PHP echo $vSONO ?></p>
        </div>
        <div class="w3-col s2  w3-center w3-border-right">
            <p id="BUYER"><?PHP echo $vBUYER ?></p>
        </div>
        <div class="w3-col s3  w3-center w3-border-right">
            <p id="STYLE"><?PHP echo $vSTYLE ?></p>
        </div>
        <div class="w3-col s3  w3-center">
            <p id="COLOR"><?PHP echo $vCOLOR ?></p>
        </div>
     </div>
    <!-- END-mainBodyHeaderDiv -->
    
    <div style="height:10px" id="idSapce"></div> <!-- spacer -->
   
    <!-- bodyPart1Div -->
    <div class="w3-row w3-blue-grey" id="idDivBodyA">
         <div class="w3-col s2  w3-center w3-border-right w3-left-align w3-row-padding">
            <p id="CC"><?PHP echo $vCNTY ?></p>
         </div>
        <div class="w3-col s1  w3-center w3-border-right w3-left-align w3-row-padding">
            <p id="FITTY"><?PHP echo $vFIT ?></p>
            <!-- <p id="TYPE">-</p> -->
        </div>
        <div class="w3-col s1 w3-center w3-blue w3-border-right">
            <p id="size">SIZE</p>
        </div>
        <div class="w3-col s1  w3-center w3-border-right">
            <p id="OPID">-</p>
        </div>
        <div class="w3-col s7 w3-center w3-left-align">
            <div class="defcontain">
            </div>
        </div>
     </div>
    <!-- END-bodyPart1Div -->

    <!-- bodyPart2Div -->
    <div class="w3-row ">
        <!-- left -->
        <form id="form1" action="outputEntry.php" method="post">
            <div class="w3-col s4  w3-center w3-border-right ">
                <div class="w3-panel ">
                    <input id="btnAddFiveA" type="button" class="w3-button w3-red" value="-5"
                        style="width:45%;height:50px;" onclick="onAddQty(this.value)">
                    <input id="btnAddFiveB" type="button" class="w3-button w3-green" value="+5"
                        style="width:45%;height:50px;" onclick="onAddQty(this.value)">
                    <input id="btnAddOneA" type="button" class="w3-button w3-red" value="-1"
                        style="width:45%;height:50px;margin-top:15px;" onclick="onAddQty(this.value)">
                    <input id="btnAddOneB" type="button" class="w3-button w3-green" value="+1"
                        style="width:45%;height:50px;margin-top:15px;" onclick="onAddQty(this.value)">
                </div>
                <div class="w3-panel w3-xlarge">
                    <span id="qty"> 1 </span>
                </div>
                <div class="w3-panel ">
                    <input id="btnSave" type="button" class="w3-button w3-black" value="DONE"
                        style="width:90%;height:50px;" onclick="onModalReject()">
                </div>
            </div>
        </form>
        <!--END-left -->

        <!-- right -->
        <div class="w3-col s8  w3-center " >
            <!-- Size Dispaly  -->
            <div class="w3-panel" id="idSizeDiv">
            </div>
            <!-- End-Size Dispaly  -->
            <!-- Reject Operration w3-sidebar -->
            <div class="w3-sidebar w3-bar-block w3-blue-grey w3-card " style="width:11%;display:none;overflow: auto;"
                id="rejOpDev">
                <!-- <h5 class="w3-bar-item w3-text-yellow" style="text-decoration: underline;">OPERATION</h5>
                                    <h5 class="w3-bar-item w3-text-yellow" style="text-decoration: underline;">OPERATION</h5> -->
                <div class="w3-row">
                    <input class="w3-radio" type="radio" name="gender" value="male" onclick="onDefectCat(1)">
                    <label>A:O</label>
                    <input class="w3-radio" type="radio" name="gender" value="female" onclick="onDefectCat(2)">
                    <label>P:Z</label>
                </div>
                <br>
                <div id="rejOpDiv"></div>
                <br> <br> <br> <br> <br> <br> <br> <br> <br> <br>
            </div>
            <div class="w3-padding" id="rejListDev" style="margin-left:23%;display:none;overflow:auto">
                <div id="opid1" class="w3-container city" style="overflow:auto">
                </div>
            </div>
            <!-- End-Reject Operration  -->
        </div>
        <!--END-right -->
     </div>
    <!-- bodyPart2Div -->

    <!-- <div  style="height:10px"></div>  -->
    <!-- spacer -->
    
    <!--footer -->
    <div class="w3-row w3-blue-grey w3-bottom">
         <div onclick="onPreviousPage(1)" class="w3-col s2  w3-center w3-border-right w3-hover-dark-grey" style="cursor: pointer">
             <p>< BACK </p>
         </div>
         <div onclick="onMYBOX()" class="w3-col s2  w3-center w3-hover-dark-grey w3-border-right" style="cursor: pointer ;">
               <p>MY-BOX</p>
         </div>
         <div class="w3-col s2 w3-blue-grey w3-center w3-border-right">
            <p>LINENO:<span id="idLINE"><?PHP echo $vLINE ?></span></p>
        </div>
        <div class="w3-col s2  w3-center w3-border-right">
            <p>USERID:<span id="UID"><?PHP echo $vUID ?></span></p>
        </div>
        <div class="w3-col s2  w3-center w3-border-right">
            <p id="BUDAT"><?PHP echo $vDATE ?></p>
        </div>
        <div onclick="onPreviousPage(2)" class="w3-col s2  w3-center w3-hover-dark-grey" style="cursor: pointer;">
         <P>EXIT</P>   
        </div>
     </div>
    <!--footer -->

    <!--Modal Reject Code-->
    <div id="id01" class="w3-modal">

        <!--w3-Modal Div-->
        <div class="w3-modal-content w3-card-4">
            <div class="w3-row w3-container">
                <div class="w3-col s3 w3-white w3-center">
                    <input type="button" class="w3-button w3-light-green" value="CUTTING" style="width:100%">
                    <div style="height:10px"></div> <!-- spacer -->
                    <input type="button" class="w3-button w3-light-green" value="SEWING" style="width:100%">
                    <div style="height:10px"></div> <!-- spacer -->
                    <input type="button" class="w3-button w3-light-green" value="FNISHING" style="width:100%">
                </div>
                <div class="w3-col s1 w3-white w3-center ">
                </div>
                <div class="w3-col s8 w3-white w3-center ">
                    <div class="w3-container">
                        <header class="w3-container w3-teal">
                            <span onclick="document.getElementById('id01').style.display='none'"
                                class="w3-button w3-display-topright">&times;</span>
                            <h6>Defect Code List (DCL)</h6>
                        </header>
                        <table class="w3-table-all  w3-striped w3-hoverable w3-card-4" id="Table1">
                            <thead>
                                <tr class="w3-light-grey">
                                    <th>Select</th>
                                    <th>Reject Name</th>
                                </tr>
                            </thead> 
                            <tbody id="Table1Body">
                            </tbody>
                        </table>
                    </div>
                    <footer class="w3-container w3-teal">
                        <p>END</p>
                    </footer>
                </div>
            </div>
        </div>
        <!--End-w3-Modal Div-->

     </div>
    <!--END-Modal Reject Code-->

    <!--Modal myBOX-->
         <!--modal country -->
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
         <!--modal country -->
    <!--END-Modal myBOX-->

    <!--For toast message-->
    <div id="snackbar"></div>

</body>

</html>