<?php

    $hostname = "localhost";
    $username = "root";
    $password = "";
    $databaseName = "registration";

    // $hostname = "175.29.184.14";
    // $username = "abuhena";
    // $password = "Purbani@123";
    // $databaseName = "registration";
    // connect to mysql database
    $db = mysqli_connect($hostname, $username, $password, $databaseName) or die("Server Error"); 

?>