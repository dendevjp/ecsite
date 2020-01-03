<?php
require '../controllers/config.php';
require '../controllers/database.php';
require_once '../controllers/lib.php';
require '../controllers/xmpf_tbl.php';

//テストのため、臨時にTRUEにする。
session_start();
$title = "カート管理";
$message = "";
try{
if( ($login = auth()) == FALSE ) {
    header('Location: login.php');
    exit;
}

if( isset($_GET['action']) && $_GET['action'] == 'del'){
    $sql = "delete from cart where users_id = :users_id and cart_id = :cart_id ";
    $stmt = $pdo -> prepare($sql);
    $stmt->bindValue(':cart_id',  $_GET['cart_id'], PDO::PARAM_STR);
    $stmt->bindValue(':users_id',  $_SESSION['users_id'], PDO::PARAM_STR);
    
    $stmt->execute();
    $message = "<font color='red'>正常に削除しました。</font>";
    
}

if( isset($_POST['action']) && $_POST['action'] == 'quantity'){
    $sql = "update cart set quantity = :quantity where users_id = :users_id and cart_id = :cart_id ";
    $stmt = $pdo -> prepare($sql);
    $stmt->bindValue(':quantity',  $_POST['quantity'], PDO::PARAM_STR);
    $stmt->bindValue(':cart_id',  $_POST['cart_id'], PDO::PARAM_STR);
    $stmt->bindValue(':users_id',  $_SESSION['users_id'], PDO::PARAM_STR);
    
    $stmt->execute();
    $message = "<font color='red'>正常に変更しました。</font>";
    
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
            <h1>カート管理</h1>
            <?php print($message); ?>
            <div>
                <table cellpadding="8" border="0">
                    <tr>
                        <td>購入日</td>
                        <td>商品</td>
                        <td>価格</td>
                        <td>数量</td>
                        <td>削除</td>
                    </tr>
<?php
$sql = "select item.*, author.author_name, cart.date, cart.quantity, cart.cart_id from cart "
            . " left join item on item.item_id = cart.item_id "
            . " left join author on item.author_id = author.author_id "
            . " where users_id = :users_id ";

$stmt = $pdo -> prepare($sql);
$stmt->bindValue(':users_id',  $_SESSION['users_id'], PDO::PARAM_STR);
$stmt->execute();

$total = 0;
foreach( $stmt as $loop){
    $total = $total + $loop['quantity'] * $loop['sale_price'];
?>
                    <tr>
                        <td>
                            <?php print( date('Y年m月d日', strtotime($loop['date']))) ?>
                        </td>
                        <td>
                            <a href="item.php?item_id=<?php print( $loop['item_id']); ?>"><?php print( $loop['item_name']); ?></a>(<?php print( $loop['author_name']); ?>)
                        </td>
                        <td>
                            <?php print( $loop['sale_price']); ?> 円
                        </td>
                        <td>
                            <form action="cart.php" method="post">
                                <input type="hidden" name="action" value="quantity">
                                <input type="hidden" name="cart_id" value="<?php print( $loop['cart_id']); ?>">
                                <input type="text" name="quantity" value="<?php print( $loop['quantity']); ?>" size="2">
                                <input type="submit" value="数量を変更">
                            </form>
                        </td>
                        <td>
                            [<a href="cart.php?action=del&cart_id=<?php print( $loop['cart_id']); ?>">削除</a>]
                        </td>
                    </tr>

<?php   
}
?>
<?php if ( $total != 0 ) {?>
                    <form action="checkout_address.php">
                        <tr>
                            <td></td>
                            <td></td>
                            <td>小計<?php print($total) ?> 円</td>
                            <td><input type="submit" value="レジに進む"</td>
                        </tr>
                    </form>
<?php
} else {
?>
                    <tr>
                        <td colspan="4">カートの中は空です。</td>
                    </tr>
<?php
}
?>
                </table>
            </div>
        </div>
        
<?php require 'footer.php'; ?>