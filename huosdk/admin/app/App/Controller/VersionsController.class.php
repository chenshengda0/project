<?php 
/**
* APP版本管理页面
*
* @author
*/
namespace App\Controller;
use Common\Controller\AdminbaseController;
class VersionsController extends AdminbaseController{
	
	/**
	 * 初始界面
	 */
	public function versions(){
		$this->versionsList();
		$this -> display();
	}
	/**
	 * 列表
	 */
	public function versionsList(){
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
			
		$result = array();
		$model = M('appversion');

		$result["total"] = $total = $model->count();
		$page = $this->page($result["total"], $rows);
		$items = $model->order('id DESC')->limit($page->firstRow . ',' . $page->listRows)->select();

		$this->assign("items",$items);
		$this->assign("page", $page->show('Admin'));
	}
	
	public function insertApp(){
		$this->display();
	}
	/**
	 * 插入信息
	 */
	public function insertApp_post(){
		//获取数据
		$verdata['versions'] = I("versions");
		$verdata['url'] = I("url");
		$verdata['size'] = I("size");
		$verdata['content'] = I("content");
		$verdata['create_time'] = time();
		
		$vermodel = M("appversion");
		$rs = $vermodel->data($verdata)->add();
		if ($rs) {
			$this->success("添加成功。");
		}else{
			$this->error("添加失败。");
		}
	}
	public function modifyApp(){
		$id = I("id");
		$model = M("appversion");
		$version = $model ->where("id = '%s'",$id)->find();
		$this->assign("version",$version);
		$this->display();
	}
	/**
	 * 修改信息
	 */
	public function modifyApp_post(){
		$ver_id = isset($_POST['id']) ? $_POST['id'] : '';
		//获取数据
		$verdata['versions'] = I("versions");
		$verdata['url'] = I("url");
		$verdata['size'] = I("size");
		$verdata['content'] = I("content");
		$verdata['create_time'] = time();
		
		$vermodel = M("appversion");
		$rs = $vermodel-> where("id='".$ver_id."' ")->data($verdata)->save();
		if ($rs) {
			$this->success("修改成功.");
		}else{
			$this->error("修改失败.");
		}
	}
		
	/**
	 * 删除
	 */
	public function remove() {
		$versionid = isset($_POST['id']) ? $_POST['id'] : '';
		if($versionid != ''){
			$model = M('appversion');
			$rs = $model -> where("id='".$versionid."' ")-> delete();//删除游戏信息
			if($rs){
				$this->success("删除成功。");
			}else{
				$this->error("删除失败。");
			}
		}
		$this->error("删除失败。");
	}
}
	
?>