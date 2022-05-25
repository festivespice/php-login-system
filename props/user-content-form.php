<?php
echo '<form class="user-content-form" action="./includes/forum-article-upload.inc.php?group-id='.$_GET['group-id'].'&group-name='.$_GET['group-name'].'" method="POST" enctype="multipart/form-data">';
if(isset($_GET['filename'])){
    echo '<input type="text" name="filename" placeholder="File name..." value="'.$_GET['filename'].'">';
} else {
    echo '<input type="text" name="filename" placeholder="File name...">';
}
if(isset($_GET['articletitle'])){
    echo '<input type="text" name="articletitle" placeholder="Article title..." value="'.$_GET['articletitle'].'">';
} else {
    echo '<input type="text" name="articletitle" placeholder="Article title...">';           
}
if(isset($_GET['articledesc'])){
    echo '<input type="text" name="articledesc" placeholder="Article description..." value="'.$_GET['articledesc'].'">';
} else {
    echo '<input type="text" name="articledesc" placeholder="Article description...">';
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
