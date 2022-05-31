<?php
session_start();
include_once '../dbh.inc.php';
include_once '../functions.inc.php';

if(isset($_POST['submit-delete'])){
    $id = $_SESSION['userId'];

    //1) Find the file file extension by globbing
    $fileName = '../../image/profile-pictures/profile'.$id.'*';
    $uploadsCaptured = glob($fileName);
    print_r($uploadsCaptured);

    $fileData = explode(".", $uploadsCaptured[0]);
    $fileExtension = $fileData[3];
    print_r($fileData);

    //2) Create file path
    $filePath = '../../image/profile-pictures/profile'.$id.'.'.$fileExtension;

    //3) Delete file
    if(!unlink($filePath)){ //check if file wasn't deleted
        header("Location: ../../profile-ed.php?deletesuccess=false");
    } else {
        //if the file was deleted, we need to update profileimg
        $sql = "update profileimg pr set pr.status=1 where pr.userId=".$id.";";
        $result = mysqli_query($conn, $sql);
        header("Location: ../../profile-edit.php?deletesuccess=true");
    }
}   