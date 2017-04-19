<?php
namespace Sdk\Controller;
use Common\Controller\AdminbaseController;
class MemberController extends AdminbaseController{
	protected $game_model,$members_model, $where;
	
	function _initialize() {
		parent::_initialize();
		$this->members_model = M("members");
		$this->game_model = D("Common/Game");
	}

	function index(){
	    $this->_mem_status();
	    $this->_game();
	    $this->_agents();
	    $this->_mList();
		$this->display();
	}
	
	function edit(){
		$id= intval(I("get.id"));
		$member = $this->members_model->where("id=%d",$id)->find();
		$this->assign($member);
		$this->display();
	}
	
	function edit_post(){
		if (IS_POST) {
			$id = I("id");
// 			require_once(substr(MODULE_PATH, 0,-16)."web/include/uc_config.inc.php");
// 			require_once(substr(MODULE_PATH, 0,-16)."web/uc_client/client.php");

			if(!empty($id) && $id > 0){
				$password = I('password');
				
				if (empty($password)) {
					$this->error("密码不能为空");
					exit();
				}
				
				$data['id'] = $id;				
				$data['password'] = pw_auth_code($password,C("AUTHCODE"));
				
				if ($this->members_model->create($data)) {
					$rs = $this->members_model->where("id = %d",$id)->save();
					if($rs){
						$userdate = $this->members_model->field("username")->where("id = %d",$id)->find();
						$username = $userdate["username"];
// 						if($data = uc_get_user($username)) {
// 							$rid = uc_user_edit($username , $password , $password , '' , 1);
// 						}
						$this->success("修改成功！", U("Member/index"));
						exit();
					}
				}
				$this->error("修改失败");
			}else{
				$this->error("未找到玩家账号");
			}
		}
	}
	
	function ban(){
        $id = I('get.id/d');
    	if ($id) {
    		$rst = $this->members_model->where(array("id"=>$id))->setField('status','3');
    		if ($rst) {
    			$this->success("账号冻结成功！", U("Member/index",array('id'=>$id)));
    		} else {
    			$this->error('账号冻结失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    
    function cancelban(){
    	$id = I('get.id/d');
    	if ($id) {
    		$rst = $this->members_model->where(array("id"=>$id))->setField('status','2');
    		if ($rst) {
    			$this->success("账号解封成功！", U("Member/index",array('id'=>$id)));
    		} else {
    			$this->error('账号解封失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }

	/*
	 * 玩家列表
	 */
	function _mList(){
		$username = I('username');
		$start_time = I('start_time');
		$end_time = I('end_time');
		$mem_id = I('id/d');

		$where = " 1 ";
		$map = array();
		if(2<$this->role_type){
			$map = 'agent_id '.$this->agentwhere;
		}

		$where_arr = array();
		if (!empty($username) && $username != '') {
			$where .= " AND username like '%$username%'";
			$this->assign('username',$username);
		}

		if (!empty($start_time) && $start_time != '') {
    		$where .= " AND reg_time >= '".strtotime($start_time)."'";
			$this->assign('start_time',$start_time);
		}

		if (!empty($end_time) && $end_time != '') {
		    $where .= " AND reg_time <= '".strtotime($end_time)."'";
		    $this->assign('end_time',$end_time);
		}
		
		if (!empty($mem_id)){
		    $where .= " AND id = $mem_id";
		    $members = $this->members_model
                		->where($where)
                		->where($map)
                		->select();
		    
		    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
		    $page = $this->page(1, $rows);
		    
		    $this->assign('username',$members[0]['username']);
		}else{
		    $count=$this->members_model->where($where)->where($map)->count();
		    
		    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
		    $page = $this->page($count, $rows);
		    
		    $members = $this->members_model
		    ->where($where)
		    ->where($map)
		    ->order("id DESC")
		    ->limit($page->firstRow . ',' . $page->listRows)
		    ->select();
		}
		
		$this->assign("members",$members);
		$this->assign("Page", $page->show('Admin'));
	}
}