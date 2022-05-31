<?php
if(isset($_POST['submit-moderation'])){
    include_once '../dbh.inc.php';
    session_start();
    $moderatorId = $_SESSION['userId'];
    $moderatedId = $_GET['moderated-id'];
    $groupId = $_GET['group-id'];
    $pageType = $_GET['page-type'];
    $reason = $_POST['reason'];
    $banUser; //banning users doesn't do anything yet. 
    $sql;
    $deleteOrRestoreSql;
    $articleId;
    $articleName;
    $groupName;
    $itemId;

    //based on what we're given, how do we dynamically know what we're moderating?
    $moderationArea = "group";
    if(isset($_GET['article-id'])){
        $articleId = $_GET['article-id'];
        $groupName = $_GET['group-name'];
        $articleName = $_GET['article-name'];
        $moderationArea = "article";
        if(isset($_GET['item-id'])){
            $itemId = $_GET['item-id'];

            //if it's an item, it uses everything... no need for an else.
            $moderationArea = "item";
        } 
    } 

    if(isset($_GET['ban-user'])){
        $banUser = $_GET['ban-user'];
    }

    //first, make the moderation. Then, update the moderation table. 
    //after deleting/restoring an article, update the number of articles in the group.
    //after deleting/restoring a comment, update the number of comments in the article. 
    if($moderationArea == "group"){
        echo $pageType;
        if($pageType == "close"){
            $sql = "update forumgroup fg set fg.isClosed=true where fg.id=".$groupId.";";
        } else if ($pageType == "delete"){
            $sql = "update forumgroup fg set fg.isDeleted=true where fg.id=".$groupId.";";
        } else if ($pageType == "restore"){
            $sql = "update forumgroup fg set fg.isDeleted=false where fg.id=".$groupId.";";
        } else if ($pageType == "open") {
            $sql = "update forumgroup fg set fg.isClosed=false where fg.id=".$groupId.";";
        }else {
            header("Location: ../../forums.php");
            exit();
        }
        $result = mysqli_query($conn, $sql);
        $moderationSql="insert into moderation (reason, moderationType, moderatorUserId, moderatedUserId, groupId) values (?, ?, ?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $moderationSql)){
            header("Location: ../../forums.php?actionStmtError");
            exit();
        }else {
            mysqli_stmt_bind_param($stmt, "sssss", $reason, $pageType, $moderatorId, $moderatedId, $groupId);
            mysqli_stmt_execute($stmt);
        }
        header("Location: ../../forums.php");
        exit();
    } else if ($moderationArea == "article"){
        if($pageType == "close"){
            $sql = "update forumarticle fa set fa.isClosed=true where fa.id=".$articleId.";";
        } else if ($pageType == "delete"){
            $deleteOrRestoreSql = "update forumgroup fg set fg.numberArticles=fg.numberArticles-1 where fg.id=".$groupId.";";
            $sql = "update forumarticle fa set fa.isDeleted=true where fa.id=".$articleId.";";
        } else if ($pageType == "restore"){
            $deleteOrRestoreSql = "update forumgroup fg set fg.numberArticles=fg.numberArticles+1 where fg.id=".$groupId.";";
            $sql = "update forumarticle fa set fa.isDeleted=false where fa.id=".$articleId.";";
        } else if ($pageType == "open") {
            $sql = "update forumarticle fa set fa.isClosed=false where fa.id=".$articleId.";";
        }else{
            header("Location: ../../forums.php");
            exit();
        }
        $result = mysqli_query($conn, $sql);
        if(isset($deleteOrRestoreSql)){ //if we're deleting or restoring, update the appropriate counts. 
            $delResResult=mysqli_query($conn, $deleteOrRestoreSql);
        }
        $moderationSql="insert into moderation (reason, moderationType, moderatorUserId, moderatedUserId, groupId, articleId) values (?, ?, ?, ?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $moderationSql)){
            header("Location: ../../forum-articles.php?actionStmtError&group-id=".$groupId."&group-name=".$groupName);
            exit();
        }else {
            mysqli_stmt_bind_param($stmt, "ssssss", $reason, $pageType, $moderatorId, $moderatedId, $groupId, $articleId);
            mysqli_stmt_execute($stmt);
        }
        header("Location: ../../forum-articles.php?group-id=".$groupId."&group-name=".$groupName);
        exit();
    } else if ($moderationArea == "item"){
        if ($pageType == "delete"){
            $deleteOrRestoreSql = "update forumarticle fa fa.numberComments=fa.numberComments-1 where fa.id=".$articleId.";";
            $sql = "update forumitem fi set fi.isDeleted=true where fi.id=".$itemId.";";
        } else if ($pageType == "restore"){
            $deleteOrRestoreSql = "update forumarticle fa fa.numberComments=fa.numberComments+1 where fa.id=".$articleId.";";
            $sql = "update forumitem fi set fi.isDeleted=false where fi.id=".$itemId.";";
        } else {
            header("Location: ../../forums.php");
            exit();
        }
        $result = mysqli_query($conn, $sql);
        if(isset($deleteOrRestoreSql)){ //if we're deleting or restoring, update the appropriate counts. 
            $delResResult=mysqli_query($conn, $deleteOrRestoreSql);
        }
        $moderationSql="insert into moderation (reason, moderationType, moderatorUserId, moderatedUserId, groupId, articleId, itemId) values (?, ?, ?, ?, ?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $moderationSql)){
            //stmt error
        }else {
            mysqli_stmt_bind_param($stmt, "sssssss", $reason, $pageType, $moderatorId, $moderatedId, $groupId, $articleId, $itemId);
            mysqli_stmt_execute($stmt);
        }
        echo "you haven't finished this in forums-action.inc.php yet";
    }
}