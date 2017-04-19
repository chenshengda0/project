<?php
/**
* 礼包管理中心
*
* @author
*/
namespace Admin\Controller;
use Common\Controller\AdminbaseController;

class GiftController extends AdminbaseController {
	
	function _initialize() {
		parent::_initialize();
	}

	
	public function index(){
		//$this->redirect('Float/Gift/index');
	    $app_id = $_SESSION['app_id'];
	    $show = I('get.show');
        if (empty($show)){
            $show = 'giftlist';
        }
        
	    if($show == "mygift"){
	        $mem_id = sp_get_current_user();
	        $where['gf.app_id'] = $app_id;
	        $where['gfc.mem_id'] = $mem_id;
	        
	        $field = "gfc.code, gf.title, gf.content";
	        $giftdata = M('gift')
	        ->alias('gf')
	        ->field($field)
	        ->join("LEFT JOIN ".C('DB_PREFIX')."gift_code gfc ON gfc.gf_id=gf.id")
	        ->where($where)
	        ->order('gfc.update_time desc')
	        ->select();
	        
	        $this->assign('gifts', $giftdata);
	        $this->display('mygift');
	        exit;
	    } else {
	        $time = time();
	        $field = "id, title, app_id, content, start_time, end_time, remain, total";
	        $where['app_id'] = $app_id;
	        $where['end_time'] = array('GT',$time);
	        $where['remain'] = array('GT',0);
	        $where['is_delete'] = 2;
	        
	        $giftdata = M('gift')
	        ->field($field)
	        ->where($where)
	        ->order("start_time ASC")
	        ->select();
	        $this->assign('gifts', $giftdata);
	        $this->display();
	    }
	}
	
	public function giftAjax(){
	    $gfc_model = M('gift_code');
	    $gf_model = M('gift');
	    
	    $data['b'] = 0;
	    $data['a'] = 7;
	    $gf_id = I('post.giftid/d');
	    if (!empty($gf_id)) {
	        $time = time();	    
	        $mem_id = sp_get_current_user();
	        $cnt = M("gift_code")->where(array('gf_id'=>$gf_id,'mem_id'=>$mem_id))->count();
	        
	        //未领取过礼包才能领取
	        if (0 == $cnt) {
	            $app_id = $_SESSION['app_id'];
	            $rs = M("gift")->where(array('id'=>$gf_id, 'app_id'=>$app_id))->setDec('remain');
	            if($rs>0){
	                $field = "code, id";
	                $giftdata = $gfc_model->field($field)->where(array('gf_id'=>$gf_id,'mem_id'=>0))->find();
	                $rs = $gfc_model->where(array('id'=>$giftdata['id']))->setField('mem_id',$mem_id);
	                if ($rs) {
	                    $data['b'] = $gf_model->where(array('id'=>$gf_id, 'app_id'=>$app_id))->getField('remain');
	                    $data['a'] = $giftdata['code'];
	                    $this->ajaxReturn($data);
	                }
	            }else{
	                $data['a'] = '3';
	            }
	        } else {
	            $data['a'] = '5';
	        }
	    }
	    $this->ajaxReturn($data);	    
	}
}