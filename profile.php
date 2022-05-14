<?php
    include_once './props/header.php';
?>
<div class="current-page-profile">
    <div class="profile-header"> 
        <?php
            //profileimg.inc.php is included in header
            echo '<img class="profile-page-img" src="'.$imagePath.'?'.mt_rand().'">';
            echo '<h1>'.$_SESSION['userName'].'</h1>';
            echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Tortor vitae purus faucibus ornare suspendisse sed nisi.</p>';
        ?>
    </div>
    <hr class="profile-hr">
    <div class="profile-image-settings">
        <form action="./includes/uploadImage.inc.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="file">
            <button type="submit" name="submit-upload">Upload</button>
        </form>
        <form action="./includes/deleteImage.inc.php" method="POST">
            <button type="submit" name="submit-delete">Delete</button>
        </form>
    </div>

</div>
<?php
    include_once './props/footer.php';
?>