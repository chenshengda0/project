<?php
include('include/common.inc.php');
$urldata = Response::verify('queryOrder');
$rdata = array();
if(!empty($urldata['code']) && $urldata['code']<0){
    $db->CloseConnection();
    if (empty($urldata['w'])){
        $urldata['w'] = 0;
    }
    return Response::show($urldata['code'], $rdata, $urldata['msg'], $urldata['w']);
}

$order_id = $urldata['ak'];
if (!get_magic_quotes_gpc()) {
    $order_id = addslashes($order_id);
}

//根据订单号，查询订单状态
$payinfo = $db->getPayinfo($order_id);
$payextinfo = $db->getPayextinfo($payinfo['id']);

//查询本用户订单
if ($payinfo && $_SESSION['mem_id'] == $payinfo['mem_id']){
    unset($payinfo['id']);
    unset($payinfo['agent_id']);
    unset($payinfo['app_id']);
    unset($payinfo['from']);
    unset($payinfo['update_time']);
    unset($payinfo['attach']);
    unset($payinfo['remark']);
    unset($payinfo['pay_id']);
    unset($payextinfo['deviceinfo']);
    unset($payextinfo['userua']);
    unset($payextinfo['agentgame']);
    unset($payextinfo['pay_ip']);
    unset($payextinfo['imei']);
    unset($payextinfo['cityid']);
    $paydata = array_merge_recursive($payinfo,$payextinfo);
    $rdata = array(
            'a' => $paydata,  //订单信息
    );
    $db->CloseConnection();
    return Response::show('1', $rdata, '查询成功', $urldata['w']);
}
$db->CloseConnection();
return Response::show('-1000', $rdata, '查询失败', $urldata['w']);
