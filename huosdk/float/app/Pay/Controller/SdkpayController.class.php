<?php
/**
 * SdkpayController.class.php UTF-8
 * 游戏内SDK充值
 * @date: 2016年7月29日下午8:50:14
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : H5 2.0
 */
namespace User\Controller;

use Common\Controller\SdkbaseController;

class SdkpayController extends SdkbaseController {
    function do_pay() {
        $type = I('post.type');
        switch ($type) {
            case 1 : // 平台币
                $pay = new PayController();
                $pay->_ptbpay();
                break;
            case 3 : // 3, alipay, 支付宝, 支付宝支付, 1
                $pay = new AlipayController();
                $pay->alipay();
                break;
            case 6 : // '6', 'yeepay', '银行卡', '易宝支付', '1'
                $pay = new YeepayController();
                $pay->yeepay();
                break;
            case 7 : // 7, shengpay, 卡类, 盛付通支付, 1
                $pay = new ShengpayController();
                $pay->shengpay();
                break;
            case 9 : // 9, heepay, 微信, 汇付宝, 1
                $pay = new HeepayController();
                $pay->heepay();
                break;
            case 11 : // 11, tenpay, 财付通, 财付通支付, 1
                $pay = new TenpayController();
                $pay->tenpay();
                break;
            default :
                $this->error("参数错误");
        }
    }
}
