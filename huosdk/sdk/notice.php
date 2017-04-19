<?php
/**
 * 初始化接口，检测版本
 */
include ('include/common.inc.php');
$urldata = Response::verify('notice');
$rdata = array();
if(!empty($urldata['code']) && $urldata['code']<0){
    $db->CloseConnection();
    return Response::show($urldata['code'], $rdata, $urldata['msg']);
}

$time = time();
$noticesql = "select id, title, content FROM ".DB_PREFIX."game_notice where app_id =:app_id AND is_delete=2 AND start_time<$time ORDER BY id DESC";
$db->bind('app_id', $_SESSION['app_id']);
$data = $db->row($noticesql);
if ($data){
    $rdata = array(
            'a' => $data['id'],
            'b' => $data['title'],
            'c' => $data['content'],
    );
    return Response::show("1", $rdata, "获取公告数据成功");
}

$db->CloseConnection();
return Response::show("-1000", $rdata, "初始化失败");