<?php

/**
 * AlipayController.class.php UTF-8
 * 支付宝支付
 * @date: 2016年7月20日下午4:48:05
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : 1.0
 */
namespace Pay\Controller;

use Common\Controller\PaybaseController;

class AlipayController extends PaybaseController
{
    public function __construct() {
        if (file_exists(SITE_PATH . "conf/pay/alipay/config.php")) {
            $aliconf = include SITE_PATH . "conf/pay/alipay/config.php";
        } else {
            $aliconf = array();
        }
        $arr = array(
            'partner' => $aliconf['partner'],  // partner 从支付宝商户版个人中心获取
            'seller_email' => $aliconf['seller_email'],  // email 从支付宝商户版个人中心获取
            'key' => $aliconf['key'],  // key 从支付宝商户版个人中心获取
            'sign_type' => strtoupper(trim('MD5')),  // 可选md5 和 RSA
            'input_charset' => 'utf-8',  // 编码 (固定值不用改)
            'transport' => 'http',  // 协议 (固定值不用改)
            'cacert' => VENDOR_PATH . 'Alipay/cacert.pem',  // cacert.pem存放的位置 (固定值不用改)
            'notify_url' => SDKSITE . U('Pay/Alipay/alipay_notify'),  // 异步接收支付状态通知的链接
            'return_url' => SDKSITE . U('Pay/Alipay/alipay_return'),  // 页面跳转 同步通知 页面路径 支付宝处理完请求后,当前页面自 动跳转到商户网站里指定页面的 http 路径。 (扫码支付专用)
            'show_url' => SDKSITE . U('Pay/Alipay/alipay_return'),  // 商品展示网址,收银台页面上,商品展示的超链接。 (扫码支付专用)
            'private_key_path' => SITE_PATH . 'conf/pay/alipay/key/rsa_private_key.pem',  // 移动端生成的私有key文件存放于服务器的 绝对路径 如果为MD5加密方式；此项可为空 (移动支付专用)
            'public_key_path' => SITE_PATH . 'conf/pay/alipay/key/alipay_public_key.pem' /*移动端生成的公共key文件存放于服务器的 绝对路径 如果为MD5加密方式；此项可为空 (移动支付专用)*/
        );
        C('ALIPAY_CONFIG', $arr);
    }
    
    // 支付宝支付函数
    function pay($orderdata) {
        header("Content-type:text/html;charset=utf-8");
        $config = C('ALIPAY_CONFIG');
        $data = array(
            "service" => "alipay.wap.create.direct.pay.by.user", 
            "partner" => trim($config['partner']), 
            "seller_id" => trim($config['seller_id']), 
            "payment_type" => $payment_type, 
            "notify_url" => $notify_url, 
            "return_url" => $return_url, 
            "out_trade_no" => $out_trade_no, 
            "subject" => $subject, 
            "total_fee" => $total_fee, 
            "show_url" => $show_url, 
            "body" => $body, 
            "it_b_pay" => $it_b_pay, 
            "extern_token" => $extern_token, 
            "_input_charset" => trim(strtolower($alipay_config['input_charset'])) 
        );
        
        // 建立请求，请求成功之后，会通知服务器的alipay_notify方法，客户端会通知$return_url配置的方法
        $alipaySubmit = new \AlipaySubmit($config);
        $go_pay = $alipaySubmit->buildRequestForm($data, "get", "支付");
        echo $go_pay;
    }
    
    /*
     * 支付宝钱包
     */
    public function wallet_notify() {
        $wallet = true;
        $this->alipay_notify($wallet);
    }
    
    /**
     * notify_url接收页面
     */
    public function alipay_notify($wallet = false) {
        // 引入支付宝
        vendor('Alipay.AlipayNotify', '', '.class.php');
        $config = C('ALIPAY_CONFIG');
        $alipayNotify = new \AlipayNotify($config);
        // 验证支付数据
        $verify_result = $alipayNotify->verifyNotify();
        if ($verify_result) {
            
            /* 平台订单号 */
            $out_trade_no = $_POST['out_trade_no'];
            
            /* 支付宝交易号 */
            $trade_no = $_POST['trade_no'];
            
            /* 交易金额 */
            $amount = $_POST['total_fee'];
            
            // 交易状态
            $trade_status = $_POST['trade_status'];
            
            if ($trade_status == 'TRADE_FINISHED') {
            } else if ($trade_status == 'TRADE_SUCCESS') {
                // 支付成功后，修改支付表中支付状态，并将交易信息写入用户平台充值记录表ptb_charge。
                if ($wallet){
                    $this->wallet_post($out_trade_no,$amount, $trade_no);
                }else{
                    $this->sdk_post($out_trade_no,$amount, $trade_no);
                }
            }
            echo "success";
            // 下面写验证通过的逻辑 比如说更改订单状态等等 $_POST['out_trade_no'] 为订单号；
        } else {
            echo "fail";
        }
    }
    
    /*
     * return_url接收页面
     */
    public function alipay_return() {
        // 引入支付宝
        vendor('Alipay.AlipayNotify', '', '.class.php');
        $config = C('ALIPAY_CONFIG');
        $notify = new \AlipayNotify($config);
        // 验证支付数据
        $status = $notify->verifyReturn();
        if ($status) {
            // 下面写验证通过的逻辑 比如说更改订单状态等等 $_GET['out_trade_no'] 为订单号；
            $this->success('支付成功');
        } else {
            $this->success('支付失败');
        }
    }
}
