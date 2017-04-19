<?php
    require_once("config.php");
    $amount = $_POST['amount'];		//交易金额
    $ttb = $_POST['ttb'];
    $orderid = $_POST['orderid'];
    $username = $_POST['username'];
    $paytypeid = $_POST['paytypeid'];
    $productname = urldecode($productname);
    if (empty($orderid) || empty($amount) || empty($username) || empty($ttb) || empty($paytypeid)) {
        $str = "缺少参数，请重新提交";
        echo "<script type='text/javascript' >";
        echo "alert('".$str."');";
        echo "window.close();";
        echo "</script>";
        exit;
    }

    if ($amount * 11 < $ttb){
        $str = "参数错误，请重新提交";
        echo "<script type='text/javascript' >";
        echo "alert('".$str."');";
        echo "window.close();";
        echo "</script>";
        exit;
    }
    
    $sql = "select id from C_ptb_charge where order_id='".$orderid."'";
    $rs = mysql_query($sql);
    $checkorder = array();
    while ($row = mysql_fetch_assoc($rs)) {
        $checkorder = $row;
    }
    if (!empty($checkorder)) {
        $str = "订单己存在，请确认是您的付款单号再付款!";
        echo "<script type='text/javascript' >";
        echo "alert('".$str."');";
        echo "window.close();";
        echo "</script>";
        exit;
    }
    
    $BuyerIp = GetIP();										//用户支付时使用的网络终端IP
    
    $transtime	= time();													//交易时间
    $sql = "select id from c_members where username='".$username."'";
    $rs = mysql_query($sql);
    $userdate = array();
    while ($row = mysql_fetch_assoc($rs)) {
        $userdate = $row;
    }

    $mem_id = $userdate['id'];
    $data['order_id'] = $orderid;
    $data['mem_id'] = $mem_id;
    $data['money'] = $amount;
    $data['ptb_cnt'] = $ttb;
    $data['status'] = 1;
    $data['create_time'] = $transtime;
    $data['payway'] = $paytypeid;
    $data['flag'] = 3;
    $data['remark'] = "官网充值";
    $data['ip'] = $BuyerIp;
    $data["payway"] = $paytypeid;
    
    $sql = "insert into c_ptb_charge (order_id,flag,admin_id,mem_id,money,ptb_cnt,payway,ip,status,create_time) 
    value ('{$orderid}','3','0','{$mem_id}','{$amount}','{$ttb}','{$paytypeid}','{$BuyerIp}','1','{$transtime}')";
    $rs = mysql_query($sql);

    if (!$rs) {
        $str = "数据处理出错，请重新提交!";
        echo "<script type='text/javascript' charset='UTF-8'>";
        echo "alert('".$str."');";
        echo "window.close();";
        echo "</script>";
        exit;
    }
    
    // 取得当前IP
    function GetIP($type=0){
        //获取客户端IP
        if(!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        } else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if(!empty($_SERVER["REMOTE_ADDR"])) {
            $cip = $_SERVER["REMOTE_ADDR"];
        } else {
            $cip = "";
        }
        preg_match("/[0-9\.]{7,15}/", $cip, $cips);
        $cip = $cips[0] ? $cips[0] : 'unknown';
        unset($cips);
        if ($type==1) $cip = myip2long($cip);
        return $cip;
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta charset="utf-8" />
<meta content="initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" name="viewport" />
<title></title>
<link rel="stylesheet" href="css/chongzhi.css">
<script src="js/jquery-1.9.1.min.js"></script>
</head>
<body>
<div class="easyown">
        <form  class="easyown1" action="server.php" method="post">
                 <div class="easyown2">
                         <div id="order">订单号：<?php echo $orderid; ?></div>
                         <div  id="payment">支付金额：<?php echo $amount; ?>元</div>
                 </div>
                <div class="second">
                    <div class="easyown3">
                    <div>充值方式：</div>
                    <div>
                        <div> <input type="radio" name="cardtype" checked value="0">移动</div>
                        <div> <input type="radio" name="cardtype" value="1">联通</div>
                        <div> <input type="radio" name="cardtype" value="2">电信</div>
                    </div>
                </div>
                <div class="easyown3">
                 	 <div>充值卡面额：</div>
                 	 <div>
                       <div><input type="radio" name="cardMoney"  value="10">10元</div>
                       <div><input type="radio" name="cardMoney"  value="20">20元</div>
                       <div><input type="radio" name="cardMoney"  checked value="50">50元</div>
                       <div><input type="radio" name="cardMoney" value="100">100元</div>
                       <div><input type="radio" name="cardMoney" value="300">300元</div>
                       <div><input type="radio" name="cardMoney" value="500">500元</div>
                    </div>
                </div>
                <div class="easyown4">
                	<div>充值卡序列：</div>
                    <div>
                       <input type="text"  id="sn" name="sn" style="letter-spacing:2px;width:300px;" maxlength="25" />
                       <input type="hidden" name="order" value="<?php echo $orderid;?>"/>
                    </div>
                </div>
                 <div class="easyown5">
                	<div>充值卡密码：</div>
                 	<div>
                      <input id="password" type="text" name="password" style="letter-spacing:2px; width:300px;" maxlength="25"/>
                    </div>
                </div>
              </div>
              <div class="easyown6"> 
                 <input type="submit"  value="确认支付" /> 
              </div>
        </form>
 </div>

 <div class="explain">
        <div><img src="images/aixin.png"><span>温馨提示</span></div>
        <ul>
              <li>1、请确保充值金额与卡实际面额一致，否则会导致充值失败。</li>
               <li>2、支持中国移动、中国联通、中国电信发行的全国通用手机话费充值卡。</li>
        </ul>
		<ul>
			<li> </li>
			<li> </li>
			<li> </li>
			<li> </li>
			<li> </li>
			<li> </li>
			<li> </li>
			<li> </li>
	    </ul>
 </div>
</body>

</html>