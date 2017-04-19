<?php
/**
* 消费充值相关数据
*
* @author
*/
namespace Mobile\Controller;
use Common\Controller\AdminbaseController;

class PayController extends AdminbaseController {
    protected  $row;
    function _initialize(){
        parent::_initialize();
        $this->row = 5;
        $this->_status();
    }
    
    
    public function _gm_list(){
        $gm_data = M('gm')->getField('app_id id, payname gmname');
        $this->assign('gmdata', $gm_data);
    }
    
    public function _status(){
        $p_data = array(
                '1'=>'待支付',
                '2'=>'支付完成',
                '3'=>'支付失败'
        );
        $this->assign('pstatus', $p_data);
    }
	
	//充值记录
	public function pay(){
	    if (IS_AJAX){
	        $this->pay_json();
	    }
	     
	    $this->assign('title',"消费记录");
	    $this->display();
	}
	
	//充值记录
	public function pay_json(){
	    $status = I('get.stat/d',0);
	    $page = I('get.page/d',0);
	    $row = I('get.row/d',$this->row);
	    $data = array();
	    if (0 == $status){
	        echo json_encode($data);
	        exit;
	    }
	    
	    $where['p.status'] = $status;
	    $where['p.mem_id'] = sp_get_current_user();
	     
	    $field = "p.amount money, p.order_id order_id, g.name gamename,DATE_FORMAT(FROM_UNIXTIME(p.create_time),'%Y-%m-%d %T') c_time";
	    $field .= ", IF(p.status=1,'待支付',IF(p.status=2,'支付完成', '支付失败')) as stat";
	    $data = M('pay')
	    ->alias('p')
	    ->field($field)
	    ->join("LEFT JOIN ".C('DB_PREFIX')."game g ON g.id=p.app_id")
	    ->where($where)
	    ->order('p.id DESC')
	    ->limit($page,$row)
	    ->select();
	    echo json_encode($data);
	    exit;
	}
	
    //充值记录
	public function charge(){
	    if (IS_AJAX){
	        $this->charge_json();
	    }
	    
	    $this->assign('title',"充值记录");
	    $this->display();
	}
	
    //充值记录
	public function charge_json(){
	    $status = I('get.stat/d',0);
	    $page = I('get.page/d',0);
	    $row = I('get.row/d',$this->row);
	    $data = array();
	    if (0 == $status){
	        echo json_encode($data);
	        exit;
	    }
	    
	    $where['gc.status'] = $status;
	    $where['gc.mem_id'] = sp_get_current_user();
	    
	    $field = "gc.money,gc.order_id, gm.payname gmname,DATE_FORMAT(FROM_UNIXTIME(gc.create_time),'%Y-%m-%d %T') c_time";
	    $field .= ", IF(gc.status=1,'待支付',IF(gc.status=2,'支付完成', '支付失败')) as stat";
	    $data = M('gm_charge')
	    ->alias('gc')
	    ->field($field)
	    ->join("LEFT JOIN ".C('DB_PREFIX')."gm gm ON gm.app_id=gc.app_id")
	    ->where($where)->limit($page,$row)->select();
	    echo json_encode($data);
	    exit;
	}
	
	
	//充值记录
	public function yxb(){
	    if (IS_AJAX){
	        $this->yxb_json(2);
	    }
	
	    $this->assign('title',"可用代金卷");
	    $this->display();
	}
	
	//充值记录
	public function yxb_history(){
	    if (IS_AJAX){
	        $this->yxb_json();
	    }
	    $this->assign('title',"代金卷历史");
	    $this->display('Pay/yxbhistory');
	}
	
	//充值记录
	public function yxb_json($option=null){
	    $page = I('get.page/d',0);
	    $row = I('get.row/d',$this->row);
	    $action = I('get.action/s','');
	    $data = array();
	    
	    if ('yxb' != $action){
	        echo json_encode($data);
	        exit;
	    }
	    
	    $where = "gmm.mem_id = ".sp_get_current_user();
	    $time = time();
	    if (2 == $option){
	        $where .= " AND gmm.update_time+gm.expire_time >".$time;
	    }
	    
	    $field = "gmm.remain gmcnt, DATE_FORMAT(FROM_UNIXTIME(gmm.update_time+gm.expire_time),'%Y-%m-%d %T') expiretime ";
	    $field .= ",gm.payname gmname";
	    $field .= ", IF(gmm.update_time+gm.expire_time<$time,'已过期','有效期') as stat";
	    $data = M('gm_mem')
	    ->alias('gmm')
	    ->field($field)
	    ->join("LEFT JOIN ".C('DB_PREFIX')."gm gm ON gm.app_id=gmm.app_id")
	    ->where($where)
	    ->order('gmm.id DESC')
	    ->limit($page,$row)
	    ->select();
	    
	    echo json_encode($data);
	    exit;
	}
}