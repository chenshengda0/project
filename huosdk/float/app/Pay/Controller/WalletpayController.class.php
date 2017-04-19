<?php

/**
 * SdkpayController.class.php UTF-8
 * SDK支付页面，所有支付在此
 * @date: 2016年6月27日下午4:20:46
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@1tsdk.com>
 * @version : 1.0
 */
namespace Pay\Controller;

use Common\Controller\MobilebaseController;

class WalletpayController extends MobilebaseController
{
    protected $pc_model;
    function _initialize() {
        parent::_initialize();
        $this->pc_model = M('ptb_charge');
    }
    
    // 获取支付方式
    function _payway($appid = NULL) {
        $paywaydata = M('payway')->where('status=2')->getField('payname payway,disc');
        return $paywaydata;
    }
    
    /**
     * 支付页面，选择支付
     * @date: 2016年6月27日下午4:21:52
     * 
     * @param arg 参数一的说明
     * @return array
     * @since 1.0
     */
    public function index() {
        // 获取玩家钱包信息
        
        // // $pay_token = I('pay_token');
        // // if (isset($_SESSION['pay_token'])){
        // // //支付点击进入 必须校验pay_token
        // // if(!empty($pay_token)){
        // // //验证是否有效客户
        // // if ($pay_token != $_SESSION['pay_token']){
        // // $this->error('玩家未登陆,或支付失效!');
        // // }
        
        // // $param_token = md5(md5($pay_token.'float').$_SESSION['clientkey']);
        // // $p_token = I('param_token');
        // // if ($param_token != $p_token){
        // // $this->error('玩家未登陆,或支付失效!');
        // // }
        // // }
        // // }else{
        // // $this->error('玩家未登陆,或支付失效!');
        // // }
        
        // // if (empty($_SESSION['order_id']) || empty($_SESSION['app_id'])){
        // // $this->error('参数错误');
        // // }
        // // $order_id = $_SESSION['order_id'];
        // // $app_id = $_SESSION['app_id'];
        // // $mem_id = $_SESSION['mem_id'];
        // // $pay_token = $_SESSION['pay_token'];
        // $mem_id = "24557";
        // $order_id = '1465922599287524640';
        // $pay_token = '123123123';
        // $app_id = 1;
        // $_SESSION['order_id'] = $order_id;
        // //获取支付方式
        // $paywaydata = $this->_payway($app_id);
        
        // //根据保留的订单号，获取订单信息
        // $paydata = $this->p_model->where(array('order_id'=>$order_id))->find();
        
        // if ($paydata['mem_id'] != $mem_id){
        // $this->error('参数错误');
        // }
        
        // if ($paydata['app_id'] != $app_id){
        // $this->error('参数错误');
        // }
        
        // if ($paydata['id']){
        // //根据保留的订单号，获取订单扩展信息
        // $payextdata = M('pay_ext')->where(array('pay_id'=>$paydata['id']))->find();
        // }
        
        // //钱包余额
        // $ptb_remain = M('ptb_mem')->where(array('mem_id'=>$mem_id))->getField('remain');
        // if (empty($ptb_remain)){
        // $ptb_remain = 0;
        // }
        // $ptb_remain = $ptb_remain/10;
        
        // $this->assign("paytoken", $pay_token);
        // $this->assign("remain", $ptb_remain);
        // $this->assign("payways", $paywaydata);
        // $this->assign("paydata", $paydata);
        // $this->assign("payextdata", $payextdata);
        $this->display();
    }
    
    /**
     * 支付post,需校验一些参数
     * 函数的含义说明
     * @date: 2016年6月27日下午4:32:05
     * 
     * @param arg 参数一的说明
     * @return array
     * @since 1.0
     */
    public function preorder() {
        $rand = I('post.randnum/d', 0);
        $pay_token = I('post.paytoken/s', '');
        $payway = I('post.paytype/s', '');
        $amount = I('post.money/d', 0);
        if ($amount <= 0) {
            $this->error("充值金额不正确，请输入整数");
        }
        
        if (empty($payway)) {
            $this->error("请选择充值方式");
        }
        // 通过支付方式获取支付编号
        $pay_controller = new \Common\Controller\PaybaseController();
        $pw_id = $pay_controller->getPaywayid($payway);
        if (empty($pw_id)) {
            $this->error("请选择充值方式");
        }
        if (empty($_SESSION['paytoken']) || strcmp($pay_token, $_SESSION['paytoken']) !== 0) {
            $this->error("非法参数");
        }
        
        $data = $this->_wallet_pay($amount, $pw_id);
        switch ($pw_id) {
            case 1 :
                {
                    break;
                }
            case 2 :
                {
                    break;
                }
            case 3 :
                {
                    $rdata['orderid'] = $data['order_id'];
                    $rdata['amount'] = $data['money'];
                    $rdata['productname'] = C('CURRENCY_NAME') . "充值";
                    $rdata['productdesc'] = C('CURRENCY_NAME') . "充值";
                    $rdata['notify_url'] = SDKSITE . U("Pay/Alipay/wallet_notify");                    
                    return $rdata;
                }
            case 4 :
                {
                    break;
                }
            case 5 :
                {
                    break;
                }
            case 8 :
                {
                    /* 微付通支付 */
                    $spay_controller = new SpayController();
                    $rdata = $spay_controller->pay($data);
//                     $rdata['orderid'] = $data['money'];
//                     $rdata['amount'] = $data['order_id'];
//                     $rdata['productname'] = C('CURRENCY_NAME') . "充值";
//                     $rdata['productdesc'] = C('CURRENCY_NAME') . "充值";
//                     $rdata['notify_url'] = SDKSITE . U("Pay/Alipay/alipay_walletnotify");
                    break;
                }
            default :
                {
                    $this->error("请选择充值方式");
                }
        }
    }
    private function _wallet_pay($amount, $pw_id) {
        if (empty($amount)) {
            $this->error("充值金额不正确，请输入整数");
        }
        
        if ($pw_id <= 0) {
            $this->error("请选择充值方式");
        }
        $data['order_id'] = setorderid();
        $data['flag'] = 3;
        $data['admin_id'] = 0;
        $data['mem_id'] = $_SESSION['user']['id'];
        $data['money'] = $amount;
        $data['ptb_cnt'] = $amount * 10;
        $data['discount'] = $amount * 10/$data['ptb_cnt'];
        $data['payway'] = $pw_id;
        $data['ip'] = get_client_ip();
        $data['status'] = 1;
        $data['create_time'] = time();
        
        $rs = M('ptb_charge')->add($data);
        if (!$rs) {
            $this->error("内部服务器发生错误");
        }
        
        return $data;
    }
}