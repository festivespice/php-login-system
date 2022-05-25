<?php

if(isset($_POST['submit-post'])){
    session_start();
    include_once './dbh.inc.php';
    include_once './functions.inc.php';

    $groupId = $_GET['group-id'];
    $groupName = $_GET['group-name'];
    
    $id = $_SESSION['userId'];
    $inputFilename = $_POST['filename'];

    if(empty($inputFilename)){
        $inputFilename = "forum-article";
    } else {
        $inputFilename = strtolower(str_replace(" ", "-", $inputFilename));
    }
    $inputForumtitle = $_POST['articletitle'];
    $inputForumdesc = $_POST['articledesc'];

    $inputFile = $_FILES['file'];
    $fileName = $inputFile['name'];
    $fileType = $inputFile['type'];
    $fileTempName = $inputFile['tmp_name'];
    $fileError = $inputFile['error'];
    $fileSize = $inputFile['size']; //file size in bytes!!!

    if($fileError === 0){
        $fileNameInfo = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameInfo));
        $allowedExtension = array("jpg", "jpeg", "png");

        if(in_array($fileExtension, $allowedExtension)){
            if($fileSize <= 20971520){ //if less than 20MB
                $fullFileName = $inputFilename.".".uniqid("", true).".".$fileExtension;
                $fileDestination = "../image/forum-articles/".$fullFileName;
                if(empty($inputForumtitle)){ //only the file title should not be null
                    header("Location: ../forum-articles.php?error=emptyInput&filename=".$inputFilename."&forumtitle=".$inputForumtitle."&forumdesc=".$inputForumdesc."&group-id=".$groupId."&group-name=".$groupName);
                    exit();
                }else {
                    //validation complete
                    $sql = "select * from forumarticle fa where fa.forumGroupId=".$groupId.";";
                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        header("Location: ../forum-articles.php?error=stmtError&filename=".$inputFilename."&forumtitle=".$inputForumtitle."&forumdesc=".$inputForumdesc."&group-id=".$groupId."&group-name=".$groupName);
                        exit();
                    }else{
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $rowCount = mysqli_num_rows($result);
                        $setArticleOrder = $rowCount + 1; //UPDATE forumgroup

                        $sql = "insert into forumarticle (title, description, imageFullName, orderNumber, userId, forumGroupId, numberComments) values (?, ?, ?, ?, ?, ?, ?);";
                        if(!mysqli_stmt_prepare($stmt, $sql)){
                            header("Location: ../forum-articles.php?error=secondStmtError&filename=".$inputFilename."&forumtitle=".$inputForumtitle."&forumdesc=".$inputForumdesc."&group-id=".$groupId."&group-name=".$groupName);
                            exit();
                        } else {
                            $numberComments = 0;
                            mysqli_stmt_bind_param($stmt, "sssssss", $inputForumtitle, $inputForumdesc, $fullFileName, $setArticleOrder, $id, $groupId, $numberComments);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                            move_uploaded_file($fileTempName, $fileDestination);
                            header("Location: ../forum-articles.php?success&group-id=".$groupId."&group-name=".$groupName);
                            exit();
                        }
                    }
                }
            }else {
                header("Location: ../forum-articles.php?error=exceedsFileSize&filename=".$inputFilename."&forumtitle=".$inputForumtitle."&forumdesc=".$inputForumdesc."&group-id=".$groupId."&group-name=".$groupName);
                exit();
            }
        }else {
            header("Location: ../forum-articles.php?error=improperExtension&filename=".$inputFilename."&forumtitle=".$inputForumtitle."&forumdesc=".$inputForumdesc."&group-id=".$groupId."&group-name=".$groupName);
            exit();  
        }
    } else {
        header("Location: ../forum-articles.php?error=errorUploading&filename=".$inputFilename."&forumtitle=".$inputForumtitle."&forumdesc=".$inputForumdesc."&group-id=".$groupId."&group-name=".$groupName);
        exit();
    }
} else {
    header("Location: ../forum-articles.php?group-id=".$groupId."&group-name=".$groupName);
    exit();
}  