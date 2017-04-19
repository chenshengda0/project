<?php
/**
* 礼包管理中心
*
* @author
*/
namespace Sdk\Controller;
use Common\Controller\AdminbaseController;

class NoticeController extends AdminbaseController {
	
	protected $n_model;
	
	function _initialize() {
		parent::_initialize();
		$this->n_model = M('game_notice');
	}

	/**
	 * 信息列表
	 */
	public function index(){
	    $this->_game();
		$this->_nList();
		$this -> display();
	}
	
	/**
	** 推送信息列表
	*/
	public function _nList(){
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
		$title = I('title');
		$gameid = I('app_id');
		
		$result = array();
		$where = "is_delete =2";
		
		$where_arr = array();
		if (isset($title) && $title != '') {
			$where .= " and title='%s'";
			array_push($where_arr,$title);
			$this->assign('title',$title);
		}
		
		if (isset($gameid) && $gameid >0) {
			$where .= " and app_id=%d";
			array_push($where_arr,$gameid);
			$this->assign('app_id',$gameid);
		}
		
		$count = $this->n_model->where($where,$where_arr)->count();
		
		$page = $this->page($count, $rows);

		$noticelist = $this->n_model->where($where,$where_arr)->order("id DESC")->limit($page->firstRow . ',' . $page->listRows)->select();
		
		$this->assign('nlist', $noticelist);
		$this->assign("Page", $page->show('Admin'));
	}
	
	
	/**
	 * 
	 * 删除推送信息
	 */
	public function del() {
		$notice_id = I('id/d');
		
		if($notice_id>0){
			//伪删除信息
			$rs = $this->n_model->where("id=%d",$notice_id)->setField('is_delete', 1);
			if ($rs) {
				$this->success("删除成功", U("Notice/index"));
				exit;
			} else {
				$this->error("删除失败");
				exit;
			}
		}
		$this->error("参数错误");
	}
	
	public function add(){
	    $this->_game(false);
		$this -> display();
	}

	/**
	 * 添加推送信息
	 */
	public function add_post(){
	    if (IS_POST){
			//获取数据
			$n_data['app_id'] = I('app_id/d');
			$n_data['title'] = I('title','');
			$n_data['content'] = htmlspecialchars_decode(I("post.content"));
			$n_data['start_time'] = strtotime(I('start_time'));
			$n_data['create_time'] = time();
			$n_data['update_time'] = time();
			
			if (empty($n_data['title']) || empty($n_data['content'])
			    || empty($n_data['start_time'])){
			    $this->error("请填写完数据后再提交");
			}
			$n_id = $this->n_model->add($n_data);
            if ($n_id){
                $this->success("添加成功!", U("Notice/index"));
				exit;
            }
			$this->error("添加失败");
	    }
	    $this->error("参数错误");
	}

	/**
	 * 编辑推送信息
	 */
	public function edit(){
	    $id = I("id");
	    $data = $this->n_model->where(array("id"=>$id))->find();
	    $this->assign("data",$data);
	    $game = M('game')->where(array("id"=>$data['app_id']))->getField("name");
	    $this->assign("game",$game);
	    $this->display();
	}
	
	/**
	 * 编辑推送信息
	 */
	public function edit_post(){
	    if (IS_POST){
			//获取数据
			$id = I("id");
			$n_data['title'] = I('title','');
			$n_data['content'] = htmlspecialchars_decode(I("post.content"));
			$n_data['start_time'] = strtotime(I('start_time'));
			$n_data['update_time'] = time();
			
			if (empty($n_data['title']) || empty($n_data['content'])
			    || empty($n_data['start_time'])){
			    $this->error("请填写完数据后再提交");
			}
			$n_id = $this->n_model->where(array("id"=>$id))->save($n_data);
            if ($n_id){
                $this->success("修改成功!", U("Notice/index"));
				exit;
            }
			$this->error("修改失败");
	    }
	    $this->error("参数错误");
	}
}