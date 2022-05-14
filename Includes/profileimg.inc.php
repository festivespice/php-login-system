<?php
require_once './includes/dbh.inc.php';
require_once './includes/functions.inc.php';
$userId = $_SESSION['userId'];
$imageExists = checkIfUserImageExists($conn, $userId);

$imagePath = returnUserImagePath($imageExists, $userId);