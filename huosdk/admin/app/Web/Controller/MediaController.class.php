<?php
/**
* 媒体管理
*
* @author
*/
namespace Web\Controller;
use Common\Controller\AdminbaseController;

class MediaController extends AdminbaseController {
	protected  $targets=array("_blank"=>"新标签页打开","_self"=>"本窗口打开");
	/**
	 *  媒体列表
	 */
	public function index(){
		$this->mlist();
		$this->display();
	}
	
	/**
	 * 媒体列表
	 */
	public function mlist(){
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;

		$model = M('webMedia');
		$result["total"] = $total = $model->count();
		$page = $this->page($result["total"], $rows);
		$items = $model->order('id DESC')->limit($page->firstRow . ',' . $page->listRows)->select();
		$WEBSITE = WEBSITE;
		$this->assign(WEBSITE,$WEBSITE);
		$this->assign('items',$items);
		$this->assign("page", $page->show('Admin'));
	}
    
	function add(){
		$this->assign("targets",$this->targets);
		$this->display();
	}
	
	/**
	 * 添加记录
	 */
	public function addMedia(){
		$model = M("webMedia");
		
		$data['url'] = I('url');
		$data['name'] = I('name');
		$data['create_time'] = time();
		$up_info=$_FILES['image'];
		$url = "";
		$data['icon'] = $this->checkImage($up_info,$url);
		
		//插入数据
		if($model->create($data)){
			$lastInsId = $model->add();
			$this->success("添加成功。");
		}else{
			$this->error("添加失败。");	
		}
	}

	/**
	* 删除合作媒体数据
	*/
	public function remove(){
		$mediaid = I('id');
		
		if($mediaid != ''){
			$gameDao = M('webMedia');
			$rs = $gameDao -> where("id='".$mediaid."' ")-> delete();//删除游戏信息
			if($rs){
				$this->success("删除成功。");
				exit();
			}else{
				$this->error("删除失败。");	
				exit;
			}
		}
		$this->error("无效数据。");
		exit;
	}
	
	public function edit(){
		$id = I("id");
		$model = M("webMedia");
		$WEBSITE = WEBSITE;
		$this->assign(WEBSITE,$WEBSITE);
		$items = $model -> where("id = '%s'",$id)->find();
		$this->assign("items",$items);
		$this->display();
	}
	
	//编辑合作媒体数据
	public function edit_post(){
		$id = I("id");
		$model = M("webMedia");
		$data['url'] = I('url');
		$data['name'] = I('name');
		$data['create_time'] = time();
		$up_info=$_FILES['image'];
		$url = "";

		if ($up_info['name']){
			$data['icon'] = $this->checkImage($up_info,$url);
		}
		
		//插入数据
		if($model->data($data)->where("id = '%s'",$id)->save()){
			$this->success("编辑成功。");
		}else{
			$this->error("编辑失败。");
		}
	}
	/**
	 * 上传图片
	 */
	public function checkImage($up_info,$url){
		
		$arrType=array('image/jpg','image/gif','image/png','image/bmp','image/pjpeg','image/jpeg');
		$max_size='102400';      // 最大文件限制（单位：byte）
		$upfile = C('UPLOADPATH')."image/"; //图片目录路径
		$time=time();
		
		$fname = $up_info['name'];

		if($_SERVER['REQUEST_METHOD'] == 'POST'){ //判断提交方式是否为POST
			if(!is_uploaded_file($up_info['tmp_name'])){ //判断上传文件是否存在
				$this->error('请上传图片.');
				exit();
			}
			   
			if($up_info['size']>$max_size){  //判断文件大小是否大于500000字节
				$this->error("上传文件太大.");
				exit();
			} 
			
			if(!in_array($up_info['type'],$arrType)){  //判断图片文件的格式
				$this->error("上传文件格式不对.");
				exit();
			}
			if(!file_exists($upfile)){  // 判断存放文件目录是否存在
				 mkdir($upfile,0777,true);
			 } 
			$imageSize = getimagesize($up_info['tmp_name']);//图片大小
			if($imageSize[0] != '210' && $imageSize[1] != '62'){
				$this->error("上传图片尺寸不对,应为210*62.");
				exit();
			}
			
			$img = $imageSize[0].'*'.$imageSize[1];
			$ftypearr = explode('.',$fname);
			$ftype = $ftypearr[1];//图片类型
	
			$fname = $time.'.'.$ftype;
			$picName = $upfile.$fname;
					 
			if(file_exists($picName)){
				$this->error("同文件名已存在.");
				exit();
			}
					
			if(!move_uploaded_file($up_info['tmp_name'],$picName)){  
				$this->error("移动文件出错.");
				exit();
						
			}
		}
		return  $fname;
	}
}