<?php
/**
* loginOauth.php UTF-8
* 
* @date: 2016年6月5日下午11:34:36
* @license 这不是一个自由软件，未经授权不许任何使用和传播。
* @author: wuyonghong <wyh@huosdk.com>
* @version: 2.0
* 
*/
include ('include/common.inc.php');
$urldata = Response::verify('loginOauth');
$rdata = array();
if(!empty($urldata['code']) && $urldata['code']<0){
    $db->CloseConnection();
    return Response::show($urldata['code'], $rdata, $urldata['msg'],$urldata['w']);
}

$openid = strtolower($urldata['b']);       //用户名 
$access_token = strtolower($urldata['h']);  //相当于密码
$userfrom = strtolower($urldata['ae']);    //第三方注册来源

$oauthinfo = $db->getOauthinfo($userfrom, $openid);

if ($oauthinfo){    
    //用户密码错误
    if ($access_token != $oauthinfo['access_token']) {
        $db->CloseConnection();
        return Response::show("-2", $rdata, "密钥错误");
    }
    
    $data = $db->getUserbyid($oauthinfo['mem_id']);

    $logindata['mem_id'] = $data['id'];
    $logindata['app_id'] = $urldata['a'];   //appid
    $logindata['agentgame'] = $urldata['e']; //agentgame
    $logindata['imei'] = $urldata['c'];  //IMEI
    $logindata['deviceinfo'] = $urldata['f'];  //deviceinfo
    $logindata['userua'] = $urldata['g'];  //userua
    $logindata['from'] = $urldata['d'];  //1为安卓，2为H5，3为苹果
    $logindata['reg_time'] = $data['reg_time'];   //注册时间
    $logindata['login_time'] = time();   //登陆时间
    $logindata['agent_id'] = $data['agent_id'];   //注册设备来源
    $logindata['login_ip'] = Library::get_client_ip();
    $logindata['ipaddrid'] = $urldata['ac'];
    $logindata['user_token'] = Library::setUsertoken($logindata['mem_id'], $urldata['w']);
    $rs = $db->insertLogin($logindata);
    
    if ($rs){
        $rdata = array(
                'a' => $logindata['mem_id'],
                'b' => $logindata['user_token'],
        );
        $db->CloseConnection();
        return Response::show(1, $rdata, '登录成功',$urldata['w']);
    }else{
        $db->CloseConnection();
        return Response::show(-999, $rdata, '内部服务器错误',$urldata['w']);
    }
    
}else{
    //没有登录过
    
    //1 为试玩状态 2为正常状态，3为冻结状态
    if ( 1 == $userfrom){
        $userdata['status'] = 1;
    }else{
        $userdata['status'] = 2;
    }
        
    $userdata['username'] = $db->setUsername();
    $userdata['password'] = Library::pw_auth_code($urldata['h'],AUTHCODE);
    $userdata['mobile'] = '';
    $userdata['nickname'] = $userdata['username'];
    $userdata['from'] = intval($urldata['d']);
    $userdata['imei'] = $urldata['c'];
    $userdata['agentgame'] = $urldata['e'];
    $userdata['app_id'] = $urldata['a'];
    $userdata['agent_id'] = $db->getAgentid($userdata['agentgame']);
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
        
        $oauthdata['from'] = $userfrom;
        $oauthdata['name'] = isset($urldata['af'])?$urldata['af']:$openid;
        $oauthdata['head_img'] = isset($urldata['ag'])?$urldata['ag']:$openid;
        $oauthdata['mem_id'] = $mem_id;
        $oauthdata['create_time'] = $userdata['reg_time'];
        $oauthdata['last_login_time'] = $userdata['reg_time'];
        $oauthdata['last_login_ip'] = $logindata['login_ip'];
        $oauthdata['access_token'] = $access_token;
        $oauthdata['expires_date'] = isset($urldata['ah'])?intval($urldata['ah']):0;
        $oauthdata['openid'] = $openid;
        $db->insertOauth($oauthdata);
        $rdata = array(
                'a' => $mem_id, //用户ID
                'b' => $logindata['user_token']   //user_token
        );
        $db->CloseConnection();
        return Response::show("1", $rdata, "注册成功", $urldata['w']);
    }else{
        $db->CloseConnection();
        return Response::show("-2", $rdata, "用户名已存在", $urldata['w']);
    }
}

// 登录失败
$db->CloseConnection();
return Response::show("-2", $rdata, "账号不存在或者密码不正确",$urldata['w']);