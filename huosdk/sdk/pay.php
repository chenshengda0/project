<?php
/**
 * 初始化接口，检测版本
 */
 
include ('include/common.inc.php');
$urldata = Response::verify('pay');
$rdata = array();
if(!empty($urldata['code']) && $urldata['code']<0){
    $db->CloseConnection();
    if (empty($urldata['w'])){
        $urldata['w'] = 0;
    }
    return Response::show($urldata['code'], $rdata, $urldata['msg'], $urldata['w']);
}
$userdata = $db->getUserbyid($urldata['v']);
if ($userdata){
    $paydata['mem_id'] = $userdata['id'];
    $paydata['order_id'] = Library::setorderid($paydata['mem_id']);
    $paydata['agent_id'] = $userdata['agent_id'];
    //$paydata['client_v'] = (int)$urldata['v'];
    $paydata['app_id'] = (int)$urldata['a'];
    $paydata['amount'] = (float)$urldata['o'];
    $paydata['from'] = intval($urldata['d']);
    $paydata['status'] = 1;  //1 为待支付状态
    $paydata['cpstatus'] = 1; //1 为待支付状态
    $paydata['create_time'] = time();
    $paydata['update_time'] = 0;
    $paydata['attach'] = $urldata['m'];
    
    $pay_extdata['role'] = $urldata['p'];
    $pay_extdata['productname'] = $urldata['k'];
    $pay_extdata['productdesc'] = $urldata['l'];
    $pay_extdata['deviceinfo'] = $urldata['f'];
    $pay_extdata['userua'] = $urldata['g'];
    $pay_extdata['agentgame'] = $urldata['e'];
    $pay_extdata['server'] = $urldata['i'];
    $pay_extdata['pay_ip'] = Library::get_client_ip();
    $pay_extdata['imei'] = $urldata['c'];
    $pay_extdata['cityid'] = $urldata['ac'];

    $paytoken = Library::setPaytoken($paydata['order_id'], $urldata['w']);
    $pay_id = $db->dopay($paydata,$pay_extdata);
    if ($pay_id){
        $_SESSION['pay_token'] = $paytoken;
        $_SESSION['order_id'] = $paydata['order_id'];
        $_SESSION['amount'] = $paydata['amount'];
        $rdata = array(
                'a' => $pay_id, //支付ID
                'b' => $paydata['order_id'],  //订单号
                'c' => $paytoken,       //支付token
        );
        $db->CloseConnection();
        return Response::show("1", $rdata, "下单成功");
    }
}

$db->CloseConnection();
return Response::show("-1000", $rdata, "下单失败");
 
