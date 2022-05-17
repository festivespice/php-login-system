<?php
session_start();
include_once './dbh.inc.php';
include_once './functions.inc.php';
if(isset($_POST['submit-text'])){
    $description = $_POST['bioDesc'];
    $name = $_POST['bioName'];
    $title = $_POST['bioTitle'];
    $userId = $_SESSION['userId'];
    //if a user wants their items empty, let them be empty.
    $sql = "update profile pr set pr.bioDesc=?, pr.bioName=?, pr.bioTitle=? where pr.userId=?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: ../profile-edit.php?error=stmtfailedtext&bioName=$name&bioTitle=$title&bioDesc=$description");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "ssss", $description, $name, $title, $userId);
        mysqli_stmt_execute($stmt);

        header("Location: ../profile-edit.php?successtext");
        exit();
    }
}