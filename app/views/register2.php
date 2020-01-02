<?php
require '../controllers/usercheck.php';

if ( $error != '') { ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
        <title>会員登録[エラー]</title>
    </head>
    <body>
        <div align="center">
            会員登録[エラー]]
            <br>
            <br>
            <?php echo $error ?>
            <br>
            <a href="register1.php">会員登録[1/4]に戻る</a>
        </div>
</html>
<?php exit; }?>
<htm>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
        <title>会員登録[2/4]</title>
    </head>
    <body>
        <div align="center">
            会員登録[2/4]<br>
        </div>
        <table>
            <form action="register3.php" method="post">
                <input type="hidden" name="email1" value="<?php print(htmlspecialchars( $_POST['email1'])) ?>">
                <input type="hidden" name="passwd" value="<?php print(htmlspecialchars( $_POST['passwd'])) ?>">
                <input type="hidden" name="name_kanji" value="<?php print(htmlspecialchars( $_POST['name_kanji'])) ?>">
                <input type="hidden" name="name_kana" value="<?php print(htmlspecialchars( $_POST['name_kana'])) ?>">
                <input type="hidden" name="sex" value="<?php print(htmlspecialchars( $_POST['sex'])) ?>">
                <input type="hidden" name="year" value="<?php print(htmlspecialchars( $_POST['year'])) ?>">
                <input type="hidden" name="month" value="<?php print(htmlspecialchars( $_POST['month'])) ?>">
                <input type="hidden" name="day" value="<?php print(htmlspecialchars( $_POST['day'])) ?>">
                <input type="hidden" name="postal1" value="<?php print(htmlspecialchars( $_POST['postal1'])) ?>">
                <input type="hidden" name="postal2" value="<?php print(htmlspecialchars( $_POST['postal2'])) ?>">
                <input type="hidden" name="xmpf" value="<?php print(htmlspecialchars( $_POST['xmpf'])) ?>">
                <input type="hidden" name="address1" value="<?php print(htmlspecialchars( $_POST['address1'])) ?>">
                <input type="hidden" name="address2" value="<?php print(htmlspecialchars( $_POST['address2'])) ?>">
                <tr>
                    <td align="right">メールアドレス</td>
                    <td><?php print($_POST['email1']) ?></td>
                </tr>
                <tr>
                    <td align="right">パスワード</td>
                    <td>*********</td>
                </tr>
                <tr>
                    <td align="right">姓名（漢字）</td>
                    <td><?php print($_POST['name_kanji']) ?></td>
                </tr>
                <tr>
                    <td align="right">姓名（かな）</td>
                    <td><?php print($_POST['name_kana']) ?></td>
                </tr>
                <tr>
                    <td align="right">性別</td>
                    <td><?php if( $_POST['sex'] == '1' ) echo '男性'; else echo '女性'?></td>
                </tr>
                <tr>
                    <td align="right">出生年月日</td>
                    <td><?php print($_POST['year'])?>年<?php print($_POST['month']) ?>月<?php print($_POST['day']) ?>日</td>
                </tr>
                <tr>
                    <td align="right">郵便番号</td>
                    <td><?php print($_POST['postal1'])?>-<?php print($_POST['postal2']) ?></td>
                </tr>
                <tr>
                    <td align="right">都道府県</td>
                    <td><?php print($_POST['xmpf']) ?></td>
                </tr>
                <tr>
                    <td align="right">住所１</td>
                    <td><?php print($_POST['address1']) ?></td>
                </tr>
                <tr>
                    <td align="right">住所２</td>
                    <td><?php print($_POST['address2']) ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" value="利用規約に同意して、登録します。"
                    </td>
                </tr>
            </form>
        </table>
    </body>
</htm>
