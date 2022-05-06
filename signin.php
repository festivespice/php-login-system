<?php
    include_once './props/header.php';
?>
<div class="accountForm">
    <h1>Log in</h1>

    <form class="authentication" action="./includes/signin.inc.php" method="POST">
    <?php
        if(isset($_GET['uid'])){
            echo '<input type="text" name="uid" placeholder="Username/Email..." value="'.$_GET['uid'].'">';
        } else {
            echo '<input type="text" name="uid" placeholder="Username/Email...">';
        }      
    ?>
    <input type="password" name="password" placeholder="Password...">

        <button type="submit" name="submit">Log in</button>
    </form>
    <?php
                if(isset($_GET['error'])){
                    $error = $_GET['error'];
                    if($error == "emptyinput"){
                        echo "<p class='error'>Some input fields were left empty. </p>";
                    }
                    else if($error == "wronglogin"){
                        echo "<p class='error'>An incorrect user/password combination was made. Try again.</p>";
                    }
                    else if($error == "stmtfailed"){
                        echo "<p class='error'>Something went wrong with the database. Try again.</p>";
                    }
                    else if($error == "none"){
                        echo "<p class='success'>You have been logged in!</p>";
                    }
                }
            ?>
</div>
<?php
    include_once './props/footer.php';
?>