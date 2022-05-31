<?php
//update both the bridge table and the article's total number of likes and dislikes
session_start();
include_once '../dbh.inc.php';
$groupId = $_GET['group-id'];
$articleId = $_GET['article-id'];

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
            header("Location: ../../forum-articles.php?deletedOrClosedArticle&group-id=".$groupId."&group-name=".$groupName."&scrollY=".$scrollY);
            exit();
        }
    }
}

$groupName = $_GET['group-name'];
$userOpinion = $_GET['user-opinion'];
$scrollY = $_GET['scroll-y'];
$userId = $_SESSION['userId'];

//a user cannot both like and dislike an article. They can only do one at a time. 
$sql = "select * from forumarticle_userslikes_bridge faub where faub.articleId=".$articleId." and faub.userId=".$userId.";";
$bridgeSql;
$articleSql;

$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) == 1){ //the user has interacted with this article before.
    while($row = mysqli_fetch_assoc($result)){
        //capture conflicts first
        if($userOpinion == "like" && $row['dislikesArticle']){ //if the user likes after having disliked
            $bridgeSql = "update forumarticle_userslikes_bridge faub set faub.likesArticle=1, faub.dislikesArticle=0 where faub.articleId=".$articleId." and faub.userId=".$userId.";";
            $articleSql = "update forumArticle fa set fa.numberDislikes=fa.numberDislikes-1, fa.numberLikes=fa.numberLikes+1 where fa.id=".$articleId.";";
        } else if($userOpinion == "like"){
            $bridgeSql = "update forumarticle_userslikes_bridge faub set faub.likesArticle=1 where faub.articleId=".$articleId." and faub.userId=".$userId.";";
            $articleSql = "update forumArticle fa set fa.numberLikes=fa.numberLikes+1 where fa.id=".$articleId.";";
        }
        if($userOpinion == "dislike" && $row['likesArticle']){ //if the user dislikes after having liked
            $bridgeSql = "update forumarticle_userslikes_bridge faub set faub.likesArticle=0, faub.dislikesArticle=1 where faub.articleId=".$articleId." and faub.userId=".$userId.";";
            $articleSql = "update forumArticle fa set fa.numberDislikes=fa.numberDislikes+1, fa.numberLikes=fa.numberLikes-1 where fa.id=".$articleId.";";
        } else if($userOpinion == "dislike"){
            $bridgeSql = "update forumarticle_userslikes_bridge faub set faub.dislikesArticle=1 where faub.articleId=".$articleId." and faub.userId=".$userId.";";
            $articleSql = "update forumArticle fa set fa.numberDislikes=fa.numberDislikes+1 where fa.id=".$articleId.";";
        }
        //if the user is already disliked/liked and presses the same button... reverse the dislike/like.
        if($userOpinion == "liked"){
            $bridgeSql = "update forumarticle_userslikes_bridge faub set faub.likesArticle=0 where faub.articleId=".$articleId." and faub.userId=".$userId.";";
            $articleSql = "update forumArticle fa set fa.numberLikes=fa.numberLikes-1 where fa.id=".$articleId.";";
        }
        if($userOpinion == "disliked"){
            $bridgeSql = "update forumarticle_userslikes_bridge faub set faub.dislikesArticle=0 where faub.articleId=".$articleId." and faub.userId=".$userId.";";
            $articleSql = "update forumArticle fa set fa.numberDislikes=fa.numberDislikes-1 where fa.id=".$articleId.";";
        }
    }
}else {
    //the user hasn't interacted with the article before, so we have to create a new row in the bridge table.
    //This means that the user can only either like or dislike a page
    if($userOpinion == "like"){
        $bridgeSql = "insert into forumarticle_userslikes_bridge (articleId, userId, likesArticle, dislikesArticle) values (".$articleId.", ".$userId.", 1, 0);"; //there is no user input, so we'll just query
        $articleSql = "update forumArticle fa set fa.numberLikes=fa.numberLikes+1 where fa.id=".$articleId.";";
    }else if($userOpinion == "dislike"){
        $bridgeSql = "insert into forumarticle_userslikes_bridge (articleId, userId, likesArticle, dislikesArticle) values (".$articleId.", ".$userId.", 0, 1);";
        $articleSql = "update forumArticle fa set fa.numberDislikes=fa.numberDislikes+1 where fa.id=".$articleId.";";
    }
}

//if they aren't empty, execute the queries... If they ARE empty, return an unexpected error message.
if(isset($bridgeSql)){
    //there is never a case where only one of the queries is set, so we don't have to check the other.
    //execute the bridgeSql first.
    $bridgeResult = mysqli_query($conn, $bridgeSql);
    $articleResult = mysqli_query($conn, $articleSql);
    header("Location: ../../forum-articles.php?interactionSuccess&group-id=".$groupId."&group-name=".$groupName."&scrollY=".$scrollY);
    exit();
}else{
    header("Location: ../../forum-articles.php?interactionError&group-id=".$groupId."&group-name=".$groupName."&scrollY=".$scrollY);
    exit();
}
echo $bridgeSql;
echo "<br>";
echo $articleSql;