<?php
/**
* login.php UTF-8
* 
* @date: 2016年6月5日下午11:34:36
* @license 这不是一个自由软件，未经授权不许任何使用和传播。
* @author: wuyonghong <wyh@huosdk.com>
* @version: 2.0
* 
*/
include ('include/common.inc.php');

$urldata = Response::verify('logout');
$rdata = array();
if(!empty($urldata['code']) && $urldata['code']<0){
    $db->CloseConnection();
    return Response::show($urldata['code'], $rdata, $urldata['msg'],$urldata['w']);
}

//获取用户信息
$data = $db->getUserbyid($urldata['v']);

if ($data) {
    $logoutdata['mem_id'] = $data['id'];
    $logoutdata['app_id'] = $urldata['a'];   //appid
    $logoutdata['agent_id'] = $data['agent_id'];   //注册设备来源
    $logoutdata['agentgame'] = $urldata['e']; //agentgame
    $logoutdata['imei'] = $urldata['c'];  //IMEI
    $logoutdata['deviceinfo'] = $urldata['f'];  //deviceinfo
    $logoutdata['userua'] = $urldata['g'];  //userua
    $logoutdata['from'] = $urldata['d'];  //1为安卓，2为H5，3为苹果
    $logoutdata['logout_time'] = time();   //登陆时间
    $logoutdata['logout_ip'] = Library::get_client_ip();
    $logoutdata['ipaddrid'] = $urldata['ac'];
    
    $rs = $db->insertLogout($logoutdata);
    
    if ($rs){
        $rdata = array(
                'a' => $logoutdata['mem_id'],
        );
        $db->CloseConnection();
        return Response::show(1, $rdata, '退出成功',$urldata['w']);
    }else{
        $db->CloseConnection();
        return Response::show(-999, $rdata, '内部服务器错误',$urldata['w']);
    }
}

// 登录失败
$db->CloseConnection();
return Response::show("-2", $rdata, "退出失败",$urldata['w']);