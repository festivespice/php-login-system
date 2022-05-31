<?php
    include_once './props/header.php';
?>
<div class="accountForm">
    <h1> Sign up </h1>

    <form class="authentication" action="./includes/auth/signup.inc.php" method="POST">
        <?php
            if(isset($_GET['name'])){
                echo '<input type="text" name="name" placeholder="Full name..." value="'.$_GET['name'].'">';
            } else {
                echo '<input type="text" name="name" placeholder="Full name...">';
            }
            if(isset($_GET['email'])){
                echo '<input type="text" name="email" placeholder="Email..." value="'.$_GET['email'].'">';
            } else {
                echo '<input type="text" name="email" placeholder="Email...">';
            }
            if(isset($_GET['uid'])){
                echo '<input type="text" name="uid" placeholder="Username..." value="'.$_GET['uid'].'">';
            } else {
                echo '<input type="text" name="uid" placeholder="Username...">';
            }            
        ?>
        <input type="password" name="password" placeholder="Password..."> 
        <input type="password" name="passwordre" placeholder="Repeat Password..."> 

        <button type="submit" name="submit">Sign up</button>
    </form>

    <?php
        if(isset($_GET['error'])){
            $error = $_GET['error'];
            if($error == "emptyinput"){
                echo "<p class='error'>Some input fields were left empty. </p>";
            }
            else if($error == "invaliduid"){
                echo "<p class='error'>The username should only use letters and numbers. </p>";
            }
            else if($error == "invalidemail"){
                echo "<p class='error'>The email input doesn't follow a valid format. </p>";
            }
            else if($error == "passwordsdontmatch"){
                echo "<p class='error'>The passwords provided don't match. </p>";
            }
            else if($error == "usernameoremailtaken"){
                echo "<p class='error'>The username or email supplied is already taken. </p>";
            }
            else if($error == "stmtfailed"){
                echo "<p class='error'>Something went wrong with the database. Try again.</p>";
            }
            else if($error == "none"){
                echo "<p class='success'>You have been signed up!</p>";
            }
        }
    ?>
</div>
<?php
    include_once './props/footer.php';
?>