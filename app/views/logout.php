<?php
require '../controllers/config.php';

session_start();
setcookie(session_name(), '', 0);
session_destroy();

if( $_GET['redirect'] == '') {
    $_GET['redirect'] = $site_url;
}

header('Location: ' . $_GET['redirect']);
?>

