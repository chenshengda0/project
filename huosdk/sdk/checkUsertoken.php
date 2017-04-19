<?php
/**
 * CP校验用户有效性
 */
define('SYS_ROOT',dirname(__FILE__).'/');
define('IN_SYS',TRUE);

define('CLASS_PATH','include/class/');
require_once SYS_ROOT.'include/config.inc.php';

require_once(SYS_ROOT.CLASS_PATH.'Db.class.php');
require_once(SYS_ROOT.CLASS_PATH.'Library.class.php');
require_once(SYS_ROOT.CLASS_PATH.'Switchlog.class.php');
$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';

// $urldata["user_token"]="38pdopq9ooht87jb1rqp84cs50";
// $urldata["mem_id"]="100176";
// $urldata["app_id"]="60026";
// $urldata["sign"] = "a47fe4ee1064894f64880edc98606130";
//$urldata = json_encode($urldata);
$rdata = array(
        'status' => 0,
        'msg' => '请求参数错误'
);

// 缺少参数
if (empty($urldata)) {
    echo json_encode($rdata);
    exit;
}
$urldata = get_object_vars(json_decode($urldata));

$app_id = isset($urldata['app_id']) ? intval($urldata['app_id']) : 0; //appid
$mem_id = isset($urldata['mem_id']) ? intval($urldata['mem_id']) : 0; //用户ID
$user_token = isset($urldata['user_token']) ? $urldata['user_token'] : ''; //token 登陆时通过SDK客户端传送给游戏服务器
$sign = isset($urldata['sign']) ? $urldata['sign'] : ''; // 签名

// 用户名不能为空
if (empty($user_token) || $mem_id<0 || $app_id<0 || empty($sign)) {
    echo json_encode($rdata);
    exit();
}

//查询是否有访问权限
// $ip = Library::get_client_ip();
// if (empty($ip)){
    // $rdata = array(
            // 'status' => 100,
            // 'msg' => '没有访问权限'
    // );
    // echo json_encode($rdata);
    // exit();
// }
//获取user_token

session_id($user_token);
session_start();
$id = session_id();
if (empty($id)){
	exit(json_encode($rdata));
}
//13 user_token错误
if (empty($_SESSION['mem_id'])){
    $rdata = array(
            'status' => 13,
            'msg' => 'user_token错误'
    );
    echo json_encode($rdata);
    exit();
}

//14	user_token超时，表示用户登录授权已超时，需引导用户重新登录，并更新接口访问令牌。（注：访问令牌的有效时长是1天）
if (!empty($_SESSION['exptime'])){
    if ($_SESSION['exptime']+86400>time()){
        $rdata = array(
                'status' => 14,
                'msg' => 'user_token超时'
        );
        echo json_encode($rdata);
        exit();
    } 
}

//15	mem_id错误
if ($mem_id !=$_SESSION['mem_id']){
    $rdata = array(
            'status' => 15,
            'msg' => 'mem_id错误'
    );
    echo json_encode($rdata);
    exit();
}

if (empty($_SESSION['cp_cnt'])){
    $_SESSION['cp_cnt'] = 0;
}

$_SESSION['cp_cnt'] = $_SESSION['cp_cnt']++;

//16	访问太频繁，超过访问次数
if ($_SESSION['cp_cnt'] > 5){
    $rdata = array(
            'status' => 16,
            'msg' => '访问太频繁，超过访问次数'
    );
    echo json_encode($rdata);
    exit();
}

$db = new DB();

//10	服务器内部错误
if (empty($db)){
    $rdata = array(
            'status' => 10,
            'msg' => '服务器内部错误'
    );
    echo json_encode($rdata);
    exit();
}
$appkey = $db->getAppkey($app_id);
$db->CloseConnection();

//11	app_id错误
if (empty($appkey)){
    $rdata = array(
            'status' => 11,
            'msg' => 'app_id错误'
    );
    echo json_encode($rdata);
    exit();
}

//12	sign错误
$signstr = "app_id=".$app_id."&mem_id=".$mem_id."&user_token=".$user_token."&app_key=".$appkey;
$verifysign = md5($signstr);

if ($sign != $verifysign){
    $rdata = array(
            'status' => 12,
            'msg' => '签名错误'
    );
    echo json_encode($rdata);
    exit();
}

//1	成功
$rdata['status'] = 1;
$rdata['msg'] = '用户已登陆';
echo json_encode($rdata);
