
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>PHP Login System</title>
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;800&display=swap" rel="stylesheet">    </head>
    <body>
        <div class="wrap">
            <div class="main">
                <div class="topnav">
                    <h2 href="index.php"> Something </h2>

                    <a href="signin.php">Log In</a>
                    <a class="active" href="signup.php">Sign Up</a>
                    <a href="blog.php">Find Blogs</a>
                    <a href="discover.php">About Us</a>
                    <a href="index.php">Home</a>
                </div>

                <div class="accountForm">
                    <h1> Sign up </h1>

                    <form class="authentication" action="./includes/signup.inc.php" method="POST">
                        <input type="text" name="name" placeholder="Full name..."> 
                        <input type="text" name="email" placeholder="Email..."> 
                        <input type="text" name="uid" placeholder="Username..."> 
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
            </div>
        </div>
        
        <footer>
            <p>Rafael Nunez @2022</p>
        </footer>
    </body>
</html>