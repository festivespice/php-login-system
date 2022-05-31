<?php
include_once './Includes/dbh.inc.php';
include_once './Includes/functions.inc.php';
$userId = $_SESSION['userId'];
$imageExists = checkIfUserImageExists($conn, $userId);

$imagePath = returnUserImagePath($imageExists, $userId);
