<?php
session_start();
include_once '../dbh.inc.php';
include_once '../functions.inc.php';

if(!isset($_GET['group-id']) || !isset($_GET['user-id'])){
    header("Location: forums.php");
} else {
    //first, check the bridge table to see if the user has already favorited. (if a row exists)
    $groupId = $_GET['group-id'];
    $userId = $_GET['user-id'];
    $scrollY = $_GET['scrollY'];
    $sql = "select * from forumgroup_userfavorites_bridge fubr where fubr.forumGroupId='$groupId' and fubr.userId='$userId';";
    $result = mysqli_query($conn, $sql);
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_num_rows($result) == 1){
        //can't favorite more than once... Our goal is to delete the row: assume that the user is unfavoriting. 
        $sql ="delete from forumgroup_userfavorites_bridge where forumGroupId=? and userId=?;";
        if(!mysqli_stmt_prepare($stmt, $sql)){
            //an error
            header("Location: ../../forums.php?error=stmt1&scrollY=".$scrollY);
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $groupId, $userId);
            mysqli_stmt_execute($stmt);
            //successfully deleted (unfavorited)
            $sql = "update forumgroup fg set fg.numberFavorites=fg.numberFavorites-1 where fg.id=".$groupId.";";
            if(!mysqli_stmt_prepare($stmt, $sql)){
                header("Location: ../../forums.php?error=stmt3&scrollY=".$scrollY);
                exit();
            }else{//successfully updated
                mysqli_stmt_execute($stmt);
                header("Location: ../../forums.php?removed=success&scrollY=".$scrollY);
                exit();
            }
        }
    } else {
        //haven't favorited yet, so then insert row
        $sql = "insert into forumgroup_userfavorites_bridge (forumGroupId, userId) values (?, ?)";
        if(!mysqli_stmt_prepare($stmt, $sql)){
            //an error
            header("Location: ../../forums.php?error=stmt2&scrollY=".$scrollY);
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $groupId, $userId);
            mysqli_stmt_execute($stmt);

            //success... update total number of favorites
            $sql = "update forumgroup fg set fg.numberFavorites=fg.numberFavorites+1 where fg.id=".$groupId.";";
            if(!mysqli_stmt_prepare($stmt, $sql)){
                header("Location: ../../forums.php?error=stmt3&scrollY=".$scrollY);
                exit();
            }else{ //successfully updated
                mysqli_stmt_execute($stmt);
                header("Location: ../../forums.php?added=success&scrollY=".$scrollY);
                exit();
            }
        }
    }
    header("Location: ../../forums.php?notLoggedIn");
    exit();
}
 