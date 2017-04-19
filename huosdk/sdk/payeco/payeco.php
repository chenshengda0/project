<?php
/**
 * payeco.php UTF-8
 * 商户下单接口，接收客户端对接demo的下单请求，并返回下单结果数据给客户端对接demo
 * @date: 2015年10月15日下午5:19:33
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

//验证参数
$urldata = Response::verify('payeco',$db);
$rdata = array();
if(!empty($urldata['code']) && $urldata['code']<0){
    $db->CloseConnection();
    if (empty($urldata['w'])){
        $urldata['w'] = 0;
    }
    return Response::show($urldata['code'], $rdata, $urldata['msg'], $urldata['w']);
}

$transtime = time(); // 交易时间
$extData = ""; // 商户保留信息,通知结果时，原样返回给商户
$miscData = ""; // 订单扩展信息

// 下订单处理自动设置的参数
$merchOrderId = $_SESSION['order_id']; // 订单号

$merchantId = Constants::getMerchantId();
$notifyUrl = Constants::getMerchantNotifyUrl(); // 需要做URLEncode
$tradeTime = Tools::getSysTime();
$expTime = ""; // 采用系统默认的订单有效时间
$notifyFlag = "0";

// 调用下单接口
$retXml = new Xml();
$retMsgJson = "";
$bOK = true;

$payinfo = $db->getPayinfo($merchOrderId);
$payextinfo = $db->getPayextinfo($payinfo['id']);

try {
    Log::setLogFlag(true);
    Log::logFile("--------商户下单接口测试---------------");
    $ret = TransactionClient::MerchantOrder(
            $merchantId, 
            $merchOrderId, 
            $payinfo['amount'], 
            $payextinfo['productname'], 
            $tradeTime, 
            $expTime, 
            $notifyUrl, 
            $extData, 
            $miscData, 
            $notifyFlag, 
            Constants::getMerchantRsaPrivateKey(), 
            Constants::getPayecoRsaPublicKey(), 
            Constants::getPayecoUrl(), 
            $retXml);

    if (strcmp("0000", $ret)) {
        $bOK = false;
        return Response::show("-10", $rdata, "下订单接口返回错误", $urldata['w']);
    }
} catch (Exception $e) {
    $bOK = false;
    $errCode = $e->getMessage();

    if (strcmp("E101", $errCode) == 0) {
        return Response::show("-11", $rdata, "下订单接口无返回数据", $urldata['w']);
    } else if (strcmp("E102", $errCode) == 0) {
        return Response::show("-12", $rdata, "验证签名失败", $urldata['w']);
    } else if (strcmp("E103", $errCode) == 0) {
        return Response::show("-13", $rdata, "进行订单签名失败", $urldata['w']);
    } else {
        return Response::show("-14", $rdata, "下订单通讯失败", $urldata['w']);
    }
}
// 设置返回给手机Json数据
if ($bOK) {
    $retMsgJson = "{\"RetCode\":\"0000\",\"RetMsg\":\"下单成功\"," .
                 "\"Version\":\"" . $retXml->getVersion() . "\",\"MerchOrderId\":\"" . $retXml->getMerchOrderId() .
                 "\",\"MerchantId\":\"" . $retXml->getMerchantId() . "\",\"Amount\":\"" . $retXml->getAmount() .
                 "\",\"TradeTime\":\"" . $retXml->getTradeTime() . "\",\"OrderId\":\"" . $retXml->getOrderId() .
                 "\",\"Sign\":\"" . $retXml->getSign() . "\"}";
	
    // 输出数据
    Log::logFile("retMsgJson=" . $retMsgJson);

    //更新支付方式
    $db->updatePayway($merchOrderId, 'payeco');
    $rdata = array(
            'Version' => $retXml->getVersion(), 
            'MerchOrderId' => $retXml->getMerchOrderId(), 
            'MerchantId' => $retXml->getMerchantId(), 
            'Amount' => $retXml->getAmount(), 
            'TradeTime' => $retXml->getTradeTime(), 
            'OrderId' => $retXml->getOrderId(), 
            'Sign' => $retXml->getSign() 
    );
    $db->CloseConnection();
    return Response::show('1', $rdata, '下单成功', $urldata['w']);
}
$db->CloseConnection();
return Response::show('-1000', $rdata, '下单失败', $urldata['w']);