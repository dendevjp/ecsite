<?php
$site_name = "ECサイト";
$support_mail = "tmmailtest2020@gmail.com";
$magic_code = "45dfrt67yuhwe23";
$site_url = "http://ecsite.jp/app/views";
$site_top_url = "http://ecsite.jp/app/views/index.php";
$system_message = "";
$title = "";

try{
ini_set('error_log', '/var/www/sites/ecsite/logs/debug.log');
}
catch(Throwable $e) {
    error_log("[". date('Y-m-d H:i:s') . "]". __LINE__ . $e->getMessage());
}
?>

