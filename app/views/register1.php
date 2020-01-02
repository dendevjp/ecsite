
<?php
require '../controllers/xmpf_tbl.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
        <title>会員登録[1/4]</title>
    </head>
    <body>
        <div align="center">
            会員登録[1/4]<br>
            <table>
                <form action="register2.php" method="post">
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
                            <input type="text" name="email1" size="48" maxlength="65">
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <small>半角６４文字まで。ログインIDになります。</small>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">パスワード
                            <font color="red">*
                            </font>
                        </td>
                        <td>
                            <input type="password" name="passwd" size="16" maxlength="16">
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
                     　　<td align="right">姓名（漢字）
                            <font color="red">*
                            </font>
                            
                        </td>
                        <td>
                            <input type="text" name="name_kanji" size="48" maxlength="32">
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
                            <input type="text" name="name_kana" size="48" maxlength="32">
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
                            <input type="radio" name="sex" value="1" checked>男性
                            <input type="radio" name="sex" value="2">女性
                        </td>
                    </tr>
                    <tr>
                     　　<td align="right">生年月日
                           <font color="red">&nbsp;
                            </font>
                        </td>
                        <td>
                            <select name="year">
                                <?php for( $n = 1900; $n <= 2020; $n ++ )
                                echo "<option value=$n>$n\n"; ?>
                            </select>年
                            <select name="month">
                                <?php for( $n = 1; $n <= 12; $n ++ )
                                echo "<option value=$n>$n\n"; ?>
                            </select>月
                            <select name="day">
                                <?php for( $n = 1; $n <= 31; $n ++ )
                                echo "<option value=$n>$n\n"; ?>
                            </select>日<br>
                        </td>
                    </tr>
                    <tr>
                     　　<td align="right">郵便番号
                           <font color="red">&nbsp;
                           </font>
                        </td>
                        <td>
                            <input type="text" name="postal1" size="5" maxlength="5">
                            -
                            <input type="text" name="postal2" size="6" maxlength="6">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                     　　<td>
                           <small>半角数字[<a href="https://www.post.japanpost.jp/zipcode/index.html" target="_blank">郵便番号検索</a>]（例　100-0000</small>）
                        </td>
                    </tr>
                    <tr>
                        <td align="right">都道県府
                           <font color="red">&nbsp;
                           </font>
                        </td>
                     　　<td>
                            <select name="xmpf">
                                <?php foreach ( $xmpf_tbl as $key => $value )
                                echo "<option value=$key>$value\n"; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">住所１
                            <font color="red">*
                            </font>
                        </td>
                        <td>
                            <input type="text" name="address1" size="48" maxlength="64">
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
                            <input type="text" name="address2" size="48" maxlength="64">
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

        
    </body>
</html>

