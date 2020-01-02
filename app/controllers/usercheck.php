<?php

$error = "";
if ( $_POST['email1'] == '') $error .='メールアドレスが入力されてません。<br>';
if ( strlen($_POST['email1']) > 64) $error .='メールアドレスが６４文字以上です。<br>';
//if ( ereg('[^!-~]', $_POST['email1'] )) $error .='メールアドレスに使えない文字が含まれています。<br>';

if ( $_POST['passwd'] == '') $error .='パスワードが入力されてません。<br>';
if ( strlen($_POST['passwd']) > 16) $error .='パスワードが１６文字以上です。<br>';
//if ( ereg('[^!-~]', $_POST['passwd'] )) $error .='パスワードに使えない文字が含まれています。<br>';

if ( $_POST['name_kanji'] == '') $error .='姓名（漢字）が入力されてません。<br>';
if ( strlen($_POST['name_kanji']) > 32) $error .='姓名（漢字）が３２文字以上です。<br>';

if ( $_POST['name_kana'] == '') $error .='姓名（かな）が入力されてません。<br>';
if ( strlen($_POST['name_kana']) > 32) $error .='姓名（かな）が３２文字以上です。<br>';

if ( $_POST['sex'] == '') $error .='性別が入力されてません。<br>';

if ( $_POST['year'] == '') $error .='年が入力されてません。<br>';
if ( $_POST['month'] == '') $error .='月が入力されてません。<br>';
if ( $_POST['day'] == '') $error .='日が入力されてません。<br>';

if ( $_POST['postal1'] == '') $error .='郵便番号が入力されてません。<br>';
if ( $_POST['postal2'] == '') $error .='郵便番号が入力されてません。<br>';

if ( $_POST['xmpf'] == '') $error .='都道府県が選択されてません。<br>';

if ( $_POST['address1'] == '') $error .='住所１が入力されてません。<br>';
if ( $_POST['address2'] == '') $error .='住所２が入力されてません。<br>';

require '../controllers/database.php';

//prepareメソッドでSQLをセット
$stmt = $pdo->prepare("select email1 from users where email1 = :email1");
//bindValueメソッドでパラメータをセット
$stmt->bindParam(':email1',$_POST['email1']);
//executeでクエリを実行
$stmt->execute();
//結果を表示
if($stmt->rowCount() > 0) {
    $error .='このメールアドレスはすでに登録されています。<br>';
}

?>
