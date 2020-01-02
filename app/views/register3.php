
<?php
require '../controllers/config.php';
require '../controllers/database.php';
require '../controllers/usercheck.php';
if ($error != '') exit;
try{
$sql = "insert into users (
    login_id,
    passwd,
    register_date,
    name_kanji,
    name_kana,
    sex,
    birthday,
    email1,
    postal_code,
    xmpf,
    address1,
    address2,
    state)
    values(
    :login_id,
    :passwd,
    :register_date,
    :name_kanji,
    :name_kana,
    :sex,
    :birthday,
    :email1,
    :postal_code,
    :xmpf,
    :address1,
    :address2,
    :state)";
    
$stmt = $pdo -> prepare($sql);
$stmt->bindParam(':login_id',  $_POST['email1'], PDO::PARAM_STR);
$stmt->bindParam(':passwd', md5($_POST['passwd']), PDO::PARAM_STR);
$stmt->bindParam(':register_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);

$stmt->bindParam(':name_kanji',  $_POST['name_kanji'], PDO::PARAM_STR);
$stmt->bindParam(':name_kana',  $_POST['name_kana'], PDO::PARAM_STR);

$stmt->bindParam(':sex',  $_POST['sex'], PDO::PARAM_INT);
$stmt->bindParam(':birthday', date("Y-m-d", strtotime($_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'] . ' 00:00:00')) , PDO::PARAM_STR);

$stmt->bindParam(':email1',  $_POST['email1'], PDO::PARAM_STR);
$stmt->bindValue(':postal_code',  $_POST['postal1'] . $_POST['postal2'], PDO::PARAM_STR);

$stmt->bindParam(':xmpf',  $_POST['xmpf'], PDO::PARAM_STR);
$stmt->bindParam(':address1',  $_POST['address1'], PDO::PARAM_STR);
$stmt->bindParam(':address2',  $_POST['address2'], PDO::PARAM_STR);
$stmt->bindValue(':state',  1, PDO::PARAM_STR);
$stmt->execute();
print('DB更新に成功しました。<br>');

$subject = "$site_name 登録確認メール";
$headers = "From: $support_mail\r\n";
$parameters = '-f' . $support_mail;
$md5 = md5($magic_code . $_POST['email1']);

$body = <<< _EOF_
${_POST['name_kanji']}様
この度は、$site_nameへのご登録ありがとうございました。
メールアドレスを確認するため、下記のURLをクリックしてください。
$site_url/register4.php?email1=${_POST['email1']}&md5=$md5
        
登録メールアドレス：${_POST['email1']}
ログインID：${_POST['email1']}
_EOF_;

//mb_language("Japanese");
//var_dump($body);
//mb_internal_encoding("UTF-8");
//var_dump($body);
echo mail( $_POST['email1'], $subject, $body);
var_dump($body);
}
catch(Throwable $e) {
    echo var_dump($e->getMessage());
}

?>
            
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
        <title>会員登録[3/4]</title>
    </head>
    <body>
        <div align="center">会員登録[3/4]<br>
        </div>
        <div align="center">
            登録メールアドレス宛に確認メールを送信しました。<br>
            メール本文中のURLをクリックし、会員情報を有効にしてください。<br>
        </div>
    </body>
</html>

