<?php

/**
 * Wallet.php UTF-8
 * 钱包处理接口
 * @date: 2016年8月18日下午9:46:57
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : api 2.0
 */
namespace app\api\controller\v1;

use app\common\controller\Base;
use think\Db;

class Wallet extends Base
{
    function _initialize() {
        parent::_initialize();
    }

    /*
     * 读取用户信息
     */
    public function read(){
        $this->isUserLogin();
        $amount = $this->getUserWallet($this->mem_id);
        if ($amount<0){
            return hs_api_responce(400,'获取玩家余额失败');
        }
        $rdata['remain'] = $amount;
        return hs_api_responce(200,'获取余额成功',$rdata);
    }
    
    private function getUserWallet($mem_id){
        if ($mem_id<=0) {
            return -1;
        }
        
        $amount = Db::name('ptb_mem')->where(array('mem_id'=>$mem_id))->value('remain');
        if ($amount>0) {
            return $amount;
        }
        return 0;
    }
}
