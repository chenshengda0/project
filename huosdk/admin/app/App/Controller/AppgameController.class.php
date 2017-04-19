<?php 
namespace App\Controller;
use Common\Controller\AdminbaseController;
class AppgameController extends AdminbaseController{
	/**
	 * 游戏列表
	 */
	public function gameList(){
		$this->appGameList();
		$this->gameType();
		$this -> display();
	}
	
	/**
	 * 游戏列表
	 */
	public function appGameList(){
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$itemid = isset($_POST['name']) ? mysql_real_escape_string($_POST['name']) : '';
	
		//$offset = ($page-1)*$rows;
		$result = array();
		$model = M('appgame');
		$where = " c_appgame.isdelete = 0 ";

		$name = isset($_POST['name']) ? $_POST['name'] : '';
		if ($name !=''){
			$where .= " AND c_appgame.name like '%".$name."%' ";
		}
		$field = "c_appgame.id,c_appgame.name,c_appgame.type,c_appgame.create_time,c_appgame.count,c_appgameinfo.androidurl";	
		$join = " left join  c_appgameinfo on c_appgame.id=c_appgameinfo.id";
		
		$result["total"] = $total = $model->where($where)->field($field)->join($join)->count();
		$page = $this->page($result["total"], 10);
		$items = $model->where($where)->field($field)->join($join)->order('c_appgame.id DESC')->limit($page->firstRow . ',' . $page->listRows)->select();

		$this->assign('items',$items);
		$this->assign("page", $page->show('Admin'));
	}

	//查询游戏类型
	public function gameType(){
		$typeDao = M('Appgametype');
		$gametype = $typeDao -> select(); //查询游戏类型
		$this->assign('gametype',$gametype);
	}

	/**
	 * 添加app游戏
	 */
	public function addGame(){
		$this->gameType();
		$this->display();
	}
	public function addGame_post(){

		$arDao = M('appgame');
		$game = I('name');
		$gamedate = $arDao -> where("name='".$game."'")-> select();
		if($gamedate){
			$this->error("游戏已经存在，添加失败。");
		}
		//获取图标文件名
		$icofile = $_FILES['micon'];

		$iconame = '';
		if($icofile['name'][0] != ''){
			$iconame = $this -> checkImage($icofile,time()+1,0);
		}

		$up_info=$_FILES['simage'];
		$smallname = '';
		$time = time()+4;
			
		//for 循环处理多个文件上传
		for($i=0;$i<count($up_info['name']);$i++){
			$time = $time+1;
		
			if($up_info['name'][$i] != ''){
				$imagename = $this -> checkImage($up_info,$time,$i);
				if($imagename != ''){
					$smallname .= $imagename.",";
				}
			}
		
		}
		$smallname = substr($smallname,0,-1);

		//获取数据
		$game_data['create_time'] = time();
		$game_data['name'] = $game;
		$game_data['lanmu'] = I('lanmu');
		$game_data['type'] = I('type');
		$game_data['link'] = I('link');
		$game_data['count'] = I('count');
		
		//获取游戏名称拼音
		import('Vendor.Pin');
		$pin = new \Pin();
		$pinyin = $pin -> pinyin($game_data['name']);
		$game_data['pinyin'] = $pinyin;
		$initial = $pin -> pinyin($game_data['name'],true);
		$game_data['initial'] = $initial;
		
		$infodata['size'] = I('size');
		$infodata['description'] = I('description');
		$infodata['versions'] = I('versions');
		
		if($iconame != ''){
			$game_data['image'] = $iconame;
		}
		if($bigname != ''){
			$infodata['bigimage'] = $bigname;
		}
		if($mobile_name != ''){
			$infodata['mobileiocn'] = $mobile_name;
		}
			
		if($smallname != ''){
			$infodata['smallimage'] = $smallname;
		}
		//插入数据
		if($lastInsId = $arDao->add($game_data)){
			$infodata['id'] = $lastInsId;
			$Dao = M("appgameinfo");
			if($lastInsId = $Dao->add($infodata)){
				$this->success("添加游戏成功。");
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
	public function editGame(){
		$gameid = I('id');
		$Dao = M("appgame");
		$gamelist = $Dao -> where("id = '%s'",$gameid) ->find();
		$arsDao = M("appgameinfo");
		$gameinfo = $arsDao -> where("id = '%s'",$gameid) ->find();
		$this->assign('gamelist',$gamelist);
		$this->assign('gameinfo',$gameinfo);
		$this->gameType();
		$this->display();
	}
	public function editGame_post(){
		$gameid = I('id');
		$arDao = M('appgame');
		$game = I('name');
		//获取图标文件名
		$icofile = $_FILES['micon'];
	
		$iconame = '';
		if($icofile['name'][0] != ''){
			$iconame = $this -> checkImage($icofile,time()+1,0);
		}
	
		$up_info=$_FILES['simage'];
		$smallname = '';
		$time = time()+4;
			
		//for 循环处理多个文件上传
		for($i=0;$i<count($up_info['name']);$i++){
			$time = $time+1;
	
			if($up_info['name'][$i] != ''){
				$imagename = $this -> checkImage($up_info,$time,$i);
				if($imagename != ''){
					$smallname .= $imagename.",";
				}
			}
		}
		$smallname = substr($smallname,0,-1);
	
		//获取数据
		$game_data['create_time'] = time();
		$game_data['name'] = $game;
		$game_data['lanmu'] = I('lanmu');
		$game_data['type'] = I('type');
		$game_data['link'] = I('link');
		$game_data['count'] = I('count');
	
		//获取游戏名称拼音
		import('Vendor.Pin');
		$pin = new \Pin();
		$pinyin = $pin -> pinyin($game_data['name']);
		$game_data['pinyin'] = $pinyin;
		$initial = $pin -> pinyin($game_data['name'],true);
		$game_data['initial'] = $initial;
	
		$infodata['size'] = I('size');
		$infodata['description'] = I('description');
		$infodata['versions'] = I('versions');
	
		if($iconame != ''){
			$game_data['image'] = $iconame;
		}
		if($bigname != ''){
			$infodata['bigimage'] = $bigname;
		}
		if($mobile_name != ''){
			$infodata['mobileiocn'] = $mobile_name;
		}
			
		if($smallname != ''){
			$infodata['smallimage'] = $smallname;
		}
		//插入数据
		if($arDao->where("id = '%s'",$gameid)->save($game_data)){
			$Dao = M("appgameinfo");
			if($Dao->where("id = '%s'",$gameid)->save($infodata)){
				$this->success("修改成功。");
			}else{
				$this->error("修改失败。");
			}
		} else {
			$this->error("修改失败。");
		}
	}

	/**
	 * 删除
	 */
	public function delGame(){
		$gameid = I('id');
		if($gameid != ''){
			$infoDao = M('appgameinfo');
			$info_del['isdelete'] = 1;
			//伪删除信息
			$gameinfo = $infoDao -> where("id='".$gameid."' ")->delete();//update
			//$gameinfo = $infoDao -> where("id='".$game_id."' ")-> delete();//删除游戏信息
			if($gameinfo){
				$gameDao = M('appgame');
				//伪删除信息
				$gamedate = $gameDao -> where("id='".$gameid."' ")->delete();//update
				if($gamedate){
					$this->success("删除成功。");
				}else{
					$this->error("删除失败。");
				}
			}
		}
		$this->error("无效数据。");
	}
	
	/**
	 * 上传图片
	 */
	public function checkImage($up_info,$time,$i){
	
		$arrType=array('image/jpg','image/gif','image/png','image/bmp','image/pjpeg','image/jpeg');
		$max_size='5242880';      // 最大文件限制（单位：byte）
		$upfile = C('UPLOADPATH')."image/"; //图片目录路径
		
		//$file = $imagefile;
		$fname = $up_info['name'][$i];
		
		if($_SERVER['REQUEST_METHOD'] == 'POST'){ //判断提交方式是否为POST
			
			if(!is_uploaded_file($up_info['tmp_name'][$i])){ //判断上传文件是否存在
				$this->error('文件不存在.');
			}
		   
			if($up_info['size'][$i]>$max_size){  //判断文件大小是否大于500000字节
				$this->error("上传文件太大.");
				exit();
			} 

			if(!in_array($up_info['type'][$i],$arrType)){  //判断图片文件的格式
				$this->error("上传文件格式不对.");
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
			}
				
			if(!move_uploaded_file($up_info['tmp_name'][$i],$picName)){ 
				$this->error("移动文件出错.");
			}
		}
		return  $fname;
	}
	
	/**
	 * 开服列表
	 */
	public function serverList(){
		$this->serverNewList();
		$this -> display();
	}	
	/**
	 * 开服列表
	 */
	public function serverNewList(){
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$appid = I("appid");
	
		//$offset = ($page-1)*$rows;
		$result = array();
		$model = M('appserverinfo');
	
		$result["total"] = $total = $model->count();
		$page = $this->page($result["total"], $rows);
		
		
		$where = " isdelete=0 ";
		$where_array = array();
		if ($appid){
			$where .= " and gameid = %s";
			array_push($where_array, $appid);
		}
		$field = " sertime,id,gameid,sername";
		$items = $model->field($field)->where($where,$where_array)->order("id DESC")->limit($page->firstRow . ',' . $page->listRows)->select();

		$gameDao = M('appgame');
		$gamelist = $gameDao -> select(); //查询游戏列表
	
		foreach ($gamelist as $key => $val) {
			$gamedata[$val['id']] = $val['name'];
		}

		foreach ($items as $key => $val) {
			$items[$key]['game'] = $gamedata[$val['gameid']];
			$items[$key]['sertime'] = date("Y-m-d H:i",$items[$key]['sertime']);
		}
		
		$this->assign("gamelist",$gamelist);
		$this->assign("items",$items);
		$this->assign("page",$page->show('Admin'));
	}		

	//添加开服信息
	public function addServer(){
		$model = M('appgame');
		$games=$model->order("id desc")->select();
		$this->assign("games",$games);
		$this -> display();
	}
	
	public function addServer_post(){
		//获取数据
		$time = time();
		$server_data['gameid'] = I("id");
		$server_data['sername'] = I("sername");
		$server_data['sertime'] = strtotime(I("sertime"));
		
		$serverinfo = M("appserverinfo");
		if($serverinfo->create($server_data)){
			$lastInsId = $serverinfo->add();
			$this->success("添加成功。");
			exit();
		} else {
			$this->error("添加失败。");
			exit();
		}
	}
	
	/**
	 * 删除
	 */
	public function removeServer(){
		$serverid = I('id');
		
		if($serverid != ''){
			$serverDao = M('appserverinfo');
			$rs = $serverDao -> where("id='".$serverid."' ")-> delete();//删除游戏信息

			if($rs){
				$this->success("删除成功。");
			}else{
				$this->error("删除失败。");
			}
			
		}
		$this->error("无效数据。");
	}
	
	/**
	 * 
	 * 上传的游戏列表
	 */
	public function uploadlist() {
		import("Think.Util.Page");
		$model = M('appuploadgame');
		
		$gameDao = M('appgame');
		$gamelist = $gameDao -> where(" isdelete=0 ") -> select(); //查询游戏
		$this -> assign('gamelist',$gamelist);
		foreach ($gamelist as $key=>$val) {
    		$game[$val['id']] = $val['name'];
    	}
    	$this->assign('game',$game);
		
		$where = 1;
		$gameid = isset($_REQUEST['gameid']) ? $_REQUEST['gameid'] : 0;
		if ($gameid !=0){
			$where = " gameid =".$gameid;
			$this->assign("gameid",$gameid);
		}
		
		$total = $model->where($where)->count();
		$p = new Page($total,30);
		
		//查询数据列表
		$list = $model->where($where)-> order('id DESC')->limit($p->firstRow . ',' .  $p->listRows)->select();
		$page = $p->show();
		
		//分页跳转的时候保证查询条件
        $p->parameter = '&';
        foreach ($_REQUEST as $key => $val) {
            if (!is_array($val) && $key != "__hash__") {
                $this->assign($key, $val);
                $p->parameter .= "$key=" . urlencode($val) . "&";
            }
        }
    	
        //分页显示
        $page = $p->show();
            
    	if ($p->parameter) {
            $this->assign('query_str', $p->parameter);
        }
        $this->assign('list',$list);
    	$this->assign('page',$page);
    	$this->display();
	}
	
	/**
     * 批量上传图片的处理程序
     *
     * @return null
     */
    public function uploadbigfile()
    {
        //Code for Session Cookie workaround
        if (isset($_POST["PHPSESSID"])) {
            session_id($_POST["PHPSESSID"]);
        } else if (isset($_GET["PHPSESSID"])) {
            session_id($_GET["PHPSESSID"]);
        }
        session_start();
        
        session_id($_POST["PHPSESSID"]);
        
        $gameid = isset($_POST["gameid"]) ? $_POST["gameid"] : 0;
        if ($gameid == 0) {
        	echo "上传失败:没有选择游戏";
			exit();
        }
        //获取该游戏的字母简称
        $model = M('appgame');
        $rs = $model->field("pinyin")->where("id=".$gameid)->findAll();
        $pinyin = $rs[0]['pinyin'];

        //保存的路径
        $save_path = C('UPLOADPATH')."image/";
        $upload_name             = "Filedata";
        $max_file_size_in_bytes = 1000*1024;                // 最大上传的文件大小为
        $extension_whitelist     = array("jpg", "jpeg", "gif", "png", "apk", "ipa");    // 上传允许的文件扩展名称
       
        //其他的验证
        $file_name              = $_FILES[$upload_name]['name'];
        $file_extension         = "";
        $uploadErrors = array(
                0=>"文件上传成功", 
                1=>"上传的文件超过了 php.ini 文件中的 upload_max_filesize directive 里的设置", 
                2=>"上传的文件超过了 HTML form 文件中的 MAX_FILE_SIZE directive 里的设置", 
                3=>"上传的文件仅为部分文件", 
                4=>"没有文件上传", 
                6=>"缺少临时文件夹"
        );
        
        //验证上传
        if (!isset($_FILES[$upload_name])) {
            echo "上传失败:没有发现上传文件";
			exit();
        } else if (!isset($_FILES[$upload_name]['name'])) {
            echo "上传失败:文件没有名字";
			exit();
        }
        
        $tmpname = substr($file_name, -4);
        $file_name = $pinyin."_".date("YmdHis").$tmpname;
        
    	if(file_exists($save_path.$file_name)){
			echo "上传失败:同文件名已存在";
			exit();
		}
			
        if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path.$file_name)) {
        	echo "上传失败:文件移动失败";
			exit();
        } else {
        	$model = M('appuploadgame');
        	$data['gameid'] = $gameid;
        	$data['gamename'] = $file_name;
        	$data['create_time'] = time();
        	$rs = $model->data($data)->add();
        	
        	if ($rs) {
        		$this->insertLog($_COOKIE['cyadmin2015'],1, 'AppgameAction.class.php', 'uploadbigfile', time(),"上传游戏:".$gameid); 
        	}
        	echo "suc:恭喜你上传成功";
			exit();    
        }      
    }
}
	
?>