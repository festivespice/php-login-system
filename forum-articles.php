<?php
    include_once './props/header.php';
    if(!isset($_SESSION['userId'])){ //if the user isn't logged in, a database connection isn't used. We'll use one here.
        include_once './includes/dbh.inc.php';
        include_once './includes/functions.inc.php';
    }
?>
<div class="currentPage">
    <div class="forums-header">
        <?php
            $sql = "select * from forumgroup fg where fg.id=".$_GET['group-id'].";";
            $result = mysqli_query($conn, $sql);
            $isClosed = 0;
            if(mysqli_num_rows($result) >= 1){
                while($row = mysqli_fetch_assoc($result)){
                    echo '<h1>'.$row['title'].'</h1>';
                    echo '<p>'.$row['description'].'</p>';
                    if($row['isClosed']){
                        $isClosed = $row['isClosed'];
                        echo '<h3>This forum group is closed, and none of its articles can be interacted with. However, they can still be viewed.</h3>';
                    }
                }
            }
        ?>
    </div>
    <div class="forums-body">
        <?php
            if(!$isClosed && isset($_SESSION['userId'])){
                include_once './props/user-content-form.php';
            }
        ?>
        <div class="articles-supergroup"> <!-- Most popular today -->
        <?php     
            echo "Nothing popular yet!";     
        ?>
        </div>
        <div class="articles-supergroup"> <!-- Everything -->
            <?php
                $power = "";
                $groupName = $_GET['group-name'];
                $groupId = $_GET['group-id'];
                $userId = 0;
                if(isset($_SESSION['userId'])){
                    $groupName = $_GET['group-name'];
                    $groupId = $_GET['group-id'];
                    $userId = $_SESSION['userId'];
                    $sql = "select * from poweruser pu where pu.userId=".$_SESSION['userId'];
                    $result = mysqli_query($conn, $sql);
                    if(mysqli_num_rows($result) >= 1){
                        while($row = mysqli_fetch_assoc($result)){
                            if($row['admin']){
                                $power = 'admin';
                            } else if ($row['moderator']){
                                $power = 'moderator';
                            }
                        }
                    }
                }

                //for the current forumgroup, grab all of the articles according to it. 
                $sql = "select * from forumarticle fa where fa.forumGroupId=".$_GET['group-id']." order by fa.orderNumber";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) >= 1){
                    while($row = mysqli_fetch_assoc($result)){
                        if(!$row['isDeleted']){
                            if($row['isClosed']){
                                echo '<div class="forums-article closed">';
                            }else{
                                echo '<div class="forums-article">';
                            }
                                if(!empty($row['imageFullName'])){
                                    echo '<div style="background-image: url(\'./image/forum-articles/'.$row['imageFullName'].'\');" class="article-image"></div>';
                                }
                                echo "<div class='article-link-container'>";
                                    echo "<a href='forum-article.php?group-id=".$_GET['group-id']."&group-name=".$_GET['group-name']."&article-id=".$row['id']."&article-name=".$row['title']."' class='article-text'>";
                                    echo '<h2>'.$row['title'].'</h2>';
                                    if(!empty($row['description'])){
                                        echo '<p>'.$row['description'].'</p>';
                                    }
                                    echo '</a>';

                                    //for each article, grab the profile image URL and the uid of the writer.
                                    $profileImageCheck = checkIfUserImageExists($conn, $row['userId']);
                                    $profileImageUrl = returnUserImagePath($profileImageCheck, $row['userId']);
                                    $userName = "user";
                                    $userSql = "select * from users u where u.id=".$row['userId'].";";
                                    $userResult = mysqli_query($conn, $userSql);
                                    if(mysqli_num_rows($userResult) == 1){
                                        while($userRow = mysqli_fetch_assoc($userResult)){
                                            $userName = $userRow['uid'];
                                        }
                                    }else{
                                        echo "<p>This shouldn't be happening.</p>";
                                    }
                                    //for each article, create a username/profile picture box.
                                    echo '<div class="profile-header-box"><p class="profile-text">'.$userName.'</p><img class="profile-img" src="'. $profileImageUrl.'?'.mt_rand().'"></div>'; 

                                    //for each article, show the number of likes/dislikes and comments. Also, provide the ability to do these things. 
                                    echo '<div>';
                                        if($userId != 0)
                                        {
                                            $userSql = "select * from forumarticle_userslikes_bridge faub where faub.articleId=".$row['id']." and faub.userId=".$userId.";";
                                            $userLikes;
                                            $userDislikes;
                                            $userResult = mysqli_query($conn, $userSql);
                                            if(mysqli_num_rows($userResult) == 1){
                                                while($userRow = mysqli_fetch_assoc($userResult)){
                                                    $userLikes = $userRow['likesArticle'];
                                                    $userDislikes = $userRow['dislikesArticle'];
                                                }
                                            }
                                        }

                                        echo '<div class="button-number">';
                                            if(!$row['isClosed'] || $userId != 0){ //if the article is closed, just show the number of likes/dislikes
                                                echo '<p>'.$row['numberLikes'].'</p>';
                                                if(isset($userLikes)){ //if we have a row to work with, display like/liked accordingly. If not, just display "like".  
                                                    if($userLikes){ 
                                                        echo '<button id="liked" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', \''.$row['title'].'\', '.$userId.', this.id, \'group\')">Liked</button>';
                                                    }else{
                                                        echo '<button id="like" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', \''.$row['title'].'\', '.$userId.', this.id, \'group\')">Like</button>';
                                                    }
                                                }else{
                                                    echo '<button id="like" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', \''.$row['title'].'\', '.$userId.', this.id, \'group\')">Like</button>';
                                                }
                                            } else {
                                                echo '<p class="article-button">Likes: '.$row['numberLikes'].'</p>';
                                            }
                                        echo '</div>';
                                        echo '<div class="button-number">';
                                            if(!$row['isClosed'] || $userId != 0){ 
                                                echo '<p>'.$row['numberDislikes'].'</p>';
                                                if(isset($userDislikes)){
                                                    if($userDislikes){
                                                        echo '<button id="disliked" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', \''.$row['title'].'\', '.$userId.', this.id, \'group\')">Disliked</button>';
                                                    }else{
                                                        echo '<button id="dislike" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', \''.$row['title'].'\', '.$userId.', this.id, \'group\')">Dislike</button>';
                                                    }
                                                }else{
                                                    echo '<button id="dislike" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', \''.$row['title'].'\', '.$userId.', this.id, \'group\')">Dislike</button>';
                                                }
                                            }else{
                                                echo '<p class="article-button">Dislikes: '.$row['numberDislikes'].'</p>';
                                            }
                                        echo '</div>';
                                        echo "<p>Comments: ".$row['numberComments']."</p>"; 
                                    echo '</div>';
                                echo "</div>";
                                echo '<div class="forum-comments-container">';
                                echo '</div>';
                                if($power == 'admin' || $power == 'moderator' || $userId == $row['userId']){ //somehow, we need to allow users to delete their own articles
                                    echo '<div class="button-number">';
                                        if($row['isDeleted']){//will be 1 or true or is deleted
                                            echo '<button id="restore" class="power-delete article-button" onclick="administrativePageArticle('.$row['id'].', '.$userId.', '.$row['forumGroupId'].', \''.$groupName.'\', \''.$row['title'].'\', this.id)">Restore</button>'; //the quotes magic here is just for escaping and putting quotes around a string.
                                        }else{
                                            echo '<button id="delete" class="power-delete article-button" onclick="administrativePageArticle('.$row['id'].', '.$userId.', '.$row['forumGroupId'].', \''.$groupName.'\', \''.$row['title'].'\', this.id)">Delete</button>'; //the quotes magic here is just for escaping and putting quotes around a string.
                                        }
    
                                        if($row['isClosed']){
                                            echo '<button id="open" class="power-delete article-button" onclick="administrativePageArticle('.$row['id'].', '.$userId.', '.$row['forumGroupId'].', \''.$groupName.'\', \''.$row['title'].'\', this.id)">Open</button>';
                                        }else{
                                            echo '<button id="close" class="power-delete article-button" onclick="administrativePageArticle('.$row['id'].', '.$userId.', '.$row['forumGroupId'].', \''.$groupName.'\', \''.$row['title'].'\', this.id)">Close</button>';
                                        }
                                    echo '</div>';
                                }
                            echo '</div>';
                        }else if ($power == "admin" || $power == "moderator"){ //if you happen to be a moderator or higher while the article is deleted... display anyways
                            if($row['isClosed']){
                                echo '<div class="forums-article closed">';
                            }else{
                                echo '<div class="forums-article">';
                            }
                            echo "<h3>Regular users or moderators cannot see this: ".$row['title']." is 'deleted'</h3>";
                                if(!empty($row['imageFullName'])){
                                    echo '<div style="background-image: url(\'./image/forum-articles/'.$row['imageFullName'].'\');" class="article-image"></div>';
                                }
                                echo "<div class='article-link-container'>";
                                    echo "<a href='forum-article.php?group-id=".$_GET['group-id']."&group-name=".$_GET['group-name']."&article-id=".$row['id']."&article-name=".$row['title']."' class='article-text'>";
                                    echo '<h2>'.$row['title'].'</h2>';
                                    if(!empty($row['description'])){
                                        echo '<p>'.$row['description'].'</p>';
                                    }
                                    echo '</a>';
                                    echo '<div>';
                                        $userSql = "select * from forumarticle_userslikes_bridge faub where faub.articleId=".$row['id']." and faub.userId=".$userId.";";
                                        $userLikes;
                                        $userDislikes;
                                        $userResult = mysqli_query($conn, $userSql);
                                        if(mysqli_num_rows($userResult) == 1){
                                            while($userRow = mysqli_fetch_assoc($userResult)){
                                                $userLikes = $userRow['likesArticle'];
                                                $userDislikes = $userRow['dislikesArticle'];
                                            }
                                        }

                                        echo '<div class="button-number">';
                                            if(!$row['isClosed']){ //if the article is closed, just show the number of likes/dislikes
                                                echo '<p>'.$row['numberLikes'].'</p>';
                                                if(isset($userLikes)){ //if we have a row to work with, display like/liked accordingly. If not, just display "like".  
                                                    if($userLikes){ 
                                                        echo '<button id="liked" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', '.$userId.', this.id)">Liked</button>';
                                                    }else{
                                                        echo '<button id="like" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', '.$userId.', this.id)">Like</button>';
                                                    }
                                                }else{
                                                    echo '<button id="like" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', '.$userId.', this.id)">Like</button>';
                                                }
                                            } else {
                                                echo '<p>Likes: '.$row['numberLikes'].'</p>';
                                            }
                                        echo '</div>';
                                        echo '<div class="button-number">';
                                            if(!$row['isClosed']){
                                                if(isset($userDislikes)){
                                                    echo '<p>'.$row['numberDislikes'].'</p>';
                                                    if($userDislikes){
                                                        echo '<button id="disliked" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', '.$userId.', this.id)">Disliked</button>';
                                                    }else{
                                                        echo '<button id="dislike" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', '.$userId.', this.id)">Dislike</button>';
                                                    }
                                                }else{
                                                    echo '<button id="dislike" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', '.$userId.', this.id)">Dislike</button>';
                                                }
                                            }else{
                                                echo '<p>Dislikes: '.$row['numberDislikes'].'</p>';
                                            }
                                        echo '</div>';
                                        echo "<p>Comments: ".$row['numberComments']."</p>"; 
                                    echo '</div>';
                                echo "</div>";
                                echo '<div class="forum-comments-container">';
                                echo '</div>';
                                if($power == 'admin' || $power == 'moderator'){ //somehow, we need to allow users to delete their own articles
                                    echo '<div class="button-number">';
                                        if($row['isDeleted']){//will be 1 or true or is deleted
                                            echo '<button id="restore" class="power-delete" onclick="administrativePageArticle('.$row['id'].', '.$row['userId'].', '.$row['forumGroupId'].', \''.$groupName.'\', \''.$row['title'].'\', this.id)">Restore</button>'; //the quotes magic here is just for escaping and putting quotes around a string.
                                        }else{
                                            echo '<button id="delete" class="power-delete" onclick="administrativePageArticle('.$row['id'].', '.$row['userId'].', '.$row['forumGroupId'].', \''.$groupName.'\', \''.$row['title'].'\', this.id)">Delete</button>'; //the quotes magic here is just for escaping and putting quotes around a string.
                                        }
    
                                        if($row['isClosed']){
                                            echo '<button id="open" class="power-delete" onclick="administrativePageArticle('.$row['id'].', '.$row['userId'].', '.$row['forumGroupId'].', \''.$groupName.'\', \''.$row['title'].'\', this.id)">Open</button>';
                                        }else{
                                            echo '<button id="close" class="power-delete" onclick="administrativePageArticle('.$row['id'].', '.$row['userId'].', '.$row['forumGroupId'].', \''.$groupName.'\', \''.$row['title'].'\', this.id)">Close</button>';
                                        }
                                    echo '</div>';
                                }
                            echo '</div>';
                        }
                    }
                }else{
                    echo "<p>Nothing yet!</p>";
                }            
            ?>
        </div>
    </div>
</div>
<script src="./js/adminButtons.js"></script>
<script src="./js/forumArticleInteractions.js"></script>

<?php
    include_once './props/footer.php';
?>