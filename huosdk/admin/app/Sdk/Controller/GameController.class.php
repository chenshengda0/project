<?php 
/*
**游戏管理
**/
namespace Sdk\Controller;
use Common\Controller\AdminbaseController;
class GameController extends AdminbaseController {
	protected $game_model, $gc_model, $gv_model;

    function _initialize() {
        parent::_initialize();
        $this->game_model = D("Common/Game");
        $this->gc_model = M('game_client');
        $this->gv_model = M('game_version');
    }

    /*
     * 获取对接参数
     */
    public function get_param(){
        $app_id = I('appid', 0);
        $param = $this->game_model->field('id app_id, name gamename, app_key')->where(array('id'=>$app_id))->find();
        $client = $this->gc_model->field('id client_id,client_key')->where(array('app_id'=>$app_id))->order('id DESC')->find();
        $data = array_merge($param,$client);
        
        $this->assign('params',$data);
        $this->display();
    }
    
	/**
	 * 游戏列表
	 */
	public function index(){
	    $this->_game_status();
		$this -> _gList();
		$this -> display();
	}

	/**
	 * 删除列表
	 */
	public function delindex(){
	    $this->_game_status();
		$this ->_gList(true);
		$this -> display();
	}
	
	/**
	 * 游戏列表
	 */
	public function _gList($is_delete=false){
		$status = I('status/d',0);
		$name = I('name', '', 'trim');
		$app_id = I('appid', 0, 'trim');

		if ($is_delete){
		    $where_ands = array('g.is_delete=1');
		}else{
		    $where_ands = array('g.is_delete=2');
		}
		
		array_push($where_ands, " g.is_own = 2 ");
		
		if(isset($name) && !empty($name)){
			array_push($where_ands, "g.name like '%$name%'");
		}

		if(isset($status) && !empty($status)){
    		array_push($where_ands, "g.status = $status");
		}
		
		if(isset($app_id) && !empty($app_id)){
    		array_push($where_ands, "g.id = $app_id");
    		$name = $this->game_model->where(array('id'=>$app_id))->getField('name');
		}
		
		$where = join(" AND ", $where_ands);
		
		$count = $this->game_model->alias('g')->where($where)->count();
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
		$page = $this->page($count, $rows);
		
		$field = "g.*, gv.packageurl";
		$items = $this->game_model
		->alias('g')
		->field($field)
		->join("LEFT JOIN ".C('DB_PREFIX')."game_version gv ON gv.app_id=g.id ")
		->where($where)
		->order("g.id DESC")
		->limit($page->firstRow . ',' . $page->listRows)
		->select();

		$this->assign("games",$items);
		$this->assign("status",$status);
		$this->assign("name",$name);
	    $this->assign("Page", $page->show('Admin'));
	    $this->assign("current_page", $page->GetCurrentPage());
	}

	/*
	 * 添加游戏
	 */
	public function add(){
	    $this->_game_status(1);
	    $this->display();
	}
	
	/*
	 * 编辑游戏
	 */
	public function edit(){
	    $appid = I('appid/d');
	    if (empty($appid)){
	        $this->error("参数错误");
	    }
	    $gdata = $this->game_model->where(array('id'=>$appid))->find();
	    $gvdata = $this->gv_model->where(array('app_id'=>$appid))->find();
	    $this->_game_status(2);
	    $this->assign('gdata',$gdata);
	    $this->assign('gvdata',$gvdata);
	    $this->display();
	}
	
	public function add_post(){
	    if (IS_POST) {
	        /* 获取POST数据 */
	        $game_data['name']        = trim(I('post.gamename'));
	        $game_data['agent_rate']  = I('post.agent_rate');
	        $game_data['game_rate']   = I('post.game_rate');
	        $version   = I('post.version');
	        $game_data['status']      = I('post.gstatus', 1);
	        $game_data['create_time'] = time();
	        $game_data['update_time'] = $game_data['create_time'];
	        $iconfile  = $_FILES['logo'];
	    
	        /* 检测输入参数合法性, 游戏名 */
	        if (empty($game_data['name'])) {
	            $this->error("游戏名为空，请填写游戏名");
	        }
	        
	        /* 检测输入参数合法性, 游戏分成比例  */
	        if (empty($game_data['game_rate'])) {
	            $this->error("CP分成比例为空，请填写CP分成比例");
	        }else{
	            $game_data['game_rate'] = (float)$game_data['game_rate'];
	            if (empty($game_data['game_rate'])  || $game_data['game_rate'] < 0 || $game_data['game_rate'] > 1){
	                $this->error("CP分成比例填写错误，请填写正确比例");
	            }
	        }
	        
	        /* 检测输入参数合法性, 渠道分成比例  */
	        if (empty($game_data['agent_rate'])) {
	            $this->error("渠道分成比例为空，请填写渠道分成比例");
	        }else{
	            $game_data['agent_rate'] = (float)$game_data['agent_rate'];
	            if (empty($game_data['agent_rate']) || $game_data['agent_rate'] < 0 || $game_data['agent_rate'] > 1){
	                $this->error("渠道分成比例填写错误，请填写正确比例");
	            }
	        }
	        
	        /* 检测输入参数合法性, 游戏版本  */
	        if (empty($version)) {
	            $this->error("游戏版本为空，请填写游戏版本");
	        }else{
	            $checkExpressions = "/^\d+(\.\d+){0,2}$/";
	            $len = strlen($version);
	            if ($len>10 || false == preg_match($checkExpressions, $version)){
	                 $this->error("游戏版本号填写错误，数字与小数点组合");
	            }
	        }

	        /* 检测输入参数合法性, 游戏LOGO */
	        if (empty($iconfile['name'])) {
	            $this->error("游戏LOGO为空!");
	        }
	    
	        // 获取游戏名称拼音
	        import('Vendor.Pin');
	        $pin = new \Pin();
	    
	        $game_data['pinyin'] = $pin->pinyin($game_data['name']);
	        $game_data['initial'] = $pin->pinyin($game_data['name'], true);
	    
	        /* 上传LOGO 文件 */
	        $logoinfo = $this->upload($iconfile, '', 'logo'.$game_data['initial'].'_'.$game_data['create_time']);
	        if(0 == $logoinfo['status']){
	            $this->error($logoinfo['msg']);
	        }else{
	            $game_data['icon'] = $logoinfo['msg'];
	        }
	    
	        if($this->game_model->create($game_data)){
	            $app_id = $this->game_model->add();
	    
	            /* 插入游戏类型  */
	            if($app_id>0){
	                $update_data['app_key'] = md5($app_id.md5($game_data['pinyin'].$game_data['create_time']));
    			    $update_data['initial'] = $game_data['initial'].'_'.$app_id;
    			    $update_data['id'] = $app_id;
    			    
    			    $this->game_model->save($update_data);
    			    
    			    //游戏版本插入
	                $gv_data['app_id'] = $app_id;
	                $gv_data['version'] = $version;
	                $gv_data['create_time'] = $game_data['create_time'];
	                $gv_id = $this->gv_model->add($gv_data);
	                
	                //client_id 操作
	                $gc_data['app_id'] = $app_id;
	                $gc_data['version'] = $version;
	                $gc_data['client_key'] = md5($version.md5($game_data['initial'].rand(10,1000)));
	                $gc_data['gv_id'] = $gv_id;
	                $gc_data['gv_new_id'] = $gv_id;
	                $this->gc_model->add($gc_data);
	                $this->success("添加成功！", U("Game/index"));
	            }
	        }else{
	            $this->error($this->game_model->getError());
	        }
	        exit;
	    }
	    $this->error('页面不存在');
	}
	
	
	public function edit_post(){
	    if (IS_POST) {
	        $game_data['id'] = I('appid/d');
	        /* 获取POST数据 */
	        $game_data['name']        = trim(I('post.gamename'));
	        $game_data['agent_rate']  = I('post.agent_rate');
	        $game_data['game_rate']   = I('post.game_rate');
// 	        $version   = I('post.version');
	        $game_data['update_time'] = time();
	         
	        /* 检测输入参数合法性, 游戏ID */
	        if (empty($game_data['id'])) {
	            $this->error("参数错误");
	        }
	         
	        /* 检测输入参数合法性, 游戏名 */
	        if (empty($game_data['name'])) {
	            $this->error("游戏名为空，请填写游戏名");
	        }
	         
	        /* 检测输入参数合法性, 游戏分成比例  */
	        if (empty($game_data['game_rate'])) {
	            $this->error("CP分成比例为空，请填写CP分成比例");
	        }else{
	            $game_data['game_rate'] = (float)$game_data['game_rate'];
	            if (empty($game_data['game_rate'])  || $game_data['game_rate'] < 0 || $game_data['game_rate'] > 1){
	                $this->error("CP分成比例填写错误，请填写正确比例");
	            }
	        }
	         
	        /* 检测输入参数合法性, 渠道分成比例  */
	        if (empty($game_data['agent_rate'])) {
	            $this->error("渠道分成比例为空，请填写渠道分成比例");
	        }else{
	            $game_data['agent_rate'] = (float)$game_data['agent_rate'];
	            if (empty($game_data['agent_rate']) || $game_data['agent_rate'] < 0 || $game_data['agent_rate'] > 1){
	                $this->error("渠道分成比例填写错误，请填写正确比例");
	            }
	        }
	         
	        /* 检测输入参数合法性, 游戏版本  */
// 	        if (empty($version)) {
// 	            $this->error("游戏版本为空，请填写游戏版本");
// 	        }else{
// 	            $checkExpressions = "/^\d+(\.\d+){0,2}$/";
// 	            $len = strlen($version);
// 	            if ($len>10 || false == preg_match($checkExpressions, $version)){
// 	                $this->error("游戏版本号填写错误，数字与小数点组合");
// 	            }
// 	        }
	        
	        if($this->game_model->create($game_data)){
	            $rs = $this->game_model->save();
	             
	            /* 更新游戏版本  */
// 	            if($rs>0){
	                	
// 	                //游戏版本插入
// 	                $gv_data['app_id'] = $game_data['id'];
// 	                $gv_data['version'] = $version;
// 	                $gv_data['update_time'] = $game_data['update_time'];
// 	                $rs = $this->gv_model->where(array('app_id'=>$app_id))->save($gv_data);
	                 
// 	                //client_id 操作
// 	                $gc_data['app_id'] = $game_data['id'];
// 	                $gc_data['version'] = $version;
// 	                $this->gc_model->where(array('app_id'=>$app_id))->save($gc_data);
	                $this->success("更新成功！", U("Game/index"));
// 	            }
	        }else{
	            $this->error($this->game_model->getError());
	        }
	        exit;
	    }
	    $this->error('页面不存在');
	}

	/**
	 * 添加游戏回调
	 */
	public function addurl(){
		$appid = I("appid");
		$games = $this->game_model-> where("id = %d",$appid)->find();
		if($games){
			$this->assign("games",$games);
		}else{
			$this->error("请生成参数对接后,再添加回调");
		}
		$this->assign("games",$games);
		$this -> display();
	}

	/**
	 * 渠道添加游戏回调
	 */
	public function addurl_post() {
		$appid = I("appid");
		$cpurl = I("post.cpurl","","trim");
		if(empty($cpurl)){
			$this->error("请填写回调地址");
		}
		
		$checkExpressions = '|^http://|';

		if (false == preg_match($checkExpressions, $cpurl)){
		    $this->error("请输入正确的回调地址http://开头");
		}
		
		$g_data['id'] = $appid;
        $g_data['update_time'] = time();
		$g_data['cpurl'] = $cpurl;
		$rs = $this->game_model->where(array('id'=>$appid))->save($g_data);
		if (FALSE != $rs){
		    $this->success("添加成功！",U("Game/index"));
		    exit;
		}else{
		    $this->error("添加失败！");
		}
	}

	/**
	 * 修改游戏回调
	 */
	public function editurl(){
	    $appid = I("appid");
	    $games = $this->game_model-> where("id = %d",$appid)->find();
	    $this->assign("games",$games);
	    $this -> display();
	}
	
	/**
	 * 修改游戏回调
	 */
	public function editurl_post() {
	    $appid = I("appid");
	    $cpurl = I("post.cpurl","","trim");
	    if(empty($cpurl)){
	        $this->error("请填写回调地址");
	    }
	    
	    $checkExpressions = '|^http://|';
	    
	    if (false == preg_match($checkExpressions, $cpurl)){
	        $this->error("请输入正确的回调地址http://开头");
	    }
	    
	    $g_data['id'] = $appid;
	    $g_data['update_time'] = time();
	    $g_data['cpurl'] = $cpurl;
	    $rs = $this->game_model->where(array('id'=>$appid))->save($g_data);
	    if (FALSE != $rs){
	        $this->success("修改成功！",U("Game/index"));
	        exit;
	    }else{
	        $this->error("修改失败！");
	    }
	}
	
	/**
	 * 添加游戏母包
	 */
	public function addpackageurl(){
	    $appid = I("appid");
	    $games = $this->gv_model->where("app_id=%d",$appid)->find();
	    $initial = $this->game_model-> where("id = %d",$appid)->getField('initial');
	    if(empty($games)){
	        $this->error("请生成参数对接后,再添加游戏母包地址");
	    }
	    
	    $opt = md5(md5($initial.$initial).'resub');
	    $pinyin = base64_encode($initial);
	    $agentgame = base64_encode($initial);
	    $opt = base64_encode($opt);
	    $data_string = array ('p' => $pinyin, 'a' => $agentgame, 'o' =>$opt);
	    $data_string = json_encode($data_string);
	    $url = DOWNSITE."/subPackage.php";
	    $cnt = 0;
	    while(1){
	        $return_content = base64_decode(http_post_data($url, $data_string));
	        if (0 < $return_content || 3 == $cnt){
	            break;
	        }
	    
	        $cnt ++;
	    }
	    
	    //若存在则更新地址
	    if (2 == $return_content){
            $games['packageurl'] = "sdkgame/".$initial.'/'.$initial.'.apk';
            $this->gv_model->save($games);
	    }
	    
	    $this->assign('initial', $initial);
	    $this->assign("games",$games);
	    $this -> display();
	}
	
	/**
	 * 渠道添加游戏母包
	 */
	public function addpackageurl_post() {
	    $appid = I("appid/d");
	    $gv_id = I("id/d");
	    if (empty($appid)){
	        $this->error("参数错误");
	    }
	    
	    if (empty($gv_id)){
	        $this->error("参数错误");
	    }
	    
	    $packageurl = I("post.packageurl","","trim");
	    if(empty($packageurl)){
	        $this->error("请填写回调地址");
	    }
	
	    $checkExpressions = '|^http://|';
	
	    if (false == preg_match($checkExpressions, $packageurl)){
	        $this->error("请输入正确的游戏母包地址http://开头");
	    }
	
	    $g_data['id'] = $gv_id;
	    $g_data['app_id'] = $appid;
	    $g_data['packageurl'] = $packageurl;
	    $rs = $this->gv_model->where(array('id'=>$gv_id))->save($g_data);
	    if (FALSE != $rs){
	        $this->success("添加成功！",U("Game/index"));
	        exit;
	    }else{
	        $this->error("地址已存在，添加失败！");
	    }
	}
	
	/**
	 * 修改游戏母包
	 */
	public function editpackageurl(){
	    $appid = I("appid");
	    $games = $this->gv_model-> where("app_id = %d",$appid)->order('id desc')->find();
	    $this->assign("games",$games);
	    $this -> display();
	}
	
	/**
	 * 修改游戏母包POST
	 */
	public function editpackageurl_post() {
	    $appid = I("appid");
	    $gv_id = I("id/d");
	    $packageurl = I("post.packageurl","","trim");
	    if(empty($packageurl)){
	        $this->error("请填写母包地址");
	    }
	     
	    $checkExpressions = '|^http://|';
	     
	    if (false == preg_match($checkExpressions, $packageurl)){
	        $this->error("请输入正确的回调地址http://开头");
	    }
	     
	    $g_data['id'] = $gv_id;
	    $g_data['update_time'] = time();
	    $g_data['packageurl'] = $packageurl;
	    $rs = $this->gv_model->where(array('id'=>$gv_id))->save($g_data);
	    if (FALSE != $rs){
	        $this->success("修改成功！",U("Game/index"));
	        exit;
	    }else{
	        $this->error("修改失败！");
	    }
	}

	/**
	 * 删除游戏
	 */
	public function delGame(){
		$id = I('id',0);
		$data['is_delete'] = 1;
		$rs = $this->game_model->where("id = %d",$id)->save($data);

		if($rs){
			$this->success("删除成功",U("Game/delindex",array('appid'=>$id)));
			exit;
		}
		$this->error('删除失败.');
	}
	
	/**
	 * 还原游戏
	 */
	public function restoreGame(){
	    $id = I('id/d',0);
	    $data['is_delete'] = 2;
	
	    $rs = $this->game_model->where("id = %d",$id)->save($data);
	
	    if($rs){
	        $this->success("还原成功",U("Game/index", array('appid'=>$id)));
	        exit;
	    }
	    $this->error('请求失败.');
	}
	
	/**
	 * 游戏状态处理
	 */
	public function set_status(){
		$id = I('id',0);
		$status = I('status',0);
		if (empty($status)){
		    $this->error("状态错误");
		}
		
		if (2 == $status){
		    $g_data = $this->game_model->where(array('id'=>$id))->find();
		    if (empty($g_data['cpurl'])){
		        $this->error("请填写回调地址");
		    }
		    $gv_id = $this->gc_model->where(array('app_id'=>$id))->getField('gv_id');
		    $packageurl = $this->gv_model->where(array('id'=>$gv_id))->getField('packageurl');
		    if (empty($packageurl)){
		        $this->error("请上传母包");
		    }
		    
		    $data['run_time'] = time();
		}
		$data['status'] = $status;
		$rs = $this->game_model->where("id = %d",$id)->save($data);
		if($rs){
			$this->success("状态切换成功",U("Game/index",array('appid'=>$id)));
			exit;
		}else{
		    $this->error('状态切换失败.');
		}
	}
	
	
	/**
	 * 设置是否在app中显示
	 */
	public function set_appstatus(){
	    $id = I('id',0);
	    $status = I('appstatus',0);
	    if (empty($status)){
	        $this->error("状态错误");
	    }
	
	    $map['id'] = $id;
	    $data['is_app'] = $status;
	    $rs = $this->game_model->where($map)->save($data);
	    if($rs){
	        $this->success("APP中显示成功",U("Newapp/Game/index",array('appid'=>$id)));
	        exit;
	    }else{
	        $this->error('APP中显示失败');
	    }
	}
	
	 /**
	  **游戏下拉列表
	  **/
	 public function _game_status($option=NULL){
	     if (empty($option)){
	         $cates=array(
	                 "0"=>"全部",
	                 "1"=>"游戏接入中",
	                 "2"=>"已上线",
	                 "3"=>"已下线",
	                 "4"=>"已删除",
	         );
	     }elseif (1 == $option){
	         $cates=array(
	                 "1"=>"游戏接入中",
	         );
	     }else{
	         $cates=array(
	                 "1"=>"游戏接入中",
	                 "2"=>"已上线",
	                 "3"=>"已下线",
	         );
	     }
	    
	    $this->assign("gamestatues",$cates);
	 }
}
