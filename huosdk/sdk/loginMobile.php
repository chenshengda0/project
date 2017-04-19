<?php
/**
 * 一键注册接口
 */
include ('include/common.inc.php');

$urldata = Response::verify('loginMobile');
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
    $rsdata = Library::getSms($mobile, 'SMSTEMPLOGIN');
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
    
    $username = strtolower($urldata['s']);
    $login_time = time();
    
    //获取用户信息
    $data = $db->getUserinfo($urldata['s']);
    if ($data) {
        //用户已禁用
        if (3 == $data['status']){
            $db->CloseConnection();
            return Response::show("-2", $rdata, "警告：您的账号已被冻结", $urldata['w']);
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
    
        if ($rs){
            $rdata = array(
                    'a' => (string)$logindata['mem_id'],
                    'b' => (string)$logindata['user_token'],
            );
            $db->CloseConnection();
            return Response::show(1, $rdata, '登录成功',$urldata['w']);
        }else{
            $db->CloseConnection();
            return Response::show(-999, $rdata, '内部服务器错误',$urldata['w']);
        }
    }
    
    $db->CloseConnection();
    return Response::show("-2", $rdata, "还未注册，请注册", $urldata['w']);
}



