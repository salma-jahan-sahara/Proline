<?php
//session_start();
include 'config.php';

if (isset($_POST['password'])) {
    $password = $_POST['password'];
    //$username = $_SESSION['username'];

    // Add your password validation logic here
    // Example: You may compare the entered password with the one stored in the database

    $sql = "SELECT * FROM backuppass WHERE password = '$password'";
    $result = mysqli_query($db, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Password not provided']);
}
?>
