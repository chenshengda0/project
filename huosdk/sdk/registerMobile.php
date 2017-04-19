<?php
/**
 * 一键注册接口
 */
include ('include/common.inc.php');

$urldata = Response::verify('registerMobile');
$rdata = array();
if(!empty($urldata['code']) && $urldata['code']<0){
    $db->CloseConnection();
    return Response::show($urldata['code'], $rdata, $urldata['msg'],$urldata['w']);
}

$limit_time = 120;  //设定超时时间 2min
//0是否发送验证码, 0请求验证码  1注册请求
if (0 == $urldata['q']){
    if (isset($_SESSION['mobile']) && $_SESSION['mobile']==$urldata['s'] && $_SESSION['sms_time']+$limit_time>time()){
        $db->CloseConnection();
        return Response::show(1, $rdata, '已发送过验证码',$urldata['w']);
    }
    
    $_SESSION['sms_time'] = time();

    $mobile = $urldata['s'];
    $_SESSION['mobile'] = $mobile;
    $rsdata = Library::getSms($mobile, 'SMSTEMPREG');
    if (0 != $rsdata['code']){
        //短信发送失败
        $db->CloseConnection();
        return Response::show($rsdata['code'], $rdata, $rsdata['msg'],$urldata['w']);
    }else{
        //发送成功
        $db->CloseConnection();
        return Response::show("1", $rdata, $rsdata['msg'],$urldata['w']);
    }
}else{
    if(empty($_SESSION['sms_time']) || $_SESSION['sms_time']+$limit_time<time()) {
        unset($_SESSION['sms_time']);
        unset($_SESSION['sms_code']);
        unset($_SESSION['mobile']);
        $db->CloseConnection();
        return Response::show("-18", $rdata,"验证码已过期",$urldata['w']);
    }
        
    //判断手机号码是否有效
    if(empty($_SESSION['mobile']) || $_SESSION['mobile'] != $urldata['s'] ){
        $db->CloseConnection();
        return Response::show("-19", $rdata,"手机号错误或未填验证码",$urldata['w']);
    }
    
    //验证验证码是否正确
    if(empty($_SESSION['sms_code']) || $_SESSION['sms_code'] != $urldata['r'] ){
        $db->CloseConnection();
        return Response::show("-18", $rdata,"验证码错误",$urldata['w']);
    }
    
    unset($_SESSION['mobile']);
    unset($_SESSION['sms_code']);
    unset($_SESSION['sms_time']);
    $userinfo = $db->getUserinfo($urldata['s']);
    if ($userinfo){
        $db->CloseConnection();
        return Response::show("-2", $rdata, "手机号/用户名已存在", $urldata['w']);
    }
    
    //先插入数据
    $userdata['username'] = $urldata['s'];
    $userdata['password'] = Library::pw_auth_code($urldata['h']);
    $userdata['mobile'] = $urldata['s'];
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
        return Response::show("-2", $rdata, "手机号/用户名已存在", $urldata['w']);
    }
}




