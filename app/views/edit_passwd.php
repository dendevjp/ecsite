<?php
require '../controllers/config.php';
require '../controllers/database.php';
require_once '../controllers/lib.php';

//テストのため、臨時にTRUEにする。
session_start();

try{
    
$title = "パスワード変更";
    
if( ($login = auth()) == FALSE ) {
    header('Location: login.php');
    exit;
}

if( isset($_POST['action']) && $_POST['action'] == 'edit')
{
    if ( $_POST['passwd_old'] == '') $error .='古いパスワードが入力されてません。<br>';
    if ( strlen($_POST['passwd_old']) > 16) $error .='古いパスワードが１６文字以上です。<br>';
    if ( ! preg_match("/^[!-~]+$/", $_POST['passwd_old'] )) $error .='古いパスワードに使えない文字が含まれています。<br>';
    
    if ( $_POST['passwd_new_1'] == '') $error .='新しいパスワードが入力されてません。<br>';
    if ( strlen($_POST['passwd_new_1']) > 16) $error .='新しいパスワードが１６文字以上です。<br>';
    if ( ! preg_match("/^[!-~]+$/", $_POST['passwd_new_1'] )) $error .='新しいパスワードに使えない文字が含まれています。<br>';
    
    if ( $_POST['passwd_new_2'] == '') $error .='再度新しいパスワードが入力されてません。<br>';
    if ( strlen($_POST['passwd_new_2']) > 16) $error .='再度新しいパスワードが１６文字以上です。<br>';
    if ( ! preg_match("/^[!-~]+$/", $_POST['passwd_new_2'] )) $error .='再度新しいパスワードに使えない文字が含まれています。<br>';
    
    if ( $_POST['passwd_new_1'] != $_POST['passwd_new_2'] ) {
        $error .='2回の新しいパスワードが一致しませんでした。<br>';
    }
    
    if ($error == '') {
        $sql = "select * from users where users_id = :users_id and state = :state ";
        $stmt = $pdo -> prepare($sql);
        $stmt->bindParam(':users_id',  $_SESSION['users_id'], PDO::PARAM_STR);
        $stmt->bindValue(':state', 0, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        if( $result['passwd'] != md5($_POST['passwd_old'])) {
            $error .='古いパスワードが正しくありません。<br>';
        }
    }

    if ($error == '') {
        $sql = "update users set
        passwd = :passwd
        where users_id = :users_id ";

        $stmt = $pdo -> prepare($sql);
        $stmt->bindValue(':passwd',  md5($_POST['passwd_new_1']), PDO::PARAM_STR);
        $stmt->bindValue(':users_id',  $_SESSION['users_id'], PDO::PARAM_STR);

        $stmt->execute();
        $message = "<font color='red'>パスワードが更新されました。</font>";
    } else {
        $message = "<font color='red'>" . $error . "</font>";
    }

}
}
catch(Throwable $e) {
    error_log("[". date('Y-m-d H:i:s') . "]". __LINE__ . $e->getMessage());
}
?>
<?php require 'header.php' ?>
<?php require 'topbar.php' ?>

        <div align="center">
            <h2>パスワード変更</h2>
            <div><?php if (isset($message)) print($message) ?></div>
            <table>
                <form action="edit_passwd.php" method="post">
                    <input type="hidden" name="action" value="edit">
                    <tr>
                        <td></td>
                     　　<td>
                           <small><font color="red">*は必須項目です</font></small>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">古いパスワード
                            <font color="red">*
                            </font>
                        </td>
                        <td>
                            <input type="password" name="passwd_old" size="16" maxlength="16">
                        </td>
                    </tr>
                    <tr>
                        <td align="right">新しいパスワード
                            <font color="red">*
                            </font>
                        </td>
                        <td>
                            <input type="password" name="passwd_new_1" size="16" maxlength="16">
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <small>半角英数記号１６文字まで。（例　ship@112）</small>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">再度新しいパスワード
                            <font color="red">*
                            </font>
                        </td>
                        <td>
                            <input type="password" name="passwd_new_2" size="16" maxlength="16">
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