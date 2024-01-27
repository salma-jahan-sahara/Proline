<?php session_start(); include 'config.php' ; $lineNo = $_SESSION['lineNo']; ?>
<!DOCTYPE html>
<html>
<head>
 <link rel="stylesheet" type="text/css" href="css/w3.css"></link>
 <script src="jquery.min.js"></script>
 <script language="javascript" type="text/javascript">
    var gvCurrDate;
    $(document).ready(function() {
        onInputFilter();
        onDateLoad();
        $("#cinnputtbl tbody").click(function(e) {
             onChanleClear();
            //for country selection
            var chkCountry = document.getElementById("idCCnt");
            if (chkCountry.checked) {
                var $tr = $(e.target).closest('tr'),
                    rowId = ($tr).attr("id"); // Here you can capture the row id of clicked cell.
                var Row = document.getElementById(rowId);
                var Cells = Row.getElementsByTagName("td");
                var TIDNO = Cells[0].innerText;
                var CNTCC = Cells[16].innerText;
                var opid = 41;
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxLineChange.php',
                        data: {
                            opid: opid
                        },
                        success: function(html) {
                            $('#sidTCC').html(html);
                        }
                    });

                document.getElementById("mdTIDCnt").value = TIDNO ;
                document.getElementById("mdIDFCC").value = CNTCC ;
                document.getElementById('id05').style.display = 'block';
                return;
             }
            var chkDate = document.getElementById("idCDate");
            if (chkDate.checked) {
                var $tr = $(e.target).closest('tr'),
                    rowId = ($tr).attr("id"); // Here you can capture the row id of clicked cell.
                var Row = document.getElementById(rowId);
                var Cells = Row.getElementsByTagName("td");
                var TIDNO = Cells[0].innerText;
                var opid = 39;
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxLineChange.php',
                        data: {
                            opid: opid,
                            TID: TIDNO
                        },
                        success: function(msg) {
                            var tidmsg = msg ; 
                            document.getElementById("mdIDate").value = tidmsg;
                        }
                    });
                document.getElementById("mdTID").value = TIDNO ;
                document.getElementById('id04').style.display = 'block';
                return;
             }

            var chkDocNo = document.getElementById("idCDoc");
            if (chkDocNo.checked) {
                onLineLoad();
                var $tr = $(e.target).closest('tr'),
                    rowId = ($tr).attr("id"); // Here you can capture the row id of clicked cell.
                var Row = document.getElementById(rowId);
                var Cells = Row.getElementsByTagName("td");
                var docNo = Cells[11].innerText;
                var docNoYear = Cells[12].innerText;
                document.getElementById("mdDocNo").value = docNo ;
                document.getElementById("mdDocNoYear").value = docNoYear ;
                document.getElementById('id03').style.display = 'block';
                return;
             }


            var chkDefChange = document.getElementById("idDefChange");
            if (chkDefChange.checked) {
                var $tr = $(e.target).closest('tr'),
                    rowId = ($tr).attr("id"); // Here you can capture the row id of clicked cell.
                var Row = document.getElementById(rowId);
                var Cells = Row.getElementsByTagName("td");
                var mtype = Cells[7].innerText;
                if (mtype == 'DEFECT' || mtype == 'DEF') {
                    var mtid = Cells[0].innerText;
                    var mtidRef = Cells[15].innerText;
                    //document.getElementById('idOUTID').innerHTML = `Defect count details:(id:${mtid})`;
                    document.getElementById('idOUTID').innerHTML = mtid ;
                    document.getElementById('idOUTIDRef').innerHTML = mtidRef ;
                    var opid = 35;
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxLineChange.php',
                        data: {
                            opid: opid,
                            outid: mtid
                        },
                        success: function(html) {
                            $('#cdefbody').html(html);
                        }
                    });
                    var opid = 36;
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxLineChange.php',
                        data: {
                            opid: opid,
                            outid: mtid
                        },
                        success: function(html) {
                            $('#idDefCount').html(html);
                        }
                    });
                    document.getElementById('id02').style.display = 'block';
                } else {
                    alert('This is not defect');
                }
            } else {
                var $tr = $(e.target).closest('tr'),
                    rowId = ($tr).attr("id"); // Here you can capture the row id of clicked cell.
                var Row = document.getElementById(rowId);
                var Cells = Row.getElementsByTagName("td");
                var mtid = Cells[0].innerText;
                var mso = Cells[1].innerText;
                var mbuyer = Cells[2].innerText;
                var mstyle = Cells[3].innerText;
                var mcolor = Cells[4].innerText;
                var mlineno = document.getElementById("lineno").innerText;
                var msize = Cells[5].innerText;
                var mqty = Cells[6].innerText;
                var mwip = Cells[14].innerText;
                var mnop = Cells[8].innerText;
                var mtyp = Cells[7].innerText;
                document.getElementById("mtid").innerText = mtid;
                document.getElementById("mso").innerText = mso;
                document.getElementById("mbuyer").innerText = mbuyer;
                document.getElementById("mstyle").innerText = mstyle;
                document.getElementById("mcolor").innerText = mcolor;
                document.getElementById("mmlineno").innerText = mlineno;
                document.getElementById("mmsize").innerText = msize;
                document.getElementById("mmmColor").innerText = mcolor;
                document.getElementById("mmmSO").innerText = mso;
                document.getElementById("mmqty").innerText = mqty;
                document.getElementById("mwip").innerText = mwip;
                document.getElementById("mnop").innerText = mnop;
                document.getElementById("mtyp").innerText = mtyp;

                onLineLoad();
                onChangeSZE(mso);
                onChangeCLR(mso);
                document.getElementById('id01').style.display = 'block';
            } // end else part

         });
     });


    function onDateLoad(){
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
                gvCurrDate = chunks[1] + '/' + chunks[0] + '/' + chunks[2] ;
                document.getElementById("idate").value = formattedDate;
                document.getElementById("mdCDate").value = formattedDate;
            }
         });
     }
    function onTransfer(){
        var TDOC = document.getElementById("mdDocNo").value ;
        var TDYR = document.getElementById("mdDocNoYear").value ;
        var TLNE = document.getElementById("mdlineno").value ;
        var TUID = document.getElementById("idUID").innerText;
        var opid = 38;
        $.ajax({
            type: 'POST',
            url: 'ajaxLineChange.php',
            data: {
                opid: opid,
                TDOC: TDOC,
                TDYR: TDYR,
                TLNE: TLNE,
                TUID: TUID
            },
            success: function(msg) {
                alert(msg);
            }
        });
     }
    function onLineLoad(){
        var opid = 31;
                $.ajax({
                    type: 'POST',
                    url: 'ajaxLineChange.php',
                    data: {
                        opid: opid
                    },
                    success: function(html) {
                        $('#mlineno').html(html);
                        $('#mdlineno').html(html);
                    }
                });
     }
    function onClickBtn(param) {
        if (param === 1) {
            document.getElementById("input1").style.backgroundColor = "red";
            document.getElementById("output").style.backgroundColor = "white";
            document.getElementById("fin").style.backgroundColor = "white";
            document.getElementById("mtype").innerText = "LIN";
            onGetLineIn(1);
        } else if (param === 2) {
            document.getElementById("output").style.backgroundColor = "red";
            document.getElementById("input1").style.backgroundColor = "white";
            document.getElementById("fin").style.backgroundColor = "white";
            document.getElementById("mtype").innerText = "LOUT";
            onGetLineIn(2);
        }
        else if (param === 3) {
            document.getElementById("fin").style.backgroundColor = "red";
            document.getElementById("input1").style.backgroundColor = "white";
            document.getElementById("output").style.backgroundColor = "white";
            document.getElementById("mtype").innerText = "FIN";
            onGetLineIn(3);
        }
     }

    function onGetLineIn(param) {
        var opid = param;
        var idate = document.getElementById("idate").value;
        var docNum = document.getElementById("myInputChange").value;
        var chistory = document.getElementById("idCHistory");
        var CHIS = 0;
        if (chistory.checked) { CHIS = 1 ;}
        $.ajax({
            type: 'POST',
            url: 'ajaxLineChange.php',
            data: {
                opid: opid,
                idate: idate,
                DOCNUM: docNum,
                CHIS: CHIS
            },
            success: function(html) {
                $('#cinput').html(html);
            }
        });

     }

    function onChangeAll(param) {
        if ((document.getElementById("mcall").checked) == true) {
            document.getElementById("mcqty").checked = true;
            document.getElementById("mcsize").checked = true;
            document.getElementById("mclineno").checked = true;
            document.getElementById("mmcColor").checked = true;
            document.getElementById("mmcSO").checked = true;
        } else {
            if ((document.getElementById("mcall").checked) == false) {
                document.getElementById("mcqty").checked = false;
                document.getElementById("mcsize").checked = false;
                document.getElementById("mclineno").checked = false;
                document.getElementById("mmcColor").checked = false;
                document.getElementById("mmcSO").checked = false;
            }
        }
     }

    function onUpdatee() {
        //original document.
        var OQTY = document.getElementById("mmqty").innerText;
        var OSZE = document.getElementById("mmsize").innerText;
        var OCLR = document.getElementById("mmmColor").innerText;
        var OSTY = document.getElementById("mstyle").innerText;
        var OBUY = document.getElementById("mbuyer").innerText;
        var OSNO = document.getElementById("mmmSO").innerText;
        var OLIN = document.getElementById("mmlineno").innerText;
        var OUID = document.getElementById("idUID").innerText;
        var ONOP = document.getElementById("mnop").innerText;
        var OTYP = document.getElementById("mtyp").innerText;
        //update document.
        var mlineno = document.getElementById("mlineno").value;
        var msize = document.getElementById("msize").value;
        var mqty = document.getElementById("mqty").value;
        var mtid = document.getElementById("mtid").innerText;
        var mmColor = document.getElementById("mmColor").value;
        var mmSO = document.getElementById("mmSO").value;
        var TOBY = document.getElementById("mmSO1").value;
        var TOSY = document.getElementById("mmSO2").value;
        var TONP = document.getElementById("mmSO3").value;
        //type like input or output and wip
        var MVTYPE = document.getElementById("mtype").innerText ;
        var MVWIP = parseInt(document.getElementById("mwip").innerText) ;

        //wip check for sewing input change
        if ( MVTYPE == 'LIN' &&   mqty >= (MVWIP * ONOP) ){
            alert('No stock');
            return;
         }
        // all change
        if ((document.getElementById("mcall").checked) == true) {

            if ( mlineno.length == 0 ) {
                 alert("ERROR.Line is empty!");
                 return;
               }
            if ( mmSO.length == 0 ) {
                 alert("ERROR.SO is empty!");
                 return;
               }
            if ( TOBY.length == 0 ) {
                 alert("ERROR.Buyer is empty!");
                 return;
               }
            if ( TOSY.length == 0 ) {
                 alert("ERROR.Style is empty!");
                 return;
               }
            if ( TONP.length == 0 ) {
                 alert("ERROR.NOP is empty!");
                 return;
               }
            if ( mmColor.length == 0 ) {
                 alert("ERROR.Color is empty!");
                 return;
               }
            if ( msize.length == 0 ) {
                 alert("ERROR.Size is empty!");
                 return;
               }
            if ( mqty.length == 0 ) {
                 alert("ERROR.Quantity is empty!");
                 return;
               }
              var opid = 21;
                $.ajax({
                    type: 'POST',
                    url: 'ajaxLineChange.php',
                    data: {
                        opid: opid,
                        mtid: mtid,
                        mlineno: mlineno,
                        mmSO: mmSO,
                        TOBY: TOBY,
                        TOSY: TOSY,
                        TONP: TONP,
                        mmColor: mmColor,
                        msize: msize,
                        mqty: mqty,
                        OQTY: OQTY,
                        OSZE: OSZE,
                        OCLR: OCLR,
                        OSTY: OSTY,
                        OBUY: OBUY,
                        OSNO: OSNO,
                        OLIN: OLIN,
                        OUID: OUID,
                        ONOP: ONOP,
                        OTYP: OTYP
                    },
                    success: function(html) {
                        $('#msg').html(html);
                    }
             });
            return;
         }
        //line change
        if ((document.getElementById("mclineno").checked) == true) {
             if ( mlineno.length == 0) {
                  var msg = 'Error.Line no is empty.';
                  ToastMessage(msg) 
                  return;
                 }

            var opid = 24;
            $.ajax({
                type: 'POST',
                url: 'ajaxLineChange.php',
                data: {
                    opid: opid,
                    mtid: mtid,
                    mlineno: mlineno ,
                    OQTY: OQTY,
                    OSZE: OSZE,
                    OCLR: OCLR,
                    OSTY: OSTY,
                    OBUY: OBUY,
                    OSNO: OSNO,
                    OLIN: OLIN,
                    OUID: OUID,
                    ONOP: ONOP,
                    OTYP: OTYP
                },
                success: function(html) {
                    $('#msg').html(html);
                }
            });
         }
        // so change
        if ((document.getElementById("mmcSO").checked) == true) {
            if ( mmSO.length == 0 ) {
                 alert("ERROR.SO is empty!");
                 return;
               }
            if ( TOBY.length == 0 ) {
                 alert("ERROR.Buyer is empty!");
                 return;
               }
            if ( TOSY.length == 0 ) {
                 alert("ERROR.Style is empty!");
                 return;
               }
            if ( TONP.length == 0 ) {
                 alert("ERROR.NOP is empty!");
                 return;
               }
            if ( mmColor.length == 0 ) {
                 alert("ERROR.Color is empty!");
                 return;
               }
            if ( msize.length == 0 ) {
                 alert("ERROR.Size is empty!");
                 return;
               }
            

            var opid = 26;
            $.ajax({
                type: 'POST',
                url: 'ajaxLineChange.php',
                data: {
                    opid: opid,
                    mtid: mtid,
                    mmSO: mmSO,
                    TOBY: TOBY,
                    TOSY: TOSY,
                    TONP: TONP,
                    mmColor: mmColor,
                    msize: msize,
                    OQTY: OQTY,
                    OSZE: OSZE,
                    OCLR: OCLR,
                    OSTY: OSTY,
                    OBUY: OBUY,
                    OSNO: OSNO,
                    OLIN: OLIN,
                    OUID: OUID,
                    ONOP: ONOP,
                    OTYP: OTYP
                },
                success: function(html) {
                    $('#msg').html(html);
                }
            });
         }
        // color change
        if ((document.getElementById("mmcColor").checked) == true) {
            if ( mmColor.length == 0 ) {
                 alert("ERROR.Color is empty!");
                 return;
               }
            var opid = 25;
            $.ajax({
                type: 'POST',
                url: 'ajaxLineChange.php',
                data: {
                    opid: opid,
                    mtid: mtid,
                    mmColor: mmColor,
                    OQTY: OQTY,
                    OSZE: OSZE,
                    OCLR: OCLR,
                    OSTY: OSTY,
                    OBUY: OBUY,
                    OSNO: OSNO,
                    OLIN: OLIN,
                    OUID: OUID,
                    ONOP: ONOP,
                    OTYP: OTYP
                },
                success: function(html) {
                    $('#msg').html(html);
                }
            });
         }
        //size change
        if ((document.getElementById("mcsize").checked) == true) {
            if ( msize.length == 0 ) {
                 alert("ERROR.Size is empty!");
                 return;
               }
            var opid = 23;
            $.ajax({
                type: 'POST',
                url: 'ajaxLineChange.php',
                data: {
                    opid: opid,
                    mtid: mtid,
                    msize: msize,
                    OQTY: OQTY,
                    OSZE: OSZE,
                    OCLR: OCLR,
                    OSTY: OSTY,
                    OBUY: OBUY,
                    OSNO: OSNO,
                    OLIN: OLIN,
                    OUID: OUID,
                    ONOP: ONOP,
                    OTYP: OTYP

                },
                success: function(html) {
                    $('#msg').html(html);
                }
            });
         }
        // qty change
        if ((document.getElementById("mcqty").checked) == true) {
            if (mqty.length == 0 ) {
                 alert("ERROR.Quantity is empty!");
                 return;
               }
            var opid = 22;
            $.ajax({
                type: 'POST',
                url: 'ajaxLineChange.php',
                data: {
                    opid: opid,
                    mtid: mtid,
                    mqty: mqty,
                    OQTY: OQTY,
                    OSZE: OSZE,
                    OCLR: OCLR,
                    OSTY: OSTY,
                    OBUY: OBUY,
                    OSNO: OSNO,
                    OLIN: OLIN,
                    OUID: OUID,
                    ONOP: ONOP,
                    OTYP: OTYP
                },
                success: function(html) {
                    $('#msg').html(html);
                }
            });
         }







     }

    function onClear() {
        if (document.getElementById("mtype").innerText == 'LIN') {
            onGetLineIn(1);
        }
        if (document.getElementById("mtype").innerText == 'LOUT') {
            onGetLineIn(2);
        }
        $('#msg > p').html("");
        document.getElementById("mdTIDCnt").value = '';
        document.getElementById("mdIDFCC").value = '';
        document.getElementById("mlineno").value = '';
        document.getElementById("msize").value = '';
        document.getElementById("mqty").value = '';
        document.getElementById("mmSO").value = '';
        document.getElementById("mcqty").checked = false;
        document.getElementById("mcsize").checked = false;
        document.getElementById("mclineno").checked = false;
        document.getElementById("mcall").checked = false;
        document.getElementById("mmcColor").checked = false;
        document.getElementById("mmcSO").checked = false;
        document.getElementById('id01').style.display = 'none';
        document.getElementById('id02').style.display = 'none';
        document.getElementById('id03').style.display = 'none';
        document.getElementById('id04').style.display = 'none';
        document.getElementById('id05').style.display = 'none';
        onChanleClear();
     }

    function onInputFilter() {
        $("#myInputChange").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#cinput tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        })
     };

    function onChangeChkSO(){
        var PSO = document.getElementById("mmSO").value ;
        if ( PSO.length == 0){
            document.getElementById("mmSO1").value = '';
            document.getElementById("mmSO2").value = '';
            document.getElementById("mmSO3").value = '';
            return;
        }
        var opid = 37;
            $.ajax({
                type: 'POST',
                url: 'ajaxLineChange.php',
                data: {
                    opid: opid,
                    PSO: PSO
                },
                success: function(html) {
                     var str = html;
                     var res = str.split('|');
                     document.getElementById("mmSO1").value = res[0];
                     document.getElementById("mmSO2").value = res[1];
                     document.getElementById("mmSO3").value = res[2];
                     onChangeCLR(PSO);
                     onChangeSZE(PSO);
                }
            });


     }

    function onChangeCLR(sonum){
        var mso = sonum;
        var opid = 34;
                $.ajax({
                    type: 'POST',
                    url: 'ajaxLineChange.php',
                    data: {
                        opid: opid,
                        sono: mso
                    },
                    success: function(html) {
                        $('#mmColor').html(html);
                    }
                });

     }

    function onChangeSZE(sonum){
        var mso = sonum;
        var opid = 32;
                $.ajax({
                    type: 'POST',
                    url: 'ajaxLineChange.php',
                    data: {
                        opid: opid,
                        sono: mso
                    },
                    success: function(html) {
                        $('#msize').html(html);
                    }
                });

     }

    function onChanleClear(){
         document.getElementById("msg").innerText = '';
         document.getElementById("mlineno").value = '';
         document.getElementById("mmSO").value = '';
         document.getElementById("mmSO1").value = '';
         document.getElementById("mmSO2").value = '';
         document.getElementById("mmSO3").value = '';
         document.getElementById("mmColor").value = '';
         document.getElementById("msize").value = '';
         document.getElementById("mqty").value = '';
         document.getElementById("mtyp").innerText = '';
     }
    
    function onTransferDate(){
         // selecte date condition not more than 2 days and not in future.
         var selectionDate = document.getElementById("mdCDate").value ;
         var chunks = selectionDate.split('-');
         selectionDate =  chunks[1] + '/' + chunks[2] + '/' + chunks[0] ;
         gvCurrDate = new Date(gvCurrDate);  
         selectionDate = new Date(selectionDate);  
         var diff = gvCurrDate.getTime() - selectionDate.getTime(); 
         diff =  Math.ceil(diff / 86400000) ;
         if (diff > 15 ) {
             alert("Selected Date is over than 2 days time period.");
             return;
         } else if ( diff < 0 ) {
             alert("Selected Date is in future.");
             return;
         }
         
         //ajax parameter for date change
         var selectionDate1 = document.getElementById("mdCDate").value ;
         var chunks1 = selectionDate1.split('-');
         selectionDate1 =  chunks1[2] + '-' + chunks1[1] + '-' + chunks1[0] ;
         var TID = document.getElementById("mdTID").value ;
         var IDT = document.getElementById("mdIDate").value ;
         var CDT = selectionDate1 ;
         var USR = document.getElementById("idUID").innerText;
         var opid = 40;
            $.ajax({
                type: 'POST',
                url: 'ajaxLineChange.php',
                data: {
                    opid: opid,
                    TID: TID,
                    IDT: IDT,
                    CDT: CDT,
                    USR: USR
                 },
                success: function(msg) {
                       alert(msg);
                 }
             });
     }
    function onChangeCountry(){
             var MDTID = document.getElementById("mdTIDCnt").value;
             var MDFCC = document.getElementById("mdIDFCC").value;
             var MDTCC = document.getElementById("sidTCC").value;
             var opid = 42;
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxLineChange.php',
                        data: {
                            opid: opid,
                            MDTID: MDTID,
                            MDFCC: MDFCC,
                            MDTCC: MDTCC
                        },
                        success: function(msg) {
                             alert(msg);
                        }
                    });

     }
    function onMoveDefect(){

             var movef = document.getElementById('idOUTIDRef').innerHTML ; 
             movef = movef.trim();
             var movet = document.getElementById('idOUTID').innerHTML ; 
             movet = movet.trim();
             
             if (parseInt(movef) == 0 ){
                 alert("No reference id");
                 return;
             }

             var opid = 43;
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxLineChange.php',
                        data: {
                            opid: opid,
                            movef: movef,
                            movet: movet 
                        },
                        success: function(msg) {
                             alert(msg);
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
            <p> Change </p>
        </div>
        <div id="lineno" class="w3-col s10 w3-blue-grey w3-center">
            <p> <?php echo $lineNo ; ?> </p>
        </div>
    </div>
    <!-- header -->
    <br><br> <br><!-- double br for stop div overlapping -->

    <!-- Container Input div for dataGrid -->
    <div class="w3-row-padding" id="inpDiv">
        <div class="w3-quarter">
            <input id="idate" class="w3-input w3-border" type="date" placeholder="One" value="">
        </div>
        <div class="w3-quarter">
            <input id="input1" class="w3-input w3-border" type="submit" placeholder="Two" name="cinput"
                value="Change Input" onclick="onClickBtn(1)" style="background-color: white;">
        </div>
        <div class="w3-quarter">
            <input id="output" class="w3-input w3-border " type="button" placeholder="Three" name="coutput"
                value="Change Output" onclick="onClickBtn(2)" style="background-color: white;">
        </div>
        <div class="w3-quarter">
            <input id="fin" class="w3-input w3-border " type="button" placeholder="Three" name="cfin"
                value="Change Finishing" onclick="onClickBtn(3)" style="background-color: white;">
        </div>
    </div>
    <!--End-Container input div for dataGrid --> 
    <br>

    <!-- Container div dataGrid for Change Input -->
    <div class="w3-row-padding" style="overflow-y:scroll;">
        <div class="w3-row">
            <input id="myInputChange" type="text" placeholder="Search..">
            <input class="w3-check" type="checkbox" id="idDefChange">
            <label class="w3-text-red" style="font-weight:bold">Defect Change</label>
            <input class="w3-check" type="checkbox" id="idCHistory">
            <label class="w3-text-red" style="font-weight:bold">Change History</label>
            <input class="w3-check" type="checkbox" id="idCDoc">
            <label class="w3-text-red" style="font-weight:bold">Transfer DocumentNo</label>
            <input class="w3-check" type="checkbox" id="idCDate">
            <label class="w3-text-red" style="font-weight:bold">Date Change</label>
            <input class="w3-check" type="checkbox" id="idCCnt">
            <label class="w3-text-red" style="font-weight:bold">Country Change</label>
        </div>
        <!-- dataGrid -->
        <table class="w3-table-all  w3-striped w3-hoverable" id="cinnputtbl">
            <thead>
                <tr class="w3-light-grey">
                    <th>TID</th>
                    <th>SO</th>
                    <th>Buyer</th>
                    <th>Style</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Qty(PC)</th>
                    <th>Type</th>
                    <th>NOP</th>
                    <th>Qty(Part)</th>
                    <th>Entry time</th>
                    <th>Document No</th>
                    <th>Year</th>
                    <th>Item No</th>
                    <th>WIP(pc)</th>
                    <th>Change-REFID</th>
                    <th>Country Code</th>
                </tr>
            </thead>
            <tbody id="cinput">
            </tbody>
        </table>
        <!--End-dataGrid -->
    </div>
    <!--End-Container div dataGrid for Change Input  -->
    <br> <br><br>

    <!--footer -->
    <div class="w3-row w3-container w3-bottom w3-blue-grey">
        <a href="initialPage.php">
            <div class="w3-col s3 w3-blue-grey w3-center w3-border-right w3-hover-dark-grey" style="cursor: pointer">
                <p> < BACK </p>
            </div>
        </a>
        <div class="w3-col s6 w3-blue-grey w3-center w3-border-right">
            <p id="idUID"> <?php echo $_SESSION['username'] ; ?> </p>
        </div>
        <div class="w3-col s3 w3-blue-grey w3-center ">
            <p> <?Php echo date('d-m-Y'); ?> </p>
        </div>
    </div>
    <!--End-footer -->

    <!--double click modal -->
    <div id="id01" class="w3-modal">
        <div class="w3-modal-content">
            <header class="w3-container w3-teal">
                <div class="w3-container">
                    <div class="w3-col s2 ">
                        <p id="mso"></p>
                    </div>
                    <div class="w3-col s4 ">
                        <p id="mbuyer"></p>
                    </div>
                    <div class="w3-col s3 ">
                        <p id="mstyle"></p>
                    </div>
                    <div class="w3-col s2 ">
                        <p id="mcolor"></p>
                    </div>
                    <div class="w3-col s1 ">
                        <span onclick="onClear()" class="w3-button w3-display-topright">&times;</span>
                    </div>
                </div>
            </header>
            <div class="w3-container w3-text-green">
                <p id="msg"></p>
                <table class="w3-table-all  w3-striped w3-hoverable">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Original</th>
                            <th>Change</th>
                            <th><input id="mcall" class="w3-check" type="checkbox" onclick="onChangeAll(this.value)">
                            </th>
                        <tr>
                    </thead>
                    <tbody>

                         <tr>
                            <td> Line No </td>
                            <td id="mmlineno"></td>
                            <td><select id="mlineno" class="w3-select" name="option"></select></td>
                            <td><input id="mclineno" class="w3-check" type="checkbox"></td>
                         </tr>

                         <tr>
                            <td> SO </td>
                            <td id="mmmSO"></td>
                            <td><span><input id="mmSO" class="w3-input w3-border" type="text" onclick="onChangeChkSO()"></span>
                                <span><label for="mmSO1">Buyer</label><input id="mmSO1" value = '' class="w3-input w3-border" type="text" style="width: 30%;" disabled></span>
                                <span><label for="mmSO2">Style</label><input id="mmSO2" value = '' class="w3-input w3-border" type="text" disabled></span>
                                <span><label for="mmSO3">NOP</label><input id="mmSO3" value = '' class="w3-input w3-border" type="text"></span>
                            </td>
                            <td><span><input id="mmcSO" class="w3-check" type="checkbox"></span>
                            </td>
                         </tr>

                         <tr>
                            <td> Color </td>
                            <td id="mmmColor"></td>
                            <td><select id="mmColor" class="w3-select" name="option"></select></td>
                            <td><input id="mmcColor" class="w3-check" type="checkbox"></td>
                         </tr>

                         <tr>
                            <td> Size </td>
                            <td id="mmsize"></td>
                            <td><select id="msize" class="w3-select" name="option"> </select></td>
                            <td><input id="mcsize" class="w3-check" type="checkbox"></td>
                         </tr>

                         <tr>
                            <td> Qty </td>
                            <td id="mmqty"></td>
                            <td><input id="mqty" class="w3-input w3-border"></td>
                            <td><input id="mcqty" class="w3-check" type="checkbox"></td>
                         </tr>

                    </tbody>
                 </table>
            </div>
            <br>
            <footer class="w3-container w3-teal">
                <div class="w3-container w3-text-green">
                    <div class="w3-col s7 w3-padding-small">
                        <input type="button" class="w3-button w3-green" value="UPDATE" onclick="onUpdatee()">
                        <input type="button" class="w3-button w3-red" value="DELETE" onclick="onDelete()">
                    </div>
                    <div class="w3-col s1 w3-padding-small">
                        <span id="mtid"></span>
                    </div>
                    <div class="w3-col s1 w3-padding-small">
                        <span id="mtype"></span>
                    </div>
                    <div class="w3-col s1 w3-padding-small">
                        <span id="mwip"></span>
                    </div>
                    <div class="w3-col s1 w3-padding-small">
                        <span id="mnop"></span>
                    </div>
                    <div class="w3-col s1 w3-padding-small">
                        <span id="mtyp"></span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!--End-double click modal -->

    <!--double click modal -->
    <div id="id02" class="w3-modal">
         <div class="w3-modal-content">
             <header class="w3-container">
             <div class="w3-container">
                    <div class="w3-col s11 ">
                        <p></p>
                        <p>
                             <span style="font-weight: bold;">Defect count details</span>
                             <span id="idOUTID"></span>
                             <span id="idOUTIDRef"></span>

                        </p>
                    </div>
                    <div class="w3-col s1 ">
                        <span onclick="onClear()" class="w3-button w3-display-topright w3-red">&times;</span>
                    </div>
                </div>
             </header>
            <!-- dataGrid -->
            <table class="w3-table-all  w3-striped w3-hoverable" id="cdeftbl">
                <thead >
                    <tr class="w3-teal">
                        <th style="display: none;">TID</th>
                        <th style="display: none;">ID</th>
                        <th>NAME</th>
                        <th>DEFECT COUNT</th>
                        <th style="display: none;">OUTID</th>
                    </tr>
                </thead>
                <tbody id="cdefbody" class="w3-light-grey">
                </tbody>
                <tfoot>
                <tr class="w3-teal">
                        <td style="display: none;">TID</td>
                        <td style="display: none;">ID</td>
                        <td>TOTAL</td>
                        <td id="idDefCount"></td>
                        <td style="display: none;">OUTID</td>
                </tr>
                <tr class="w3-teal">
                        <td><input type="button" class="w3-button w3-green" value="MOVE DEFECTS" onclick="onMoveDefect()"></td>
                        <td></td>
                </tr>
	            </tfoot>
            </table>
            <!--End-dataGrid -->
         </div>
    </div>
    <!--End-double click modal -->

    <!--double click modal -->
    <div id="id03" class="w3-modal">
         <div class="w3-modal-content">
             <div class="w3-container">
                    <div class="w3-col s11 ">
                        <p>DocumentNo transfer to new line.</p>
                    </div>
                    <div class="w3-col s1 ">
                        <span onclick="onClear()" class="w3-button w3-display-topright w3-red">&times;</span>
                    </div>
             </div>
             <div class="w3-container w3-teal">
                 <br>
                 <input id="mdDocNo" class="w3-input" type="text" disabled> 
                 <input id="mdDocNoYear" class="w3-input" type="text" disabled>
                 <select id="mdlineno" class="w3-select" name="option"></select><br><br>
                 <input type="button" class="w3-button w3-green" value="Transfer" onclick="onTransfer()"><br><br>
             </div>
         </div>
    </div>

    <!--double click modal -->
    <div id="id04" class="w3-modal">
         <div class="w3-modal-content">
             <div class="w3-container">
                    <div class="w3-col s11 ">
                        <p>DateChange.</p>
                    </div>
                    <div class="w3-col s1 ">
                        <span onclick="onClear()" class="w3-button w3-display-topright w3-red">&times;</span>
                    </div>
             </div>
             <div class="w3-container w3-teal">
                 <br>
                 <input id="mdTID" class="w3-input" type="text" disabled> 
                 <input id="mdIDate" class="w3-input" type="text" disabled>
                 <input id="mdCDate" class="w3-input w3-border" type="date" placeholder="One" value=""><br><br>
                 <input type="button" class="w3-button w3-green" value="Transfer" onclick="onTransferDate()"><br><br>
             </div>
         </div>
    </div>

    <!--double click modal for country change-->
    <div id="id05" class="w3-modal">
         <div class="w3-modal-content">
             <div class="w3-container">
                    <div class="w3-col s11 ">
                        <p>Country Change.</p>
                    </div>
                    <div class="w3-col s1 ">
                        <span onclick="onClear()" class="w3-button w3-display-topright w3-red">&times;</span>
                    </div>
             </div>
             <div class="w3-container w3-teal">
                 <br>
                 <input id="mdTIDCnt" class="w3-input" type="text" disabled> 
                 <input id="mdIDFCC" class="w3-input" type="text" disabled>
                 <select name="option" id="sidTCC" class="w3-select">
                 </select>
                 <input type="button" class="w3-button w3-green" value="Country Transfer" onclick="onChangeCountry()"><br><br>
             </div>
         </div>
    </div>

</body>
<!--End-main body -->

</html>