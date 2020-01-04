<?php
require '../controllers/config.php';
require '../controllers/database.php';
require_once '../controllers/lib.php';
require '../controllers/xmpf_tbl.php';
require '../controllers/payment_tbl.php';

try{
    
session_start();
$title = "注文完了";

if( ($login = auth()) == FALSE ) {
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['last_order']) && $_SESSION['last_order'] > time() + 60) {
    header('Location: index.php');
} else {
    $_SESSION['last_order'] = time();
}
}
catch(Throwable $e) {
    error_log("[". date('Y-m-d H:i:s') . "]". __LINE__ . $e->getMessage());
}

$now = time();

$sql = "select cart.*, item.item_name, item.sale_price from cart "
        . " left join item on cart.item_id = item.item_id"
        . " where cart.users_id = :users_id ";

$stmt = $pdo -> prepare($sql);
$stmt->bindParam(':users_id',  $_SESSION['users_id'], PDO::PARAM_STR);
$stmt->execute();

foreach($stmt as $loop){
    $pdo->beginTransaction();

    try{

        $sql = "select * from stock where item_id = :item_id "
            . " and quantity >= :quantity ";

        $stmt = $pdo -> prepare($sql);
        $stmt->bindParam(':item_id',  $loop['item_id'], PDO::PARAM_STR);
        $stmt->bindValue(':quantity',  $loop['quantity'], PDO::PARAM_STR);
        
        $stmt->execute();
        $rs_item = $stmt->fetch();
        
        if ($stmt->rowCount() == 0 ) {
            $pdo->rollback();
            $out_stock[] = $loop['item_id'];
            continue;;
        } else {
            $sql = "update stock set quantity = :quantity where item_id = :item_id ";
            $stmt = $pdo -> prepare($sql);
            $stmt->bindParam(':item_id',  $loop['item_id'], PDO::PARAM_STR);
            $stmt->bindValue(':quantity',  $rs_item['quantity'] - $loop['quantity'], PDO::PARAM_STR);
            $stmt->execute();
            $pdo->commit();
        }
        
        $sql = "insert into shipping (
                order_date,
                users_id,
                item_id,
                quantity,
                sale_price,
                payment,
                state,
                remote_addr,
                shipping_name_kanji,
                shipping_postal_code,
                shipping_xmpf,
                shipping_address1,
                shipping_address2 
                ) values (
                :order_date,
                :users_id,
                :item_id,
                :quantity,
                :sale_price,
                :payment,
                :state,
                :remote_addr,
                :shipping_name_kanji,
                :shipping_postal_code,
                :shipping_xmpf,
                :shipping_address1,
                :shipping_address2 
                )";
       
        $stmt = $pdo -> prepare($sql);
        $stmt->bindValue(':order_date',  date( 'Y-m-d H:i:s', $now), PDO::PARAM_STR);
        $stmt->bindValue(':users_id',  $loop['users_id'], PDO::PARAM_STR);
        $stmt->bindValue(':item_id',  $loop['item_id'], PDO::PARAM_STR);
        $stmt->bindValue(':quantity',  $loop['quantity'], PDO::PARAM_STR);
        $stmt->bindValue(':sale_price',  $loop['sale_price'], PDO::PARAM_STR);
        $stmt->bindValue(':payment',  $_POST['payment'], PDO::PARAM_STR);
        $stmt->bindValue(':state',  0, PDO::PARAM_STR);
        $stmt->bindValue(':remote_addr',  $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $stmt->bindValue(':shipping_name_kanji',  $_POST['shipping_name_kanji'], PDO::PARAM_STR);
        $stmt->bindValue(':shipping_postal_code',  $_POST['shipping_postal_code'], PDO::PARAM_STR);
        $stmt->bindValue(':shipping_xmpf',  $_POST['shipping_xmpf'], PDO::PARAM_STR);
        $stmt->bindValue(':shipping_address1',  $_POST['shipping_address1'], PDO::PARAM_STR);
        $stmt->bindValue(':shipping_address2',  $_POST['shipping_address2'], PDO::PARAM_STR);
        $stmt->execute();
        
        $body_item .= $loop['item_name'] . "    " . $loop['sale_price'] . "円\n";
        $subtotal = $subtotal + $loop['sale_price'] * $loop['quantity'] ;
        
        $sql = "insert into shipping (
                order_date,
                users_id,
                item_id,
                quantity,
                sale_price,
                payment,
                state,
                remote_addr,
                shipping_name_kanji,
                shipping_postal_code,
                shipping_xmpf,
                shipping_address1,
                shipping_address2 
                ) values (
                :order_date,
                :users_id,
                :item_id,
                :quantity,
                :sale_price,
                :payment,
                :state,
                :remote_addr,
                :shipping_name_kanji,
                :shipping_postal_code,
                :shipping_xmpf,
                :shipping_address1,
                :shipping_address2 
                )";
                
        $stmt = $pdo -> prepare($sql);
        $stmt->bindValue(':order_date',  date( 'Y-m-d', $now), PDO::PARAM_STR);
        $stmt->bindValue(':users_id',  $loop['users_id'], PDO::PARAM_STR);
        $stmt->bindValue(':item_id',  -1, PDO::PARAM_STR);
        $stmt->bindValue(':quantity',  $loop['quantity'], PDO::PARAM_STR);
        $stmt->bindValue(':sale_price',  $loop['sale_price'], PDO::PARAM_STR);
        $stmt->bindValue(':payment',  $_POST['payment'], PDO::PARAM_STR);
        $stmt->bindValue(':state',  0, PDO::PARAM_STR);
        $stmt->bindValue(':remote_addr',  $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $stmt->bindValue(':shipping_name_kanji',  $_POST['shipping_name_kanji'], PDO::PARAM_STR);
        $stmt->bindValue(':shipping_postal_code',  $_POST['shipping_postal_code'], PDO::PARAM_STR);
        $stmt->bindValue(':shipping_xmpf',  $_POST['shipping_xmpf'], PDO::PARAM_STR);
        $stmt->bindValue(':shipping_address1',  $_POST['shipping_address1'], PDO::PARAM_STR);
        $stmt->bindValue(':shipping_address2',  $_POST['shipping_address2'], PDO::PARAM_STR);
        
        $stmt->execute();
        
        
        //delete cart
        $sql = "delete from cart where users_id = :users_id ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':users_id',  $loop['users_id'], PDO::PARAM_STR);
        $stmt->execute();
                
        $sql = "select * from users where users_id = :users_id ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':users_id',  $loop['users_id'], PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();


    } catch(PDOException $e){
          //ロールバック
          //$pdo->rollback();
          throw $e;
    }
}

$handling = calc_handling( $subtotal, $_POST['payment']);
$total = $subtotal + $handling;
        
$body = <<< EOT
${user['name_kanji']}様、ご注文いただきありがとうございます。
ご注文内容を確認または変更する場合は、${site_name}　ページの右上にある「アカウント」をクリックしてください。

- 注文内容：
メールアドレス：${user['email1']}

--請求先：
〒${user['postal_code']} ${xmpf_tbl[$user['xmpf']]}
${user['address1']}
${user['address2']}

--お届け先
〒${$_POST['shipping_postal_code']} ${xmpf_tbl[$_POST['shipping_xmpf']]}
${_POST['shipping_address1']}
${_POST['shipping_address2']}

--ご注文内容
${body_item}

注文合計金額　${subtotal}　円
送料・手数料　${handling}　円

合計　${total}　円

EOT;

echo mail( $user['email1'], "ご注文の確認", $body);

?>

<?php require 'header.php' ?>
<?php require 'topbar.php' ?>

<?php require 'footer.php'; ?>