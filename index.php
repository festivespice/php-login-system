<?php
    include_once './props/header.php';
?>
<div class="currentPageContentSized">
    <?php
        if(isset($_SESSION['userUid']) && isset($_SESSION['userId'])){
            echo "<h2>Hello, ".$_SESSION['userUid']."! User ID: ".$_SESSION['userId']."</h2>";
        }
    ?>
    <h1> Hello, this is content! </h1>
    <p> 
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Tortor vitae purus faucibus ornare suspendisse sed nisi. Velit aliquet sagittis id consectetur purus. Adipiscing diam donec adipiscing tristique risus nec. Pharetra et ultrices neque ornare aenean euismod elementum nisi quis. Bibendum enim facilisis gravida neque convallis a cras semper auctor. Nunc mi ipsum faucibus vitae aliquet nec ullamcorper sit amet. Hac habitasse platea dictumst vestibulum rhoncus. Varius vel pharetra vel turpis nunc eget lorem. Volutpat commodo sed egestas egestas fringilla. Consectetur lorem donec massa sapien faucibus et molestie ac feugiat. Commodo nulla facilisi nullam vehicula. Libero volutpat sed cras ornare arcu dui vivamus arcu felis. Sit amet aliquam id diam. Tristique risus nec feugiat in.
    </p>
</div>
<?php
    include_once './props/footer.php';
?>