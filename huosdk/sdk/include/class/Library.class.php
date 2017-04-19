<?php
/*
 * 公共方法集合类
 */
class Library{
	//获取短信验证码
	public static function getSms($mobile,$smstemp,$product=''){
	    return self::alidayuSend($mobile,$smstemp,$product);
	}
	
	/* 获取短信验证码,阿里大鱼
	 * $mobile 电话号码
	*/
	public static function alidayuSend($mobile,$smstemp,$product=''){
	    include(SITE_PATH.'thinkphp/Core/Library/Vendor/taobao/TopSdk.php');
	    require_once(SITE_PATH.'thinkphp/Core/Library/Vendor/taobao/top/TopClient.php');
	    require_once(SITE_PATH.'thinkphp/Core/Library/Vendor/taobao/top/request/AlibabaAliqinFcSmsNumSendRequest.php');
	
	    //获取阿里大鱼配置信息
    	if(file_exists(SITE_PATH."conf/alidayu.php")){
            $dayuconfig = include SITE_PATH."conf/alidayu.php";
        }else{
            $dayuconfig = array();
        }
        
        if (empty($dayuconfig)){
            return FALSE;
        }
        
        if (empty($product)){
            $product =  $dayuconfig['PRODUCT'];
        }
        
        $sms_code = self::getSmsCode(4);   //获取随机码
        $_SESSION['sms_code'] = $sms_code;
	    $content = array(
	            "code" => "".$sms_code,
	            "product" => $product
	    );
	    
	    $c = new TopClient;
	    $c->appkey = $dayuconfig['APPKEY'];
	    $c->secretKey = $dayuconfig['APPSECRET'];
	    $req = new AlibabaAliqinFcSmsNumSendRequest;
	    $req->setExtend($dayuconfig['SETEXTEND']);
	    $req->setSmsType($dayuconfig['SMSTYPE']);
	    $req->setSmsFreeSignName($dayuconfig['SMSFREESIGNNAME']);
	    $req->setSmsParam(json_encode($content));
	    $req->setRecNum($mobile);
	    $req->setSmsTemplateCode($dayuconfig[$smstemp]);

	    $resp = $c->execute($req);
	    $resp = (array)$resp;
	    
	    if (!empty($resp['result'])){
	        $result =  (array)$resp['result'];
	        $data['code'] =  (int)$result['err_code'];
	        $data['msg'] =  '发送成功';
	    }else{
	        $data['code'] =  (int)$resp['code'];
	        $data['msg'] =  $resp['msg'].$resp['sub_msg'];
	    }
	    	    
	    return $data;
	}

	//随机生成 5 个字母的字符串
	public static function getRndstr($length=5){
		$str='abcdefghijklmnopqrstuvwxyz';
		$rndstr = '';
		for($i=0;$i<$length;$i++){
			$rndcode=rand(0,25);
			$rndstr.=$str[$rndcode];
		}
		return $rndstr;
	}
	
	//随机生成 6 位数字短信码
	public static function getSmsCode($length=6){
			return str_pad(mt_rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
	}
	
	//数据去重复并重新排序
	public static function arrayUnique($array){
		$out = array();
		foreach ($array as $key=>$value){
			if (!in_array($value, $out)){
				$out[$key] = $value;
			}
		}
		sort($out);
		return $out;
	}
	//弧度转换
	public static function rad($d){
		return $d * 3.1415926535898 / 180.0;
	}
	/**
	 * @desc 根据经纬度,计算两点之间公里数
	 * @param float $lat 纬度值
	 * @param float $lng 经度值
	 */
	public static function GetDistance($lat1, $lng1, $lat2, $lng2){
	     $EARTH_RADIUS = 6378.137;
		 $radLat1 = Library::rad($lat1);
	     $radLat2 = Library::rad($lat2);
	     $a = $radLat1 - $radLat2;
	     $b = Library::rad($lng1) - Library::rad($lng2);
	     $s = 2 * asin(sqrt(pow(sin($a/2),2) +
		 cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
	     $s = $s *$EARTH_RADIUS;
	     $s = round($s * 10000) / 10000;
	     return $s;
	}
	
	// 获取IP
	public static function get_client_ip() {
	    $client_ip = "";
	    if (getenv('HTTP_CLIENT_IP')) {
	        $client_ip = getenv('HTTP_CLIENT_IP');
	    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
	        $client_ip = getenv('HTTP_X_FORWARDED_FOR');
	    } elseif (getenv('REMOTE_ADDR')) {
	        $client_ip = getenv('REMOTE_ADDR');
	    } else {
	        $client_ip = $_SERVER['REMOTE_ADDR'];
	    }
	    return $client_ip;
	}
	
	//根据出生年月日计算年龄
	public static function getAge($YTD){
		 $YTD = strtotime($YTD);//int strtotime ( string $time [, int $now ] )
		 $year = date('Y', $YTD);
		 if(($month = (date('m') - date('m', $YTD))) < 0){
		  $year++;
		 }else if ($month == 0 && date('d') - date('d', $YTD) < 0){
		  $year++;
		 }
		 return date('Y') - $year;
	}
	//时间转换,友好时间格式
	public static function mdate($time = NULL){
		$text = '';
		$time = $time === NULL || $time > time() ? time() : intval($time);
		$t = time() - $time; //时间差 （秒）
		$y = date('Y', $time)-date('Y', time());//是否跨年
		switch($t){
			case $t == 0:
				$text = '刚刚';
				break;
			case $t < 60:
				$text = $t . '秒前'; // 一分钟内
				break;
			case $t < 60 * 60:
				$text = floor($t / 60) . '分钟前'; //一小时内
				break;
			case $t < 60 * 60 * 24:
				$text = floor($t / (60 * 60)) . '小时前'; // 一天内
				break;
			case $t < 60 * 60 * 24 * 3:
				$text = floor($time/(60*60*24)) ==1 ?'昨天 ' . date('H:i', $time) : '前天 ' . date('H:i', $time) ; //昨天和前天
				break;
			case $t < 60 * 60 * 24 * 30:
				$text = date('m月d日  H:i', $time); //一个月内
				break;
			case $t < 60 * 60 * 24 * 365&&$y==0:
				$text = date('m月d日', $time); //一年内
				break;
			default:
				$text = date('Y年m月d日', $time); //一年以前
				break;
		}
		return $text;
	}
	
	/**
	 * POST方式请求数据
	 *
	 * @param $url 请求的地址 ;
	 * @param $data_string 数据
	 *
	 * @return 加密字符串
	 *
	 */
	public static function  http_post_data($url, $data_string) {
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	    curl_setopt(
	            $ch,
	            CURLOPT_HTTPHEADER,
	            array(
	                    'Content-Type: application/json; charset=utf-8',
	                    'Content-Length: ' . strlen($data_string)
	            ));
	    ob_start();
	    curl_exec($ch);
	    $return_content = ob_get_contents();
	    ob_end_clean();
	
	    $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);
	    return $return_content;
	}
	
	/**
	 * 检查字符串是否表示金额,此金额小数点后最多带2位
	 *
	 * @param amount 需要检查的金额
	 * @return ： true－表示金额,false-不表示金额
	 */
	public static function checkAmount($amount) {
	    if (empty($amount)) {
	        return FALSE;
	    }
	    
	    $checkExpressions = "/^[0-9]+(.[0-9]{1,2})?$/";
	    if (preg_match($checkExpressions, $amount)){
	        $amount = round((float)$amount,2);
	        /* 金额小于0.01 返回错误 */
	        if ($amount<0.01){
	            return FALSE;
	        }
	        return TRUE;
	    }
	    
	    return FALSE;
	}
	
	//生成订单号
	public static function setorderid($mem_id) {
	    list($usec, $sec) = explode(" ", microtime());
	
	    // 取微秒前3位+再两位随机数+渠道ID后四位
	    $orderid = $sec . substr($usec, 2, 3) . rand(10, 99) . sprintf("%04d", $mem_id % 10000);
	    return $orderid;
	}
	
	//生成用户登录token
	public static function setUsertoken($mem_id,$code, $clientkey='') {
	    $time = time();
	    if (empty($clientkey) && !isset($_SESSION['clientkey'])){
	        $clientkey = $_SESSION['clientkey'];
	    }

	    $user_token = md5(md5($mem_id.$time.$code).$clientkey);
	    return $user_token;
	}
	
	//生成用户支付token
	public static function setPaytoken($order_id, $code, $clientkey='') {
	    $time = time();
	    if (empty($clientkey) && !isset($_SESSION['clientkey'])){
	        $clientkey = $_SESSION['clientkey'];
	    }
	
	    $pay_token = md5(md5($order_id.$code).$time.$clientkey);
	    return $pay_token;
	}
	
	/* 密码加密函数 */
	public static function pw_auth_code($pw,$authcode=''){
	    if(empty($authcode)){
	        $authcode=AUTHCODE;
	    }
	    
	    $result=md5(md5($authcode.$pw).$pw);
	    return $result;
	}
}




