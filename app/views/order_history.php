<?php
require '../controllers/config.php';
require '../controllers/database.php';
require_once '../controllers/lib.php';
require '../controllers/shipping_state_tbl.php';

try{

    session_start();
    if( ($login = auth()) == FALSE ) {
        header('Location: login.php');
        exit;
    }
    
    if (!isset($_GET['year_s']) || $_GET['year_s'] == '') {
        $time = time() -30 * 24 * 3600;
        $_GET['year_s'] = date('Y',$time);
        $_GET['month_s'] = date('m',$time);
        $_GET['day_s'] = date('d',$time);
        
        $time = time();
        $_GET['year_e'] = date('Y',$time);
        $_GET['month_e'] = date('m',$time);
        $_GET['day_e'] = date('d',$time);
        
    }

    $title = '注文履歴';


?>

<?php require 'header.php' ?>
<?php require 'topbar.php' ?>

<h1>注文履歴</h1>

<table>
    <form action="order_history.php" method="get">
        <tr>
            <td>
                <select name="year_s">
                    <?php for( $n = 2019; $n <= date( 'Y' ); $n ++ ) 
                        if (isset ($_GET['year_s']) && $n==$_GET['year_s'])
                            echo "<option value=$n selected>$n\n";
                        else
                            echo "<option value=$n >$n\n";
                    ?>
                </select>年
                <select name="month_s">
                    <?php for( $n = 1; $n <= 12; $n ++ )
                        if (isset ($_GET['month_s']) && $n==$_GET['month_s'])
                            echo "<option value=$n selected>$n\n";
                        else
                            echo "<option value=$n >$n\n"; 
                    ?>
                </select>月
                <select name="day_s">
                    <?php for( $n = 1; $n <= 31; $n ++ )
                        if (isset ($_GET['day_s']) && $n==$_GET['day_s'])
                            echo "<option value=$n selected>$n\n";
                        else
                            echo "<option value=$n >$n\n";
                    ?>
                </select>日から
                <select name="year_e">
                    <?php for( $n = 2019; $n <= date( 'Y' ); $n ++ )
                        if (isset ($_GET['year_e']) && $n==$_GET['year_e'])
                            echo "<option value=$n selected>$n\n";
                        else
                            echo "<option value=$n >$n\n"; 
                    ?>
                </select>年
                <select name="month_e">
                    <?php for( $n = 1; $n <= 12; $n ++ )
                        if (isset ($_GET['month_e']) && $n==$_GET['month_e'])
                            echo "<option value=$n selected>$n\n";
                        else
                            echo "<option value=$n >$n\n"; 
                    ?>
                </select>月
                <select name="day_e">
                    <?php for( $n = 1; $n <= 31; $n ++ )
                        if (isset ($_GET['day_e']) && $n==$_GET['day_e'])
                            echo "<option value=$n selected>$n\n";
                        else
                            echo "<option value=$n >$n\n";
                    ?>
                </select>日の注文
            </td>
            <td><input type="submit" value="検索" ></td>
        </tr>
    </form>
</table>
<table border="1">
    <tr>
        <td>注文ID</td>
        <td>注文日時</td>
        <td>商品名</td>
        <td>個数</td>
        <td>状態</td>
        <td>発送日時</td>
    </tr>
<?php 

$date_s = $_GET['year_s'] . '-' . $_GET['month_s'] . '-' . $_GET['day_s'] . ' 00:00:00';
$date_e = $_GET['year_e'] . '-' . $_GET['month_e'] . '-' . $_GET['day_e'] . ' 23:59:59';

$sql = "select * from shipping
inner join item on shipping.item_id = item.item_id 
where shipping.users_id = :users_id and order_date >= :date_s and order_date <= :date_e and shipping_id > 0 order by shipping_id desc ";

$stmt = $pdo -> prepare($sql);
var_dump($_SESSION['users_id']);
$stmt->bindValue(':users_id',  $_SESSION['users_id'], PDO::PARAM_STR);
$stmt->bindValue(':date_s',  date("Y-m-d H:i:s", strtotime($date_s)), PDO::PARAM_STR);
$stmt->bindValue(':date_e',  date("Y-m-d H:i:s", strtotime($date_e)), PDO::PARAM_STR);
$stmt->execute();

foreach( $stmt as $loop) {

?>
    <tr>
        <td><?php print($loop['shipping_id']) ?></td>
        <td><?php print(date('Y年m月d日', strtotime($loop['order_date']))) ?></td>
        <td><?php print($loop['item_name']) ?></td>
        <td><?php print($loop['quantity']) ?></td>
        <td><?php print($shipping_state_tbl[$loop['state']]) ?></td>
        <td><?php print(date('Y年m月d日', strtotime($loop['shipping_date']))) ?></td>       
    </tr>
<?php
}
}
catch(Throwable $e) {
    error_log("[". date('Y-m-d H:i:s') . "]". __LINE__ . $e->getMessage());
}
?>
</table>

<?php require 'footer.php'; ?>