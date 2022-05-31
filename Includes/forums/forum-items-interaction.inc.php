<?php
//update both the bridge table and the article's total number of likes and dislikes
session_start();
include_once '../dbh.inc.php';
$groupId = $_GET['group-id'];
$articleId = $_GET['article-id'];
$articleName = $_GET['article-name'];
$itemId = $_GET['item-id'];
$scrollY = $_GET['scroll-y'];


//first, check if the group, article, or item are deleted.
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
            header("Location: ../../forum-articles.php?deletedOrClosedArticle&group-id=".$groupId."&group-name=".$groupName);
            exit();
        }
    }
}

$sql = "select * from forumitem fi where fi.id = ".$itemId.";";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) == 1){
    while($row = mysqli_fetch_assoc($result)){
        if($row['isDeleted'] == 1){ 
            header("Location: ../../forum-article.php?deletedItem&group-id=".$groupId."&group-name=".$groupName."&article-name=".$articleName."&article-id=".$articleId."&scrollY=".$scrollY);
            exit();
        }
    }
}

$groupName = $_GET['group-name'];
$userOpinion = $_GET['user-opinion'];
$userId = $_SESSION['userId'];

//a user cannot both like and dislike an article. They can only do one at a time. 
$sql = "select * from forumitem_userslikes_bridge fiub where fiub.itemId=".$itemId." and fiub.userId=".$userId.";";
$bridgeSql;
$itemSql;

$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) == 1){ //the user has interacted with this article before.
    while($row = mysqli_fetch_assoc($result)){
        //capture conflicts first
        if($userOpinion == "like" && $row['dislikesArticle']){ //if the user likes after having disliked
            $bridgeSql = "update forumitem_userslikes_bridge fiub set fiub.likesArticle=1, fiub.dislikesArticle=0 where fiub.itemId=".$itemId." and fiub.userId=".$userId.";";
            $itemSql = "update forumItem fi set fi.numberDislikes=fi.numberDislikes-1, fi.numberLikes=fi.numberLikes+1 where fi.id=".$itemId.";";
        } else if($userOpinion == "like"){
            $bridgeSql = "update forumitem_userslikes_bridge fiub set fiub.likesArticle=1 where fiub.itemId=".$itemId." and fiub.userId=".$userId.";";
            $itemSql = "update forumItem fi set fi.numberLikes=fi.numberLikes+1 where fi.id=".$itemId.";";
        }
        if($userOpinion == "dislike" && $row['likesArticle']){ //if the user dislikes after having liked
            $bridgeSql = "update forumitem_userslikes_bridge fiub set fiub.likesArticle=0, fiub.dislikesArticle=1 where fiub.itemId=".$itemId." and fiub.userId=".$userId.";";
            $itemSql = "update forumItem fi set fi.numberDislikes=fi.numberDislikes+1, fi.numberLikes=fi.numberLikes-1 where fi.id=".$itemId.";";
        } else if($userOpinion == "dislike"){
            $bridgeSql = "update forumitem_userslikes_bridge fiub set fiub.dislikesArticle=1 where fiub.itemId=".$itemId." and fiub.userId=".$userId.";";
            $itemSql = "update forumItem fi set fi.numberDislikes=fi.numberDislikes+1 where fi.id=".$itemId.";";
        }
        //if the user is already disliked/liked and presses the same button... reverse the dislike/like.
        if($userOpinion == "liked"){
            $bridgeSql = "update forumitem_userslikes_bridge fiub set fiub.likesArticle=0 where fiub.itemId=".$itemId." and fiub.userId=".$userId.";";
            $itemSql = "update forumItem fi set fi.numberLikes=fi.numberLikes-1 where fi.id=".$itemId.";";
        }
        if($userOpinion == "disliked"){
            $bridgeSql = "update forumitem_userslikes_bridge fiub set fiub.dislikesArticle=0 where fiub.itemId=".$itemId." and fiub.userId=".$userId.";";
            $itemSql = "update forumItem fi set fi.numberDislikes=fi.numberDislikes-1 where fi.id=".$itemId.";";
        }
    }
}else {
    //the user hasn't interacted with the article before, so we have to create a new row in the bridge table.
    //This means that the user can only either like or dislike a page
    if($userOpinion == "like"){
        $bridgeSql = "insert into forumitem_userslikes_bridge (articleId, userId, likesArticle, dislikesArticle) values (".$articleId.", ".$userId.", 1, 0);"; //there is no user input, so we'll just query
        $itemSql = "update forumItem fi set fi.numberLikes=fi.numberLikes+1 where fi.id=".$itemId.";";
    }else if($userOpinion == "dislike"){
        $bridgeSql = "insert into forumitem_userslikes_bridge (articleId, userId, likesArticle, dislikesArticle) values (".$articleId.", ".$userId.", 0, 1);";
        $itemSql = "update forumItem fi set fi.numberDislikes=fi.numberDislikes+1 where fi.id=".$itemId.";";
    }
}

//if they aren't empty, execute the queries... If they ARE empty, return an unexpected error message.
if(isset($bridgeSql)){
    //there is never a case where only one of the queries is set, so we don't have to check the other.
    //execute the bridgeSql first.
    $bridgeResult = mysqli_query($conn, $bridgeSql);
    $articleResult = mysqli_query($conn, $itemSql);
    header("Location: ../../forum-article.php?interactionSuccess&group-id=".$groupId."&group-name=".$groupName."&article-name=".$articleName."&article-id=".$articleId."&scrollY=".$scrollY);
    exit();
}else{
    header("Location: ../../forum-article.php?interactionError&group-id=".$groupId."&group-name=".$groupName."&article-name=".$articleName."&article-id=".$articleId."&scrollY=".$scrollY);
    exit();
}
echo $bridgeSql;
echo "<br>";
echo $itemSql;