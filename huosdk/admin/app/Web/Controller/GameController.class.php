<?php 
/*
**游戏管理
**/

namespace Web\Controller;
use Common\Controller\AdminbaseController;

class GameController extends AdminbaseController {
	
	protected $game_model;

    function _initialize() {
        parent::_initialize();
        $this->game_model = D("Common/Game");
    }

	/**
	 **游戏下拉列表
	 **/
	 public function gameCombobox(){
		$gamelist1[0]['appid'] = 0;
		$gamelist1[0]['name'] = '请选择游戏名称'; 

		$gamemodel = M('game');
		$gamelist2 = $gamemodel->field("app_id,name")->select();
		
		$gamelist = array_merge($gamelist1,$gamelist2);
		echo json_encode($gamelist);
	 }
	

	/*
	 * 向下载服务器发送请求数据
	 */
	function http_post_data($url, $data_string) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json; charset=utf-8',
				'Content-Length: ' . strlen($data_string))
		);
		ob_start();
		curl_exec($ch);
		$return_content = ob_get_contents();
		ob_end_clean();
	
		$return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		return $return_content;
	}
	/**
	 * 开服列表
	 */
	public function serverList(){
		$this->serverNewList();
		$this -> display();
	}	
	/**
	 * 获取开服的信息
	 */
	public function serverNewList(){
		//获取页码和每页显示条数
		$page = I('p');   //获取页码
		$rows = I("rwos",10); //获取每页显示条数
		$appid = I("appid"); //获取游戏的ID;

		$model = M('webServer');

		//当接收分页时附加上上次搜索的游戏ID,保证搜索条件不丢失。
		if($page && session('search_game_id')){
			$map['app_id'] = session('search_game_id');
		}
		
		//组装分页搜索条件
		$map['is_delete'] = 2;
		if($appid){
			$map['app_id'] = $appid;		//如果传了appid,则覆盖搜索的app_id，并更新session中的search_game_id
			session('search_game_id',$appid);  //把查询的游戏ID保存到session中; 
		}	
		
		//如果既没有传页码，也没有传游戏的ID，则清空保存的appid
		if(!$page && !$appid){
			session('search_game_id',null);  //清空保存的游戏ID; 				
		}
		
		$total = $model->where($map)->count();//总条数
		$page = $this->page($total, $rows);  //调用分页的方法实例化分页类
		
		//获取开服的信息
		$field = " start_time,id,app_id,sername,status";
		$items = $model->field($field)->where($map)->order("id DESC")->limit($page->firstRow. ',' . $page->listRows)->select();
		$gameDao = M('game');
		$gamelist = $gameDao -> select(); //查询游戏列表
		
		//把游戏的名字放到对应游戏的ID下
		foreach ($gamelist as $key => $val) {
			$gamedata[$val['id']] = $val['name'];
		}

		//拼接显示数据
		foreach ($items as $key => $val) {
			$items[$key]['game'] = $gamedata[$val['app_id']];   //游戏名称
			$items[$key]['start_time'] = date("Y-m-d H:i",$items[$key]['start_time']);//开服时间
		}

		$this->assign("gamelist",$gamelist);
		$this->assign("items",$items);
		$this->assign("page",$page->show('Admin'));
	}

	//编辑开服信息
	public function editServer(){
		$games=$this->game_model->where("status=2")->order("id desc")->select();
		$this->assign("games",$games);
		$this -> display();
	}
	
	//显示开服信息
	public function addServer(){
		$games=$this->game_model->field('id,name')->where("status=2")->order("id desc")->select();
		$this->assign("games",$games);
		$this -> display();
	}
	
	//删除开服信息
	public function delServer(){
	    $id = intval(I('get.id'));
	    if ($id>0){
	        $smodel = M("webServer");
	        $where = array('id'=>$id);
	        
	        $rs = $smodel->where($where)->save(array('is_delete'=>1));    //is_delete为1表示该信息已经删除
	        if($rs){
	            $this->success("删除成功");
	            exit;
	        }
	    }
	    $this->error('删除失败');
	}
	
	//添加开服信息
	public function addServer_post(){
		$serverid = I("id");
		//获取数据
		$time = time();
		$server_data['app_id'] = I("app_id");
		$server_data['sername'] = I("sername");
		$server_data['status'] = I("serstatus");
		$server_data['start_time'] = strtotime(I("sertime"));
		$server_data['gift_url'] = WEBSITE.U('Web/Gift/index',array('app_id'=>I('app_id')));
		$server_data['is_delete'] = 2;//正常

		$icofile = $_FILES['image'];

		if($icofile['name'][0] != ''){
			$iconame = $this -> checkImage($icofile,time(),0);
			if($iconame != ''){
				$server_data['image'] = $iconame;
			}
		}

		$serverinfo = M("webServer");
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
	 * 上传图片
	 */
	public function checkImage($up_info,$time,$i){
		$arrType=array('image/jpg','image/gif','image/png','image/bmp','image/pjpeg','image/jpeg');
		$max_size ='5242880';      // 最大文件限制（单位：byte）
		$upfile = C('UPLOADPATH')."image/";; //图片目录路径
		
		$fname = $up_info['name'][$i];
		if(!is_uploaded_file($up_info['tmp_name'][$i])){ //判断上传文件是否存在
			echo json_encode(array('msg'=>'文件不存在.'));
			exit();
		}
	   
		if($up_info['size'][$i]>$max_size){  //判断文件大小是否大于500000字节
			echo json_encode(array('msg'=>'上传文件太大.'));
			exit();
		} 

		if(!in_array($up_info['type'][$i],$arrType)){  //判断图片文件的格式
			echo json_encode(array('msg'=>'上传文件格式不对.'));
			exit();
		}

		$ftypearr = explode('.',$fname);
		$ftype = $ftypearr[1];//图片类型

		$fname = $time.'.'.$ftype;
		$picName = $upfile.$fname;
			 
		if(file_exists($picName)){
			echo json_encode(array('msg'=>'同文件名已存在.'));
			exit();
		}
			
		if(!move_uploaded_file($up_info['tmp_name'][$i],$picName)){  
			echo json_encode(array('msg'=>'移动文件出错.'));
			exit();
		}
		return  $fname;
	}
	
}
	
?>