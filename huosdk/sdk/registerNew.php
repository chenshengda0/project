<?php
/**
 * 一键注册接口
 */
include ('include/common.inc.php');

$urldata = Response::verify('registerNew');
$rdata = array();
if(!empty($urldata['code']) && $urldata['code']<0){
    $db->CloseConnection();
    return Response::show($urldata['code'], $rdata, $urldata['msg'],$urldata['w']);
}

//判断用户是否存在
$userinfo = $db->getUserinfo($urldata['b']);
if ($userinfo){
    $db->CloseConnection();
    return Response::show("-2", $rdata, "用户名已存在", $urldata['w']);
}

//先插入数据
$userdata['username'] = $urldata['b'];
$userdata['password'] = Library::pw_auth_code($urldata['h'],AUTHCODE);
$userdata['mobile'] = '';
$userdata['nickname'] = $userdata['username'];
$userdata['from'] = intval($urldata['d']);
$userdata['imei'] = $urldata['c'];
$userdata['agentgame'] = $urldata['e'];
$userdata['app_id'] = $urldata['a'];
$userdata['agent_id'] = $db->getAgentid($userdata['agentgame']);
$userdata['status'] = 2;  //1 为试玩状态 2为正常状态，3为冻结状态
$userdata['reg_time'] = time();
$userdata['update_time'] = $userdata['reg_time'];
$mem_id = $db->insertRegist($userdata);

if ($mem_id){
    $logindata['mem_id'] = $mem_id;
    $logindata['app_id'] = $userdata['app_id'];   //appid
    $logindata['agentgame'] = $userdata['agentgame']; //agentgame
    $logindata['imei'] = $userdata['imei'];  //IMEI
    $logindata['deviceinfo'] = $urldata['f'];  //deviceinfo
    $logindata['userua'] = $urldata['g'];  //userua
    $logindata['from'] = $userdata['from'];  //1为安卓，2为H5，3为苹果
    $logindata['reg_time'] = $userdata['reg_time'];   //注册时间
    $logindata['login_time'] =$userdata['reg_time'];   //登陆时间
    $logindata['agent_id'] = $userdata['agent_id'];   //注册设备来源
    $logindata['login_ip'] = Library::get_client_ip();
    $logindata['ipaddrid'] = $urldata['ac'];
    $logindata['user_token'] = Library::setUsertoken($logindata['mem_id'], $urldata['w']);

    $db->insertLogin($logindata);
    $user_token = Library::setUsertoken($mem_id, $urldata['w']);
    $rdata = array(
            'a' => $mem_id, //用户ID
            'b' => $user_token   //user_token
    );
    
    $db->CloseConnection();
    return Response::show("1", $rdata, "注册成功", $urldata['w']);
}else{
    $db->CloseConnection();
    return Response::show("-2", $rdata, "用户名已存在", $urldata['w']);
}


