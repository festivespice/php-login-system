<?php
    include_once './props/header.php';
?>
<div class="currentPage">
    <div class="forums-header">
        <h1> Forums </h1>
        <p> 
            Posts about (specific subject) will be made here. 
            Treat other people how you would like to be treated. 
        </p>
    </div>
    <div class="forums-body">
        <?php
            $sql = "select * from poweruser po where po.userId=".$_SESSION['userId'].";";
            $result = mysqli_query($conn, $sql);
            $formType="forum-group";
            include_once './props/admin-content-form.php';
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
        <div class="forums-supergroup"> <!-- Favorites -->
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
                $sql = "select * from forumgroup fg order by fg.orderNumber desc";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) >= 1){
                    while($row = mysqli_fetch_assoc($result)){
                        echo '<div class="forums-group">';
                        if(!empty($row['imageFullName'])){
                            echo '<div style="background-image: url(\'./image/forum-groups/'.$row['imageFullName'].'\');" class="item-image"></div>';
                        }
                        echo '<div class="group-text">';
                        echo '<h2>'.$row['title'].'</h2>';
                        if(!empty($row['description'])){
                            echo '<p>'.$row['description'].'</p>';
                        }
                        echo '<button class="favorite-group">Favorite</button>';
                        echo '</div>';
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