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
            // $sql = "select * from poweruser po where po.userId=".$_SESSION['userId'].";";
            // $result = mysqli_query($conn, $sql);
            // $formType="forum-group";
            // include_once './props/admin-content-form.php';
        ?>
        <div class="forums-supergroup"> <!-- Most popular today -->
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
        <div class="forums-supergroup"> <!-- Everything -->
            <?php
                $sql = "select * from forumarticle fa where fa.forumGroupId=".$_GET['group-id']." order by fa.orderNumber";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) >= 1){
                    while($row = mysqli_fetch_assoc($result)){
                        echo '<div class="forums-article">';
                        if(!empty($row['imageFullName'])){
                            echo '<div style="background-image: url(\'./image/forum-articles/'.$row['imageFullName'].'\');" class="article-image"></div>';
                        }
                        echo "<a href='forum-article.php?group-id=".$_GET['group-id']."&group-name=".$_GET['group-name']."&article-id=".$row['id']."&article-name=".$row['title']."' class='article-text'>";
                        echo '<h2>'.$row['title'].'</h2>';
                        if(!empty($row['description'])){
                            echo '<p>'.$row['description'].'</p>';
                        }
                        echo '</a>';
                        echo '<button class="like-article">Like</button>';
                        echo '<button class="dislike-article">Dislike</button>';
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