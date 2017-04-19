<?php

/**
 * 代理商管理
 * 
 * @author
 *
 */
namespace Agentmoney\Controller;
use Common\Controller\AdminbaseController;

class GmController extends AdminbaseController {
    protected $gm_model,$gmg_model;
    
    function _initialize() {
        parent::_initialize();
        $this->gm_model = M('gm');
    }
    
    function _gamemoney($option=true){
        $cates=array(
                "0"=>"请选择币种",
        );
         
        if (3 <= $this->role_type) {
            $agents = $this->_getOwnerAgents();
            $apparr = M('agent_game')->where(array('agent_id'=>array('in', $agents)))->getField('app_id', true);
            $where['app_id'] = array('in', implode(',',$apparr));
        }
        
        $gamemoneys = M('gm')->getField("app_id id, payname", true);
         
        if($option && $gamemoneys){
            $gamemoneys = $cates + $gamemoneys;
        }
    
        $this->assign("gamemoneys",$gamemoneys);
    }
    
    function _charge_flag($option=true){
        $cates=array(
                "0"=>"全部",
        );
        
        $flag = array(
                "0"=>"全部",
                "1"=>"官方发放   ",
                "2"=>"代理发放",
                "3"=>"官网充值",
        );
         
        if($option && $flag){
            $flag = $cates + $flag;
        }
    
        $this->assign("cflags",$flag);
    }
    
    function _pay_status($option=true){
        $cates=array(
                "0"=>"全部",
        );
        
        $paystatus = array(
                "0"=>"全部",
                "1"=>"待支付",
                "2"=>"支付完成",
                "3"=>"支付失败",
        );
         
        if($option && $paystatus){
            $paystatus = $cates + $paystatus;
        }
    
        $this->assign("paystatus",$paystatus);
    }
    
    function _takeclass($option=true){
        $cates=array(
                "0"=>"全部",
        );
        
        $takeclasses = array(
                "0"=>"全部",
                "1"=>"代理商",
                "2"=>"游戏玩家",
        );
         
        if($option && $takeclasses){
            $takeclasses = $cates + $takeclasses;
        }
    
        $this->assign("takeclasses",$takeclasses);
    }
    
	//币种数据统计
	public function dataidnex(){
		$this->display();
	}
	
	//游戏币发放
	public function give(){
	    $this->_gamemoney(false);
	    $this->_game();
	    $mem_id = I('mem_id/d',0);
	    $app_id = I('app_id/d',0);
	    if ($mem_id<=0){
	        $this->error('账号不存在');
	    }

	    $where = "id=".$mem_id." AND agent_id".$this->agentwhere;
	    $username = M('members')->where($where)->getField('username');
	    if (empty($username)){
	        $this->error("账号不存在");
	    }
	    
		if ($app_id<=0){
	        $this->error('游戏不存在');
	    }else{
			$agent_id = sp_get_current_admin_id();
			$remain = M('gm_agent')->where(array('app_id'=>$app_id, 'agent_id'=>$agent_id))->getField('remain');
			if (empty($remain)){
				$this->error("亲爱的，你发放的币种余额为零，不能实现发放功能，请联系您的管理员充值。");
			}
			$this->assign('app_id', $app_id);
			$this->assign('remain', $remain);
		}
	    
	    $this->_game();
	    $this->_gamemoney(false);
	    $this->assign('username', $username);
	    $this->assign('mem_id', $agent_id);
		$this->display();
	}
	
	//游戏币发放
	public function givenogame(){
	    $this->_gamemoney(true);
	    $this->_game();
	    $mem_id = I('mem_id/d',0);
	    if ($mem_id<=0){
	        $this->error('账号不存在');
	    }
	
	    $where = "id=".$mem_id." AND agent_id".$this->agentwhere;
	    $username = M('members')->where($where)->getField('username');
	    if (empty($username)){
	        $this->error("账号不存在");
	    }
	
	    $this->assign('username', $username);
	    $this->assign('mem_id', $agent_id);
	    $this->display();
	}
	
	//游戏币转账
	public function exchange(){
	    $this->_gamemoney(false);
	    $this->_game();
// 	    $agent_id = I('agent_id/d',0);
// 	    if ($agent_id<=0){
// 	        $this->error('账号错误');
// 	    }
	    
// 	    $username = M('users')->where(array('id'=>$agent_id))->getField('user_login');
// 	    $this->assign('username', $username);
	    $this->assign('agent_id', $agent_id);
		$this->display();
		
	}
	

	//代理游戏管理
	public function index(){
	    $this->_game(true);
	    $this->_gmList(2);
	    $this->display();
	}
	
	public function _gmList($is_delete){
	    $where_ands = array('is_delete='.$is_delete);
        $fields = array(
                'app_id' => array(
                        "field" => "gm.app_id",
                        "operator" => "="
                ),
        );
        
        if (IS_POST) {
            foreach ($fields as $param => $val) {
                if (isset($_POST[$param]) && !empty($_POST[$param])) {
                    $operator = $val['operator'];
                    $field = $val['field'];
                    $get = trim($_POST[$param]);
                    $_GET[$param] = $get;
        
                    if ($operator == "like") {
                        $get = "%$get%";
                    }
                    array_push($where_ands, "$field $operator '$get'");
                }
            }
        }else{
            foreach ($fields as $param => $val) {
                if (isset($_GET[$param]) && !empty($_GET[$param])) {
                    $operator = $val['operator'];
                    $field = $val['field'];
                    $get = trim($_GET[$param]);
        
                    if ($operator == "like") {
                        $get = "%$get%";
                    }
                    array_push($where_ands, "$field $operator '$get'");
                }
            }
        }
        
        $where = join(" and ", $where_ands);
        
        $count = $this->gm_model
        ->alias("gm")
        ->where($where)
        ->count();
        
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
        $page = $this->page($count, $rows);
        
        $field = "gm.*";
        
        $items = $this->gm_model
        ->alias("gm")
        ->field($field)
        ->where($where)
        ->order("gm.app_id DESC")
        ->limit($page->firstRow . ',' . $page->listRows)
        ->select();
        
        $this->assign("items", $items);
        $this->assign("formget", $_GET);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->GetCurrentPage());
    }
    
    
    /*
     * 添加游戏
     */
    public function add(){
	    $where['g.status'] = 2;
	    $where['g.is_delete'] =2;
	    
	    $games = M('game')
	    ->alias("g")	    
	    ->where($where)
	    ->where("g.id NOT IN (SELECT app_id FROM ".C('DB_PREFIX')."gm)")
	    ->getField("id,name gamename", true);
	    
	    if (empty($games)){
	        $this->error("所有游戏已添加游戏币");
	    }
	    
	    $this->assign('games',$games);
        $this->display();
    }
    
    /*
     * 编辑游戏
     */
    public function edit(){
        $app_id = I('app_id/d');
        if (empty($app_id)){
            $this->error("参数错误");
        }
        $gmdata = $this->gm_model->where(array('app_id'=>$app_id))->find();
        $this->assign($gmdata);
        $this->_game(false);
        $this->display();
    }
    
    public function add_post(){
        if (IS_POST) {
            /* 获取POST数据 */
            $gm_data['payname']   = I('post.payname');
            $gm_data['app_id']  = I('post.app_id');
            $gm_data['give_max_cnt']  = I('post.give_max_cnt/d');
            $gm_data['description']  = I('post.dis');
             
            /* 检测输入参数合法性, 游戏名 */
            if (empty($gm_data['payname'])) {
                $this->error("游戏币名称为空，请填写游戏名");
            }
             
            /* 检测输入参数合法性, 游戏分成比例  */
            if ($gm_data['give_max_cnt']<=0) {
                $this->error("单次限制格式错误");
            }
            
            $gm_data['create_time'] = time();
            $gm_data['update_time'] = $gm_data['create_time'];
            $rs = $this->gm_model->add($gm_data);
            if ($rs){
                $this->success("添加成功！", U("Gm/index"));
            }else{
                $this->error("游戏币已存在！");
            }
        }
        $this->error('页面不存在');
    }
    
    
    public function edit_post(){
        if (IS_POST) {
            /* 获取POST数据 */
            $gm_data['app_id']   = I('post.app_id/d');
            $gm_data['payname']   = I('post.payname');
            $gm_data['app_id']  = I('post.app_id');
            $gm_data['give_max_cnt']  = I('post.give_max_cnt/d');
            $gm_data['description']  = I('post.dis');
             
            /* 检测输入参数合法性, 游戏名 */
            if (empty($gm_data['payname'])) {
                $this->error("游戏币名称为空，请填写游戏名");
            }
             
            if ($gm_data['app_id'] <= 0) {
                $this->error("游戏错误");
            }
             
            /* 检测输入参数合法性, 游戏分成比例  */
            if ($gm_data['give_max_cnt']<=0) {
                $this->error("单次限制格式错误");
            }
            
            $gm_data['update_time'] = time();
            $rs = $this->gm_model->save($gm_data);
            if ($rs){
                $this->success("更新成功！", U("Gm/index"));
            }else{
                $this->error("更新失败！");
            }
        }
        $this->error('页面不存在');
    }
    
    /**
     * 删除游戏
     */
    public function del(){
        $app_id = I('app_id/d');
        $rs = $this->gm_model->where("app_id = %d",$app_id)->setField('is_delete', 1);
        if($rs){
            $this->success("删除成功");
            exit;
        }
        $this->error('删除失败.');
    }
    
	
	public function give_verify(){
	    $data['username'] = I('username');
	    $data['gm_cnt'] = I('newgm/d',0);
	    $data['beizhu'] = I('beizhu');
	    $data['amount'] = I('amount');
	    
	    if (empty($data['username']) || $data['gm_cnt'] <= 0) {
	        $this->error("请填写完整参数.");
	        exit();
	    }
	    
	    if ($data['amount'] < 0) {
	        $this->error("充值金额错误");
	        exit();
	    }
	    
	    $agent_id  = sp_get_current_admin_id();
	    $give_max_cnt = M('gm_agent')->where(array('app_id'=>$_POST['app_id'], 'agent_id'=>$agent_id))->getField('remain');
	    if ($data['gm_cnt'] > $give_max_cnt){
	        $this->error($data['gm_cnt'].'超过已有游戏币额度'.$give_max_cnt.',发放失败');
	    }
	    
	    $this->assign('remain', $give_max_cnt);
	    
	    $this->assign($_POST);
	    $this->_game(false);
	    $this->_gamemoney(false);
		$this->display();
	}
	
	//游戏币发放
	public function give_post(){
		$action = I('action');
		if (isset($action) && isset($action) == 'add') {
    		$username = I('post.username','','trim');
    		$data['gm_cnt'] = I('post.newgm/d',0);
    		$data['remark'] = I('post.beizhu');
    		$data['money'] = I('post.amount/d');
    		$data['app_id'] = I('post.app_id/d');
    		$password = I('post.password');
    		$takeclass = I('post.takeclass/d');
    		
    		if (empty($username) || $data['gm_cnt'] <= 0 || empty($takeclass) || empty($password)) {
				$this->error("请填写完整参数.");
				exit();
    		}
    		
    		if ($data['money'] < 0) {
    		    $this->error("充值金额错误");
    		    exit();
    		}
    		
    		if ($data['app_id'] <= 0) {
    		    $this->error("游戏错误");
    		    exit();
    		}
    		
    		$data['admin_id'] = get_current_admin_id();
    		$give_max_cnt = M('gm_agent')->where(array('app_id'=>$data['app_id'], 'agent_id'=>$data['admin_id']))->getField('remain');
    		if ($data['gm_cnt'] > $give_max_cnt){
    		    $this->error($data['gm_cnt'].'超过已有游戏币额度'.$give_max_cnt.',发放失败');
    		}
    		//验证密码
    		$this->verifyPaypwd($password);
	        		
    		//插入记录
    		$data['order_id'] = setorderid();
    		$data['flag'] = 1;
    		$data['payway'] = 0;
    		$data['ip'] = get_client_ip();
    		$data['status'] = 2;
    		$data['create_time'] = time();
    		$data['update_time'] = $data['create_time'];
    		$data['discount'] = $data['money']*10/$data['gm_cnt'];
    		
    		$user_model = D("Common/Users");
    		if (1 == $takeclass){
    		    $agent_id = $user_model->where("user_login='%s'",$username)->getField('id');
    		    if (empty($agent_id)) {
    		        $this->error("用户名不存在!");
    		        exit;
    		    }
    		    $data['agent_id'] = $agent_id;
    		    if ($data['agent_id'] == $data['admin_id']){
    		        $this->error("不能给自己发币");
    		    }
    		    $gmac_model = M('gm_agentcharge');
    		    $rs = $gmac_model->add($data);
    		    if ($rs){
    		        $ga_model = M('gm_agent');
    		        //扣除拥有数量
    		        $gaars = $ga_model->where(array('agent_id'=>$data['admin_id'], 'app_id'=>$data['app_id']))->setDec('remain', $data['gm_cnt']);
    		        if (!$gaars){
    		            $this->error('转账失败');
    		        }
    		        
    		        $ga_data = $ga_model->where(array('agent_id'=>$agent_id, 'app_id'=>$data['app_id']))->find();
    		        if ($ga_data){
    		            $ga_data['sum_money'] = $ga_data['sum_money']+$data['money'];
    		            $ga_data['remain'] = $ga_data['remain']+$data['gm_cnt'];
    		            $ga_data['total'] = $ga_data['total']+$data['gm_cnt'];
    		            $ga_data['update_time'] = $data['create_time'];
    		            $gars = $ga_model->save($ga_data);
    		            
    		            if ($gars){
    		                $this->success("转账成功", U('Gm/exchange'));
    		                exit;
    		            }    		           
    		        }else{ 
    		            $ga_data['agent_id'] = $data['agent_id'];
    		            $ga_data['app_id'] = $data['app_id'];
    		            $ga_data['sum_money'] = $data['money'];
    		            $ga_data['remain'] = $data['gm_cnt'];
    		            $ga_data['total'] = $data['gm_cnt'];
    		            $ga_data['create_time'] = $data['create_time'];
    		            $ga_data['update_time'] = $data['create_time'];
    		            $gars = $ga_model->add($ga_data);
    		            if ($gars){
    		                $this->success("转账成功", U('Gm/exchange'));
    		                exit;
    		            }    		  
    		        }
    		    }
    		}elseif (2 == $takeclass){
    		    $mem_model = M('members');
    		    $mem_id = $mem_model->where("username='%s'",$username)->getField('id');
    		    if (empty($mem_id)) {
    		        $this->error("用户名不存在!");
    		        exit;
    		    }
    		    $data['mem_id'] = $mem_id;
    		    
    		    $gmc_model = M('gm_charge');
    		    $rs = $gmc_model->add($data);
    		    if ($rs){
    		        
    		        $ga_model = M('gm_agent');
    		        //扣除拥有数量
    		        $gaars = $ga_model->where(array('agent_id'=>$data['admin_id'], 'app_id'=>$data['app_id']))->setDec('remain', $data['gm_cnt']);
    		        if (!$gaars){
    		            $this->error('转账失败');
    		        }
    		        
    		        $gmm_model = M('gm_mem');
    		        $gmm_data = $gmm_model->where(array('mem_id'=>$mem_id, 'app_id'=>$data['app_id']))->find();
    		        if ($gmm_data){
    		            $gmm_data['sum_money'] = $gmm_data['sum_money']+$data['money'];
    		            $gmm_data['remain'] = $gmm_data['remain']+$data['gm_cnt'];
    		            $gmm_data['total'] = $gmm_data['total']+$data['gm_cnt'];
    		            $gmm_data['update_time'] = $data['create_time'];
    		            $gmmrs = $gmm_model->save($gmm_data);
    		            if ($gmmrs){
    		                $this->success("发放成功", U('Gmmem/index'));
    		                exit;
    		            }    		           
    		        }else{ 
    		            $gmm_data['mem_id'] = $data['mem_id'];
    		            $gmm_data['app_id'] = $data['app_id'];
    		            $gmm_data['sum_money'] = $data['money'];
    		            $gmm_data['remain'] = $data['gm_cnt'];
    		            $gmm_data['total'] = $data['gm_cnt'];
    		            $gmm_data['create_time'] = $data['create_time'];
    		            $gmm_data['update_time'] = $data['create_time'];
    		            $gmmrs = $gmm_model->add($gmm_data);
    		            if ($gmmrs){
    		                $this->success("发放成功", U('Gmmem/index'));
    		                exit;
    		            }    		  
    		        }
    		    }
    		}
    		$this->error("发放失败！");
		}
		$this->error("参数错误！");
	 }
	 
	
   	/**
   	 * 通过AJAX来获取用户的平台币余额
   	 */
    public function ajaxGetgm(){
    	$username = I('get.username', '', 'trim');
    	$takeclass = I('get.takeclass/d');
    	$app_id = I('get.app_id/d');
    	if (empty($app_id)){
    	    echo "noexit";
    	    exit;
    	}
    	$takeclass = 1;
    	
    	if (2== $takeclass){
    	    //检测该用户是否存在
    	    $model = M('members');
    	    $mem_id = $model->where("username='%s'",$username)->getField('id');
    	    if (empty($mem_id)) {
    	        echo "noexit";
    	        exit;
    	    }
    	    $model = M('gm_mem');
    	    $where['mem_id'] = $mem_id;
    	    $where['app_id'] = $app_id;
    	    $gm = $model->where($where)->getField('remain');
    	}elseif (1 == $takeclass){
    	    //检测该代理是否存在
    	    // $model = M('users');
    	    // $agent_id = $model->where("user_login='%s'",$username)->getField('id');
    	    // if (empty($agent_id)) {
    	        // echo "noexit";
    	        // exit;
    	    // }
    	    $model = M('gm_agent');
    	    $where['agent_id'] = get_current_admin_id();
    	    $where['app_id'] = $app_id;
    	    $gm = $model->where($where)->getField('remain');
    	}else{
    	    echo "noexit";
    	    exit;
    	}
    	
    	if ($gm) {
    		echo $gm;
    		exit;
    	} else {
    		echo "0";
    		exit;
    	}
    }
}
