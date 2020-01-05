<?php
require '../controllers/config.php';
require '../controllers/database.php';
require_once '../controllers/lib.php';
require '../controllers/xmpf_tbl.php';

try{
    
session_start();
$title = "会員情報変更";

if( ($login = auth()) == FALSE ) {
    header('Location: login.php');
    exit;
}

$selectDbFlag = TRUE;
if( isset($_POST['action']) && $_POST['action'] == 'edit')
{
    require '../controllers/usercheck_edit.php';
    if ($error == '') {

    $sql = "update users set
        email1 = :email1,
        name_kanji = :name_kanji,
        name_kana = :name_kana,
        sex = :sex,
        birthday = :birthday,
        postal_code = :postal_code,
        xmpf = :xmpf,
        address1 = :address1,
        address2 = :address2
        where users_id = :users_id ";

    $stmt = $pdo -> prepare($sql);
    $stmt->bindValue(':email1',  $_POST['email1'], PDO::PARAM_STR);
    $stmt->bindValue(':name_kanji',  $_POST['name_kanji'], PDO::PARAM_STR);
    $stmt->bindValue(':name_kana',  $_POST['name_kana'], PDO::PARAM_STR);

    $stmt->bindValue(':sex',  $_POST['sex'], PDO::PARAM_INT);
    
    $stmt->bindValue(':birthday', date("Y-m-d", strtotime($_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'] . ' 00:00:00')) , PDO::PARAM_STR);

    $stmt->bindValue(':postal_code',  $_POST['postal_code'], PDO::PARAM_STR);

    $stmt->bindValue(':xmpf',  $_POST['xmpf'], PDO::PARAM_STR);
    $stmt->bindValue(':address1',  $_POST['address1'], PDO::PARAM_STR);
    $stmt->bindValue(':address2',  $_POST['address2'], PDO::PARAM_STR);
    
    $stmt->bindValue(':users_id',  $_SESSION['users_id'], PDO::PARAM_STR);
    
    $stmt->execute();
     $_SESSION['name_kanji'] = $_POST['name_kanji'];
    $message = "<font color='red'>アカウント情報が更新されました。</font>";
    $selectDbFlag = TRUE;
    } else
    {
        $message = "<font color='red'>" . $error . "</font>";
        $selectDbFlag = FALSE;
    }
}
if( $selectDbFlag ) {
$sql = "select * from  users  where users_id = :users_id ";
$stmt = $pdo -> prepare($sql);
$stmt->bindValue(':users_id',  $_SESSION['users_id'], PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetch();

if( isset($result['birthday'])) {
    $year = date( 'Y', strtotime( $result['birthday'] ));
    $month = date( 'm', strtotime( $result['birthday'] ));
    $day = date( 'd', strtotime( $result['birthday'] ));
    
    $name_kanji =  $result['name_kanji'] ;
    $name_kana = $result['name_kana'] ;
    $sex = $result['sex'] ;
    
    $postal_code = $result['postal_code'] ;
    $xmpf = $result['xmpf'] ;
    $address1 = $result['address1'] ;
    $address2 = $result['address2'] ;
    
    $email1 = $result['email1'] ;
}
} else {
    $year = date( 'Y', strtotime( $_POST['year'] ));
    $month = date( 'm', strtotime( $_POST['month'] ));
    $day = date( 'd', strtotime( $_POST['day'] ));
    
    $name_kanji =  $_POST['name_kanji'] ;
    $name_kana = $_POST['name_kana'] ;
    $sex = $_POST['sex'] ;
    
    $postal_code = $_POST['postal_code'] ;
    $xmpf = $_POST['xmpf'] ;
    $address1 = $_POST['address1'] ;
    $address2 = $_POST['address2'] ;
    
    $email1 = $_POST['email1'] ;
}



}
catch(Throwable $e) {
    error_log("[". date('Y-m-d H:i:s') . "]". __LINE__ . $e->getMessage());
}
?>

<?php require 'header.php' ?>
<?php require 'topbar.php' ?>

        <div align="center">
            <h2>会員情報変更</h2>
            <div><?php if (isset($message)) print($message) ?></div>
            <table>
                <form action="edit_account.php" method="post">
                    <input type="hidden" name="action" value="edit">
                    <tr>
                        <td></td>
                     　　<td>
                           <small><font color="red">*は必須項目です</font></small>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">メールアドレス
                            <font color="red">*
                            </font>
                        </td>
                        <td>
                            <input type="text" name="email1" size="48" maxlength="65" value="<?php print($email1); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <small><font color="red" > <b>※注意、メールアドレスを変更するとログインIDも変更されます。</b></font></small>
                        </td>
                    </tr>
                    <tr>
                     　　<td align="right">姓名（漢字）
                            <font color="red">*
                            </font>
                            
                        </td>
                        <td>
                            <input type="text" name="name_kanji" size="48" maxlength="32" value="<?php print($name_kanji); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <small>全角３２文字まで。（例　田中　太郎）</small>
                        </td>
                    </tr>
                    <tr>
                     　　<td align="right">姓名（かな）
                            <font color="red">*
                            </font>
                            
                        </td>
                        <td>
                            <input type="text" name="name_kana" size="48" maxlength="32" value="<?php print($name_kana); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <small>全角３２文字まで。（例　たなか　たろう）</small>
                        </td>
                    </tr>
                    <tr>
                     　　<td align="right">性別
                           <font color="red">&nbsp;
                            </font>
                        </td>
                        <td>
                            <input type="radio" name="sex" value="1" <?php if ($sex == 1) echo 'checked'; ?>>男性
                            <input type="radio" name="sex" value="2" <?php if ($sex == 2) echo 'checked'; ?>>女性
                        </td>
                    </tr>
                    <tr>
                     　　<td align="right">生年月日
                           <font color="red">&nbsp;
                            </font>
                        </td>
                        <td>
                            <select name="year">
                                <?php for( $n = 1900; $n <= 2020; $n ++ ) {
                                if ( $n == $year ){
                                    echo "<option value=$n selected>$n\n";
                                }
                                else {
                                    echo "<option value=$n>$n\n";
                                } }?>
                            </select>年
                            <select name="month">
                                <?php for( $n = 1; $n <= 12; $n ++ ) {
                                if ( $n == $month ){
                                    echo "<option value=$n selected>$n\n";
                                }
                                else {
                                    echo "<option value=$n>$n\n";
                                } }?>
                            </select>月
                            <select name="day">
                                <?php for( $n = 1; $n <= 31; $n ++ ) {
                                if ( $n == $day ){
                                    echo "<option value=$n selected>$n\n";
                                }
                                else {
                                    echo "<option value=$n>$n\n";
                                } }?>
                            </select>日<br>
                        </td>
                    </tr>
                    <tr>
                     　　<td align="right">郵便番号
                           <font color="red">&nbsp;
                           </font>
                        </td>
                        <td>
                            <input type="text" name="postal_code" size="12" maxlength="12" value="<?php print($postal_code); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                     　　<td>
                           <small>半角数字[<a href="https://www.post.japanpost.jp/zipcode/index.html" target="_blank">郵便番号検索</a>]（例　1001000</small>）
                        </td>
                    </tr>
                    <tr>
                        <td align="right">都道県府
                           <font color="red">&nbsp;
                           </font>
                        </td>
                     　　<td>
                            <select name="xmpf">
                                <?php foreach ( $xmpf_tbl as $key => $value ) {
                                    echo "<option value=$key" . " "; if($key == $xmpf) {echo "selected";} echo " >$value</option>\n";
                                } ?> 
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">住所１
                            <font color="red">*
                            </font>
                        </td>
                        <td>
                            <input type="text" name="address1" size="48" maxlength="64" value="<?php print($address1); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <small>半角６４文字まで。（例　千代田区0-0-0）</small>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">住所２
                            <font color="red">*
                            </font>
                        </td>
                        <td>
                            <input type="text" name="address2" size="48" maxlength="64" value="<?php print($address2); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <small>半角６４文字まで。（例　仮マンション１００号室）</small>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input type="submit" value="確認"></td>
                        </td>
                    </tr>
                </form>
            </table>
        </div>

        
<?php require 'footer.php'; ?>