<?php
require '../controllers/config.php';
require '../controllers/database.php';
require_once '../controllers/lib.php';
require '../controllers/xmpf_tbl.php';
require '../controllers/payment_tbl.php';

try{
    
session_start();
$title = "お届け先を選択";

if( ($login = auth()) == FALSE ) {
    header('Location: login.php');
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
<h1>お届け先を選択してください。</h1>
<p>
お届け先住所が下に表示されています？
または、新しいお届け先住所を入力してください。

<form action="checkout_confirm.php" method="post">
    <table>
        <tr>
            <td>
                姓名（漢字）<font color="red">*</font>
            </td>
            <td>
                <input type="text" name="shipping_name_kanji" size="48" maxlength="32" value="<?php print($result['name_kanji']) ?>" >
            </td>
        </tr>
        <tr>
            <td>
                郵便番号
            </td>
            <td>
                〒<input type="text" name="shipping_postal_code" size="12" maxlength="12" value="<?php print($result['postal_code']) ?>" > <br>
                <select name="shipping_xmpf">
                    <?php foreach ( $xmpf_tbl as $key => $value ) {
                        echo "<option value=$key" . " "; if($key == $result['xmpf']) {echo "selected";} echo " >$value</option>\n";
                    } ?> 
                </select>
            </td>
        </tr>
        <tr>
            <td>
                住所１<font color="red">*</font>
            </td>
            <td>
                <input type="text" name="shipping_address1" size="48" maxlength="64" value="<?php print($result['address1']) ?>" >
            </td>
        </tr>
        <tr>
            <td>
                住所２<font color="red">*</font>
            </td>
            <td>
                <input type="text" name="shipping_address2" size="48" maxlength="64" value="<?php print($result['address2']) ?>" >
            </td>
        </tr>
    </table>
<h1>支払方法を選んでください。</h1>
<p>
お支払方法を選び、その左側にチェックを入れ、必要な情報を入力してください。
</p>

<?php foreach ( $payment_tbl as $key => $value ) { ?>
<input type="radio" name="payment" value="<?php print($key) ?>" ><?php print($value) ?> <br>
<?php } ?> 
<br>
<input type="submit" value="確認" ><br>
</form>
</p>
<?php require 'footer.php'; ?>