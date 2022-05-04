<?php
    //needs four parameters for connecting...
    $serverName = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "loginSystem";

    $conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

    if(!$conn){
        die("Connection failed: " . mysqli_connect_error()); //kills the current process
    }