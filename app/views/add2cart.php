<?php
require '../controllers/config.php';
require '../controllers/database.php';
require_once '../controllers/lib.php';

try{
$message = "";
session_start();

//
if( ($login = auth()) == FALSE ) {
    header('Location: login.php');
    exit;
}

if( isset($_GET['action']) && $_GET['action'] == 'add') {
    $sql = "insert into cart ("
            . " date, "
            . " users_id, "
            . " item_id, "
            . " quantity "
            . ") "
            . " values ( "
            . " :date, "
            . " :users_id, "
            . " :item_id, "
            . " :quantity "
            . ")";
    
    $stmt = $pdo -> prepare($sql);
    $stmt->bindValue(':date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
    $stmt->bindValue(':users_id',  $_SESSION['users_id'], PDO::PARAM_STR);
    $stmt->bindValue(':item_id',  $_GET['item_id'], PDO::PARAM_STR);
    $stmt->bindValue(':quantity', 1, PDO::PARAM_STR);
    
    $stmt->execute();
    $message = "カートに追加しました。";
}

}
catch(Throwable $e) {
    $message = "処理中にエラーが発生しました。";
    error_log("[". date('Y-m-d H:i:s') . "]". __LINE__ . $e->getMessage());
}
?>
<?php require 'header.php' ?>
<?php require 'topbar.php' ?>

        <div align="center">
            <h1>カート</h1>
            <?php print($message); ?>
        </div>
        
<?php require 'footer.php'; ?>
