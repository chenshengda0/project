<?php

/**
 * PaybaseController.class.php UTF-8
 * 钱包基类
 * @date: 2016年9月7日下午6:22:47
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : H5 2.0
 */
namespace Common\Controller;

use Common\Controller\AppframeController;

class PaybaseController extends AppframeController
{
    private $wallet_model;
    public function __construct() {
    }
    function _initialize() {
        parent::_initialize();
    }
    
    // 获取玩家钱包信息
    public function getUserwallet($mem_id) {
        $this->wallet_model = M('ptb_mem');
        if (empty($mem_id) || $mem_id < 0) {
            // 判断输入参数合法性
            return array();
        }
        $map['mem_id'] = $mem_id;
        $wallet_info = $this->wallet_model->where($map)->find();
        if (empty($wallet_info)) {
            return array();
        }
        return $wallet_info;
    }
    
    // 根据支付方式获取支付方式ID
    public function getPaywayid($payway) {
        if (empty($payway)) {
            return 0;
        }
        
        $map['payname'] = $payway;
        $pw_id = M('payway')->where($map)->getField('id');
        if (empty($pw_id)) {
            return 0;
        } else {
            return $pw_id;
        }
    }
    
    /*
     * 钱包支付通知函数
     */
    protected function wallet_post($orderid, $amount, $paymark = ''){
        $pay_model = M("ptb_charge");
        // 获取支付表中的支付信息
        $pay_data = $pay_model->where(array(
            'order_id' => $orderid
        ))->find();
        $myamount = number_format($pay_data['money'], 2, '.', '');
        $amount = number_format($amount, 2, '.', '');
        
        //判断金额是否正确
        if (($myamount == $amount) && 2 != $pay_data['status']) {
//         if (2 != $pay_data['status']) {            
            $pay_data['status'] = 2;
            $pay_data['remark'] = $paymark;
            $pay_data['update_time'] = time();
            
            // 将订单信息写入pay表中，并修改订单状态为2，即支付成功状态
            $rs = $pay_model->save($pay_data);
            if($rs){
                $this->updateWallet($pay_data);
            }
        }
    }
    
    private function updateWallet($paydata){
        $pm_model = M('ptb_mem');
        $map['mem_id'] = $paydata['mem_id'];
        $pm_data = $pm_model->where($map)->find();
        if (empty($pm_data)){
            $pm_data['mem_id'] = $paydata['mem_id'];
            $pm_data['sum_money'] = $paydata['money'];
            $pm_data['total'] = $paydata['ptb_cnt'];
            $pm_data['remain'] = $paydata['ptb_cnt'];
            $pm_data['create_time']  = time();
            $pm_data['update_time']  = $pm_data['create_time'];
            $pm_model->add($pm_data);
        }else{
            $pm_data['sum_money'] += $paydata['money'];
            $pm_data['total'] += $paydata['ptb_cnt'];
            $pm_data['remain'] += $paydata['ptb_cnt'];
            $pm_data['update_time']  = time();
            $pm_model->save($pm_data);
        }
    }
        
    protected function sdk_post($orderid, $amount, $paymark = '') {
        $pay_model = M("pay");
        // 获取支付表中的支付信息
        $pay_data = $pay_model->where(array(
            'order_id' => $orderid 
        ))->find();
        
        $myamount = number_format($pay_data['amount'], 2, '.', '');
        $amount = number_format($amount, 2, '.', '');
        
        // 判断充值金额与回调中是否一致，且状态不为2，即待支付状态
        if (($myamount == $amount) && 2 != $pay_data['status']) {
            $pay_data['status'] = 2;
            $pay_data['paymark'] = $paymark;
            $pay_data['update_time'] = time();
            // 将订单信息写入pay表中，并修改订单状态为2，即支付成功状态
            $rs = $pay_model->save($pay_data);
            
            // 判断订单信息是否修改成功
            if ($rs) {
                $this->updateMeminfo($pay_data);
                
                // 2.2.1 查询CP回调地址与APPKEY
                $game_data = $this->getGameinfobyid($pay_data['app_id']);
                $cpurl = $game_data['cpurl'];
                $app_key = $game_data['app_key'];
                
                $param['order_id'] = (string) $pay_data['order_id'];
                $param['mem_id'] = (string) $pay_data['mem_id'];
                $param['app_id'] = (string) $pay_data['app_id'];
                $param['money'] = (string) $myamount;
                $param['order_status'] = '2';
                $param['paytime'] = (string) $pay_data['create_time'];
                $param['attach'] = (string) $pay_data['attach'];
                
                $signstr = "order_id=" .
                         $pay_data['order_id'] . "&mem_id=" . $pay_data['mem_id'] . "&app_id=" . $pay_data['app_id'];
                $signstr .= "&money=" .
                             $pay_data . "&order_status=2&paytime=" . $pay_data['create_time'] . "&attach=" .
                             $pay_data['attach'];
                $md5str = $signstr . "&app_key=" . $app_key;
                
                $sign = md5($md5str);
                $param['sign'] = (string) $sign;
                
                // 2.2.3 通知CP
                if ($pay_data['cpstatus'] == 1 || $pay_data['cpstatus'] == 3) {
                    $i = 0;
                    while (1) {
                        $cp_rs = hs_payback($cpurl, $param);
                        if ($cp_rs > 0) {
                            $cpstatus = 2;
                            break;
                        } else {
                            $cpstatus = 3;
                            $i++;
                            sleep(2);
                        }
                
                        if ($i == 3) {
                            break;
                        }
                    }
                }
                // 更新CP状态
                $pay_model->where(array('id'=>$pay_data['id']))->setField('cpstatus',$cpstatus);
            }
        }
    }
    
    
    
    /*
     * 更新用户扩展支付信息
     */
    private function updateMeminfo(array $paydata) {
        $me_model = M('mem_ext');
        $map['mem_id'] = $paydata['mem_id'];
        $ext_data = $me_model->where($map)->find();
        $ext_data['order_cnt'] += 1;
        $ext_data['sum_money'] += $paydata['amount'];
        $ext_data['last_pay_time'] = $paydata['create_time'];
        $ext_data['last_money'] = $paydata['amount'];
        $me_model->save($ext_data);
    }
    
    /*
     * 获取单个游戏信息
     */
    private function getGameinfobyid($appid) {
        if ($appid <= 0 || empty($appid)) {
            return array();
        }
        $map['id'] = $appid;
        $game_data = M('game')->where($map)->find();
        if (empty($game_data)) {
            return array();
        }
        
        return $game_data;
    }
    	function _authPaypwd($repwd){
		if(empty($repwd)){
			$this->error("请输入二级密码！");
    		exit;
		}
		$user_obj = D("Common/Users");                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
		$uid=get_current_admin_id();
		$admin=$user_obj->where(array("id"=>$uid))->find();
			
		$repwd = sp_password($repwd);
			
		if($admin['pay_pwd'] != $repwd){
			$this->error("二级密码错误,操作失败！");
    		exit;
		}
	}
}