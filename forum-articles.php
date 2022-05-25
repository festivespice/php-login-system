<?php
    include_once './props/header.php';
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
            if(!$isClosed){
                include_once './props/user-content-form.php';
            }
        ?>
        <div class="articles-supergroup"> <!-- Most popular today -->
        <?php
                // $sql = "select * from forumgroup fg order by fg.orderNumber desc";
                // $result = mysqli_query($conn, $sql);
                // if(mysqli_num_rows($result) >= 1){
                //     while($row = mysqli_fetch_assoc($result)){
                //         echo '<div class="forums-group">';
                //         if(!empty($row['imageFullName'])){
                //             echo '<div class="fake-image" href="'.$row['imageFullName'].'"></div>';
                //         }
                //         echo '<div class="group-text">';
                //         echo '<h2>'.$row['title'].'</h2>';
                //         if(!empty($row['description'])){
                //             echo '<p>'.$row['description'].'</p>';
                //         }
                //         echo '<button class="favorite-group">Favorite</button>';
                //         echo '</div>';
                //         echo '</div>';
                //     }
                // }else{
                //     echo "<p>Nothing yet!</p>";
                // }       
            echo "Nothing popular yet!";     
            ?>
        </div>
        <div class="articles-supergroup"> <!-- Everything -->
            <?php
                $power;
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

                $groupName = $_GET['group-name'];
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
                                    echo '<div>';
                                        echo '<div class="button-number">';
                                            echo '<p>'.'0'.'</p>';
                                            echo '<button class="article-button">Like</button>';
                                        echo '</div>';
                                        echo '<div class="button-number">';
                                            echo '<p>'.'0'.'</p>';
                                            echo '<button class="article-button">Dislike</button>';
                                        echo '</div>';
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
                        }else if ($power == "admin" || $power == "moderator"){ //if you happen to be a moderator or higher... 
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
                                        echo '<div class="button-number">';
                                            echo '<p>'.'0'.'</p>';
                                            echo '<button class="article-button">Like</button>';
                                        echo '</div>';
                                        echo '<div class="button-number">';
                                            echo '<p>'.'0'.'</p>';
                                            echo '<button class="article-button">Dislike</button>';
                                        echo '</div>';
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
<?php
    include_once './props/footer.php';
?>