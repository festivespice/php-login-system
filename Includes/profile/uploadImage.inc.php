<?php
session_start();
include_once '../dbh.inc.php';
include_once '../functions.inc.php';

$id = $_SESSION['userId'];

if(isset($_POST['submit-upload'])){
    //1) Get file data
    $file = $_FILES['file'];

    $fileError = $file['error'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileType = $file['type'];

    $fileNameArr = explode('.', $fileName);
    $fileExtension = strtolower(end($fileNameArr)); //gets the end of the explosion in lowercase
    
    $allowedExtensions = array('jpg', 'jpeg', 'png');

    //2)Validation checks
    if($fileError === 0){
        if($fileSize <= 52428800){ //50 MB
            if(in_array($fileExtension, $allowedExtensions)){ 
                //currently, the file passes all of our validation checks. 
                $newFileName = "profile".$id.".".$fileExtension;
                $fileDestination = "../../image/profile-pictures/".$newFileName;

                //3) save file permanently
                move_uploaded_file($fileTmpName, $fileDestination);
                $sql = "update profileimg pr set pr.status=0 where userid='$id';"; //no input validation needed
                $result = mysqli_query($conn, $sql);
                
                header("Location: ../../profile-edit.php?uploadsuccess=true");                
            } else { //extension used is invalid
                header("Location: ../../profile-edit.php?uploadsuccess=false&msg=invalidExtension");                
            }
        } else { //file is too big: exceeds 50 MB
            header("Location: ../../profile-edit.php?uploadsuccess=false&msg=sizeLimitExceeded");                
        }
    } else { //error uploading file
        header("Location: ../../profile-edit.php?uploadsuccess=false&msg=serverError2");                
    }
}

