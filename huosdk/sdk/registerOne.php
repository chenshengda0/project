<?php
/**
 * 一键注册接口
 */

include ('include/common.inc.php');
$urldata = Response::verify('registerOne');
$rdata = array();
if(!empty($urldata['code']) && $urldata['code']<0){
    $db->CloseConnection();
    return Response::show($urldata['code'], $rdata, $urldata['msg'], $urldata['w']);
}

$username = $db->setUsername();

if ($username){
    $rdata = array(
            'a' => $username, //用户名 
            'b' => rand(100000,999999)   //密码
    );
    $db->CloseConnection();
    return Response::show("1", $rdata, "一键注册成功");
}

$db->CloseConnection();
return Response::show("-2", $rdata, "一键注册失败",  $urldata['w']);
