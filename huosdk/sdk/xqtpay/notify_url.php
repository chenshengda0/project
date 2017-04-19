<?php
/*
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 * ************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */
// $begin = microtime(TRUE);
include ('../include/common.inc.php');
require_once ("config.php");

$state      = trim($_GET['state']);       //订单充值状态	1.充值成功 2.充值失败
$customerid = trim($_GET['customerid']);  //商户ID	商户注册的时候，网关自动分配的商户ID
$sd51no     = trim($_GET['sd51no']);      //sd51no	订单在网关的订单号	该订单在网关系统的订单号
$sdcustomno = trim($_GET['sdcustomno']);  //sdcustomno	商户订单号	该订单在商户系统的流水号
$ordermoney = trim($_GET['ordermoney']);  //ordermoney	订单实际金额	商户订单实际金额 单位：（元）
$cardno     = trim($_GET['cardno']);      //cardno	支付类型	固定值32为（微信）  36为（手机QQ）
$mark       = trim($_GET['mark']);        //mark	支付备注	固定值APP,不可修改为其他值，否则会导致验签失败
$sign       = trim($_GET['sign']);        //sign	md5签名字符串	发送给商户的签名字符串
$resign     = trim($_GET['resign']);      //resign	md5二次签名字符串	发送给商户的签名字符串
$des        = trim($_GET['des']);         //des	支付备注	描述订单支付成功或失败的系统备注

//以下只简述三步主要步骤，具体还需根据自己本身系统完成业务步骤  (*具体代码需自己实现*)

//**************************************************************************
//*第一步
//* 记录日志（记录接收到的 通知地址 和 参数）  以便日后查证订单，（*必须）
//**************************************************************************
//.........
$log = new Switchlog($huosdk_blogflag);
$message .= "\r\n接收参数 : " . json_encode($_GET);
$log->write($message,'xqtpay');

//**************************************************************************************************
//*第二步
//*验证处理
//*根据自己需要验证参数（1.验证是否为星启天通知过来的（可做IP限制）[必须] 2.验证参数合法性[可选]）
//**************************************************************************************************
$key=MERCHANT_KEY;  //key可从星启天网关客服处获取
$sign2=strtoupper(md5("customerid=".$customerid."&sd51no=".$sd51no."&sdcustomno=".$sdcustomno."&mark=".$mark."&key=".$key));
$resign2=strtoupper(md5("sign=".$sign."&customerid=".$customerid."&ordermoney=".$ordermoney."&sd51no=".$sd51no."&state=".$state."&key=".$key));

if($sign!=$sign2)
{
    echo "签名不正确";
    $db->CloseConnection();
    //记录日志
    exit();
}

if($resign!=$resign2)
{
    echo "签名不正确";
    $db->CloseConnection();
    //记录日志
    exit();
}

//**************************************************************************
//*第三步
//*商户系统业务逻辑处理
//**************************************************************************
if($state=="1")
{
    //当充值成功后同步商户系统订单状态
    //此处编写商户系统处理订单成功流程
    //............
    //............
    //商户在接受到网关通知时，应该打印出<result>1</result>标签，以供接口程序抓取信息，以便于我们获取是否通知成功的信息，否则订单会显示没有通知商户
    $db->doPaynotify($sdcustomno, $ordermoney, $sd51no);

    echo "<result>1</result>";
    //记录订单处理日志
}
else if($state=="2")
{
    //当充值失败后同步商户系统订单状态
    //此处编写商户系统处理订单失败流程
    //............
    //............
    //商户在接受到网关通知时，应该打印出<result>1</result>标签，以供接口程序抓取信息，以便于我们获取是否通知成功的信息，否则订单会显示没有通知商户
    echo "<result>1</result>";
    //记录订单处理日志
}
else{
    //异常处理部分（可选）,根据自己系统而定
    echo "<result>0</result>";   //当返回<result>0</result>时星启天网关系统会继续通知
    //记录订单处理日志
}
$db->CloseConnection();
?>