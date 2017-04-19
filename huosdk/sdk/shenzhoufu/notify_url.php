<?php	
include ('../include/common.inc.php');
include ('config.php');

$privateKey = $szf_config["privateKey"]; //密钥
$certFile   = $szf_config['certFile']; //神州付证书文件

//获得服务器返回数据
$version = $_REQUEST['version']; //版本号
$merId = $_REQUEST['merId']; //商户ID
$payMoney =$_REQUEST['payMoney']; //支付金额
$orderId = $_REQUEST['orderId']; //订单号
$payResult = $_REQUEST['payResult']; //支付结果 1:支付成功 0：支付失败
$privateField = $_REQUEST['privateField']; //商户私有数据
$payDetails = $_REQUEST['payDetails']; //支付详情
$cardMoney = $_REQUEST['cardMoney']; //充值卡面额[单位:元]
$md5String = $_REQUEST['md5String']; //MD5校验串
$signString = $_REQUEST['signString']; //证书签名

if ($cardMoney != null) {
    $combineString = $version."|".$merId."|".$payMoney."|".$cardMoney."|".$orderId."|".$payResult."|".$privateField."|".$payDetails."|".$privateKey;
} else {
    $combineString = $version.$merId.$payMoney.$orderId.$payResult.$privateField.$payDetails.$privateKey;
}

//进行MD5校验
$myMd5String=md5($combineString);

if($myMd5String==$md5String){
	echo "MD5校验成功!</br>";
	//校验证书签名
	$fp = fopen($certFile, "r");
	$cert = fread($fp, 8192);
	fclose($fp);
	$pubkeyid = openssl_get_publickey($cert);

	If(openssl_verify($myMd5String,base64_decode($signString),$pubkeyid,OPENSSL_ALGO_MD5)==1){
		echo $orderId;//响应服务器
		echo "二级签名校验成功！";
		//todo...商户业务逻辑
		if($payResult==1){
			$payMoney = ((float)$payMoney)/100;
			$db->doPaynotify($privateField, $payMoney, $orderId);
			//todo...支付成功
		}else{
			//todo...支付失败
		}
	}else
	{
		echo "二级签名校验失败！";
		while ($msg = openssl_error_string()){
			echo $msg . "<br/>\n";
		}

	}

}else{
	echo 'MD5校验失败';
}
?>