<?php

if (!defined('IN_SYS')) {
    exit('Access Denied');
}

// ↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
define('XQTCONF_PATH', substr(dirname(__FILE__), 0, -11)."/");

$gconfdir = substr(dirname(__FILE__), 0, -11)."/conf/";
if(file_exists($gconfdir."domain.inc.php")){
    include $gconfdir."domain.inc.php";
}else{
    exit;
}

if(file_exists($gconfdir."pay/xqtpay/config.php")){
    $xqtconf = include $gconfdir."pay/xqtpay/config.php";
}else{
    $xqtconf = array();
}

// 商户名称
define('MERCHANT_NAME',$xqtconf['merchant_name']);

//商 户 号
define('MERCHANT_ID',$xqtconf['merchant_id']);

//密钥
define('MERCHANT_KEY',$xqtconf['merchant_key']);

//回调地址
define('NOTIFY_URL',SDKSITE.'/sdk/xqtpay/notify_url.php');

?>