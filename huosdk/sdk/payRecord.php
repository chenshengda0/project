<?php
/**
 * 获取充值记录
 */
$pagesize = 10;

include ('include/common.inc.php');
$urldata = Response::verify('payRecord');
$rdata = array();
if(!empty($urldata['code']) && $urldata['code']<0){
    $db->CloseConnection();
    if (empty($urldata['w'])){
        $urldata['w'] = 0;
    }
    return Response::show($urldata['code'], $rdata, $urldata['msg'], $urldata['w']);
}

$paycntsql = "select count(*) as t from c_pay where mem_id =:mem_id and status=:paystatus";
$db->bind('mem_id', $_SESSION['mem_id']);
$db->bind('paystatus', $urldata['t']);
$numrows = $db->row($paycntsql);

// 计算总页数
$pages = intval($numrows['t'] / $pagesize);

// 计算记录偏移量
$offset = $pagesize * $pages;

$paysql = "SELECT p.order_id as a, p.amount as b, pw.disc as c,  p.create_time as d ";
$paysql .= " from ".DB_PREFIX."pay p LEFT JOIN ".DB_PREFIX."payway pw ON p.payway = pw.payname";
$paysql .= " WHERE p.mem_id=:mem_id AND p.payway!='0' AND p.status =:paystatus ORDER BY p.id DESC limit ".$offset.",".$pagesize;
$db->bind('mem_id', $_SESSION['mem_id']);
$db->bind('paystatus', (int)$urldata['t']);

$data = $db->query($paysql);
if ($data) {
    
    $db->CloseConnection();
    return Response::show("1", $data, "查询记录成功", $urldata['w']);
} else {
    $db->CloseConnection();
    return Response::show("1", $rdata, "没有充值记录", $urldata['w']);
}

?>