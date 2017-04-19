<?php
namespace Mobile\Controller;

use Common\Controller\MobilebaseController;

class PayDetailController extends MobilebaseController {
    protected $row=5;
    
    /**
     *  初始化函数
     *  $access public
     *  $return string
     */
    function _initialize() 
    {
        parent::_initialize();
    }
    
    /**
     * 平台币充值记录walle/charge_detail
     * $access public
     * $param int $status 支付的状态信息
     * $param int $page 页
     * $param int $offset 每页数量
     * $param int $payway 支付方式
     * $return string
     */
    public function PayData(int $status, int $page, int $offset,$payway=0) {
        if (!empty($status)) {
            $map['p.status'] = $status;
        }
		
		if (!empty($payway)) {
            $map['p.payway'] = $payway;
        }
        
        if (empty($page)) {
            $page = 0;
        }
        $page = (int)$page;
        
        if (empty($offset)) {
            $offset = $this->row;
        }
        $offset = (int)$offset;
        
        $field = "p.order_id orderid,IF(p.status=1,'待支付','支付完成') status,p.amount amount,FROM_UNIXTIME(p.create_time, '%Y-%m-%d %T') createtime,g.name gamename";
        $limit = $page*$offset.','.$offset;
        
        $map['mem_id'] = sp_get_current_userid();
        $offset = $this->row;
        
        $rdata = M('pay')->alias('p')->field($field)->join('left join '.C('DB_PREFIX').'game g on g.id=p.app_id')->where($map)->order('p.create_time desc')->limit($limit)->select();

        return $rdata;
    }
}