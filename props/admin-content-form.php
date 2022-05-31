<?php
$numRows = mysqli_num_rows($result);
if($numRows >= 1){
    while($row = mysqli_fetch_assoc($result)){
        if($row['admin']){
            if($formType=="gallery")
            {
                echo '<form class="admin-content-form" action="./includes/gallery/gallery-upload.inc.php" method="POST" enctype="multipart/form-data">';
            }else if ($formType=="forum-group"){
                echo '<form class="admin-content-form" action="./includes/forums/forum-group-upload.inc.php" method="POST" enctype="multipart/form-data">';
            }

            if(isset($_GET['filename'])){
                echo '<input type="text" name="filename" placeholder="File name..." value="'.$_GET['filename'].'">';
            } else {
                echo '<input type="text" name="filename" placeholder="File name...">';
            }

            if($formType=="gallery"){
                if(isset($_GET['filetitle'])){
                    echo '<input type="text" name="filetitle" placeholder="Image title..." value="'.$_GET['filetitle'].'">';
                } else {
                    echo '<input type="text" name="filetitle" placeholder="Image title...">';           
                }
                if(isset($_GET['filedesc'])){
                    echo '<input type="text" name="filedesc" placeholder="Image description..." value="'.$_GET['filedesc'].'">';
                } else {
                    echo '<input type="text" name="filedesc" placeholder="Image description...">';
                }
            } else if ($formType="forum-group"){
                if(isset($_GET['filetitle'])){
                    echo '<input type="text" name="forumtitle" placeholder="Forum title..." value="'.$_GET['forumtitle'].'">';
                } else {
                    echo '<input type="text" name="forumtitle" placeholder="Forum title...">';           
                }
                if(isset($_GET['filedesc'])){
                    echo '<input type="text" name="forumdesc" placeholder="Forum description..." value="'.$_GET['forumdesc'].'">';
                } else {
                    echo '<input type="text" name="forumdesc" placeholder="Forum description...">';
                }
            }

            echo '<input type="file" name="file">
            <button type="submit" name="submit-post">Post</button>';


            //get the response message inside of the form area.
            if(isset($_GET['error'])){
                $errorMessage = $_GET['error'];
                if($errorMessage === "emptyInput") {
                    echo "<p class='error'>Some form inputs were left empty.</p>";
                }
                if($errorMessage === "stmtError") {
                    echo "<p class='error'>Server statement error. Try again!</p>";
                }
                if($errorMessage === "secondStmtError") {
                    echo "<p class='error'>Server statement error. Try again!</p>";
                }
                if($errorMessage === "exceedsFileSize") {
                    echo "<p class='error'>The image file used exceeds the size of 20 MB.</p>";
                }
                if($errorMessage === "improperExtension") {
                    echo "<p class='error'>The extension used was not a .png, .jpg, or .jpeg file.</p>";
                }
                if($errorMessage === "errorUploading") {
                    echo "<p class='error'>There was a server error trying to upload the file. Try again!</p>";
                }
            } else if (isset($_GET['success'])){
                echo "<p class='success'>The post was successfully made!</p>";
            }
            echo '</form>';
        }
    }
}