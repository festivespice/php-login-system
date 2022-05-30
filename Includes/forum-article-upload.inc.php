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
    $inputArticleTitle = $_POST['articletitle'];
    $inputArticleDesc = $_POST['articledesc'];

    $inputArticleTitle = str_replace("'", "", $inputArticleTitle); //double quotes or single quotes in the forum title can break the buttons used for interacting with articles.
    $inputArticleTitle = str_replace('"', "", $inputArticleTitle); //This is a quick fix, although it might be possible to have quotes in an article title 

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
                if(empty($inputArticleTitle)){ //only the file title should not be null
                    header("Location: ../forum-articles.php?error=emptyInput&filename=".$inputFilename."&forumtitle=".$inputArticleTitle."&forumdesc=".$inputArticleDesc."&group-id=".$groupId."&group-name=".$groupName);
                    exit();
                }else {
                    //validation complete
                    $sql = "select * from forumarticle fa where fa.forumGroupId=".$groupId.";";
                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        header("Location: ../forum-articles.php?error=stmtError&filename=".$inputFilename."&forumtitle=".$inputArticleTitle."&forumdesc=".$inputArticleDesc."&group-id=".$groupId."&group-name=".$groupName);
                        exit();
                    }else{
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $rowCount = mysqli_num_rows($result);
                        $setArticleOrder = $rowCount + 1; //used for updating forumgroup's numberArticles

                        $sql = "insert into forumarticle (title, description, imageFullName, orderNumber, userId, forumGroupId, numberComments) values (?, ?, ?, ?, ?, ?, ?);";
                        if(!mysqli_stmt_prepare($stmt, $sql)){
                            header("Location: ../forum-articles.php?error=secondStmtError&filename=".$inputFilename."&forumtitle=".$inputArticleTitle."&forumdesc=".$inputArticleDesc."&group-id=".$groupId."&group-name=".$groupName);
                            exit();
                        } else {
                            $numberComments = 0;
                            mysqli_stmt_bind_param($stmt, "sssssss", $inputArticleTitle, $inputArticleDesc, $fullFileName, $setArticleOrder, $id, $groupId, $numberComments);
                            mysqli_stmt_execute($stmt); //successfully inserted article... update number of articles
                            $sql = "update forumgroup fg set fg.numberArticles=fg.numberArticles+1 where fg.id=".$groupId.";";
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                                header("Location: ../forum-articles.php?error=thirdStmtError&filename=".$inputFilename."&forumtitle=".$inputArticleTitle."&forumdesc=".$inputArticleDesc."&group-id=".$groupId."&group-name=".$groupName);
                                exit();
                            }else{ //no errors, execute and upload file
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_close($stmt);
                                move_uploaded_file($fileTempName, $fileDestination);
                                header("Location: ../forum-articles.php?success&group-id=".$groupId."&group-name=".$groupName);
                                exit();
                            }
                        }
                    }
                }
            }else {
                header("Location: ../forum-articles.php?error=exceedsFileSize&filename=".$inputFilename."&forumtitle=".$inputArticleTitle."&forumdesc=".$inputArticleDesc."&group-id=".$groupId."&group-name=".$groupName);
                exit();
            }
        }else {
            header("Location: ../forum-articles.php?error=improperExtension&filename=".$inputFilename."&forumtitle=".$inputArticleTitle."&forumdesc=".$inputArticleDesc."&group-id=".$groupId."&group-name=".$groupName);
            exit();  
        }
    } else {
        header("Location: ../forum-articles.php?error=errorUploading&filename=".$inputFilename."&forumtitle=".$inputArticleTitle."&forumdesc=".$inputArticleDesc."&group-id=".$groupId."&group-name=".$groupName);
        exit();
    }
} else {
    header("Location: ../forum-articles.php?group-id=".$groupId."&group-name=".$groupName);
    exit();
}  