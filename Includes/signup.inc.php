<?php

    //check if a user entered this page without submitting the form
    if (isset($_POST['submit'])){

        $name = $_POST['name'];
        $email = $_POST['email'];
        $uid = $_POST['uid'];
        $password = $_POST['password'];
        $passwordre = $_POST['passwordre'];

        require_once 'dbh.inc.php';
        require_once 'functions.inc.php';

        //error handlers. the order sort of matters: you'd want users to know that something was empty before anything else.
        if(emptyInputSignup($name, $email, $uid, $password, $passwordre) !== false){
            header("location: ../signup.php?error=emptyinput");
            exit(); //stops the script from running
        }
        if(invalidUid($uid) !== false){
            header("location: ../signup.php?error=invaliduid&name=$name&email=$email");
            exit(); 
        }
        if(invalidEmail($email) !== false){
            header("location: ../signup.php?error=invalidemail&name=$name&uid=$uid");
            exit(); 
        }
        if(passwordMatch($password, $passwordre) !== false){
            header("location: ../signup.php?error=passwordsdontmatch&name=$name&email=$email&uid=$uid");
            exit(); 
        }
        if(uidExists($conn, $uid, $email, $name) !== false){ //we actually need the database connection to check this
            header("location: ../signup.php?error=usernameoremailtaken&name=$name&email=$email");
            exit(); 
        }

        //at this point, the user's input is valid
        createUser($conn, $name, $email, $uid, $password);
        exit();
    } else {
        header("location: ../signup.php");
        exit(); 
    }

