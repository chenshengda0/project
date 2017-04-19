<?php
/**
 * 初始化接口，检测版本
 */
include ('include/common.inc.php');

$data['user_token'] = 'rkmi2huqu9dv6750g5os11ilv2';
$data['mem_id'] = '23';
$data['app_id'] = '1';

$gameinfo = $db->getGameinfo((int)$data['app_id']);
$appkey = $gameinfo['app_key'];

$signstr = "app_id=".$data['app_id']."&mem_id=".$data['mem_id']."&user_token=".$data['user_token']."&app_key=".$appkey;

$data['sign'] = md5($signstr);
$data['appkey'] = $appkey;

$params = json_encode($data);
$url = "http://www.shouyoucun.cn/sdk/checkUsertoken.php";

$rdata = http_post_data($url, $params);
if($rdata){
	$data = (array)json_decode($rdata);
	if('1' == $data['status']){
		//CP操作,说明用户可用
		echo $data['data'];
	}
}

function http_post_data($url, $data_string) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);//设置等待时间
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//要求结果为字符串且输出到屏幕上
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json; charset=utf-8',
		'Content-Length: ' . strlen($data_string))
	);
	ob_start();
	curl_exec($ch);
	$return_content = ob_get_contents();
	ob_end_clean();
	return $return_content;
}
