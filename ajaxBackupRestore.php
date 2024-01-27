<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : null;
    $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : null;

    // Check if dates are provided
    if (!$startDate || !$endDate) {
        echo "error: Please select both start and end dates.";
        exit();
    }

    // Array containing the names of your tables
    $tables = ["archiveeff", "dhu","inputdetails","kpiview","mcrd", "outputdetails", "rejectdetails","tpfn","tppk"];
    
    $successMessage = '';

    foreach ($tables as $table) {
        if ($action === 'backup') {
            $result = executeOperation($table, $startDate, $endDate, 'backup');
        } elseif ($action === 'restore') {
            $result = executeOperation($table, $startDate, $endDate, 'restore');
        }
        if ($result['status'] === 'success') {
            $successMessage .= $result['message'] . ' '; // Concatenate success messages
        }
    }
    if (!empty($successMessage)) {
        echo 'success: ' . $successMessage;
    }
}

function executeOperation($tableName, $startDate, $endDate, $operation)
{
    // Establish a MySQL connection (modify with your actual connection details)
    $conn = mysqli_connect("localhost", "root", "", "registration");
    
    // $conn = mysqli_connect('175.29.184.14', 'abuhena', 'Purbani@123', 'registration');

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Escape input to prevent SQL injection (you should use prepared statements)
    $startDate = mysqli_real_escape_string($conn, $startDate);
    $endDate = mysqli_real_escape_string($conn, $endDate);
    $tableName = mysqli_real_escape_string($conn, $tableName);

    if ($operation === 'backup') {
        $result = backupTable($conn, $tableName, $startDate, $endDate);
    } elseif ($operation === 'restore') {
        $result = restoreTable($conn, $tableName, $startDate, $endDate);
    }

    // Close the connection
    mysqli_close($conn);

    // Return the result of the operation
    // Instead of json_encode, just echo the result
    //echo $result['status'] . ': ' . $result['message'];
    //echo  $result['message'];

    return $result;


}

function backupTable($conn, $tableName, $startDate, $endDate)
{
    // Backup query for rejectdetails
    $rejectbackupQuery = "INSERT INTO rejectdetails_backup (TID,ID,NAME,DFQTY,OUTID) 
     SELECT a.TID, a.ID, a.NAME, a.DFQTY, a.OUTID 
     FROM rejectdetails AS a 
     JOIN outputdetails AS b ON a.OUTID = b.TID 
     WHERE CONVERT(DATE_FORMAT(STR_TO_DATE(b.BUDAT,'%d-%m-%Y'),'%Y-%m-%d'),DATE) BETWEEN '{$startDate}' AND '{$endDate}'";

    $deleteRejectBackupQuery = "DELETE FROM rejectdetails 
     WHERE OUTID IN (SELECT TID FROM outputdetails WHERE CONVERT(DATE_FORMAT(STR_TO_DATE(BUDAT,'%d-%m-%Y'),'%Y-%m-%d'),DATE) BETWEEN '{$startDate}' AND '{$endDate}')";
    // Execute the query
    $rejectresultBackup = @mysqli_query($conn, $rejectbackupQuery);
    $rejectresultDeleteBackup = @mysqli_query($conn, $deleteRejectBackupQuery);

    if ($rejectresultBackup === false || $rejectresultDeleteBackup === false) {
        return array('status' => 'error', 'message' => " Backup failed");
    }

    if ($tableName !== 'rejectdetails') {
        // Backup query
        $backupQuery = "INSERT INTO {$tableName}_backup SELECT * FROM {$tableName} WHERE CONVERT(DATE_FORMAT(STR_TO_DATE(BUDAT,'%d-%m-%Y'),'%Y-%m-%d'),DATE) BETWEEN '{$startDate}' AND '{$endDate}';";

        // Delete query
        $deleteQuery = "DELETE FROM {$tableName} WHERE CONVERT(DATE_FORMAT(STR_TO_DATE(BUDAT,'%d-%m-%Y'),'%Y-%m-%d'),DATE) BETWEEN '{$startDate}' AND '{$endDate}';";

        // Execute the queries
        $resultBackup = @mysqli_query($conn, $backupQuery);
        $resultDelete = @mysqli_query($conn, $deleteQuery);

        // Check for errors
        if ($resultBackup === false || $resultDelete === false) {
            return array('status' => 'error', 'message' => " Backup failed");
        }
    }
    return array('status' => 'success', 'message' => " Backup Successful.");
}

function restoreTable($conn, $tableName, $startDate, $endDate)
{
    if ($tableName === 'rejectdetails') {
        // Restore query for rejectdetails
        $rejectrestoreQuery = "INSERT INTO rejectdetails SELECT * FROM rejectdetails_backup WHERE OUTID IN (SELECT TID FROM outputdetails WHERE CONVERT(DATE_FORMAT(STR_TO_DATE(BUDAT,'%d-%m-%Y'),'%Y-%m-%d'),DATE) BETWEEN '{$startDate}' AND '{$endDate}')";

        // Delete restored query for rejectdetails
        $deleteRejectRestoreQuery = "DELETE FROM rejectdetails_backup WHERE OUTID IN (SELECT TID FROM outputdetails WHERE CONVERT(DATE_FORMAT(STR_TO_DATE(BUDAT,'%d-%m-%Y'),'%Y-%m-%d'),DATE) BETWEEN '{$startDate}' AND '{$endDate}')";

        // Execute the queries
        $rejectresultRestore = @mysqli_query($conn, $rejectrestoreQuery);
        $resultDeleteRestore = @mysqli_query($conn, $deleteRejectRestoreQuery);

        if ($rejectresultRestore === false || $resultDeleteRestore === false) {
            return array('status' => 'error', 'message' => "Restore failed");
        }
        return array('status' => 'success', 'message' => "Restore Successful.");
    } else {
        // Restore query
        $restoreQuery = "INSERT INTO {$tableName} SELECT * FROM {$tableName}_backup WHERE CONVERT(DATE_FORMAT(STR_TO_DATE(BUDAT,'%d-%m-%Y'),'%Y-%m-%d'),DATE) BETWEEN '{$startDate}' AND '{$endDate}';";


        // Delete restored query
        $deleteBackupQuery = "DELETE FROM {$tableName}_backup WHERE CONVERT(DATE_FORMAT(STR_TO_DATE(BUDAT,'%d-%m-%Y'),'%Y-%m-%d'),DATE) BETWEEN '{$startDate}' AND '{$endDate}';";

        // Execute the queries
        $resultRestore = @mysqli_query($conn, $restoreQuery);
        $resultDeleteBackup = @mysqli_query($conn, $deleteBackupQuery);

        // Check for errors
        if ($resultRestore === false || $resultDeleteBackup === false) {
            return array('status' => 'error', 'message' => "Restore failed");
        }

        return array('status' => 'success', 'message' => "Restore Successful.");
    }
}
