<?php

if(isset($_POST['submit-post'])){
    session_start();
    include_once '../dbh.inc.php';
    include_once '../functions.inc.php';

    $id = $_SESSION['userId'];
    $inputFilename = $_POST['filename'];

    if(empty($inputFilename)){
        $inputFilename = "forum-group";
    } else {
        $inputFilename = strtolower(str_replace(" ", "-", $inputFilename));
    }
    $inputForumtitle = $_POST['forumtitle'];
    $inputForumdesc = $_POST['forumdesc'];

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
                $fileDestination = "../../image/forum-groups/".$fullFileName;
                if(empty($inputForumtitle)){ //only the file title should not be null
                    header("Location: ../../forums.php?error=emptyInput&filename=".$inputFilename."&forumtitle=".$inputForumtitle."&forumdesc=".$inputForumdesc);
                    exit();
                }else {
                    //validation complete
                    $sql = "select * from forumgroup;";
                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        header("Location: ../../forums.php?error=stmtError&filename=".$inputFilename."&forumtitle=".$inputForumtitle."&forumdesc=".$inputForumdesc);
                        exit();
                    }else{
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $rowCount = mysqli_num_rows($result);
                        $setImageOrder = $rowCount + 1;

                        $sql = "insert into forumgroup (title, description, imageFullName, orderNumber, userId, numberArticles) values (?, ?, ?, ?, ?, ?);";
                        if(!mysqli_stmt_prepare($stmt, $sql)){
                            header("Location: ../../forums.php?error=secondStmtError&filename=".$inputFilename."&forumtitle=".$inputForumtitle."&forumdesc=".$inputForumdesc);
                            exit();
                        } else {
                            $numberArticles = 0;
                            mysqli_stmt_bind_param($stmt, "ssssss", $inputForumtitle, $inputForumdesc, $fullFileName, $setImageOrder, $id, $numberArticles);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);

                            move_uploaded_file($fileTempName, $fileDestination);
                            header("Location: ../../forums.php?success");
                            exit();
                        }
                    }
                }
            }else {
                header("Location: ../../forums.php?error=exceedsFileSize&filename=".$inputFilename."&forumtitle=".$inputForumtitle."&forumdesc=".$inputForumdesc);
                exit();
            }
        }else {
            header("Location: ../../forums.php?error=improperExtension&filename=".$inputFilename."&forumtitle=".$inputForumtitle."&forumdesc=".$inputForumdesc);
            exit();  
        }
    } else {
        header("Location: ../../forums.php?error=errorUploading&filename=".$inputFilename."&forumtitle=".$inputForumtitle."&forumdesc=".$inputForumdesc);
        exit();
    }
} else {
    header("Location: ../../forums.php");
    exit();
}  