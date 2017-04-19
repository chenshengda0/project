<?php
/**
 * 初始化接口，检测版本
 */
include ('include/common.inc.php');
$urldata = Response::verify('initpay');
$rdata = array();
if(!empty($urldata['code']) && $urldata['code']<0){
    $db->CloseConnection();
    return Response::show($urldata['code'], $rdata, $urldata['msg']);
}

$pwdata = $db->getPayway($urldata['a']);

if ($pwdata){
    $rdata = array(
            'a' => $pwdata
    );
    return Response::show("1", $rdata, "获取支付数据成功");
}

$db->CloseConnection();
return Response::show("-2", $rdata, "初始化失败");