<?php
/**
* NoticeController.class.php UTF-8
* 消息控制
* @date: 2016年8月23日下午10:54:46
* @license 这不是一个自由软件，未经授权不许任何使用和传播。
* @author: ou <ozf@huosdk.com>
* @version: 1.0
*/
namespace Float\Controller;
use Common\Controller\AdminbaseController;
class NoticeController extends AdminbaseController{
	protected $notice_model;
	
	function _initialize() {
		parent::_initialize();
		//$_SESSION['username']="testone";
		//$_SESSION["mem_id"]="100242";
		$this->notice_model = M('gameNotice');
	}
	
	//浮点推送信息页面
	public function index(){
	    $app_id = $_SESSION['app_id'];
	    //$app_id = 60000;
	    $map['app_id'] = array('eq',$app_id);
	    $map['is_delete'] = array('eq',2);
	    $map['start_time'] = array('lt',time());
	    $noticedata = $this->notice_model->where($map)->order('update_time desc')->getField("id,title,create_time,update_time",true);
	    $this->assign("noticedata",$noticedata);
	    $this->display();
	}
	
	//浮点推送信息详细页面
	public function notice(){
	    $id = I('id');
	    $where['id'] = $id;
	    $notice = $this->notice_model->where($where)->field("title,create_time,update_time,content")->find();
	    $this->assign("notice",$notice);
	    $this->display();
	}
}