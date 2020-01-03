<?php

require '../controllers/config.php';
require '../controllers/database.php';
require_once '../controllers/lib.php';

session_start();

//
if( ($login = auth()) == FALSE ) {
    header('Location: login.php');
    exit;
}

?>

<?php require 'header.php' ?>
<?php require 'topbar.php' ?>

<h1>
    アカウント
</h1>
<h2>
    注文
</h2>
<a href="order_history.php">注文履歴</a><br>
<h2>
    アカウント設定
</h2>
<a href="edit_account.php">アカウント情報の編集</a><br>
<a href="edit_passwd.php">パスワードの変更</a><br>

<?php require 'footer.php'; ?>
