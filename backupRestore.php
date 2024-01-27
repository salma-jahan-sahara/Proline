<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username'])) {
    // Redirect to the login page
    echo "<script>alert('You must be logged in'); window.location.href='login.php';</script>";
    exit();
} else {
    $USERIDNEW = isset($_SESSION['USERIDNEW']) ? $_SESSION['USERIDNEW'] : '';
    // Use the USERIDNEW session variable here
    $date = date('d-m-Y');
    mysqli_close($db);
}
?>  
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BackupRestore</title>

    <link rel="stylesheet" type="text/css" href="css/w3.css">
    </link>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        function showAlert(status, message) {
            var alertClass = status === 'success' ? 'alert-success' : 'alert-danger';
            var alertDiv = document.getElementById('statusAlert');
            alertDiv.innerHTML = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                '<strong>' + status + ':</strong> ' + message +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>';
            alertDiv.style.display = 'block';
        }

        function showConfirmationDialog(callback, action) {
        if (confirm("Are you sure you want to " + action + "?")) {
            callback();
        }
    }

        function backupTables() {
            // Get start date and end date from input fields
            var startDate = document.getElementById('fDate').value;
            var endDate = document.getElementById('tDate').value;

            // Array containing the names of your tables
            var tables = ["archiveeff", "dhu","inputdetails","kpiview","mcrd", "outputdetails", "rejectdetails","tpfn","tppk"];
            // Loop through each table and trigger backup
           /*  tables.forEach(function(table) {
                backupTable(table, startDate, endDate);
            }); */
            showConfirmationDialog(function () {
            tables.forEach(function (table) {
                backupTable(table, startDate, endDate);
            });
        }, 'Backup');
        }

        function backupTable(tableName, startDate, endDate) {
            // Assuming you are sending an AJAX request to your PHP script
            $.ajax({
                type: 'POST',
                url: 'ajaxBackupRestore.php', // Replace with the actual path to your PHP script
                data: {
                    action: 'backup',
                    start_date: startDate,
                    end_date: endDate,
                    table: tableName
                },
                dataType: 'text', // Specify the expected response data type as text
                success: function(response) {
                    // Check if the response contains the word 'error'
                    if (response.toLowerCase().includes('error')) {
                        showAlert('error', 'An error occurred: ' + response);
                    } else {
                        showAlert('success',' Backup Successful');
                    }
                },
                error: function(error) {
                    showAlert('error', 'An error occurred: ' + error.statusText);
                }
            });
        }

        function restoreTables() {
            // Get start date and end date from input fields
            var startDate = document.getElementById('fDate').value;
            var endDate = document.getElementById('tDate').value;

            // Array containing the names of your tables
            var tables = ["archiveeff", "dhu","inputdetails","kpiview","mcrd", "outputdetails", "rejectdetails","tpfn","tppk"];

            // Loop through each table and trigger restore
           /*  tables.forEach(function(table) {
                restoreTable(table, startDate, endDate);
            }); */
            showConfirmationDialog(function () {
            tables.forEach(function (table) {
                restoreTable(table, startDate, endDate);
            });
        }, 'Restore');
        }

        function restoreTable(tableName, startDate, endDate) {
            // Assuming you are sending an AJAX request to your PHP script
            $.ajax({
                type: 'POST',
                url: 'ajaxBackupRestore.php', // Replace with the actual path to your PHP script
                data: {
                    action: 'restore',
                    start_date: startDate,
                    end_date: endDate,
                    table: tableName
                },
                dataType: 'text', // Specify the expected response data type as text
                success: function(response) {
                    // Check if the response contains the word 'error'
                    if (response.toLowerCase().includes('error')) {
                        showAlert('error', 'An error occurred: ' + response);
                    } else {
                        showAlert('success',' Restore Successful');
                    }
                },
                error: function(error) {
                    showAlert('error', 'An error occurred: ' + error.statusText);
                }
            });
        }
    </script>

</head>

<body style="background-color: rgb(241, 241, 241);">
    <!-- header -->
    <div class="w3-row w3-blue-grey w3-top ">
        <div class="w3-col s3 w3-blue-grey w3-center w3-border-right ">
            <p class="w3-margin-top">BACKUP / RESTORE</p>
        </div>
        <div class="w3-col s9 w3-blue-grey w3-center ">
            <p class="w3-margin-top">BACKUP / RESTORE Previous Data </p>
        </div>
    </div>
    <br><br>
    <!-- header -->
    <br>
    <div class="w3-container text-center ">
        <input type="date" id="fDate" name="fDate">
        <label>-</label>
        <input type="date" id="tDate" name="tDate">
        <br>
    </div class="w3-container text center">
    <br>
    <!-- Backup Button -->
    <div class="w3-container text-center w3-padding-16">
        <button onclick="backupTables()" class="w3-button w3-teal w3-large">Backup</button>
    </div>
    <br>
    <!-- Restore Button -->
    <div class="w3-container text-center w3-padding-16">
        <button onclick="restoreTables()" class="w3-button w3-teal w3-large">Restore</button>
    </div>
    </div>
    </div>
    <!-- Status Alert -->
    <div id="statusAlert" class="position-fixed top-50 start-50 translate-middle-x col-8">
        <!-- Alert will be displayed here -->
    </div>
    <!--footer -->
<div class="w3-row w3-container w3-bottom w3-blue-grey ">
    <a href="initialPage.php">
        <div class="w3-col s3 w3-blue-grey w3-center w3-border-right w3-hover-dark-grey " style="cursor: pointer ;">
            <p class="w3-margin-top">
                < BACK </p>
        </div>
    </a>
    <div class="w3-col s6 w3-blue-grey w3-center w3-border-right">
        <p class="w3-margin-top">USERID:<span id="idUSERIDNEW"><?php echo $USERIDNEW; ?></span></p>
    </div>
    <div class="w3-col s3 w3-blue-grey w3-center ">
        <p class="w3-margin-top" id="BUDAT"> <?php echo $date; ?> </p>
    </div>
</div>
<!--End-footer -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>