<?php
	/**
	 *  首页、游戏首页、新闻中心首页广告
	 */
namespace Web\Controller;
use Common\Controller\AdminbaseController;

class IndexguangaoController extends AdminbaseController {

	public function guangGao(){
		$type = I('type',1);
		$guanggao = M('webAds');
		$indexdata = $guanggao -> where("type=".$type)-> select();
		$WEBSITE = WEBSITE;
		$this->assign(WEBSITE,$WEBSITE);
		$this -> assign('indexdata',$indexdata[0]);
		$this->assign('type',$type);
		$this->display();
	}
	
	public function guangGao_post(){
		$indexid = I('indexid');
		$up_info=$_FILES['image'];
		$type = I('type',1);
		$img = '';
		$time = time();
		$guanggao = M("webAds");

		if (!empty($up_info['name'])) {
			$imagename = $this -> uploadImage($up_info,$time,'guangGao&type='.$type);	
		}

		//获取数据
		$index_data['title']		= I("title");
		$index_data['url']			= I("url");
		$index_data['type']			= $type;
		$index_data['create_time'] 	= time();

		if($imagename != ''){
			$index_data['img'] = $imagename;
		}
		/*
		 * 判断是插入还是修改保存
		 */
		if($indexid != ''){
			if (empty($index_data['title']) || empty($index_data['url'])) {
				$this ->error("请填写完整参数");
				exit();
			}
			$update = $guanggao -> where("id=".$indexid)->save($index_data);//update记录
			if($update){
				//$this->insertLog($_SESSION['user']['username'],3, 'IndexguangaoController.class.php', 'guangGao', time(),"修改了广告:".$type);
				$this->success("修改成功！", U("Indexguangao/guangGao")."?type=$type");
				exit();
			}else{
				$this->error("修改失败");
				exit();
			}
		}else {
			if (empty($index_data['title']) || empty($index_data['url']) || empty($index_data['img'])) {
				$this->error("请填写完整参数");
				exit();
			}

			//插入数据
			if($guanggao->create($index_data)){
				$lastInsId =$guanggao->add();
				//$this->insertLog($_SESSION['user']['username'],3, 'IndexguangaoController.class.php', 'guangGao', time(),"添加了广告:".$type);
				$this->success("添加成功",U("Indexguangao/guangGao"));
				exit();
			}else{
				$this->error("添加失败");
				exit();
			}
		}
	}
	
	/**
	 * 上传图片
	 */
	public function uploadImage($up_info,$time,$url) {
		$arrType=array('image/jpg','image/gif','image/png','image/bmp','image/pjpeg','image/jpeg');
		$max_size='5242880';      // 最大文件限制（单位：byte）
		$upfile = C('UPLOADPATH')."/image/"; //图片目录路径
			
		$fname = $up_info['name'];
			
		if(!is_uploaded_file($up_info['tmp_name'])){ //判断上传文件是否存在
			$this->error('文件不存在.');
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
		return  $fname;
	}
}