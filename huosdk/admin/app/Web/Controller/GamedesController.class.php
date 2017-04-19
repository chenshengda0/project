<?php 
/*
**游戏详细信息
**/

namespace Web\Controller;
use Common\Controller\AdminbaseController;

class GamedesController extends AdminbaseController {
    protected $game_model;
    
    function _initialize() {
        parent::_initialize();
        $this->game_model = D("Common/Game");
    }
    
	/**
	 * 游戏信息
	 */
	public function gameList(){
		$this->gList();
		$this -> display();
	}
	
	/**
	 * 游戏列表
	 */
	public function gList(){
		$page = I('p');      //页码
		$gametitle = I('gametitle');  //新闻名
		
		//当接收分页时附加上上次搜索的游戏名,保证搜索条件不丢失。
		if($page && session('search_game_title')){
			$title = session('search_game_title');
			$map['name'] = array('like',"%$title%");
		}
		
		//组装分页搜索条件
		$map['is_delete'] = 2;
		if($gametitle){
			$map['name'] = array('like',"%$gametitle%");	//如果传了title,则覆盖搜索的title，并更新session中的search_game_id
			session('search_game_title',$gametitle);  //把查询的新闻title保存到session中; 
		}
		
		//如果既没有传页码，也没有传游戏的ID，则清空保存的title
		if(!$page && !$gametitle){
			session('search_game_title',null);  //清空保存的游戏ID; 				
		}

		//获取符合条件的总条数
		$total = $this->game_model->where($map)->count();
		
		$rows = isset($_POST['row'])?intval($_POST['row']): 10;
		
		//获取分页类
		$page = $this->page($total,$rows);
		
		//获取游戏内容
		$items = $this->game_model->where($map)->order('listorder ASC ,id DESC')->limit($page->firstRow . ',' . $page->listRows)->select();

		//游戏类型列表
		$typeDao = M('gameType');
		$typedata = $typeDao ->select();
		foreach ($typedata as $key=>$val) {
			$typelist[$val['id']] = $val['name'];
		}
		
		$infomodel = M('gameInfo');

		foreach ($items as $key=>$val) {
			
			$items[$key]['type'] = $typelist[$val['type']];
			$items[$key]['typeid'] = $val['type'];
			$list = $infomodel->where("app_id='%s'",$val['app_id'])->select();

			foreach ($list as $k=>$v) {
				$items[$key]['description'] = $v['description'];
				$items[$key]['url'] = $v['url'];
				$items[$key]['iosurl'] = $v['iosurl'];
				$items[$key]['androidurl'] = $v['androidurl'];
				$items[$key]['versions'] = $v['versions'];
				$items[$key]['size'] = $v['size'];
				$items[$key]['iosxt'] = $v['iosxt'];
				$items[$key]['yiosurl'] = $v['yiosurl'];
				$items[$key]['adxt'] = $v['adxt'];
			}
		}

		$this->assign("items",$items);
		$this->assign("Page", $page->show('Admin'));
	}
	
	/**
	 * 显示添加游戏详细信息的界面
	 */
	public function addGamedes(){
		$this->_game(false);
		$this->_game_type(false);
		$this->display();
	}
	
	//保存游戏的详细数据
	public function addGamedes_post(){
		$app_id = I('app_id/d');
		$gi_model = M("game_info");
		if (empty($app_id)){
		    $this->error("参数错误");
		}
		
		$check = $gi_model->where("app_id = '%s'",$app_id)->find();
		//判断添加的游戏是否已经存在
		if($check){
			$this->error("游戏信息已添加，如若修改，请在右侧选择修改。");
		}
		
		$time = time();
		
		//获取图标文件名
		$icofile = $_FILES['imageicon'];
		if($icofile['name'][0] != ''){
			$iconame = $this -> checkImage($icofile,$time+1,0);
		}
		
		//获取手机图片
		$mobile_file = $_FILES['micon'];
		if($mobile_file['name'][0] != ''){
			$mobile_name = $this -> checkImage($mobile_file,$time+30,0);
		}
		
		//获取数据
		$g_data['id'] = $app_id;
		$g_data['listorder'] = I("listorder/d");
		$g_data['type'] = I("type/d");
		
		$infodata['lanmu'] = I("lanmu");
		$infodata['publicity'] = I("publicity");
		$infodata['description'] = I("description");
		$infodata['url'] = I("url");
		$infodata['androidurl'] = I("androidurl");
		$infodata['iosurl'] = I("iosurl");
		$infodata['size'] = I("size");
		$infodata['adxt'] = I("adxt");
		$infodata['iosxt'] = I("iosxt");
		
		if($iconame != ''){
			$infodata['bigimage'] = $iconame;
		}
		
		if($mobile_name != ''){
			$infodata['mobile_icon'] = $mobile_name;
			$g_data['mobile_icon'] = $mobile_name;
		}
		
		$rs = $this->game_model->save($g_data);
		if($rs){
			$infodata['app_id'] = $app_id;
			$lastInsId = $gi_model->data($infodata)->add();
			if($lastInsId){
				$this->success("添加成功。",U('Gamedes/gameList'));
				exit;
			}else{
				$this->error("添加失败。");
			}
		} else {
			$this->error("添加失败。");
		}
	}
    
	/**
	 * 编辑游戏信息
	 */
	public function editGamedes(){
		$app_id = I("app_id");
		$model = M("game");
		$gamelist = $model ->where("id = '%s'",$app_id)-> find();
		
		$this->_game_type(false);
		$gameInfo = M("gameInfo");
		$gamedes = $gameInfo ->where("app_id = '%s'",$app_id)-> find();
		//游戏版本
		$version = M('gameVersion')->where('app_id='.$app_id)->getField('version');
		$this->assign('version',$version);
		
		$gametype = M('gameType')->select();//游戏类型列表
		$this->assign("gamedes",$gamedes); 
		$this->assign("gametype",$gametype);
		
		$this->assign('gamelist',$gamelist);
		$this->display();
	}

	//编辑
	public function editGamedes_post(){
		$time = time();
		//获取图标文件名
		$icofile = $_FILES['imageicon'];
		if($icofile['name'][0] != ''){
			$iconame = $this -> checkImage($icofile,$time+1,0);
		}
	
		//获取游戏相关图片名
		$bigfile = $_FILES['gimage'];
		if($bigfile['name'][0] != ''){
			$bigname = $this -> checkImage($bigfile,$time+2,0);
		}
	
		//获取手机图片
		$mobile_file = $_FILES['micon'];
		if($mobile_file['name'][0] != ''){
			$mobile_name = $this -> checkImage($mobile_file,$time+30,0);
		}

		//获取数据
		$checkappid = I("app_id");             //游戏ID
		$game_data['listorder'] = I("listorder");//游戏排序
		$game_data['type'] = I("type/d");   //游戏类型
		$game_data['status'] = I("status");  //游戏状态

		$infodata['description'] = I("description");//游戏描述
		$infodata['url'] = I("url");  //官网地址
		$infodata['androidurl'] = I("androidurl");  //androidurl下载路径
		$infodata['iosurl'] = I("iosurl");     //iosurl下载路径
		$infodata['size'] = I("size"); //游戏大小
		$infodata['adxt'] = I("adxt"); //安卓版适用系统环境
		$infodata['iosxt'] = I("iosxt");//IOS版适用系统环境
		$infodata['app_id'] = $checkappid; //游戏ID
		$infodata['publicity'] = I("publicity"); //游戏宣传语
		$infodata['lanmu'] = I("lanmu"); //游戏栏目   1 单机游戏 2 热门游戏 3 最新游戏
		$infodata['status'] = I("status");  //游戏状态
		
// 		if($iconame != ''){
// 			$game_data['image'] = $iconame;
// 		}
		if($iconame != ''){
			$infodata['bigimage'] = $iconame;
		}

		if($mobile_name != ''){
			$infodata['mobile_icon'] = $mobile_name;
		}
		$game_model = M("game");
		$game_data["update_time"] = time();
		
		//保存游戏的版本信息
		$appversions['version'] = I("version"); //游戏版本
		$map['app_id'] = $checkappid;
		$version_id = M('gameVersion')->where($map)->order('create_time DESC')->getField('id'); //添加order排序是为了app有多个版本是修改最新的版本号
		$gversions = M('gameVersion')->where('id='.$version_id)->save($appversions);

		//保存游戏的信息
		$rs = $game_model->where(" id = '%s'",$checkappid)->data($game_data)->save();

		if($rs){
			$gameInfo = M("gameInfo");
			//获取游戏的ID
			$app_id = $gameInfo->where('app_id='.$checkappid)->getfield('app_id');
			//游戏存在则执行保存，不存在则执行插入
			if($app_id){
				$result = $gameInfo->where(" app_id = '%s'",$checkappid)->data($infodata)->save();
			}else{
				$result = $gameInfo->where(" app_id = '%s'",$checkappid)->data($infodata)->add();
			}

			$this->success("编辑成功");
		} else {
			$this->error("编辑失败a。");
			exit();
		}
	}
	/**
	 **游戏下拉列表
	 **/
	 public function gameCombobox(){
		$gamelist1[0]['app_id'] = 0;
		$gamelist1[0]['name'] = '请选择游戏名称'; 

		$gamemodel = M('game');
		$gamelist2 = $gamemodel->field("app_id,name")->select();
		
		$gamelist = array_merge($gamelist1,$gamelist2);
		echo json_encode($gamelist);
	 }
	
     /**
      * 删除游戏
      */
     public function delGamedes(){
         $appid = I("app_id");
         $data['is_delete'] = 1;
         $rs = M('Game')->where(array("id"=>$appid))->data($data)->save();
         M('GameInfo')->where(array("id"=>$appid))->delete();
         if ($rs){
             $this->success("删除成功");
             exit;
         }
         $this->error("删除失败");
     }
	/**
	 * 上传图片
	 */
	public function checkImage($up_info,$time,$i){
		$arrType=array('image/jpg','image/gif','image/png','image/bmp','image/pjpeg','image/jpeg');
		$max_size ='5242880';      // 最大文件限制（单位：byte）
		$upfile = C('UPLOADPATH')."image/";; //图片目录路径

		$fname = $up_info['name'][$i];
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
		return  $fname;
	}
	
}
	
?>