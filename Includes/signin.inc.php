<?php 

    if(isset($_POST['submit']))
    {
        $uid = $_POST['uid']; //could be the username or email
        $password = $_POST['password'];

        include_once './dbh.inc.php';
        include_once './functions.inc.php';

        //error handlers. the order doesn't really matter.
        if(emptyInputLogin($uid, $password) !== false){
            header("location: ../signin.php?error=emptyinput");
            exit(); //stops the script from running
        }

        //the user input is valid
        loginUser($conn, $uid, $password);
    } else {
        header("location: ../signin.php");
        exit(); 
    }