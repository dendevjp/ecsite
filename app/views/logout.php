<?php
require '../controllers/config.php';

session_start();
setcookie(session_name(), '', 0);
session_destroy();

header('Location: login.php');
?>

