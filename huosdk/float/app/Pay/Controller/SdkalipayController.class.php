<?php
/**
* SdkalipayController.class.php UTF-8
* SDK支付页面，所有支付在此
* @date: 2016年6月27日下午4:20:46
* @license 这不是一个自由软件，未经授权不许任何使用和传播。
* @author: wuyonghong <wyh@1tsdk.com>
* @version: 1.0
* 
*/
namespace Pay\Controller;

class SdkalipayController extends SdkpayController{
	function _initialize() {
		parent::_initialize();
// 		import('Vendor.Alipaywap');
        Vendor('alipay.Alipaywap');
// 		import('Vendor.Alipayweb');
	}
	
	/* 支付宝提交函数  */
	function alipay_post(){
	    //根据保留的订单号，获取订单信息
	    $order_id = $_SESSION['order_id'];
	    $paydata = $this->p_model->where(array('order_id'=>$order_id))->find();
	    $payextdata = M('pay_ext')->where(array('pay_id'=>$paydata['id']))->find();
	    
	    $data['out_trade_no'] = $order_id;   //商户订单号，商户网站订单系统中唯一订单号，必填
	    $data['subject'] = $payextdata['productname'];   //订单名称，必填
	    $data['total_fee'] = $paydata['amount'];         //付款金额，必填
	    $data['body'] = $payextdata['productdesc'];        //商品描述，可空 
	    $data['show_url'] = U('Admin/User/index');
	    
	    $alipaywap = new \Alipaywap();
	    $html_text = $alipaywap->do_pay($data);
	    echo $html_text;
	    exit;
	}
	
	//支付宝回调服务器接口
	public function notify_url(){
	    $alipaywap = new \Alipaywap();
	    $rs = $alipaywap->Alipay_notify();
	    if (false == $rs){
	        echo "fail";
	    }else{
	        //返回数组说明回调成功
	        if (is_array($rs)){
	            $data = $rs;
	            $rs = $this->notify($data);
	            //逻辑处理,更新支付状态，做其他操作
	        }
	        echo "success";
	    }
	    exit;
	}
	
	//前端返回接口,通知到客户端
	public function return_url(){
	    $alipaywap = new \Alipaywap();
	    $data = $alipaywap->Alipay_return();
	    
	    if (false != $data){
	        //表明通知成功，显示充值成功,并返回游戏
	        $this->r_url(TRUE,$data['order_no']);
	    }else{
	        //表明通知失败
	        $this->r_url(FALSE);
	    }
	}
}