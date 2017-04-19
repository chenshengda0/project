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

$urldata = Response::verify('login');
$rdata = array();
if(!empty($urldata['code']) && $urldata['code']<0){
    $db->CloseConnection();
    return Response::show($urldata['code'], $rdata, $urldata['msg'],$urldata['w']);
}

$username = strtolower($urldata['b']);
$password = Library::pw_auth_code($urldata['h'],AUTHCODE);
$login_time = time();

//获取用户信息
$data = $db->getUserinfo($urldata['b']);

if ($data) {
    //用户已禁用
    if (3 == $data['status']){
        $db->CloseConnection();
        return Response::show("-2", $rdata, "警告：您的账号已被冻结", $urldata['w']);
    }
    
    //用户密码错误
    if ($password != $data['password']) {
        $db->CloseConnection();
        return Response::show("-2", $rdata, "密码错误",$urldata['w']);
    }
    
    $logindata['mem_id'] = $data['id'];
    $logindata['app_id'] = $urldata['a'];   //appid
    $logindata['agentgame'] = $urldata['e']; //agentgame
    $logindata['imei'] = $urldata['c'];  //IMEI
    $logindata['deviceinfo'] = $urldata['f'];  //deviceinfo
    $logindata['userua'] = $urldata['g'];  //userua
    $logindata['from'] = $urldata['d'];  //1为安卓，2为H5，3为苹果
    $logindata['reg_time'] = $data['reg_time'];   //注册时间
    $logindata['login_time'] = $login_time;   //登陆时间
    $logindata['agent_id'] = $data['agent_id'];   //注册设备来源
    $logindata['login_ip'] = Library::get_client_ip();
    $logindata['ipaddrid'] = $urldata['ac'];
    $logindata['user_token'] = Library::setUsertoken($logindata['mem_id'], $urldata['w']);
    $rs = $db->insertLogin($logindata);
    
	$c = '1';
	if (empty($data['mobile'])){
		$c = '0';
    }
	
    if ($rs){
        $rdata = array(
                'a' => $logindata['mem_id'],
                'b' => $logindata['user_token'],
				'c' => $c,
        );
        $db->CloseConnection();
        return Response::show(1, $rdata, '登录成功',$urldata['w']);
    }else{
        $db->CloseConnection();
        return Response::show(-999, $rdata, '内部服务器错误',$urldata['w']);
    }
}

// 登录失败
$db->CloseConnection();
return Response::show("-2", $rdata, "账号不存在或者密码不正确",$urldata['w']);