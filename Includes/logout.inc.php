<?php
    //To logout, just destroy the session variables associated with the session.
    session_start();
    session_unset();
    session_destroy();

    header("location: ../index.php");
    exit(); 