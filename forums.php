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
            echo "Nothing popular yet!";  
        ?>
        </div>
        <div class="forums-supergroup"> <!-- Favorites -->
        <?php
            $sql = "select * from forumgroup_userfavorites_bridge fubr where fubr.userId=".$_SESSION['userId'].";";
            $favoritesResult = mysqli_query($conn, $sql);
            if(mysqli_num_rows($favoritesResult) >= 1){
                while($favoritesRow = mysqli_fetch_assoc($favoritesResult)){
                    $sql = "select * from forumgroup fg where fg.id=".$favoritesRow['forumGroupId'].";";
                    $articlesResult = mysqli_query($conn, $sql);
                    if(mysqli_num_rows($articlesResult) == 1){ 
                        while($articlesRow = mysqli_fetch_assoc($articlesResult)){
                            echo '<div class="forums-group">';
                            if(!empty($articlesRow['imageFullName'])){
                                echo '<div style="background-image: url(\'./image/forum-groups/'.$articlesRow['imageFullName'].'\');" class="group-image"></div>';
                            }
                            echo "<div class='forum-link-container'>";
                            echo "<a href='forum-articles.php?group-id=".$articlesRow['id']."&group-name=".$articlesRow['title']."' class='group-text'>";
                            echo '<h2>'.$articlesRow['title'].'</h2>';
                            if(!empty($articlesRow['description'])){
                                echo '<p>'.$articlesRow['description'].'</p>';
                            }
                            echo '</a>';
                            echo '<button id="favorite-forum-group" class="favorite-group" onclick="favoriteGroup('.$articlesRow['id'].', '.$_SESSION['userId'].')">Un-favorite</button>';                    
                            echo "</div>";
                            echo '</div>';
                        }
                    }    
                }
            }else{
                echo "<p>Nothing in your favorites yet!</p>";
            }                
            ?>
        </div>
        <div class="forums-supergroup"> <!-- Everything -->
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

                $sql = "select * from forumgroup fg order by fg.orderNumber";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) >= 1){
                    while($row = mysqli_fetch_assoc($result)){
                        if(!$row['isDeleted']){
                            if($row['isClosed']){
                                echo '<div class="forums-group closed">';
                            }else{
                                echo '<div class="forums-group">';
                            }
                            if(!empty($row['imageFullName'])){
                                echo '<div style="background-image: url(\'./image/forum-groups/'.$row['imageFullName'].'\');" class="group-image"></div>';
                            }
                            echo "<div class='forum-link-container'>";
                            echo "<a href='forum-articles.php?group-id=".$row['id']."&group-name=".$row['title']."' class='group-text'>";
                            echo '<h2>'.$row['title'].'</h2>';
                            if(!empty($row['description'])){
                                echo '<p>'.$row['description'].'</p>';
                            }
                            echo '</a>';
                            $sql = "select * from forumgroup_userfavorites_bridge fubr where fubr.forumGroupId=".$row['id']." and fubr.userId=".$_SESSION['userId'].";";
                            $favoritesResult = mysqli_query($conn, $sql);
                            if(mysqli_num_rows($favoritesResult) == 1) { //assume to be favorited, true
                                echo '<button id="favorite-forum-group" class="favorite-group" onclick="favoriteGroup('.$row['id'].', '.$_SESSION['userId'].')">Un-favorite</button>';
                            } else {
                                echo '<button id="favorite-forum-group" class="favorite-group" onclick="favoriteGroup('.$row['id'].', '.$_SESSION['userId'].')">Favorite</button>';
                            }
                            echo "</div>";
                            if($power == 'admin'){
                                echo '<div class="button-number">';
                                    if($row['isDeleted']){//will be 1 or true or is deleted
                                        echo '<button id="restore" class="power-delete" onclick="administrativePage('.$row['userId'].', '.$row['id'].', \''.$row['title'].'\', this.id)">Delete</button>'; //the quotes magic here is just for escaping and putting quotes around a string.
                                    }else{
                                        echo '<button id="delete" class="power-delete" onclick="administrativePage('.$row['userId'].', '.$row['id'].', \''.$row['title'].'\', this.id)">Delete</button>'; //the quotes magic here is just for escaping and putting quotes around a string.
                                    }
    
                                    if($row['isClosed']){
                                        echo '<button id="open" class="power-delete" onclick="administrativePage('.$row['userId'].', '.$row['id'].', \''.$row['title'].'\', this.id)">Open</button>';
                                    }else{
                                        echo '<button id="close" class="power-delete" onclick="administrativePage('.$row['userId'].', '.$row['id'].', \''.$row['title'].'\', this.id)">Close</button>';
                                    }
                                echo '</div>';
                            }
                            echo '</div>';
                        }else if($power == "admin"){
                            if($row['isClosed']){
                                echo '<div class="forums-group closed">';
                            }else{
                                echo '<div class="forums-group">';
                            }
                            echo "<h3>Regular users or moderators cannot see this: ".$row['title']." is 'deleted'</h3>";
                            if(!empty($row['imageFullName'])){
                                echo '<div style="background-image: url(\'./image/forum-groups/'.$row['imageFullName'].'\');" class="group-image"></div>';
                            }
                            echo "<div class='forum-link-container'>";
                            echo "<a href='forum-articles.php?group-id=".$row['id']."&group-name=".$row['title']."' class='group-text'>";
                            echo '<h2>'.$row['title'].'</h2>';
                            if(!empty($row['description'])){
                                echo '<p>'.$row['description'].'</p>';
                            }
                            echo '</a>';
                            $sql = "select * from forumgroup_userfavorites_bridge fubr where fubr.forumGroupId=".$row['id']." and fubr.userId=".$_SESSION['userId'].";";
                            $favoritesResult = mysqli_query($conn, $sql);
                            if(mysqli_num_rows($favoritesResult) == 1) { //assume to be favorited, true
                                echo '<button id="favorite-forum-group" class="favorite-group" onclick="favoriteGroup('.$row['id'].', '.$_SESSION['userId'].')">Un-favorite</button>';
                            } else {
                                echo '<button id="favorite-forum-group" class="favorite-group" onclick="favoriteGroup('.$row['id'].', '.$_SESSION['userId'].')">Favorite</button>';
                            }
                            echo "</div>";
                            if($power == 'admin'){
                                echo '<div class="button-number">';
                                    if($row['isDeleted']){//will be 1 or true or is deleted
                                        echo '<button id="restore" class="power-delete" onclick="administrativePage('.$row['userId'].', '.$row['id'].', \''.$row['title'].'\', this.id)">Restore</button>'; //the quotes magic here is just for escaping and putting quotes around a string.
                                    }else{
                                        echo '<button id="delete" class="power-delete" onclick="administrativePage('.$row['userId'].', '.$row['id'].', \''.$row['title'].'\', this.id)">Delete</button>'; //the quotes magic here is just for escaping and putting quotes around a string.
                                    }
    
                                    if($row['isClosed']){
                                        echo '<button id="open" class="power-delete" onclick="administrativePage('.$row['userId'].', '.$row['id'].', \''.$row['title'].'\', this.id)">Open</button>';
                                    }else{
                                        echo '<button id="close" class="power-delete" onclick="administrativePage('.$row['userId'].', '.$row['id'].', \''.$row['title'].'\', this.id)">Close</button>';
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
<script src="./js/favoriteButton.js"></script>
<script src="./js/adminButtons.js"></script>
<?php
    include_once './props/footer.php';
?>