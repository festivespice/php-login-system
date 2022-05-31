<?php
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
        <script type = "text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> 
    </head>
    <body>
    <div class="wrap">
        <div class="main">

            <div class="topnav">
                <h2> Something </h2>
                <div class="lhs-navbar">
                <?php 

                    if(isset($_SESSION['userUid'])){ //there is currently a logged in user
                        //if the user is logged in, query whether they have a picture. If they do, then try to create its pathname and use it.
                        include_once './includes/profile/profileimg.inc.php';

                        echo '<a id="signup" href="./Includes/auth/logout.inc.php">Logout</a>';
                        echo '<div class="header-profile-box"><a id="profile" href="profile.php?user='.$_SESSION['userId'].'" class="profile-tab"><p class="profile-text">'.$_SESSION['userUid'].'</p><img class="profile-img" src="'.$imagePath.'?'.mt_rand().'"></a></div>';
                    } else  {
                        echo '<a id="signin" href="signin.php">Log In</a>';
                        echo '<a id="signup" href="signup.php">Sign Up</a>';
                    }
                ?>
                <a id="forums" href="forums.php">Forums</a>
                <a id="discover" href="discover.php">About Us</a>
                <a id="index" href="index.php">Home</a>
                </div>
            </div>