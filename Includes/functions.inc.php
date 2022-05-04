<?php
    //return true if the input was empty for one of the attributes
    function emptyInputSignup($name, $email, $uid, $password, $passwordre){
        $result;
        
    }
    
    //return true if the UID contains invalid characters
    function invalidUid($username) {
        $result;
        
    }

    //return true if the email is not valid
    function invalidEmail($email) {
        $result;
        
    }

    //return true if the password and its retyped copy is equal
    function passwordMatch($password, $passwordre) {
        $result;
    }

    //return true if the UID trying to be created 
    function uidExists($conn, $username) {
        $result;
    }

    //uses an existing database connection to add a valid user to the 'users' table
    function createUser($conn, $name, $email, $uid, $password) {
        $result;
    }