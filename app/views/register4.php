<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
        <title>会員登録[4/4]</title>
    </head>
    <body>

<?php

require '../controllers/config.php';
require '../controllers/database.php';
try{
//prepareメソッドでSQLをセット
$stmt = $pdo->prepare("select email1 from users where email1 = :email1");
//bindValueメソッドでパラメータをセット
$stmt->bindParam(':email1',$_GET['email1']);
//executeでクエリを実行
$stmt->execute();
//結果を表示
//データを一件だけ
//$result = $stmt->fetch();
//echo "name = ".$result['name'].PHP_EOL;

//データが複数件
//PDO Statementオブジェクトをそのままforeachへ
//foreach($stmt as $loop){
//    echo "name = ".$loop['name'].PHP_EOL;
//}
$result = $stmt->fetch();
if( md5($magic_code . $result['email1']) == $_GET['md5']) {
    //prepareメソッドでSQLをセット
    $stmt = $pdo->prepare("update users set state = '0' where email1 = :email1");
    //bindValueメソッドでパラメータをセット
    $stmt->bindParam(':email1',$_GET['email1']);
    //executeでクエリを実行
    $stmt->execute();
    
    $message = "登録確認完了しました<br><a href='login.php'>ログインページ</a>からログインしてください。";
} else {
    $message = "登録確認が失敗しました。<br>サポートまでお問い合わせください。";
}
}
catch(Throwable $e) {
    error_log("[". date('Y-m-d H:i:s') . "]". __LINE__ . $e->getMessage());
}
?>
        <div align="center">会員登録[4/4]<br>
        </div>
        <div align="center">
            <?php print($message) ?>
        </div>
    </body>
</html>
