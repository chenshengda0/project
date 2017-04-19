<?php
/**
 * 初始化接口，检测版本
 */
include ('../include/common.inc.php');
require_once ("config.php");

$urldata = Response::verify('xqtpay',$db);
$rdata = array();
if(!empty($urldata['code']) && $urldata['code']<0){
    $db->CloseConnection();
    if (empty($urldata['w'])){
        $urldata['w'] = 0;
    }
    
    $log = new Switchlog($huosdk_blogflag);
    $message .= "请求参数 : " . json_encode($urldata);
    $log->write($message,'xqtpay');   
    
    return Response::show($urldata['code'], $rdata, $urldata['msg'], $urldata['w']);
}
//更新支付方式
$pw = $db->updatePayway($_SESSION['order_id'], 'xqtpay');
if ($pw){   
    $rdata = array(
        'a' => $_SESSION['order_id'],  //订单号
        'b' => MERCHANT_ID,   //商户ID
        'c' => MERCHANT_KEY,  //密钥
        'd' => NOTIFY_URL,  //回调地址
    );
    
    $db->CloseConnection();
    return Response::show("1", $rdata, "支付成功",$urldata['w']);
}

$db->CloseConnection();
return Response::show("-1000", $rdata, "支付失败",$urldata['w']);
 
