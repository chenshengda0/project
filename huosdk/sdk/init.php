<?php
/**
 * 初始化接口，检测版本
 */
include ('include/common.inc.php');
session_id();
$urldata = Response::verify('init',$db);
$rdata = array();
if(!empty($urldata['code']) && $urldata['code']<0){
    $db->CloseConnection();
    if (empty($urldata['w'])){
        $urldata['w'] = 0;
    }
    return Response::show($urldata['code'], $rdata, $urldata['msg'], $urldata['w']);
}

//获取是否是送检查版本
if (isset($_SESSION['is_switch'])){
    if ($_SESSION['gh_new_id']>$_SESSION['gh_id']){
        $c = 1;
        $d = $_SESSION['new_url'];
    }else{
        $c = 0;
        $d = '';
    }  
    
    $kefusql = "SELECT qq, qqgroup FROM ".DB_PREFIX."game_contact where app_id =:app_id OR app_id =0 ORDER BY app_id limit 1";
    $db->bind('app_id', $urldata['a']);
    $kefu = $db->row($kefusql);
    $kefu['wx'] = '';
    
    $rdata = array(
            'a' => $_SESSION['is_switch'], //是否SDK更新
            'b' => Library::get_client_ip(),  //客户端IP地址
//             'b' => "119.129.211.6",  //客户端IP地址
            'c' => $c,       //是否更新版本
            'd' => $d,      //更新版本地址
            'e' => $kefu      //客服信息
                    
    );
    $_SESSION['app_id'] = $urldata['a'];
    unset($_SESSION['gh_id']);
    unset($_SESSION['gh_new_id']);
    unset($_SESSION['new_url']);
    
    $db->CloseConnection();
    return Response::show("1", $rdata, "初始化成功", $urldata['w']);
}

session(NULL);
$db->CloseConnection();
return Response::show("-2", $rdata, "初始化失败", $urldata['w']);
 
