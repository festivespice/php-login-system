<?php
    include_once './props/header.php';
?>
<div class="current-page-profile">
    <div class="profile-header"> 
        <?php
            //profileimg.inc.php is included in header
            echo '<img class="profile-page-img" src="'.$imagePath.'?'.mt_rand().'">';
            echo '<div>';
            echo '<button href="./profile-edit.php" style="float:right;">Edit profile</button>';
            echo '<h1>'.$_SESSION['userName'].'</h1>';
            echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Tortor vitae purus faucibus ornare suspendisse sed nisi.</p>';
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