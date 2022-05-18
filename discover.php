<?php
    include_once './props/header.php';
?>
<div class="currentPage">
    <div class="gallery-header">
        <h1> Hello, this is stuff about us! </h1>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Diam maecenas ultricies mi eget. Et tortor consequat id porta. Volutpat diam ut venenatis tellus in metus vulputate eu scelerisque. Morbi non arcu risus quis varius. Lobortis feugiat vivamus at augue eget. Mauris cursus mattis molestie a iaculis at erat. Purus gravida quis blandit turpis cursus in hac. Sed cras ornare arcu dui vivamus arcu felis. Cum sociis natoque penatibus et magnis dis parturient montes. Massa tincidunt nunc pulvinar sapien et ligula ullamcorper malesuada. Neque gravida in fermentum et sollicitudin ac orci. Iaculis urna id volutpat lacus laoreet non curabitur gravida. Porttitor leo a diam sollicitudin tempor id eu nisl. Nunc vel risus commodo viverra maecenas accumsan lacus vel. Scelerisque fermentum dui faucibus in.
        </p>
        <br>
        <p>
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
        //build the form...
        $sql = "select * from poweruser pr where userId=".$_SESSION['userId'].";";
        $result = mysqli_query($conn, $sql);
        $formType="gallery";
        include_once './props/admin-content-form.php';
    ?>
</div>
<?php
    include_once './props/footer.php';
?>