<?php
    include_once './props/header.php';
?>
<div class="currentPage">
    <div class="forums-header">
        <?php
            $sql = "select * from forumgroup fg where fg.id=".$_GET['group-id'].";";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) >= 1){
                while($row = mysqli_fetch_assoc($result)){
                    echo '<h1>'.$row['title'].'</h1>';
                    echo '<p>'.$row['description'].'</p>';
                }
            }
        ?>
    </div>
    <div class="forums-body">
        <?php
            include_once './props/user-content-form.php';
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
                $sql = "select * from forumarticle fa where fa.forumGroupId=".$_GET['group-id']." order by fa.orderNumber";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) >= 1){
                    while($row = mysqli_fetch_assoc($result)){
                        echo '<div class="forums-article">';
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
                            if($power == 'admin' || $power == 'moderator' || $row['userId'] === $_SESSION['userId']){
                                echo '<div class="button-number">';
                                    echo '<button class="power-delete">Delete</button>';
                                    echo '<button class="power-delete">Close</button>';
                                echo '</div>';
                            }
                        echo '</div>';
                    }
                }else{
                    echo "<p>Nothing yet!</p>";
                }            
            ?>
        </div>
    </div>
</div>
<?php
    include_once './props/footer.php';
?>