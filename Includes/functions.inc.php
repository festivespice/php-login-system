<?php
    //return true if the input was empty for one of the attributes
    function emptyInputSignup($name, $email, $uid, $password, $passwordre){
        $result;
        if(empty($name) || empty($email) || empty($uid) || empty($password) || empty($passwordre)){
            $result = true;
        }else {
            $result = false;
        }
        return $result;
    }
    
    //return true if the UID contains invalid characters
    function invalidUid($uid) {
        $result;
        if(!preg_match("/^[a-zA-Z0-9]*$/", $uid)){
            $result = true; //we're checking if it's invalid. If it is, we'll land here
        } //using regex to check if numbers and lowercase/uppercase letters are used exclusively
        else {
            $result = false;
        }
        return $result;
    }

    //return true if the email is not valid
    function invalidEmail($email) {
        $result;
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result = true; 
        } else {
            $result = false; 
        }
        return $result;
    }

    //return true if the password and its retyped copy is equal
    function passwordMatch($password, $passwordre) {
        $result;
        if($password !== $passwordre){
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    //return true if the UID trying to be created 
    function uidExists($conn, $uid, $email, $name) {
        $result;
        $sql = "select * from users u where u.uid =? or u.email =?;"; //check for user ID or email
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){ //does it fail?
            //there is an error,
            header("location: ../signup.php?error=stmtfailed&name=$name&email=$email&uid=$uid");
            exit(); 
        } else { //did not fail
            mysqli_stmt_bind_param($stmt, "ss", $uid, $email); //two strings, and then these two strings
            mysqli_stmt_execute($stmt);

            $resultData = mysqli_stmt_get_result($stmt); //is it empty?
            if($row = mysqli_fetch_assoc($resultData)){ //fetches the result as an associative array
                //if there is a username in this row, then what?
                return $row; 
            } else { //array is empty
                $result = false;
                return $result;
            }
            mysqli_stmt_close($stmt);
        }
    }

    //uses an existing database connection to add a valid user to the 'users' table
    function createUser($conn, $name, $email, $uid, $password) {
        $result;
        $sql = "insert into users(name, email, uid, password) values(?, ?, ?, ?);"; //check for user ID or email
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){ //does it fail?
            //there is an error,
            header("location: ../signup.php?error=stmtfailed&name=$name&email=$email&uid=$uid");
            exit(); 
        } else { //did not fail

            //before registering the user, hash the password. 
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); 

            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $uid, $hashedPassword); //two strings, and then these two strings
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            header("location: ../signup.php?error=none");
            exit(); 
        }
    }

    function emptyInputLogin($uid, $password) {
        $result;
        if(empty($uid) || empty($password)){
            $result = true;
        }else {
            $result = false;
        }
        return $result;
    }

    function loginUser($conn, $uid, $password) {
        //uidExists($conn, $uid, $email, $name)
        $uidExists = uidExists($conn, $uid, $uid, "");

        if($uidExists === false){
            header("location: ../signin.php?error=wronglogin");
            exit();
        }
        
        //uid returns an associative array (a row) if there is a uid. 
        $hashedPassword = $uidExists['password'];
        //we don't de-hash the hash. That wouldn't be possible, and it would be unsecure.
        //Instead, we hash the inputted password and compare the original hashed password. 
        $checkPassword = password_verify($password, $hashedPassword);
        
        if($checkPassword == false) { //the password was incorrect. Don't tell the user that the password specifically was incorrect.
            header("location: ../signin.php?error=wronglogin");
            exit();
        } else if ($checkPassword === true){
            session_start();
            //now that a session is started, we can start using session variables without losing them.
            $_SESSION["userId"] = $uidExists["id"]; //the id of the row returned from 'uidExists()'
            $_SESSION["userUid"] = $uidExists["uid"]; //the uid of the row returned from 'uidExists()'
            $_SESSION["userName"] = $uidExists["name"];
            header("location: ../index.php?error=none");
            exit();
        }
    }

    function checkIfUserImageExists($conn, $userId){
        $imageExists = false;

        $sql = "select * from profileimg pr where pr.userId=".$userId.";"; //no user input, so no need to sanitize.
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if($row['status'] == 0){
                $imageExists = true;
            }
        }
        return $imageExists;
    }

    function returnUserImagePath($imageExists, $userId){
        $imageUrl = "./uploads/"; //relative to the parent .. directory.
        if($imageExists){
            $globUrl = $imageUrl."profile".$userId."*";
            $globResult = glob($globUrl); //check if this is only one. It should return all matches to the input pattern

            $arrWithExtension = explode(".", $globResult[0]);
            $fileExtension = $arrWithExtension[2]; //the only '.' in the name has the extension after it
            $imageUrl = $imageUrl."profile".$userId.".".$fileExtension;
        } else {
            $imageUrl = $imageUrl."default.png";
        }
        return $imageUrl;
    }
