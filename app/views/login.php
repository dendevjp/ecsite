<?php
require '../controllers/config.php';
require '../controllers/database.php';

try{
if ( isset($_POST['keep_login']) && $_POST['keep_login'] != '' ) {
    session_set_cookie_params( 365 * 24 * 3600 );
} else {
    session_set_cookie_params( 0 );
}

session_start();
if( isset($_POST['passwd']) && $_POST['passwd'] == '') {
    $_POST['passwd'] = time();
}

if( $_SERVER["REQUEST_METHOD"] == "POST" ) {
    
    
    $sql = "select * from users where login_id = :login_id and state = :state ";
    $stmt = $pdo -> prepare($sql);
    $stmt->bindParam(':login_id',  $_POST['login_id'], PDO::PARAM_STR);
    $stmt->bindValue(':state', 0, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch();

    if( $result['passwd'] == md5($_POST['passwd'])) {
        var_dump($_POST['login_id']);
        $_SESSION['login_id'] = $_POST['login_id'];
        $_SESSION['auth_code'] = md5( $magic_code . $_POST['login_id']);
        $_SESSION['name_kanji'] = $result['name_kanji'];
        
        $sql = "update users set login_date = :login_date where login_id = :login_id
                and state = :state";
        $stmt = $pdo -> prepare($sql);
        $stmt->bindParam(':login_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindParam(':login_id', $_POST['login_id'], PDO::PARAM_STR);
        $stmt->bindValue(':state', 0, PDO::PARAM_STR);
        $stmt->execute();

        if ( isset($_GET['redirect']) && $_GET['redirect'] != '') {
            header( 'Location: ' . $_GET['redirect']);
            exit;
        } else {
            error_log("[". date('Y-m-d H:i:s') . "]". __LINE__ .$site_top_url);
            header( 'Location: ' . $site_top_url);
            exit;
        }
    } else {
        $message = "<br><font color='red'>ログインできませんでした。</font><br>";
    }
}
}catch(Throwable $e) {
    error_log("[". date('Y-m-d H:i:s') . "]". __LINE__ . $e->getMessage());
}

?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
        <title>ログイン</title>
    </head>
    <body>
        <div align="center">
            ログイン<br>
            <?php if (isset($message)) print($message) ?>
            <table>
                <form action="login.php" method="post">
                    <input type="hidden" name="action" value="login">
                    <tr>
                        <td align="center" colspan="2"><?php print($system_message) ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">ログイン</td>
                        <td>
                            <input type="text" name="login_id" size="32" maxlength="64">
                        </td>    
                    </tr>
                    <tr>
                        <td align="right">パスワード</td>
                        <td>
                            <input type="password" name="passwd" size="32" maxlength="16">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="checkbox" name="keep_login">
                            次回ログインを省略する。
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="submit" value="ログイン">
                        </td>
                    </tr>
                </form>
            </table>
        </div>
    </body>
</html>