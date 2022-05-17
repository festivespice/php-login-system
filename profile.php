<?php
    include_once './props/header.php';
?>
<div class="current-page-profile">
    <div class="profile-header"> 
        <?php
            //profileimg.inc.php is included in header
            echo '<img class="profile-page-img" src="'.$imagePath.'?'.mt_rand().'">';
            echo '<div>';
            echo '<button class="edit-profile-btn"><a href="./profile-edit.php" style="float:right;">Edit profile</a></button>';

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
    <div class="profile-body"> 
        <hr class="profile-hr">

    </div>
</div>
<?php
    include_once './props/footer.php';
?>