<!DOCTYPE html>
<html>
<head>
<title>Purbani:Login</title>
   <meta name="viewport" content="width=device-width,height=device-height, initial-scale=1.0">
   <link rel="stylesheet" type="text/css" href="css/w3.css"></link>
   <link rel="stylesheet" href="css/font-awesome.min.css">
   <link rel="stylesheet" type="text/css" href="css/loginPRO.css"></link>
   <script type="text/javascript" src="jquery.min.js"></script>
   <script type="text/javascript">
        
        function saveCookie(){
           var txt1 = $('#uname').val();
           var txt2 = $('#upass').val();
           document.cookie = "Name=" + txt1;
           document.cookie = "Pass=" + txt2;
         }
        function loadCookie(){ 
             var txtt1 ='';
             var txtt2 ='';
             var allcookies = document.cookie;
             var cookiearray = [];
             cookiearray = allcookies.split(';');
             if (cookiearray.length > 0){
                for(var i=0; i<cookiearray.length; i++) {
                    keyNam1 = cookiearray[i].split('=')[0];
                    keyName = keyNam1.replace(/\s+/g, ' ').trim();
                    keyValue = cookiearray[i].split('=')[1];
                    switch(keyName) {
                        case "Name":
                          txtt1 = keyValue ;
                          break;zz
                        case "Pass":
                          txtt2 = keyValue ;
                          break;
                        default:
                    }
                }
             }
             document.getElementById('uname').value = txtt1 ;
             document.getElementById('upass').value = txtt2 ;
         }
        function login(param){
           saveCookie();
           var txt1 = $('#uname').val();
           var txt2 = $('#upass').val();
            $.ajax({
                  type: 'POST',
                  url: 'loginPOST.php',
                  data: {
                      username: txt1,
                      password: txt2,
                      login_user: '1'
                  },
                  success: function(msg) {
                      var msgNew1 = msg.replace(/\s+/g, ' ').trim();
                      var msgNew2 = 'OK';
                      var n = msgNew1.localeCompare(msgNew2);
                      if ( n == 0) {
                         if ( param == 1) {
                             window.location.href = 'initialPage.php';
                         } else if ( param == 2 ) {
                             window.location.href = 'http://proline.purbani.com/zPGReportAllWeb/webapp/index.html';
                         }
                      } else {
                         alert ('username/password not correct');
                      }
                  }
              })
         }
   </script>
</head>

<body>

    <div class="bottom-containerHEAD">
      <div class="row">
        <div class="col">
            <p></p>
        </div>
        <div class="col">
            <p></p>
        </div>
      </div>
    </div>

    <div class="container">
      <form method="post">
        <div class="row">
          <h2 style="text-align:center"></h2>
           <!-- <div class="vl">
            <span class="vl-innertext">>></span>
           </div> -->
            <div class="col">
                <img src="image/proline.png" alt="Flowers in Chania"> 
            </div>
          <div class="col">
             <div class="hide-md-lg">
                <p></p>
             </div>
            <input type="text" name="username" id="uname" placeholder="Username" required>
            <input type="password" name="password" id="upass" placeholder="Password" required>
            <input type="button" value="Login For Production Apps" name="login_user" id="btnULOG" onclick="login(1)" style="background-color: #4CAF50;color: white;cursor: pointer;">
            <input type="button" value="Login For Graphically Apps" name="login_user" id="btnULOG1" onclick="login(2)" class="w3-blue-grey" style="cursor: pointer;">
          </div>
        </div>
      </form>
    </div>
    
    <div class="bottom-containerFOOT">
      <div class="row">
        <div class="col">
           <p></p>
        </div>
        <div class="col">
           <p></p>
        </div>
      </div>
    </div>


</body>

</html>
