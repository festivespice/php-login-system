<?php
    include_once './props/header.php';
    if(!isset($_SESSION['userId'])){ //if the user isn't logged in, a database connection isn't used. We'll use one here.
        include_once './includes/dbh.inc.php';
        include_once './includes/functions.inc.php';
    }
    //display the original article post

    //Then show all of the items which are in reply to the post. Order by date (make sure to use date objects), and then by popularity

    //Then, for each post, check if there are replies to the post. If there are, then create a new indentation to indicate replies. This will probably be recursive. If there are more than 2 replies, add a "load x comments" button.

    //For each post or item, allow users to like or dislike the item. Also, allow moderators and admins to delete/restore an intl_get_error_message

    //Allow a post owner to pin posts, so that they're ordered before 

    //Deleted items will be marked in the database as deleted and will simply display deleted post in both the post's body and username.
?>
    <div class="currentPage">
        <div class="article-header">
        <?php
            $power = "";
            $groupName = $_GET['group-name'];
            $groupId = $_GET['group-id'];
            $articleId = $_GET['article-id'];
            $articleName = $_GET['article-name'];
            $userId = 0;
            
            if(isset($_SESSION['userId'])){
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
            $sql = "select * from forumarticle fa where fa.id=".$articleId.";";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) >= 1){
                while($row = mysqli_fetch_assoc($result)){
                    if(!$row['isDeleted']){
                        if($row['isClosed']){
                            echo '<div class="forums-article-item closed">';
                        }else{
                            echo '<div class="forums-article-item">';
                        }
                            if(!empty($row['imageFullName'])){
                                echo '<div style="background-image: url(\'./image/forum-articles/'.$row['imageFullName'].'\');" class="item-image"></div>';
                            }
                            echo "<div class='article-item-text-container'>";
                                echo "<div class='article-item-text'>";
                                    echo '<h2>'.$row['title'].'</h2>';
                                    if(!empty($row['description'])){
                                        echo '<p>'.$row['description'].'</p>';
                                    }
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
                                echo '</div>';

                                
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
                                                    echo '<button id="liked" class="article-button" onclick="userLikeDislike('.$groupId.', \''.$groupName.'\', '.$row['id'].', '.$userId.', this.id)">Liked</button>';
                                                }else{
                                                    echo '<button id="like" class="article-button" onclick="userLikeDislike('.$groupId.', \''.$groupName.'\', '.$row['id'].', '.$userId.', this.id)">Like</button>';
                                                }
                                            }else{
                                                echo '<button id="like" class="article-button" onclick="userLikeDislike('.$groupId.', \''.$groupName.'\', '.$row['id'].', '.$userId.', this.id)">Like</button>';
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
                                                    echo '<button id="disliked" class="article-button" onclick="userLikeDislike('.$groupId.', \''.$groupName.'\', '.$row['id'].', '.$userId.', this.id)">Disliked</button>';
                                                }else{
                                                    echo '<button id="dislike" class="article-button" onclick="userLikeDislike('.$groupId.', \''.$groupName.'\', '.$row['id'].', '.$userId.', this.id)">Dislike</button>';
                                                }
                                            }else{
                                                echo '<button id="dislike" class="article-button" onclick="userLikeDislike('.$groupId.', \''.$groupName.'\', '.$row['id'].', '.$userId.', this.id)">Dislike</button>';
                                            }
                                        }else{
                                            echo '<p class="article-button">Dislikes: '.$row['numberDislikes'].'</p>';
                                        }
                                    echo '</div>';
                                    echo "<p>Comments: ".$row['numberComments']."</p>"; 
                                echo '</div>';
                            echo "</div>";
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
                    }
                }
            }else{
                echo "<p>Database error trying to receive original post...</p>";
            }            
        ?>
        </div>
    </div>
    <script src="./js/adminButtons.js"></script>
    <script src="./js/forumArticleInteractions.js"></script>
<?php
    include_once './props/footer.php';
?>