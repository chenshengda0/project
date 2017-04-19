<?php
/**
 * notify_url.php UTF-8
 * @date: 2015年10月15日下午5:36:01
 * 接收订单结果通知处理;订单结果的参数获取；签名验证；订单状态的判断
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@1tsdk.com>
 * @version : 1.0
 */
require_once 'lib/HttpClient.php';
require_once 'lib/Log.php';
require_once 'lib/Signatory.php';
require_once 'lib/Tools.php';
require_once 'lib/Xml.php';
require_once 'lib/ConstantsClient.php';
require_once 'lib/TransactionClient.php';
require_once 'Constants.php';

include ('../include/common.inc.php');

// 结果通知参数，易联异步通知采用GET提交
$version = $_REQUEST["Version"];
$merchantId = $_REQUEST["MerchantId"];
$merchOrderId = $_REQUEST["MerchOrderId"];
$amount = $_REQUEST["Amount"];
$extData = $_REQUEST["ExtData"];
$orderId = $_REQUEST["OrderId"];
$status = $_REQUEST["Status"];
$payTime = $_REQUEST["PayTime"];
$settleDate = $_REQUEST["SettleDate"];
$sign = $_REQUEST["Sign"];

// 需要对必要输入的参数进行检查，本处省略...
if (!get_magic_quotes_gpc()) {
    $version = addslashes($version);
    $merchantId = addslashes($merchantId);
    $merchOrderId = addslashes($merchOrderId);
    $amount = addslashes($amount);
    $extData = addslashes($extData);
    $orderId = addslashes($orderId);
    $status = addslashes($status);
    $payTime = addslashes($payTime);
    $settleDate = addslashes($settleDate);
    $sign = addslashes($sign);
}

$trade['merchOrderId'] = $merchOrderId;

 Log::setLogFlag(true);
if ($merchantId != Constants::getMerchantId()) {
    Log::logFile($merchantId . "商户号不正确!" . Constants::getMerchantId());
    exit();
}

// 订单结果逻辑处理
$retMsgJson = "";
try {
    // 验证订单结果通知的签名
    Log::logFile("------订单结果通知验证-----------------");
    $b = TransactionClient::bCheckNotifySign(
            $version, 
            $merchantId, 
            $merchOrderId, 
            $amount, 
            $extData, 
            $orderId, 
            $status, 
            $payTime, 
            $settleDate, 
            $sign, 
            Constants::getPayecoRsaPublicKey());
    if (!$b) {
        $retMsgJson = "{\"RetCode\":\"E101\",\"RetMsg\":\"验证签名失败!\"}";
        Log::logFile("验证签名失败!");
    } else {
        /*
         * 签名验证成功后，需要对订单进行后续处理
         * 订单已支付
         * 1、检查Amount和商户系统的订单金额是否一致
         * 2、订单支付成功的业务逻辑处理请在本处增加（订单通知可能存在多次通知的情况，需要做多次通知的兼容处理）；
         * 3、返回响应内容
         */
        if (strcmp("02", $status) == 0) {
            $retMsgJson = "{\"RetCode\":\"0000\",\"RetMsg\":\"订单已支付\"}";
            Log::logFile("订单已支付!");
            $db->doPaynotify($merchOrderId, $amount, $orderId);
        } else {
            // 1、订单支付失败的业务逻辑处理请在本处增加（订单通知可能存在多次通知的情况，需要做多次通知的兼容处理，避免成功后又修改为失败）；
            // 2、返回响应内容
            $retMsgJson = "{\"RetCode\":\"E102\",\"RetMsg\":\"订单支付失败" . $status . "\"}";
            Log::logFile("订单支付失败!status=" . $status);
        }
    }
} catch (Exception $e) {
    $retMsgJson = "{\"RetCode\":\"E103\",\"RetMsg\":\"处理通知结果异常\"}";
    Log::logFile("处理通知结果异常!e=" . $e->getMessage());
}
Log::logFile("-----处理完成----");

// 返回数据
echo $retMsgJson;
$db->CloseConnection();
?>