<?php session_start();
include 'config.php'; ?>
<?php
//$query = "SELECT * FROM `zpp_machine_mast` ";
$query = '';
$uname =  $_SESSION['username'];
//check data for insert.
$sqll = "SELECT * FROM `userline` WHERE USERNAME = '$uname' ORDER BY MNO ";
if ($resultt =  mysqli_query($db, $sqll)) {
  $rowCount = mysqli_num_rows($resultt);
  if ($rowCount >= 1) {
    $query = "SELECT USERNAME,MNO FROM `userline` WHERE USERNAME = '$uname' ORDER BY MNO";
  } else if ($rowCount == 0) {
    $query = "SELECT * FROM `zpp_machine_mast` ";
  }
}
mysqli_free_result($resultt);
// for method 1
$result = mysqli_query($db, $query);
$options = "";
while ($row2 = mysqli_fetch_array($result)) {
  $options = $options . "<option>$row2[1]</option>";
}


$date = date('d-m-Y');
if (isset($_POST['sew'])) {
  $_SESSION['USERIDNEW'] = $uname;
  $_SESSION['lineNo'] = $_POST['option'];
  $_SESSION['prdty'] = 'SEWING';
  header('location:lineIn.php');
}
if (isset($_POST['fin'])) {
  $_SESSION['USERIDNEW'] = $uname;
  $_SESSION['lineNo'] = $_POST['option'];
  $_SESSION['prdty'] = 'FINISHING';
  header('location:lineIn.php');
}
if (isset($_POST['pak'])) {
  $_SESSION['USERIDNEW'] = $uname;
  $_SESSION['lineNo'] = $_POST['sFLOOR'];
  $_SESSION['prdty'] = 'PACKING';
  header('location:lineIn.php');
}

if (isset($_POST['change'])) {
  $_SESSION['lineNo'] = $_POST['option'];
  header('location:lineChange.php');
}
if (isset($_POST['dashboard'])) {
  $_SESSION['lineNo'] = $_POST['option'];
  header('location:viewKpi.php');
}
if (isset($_POST['details'])) {
  $_SESSION['lineNo'] = $_POST['option'];
  header('location:viewMaster.php');
}
if (isset($_POST['setting'])) {
  $_SESSION['lineNo'] = $_POST['option'];
  header('location:viewSetting.php');
}
if (isset($_POST['export'])) {
  $_SESSION['lineNo'] = $_POST['option'];
  header('location:exportData.php');
}
if (isset($_POST['qtycheck'])) {
  $_SESSION['lineNo'] = $_POST['option'];
  header('location:viewQtyCheck.php');
}
if (isset($_POST['adjust'])) {
  $_SESSION['USERIDNEW'] = $uname;
  $_SESSION['lineNo'] = $_POST['option'];
  header('location:lineAdjust.php');
}
if (isset($_POST['mis'])) {
  $_SESSION['USERIDNEW'] = $uname;
  $_SESSION['lineNo'] = $_POST['option'];
  header('location:viewPRDMIS.php');
}

// Close connection
mysqli_close($db);
if (isset($_GET['LOGOUT'])) {
  session_destroy();
  unset($_SESSION['username']);
  header("location: login.php");
}

?>
<!DOCTYPE html>
<html>

<head>
  <title> SELECT LINE NO </title>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="css/w3.css">
  </link>
  <link rel="stylesheet" type="text/css" href="css/mycss.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  </link>
  <style>
    body{
      font-family: Verdana,  sans-serif;
      font-size: 15px;
    }
  </style>
  <script src="jquery.min.js"></script>
  <script type="text/javascript" src="jquery.cookie.min.js"></script>
  <script language="javascript" type="text/javascript">
    $(function() {
      var urTYP = document.getElementById('idUSR').innerText;
      var idBTF = document.getElementById('idBTF');
      var idBTS = document.getElementById('idBTS');
      var idBTP = document.getElementById('idBTP');
      var idChange = document.getElementById('idChange');
      var idAdjust = document.getElementById('idAdjust');
      var idFLR = document.getElementById('sidFLOOR');
      var idLNE = document.getElementById('sidLINE');
      if (urTYP >= 1 && urTYP <= 41) {
        $("#idBTF").hide();
        $("#idBTP").hide();
        $("#idChange").hide();
        $("#idAdjust").hide();
        idFLR.style.display = "none";
      } else if (urTYP >= 101 && urTYP <= 999) {
        $("#idBTS").hide();
        $("#idBTP").hide();
        $("#idChange").hide();
        $("#idAdjust").hide();
        idFLR.style.display = "none";
      } else if (urTYP >= 1001) {
        $("#idBTS").hide();
        $("#idBTF").hide();
        $("#idChange").hide();
        $("#idAdjust").hide();
        idLNE.style.display = "none";
      } else if (urTYP == "ppq29" || urTYP == "PPQ29" || urTYP == "Ppq29") {
        $("#idBTF").hide();
        $("#idBTS").hide();
        $("#idBTP").hide();
        $("#idChange").hide();
        $("#idAdjust").hide();
        idFLR.style.display = "none";
      } else {
        //todo;
      }
    });
    function showPasswordModal() {
        // Display the modal
        document.getElementById('passwordModal').style.display = 'block';
        return false;
    }

    // Function to hide the password modal
    function hidePasswordModal() {
        // Hide the modal
        document.getElementById('passwordModal').style.display = 'none';
    }

    // author - sahara
    function showDashboardModal() {
        // Display the modal
        document.getElementById('dashboardModal').style.display = 'block';
        return false;
    }

    // Function to hide the password modal
    function hideDashboardModal() {
        // Hide the modal
        document.getElementById('dashboardModal').style.display = 'none';
    }
    // author - sahara

    // Function to validate the password using AJAX
    function validatePassword() {
        var password = document.getElementById('passwordInput').value;

        $.ajax({
            type: 'POST',
            url: 'validate_password.php',
            data: { password: password },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Password is valid, proceed with the action
                    // You can redirect or perform any other action here
                    window.location.href = 'backupRestore.php';
                    
                } else {
                    // Password is invalid, show an error message
                    alert('Error: ' + response.message);
                    passwordInput.value = "";
                }
            },
            error: function() {
                alert('Error occurred during password validation.');
            }
        });
    }

  </script>
</head>

<body style="background-color: rgb(241, 241, 241);">

  <!-- MainBody -->
  <!-- header -->
  <div class="w3-row w3-container w3-blue-grey">
    <div class="w3-col s12 w3-blue-grey w3-center ">
      <p class="my-3">SELECT LINE NO</p>
    </div>
  </div>
  <!-- End-header -->

  <!-- Body -->
  <form action="initialPage.php" method="post" style="height:100%">
    <select name="sFLOOR" id="sidFLOOR" class="w3-select">
      <option value="FLOOR-01">FLOOR-01</option>
      <option value="FLOOR-02">FLOOR-02</option>
      <option value="FLOOR-03">FLOOR-03</option>
      <option value="FLOOR-04">FLOOR-04</option>
      <option value="FLOOR-05">FLOOR-05</option>
    </select>
    <br><br>
    <select name="option" id="sidLINE" class="w3-select">
      <?php echo $options; ?>
    </select>
    <br><br>
    <div class="w3-row">
      <button class="w3-button w3-teal w3-mobile" name="sew" id="idBTS">SEWING</button>
      <button class="w3-button w3-teal w3-mobile" name="fin" id="idBTF">PAD SEWING</button>
      <button class="w3-button w3-teal w3-mobile" name="pak" id="idBTP">PACKING</button>
      <button class="w3-button w3-teal w3-mobile" name="dashboard" id="idDashboard" 
      onclick="return showDashboardModal()">DASHBOARD</button> 
      <!--  id="idDashboard" onclick="return showDashboardModal()" added -->
      <button class="w3-button w3-teal w3-mobile" name="details">DETAILS</button>
    </div>
    <br>
    <div class="w3-row">
      <button class="w3-button w3-teal w3-mobile" name="change" id="idChange">CHANGE</button>
      <button class="w3-button w3-teal w3-mobile" name="adjust" id="idAdjust">ADJUST</button>
      <button class="w3-button w3-teal w3-mobile" name="setting">ZTARGET</button>
      <button class="w3-button w3-teal w3-mobile" name="qtycheck">ALL QTY</button>
      <button class="w3-button w3-red  w3-mobile" name="export">EXPORT</button>
    </div>
    <br>
    <div class="w3-row">
      <button class="w3-button w3-teal w3-mobile" name="mis" id="idMIS">PRODUCTION MIS</button>
      <button class="w3-button w3-black w3-mobile" name="backup_restore" id="idBackupRestore" onclick="return showPasswordModal()">BACKUP / RESTORE</button>
    </div><br>
  </form>
  <!-- End-Body -->
  <div id="passwordModal" class="w3-modal ">
        <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width: 400px">
            <div class="w3-container">
                <span onclick="hidePasswordModal()" class="w3-button w3-display-topright">&times;</span>
                <h2 class="w3-center w3-margin-top">Password Verification</h2>
                <!-- <p>Please enter your password to proceed:</p> -->
                <input type="password" id="passwordInput"class="w3-input" placeholder="Enter password" required>
                <br>
                <button class="w3-button w3-teal" name="submit" onclick="validatePassword()">Submit</button>
            </div><br>
        </div>
    </div>
  <!-- End-Modal -->
  <!-- Dashboard Modal Start -->
  <div id="dashboardModal" class="w3-modal">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">SELECT LINE NO</h5>
                <button type="button" class="btn-close" onclick="hideDashboardModal()" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Line No List -->
                <div class="container">
                    <?php
                    // Assuming $options is a string containing HTML <option> tags
                    $optionsArray = explode('</option>', $options);
                    array_pop($optionsArray);  // Remove the last element (empty string after the last </option>)

                    $rowCount = count($optionsArray);
                    $colCount = 2; // Number of columns

                    // Calculate the number of rows needed
                    $rowCount = ceil($rowCount / $colCount);

                    for ($i = 0; $i < $rowCount; $i++) {
                    ?>
                        <div class="row">
                            <?php
                            for ($j = 0; $j < $colCount; $j++) {
                                $index = $i * $colCount + $j;
                                if ($index < count($optionsArray)) {
                                    $option = $optionsArray[$index];
                                    // Extract the text content as the value
                                    $value = strip_tags($option);
                            ?>
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="<?php echo $value; ?>" id="flexCheck_<?php echo $value; ?>">
                                            <label class="form-check-label" for="flexCheck_<?php echo $value; ?>">
                                                <?php echo $value; ?>
                                            </label>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="hideDashboardModal()">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Modal Ends -->
  
    <!--footer -->
  <div class="w3-container w3-bottom w3-blue-grey">
    <a href="initialPage.php">
      <div class="w3-col s3 w3-blue-grey w3-center w3-border-right w3-hover-dark-grey">
        <p class="my-3"> <a href="index.php?logout='1'" class="text-white">LOGOUT</a> </p>
      </div>
    </a>

    <div class="w3-col s6 w3-blue-grey w3-center w3-border-right">
      <p class="my-3">USERID:<span id="idUSR"><?php echo  $uname; ?></span></p>
    </div>
    <div class="w3-col s3 w3-blue-grey w3-center ">
      <p class="my-3"> <?php echo $date; ?> </p>
    </div>
  </div>
  <!--End-footer -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body> <!-- End-MainBody -->

</html>