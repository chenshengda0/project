<?php
/**
* 礼包管理中心
*
* @author
*/
namespace Web\Controller;
use Common\Controller\AdminbaseController;

class ProblemController extends AdminbaseController {
	/**
	 * 问题列表
	 */
	public function index(){
		$this->indexList();
		$this -> display();
	}
	public function indexList(){
		$page = 1;	
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		//$offset = ($page-1)*$rows;
		
		$result = array();
		$mp_model = M('memProblem');

		$result["total"] = $total = $mp_model->count();
		$page = $this->page($result["total"], $rows);

		$items = $mp_model->order('id DESC')->limit($page->firstRow . ',' . $page->listRows)->select();
		$m_model = M('members');
		$gamedate = M('game');
		//获取游戏列表
    	$gamelist = $gamedate->field("id,name")->select();
		foreach ($gamelist as $key=>$val) {
    		$game[$val['id']] = $val['name'];
    	}

    	foreach ($items as $key=>$val) {
    		$members = $m_model->where("id='%d'",$val['mem_id'])->find();
    		$items[$key]['mem_id'] = $members['mem_id'];
    		$items[$key]['username'] = $members['username'];
			$items[$key]['game'] = $game[$val['app_id']];
    	}

		$this->assign("gamelist",$gamelist);
		$this->assign("items",$items);	
		$this->assign("page", $page->show('Admin'));
	}
  
 	/**
	 * 编辑问题
	 * 
	 * return void
	 */
    public function editProblem() {
    	$id = I("id");
    	$model = M('memProblem');
    	$myask = $model->where("id=".$id)->find();
    	$WEBSITE = WEBSITE;
    	$this->assign(WEBSITE,$WEBSITE);
		$this->assign('myask',$myask);
		
    	$this->display();
    }
    
    /**
     * 问题处理
     *
     * return void
     */
    public function editProblem_post() {
    	$id = I("id");
    	$data['status'] = I("status");
    	$model = M("memProblem");
    
    	$rs = $model->where("id=".$id)->save($data);
    	if($rs){
    		$this->success("处理成功。");
    	}else{
    		$this->error("处理失败。");
    	}
    }
    /**
     * 
     * 删除问题
     */
    public function delProblem() {
    	$id = I('id',0);
    	$myask = M('memProblem');
    	if ($id) {
    		$rs = $myask->where("id=".$id)->delete();
    		if ($rs) {
    			//$this->insertLog($_COOKIE['mgadmin2015'],2, 'ProblemAction.class.php', 'delProblem', time(),"删除问题：");
	    		$this->success("删除成功。");
				exit();
    		} else {
    			$this->error("删除失败。");	
				exit;
    		}
    	} else {
    		$this->error("删除失败。");	
			exit;
    	}
    }
}