<?php
require '../controllers/config.php';
require '../controllers/database.php';
require_once '../controllers/lib.php';


try{
    $clientip_pc = 12345;//zero company publish this code.
    session_start();
    if( ($login = auth()) == FALSE ) {
        header('Location: login.php');
        exit;
    }
    
    $sql = "select sum( quantity * sale_price) as total from shipping
        where users_id = :users_id and payment = '0' and shipping.state = '0' ";
    $stmt = $pdo -> prepare($sql);
    $stmt->bindParam(':users_id',  $_SESSION['users_id'], PDO::PARAM_STR);
    $stmt->execute();
    $totaldb = $stmt->fetch();
    $total = $totaldb['total'];
    
    $sql = "select item.*, shipping.sale_price as real_sale_price,shipping.quantity from shipping
        inner join item on shipping.item_id = item.item_id 
        where users_id = :users_id and payment = '0' and shipping.state = '0' 
        order by shipping_id desc ";
    $stmt = $pdo -> prepare($sql);
    $stmt->bindParam(':users_id',  $_SESSION['users_id'], PDO::PARAM_STR);
    $stmt->execute();
    $detail = $stmt->fetch();

}
catch(Throwable $e) {
    error_log("[". date('Y-m-d H:i:s') . "]". __LINE__ . $e->getMessage());
}

?>
<?php require 'header.php' ?>
<?php require 'topbar.php' ?>

<h1>クレジットカード決済</h1>
<P>
    ご注文いただいた商品の決済をクレジットカードで行います。<br>
    <br>
    ※クレジットカード決済に関する説明（必ずお読みください）<br>
    <a href="https://www.axes-payment.co.jp/index.html">https://www.axes-payment.co.jp/index.html</a>
    クレジットカード決済はSBIを利用しています。<br>
    クレジットカード決済に関する問い合わせは<br>
    TEL:01234-5678<br>
    <a href="mailto:creditinquiry@sbi.co.jp">creditinquiry@sbi.co.jp</a>
</P>
<table>
    <tr>
        <td>注文日時</td>
        <td>商品名 </td>
        <td>個数</td>
        <td>金額</td>
    </tr>
<?php 
foreach($detail as $loop){
?>
    <tr>
        <td><?php print(date('Y年m月d日', strtotime($loop['order_date']))) ?></td>
        <td><?php print( $loop['item_name'] ) ?></td>
        <td><?php print( $loop['quantity'] ) ?></td>
        <td><?php print( $loop['real_sale_price'] * $loop['quantity'] ) ?></td>
    </tr>
<?php
}
?>
    <tr>
        <td>
            決済金額
        </td>
        <td></td>
        <td></td>
        <td><?php print($total) ?></td>
    </tr>
</table>

<form action="https://sbi.co.jp/cgi-bi/order.cgi?orders" method="post" target="_blank">
    <input type="hidden" name="clientip" value="<?php print($clientip_pc) ?>">
    <input type="hidden" name="money" value="<?php print($total) ?>">
    <input type="submit" value="決済" name="submit">
    
</form>
<?php require 'footer.php'; ?>