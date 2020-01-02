<?php
$site_name = "ECサイト";
$support_mail = "tmmailtest2020@gmail.com";
$magic_code = "45dfrt67yuhwe23";
$site_url = "http://ecsite.jp/app/views";
try{
ini_set('error_log', '/var/www/sites/ecsite/logs/debug.log');
}
catch(Throwable $e) {
    $message = $e->getMessage();
     print($message);
}
?>

