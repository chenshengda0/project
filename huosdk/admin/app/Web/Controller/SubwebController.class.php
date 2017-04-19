<?php
/**
* 平台充值管理
*
* @author
*/
namespace Web\Controller;
use Common\Controller\AdminbaseController;

class SubwebController extends AdminbaseController {
	/**
	 * 游戏列表
	 */
	public function webList(){
		$this->subwebList();
		$this -> display();
	}
	
	/**
	 * 子站列表
	 */
	public function subwebList(){
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$appid = I("appid",0);

		//组装搜索条件
		$wher_array = array();
		$where = " is_delete='2'";
		if($appid !=0){
			$where .= " AND app_id like '%s'";
			array_push($wher_array,"%{$appid}%");
		}
		$result = array();
		$gs_model = M('gameSubsite');
		$result["total"] = $total = $gs_model->where($where,$wher_array)->count();
		$page = $this->page($result["total"], $rows);
		
		$items = $gs_model->field('app_id,create_time,website')->where($where,$wher_array)->order('app_id DESC')->limit($page->firstRow . ',' . $page->listRows)->select();
 
		$game_model = M('game');
		$gamelist = $game_model ->field('id,name')->where("is_delete = 2")-> select(); //查询游戏列表

		//把游戏的信息放在以该游戏的id为键的数组中
 		foreach ($gamelist as $key => $val) {
			$gamename[$val['id']] = $val;
		}
		
		//把游戏名放到对应的游戏ID的$items中
		foreach ($items as $key => $val) {
			$items[$key]['game'] = $gamename[$val['app_id']]['name'];
		}

		$this->assign('items',$items);
		$this->assign('gamelist',$gamelist);
		$this->assign("page", $page->show('Admin'));
	}
	
	//获取游戏列表
	public function gameList(){
		$game_model = M('game');
		$gamelist = $game_model -> where("is_delete=2")->select(); //查询游戏列表
		$this->assign("gamelist",$gamelist);
	}
	
	//子站添加
	public function addSub(){
		$this->gameList();
		$this->display();
	}
	
	//子站添加
	public function addSub_post(){
		$subweb_data['app_id']		= I('appid');
		$subweb_data['website'] 	= WEBSITE."/index.php/Web/Substation/index/gameid/".I('appid');
		$subweb_data['lboneurl'] 	= I('lboneurl');
		$subweb_data['lbtwourl'] 	= I('lbtwourl');
		$subweb_data['lbthreeurl'] 	= I('lbthreeurl');
		$subweb_data['lbfoururl'] 	= I('lbfoururl');
		$subweb_data['bbs_url'] 	= I('luntanurl');
		$subweb_data['gift_url'] 	= I('libao');
		$subweb_data['glurlone'] 	= I('glurlone');
		$subweb_data['glurltwo'] 	= I('glurltwo');
		$subweb_data['glurlthree'] 	= I('glurlthree');
		$subweb_data['glurlfour'] 	= I('glurlfour');
		$subweb_data['guildurl'] 	= I('guildurl');
		$subweb_data['tgurl'] 	    = I('tgurl');
		$subweb_data['activityurl'] = I('activityurl');
		$subweb_data['noviceurl'] 	= I('noviceurl');
		$subweb_data['screenshoturl']= I('screenshoturl');
		
		$subweb_data['is_delete'] 	= 2;
		$subweb_data['create_time'] = time();
		if ($subweb_data['app_id'] == 0 || empty($subweb_data['website'])) {
			$this->error("请填写完整参数。");
			exit();
		}
		$wher_array = array();
		$gs_model = M("gameSubsite");
		$where = " app_id = '%s'";
		$checkgameid = $subweb_data['app_id'];
		
		array_push($wher_array,$checkgameid);
	
		$checkgame = $gs_model -> where($where,$wher_array)->select();
		if($checkgame){
			$this->error("该游戏已添加了子站，请不要重复添加。");
			exit();
		}
		$up_info = $_FILES['image'];
		$bannerinfo = $_FILES['banner'];
		$gonglueinfo = $_FILES['gonglue'];
	
		$banner = '';
		$lunbotu = '';
		$gongluetu = '';
		$time = time()+3;
		$arrlunbo = array();
		$arrgonglue = array();
			
		if($bannerinfo['name'][0] != ''){
			$banner = $this -> checkImage($bannerinfo,$time,0);
		}
			
		//for 循环处理多个文件上传
		for($i=0;$i<count($up_info['name']);$i++){
			$time = $time+1;
		
			if($up_info['name'][$i] != ''){
				$imagename = $this -> checkImage($up_info,$time,$i);
				if($imagename != ''){
					$arrlunbo[$i] = $imagename;
					$lunbotu .= $imagename.",";
				}
			}else{
				$arrlunbo[$i] = $up_info['name'][$i];
			}
		}
		
		for($j=0;$j<count($gonglueinfo['name']);$j++){
			$time = $time+100+$j;
		
			if($gonglueinfo['name'][$j] != ''){
				$gongluename = $this -> checkImage($gonglueinfo,$time,$j);
				if($gongluename != ''){
					$arrgonglue[$j] = $gongluename;
					$gongluetu .= $gongluename.",";
				}
			} else {
				$arrgonglue[$j] = $gonglueinfo['name'][$j];
			}
		}
		
		$lunbotu = substr($lunbotu,0,-1);
		$gongluetu = substr($gongluetu,0,-1);
		
		if($banner != ''){
			$subweb_data['banner'] 	= $banner;
		}
			
		if($lunbotu != ''){
			$subweb_data['lunbotu'] = $lunbotu;
		}
			
		if($gongluetu != ''){
			$subweb_data['gongluetu'] = $gongluetu;
		}	
		
		if($lastInsId = $gs_model->add($subweb_data)){
			//$this->insertLog($_COOKIE['mgadmin2015'],1, 'SubwebAction.class.php', 'webModify', time(),"添加了子站:".$subweb_data['name']);
			$this->success("子站添加成功。");
			exit();
				
		} else {
			$this->error("添加失败。");
			exit();
		}
	}
	/**
	 * 编辑子站信息
	 */
	public function editSub_post(){
		$subweb_id = I('app_id');
		$subweb_data['app_id']		= I('app_id');
		$subweb_data['website'] 	= WEBSITE."/index.php/Web/Substation/index/gameid/".I('app_id');
		$subweb_data['lboneurl'] 	= I('lboneurl');
		$subweb_data['lbtwourl'] 	= I('lbtwourl');
		$subweb_data['lbthreeurl'] 	= I('lbthreeurl');
		$subweb_data['bbs_url'] 	= I('luntanurl');
		$subweb_data['gift_url'] 	= I('gift_url');
		$subweb_data['glurlone'] 	= I('glurlone');
		$subweb_data['glurltwo'] 	= I('glurltwo');
		$subweb_data['glurlthree'] 	= I('glurlthree');
		$subweb_data['glurlfour'] 	= I('glurlfour');
		$subweb_data['guildurl'] 	= I('guildurl');
		$subweb_data['tgurl'] 	    = I('tgurl');
		$subweb_data['activityurl'] = I('activityurl');
		$subweb_data['noviceurl'] 	= I('noviceurl');
		$subweb_data['screenshoturl']= I('screenshoturl');

		$subweb_data['is_delete'] 	= 2;
		$subweb_data['create_time'] = time();
		if ($subweb_data['app_id'] == 0 || empty($subweb_data['website'])) {
			$this->error("请填写完整参数。");
			exit();
		}
		
		$up_info = $_FILES['image'];
		$bannerinfo = $_FILES['banner'];
		$gonglueinfo = $_FILES['gonglue'];
		
		$banner = '';
		$lunbotu = '';
		$gongluetu = '';
		$time = time()+3;
		$arrlunbo = array();
		$arrgonglue = array();
		
		if($bannerinfo['name'][0] != ''){
			$banner = $this -> checkImage($bannerinfo,$time,0);
		}
		
		//for 循环处理多个文件上传
		for($i=0;$i<count($up_info['name']);$i++){ 
			$time = $time+1;
			
			if($up_info['name'][$i] != ''){
				$imagename = $this -> checkImage($up_info,$time,$i);
				if($imagename != ''){
					$arrlunbo[$i] = $imagename;
					$lunbotu .= $imagename.",";
				}
			}else{
				$arrlunbo[$i] = $up_info['name'][$i];
			}
		}

		for($j=0;$j<count($gonglueinfo['name']);$j++){ 
			$time = $time+100+$j;

			if($gonglueinfo['name'][$j] != ''){
				$gongluename = $this -> checkImage($gonglueinfo,$time,$j);
				if($gongluename != ''){
					$arrgonglue[$j] = $gongluename;
					$gongluetu .= $gongluename.",";
				}
			} else {
				$arrgonglue[$j] = $gonglueinfo['name'][$j];
			}
		}

		$lunbotu = substr($lunbotu,0,-1);
		$gongluetu = substr($gongluetu,0,-1);

		if($banner != ''){
			$subweb_data['banner'] 	= $banner;
		}
		
		if($lunbotu != ''){
			$subweb_data['lunbotu'] = $lunbotu;
		}
		
		if($gongluetu != ''){
			$subweb_data['gongluetu'] = $gongluetu;
		}
		
		$gs_model = M("gameSubsite");
		/*
		 * 修改保存
		 */
		if($subweb_id != 0){
			$webdata = $gs_model -> where("app_id=".$subweb_id)-> select();//查询选中记录信息，用于编辑
			
			//获取原图片信息
			$bannerimg = $webdata[0]['barner'];
			$arimage = explode(",",$webdata[0]['lunbotu']);
			$glimage = explode(",",$webdata[0]['gongluetu']);
			$lbtu = '';
			$bimg = '';
			$glimg = '';
			
			if (empty($bannerinfo['name'])) {
				$bimg = $bannerimg;
			} else {
				$bimg = $banner;
			}
			
			if($bimg != ''){
				$subweb_data['banner'] = $bimg;
			}
			
			//判断图片是否有修改，无修改则用原图
			for($i=0;$i<count($up_info['name']);$i++){
				if(empty($arrlunbo[$i]) || $arrlunbo[$i] == ''){
					$lbtu .= $arimage[$i].",";
				}else{
					$lbtu .= $arrlunbo[$i].",";
				}
			}
			//去除字符后缀逗号
			$lbtu = substr($lbtu,0,-1);
			//获取保存信息
			if($lbtu != ''){
				$subweb_data['lunbotu'] = $lbtu;
			}
			
			for($i=0;$i<count($gonglueinfo['name']);$i++){
				if(empty($arrgonglue[$i]) || $arrgonglue[$i] == ''){
					$gltu .= $glimage[$i].",";
				}else{
					$gltu .= $arrgonglue[$i].",";
				}
			}
			$gltu = substr($gltu,0,-1);
			if($gltu != ''){
				$subweb_data['gongluetu'] = $gltu;
			}
			
			//修改数据
			$update = $gs_model -> where("app_id=".$subweb_id)->save($subweb_data);//update
				
			if($update){
				//$this->insertLog($_COOKIE['mgadmin2015'],3, 'SubwebAction.class.php', 'webModify', time(),"修改了子站:".$subweb_data['name']);
				$this->success("修改成功。");
				exit();
			}else{
				$this->error("修改失败。");
				exit();
			}
		}
		$this->error("未选择编辑游戏。");
		exit();
	}

	/**
	 * 显示游戏编辑页面
	 */
	public function editSub(){
		$subweb_id = I("id");
		
		if ($subweb_id) {
			$gs_model = M('gameSubsite');
			$webdata = $gs_model -> where("app_id='".$subweb_id."' ")-> select();//查询选中记录信息，用于编辑

			$game_model = M('game');
			
			$where = 1;
			$wher_array = array();
			$where .= " and app_id like '%s'";
			$app_id = $webdata[0]['app_id'];
 
			array_push($wher_array,"%$app_id%");
			
			$game = $game_model ->field("id,name")-> select();
			$arimage = explode(",",$webdata[0]['lunbotu']);
			$glimage = explode(",",$webdata[0]['gongluetu']);

			$this -> assign("gameid",$app_id);
			$this -> assign('glimage',$glimage);
			$this -> assign('arimage',$arimage);
			$this -> assign('game',$game);
			$this -> assign('webdata',$webdata[0]);
		}
		$this -> display();
	}
	
	/**
	 * 删除子站
	 */
	public function delSub(){
		$subweb_id = I("id");
		if($subweb_id != ''){
			$gs_model = M('gameSubsite');
			//伪删除信息
			$webdel = $gs_model -> where("app_id='".$subweb_id."' ")->delete();//update
			if($webdel){
				//$this->insertLog($_COOKIE['mgadmin2015'],2, 'SubwebAction.class.php', 'webList', time(),"删除了子站ID:".$subweb_id);
				$this->success("删除成功。");
				exit();
			}else{
				$this->error("删除失败。");
				exit();
			}
		}
	}
	
	/**
	 * 上传图片
	 */
	public function checkImage($up_info,$time,$i){
	
		$arrType=array('image/jpg','image/gif','image/png','image/bmp','image/pjpeg','image/jpeg');
		$max_size='5242880';      // 最大文件限制（单位：byte）
		$upfile = C('UPLOADPATH')."image/";; //图片目录路径
		
		$fname = $up_info['name'][$i];
		
		if($_SERVER['REQUEST_METHOD'] == 'POST'){ //判断提交方式是否为POST
			
			if(!is_uploaded_file($up_info['tmp_name'][$i])){ //判断上传文件是否存在
				$this->error('文件不存在.');
				exit();
			}
		   
			if($up_info['size'][$i]>$max_size){  //判断文件大小是否大于500000字节
				$this->error("上传文件太大.");
				exit();
			} 

			if(!in_array($up_info['type'][$i],$arrType)){  //判断图片文件的格式
				$this->error("上传文件格式不对.");
				exit();
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
				$this->error("同文件名已存在.");
				exit();
			}
				
			if(!move_uploaded_file($up_info['tmp_name'][$i],$picName)){  
				$this->error("移动文件出错.");
				exit();
					
			}
		}
		return  $fname;
	}
}