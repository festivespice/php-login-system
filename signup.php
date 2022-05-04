
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
                </div>
            </div>
        </div>
        
        <footer>
            <p>Rafael Nunez @2022</p>
        </footer>
    </body>
</html>