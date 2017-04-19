<?php 
namespace App\Controller;
use Common\Controller\AdminbaseController;

class BaomingController extends AdminbaseController{
    
	/**
	 * 包名界面
	 */
	public function index(){
		$this->indexList();
		$this -> display();
	}
	 /**
	 * 包名列表
	 */
	public function indexList(){
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
			
		$result = array();
		$model = M('app_baoming');

		$where = 1;
		if (isset($_POST['name']) && $_POST['name'] != '') {
			$where .= " and name='".$_POST['name']."'";
		}
		if (isset($_POST['gameid']) && $_POST['gameid'] >0) {
			$where .= " and gameid=".$_POST['gameid'];
		}

		$result["total"] = $model->count();
		$page = $this->page($result["total"], $rows);
		$items = $model->where($where)->order('id DESC')->limit($page->firstRow . ',' . $page->listRows)->select();
		
		$game = M('appgame');
		$gamearr = $game -> where(' isdelete = 0')->select(); //查询游戏列表
		foreach ($gamearr as $key => $val) {
			$gamelist[$val['id']] = $val['name'];
		}
		foreach ($items as $key => $val) {
			$items[$key]['game'] = $gamelist[$val['gameid']];
		}
		$this->assign("page", $page->show('Admin'));
		$this->assign('items',$items);	
	}
	
	public function gameList(){
		$game = M('appgame');
		$gamearr = $game -> where(' isdelete = 0')->select(); //查询游戏列表	
		
		$this->assign("gamearr",$gamearr);
	}
	
	public function addBao(){
		$this->gameList();
		$this->display();
	}
	/**
	 * 添加包名
	 */
	public function addBao_post() {
		$data['gameid'] = I("gameid");
		$data['name'] = I("name");
		$data['create_time'] = time();
		$model = M('baoming');
		$check = $model ->where("gameid = '%s'",$data['gameid'])->find();
		if ($check){
			$this->error("该游戏包名已添加。");
		}
		
		$rs = $model->data($data)->add();
		if ($rs) {
			$this->success("添加包名成功。");
		}else{
			$this->error("添加包名失败。");
		}
		exit;
	}
	public function editBao(){
		$baoid = I("id");
		$model = M('baoming');
		$items = $model ->where("id = '%s'",$baoid)->find();
		$appgame = M("appgame");
		$game = $appgame -> where("id = '%s'",$items['gameid'])->find();
		$this->assign("game",$game);
		$this->assign("items",$items);
		$this->display();
	}
	/**
	 * 保存修改的包名
	 */
	public function editBao_post() {
			$gameid = I("gameid");
			$data['name'] = I("name");
			$model = M('baoming');
			$rs = $model -> where("gameid='%s'",$gameid)->data($data)->save();//update
			if ($rs) {
				$this->success("修改成功。");
			}else{
				$this->error("修改失败。");
			}
			exit;
	}
			
	/**
	 * 删除包名
	 */
	public function remove() {
		$baoid = isset($_POST['id']) ? $_POST['id'] : '';
		if($baoid != ''){
			$model = M('baoming');
			$rs = $model -> where("id= '%s'",$baoid)-> delete();//删除游戏信息
			if($rs){
				$this->success("删除成功。");
			}else{
				$this->error("删除失败。");
			}
		}
		$this->error("删除失败。");
	}


}