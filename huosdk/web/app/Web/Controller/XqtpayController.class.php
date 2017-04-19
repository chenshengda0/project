<?php

/**
 * 游戏充值
 * @ou
 * @2016-4-7
 */
namespace Web\Controller;

use Web\Controller\PayController;

class XqtpayController extends PayController {
    private $merchant_id, $merchant_key;
    function _initialize() {
        define('IN_SYS',TRUE);
        // 包含配置文件
        $conffile = SITE_PATH . "conf/pay/xqtpay/config.php";
        if (file_exists($conffile)) {
            $xqtpayconf = include $conffile;
        } else {
            $xqtpayconf = array();
        }
        
        $this->merchant_id = $xqtpayconf["merchant_id"]; // 星启天商户号
        $this->merchant_key = $xqtpayconf["merchant_key"]; // 星启天签名
    }
    
    // 星启天支付函数
    function xqtpay() {
        header("Content-type:text/html;charset=utf-8");
        if (IS_POST) {
            $data = $this->_insertpay();
            if (empty($data['order_id'])) {
                $this->error("内部服务器发生错误");
                exit();
            }
            
            $notify_url = WEBSITE . U('Web/Xqtpay/xqtpay_notify');
            $return_url = WEBSITE . U('Web/Xqtpay/xqtpay_return');
            
            $customerid=$this->merchant_id;
            $sdcustomno=$data['order_id'];
            $orderAmount=$data['money']*100;
            $cardno="32";
            $noticeurl=$notify_url;
            $backurl=$return_url;
            $key = $this->merchant_key;
            $mark = "pc recharge";
            $Md5str='customerid='.$customerid.'&sdcustomno='.$sdcustomno.'&orderAmount='.$orderAmount.'&cardno='.$cardno.'&noticeurl='.$noticeurl.'&backurl='.$backurl.$key;
            $sign=strtoupper(md5($Md5str));
            
            $gourl='http://www.zhifuka.net/gateway/weixin/weixinpay.asp?customerid='.$customerid.'&sdcustomno='.$sdcustomno.'&orderAmount='.$orderAmount.'&cardno='.$cardno.'&noticeurl='.$noticeurl.'&backurl='.$backurl .'&sign='.$sign.'&mark='.$mark;
            echo "<script language=\"javascript\">";
            echo "document.location=\"".$gourl."\"";
            echo "</script>";
        }
    }
    
    /**
     * 星启天服务器异步回调函数
     */
    function xqtpay_notify() {
        $state=trim($_GET["state"]);            // 1:充值成功 2:充值失败
        $customerid=trim($_GET["customerid"]);	//商户注册的时候，网关自动分配的商户ID
        $sd51no=trim($_GET["sd51no"]);          //该订单在网关系统的订单号
        $sdcustomno=trim($_GET["sdcustomno"]);  //该订单在商户系统的流水号
        $ordermoney=trim($_GET["ordermoney"]);  //商户订单实际金额单位：（元）
        $cardno=trim($_GET["cardno"]);          //支付类型，为固定值 32
        $mark=trim($_GET["mark"]);              //未启用暂时返回空值
        $sign=trim($_GET["sign"]);              //发送给商户的签名字符串
        $resign=trim($_GET["resign"]);          //发送给商户的签名字符串
        $des=trim($_GET["des"]);                //描述订单支付成功或失败的系统备注
        
        $key=$this->merchant_key;  //key可从星启天网关客服处获取

        $sign2=strtoupper(md5("customerid=".$customerid."&sd51no=".$sd51no."&sdcustomno=".$sdcustomno."&mark=".$mark."&key=".$key));
        $resign2=strtoupper(md5("sign=".$sign."&customerid=".$customerid."&ordermoney=".$ordermoney."&sd51no=".$sd51no."&state=".$state."&key=".$key));
        
        if($sign!=$sign2)
        {
            $msg = "签名不正确";
            $this->returninfo($msg);
        }
        if($resign!=$resign2)
        {
            $msg = "签名不正确";
            $this->returninfo($msg);
        }
        
        if($state=="1")
        {
            $this->paypost($sdcustomno ,$ordermoney);
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
        } else{
            //异常处理部分（可选）,根据自己系统而定
            echo "<result>0</result>";   //当返回<result>0</result>时星启天网关系统会继续通知
            //记录订单处理日志
        }
    }
    
    /**
     * 星启天支付通知页面
     */
    function xqtpay_return() {
        
        $state=trim($_GET["state"]);            // 1:充值成功 2:充值失败
        $sdcustomno=trim($_GET["sdcustomno"]);  //该订单在商户系统的流水号
        $ordermoney=trim($_GET["orderMoney"]);  //商户订单实际金额单位：（元）
        $paysite = WEBSITE.U("Web/Pay/index");

        $html = "<!DOCTYPE HTML>";
        $html .= "<html>";
        $html .= "<head>";
        $html .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
        $html .= "<link href='public/pay/css/toper.css' rel='stylesheet' type='text/css'>";
        $html .= "<style type='text/css'>";
        $html .= "	.cz_ba p{ line-height:22px;}";
        $html .= "	.cz_b{width:600px; margin:0 auto; padding-top:50px;}";
        $html .= "	.cz_ba{ background:url(../images/cg.jpg) no-repeat; padding-left:80px;}";
        $html .= "	.mna{padding-top:10px;}";
        $html .= "	.mna a{ color:#006699; padding:0 6px;}";
        $html .= "	.cz_ann{ height:30px; padding:0 10px;}";
        $html .= "</style>";
        
        if($state=="1") {
            $html .= "<div class='cz_b'>";
            $html .= "<div class='cz_ba'>";
            $html .= "<p style='font-size:16px; font-weight:bold;'>恭喜您，充值成功！</p>";
            $html .= "<p style='border-bottom:1px solid #e0e0e0; padding-bottom:10px; line-height:20px;'>如果查询未到账可能是运营商网络问题而导致暂时充值不成功，请联系客服。</p>";
            $html .= "<p class='mna'>订单号：".$sdcustomno."</p>";
            $html .= "<p>充值金额：".$ordermoney."</p>";
            $html .= "<p style='margin-top:20px;'><a href=".$paysite."><input type='button' value='返回充值中心' class='cz_ann'/></a></p>";
            $html .= "</div>";
            $html .= "</div>";
        } else {
            $html .= "<div class='cz_b'>";
            $html .= "<div class='cz_ba'>";
            $html .= "<p style='font-size:16px; font-weight:bold;'>充值失败，请重试！</p>";
            $html .= "<p style='margin-top:20px;'><a href=".$paysite."><input type='button' value='返回充值中心' class='cz_ann'/></a></p>";
            $html .= "</div>";
            $html .= "</div>";
        }
        $html .= "<title>微信充值</title>";
        $html .= "</head>";
        $html .= "<body>";
        $html .= "</body>";
        $html .= "</html>";
        echo $html;
    }
}
