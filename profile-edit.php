<?php
    include_once './props/header.php';
?>
<div class="current-page-profile">
    <?php
        $edit = true;
        include_once './props/profile-header.php';
    ?> 
    <div class="profile-body"> 
        <hr class="profile-hr">
        <div class="settings-container">
            <div class="profile-settings">
                <form class="text-settings" action="./includes/updateProfile.inc.php" method="POST">
                    <?php
                        //no input validation needed.
                        $sql = "select * from profile pr where userId=".$_SESSION['userId'].";";
                        $result = mysqli_query($conn, $sql);

                        if(mysqli_num_rows($result) === 0){
                            echo "<input type='text' name='bioName' placeholder='Name...'>";
                            echo "<input type='text' name='bioTitle' placeholder='Title...'>";
                            echo "<input type='text' name='bioDesc' placeholder='Bio...'>";
                        } else {
                            $row; 
                            while($row = mysqli_fetch_assoc($result)){
                                $name = $row['bioName'];
                                $title = $row['bioTitle'];
                                $description = $row['bioDesc'];
                                echo "<input type='text' name='bioName' placeholder='Name...' value='$name'>";
                                echo "<input type='text' name='bioTitle' placeholder='Title...' value='$title'>";
                                echo "<input type='text' name='bioDesc' placeholder='Bio...' value='$description'>";
                            }
                        }
                    ?>
                    <button type="submit" name="submit-text">Submit</button>
                </form>
                <?php
                    if(isset($_GET['successtext'])){
                        echo "<p class='success'>Successfully changed profile!</p>";
                    }
                ?>
            </div>  
            <div class="profile-settings">
                <form action="./includes/uploadImage.inc.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="file">
                    <button type="submit" name="submit-upload">Upload</button>
                </form>
                <form action="./includes/deleteImage.inc.php" method="POST">
                    <button type="submit" name="submit-delete">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
    include_once './props/footer.php';
?>