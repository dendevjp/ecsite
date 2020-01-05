<?php
require '../controllers/config.php';
require '../controllers/database.php';
require_once '../controllers/lib.php';
require '../controllers/xmpf_tbl.php';
require '../controllers/payment_tbl.php';

try{
    
session_start();
$title = "注文確定";

if( ($login = auth()) == FALSE ) {
    header('Location: login.php');
    exit;
}

if ( $_POST['shipping_name_kanji'] == '') $error .='お届け先名が入力されていません。<br>';
if ( $_POST['shipping_postal_code'] == '') $error .='お届け先郵便番号が入力されていません。<br>';
if ( $_POST['shipping_xmpf'] == '') $error .='お届け先都道府県が入力されていません。<br>';
if ( $_POST['shipping_address1'] == '') $error .='お届け先住所１が入力されていません。<br>';
if ( $_POST['shipping_address2'] == '') $error .='お届け先住所２が入力されていません。<br>';
if ( $_POST['payment'] == '') $error .='支払方法が選択されていません。<br>';

if ( $error != '') {
    require ('header.php');
    echo "<h1>エラー</h1>";
    echo $error;
    echo "<br>ブラウザのバックボタンで戻り、入力を確認してください。";
    require ('footer.php');
    exit;
}

$sql = "select * from users where users_id = :users_id ";
$stmt = $pdo -> prepare($sql);
$stmt->bindParam(':users_id',  $_SESSION['users_id'], PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetch();

}
catch(Throwable $e) {
    error_log("[". date('Y-m-d H:i:s') . "]". __LINE__ . $e->getMessage());
}
?>

<?php require 'header.php' ?>
<?php require 'topbar.php' ?>
<h1>ご注文の確認</h1>
注文内容、合計金額をご確認の上、注文を確定してください。<br>
下の「注文を確定する」ボタンを押して注文いただくことで、<br>
お客様は当サイトの「プライバシー規約」および「利用規約」に同意の上、<br>
商品をご注文されたことになります。

<h2>お届け先</h2>
    <?php print($_POST['shipping_name_kanji']) ?><br>
    〒<?php print($_POST['shipping_postal_code']) ?>&nbsp;<?php print($xmpf_tbl[$_POST['shipping_xmpf']]); ?><br>
    <?php print($_POST['shipping_address1']) ?><br>
    <?php print($_POST['shipping_address2']) ?><br>
    <h2>お支払方法</h2>
    
    <?php print($payment_tbl[$_POST['payment']]) ?>
    
    <h2>ご購入商品</h2>
    <ul>
<?php 

$sql = "select item.*, author.author_name, cart.date, cart.quantity, cart.cart_id from cart "
            . " left join item on item.item_id = cart.item_id "
            . " left join author on item.author_id = author.author_id "
            . " where users_id = :users_id ";

$stmt = $pdo -> prepare($sql);
$stmt->bindValue(':users_id',  $_SESSION['users_id'], PDO::PARAM_STR);
$stmt->execute();

$subtotal = 0;
foreach( $stmt as $loop){ 
    $subtotal += $loop['quantity'] * $loop['sale_price'];
?>
        <li>
            <a href="item.php?item_id=<?php print($loop['item_id']) ?>"><?php print($loop['item_name']) ?></a><br>
            <?php print($loop['author_name']) ?> <?php print($loop['sale_price']) ?> 円&nbsp;数量 <?php print($loop['quantity']) ?>
        </li>
<?php }
?>
    </ul>
    <h2>金額</h2>
    <table>
        <tr>
            <td>商品</td>
            <td><?php print($subtotal) ?></td>
        </tr>
<?php $handling = calc_handling( $subtotal, $_POST['payment']) ?>
        <tr>
            <td>送料・手数料</td>
            <td><?php print($handling) ?></td>
        </tr>
        <tr>
            <td>合計</td>
            <td><?php print($handling + $subtotal) ?></td>
        </tr>
    </table>
    
    <form action="checkout_thanks.php" method="post">
        <input type="hidden" name="shipping_name_kanji" value="<?php print($_POST['shipping_name_kanji']) ?>">
        <input type="hidden" name="shipping_postal_code" value="<?php print($_POST['shipping_postal_code']) ?>">
        <input type="hidden" name="shipping_xmpf" value="<?php print($_POST['shipping_xmpf']) ?>">
        <input type="hidden" name="shipping_address1" value="<?php print($_POST['shipping_address1']) ?>">
        <input type="hidden" name="shipping_address2" value="<?php print($_POST['shipping_address2']) ?>">
        <input type="hidden" name="payment" value="<?php print($_POST['payment']) ?>">
               
        <input type="submit" value="確認" ><br>
    </form>
<?php require 'footer.php'; ?>