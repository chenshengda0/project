<?php	
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
		    $time = time();
		    $sql = "select order_id,mem_id,money,ptb_cnt,status,create_time from c_ptb_charge where order_id='".$privateField."'";

		    $rs = mysql_query($sql);
		    $data = array();
		    
		    while ($row = mysql_fetch_assoc($rs)) {
		        $data = $row;
		    }
		    if (!empty($data) && $data['status'] == 1) {
    			$payMoney = ((float)$payMoney)/100;
    			$sql = "update c_ptb_charge set status=2,remark='".$orderId."' where order_id='".$privateField."'";
    			$rs = mysql_query($sql);
    				
    			if ($rs) {
    			    $check = checkPtb($data['mem_id'],$data['ptb_cnt'],$payMoney);
    			    if ($check) {
    			        echo '0000'; //如果交易完成 则返回'0000'通知系统
    			        exit;
    			    }
    			}
		    }
			//todo...支付成功
		}else{
			//todo...支付失败
		}
	}else{
		echo "二级签名校验失败！";
		while ($msg = openssl_error_string()){
			echo $msg . "<br/>\n";
		}
	}

}else{
	echo 'MD5校验失败';
}

//检查是否已经存在并更新
function checkPtb($mem_id,$ptb_cnt,$amount) {
    $time = time();
    $sql_ttb = "select id from c_ptb_mem where mem_id='".$mem_id."'";
    $rs_ttb = mysql_query($sql_ttb);
    $ttbarr = array();
    while ($row_ttb = mysql_fetch_assoc($rs_ttb)) {
        $ttbarr = $row_ttb;
    }

    if ($ttbarr) {
        $cksql = "update c_ptb_mem set remain=remain+".$ptb_cnt.",update_time=".$time.",total=total+".$ptb_cnt.",sum_money=sum_money+".$amount." where mem_id=".$mem_id;
    } else {
        $cksql = "insert into c_ptb_mem (mem_id,sum_money,remain,create_time,total) value ({$mem_id},{$amount},{$ptb_cnt},{$time},{$ptb_cnt})";
    }

    $result = mysql_query($cksql);

    if ($result) {
        return true;
    } else {
        return false;
    }
}
?>