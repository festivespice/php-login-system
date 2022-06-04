<?php
session_start();
include_once '../dbh.inc.php';
include_once '../functions.inc.php';
$userId = $_SESSION['userId'];
$groupId = $_GET['group-id'];
$groupName = $_GET['group-name'];
$articleId = $_GET['article-id'];
$articleName = $_GET['article-name'];
$itemId;
$time = time(); //the type of dateCreated in forumItem is int so that the Unix timestamp can be stored instead of datetime. This way, we can convert the time however we want (timezones).

if(isset($_GET['item-id'])){
    $itemId = $_GET['item-id'];
}

//first, check if either the group or the article are closed or deleted.
$sql = "select * from forumgroup fg where fg.id = ".$groupId.";";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) == 1){
    while($row = mysqli_fetch_assoc($result)){
        if($row['isClosed'] == 1 || $row['isDeleted'] == 1){ 
            header("Location: ../../forums.php?deletedOrClosed");
            exit();
        }
    }
}
$sql = "select * from forumarticle fa where fa.id = ".$articleId.";";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) == 1){
    while($row = mysqli_fetch_assoc($result)){
        if($row['isClosed'] == 1 || $row['isDeleted'] == 1){ 
            if($source == "group"){
                header("Location: ../../forum-articles.php?deletedOrClosedArticle&group-id=".$groupId."&group-name=".$groupName);
                exit();
            }else if($source == "article"){
                header("Location: ../../forum-article.php?deletedOrClosedArticle&group-id=".$groupId."&group-name=".$groupName."&article-id=".$articleId."&article-name=".$articleName);
                exit();
            }
        }
    }
}

//Then, check if we're replying to an item (itemId is set). If we are, check if it's deleted. 
if(isset($itemId)){
    $sql = "select * from forumItem fa where fi.id = ".$itemId.";";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 1){
        while($row = mysqli_fetch_assoc($result)){
            if($row['isDeleted'] == 1){ 
                header("Location: ../../forum-article.php?deletedItem&group-id=".$groupId."&group-name=".$groupName."&article-id=".$articleId."&article-name=".$articleName);
                exit();
            }
        }
    }
}


//if everything is well, do input validation
$inputFilename = $_POST['filename'];
if(empty($inputFilename)){
    $inputFilename = "forum-article";
} else {
    $inputFilename = strtolower(str_replace(" ", "-", $inputFilename));
}
$inputItemContent = $_POST['item-content'];

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
        if($fileSize <= 5242880){ //if less than 5MB (There will be many posts, so it can't be that big.)
            $fullFileName = $inputFilename.".".uniqid("", true).".".$fileExtension;
            $fileDestination = "../../image/forum-items/".$fullFileName;
            if(empty($inputItemContent)){ //if there isn't anything in the post, then don't continue
                header("Location: ../../forum-article.php?error=emptyInput&filename=".$inputFilename."&item-content=".$inputItemContent."&group-id=".$groupId."&group-name=".$groupName."&article-name=".$articleName."&article-id=".$articleId);
                exit();
            }else {

                //validation complete
                $sql = "select * from forumitem fi where fi.forumArticleId=".$articleId.";"; // the post should be displayed in order relative to the article
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    header("Location: ../../forum-article.php?error=firstStmtError&filename=".$inputFilename."&item-content=".$inputItemContent."&group-id=".$groupId."&group-name=".$groupName."&article-name=".$articleName."&article-id=".$articleId);
                    exit();
                }else{
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $rowCount = mysqli_num_rows($result);
                    $setItemOrder = $rowCount + 1; //used for updating forumArticle's number of comments

                    $sql = "insert into forumItem (text, imageFullName, orderNumber, userId, forumArticleId, dateCreated) values (?, ?, ?, ?, ?, ?);";
                    if(isset($itemId)){ //for replies
                        $sql = "insert into forumItem (text, imageFullName, orderNumber, userId, forumArticleId, dateCreated, replyItemId) values (?, ?, ?, ?, ?, ?, ?);";
                    }

                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        header("Location: ../../forum-articles.php?error=secondStmtError&filename=".$inputFilename."&item-content=".$inputItemContent."&group-id=".$groupId."&group-name=".$groupName."&article-name=".$articleName."&article-id=".$articleId);
                        exit();
                    } else {
                        if(isset($itemId)){
                            mysqli_stmt_bind_param($stmt, "sssssss", $inputItemContent, $fullFileName, $setItemOrder, $userId, $articleId, $time, $itemId);
                        }else{
                            mysqli_stmt_bind_param($stmt, "ssssss", $inputItemContent, $fullFileName, $setItemOrder, $userId, $articleId, $time);
                        }
                        mysqli_stmt_execute($stmt); //successfully inserted item... now, update number of comments

                        $sql = "update forumarticle fa set fa.numberComments=fa.numberComments+1 where fa.id=".$articleId.";";
                        if(!mysqli_stmt_prepare($stmt, $sql)){
                            header("Location: ../../forum-article.php?error=thirdStmtError&filename=".$inputFilename."&item-content=".$inputItemContent."&group-id=".$groupId."&group-name=".$groupName."&article-name=".$articleName."&article-id=".$articleId);
                            exit();
                        }else{ //no errors, execute final statement and upload file
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                            move_uploaded_file($fileTempName, $fileDestination);
                            header("Location: ../../forum-article.php?success&group-id=".$groupId."&group-name=".$groupName."&article-name=".$articleName."&article-id=".$articleId);
                            exit();
                        }
                    }
                }
            }
        }else {
            header("Location: ../../forum-article.php?error=exceedsFileSize&filename=".$inputFilename."&item-content=".$inputItemContent."&group-id=".$groupId."&group-name=".$groupName."&article-name=".$articleName."&article-id=".$articleId);
            exit();
        }
    }else {
        header("Location: ../../forum-article.php?error=improperExtension&filename=".$inputFilename."&item-content=".$inputItemContent."&group-id=".$groupId."&group-name=".$groupName."&article-name=".$articleName."&article-id=".$articleId);
        exit();  
    }
} else {
    header("Location: ../../forum-article.php?error=errorUploading&filename=".$inputFilename."&item-content=".$inputItemContent."&group-id=".$groupId."&group-name=".$groupName."&article-name=".$articleName."&article-id=".$articleId);
    exit();
}