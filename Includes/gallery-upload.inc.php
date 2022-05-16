<?php

if(isset($_POST['submit-post'])){
    session_start();
    include_once './dbh.inc.php';
    include_once './functions.inc.php';

    //$id = $_SESSION['userId'];
    $inputFilename = $_POST['filename'];

    //input validation
    if($inputFilename){//if the file name is empty...
        $inputFilename = "gallery";
    } else {
        //if not, replace all spaces with something else
        $inputFile = strtolower(str_replace(" ", "-", $inputFile));
    }
    $inputFiletitle = $_POST['filetitle'];
    $inputFiledesc = $_POST['filedesc'];

    $inputFile = $_FILES['file'];
    $fileName = $inputFile["name"];
    $fileType = $inputFile["type"];
    $fileTempName = $inputFile["tmp_name"];
    $fileError = $inputFile["error"];
    $fileSize = $inputFile["size"]; //size is in KB

    if($fileError === 0){
        $fileNameInfo = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameInfo));
        $allowedExtensions = array("jpg", "jpeg", "png");
    
        if(in_array($fileExtension, $allowedExtensions)){
            if($fileSize < 20000){ //if less than 20MB
                //all validation checks are done!
                
            } else {
                echo "File size is too big";
                exit();
            }
        } else {
            echo "extension error";
            exit();
        }
    } else {
        echo "file error";
        exit();
    }
    
}