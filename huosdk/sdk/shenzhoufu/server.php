<?php 
	session_start();
    define('SYS_ROOT', substr(dirname(__FILE__), 0, -11).'/');
    define('CLASS_PATH','include/class/');
    define('IN_SYS',TRUE);

    include ('config.php');
    require_once SYS_ROOT.'include/config.inc.php';
    require_once(SYS_ROOT.CLASS_PATH.'Switchlog.class.php');
    require_once(SYS_ROOT.CLASS_PATH.'Db.class.php');
    require_once(SYS_ROOT.CLASS_PATH.'Library.class.php');
	
    $db = new DB();
    $payinfo = $db->getPayinfo($_SESSION['order_id']);
	
    // 更新支付方式
    $db->CloseConnection();
    $cardTypeCombine = $_POST['cardtype'];          //充值卡类型  0：移动 1：联通 2：电信
    $cardMoney = $_POST['cardMoney'];   //充值卡面额[单位:元]
    $sn = $_POST["sn"];                 //充值卡序列号 
    $password = $_POST["password"];          //充值卡密码 
    
    $szfurl = $szf_config['szfurl'];   //神州行充值卡服务器直连接口入口
    $version = "3"; //接口版本号
    $merId = $szf_config['merId']; //商户ID
    $payMoney = (int)$payinfo['amount'] * 100;  //订单金额 单位：分
    $orderId = date('Ymd').'-'.$merId.'-'.$_SESSION['order_id'];  //商户网站形成的订单号，请按照神州付订单规范组织订单，同一商户的订 单号不能重复，长度 1-50 位之间。
    $returnUrl = $szf_config['serverReturnUrl']; //服务器返回地址

    $merUserName = $payinfo['mem_id'];  //玩家名称或者消费者用户名
    $merUserMail = "";  //长度 1-100 位之间 玩家邮箱或者消费者邮箱
    $privateField = $_SESSION['order_id']; //商户私有数据 可以传任意字母数字组成的字符串,回调的时候会传回给商户(可传空字符串)
    $verifyType = "1";                      //  数据校验方式 MD5 校验
    $desKey =  $szf_config['desKey'];      //DES 密钥
    $privateKey = $szf_config['privateKey'];        

    $cardInfo = GetDesCardInfo($cardMoney, $sn, $password, $desKey);   //充值卡加密信息

    $combineString = $version.$merId.$payMoney.$orderId.$returnUrl.$cardInfo.$privateField.$verifyType.$privateKey;
    $md5String = md5($combineString); //MD5 校验串
	
    //构造 url 请求数据
    $urlRequestData = $szfurl."?version=".$version
            ."&merId=".$merId
            ."&payMoney=".$payMoney
            ."&orderId=".$orderId
            ."&returnUrl=".$returnUrl
            ."&cardInfo=".urlencode($cardInfo)
            ."&merUserName=".$merUserName
            ."&merUserMail=".$merUserMail
            ."&privateField=".$privateField
            ."&verifyType=".$verifyType
            ."&cardTypeCombine=".$cardTypeCombine
            ."&md5String=".$md5String
            ."&signString=";

    $data = http_curl_get($urlRequestData);
    if (200 == $data['code'] && 200 == $data['data']){
		$title = "提交订单成功";
    }else{
        $error_code = include 'error_code.php';
        if (empty($error_code[$data['data']])){
			$title = "服务器异常";	
        }else{
			$title = $error_code[$data['data']];
        }
    }
	 
	$html = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";
	$html .= "<html>";
	$html .= "<head>";
	$html .= "<meta http-equiv='Content-Type' content='textml; charset=UTF-8'/>";
	$html .= "<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui' />";
	$html .= "<link rel='stylesheet' href='css/sub.css'>";
	$html .= "</head>";
	$html .= "<body>";
	$html .= "<div class='sub'>";
	$html .= "<div class='sub0' >";
	$html .= "<div class='sub1'>";
	$html .= "<div><img src='images/1.png'><span>{$title}</span></div>";
	$html .= "</div>";
	$html .= "<div  class='sub2'>充值结果请留意游戏内通知。</div>";
	$html .= "<div  class='sub3'> ";
	$html .= "<ul>";
	$html .= "<li><span>订单号：</span>{$orderId}</li>";
	$html .= "<li><span>充值金额：</span>{$payinfo['amount']}</li>";
	$html .= "</ul>";
	$html .= "</div>";
	$html .= "<div class='sub4'>系统正在处理中，到帐时间一般在10分钟内，请稍候留意账户！</div>";
	$html .= "<div class='sub5' onclick='backgame()' >返回游戏</div>";
	$html .= "</div>";
	$html .= "</div>";
	$html .= "</body>";
	$html .= "<script language='javascript'>"; 
	$html .= "function backgame(){";
	$html .= "	window.back_game.goToGame();";
	$html .= "}";
	$html .= "</script>";
	$html .= "</html>";
	echo $html;
	exit;
	echo "<html>"."<head><title></title>"."</head>" ."<body><p line-height:250px; font-size:16px; text-align:center;>".$msg."</p> " ."</body>" ."</html>";
	exit;
	
    function http_curl_get($url){
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);//获取内容url
        curl_setopt($curl,CURLOPT_NOBODY,0);//不返回html的body信息
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);//返回数据流，不直接输出
        curl_setopt($curl,CURLOPT_TIMEOUT,30); //超时时长，单位秒
        $rdata['data'] = curl_exec($curl);
        $rdata['code']= curl_getinfo($curl,CURLINFO_HTTP_CODE);
        curl_close($curl);
        return  $rdata;
    }
	
	function GetDesCardInfo($cardmoney,$cardnum,$cardpwd,$deskey){
		$str=$cardmoney."@".$cardnum."@".$cardpwd;	

		$size = mcrypt_get_block_size('des', 'ecb'); 

		$input = pkcs5_pad($str, $size);
		
		$td = mcrypt_module_open(MCRYPT_DES,'','ecb',''); //使用MCRYPT_DES算法,ecb模式   
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);   
		$ks = mcrypt_enc_get_key_size($td);   
		$key=base64_decode($deskey);
		mcrypt_generic_init($td, $key, $iv); //初始处理   
		//加密   
		$encrypted_data = mcrypt_generic($td, $input);   
		
		//结束处理   
		mcrypt_generic_deinit($td);   
		mcrypt_module_close($td); 
		/////作base64的编??
		$encode = base64_encode($encrypted_data); 
		return $encode; 
	}
         
	function pkcs5_pad ($text, $blocksize){    	
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);	
	}
?>