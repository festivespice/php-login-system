<?php
if(isset($_GET['success'])){
    if(isset($replyArticleId)){
        echo '<form id="article0" class="user-content-form" action="./includes/forums/forum-item-upload.inc.php?group-id='.$_GET['group-id'].'&group-name='.$_GET['group-name'].'&article-name='.$_GET['article-name'].'&article-id='.$_GET['article-id'].'" method="POST" enctype="multipart/form-data">';
    }else if(isset($replyItemId)){
        echo '<form id="item'.$replyItemId.'" class="user-content-form" action="./includes/forums/forum-item-upload.inc.php?group-id='.$_GET['group-id'].'&group-name='.$_GET['group-name'].'&article-name='.$_GET['article-name'].'&article-id='.$_GET['article-id'].'&item-id='.$_GET['item-id'].'" method="POST" enctype="multipart/form-data">';
    }
}else{
    if(isset($replyArticleId)){
        echo '<form id="article0" class="user-content-form hidden" action="./includes/forums/forum-item-upload.inc.php?group-id='.$_GET['group-id'].'&group-name='.$_GET['group-name'].'&article-name='.$_GET['article-name'].'&article-id='.$_GET['article-id'].'" method="POST" enctype="multipart/form-data">';
    }else if(isset($replyItemId)){
        echo '<form id="item'.$replyItemId.'" class="user-content-form hidden" action="./includes/forums/forum-item-upload.inc.php?group-id='.$_GET['group-id'].'&group-name='.$_GET['group-name'].'&article-name='.$_GET['article-name'].'&article-id='.$_GET['article-id'].'&item-id='.$_GET['item-id'].'" method="POST" enctype="multipart/form-data">';
    }
}


if(isset($_GET['filename'])){
    echo '<input type="text" name="filename" placeholder="Picture name..." value="'.$_GET['filename'].'">';
} else {
    echo '<input type="text" name="filename" placeholder="Picture name...">';
}
if(isset($_GET['item-content'])){
    echo '<input type="text" name="item-content" placeholder="What is on your mind?" value="'.$_GET['item-content'].'">';
} else {
    echo '<input type="text" name="item-content" placeholder="What is on your mind?">';
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
    if($errorMessage === "exceedsFileSize") {
        echo "<p class='error'>The image file used exceeds the size of 5 MB.</p>";
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

