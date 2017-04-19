<?php

namespace Mobile\Controller;

use Common\Controller\MobilebaseController;

class ChargeDetailController extends MobilebaseController
{
    protected $row = 5;
    
    /**
     * 初始化函数
     * $access public
     * $return string
     */
    function _initialize() {
        parent::_initialize();
    }
    
    /**
     * 平台币充值记录walle/charge_detail
     * $access public
     * $param int $status 支付的状态信息
     * $param int $page 页
     * $param int $offset 每页数量
     * $return string
     */
    public function ChargeData(int $status, int $page, int $offset) {
        if (!empty($status)) {
            $map['status'] = $status;
        }
        
        if (empty($page)) {
            $page = 0;
        }
        $page = (int)$page;
        
        if (empty($offset)) {
            $offset = $this->row;
        }
        $offset = (int)$offset;
        
        $field = "order_id orderid,IF(status=1,'待支付','支付完成') status,money amount,FROM_UNIXTIME(create_time, '%Y-%m-%d %T') createtime, ptb_cnt ptbcnt";
        $limit = $page*$offset.','.$offset;
        
        $map['mem_id'] = sp_get_current_userid();
        $offset = $this->row;
        
        $rdata = M('ptb_charge')->field($field)->where($map)->order('create_time desc')->limit($limit)->select();
        return $rdata;
    }
}