<?php
 /**
 * payCheck.php UTF-8
 * 
 * 支付回调DEMO
 * 
 */
$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';

$success = "SUCCESS";
$fail = "FAILURE";

// 缺少参数
if (empty($urldata)) {
    exit($fail);
}

$urldata = get_object_vars(json_decode($urldata));
$order_id = isset($urldata['order_id']) ? $urldata['order_id'] : ''; 
$mem_id = isset($urldata['mem_id']) ? $urldata['mem_id'] : ''; 
$app_id = isset($urldata['app_id']) ? intval($urldata['app_id']) : 0; 
$money = isset($urldata['money']) ? $urldata['money'] : 0.00; 
$order_status = isset($urldata['order_status']) ? $urldata['order_status'] : ''; 
$paytime = isset($urldata['paytime']) ? intval($urldata['paytime']) : 0;
$attach = isset($urldata['attach']) ? $urldata['attach'] : ''; //CP扩展参数
$sign = isset($urldata['sign']) ? $urldata['sign'] : ''; // 签名

//money 参数为小数点后两位 
$money = number_format($money,2,'.','');

//1 校验参数合法性
if (empty($urldata) || empty($order_id) || empty($mem_id) || empty($app_id) || empty($money)
    || empty($order_status) || empty($paytime) || empty($attach) || empty($sign)){
    //CP添加自定义参数合法检测
    exit($fail);
    
}

//2 校验此单合法性
{
    $attach;
    //CP自定义参数，可以为CP的订单ID,与其他信息，校验平台订单合法性.建议英文与数字，请不要有特殊字符 UTF-8编码
}

//3 通过游戏id查询appkey
{
    //通过游戏ID查询到此游戏的appkey,
    $appkey = "de933fdbede098c62cb309443c3cf251";
}

// 4 拼接参数
{
    $paramstr = "order_id=".$order_id."&mem_id=".$mem_id."&app_id=".$app_id."&money=".$money."&order_status=".$order_status."&paytime=".$paytime."&attach=".$attach."&app_key=".$appkey;
    $verrifysign = md5($paramstr);
    
    if (0 == strcasecmp($verrifysign, $sign)){
        exit($success);
    }
}

exit($fail);