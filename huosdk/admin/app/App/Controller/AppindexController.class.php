<?php
/**
* 首页广告
*
* @author
*/
namespace App\Controller;
use Common\Controller\AdminbaseController;

class AppindexController extends AdminbaseController{
	/**
	 * 轮播图
	 */
	public function IndexModify(){
		$appDao = M('appindex');
		$appdata = $appDao -> where("id='1' ")-> find();
		
		$gameDao = M('appgame');
		$gamelist = $gameDao -> where("isdelete='0' ") ->select(); //查询游戏列表
		$WEBSITE = WEBSITE;
		$this->assign(WEBSITE,$WEBSITE);
		$this -> assign('gamelist',$gamelist);
		$this -> assign('appdata',$appdata);
		$this->display();
	}
	
	/**
	 * 轮播图添加、修改
	 */
	public function indexModify_post(){
		$indexid = I('indexid');
		$up_infoa=$_FILES['imageone'];
		$up_infob=$_FILES['imagetwo'];
		$up_infoc=$_FILES['imagethr'];
		$up_infod=$_FILES['imagefour'];

		$time = time();
		//图1
		if (!empty($up_infoa['name'])) {
			$lunbotua = $this -> checkImage($up_infoa,$time);
		}
		if($lunbotua != ''){
			$index_data['lunbotua'] = $lunbotua;
		}
		//图2
		if (!empty($up_infob['name'])) {
			$lunbotub = $this -> checkImage($up_infob,$time+1);
		}
		if($lunbotub != ''){
			$index_data['lunbotub'] = $lunbotub;
		}
		//图3
		if (!empty($up_infoc['name'])) {
			$lunbotuc = $this -> checkImage($up_infoc,$time+2);
		}
		if($lunbotuc != ''){
			$index_data['lunbotuc'] = $lunbotuc;
		}
		//图4
		if (!empty($up_infod['name'])) {
			$lunbotud = $this -> checkImage($up_infod,$time+3);
		}
		if($lunbotud != ''){
			$index_data['lunbotud'] = $lunbotud;
		}

		//获取数据
		$index_data['create_time'] = time();
		$index_data['onegameid'] = I('onegameid');
		$index_data['twgameid'] = I('twgameid');
		$index_data['thrgameid'] = I('thrgameid');
		$index_data['fourgameid'] = I('fourgameid');
		$index_data['isdelete'] = 0;

		/*
		 * 判断是插入还是修改保存
		 */
		if($indexid != ''){
			$arDao = M("appindex");
			$update = $arDao -> where("id='".$indexid."' ")->data($index_data)->save();//update记录

			if($update){
				$this->success("修改成功!", U("Appindex/IndexModify"));
				exit();
			}else{
				$this->error("修改失败", U("Appindex/IndexModify"));
				exit();
			}
		}else {
			$Dao = M("appindex");
			//插入数据
			if($lastInsId = $Dao->add($index_data)){
				$this->success("添加成功!", U("Appindex/IndexModify"));
				exit();
			}else{
				$this->error("添加失败", U("Appindex/IndexModify"));
				exit();
			}
		}
	}
	
	
	/**
	 * 上传图片
	 */
	public function checkImage($up_info,$time) {
		$arrType=array('image/jpg','image/gif','image/png','image/bmp','image/pjpeg','image/jpeg');
		$max_size='5242880';      // 最大文件限制（单位：byte）
		$upfile = C('UPLOADPATH')."image/";; //图片目录路径
			
		$fname = $up_info['name'];
			
		if($_SERVER['REQUEST_METHOD'] == 'POST'){ //判断提交方式是否为POST
				
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
		}
		return  $fname;
	}
}
	
?>