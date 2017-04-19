<?php 
namespace App\Controller;
use Common\Controller\AdminbaseController;

class GametypeController extends AdminbaseController {
	/**
	 * 类型列表
	 */
	public function index(){
		$type = M('appgametype');
		$del = isset($_REQUEST['del']) ? $_REQUEST['del']: '';
		//删除类型
		if($del == 'delete'){
			$typeid = isset($_REQUEST['typeid']) ? $_REQUEST['typeid']: '';
			$rs = $type -> where("id='".$typeid."' ")-> delete();//删除
			$this->success("删除成功。");
		}

		$page = 1;
		$where = 1;
		if (isset($_REQUEST['name']) && $_REQUEST['name'] != '') {
			$where .= " and name='".$_REQUEST['name']."'";
			$this->assign('name',$_REQUEST['name']);
		}
		
		$total = $type->where($where)->count();
		$page = $this->page($total, 10);
		
		//查询数据列表
		$list = $type->where($where)->order('id DESC')->limit($page->firstRow . ',' .  $page->listRows)->select();
		$WEBSITE = WEBSITE;
		$this->assign(WEBSITE,$WEBSITE);
		$this->assign("page", $page->show('Admin'));
		$this -> assign('list',$list);
		$this -> display();
	}
	
	public function addType(){
		$this->display();
	}
	/**
	 * 添加类型
	 */
	public function addType_post() {

		if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add') {
			$data['name'] = I("name");
			$data['create_time'] = time();
			$model = M('appgametype');
			$check = $model ->where("name = '%s'",$data['name'])->find();
			if($check){
				$this->error("该类型已添加,请不要重复添加！");
			}
			//获取图标文件名
			$icofile = $_FILES['micon'];
				
			$iconame = '';
			if($icofile['name'][0] != ''){
				$iconame = $this -> checkImage($icofile,time()+1,0);
			}
			$data['ticon'] = $iconame;
			
			
			$rs = $model->data($data)->add();
			
			if ($rs) {
				$this->success("添加类型成功。");
			}else{
				$this->error("添加类型失败。");
			}
			exit;
		}
		
	}


	/**
	 * 跳转修改界面
	 */
	public function modifyType() {
		$typemodel = M('appgametype');
		$typeid = I("typeid");
		$typedata = $typemodel -> where("id='".$typeid."' ")-> select();//查询选中记录信息，用于编辑

		$this -> assign('typedata',$typedata[0]);
		
		$this -> display();
	}

	/**
	 * 保存修改
	 */
	public function saveType() {

		if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'type') {
			$typeid = I("typeid");
			$data['name'] = I("name");
			
			//获取图标文件名
			$icofile = $_FILES['micon'];
			$iconame = '';
			
			if($icofile['name'][0] != ''){
				$iconame = $this -> checkImage($icofile,time()+2,0);
				$data['ticon'] = $iconame;
			}
			
			$model = M('appgametype');
			$rs = $model -> where("id='".$type_id."' ")->data($data)->save();//update
			if ($rs) {
				$this->success("修改类型成功。");
			}else{
				$this->error("修改类型失败。");
			}
			exit;
		}
		
	}


	/**
		 * 上传图片
		 */
		public function checkImage($up_info,$time,$i){
		
			$arrType=array('image/jpg','image/gif','image/png','image/bmp','image/pjpeg','image/jpeg');
			$max_size='5242880';      // 最大文件限制（单位：byte）
			$upfile = C('UPLOADPATH')."image/";; //图片目录路径
			
			//$file = $imagefile;
			$fname = $up_info['name'][$i];
			
			if($_SERVER['REQUEST_METHOD'] == 'POST'){ //判断提交方式是否为POST
				
				if(!is_uploaded_file($up_info['tmp_name'][$i])){ //判断上传文件是否存在
					$this->error("文件不存在。");
				}
			   
				if($up_info['size'][$i]>$max_size){  //判断文件大小是否大于500000字节
					$this->error("上传文件太大。");
				} 
	
				if(!in_array($up_info['type'][$i],$arrType)){  //判断图片文件的格式
					$this->error("上传文件格式不对。");
				}
				if(!file_exists($upfile)){  // 判断存放文件目录是否存在
					 mkdir($upfile,0777,true);
				 } 
				$imageSize = getimagesize($up_info['tmp_name'][$i]);//图片大小
				$img = $imageSize[0].'*'.$imageSize[1];
				$ftypearr = explode('.',$fname);
				$ftype = $ftypearr[1];//图片类型
	
				$fname = $time.'.'.$ftype;
				$picName = $upfile.$fname;
					 
				if(file_exists($picName)){
					$this->error("同文件名已存在。");
				}
					
				if(!move_uploaded_file($up_info['tmp_name'][$i],$picName)){  
					$this->error("移动文件出错。");
						
				}
			}
			return  $fname;
		}


}