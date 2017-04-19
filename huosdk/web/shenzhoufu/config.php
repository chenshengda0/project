<?php
header("Content-Type:text/html; charset=utf-8");
$gconfdir = substr(dirname(__FILE__), 0, -15)."/conf/";
if(file_exists($gconfdir."domain.inc.php")){
    include $gconfdir."domain.inc.php";
}else{
    exit;
}

if(file_exists($gconfdir."pay/shenzhoufu/config.php")){
    $szf_config = include $gconfdir."pay/shenzhoufu/config.php";
}else{
    $szf_config = array();
}

$szf_config['serverReturnUrl'] = 'http://shouyoucun.cn/web/shenzhoufu/notify_url.php'; //服务器返回地址
$szf_config['certFile'] = $gconfdir.'pay/shenzhoufu/ShenzhoufuPay.cer'; //服务器返回地址

$con = mysql_connect("127.0.0.1","lianfafajiesql","fafalian##341345AA");
if (!$con)
{
    die('Could not connect: ' . mysql_error());
}
@mysql_select_db('db_sdk', $con);
?>