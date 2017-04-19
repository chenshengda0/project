<?php

/**
 * WalletController.class.php UTF-8
 * 钱包
 * @date: 2016年9月7日下午3:29:34
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : FLOAT 2.0
 */
namespace Mobile\Controller;

use Common\Controller\MobilebaseController;
use Common\Controller\PaybaseController;

class WalletController extends MobilebaseController
{
    protected $row;
    function _initialize() {
        parent::_initialize();
        $this->row = 5;
        // $this->_status();
    }
    
    // 钱包wallet/index(平台币)
    public function index() {
        $this->assign('title', "钱包");
        $this->display();
    }
    
    // 查询充值结果
    public function payresult() {
        $this->assign('title', "钱包");
        $this->display();
    }
    
    // 钱包余额充值walle/charge
    public function charge() {
        $pay_controller = new \Common\Controller\PaybaseController();
        $wallet_info = $pay_controller->getUserwallet($this->mem_id);
        $this->assign('wallet', $wallet_info);
        
        $_SESSION['paytoken'] = md5(sp_random_string(10));
        $this->assign('paytoken', $_SESSION['paytoken']);
        $this->assign('title', "充值");
		$contact = sp_get_help();
		$this->assign('qq',$contact['qq']);
		$this->assign('qqgroup',$contact['qqgroup']);
        $this->display();
        // $pay_controller = new \Pay\Controller\WalletpayController();
        // $pay_controller->index($this->mem_id);
    }
    public function preorder() {
        $pay_controller = new \Pay\Controller\WalletpayController();
        $pay_data = $pay_controller->preorder();
        
//         $pay_data['amount'] = 0.01; // 测试
        $data['payway'] = I('post.paytype/s', '');
        if (empty($pay_data)) {
            $data['status'] = 0;
            $data['token'] = '';
            $data['info'] = '内部错误';
        } else {
            $data['status'] = 1;
            $data['token'] = json_encode($pay_data);
        }
        $this->ajaxReturn($data);
    }
    
	
    // 充值记录walle/chare_detail
    public function charge_detail() {
        $status = I('status/d', 2);
        
        // 获取充值记录,$status为支付状态,默认为充值成功记录
        $chargeDetail = new ChargeDetailController();
        $rdata = $chargeDetail->ChargeData($status,0,$this->row);
        
        $count = count($rdata);
        if ($count<$this->row){
            $page=-1;
        }
        
        $this->assign('status', $status);
        $this->assign('page', $page);
        $this->assign('ptb_detail', $rdata);
        $this->assign('title', "钱包充值记录");
        $this->display();
    }
    
    // 显示更多充值记录
    public function charge_detail_more() {
        $status = I('get.status/d', 2);
        $page = I('get.page/d', 0);
        $chargeDetail = new ChargeDetailController();
        $data = $chargeDetail->ChargeData($status, $page, $this->row);
        $rdata['data'] = $data;
        
        $count = count($data);
        
        $rdata['page'] = $page;
        if ($count<$this->row){
            $rdata['page']=-1;
			$rdata['status'] = 1;
        }else{
            if (!empty($data)){
                $rdata['page']++;
				$rdata['status'] = 1;
            }
        }
        $rdata['status'] = $status;
       
        $this->ajaxReturn($rdata);
    }
	
    // 游戏消费记录walle/pay_detail
    public function pay_detail() {
		$status = I('status/d', 2);
        
        // 获取游戏消费记录,$status为支付状态,默认为充值成功记录
        $PayDetail = new PayDetailController();
        $rdata = $PayDetail->PayData($status,0,$this->row);
        
        $count = count($rdata);
        if ($count<$this->row){
            $page=-1;
        }
        
        $this->assign('payflag', 'pay');
        $this->assign('page', $page);
        $this->assign('pay_detail', $rdata);
		$this->assign('title', "消费记录");
        $this->assign('ptbpaytitle', "钱包消费记录");
		$this->assign('gamepaytitle', "游戏消费记录");
        $this->display();
    }
    
    // 显示更多消费记录
    public function pay_detail_more() {
		$status = I('get.status/d', 2);
        $page = I('get.page/d', 0);
        $chargeDetail = new PayDetailController();
        $data = $chargeDetail->PayData($status, $page, $this->row);
		
        $rdata['data'] = $data;
        
        $count = count($data);
        
        $rdata['page'] = $page;
        if ($count<$this->row){
            $rdata['page']=-1;
			$rdata['status'] = 1;
        }else{
            if (!empty($data)){
				$rdata['status'] = 1;
                $rdata['page']++;
            }
        }
       
        $this->ajaxReturn($rdata);
    }
    
    // 钱包消费记录
    public function wallet_detail() {
		$status = I('status/d', 2);
        
        // 获取游戏消费记录,$status为支付状态,默认为充值成功记录
        $PayDetail = new PayDetailController();
        $rdata = $PayDetail->PayData($status,0,$this->row,1);
        
        $count = count($rdata);
        if ($count<$this->row){
            $page=-1;
        }
        
        $this->assign('payflag', 'wallet');
        $this->assign('page', $page);
        $this->assign('pay_detail', $rdata);
		$this->assign('title', "消费记录");
        $this->assign('ptbpaytitle', "钱包消费记录");
		$this->assign('gamepaytitle', "游戏消费记录");
		$this->display('pay_detail');
    }
    
    // 显示更多钱包消费记录
    public function wallet_detail_more() {
		$status = I('get.status/d', 2);
        $page = I('get.page/d', 0);
        $chargeDetail = new PayDetailController();
        $data = $chargeDetail->PayData($status, $page, $this->row,1);
        $rdata['data'] = $data;
        
        $count = count($data);
        
        $rdata['page'] = $page;
        if ($count<$this->row){
            $rdata['page']=-1;
			$rdata['status'] = 1;
        }else{
            if (!empty($data)){
                $rdata['page']++;
				$rdata['status'] = 1;
            }
        }
       
        $this->ajaxReturn($rdata);
    }
}