<?php
define('SYS_ROOT', substr(dirname(__FILE__), 0, -11).'/');
define('CLASS_PATH','include/class/');
define('IN_SYS',TRUE);

require_once SYS_ROOT.'include/config.inc.php';
require_once(SYS_ROOT.CLASS_PATH.'Switchlog.class.php');
require_once(SYS_ROOT.CLASS_PATH.'Db.class.php');
require_once(SYS_ROOT.CLASS_PATH.'Library.class.php');

$session_id = $_POST['session_id'];
$user_token = $_POST['user_token'];
$pay_token = $_POST['pay_token'];
$param_token = $_POST['param_token'];
if (empty($session_id) || empty($user_token) || empty($user_token) || empty($user_token)){
	echo '请求数据错误';	
	exit;
}

session_id($session_id);
session_start();

if (isset($_SESSION['user_token']) && isset($_SESSION['pay_token'])){	
	// 浮点点击进入 必须校验user_token
	// 验证是否有效客户
	if ($user_token != $_SESSION['user_token']){
		echo '玩家未登陆,或登录失效!';
		exit;
	}
	
	// 验证支付token
	if ($pay_token != $_SESSION['pay_token']){
		echo '未支付,或登录失效!';
		exit;
	}
	
	$vp_token = md5(md5($user_token.$pay_token.'shenzhoufu').$_SESSION['clientkey']);
	if ($param_token != $vp_token){
		echo '数据错误';
		exit;
	}
}else{
	echo '请求数据错误';
	exit;
}	
$db = new DB();	
$payinfo = $db->getPayinfo($_SESSION['order_id']);
// 更新支付方式
$db->updatePayway($_SESSION['order_id'], 'shenzhoufu');
$db->CloseConnection();
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
                         <div id="order">订单号：<?php echo $payinfo['order_id']; ?></div>
                         <div  id="payment">支付金额：<?php echo $payinfo['amount']; ?>元</div>
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
                       <input type="hidden"  id="pay_token" name="pay_token" value="<?php echo $_SESSION['pay_token'];?>"/>
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