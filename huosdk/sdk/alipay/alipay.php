<?php
/**
 * 初始化接口，检测版本
 */
 
include ('../include/common.inc.php');
$urldata = Response::verify('alipay',$db);
$rdata = array();
if(!empty($urldata['code']) && $urldata['code']<0){
    $db->CloseConnection();
    if (empty($urldata['w'])){
        $urldata['w'] = 0;
    }
    return Response::show($urldata['code'], $rdata, $urldata['msg'], $urldata['w']);
}
//更新支付方式
$pw = $db->updatePayway($_SESSION['order_id'], 'alipay');
if ($pw){   
    $rdata = array(
            'a' => $_SESSION['order_id'],  //订单号
    );
    
    $db->CloseConnection();
    return Response::show("1", $rdata, "支付成功",$urldata['w']);
}

$db->CloseConnection();
return Response::show("-1000", $rdata, "支付失败",$urldata['w']);
 
