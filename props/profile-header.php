<div class="profile-header"> 
        <?php
            //profileimg.inc.php is included in header
            if($edit == false){
                echo '<button class="edit-profile-btn"><a href="./profile-edit.php" style="float:right;">Edit profile</a></button>';
            } else {
                echo '<button class="edit-profile-btn"><a href="./profile.php" style="float:right;">Return</a></button>';
            }

            $sql = "select * from poweruser po where po.userId=".$_SESSION['userId'].";";
            $result = mysqli_query($conn, $sql);

            echo '<div class="profile-picture-container">';
            echo '<img class="profile-page-img" src="'.$imagePath.'?'.mt_rand().'">';
            if(mysqli_num_rows($result) >= 1){
                while($row = mysqli_fetch_assoc($result)){
                    if($row['admin']){
                        echo '<p class="profile-admin">Admin</p>';
                    }else if($row['moderator']){
                        echo '<p class="profile-moderator">Moderator</p>';
                    }
                }
            }
            echo '</div>';
            echo '<div class="profile-info-container">';

            $sql = "select * from profile pr where pr.userId='".$_SESSION['userId']."';";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) >= 1){
                while($row = mysqli_fetch_assoc($result)){
                    if(!empty($row['bioName'])){
                        echo '<h1>'.$row['bioName'].'</h1>';
                    }
                    if(!empty($row['bioTitle'])){
                        echo '<h2>'.$row['bioTitle'].'</h2>';
                    }
                    if(!empty($row['bioDesc'])){
                        echo '<p>'.$row['bioDesc'].'</p>';
                    }
                }
            } else {
                echo '<h1>'.$_SESSION['userName'].'</h1>';
            }
            
            echo '</div>';
        ?>
    </div>