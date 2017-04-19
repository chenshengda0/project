<?php
$gconfdir = substr(dirname(__FILE__), 0, -11)."/conf/";
if(file_exists($gconfdir."domain.inc.php")){
    include $gconfdir."domain.inc.php";
}else{
    exit;
}

if(file_exists($gconfdir."pay/payeco/config.php")){
    $payecoconfig = include $gconfdir."pay/payeco/config.php";
}else{
    $payecoconfig = array();
}

// ----商户信息：商户根据对接的实际情况对下面数据进行修改； 以下数据在测试通过后，部署到生产环境，需要替换为生产的数据----
// 商户编号，由易联产生，邮件发送给商户
define('CONSTANTS_MERCHANT_ID',$payecoconfig['merchant_id']);

// 商户接收订单通知接口地址；
define('CONSTANTS_MERCHANT_NOTIFY_URL', SDKSITE.'/sdk/payeco/notify_url.php');

// 商户RSA私钥，商户自己产生（可采用易联提供RSA工具产生）
define('CONSTANTS_MERCHANT_RSA_PRIVATE_KEY',$gconfdir.'pay/payeco/key/rsa_private_key.pem');

// ----易联信息： 以下信息区分为测试环境和生产环境，商户根据自己对接情况进行数据选择----
// 易联服务器地址
define('CONSTANTS_PAYECO_URL',$payecoconfig['payeco_url']);

// 订单RSA公钥（易联提供）
define('CONSTANTS_PAYECO_RSA_PUBLIC_KEY',$gconfdir.'pay/payeco/key/rsa_public_key.pem');

class Constants {
    static function getMerchantId() {
        return CONSTANTS_MERCHANT_ID;
    }
    static function getMerchantNotifyUrl() {
        return CONSTANTS_MERCHANT_NOTIFY_URL;
    }
    static function getMerchantRsaPrivateKey() {
        return CONSTANTS_MERCHANT_RSA_PRIVATE_KEY;
    }
    static function getPayecoUrl() {
        return CONSTANTS_PAYECO_URL;
    }
    static function getPayecoRsaPublicKey() {
        return CONSTANTS_PAYECO_RSA_PUBLIC_KEY;
    }
}

?>
