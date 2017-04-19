<?php
/**
 * 初始化接口，检测版本
 */
 
include ('../include/common.inc.php');
$urldata = Response::verify('ptbpay',$db);
$rdata = array();
if(!empty($urldata['code']) && $urldata['code']<0){
    $db->CloseConnection();
    if (empty($urldata['w'])){
        $urldata['w'] = 0;
    }
    return Response::show($urldata['code'], $rdata, $urldata['msg'], $urldata['w']);
}

//平台币金额需不小于金额的10倍
if ($urldata['n'] < 10 * $_SESSION['amount']){
    $db->CloseConnection();
    return Response::show("-14", $rdata, "提交平台币金额错误",$urldata['w']);
}

//检查余额是否正确
$ptbdata = $db->getPtb($urldata['v']);

if ($ptbdata['remain'] < $urldata['n']){
    $db->CloseConnection();
    return Response::show("-15", $rdata, "余额不足", $urldata['w']);
}

if ($ptbdata['remain'] <= 0){
    $db->CloseConnection();
    return Response::show("-15", $rdata, "余额不足", $urldata['w']);
}

//更新支付方式
$pw = $db->updatePayway($_SESSION['order_id'], 'ptbpay');
$rs = $db->doPaynotify($_SESSION['order_id'], $_SESSION['amount']);
if ($rs){
    $prs = $db->doPtbpay($_SESSION['order_id'], $urldata['n']);
    //更新平台币余额
    $upsql = "update ".DB_PREFIX."ptb_mem set remain=remain-:count where mem_id=:mem_id";
    $db->bind("count",  $urldata['n']);
    $db->bind("mem_id", $_SESSION['mem_id']);
    $rs_ptb = $db->query($upsql);
    $rdata = array(
            'a' => $_SESSION['order_id'],  //订单号
    );
    $_SESSION['paystatus'] = 2;
    $db->CloseConnection();
    return Response::show("1", $rdata, "支付成功",$urldata['w']);
}

$db->CloseConnection();
return Response::show("-1000", $rdata, "支付失败",$urldata['w']);
 
