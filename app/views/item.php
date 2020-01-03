<?php
require '../controllers/config.php';
require '../controllers/database.php';
require_once '../controllers/lib.php';

session_start();
try{
$sql = "select item.*,author.author_name, stock.quantity, stock.arrival_date from item "
        . " left join author on item.author_id = author.author_id "
        . " left join stock on item.item_id = stock.item_id "
        . " where item.item_id = :item_id ";
$stmt = $pdo -> prepare($sql);
$stmt->bindParam(':item_id',  $_GET['item_id'], PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetch();
}
catch(Throwable $e) {
    error_log("[". date('Y-m-d H:i:s') . "]". __LINE__ . $e->getMessage());
}
?>
<?php require 'header.php' ?>
<?php require 'topbar.php' ?>

<div align="center">
    <table width="80%">
        <tr>
            <td>
                <img src="<?php print($result['image_url']); ?>" width="120"
                     alt="<?php print($result['item_name']); ?>" align="top" border="0"><br>
                <a href="#" onclick="window.open('<?php print($result['image_url']) ?>','item_image','width=540,height=540')">画像を拡大</a>
            </td>
            <td>
                <?php print($result['item_name']); ?>
                <a href="author.php?author_id=<?php print($result['author_id']); ?>"><?php print($result['author_name']) ?></a><br>
                価格：<?php print($result['sale_price']) ?> 円（税込）
<?php 
if ($result['quantity'] <= 0 ) {
    if ( strtotime($result['arrival_date']) < time()) {
        $result['arrival_date'] = "未　定";
    } else {
        $result['arrival_date'] = date("Y年m月d日", strtotime($result['arrival_date']));
    }
?>
                在庫：在庫なし　入荷予定日：<?php print($result['arrival_date']) ?><br>
<?php } else { ?>
                在庫：<?php print($result['quantity']) ?> 点<br>
                <br>
                [<a href="add2cart.php?action=add&item_id=<?php print($result['item_id']) ?>">カートに入れる</a>]<br>
<?php }
?>
                <br>
                商品の説明<br>
                <?php print($result['description']) ?>

            </td>
        </tr>
    </table>
</div>

<?php require 'footer.php'; ?>