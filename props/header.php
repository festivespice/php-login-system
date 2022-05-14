<?php
    //used to stop stuff like CSS from being cached.
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT"); 
    session_start(); //on every page of the website, a session will be started so that session variables can be accessed. 

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>PHP Login System</title>
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;800&display=swap" rel="stylesheet">    
    </head>
    <body>
    <div class="wrap">
        <div class="main">

            <div class="topnav">
                <h2> Something </h2>
                <?php 

                    if(isset($_SESSION['userUid'])){ //there is currently a logged in user
                        //if the user is logged in, query whether they have a picture. If they do, then try to create its pathname and use it.
                        include_once './includes/profileimg.inc.php';

                        echo '<a id="signup" href="./Includes/logout.inc.php">Logout</a>';
                        echo '<a id="profile" href="profile.php" class="profile-tab"><p class="profile-text">'.$_SESSION['userUid'].'</p><img class="profile-img" src="'.$imagePath.'?'.mt_rand().'"></a>';
                    } else  {
                        echo '<a id="signin" href="signin.php">Log In</a>';
                        echo '<a id="signup" href="signup.php">Sign Up</a>';
                    }
                ?>
                <a id="forums" href="forums.php">Forums</a>
                <a id="discover" href="discover.php">About Us</a>
                <a id="index" href="index.php">Home</a>
            </div>