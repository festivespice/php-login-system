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
        <?php
        echo '<div class="article-header">';
            $power = "";
            $groupName = $_GET['group-name'];
            $groupId = $_GET['group-id'];
            $articleId = $_GET['article-id'];
            $articleName = $_GET['article-name'];
            $userId = 0;
            $isClosed = true;
            
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
                    $isClosed = $row['isClosed'];
                    if(!$row['isDeleted']){
                        if($row['isClosed']){
                            echo '<div class="forums-article-item closed">';
                        }else{
                            echo '<div class="forums-article-item">';
                        }
                            if(!empty($row['imageFullName'])){
                                echo '<div>';
                                    echo '<div style="background-image: url(\'./image/forum-articles/'.$row['imageFullName'].'\');" class="item-image"></div>';
                                echo '</div>';
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
                                                    echo '<button id="liked" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', \''.$articleName.'\', '.$userId.', this.id, \'article\')">Liked</button>';
                                                }else{
                                                    echo '<button id="like" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', \''.$articleName.'\', '.$userId.', this.id, \'article\')">Like</button>';
                                                }
                                            }else{
                                                echo '<button id="like" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', \''.$articleName.'\', '.$userId.', this.id, \'article\')">Like</button>';
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
                                                    echo '<button id="disliked" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', \''.$articleName.'\', '.$userId.', this.id, \'article\')">Disliked</button>';
                                                }else{
                                                    echo '<button id="dislike" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', \''.$articleName.'\', '.$userId.', this.id, \'article\')">Dislike</button>';
                                                }
                                            }else{
                                                echo '<button id="dislike" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', \''.$articleName.'\', '.$userId.', this.id, \'article\')">Dislike</button>';
                                            }
                                        }else{
                                            echo '<p class="article-button">Dislikes: '.$row['numberDislikes'].'</p>';
                                        }
                                    echo '</div>';
                                    echo '<div class="button-number">';
                                        echo '<div>'.$row['numberComments'].' replies</div>';
                                        echo '<button id="0" class="article-button" onclick="openHiddenForm(this.id, \'article\')">Reply</button>';
                                    echo '</div>';
                                echo '</div>';
                            echo "</div>";
                            if($power == 'admin' || $power == 'moderator' || $userId == $row['userId']){ //somehow, we need to allow users to delete their own articles
                                echo '<div class="button-number">';
                                    if($row['isDeleted']){//will be 1 or true or is deleted
                                        echo '<button id="restore" class="power-delete article-button" onclick="administrativePageItem('.$row['id'].', '.$userId.', '.$row['forumGroupId'].', \''.$groupName.'\', \''.$row['title'].'\', this.id)">Restore</button>'; //the quotes magic here is just for escaping and putting quotes around a string.
                                    }else{
                                        echo '<button id="delete" class="power-delete article-button" onclick="administrativePageItem('.$row['id'].', '.$userId.', '.$row['forumGroupId'].', \''.$groupName.'\', \''.$row['title'].'\', this.id)">Delete</button>'; //the quotes magic here is just for escaping and putting quotes around a string.
                                    }

                                    if($row['isClosed']){
                                        echo '<button id="open" class="power-delete article-button" onclick="administrativePageItem('.$row['id'].', '.$userId.', '.$row['forumGroupId'].', \''.$groupName.'\', \''.$row['title'].'\', this.id)">Open</button>';
                                    }else{
                                        echo '<button id="close" class="power-delete article-button" onclick="administrativePageItem('.$row['id'].', '.$userId.', '.$row['forumGroupId'].', \''.$groupName.'\', \''.$row['title'].'\', this.id)">Close</button>';
                                    }
                                echo '</div>';
                            }
                        echo '</div>';
                    }
                }
            }else{
                echo "<p>Database error trying to receive original post...</p>";
            }  
        echo '</div>';     
        if(isset($isClosed)){
            if(!$isClosed && isset($_SESSION['userId'])){
                $replyArticleId = 0;
                include_once './props/item-content-form.php';
            }
        }
        ?>

        <?php 
        //load all comments that have null for replyItemId (all comments replying exclusively to the article)
        //for all comments that are loaded, if their replies are loaded, then create a new items-supergroup to indicate that a string of replies are towards a specific item. 
            echo '<div class="items-supergroup">';
                $sql = "select *, FROM_UNIXTIME(fi.dateCreated) as datetime from forumitem fi where fi.forumArticleId=".$articleId." order by fi.orderNumber desc;";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) >= 1){
                    while($row = mysqli_fetch_assoc($result)){
                        echo '<div class="item-container">';
                            //for each item, create a user profile box.
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
                            echo '<div class="profile-header-box"><p class="profile-text">'.$userName.'</p><img class="profile-img" src="'. $profileImageUrl.'?'.mt_rand().'"><p class="date">'.$row['datetime'].'</p></div>';
                            echo '<div class="item-body">';
                                if($row['isDeleted']){
                                    echo '<div class="article-item-text-container">';
                                    echo '<p>This content has been deleted</p>';
                                }else{
                                    if(!empty($row['imageFullName'])){
                                        echo '<div>';
                                            echo '<div style="background-image: url(\'./image/forum-items/'.$row['imageFullName'].'\');" class="item-comment-image"></div>';
                                        echo '</div>';
                                    }
                                    echo '<div class="article-item-text-container">';
                                    echo '<p class="item-comment-content">'.$row['text'].'</p>';
                                }

                                echo '<div class="item-footer">';
                                $userLikes;
                                $userDislikes;
                                if($userId != 0){
                                    $userSql = "select * from forumitem_userslikes_bridge fiub where fiub.itemId=".$row['id']." and fiub.userId=".$userId.";";
                                    $userResult = mysqli_query($conn, $userSql);
                                    if(mysqli_num_rows($userResult) == 1){
                                        while($userRow = mysqli_fetch_assoc($userResult)){
                                            $userLikes = $userRow['likesItem'];
                                            $userDislikes = $userRow['dislikesItem'];
                                        }
                                    }
                                }

                                echo '<div class="button-number">';
                                    if(!$row['isDeleted'] || $userId != 0){ //if the article is closed, just show the number of likes/dislikes
                                        echo '<p>'.$row['numberLikes'].'</p>';
                                        if(isset($userLikes)){ //if we have a row to work with, display like/liked accordingly. If not, just display "like".  
                                            if($userLikes){ 
                                                echo '<button id="liked" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', \''.$articleName.'\', '.$userId.', this.id, \'article\')">Liked</button>';
                                            }else{
                                                echo '<button id="like" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', \''.$articleName.'\', '.$userId.', this.id, \'article\')">Like</button>';
                                            }
                                        }else{
                                            echo '<button id="like" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', \''.$articleName.'\', '.$userId.', this.id, \'article\')">Like</button>';
                                        }
                                    } else {
                                        echo '<p class="article-button">Likes: '.$row['numberLikes'].'</p>';
                                    }
                                echo '</div>';
                                echo '<div class="button-number">';
                                    if(!$row['isDeleted'] || $userId != 0){ 
                                        echo '<p>'.$row['numberDislikes'].'</p>';
                                        if(isset($userDislikes)){
                                            if($userDislikes){
                                                echo '<button id="disliked" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', \''.$articleName.'\', '.$userId.', this.id, \'article\')">Disliked</button>';
                                            }else{
                                                echo '<button id="dislike" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', \''.$articleName.'\', '.$userId.', this.id, \'article\')">Dislike</button>';
                                            }
                                        }else{
                                            echo '<button id="dislike" class="article-button" onclick="userLikeDislikeArticle('.$groupId.', \''.$groupName.'\', '.$row['id'].', \''.$articleName.'\', '.$userId.', this.id, \'article\')">Dislike</button>';
                                        }
                                    }else{
                                        echo '<p class="article-button">Dislikes: '.$row['numberDislikes'].'</p>';
                                    }
                                echo '</div>';
                                echo '<div class="button-number">';
                                    echo '<div>'.$row['numberComments'].' replies</div>';
                                    if(!$row['isDeleted']){
                                        echo '<button id="'.$row['id'].'" class="article-button" onclick="openHiddenForm(this.id, \'item\')">Reply</button>';
                                    }
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            echo '</div>';
                            
                            //in the footer, check if the user viewing the page is logged in. If they are, see if they have liked/disliked the item. 
                            
                            
                            //If the item isn't deleted, allow users to reply to it.
                            $itemId = $row['id'];
                            if(!$row['isDeleted'] && isset($_SESSION['userId'])){
                                include_once './props/item-content-form.php';
                            }
                            //Even if an item is deleted, show replies...
                            if($row['numberComments'] >= 1){
                                echo '<div class="item-comments">';
                                    echo '<p>Load '.$row['numberComments'].' replies</p>';
                                echo '</div>';
                            }

                            //admin buttons
                            // if($power == 'admin' || $power == 'moderator' || $userId == $row['userId']){ //somehow, we need to allow users to delete their own articles
                            //     echo '<div class="button-number">';
                            //         if($row['isDeleted']){//will be 1 or true or is deleted
                            //             echo '<button id="restore" class="power-delete article-button" onclick="administrativePageArticle('.$row['id'].', '.$userId.', '.$row['forumGroupId'].', \''.$groupName.'\', \''.$row['title'].'\', this.id)">Restore</button>'; //the quotes magic here is just for escaping and putting quotes around a string.
                            //         }else{
                            //             echo '<button id="delete" class="power-delete article-button" onclick="administrativePageArticle('.$row['id'].', '.$userId.', '.$row['forumGroupId'].', \''.$groupName.'\', \''.$row['title'].'\', this.id)">Delete</button>'; //the quotes magic here is just for escaping and putting quotes around a string.
                            //         }
                            //     echo '</div>';
                            // }
                        echo '</div>';
                    }
                }else{
                    echo "<p>No replies yet!</p>";
                }
            echo '</div>';
        ?>
        <div class="items-supergroup"> <!-- Load all items for this article that aren't replies to other items on this article -->
            <div class="item-container">
                <div class="item-body">
                    <div>
                        <div class="item-comment-image"></div>
                    </div>
                    <div class="item-comment-content">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
                </div>
                <div class="item-footer">
                    <div class="item-user-profile">Rafael</div>
                    <div class="button-number">
                        <div>0 Likes</div>
                        <button id="like" class="article-button">Like</button>
                    </div>
                    <div class="button-number">
                        <div>0 Dislikes</div>
                        <button id="dislike" class="article-button">Dislike</button>
                    </div>
                    <div class="button-number">
                        <div>0 Comments</div>
                        <button id="comment" class="article-button">Comment</button>
                    </div>
                    <!-- Add a "reply" button. This should reveal a hidden form that can be used to create a reply item. --> 
                </div>
                <?php
                    $isClosed = true;
                    if(!$isClosed && isset($_SESSION['userId'])){
                        include_once './props/item-content-form.php';
                    }
                ?>
                <div class="item-comments"> <!-- If the number of comments is  > 0 then allow the user to have a choice to load them (they should be stored in an array)-->
                    <p>Load 2 comments...</p>
                </div>
            </div>
        </div>
    </div>
    <script src="./js/adminButtons.js"></script>
    <script src="./js/forumArticleInteractions.js"></script>
<?php
    include_once './props/footer.php';
?>