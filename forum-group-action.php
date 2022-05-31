<?php
    include_once './props/header.php';
?>
<div class="currentPageContentSized">
    <?php //the goal is to dynamically create a title, show what's being moderated, and include a form.
        $userId = $_SESSION['userId'];
        $moderatedId = $_GET['moderated-id'];
        $groupId = $_GET['group-id'];
        $pageType = $_GET['page-type'];
        $groupName = $_GET['group-name'];
        $articleId;
        $articleName;
        $groupName;
        $itemId;
        $moderationArea = "group";
        $power = "";
        $sql = "select * from poweruser pu where pu.userId=".$userId.";";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) == 1){
            while($row = mysqli_fetch_assoc($result)){
                if($row['admin']){
                    $power = "admin";
                }else if($row['moderator']){
                    $power = "moderator";
                }
            }
        }
        //First, what type of thing are we acting on? A group, article, or item? 
        $action = "./includes/forums/forums-action.inc.php?moderated-id=".$moderatedId."&group-id=".$groupId."&page-type=".$pageType."&group-name=".$groupName;
        if(isset($_GET['article-id'])){
            $articleId = $_GET['article-id'];
            $articleName = $_GET['article-name'];
            $action = "./includes/forums/forums-action.inc.php?moderated-id=".$moderatedId."&group-id=".$groupId."&page-type=".$pageType."&article-id=".$articleId."&group-name=".$groupName."&article-name=".$articleName;
            $moderationArea = "article";
            if(isset($_GET['item-id'])){
                $itemId = $_GET['item-id'];
                $action = "./includes/forums/forums-action.inc.php?moderated-id=".$moderatedId."&group-id=".$groupId."&page-type=".$pageType."&article-id=".$articleId."&group-name=".$groupName."&article-name=".$articleName."&item-id=".$itemId;
                $moderationArea = "item";
                echo '<h2>'.$pageType.' forum item: from group "'.$groupName.'", article "'.$articleName.'".</h2>'; //what are we doing and to what object?
            }else {
                echo '<h2>'.$pageType.' forum article: from group "'.$groupName.'", article "'.$articleName.'".</h2>';
            }
        }else {
            echo '<h2>'.$pageType.' forum group: '.$groupName.'.</h2>';
        }
        echo '<form class="user-content-form" action="'.$action.'" method="POST">';
            if($moderationArea == "group"){
                $sql = "select * from forumgroup fg where fg.id=".$groupId.";";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) == 1){
                    while($row = mysqli_fetch_assoc($result)){
                        echo '<div class="forums-group">';
                            if(!empty($row['imageFullName'])){
                                echo '<div style="background-image: url(\'./image/forum-groups/'.$row['imageFullName'].'\');" class="group-image"></div>';
                            }
                            echo "<div class='forum-link-container'>";
                                echo "<a href='forum-articles.php?group-id=".$row['id']."&group-name=".$row['title']."' class='group-text'>";
                                echo '<h2>'.$row['title'].'</h2>';
                                if(!empty($row['description'])){
                                    echo '<p>'.$row['description'].'</p>';
                                }
                                echo '</a>';
                            echo "</div>";
                        echo '</div>';
                    }
                }
            } else if ($moderationArea == "article") {
                $articleSql = "select * from forumarticle fa where fa.forumGroupId=".$groupId." and fa.id=".$articleId.";";
                $articleResult = mysqli_query($conn, $articleSql);
                if(mysqli_num_rows($articleResult) >= 1){
                    while($articleRow = mysqli_fetch_assoc($articleResult)){
                        echo '<div class="forums-article">';
                            if(!empty($articleRow['imageFullName'])){
                                echo '<div style="background-image: url(\'./image/forum-articles/'.$articleRow['imageFullName'].'\');" class="article-image"></div>';
                            }
                            echo "<div class='article-link-container'>";
                                echo "<a href='forum-article.php?group-id=".$groupId."&group-name=".$groupName."&article-id=".$articleId."&article-name=".$articleName."' class='article-text'>";
                                echo '<h2>'.$articleRow['title'].'</h2>';
                                if(!empty($articleRow['description'])){
                                    echo '<p>'.$articleRow['description'].'</p>';
                                }
                                echo '</a>';
                            echo "</div>";
                        echo '</div>';
                    }
                }           
            } else if ($moderationArea == "item") {
                echo '<p>lol</p>';
            }
            echo '<input type="text" name="reason" placeholder="Reason for moderation">';
            if(($pageType == "delete" || $pageType == "close") && ($power == "moderator" || $power == "admin")){
                echo '<div>
                <input type="checkbox" id="ban-user" name="ban-user">
                <label for="ban-user">Ban user</label>
                </div>';
            }
            echo '<button type="submit" name="submit-moderation">Submit</button>
        </form>';
        ?>
</div>
<?php
    include_once './props/footer.php';
?>