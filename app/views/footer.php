<?php
require '../controllers/config.php';
require '../controllers/database.php';
?>

<hr>
<div align="center">
<a href="./"><?php print($site_name) ?></a> |
<a href="contact.php">問い合わせ</a> |
<a href="help.php">ヘルプ</a> |
<a href="cart.php">カートを見る</a> |
<a href="account.php">アカウント</a> |
<a href="about.php"><?php print($site_name); ?>について</a> |
<a href="term.php">利用規約</a> |
<a href="privacy.php">プライバシー規約</a><br>
2020 ~ <?php print(date( 'Y' ))?> &copy:  <?php print($site_name); ?> all rights reserved.
</div>
</body>
</html>



