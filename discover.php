<?php
    include_once './props/header.php';
?>
<div class="currentPage">
    <div class="gallery-header">
        <h1> Hello, this is stuff about us! </h1>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Diam maecenas ultricies mi eget. Et tortor consequat id porta. Volutpat diam ut venenatis tellus in metus vulputate eu scelerisque. Morbi non arcu risus quis varius. Lobortis feugiat vivamus at augue eget. Mauris cursus mattis molestie a iaculis at erat. Purus gravida quis blandit turpis cursus in hac. Sed cras ornare arcu dui vivamus arcu felis. Cum sociis natoque penatibus et magnis dis parturient montes. Massa tincidunt nunc pulvinar sapien et ligula ullamcorper malesuada. Neque gravida in fermentum et sollicitudin ac orci. Iaculis urna id volutpat lacus laoreet non curabitur gravida. Porttitor leo a diam sollicitudin tempor id eu nisl. Nunc vel risus commodo viverra maecenas accumsan lacus vel. Scelerisque fermentum dui faucibus in.
        </p><p>
            Aliquam sem et tortor consequat id porta nibh venenatis cras. Arcu dictum varius duis at. Libero enim sed faucibus turpis. Lorem ipsum dolor sit amet. Lacinia at quis risus sed vulputate odio ut enim blandit. Massa tincidunt nunc pulvinar sapien et ligula. Vel eros donec ac odio tempor. Venenatis urna cursus eget nunc scelerisque. Tincidunt ornare massa eget egestas purus viverra accumsan. Sollicitudin ac orci phasellus egestas tellus. Eget lorem dolor sed viverra ipsum nunc aliquet bibendum enim. Nec dui nunc mattis enim ut tellus elementum. Ut faucibus pulvinar elementum integer enim neque volutpat ac. Enim diam vulputate ut pharetra sit amet aliquam. Gravida arcu ac tortor dignissim convallis. Purus faucibus ornare suspendisse sed nisi lacus sed. Id venenatis a condimentum vitae sapien. Aliquam faucibus purus in massa tempor. Aenean vel elit scelerisque mauris. Ut aliquam purus sit amet luctus venenatis lectus magna.
        </p>

        <h1> What we're up to </h1>
    </div>
    <div class="gallery-body">
        <?php
        include_once './includes/dbh.inc.php';
        $sql = "select * from galleryitem gal order by gal.orderNumber desc;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            echo "SQL stmt error!";
        }else{
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $numRows = mysqli_num_rows($result);
            if($numRows >= 1){
                while($row = mysqli_fetch_assoc($result)){
                    echo '<div class="gallery-item-container">
                    <a href="#">
                        <div style="background-image: url(\'./image/gallery/'.$row['imageFullName'].'\');" class="item-image"></div>
                        <h3 class="item-title">'.$row['title'].'</h3>
                        <p class="item-desc">'.$row['description'].'</p>
                    </a>
                    </div>';
                }
            }else {
                echo "Nothing yet!";
            }
        }
        ?>
    </div>
    <?php
        if($_SESSION){
            if($_SESSION['userId'] === 1){ //1 is considered admin user, or include some other sort of user group...
                //build the form...
                echo '<form class="admin-gallery-form" action="./includes/gallery-upload.inc.php" method="POST" enctype="multipart/form-data">';
                if(isset($_GET['filename'])){
                    echo '<input type="text" name="filename" placeholder="File name..." value="'.$_GET['filename'].'">';
                } else {
                    echo '<input type="text" name="filename" placeholder="File name...">';
                }
                if(isset($_GET['filename'])){
                    echo '<input type="text" name="filetitle" placeholder="Image title..." value="'.$_GET['filetitle'].'">';
                } else {
                    echo '<input type="text" name="filetitle" placeholder="Image title...">';           
                }
                if(isset($_GET['filedesc'])){
                    echo '<input type="text" name="filedesc" placeholder="Image description..." value="'.$_GET['filedesc'].'">';
                } else {
                    echo '<input type="text" name="filedesc" placeholder="Image description...">';
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
        ?>
</div>
<?php
    include_once './props/footer.php';
?>