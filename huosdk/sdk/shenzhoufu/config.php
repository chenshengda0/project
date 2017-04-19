<?php

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

$szf_config['serverReturnUrl'] = SDKSITE.'/sdk/shenzhoufu/notify_url.php'; //服务器返回地址
$szf_config['certFile'] = $gconfdir.'pay/shenzhoufu/ShenzhoufuPay.cer'; //服务器返回地址
?>