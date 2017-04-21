<?php
/**
* 平台充值管理
*
* @author
*/
namespace Pay\Controller;
use Common\Controller\AdminbaseController;

class PtbController extends AdminbaseController {
    
    function _payway($type=NULL, $option=true){
        $cates=array(
                ""=>"全部"
        );
        $payways = M('payway')->getField("id,realname", true);
        if($option){
            $payways=$cates + $payways;
        }
    
        $this->assign("payways",$payways);
    }
    
    /* 平台币余额 */
    public function Ptb() {
        $ptbname = C('PTBNAME');
        if(empty($ptbname)){
            $ptbname = '平台币';
        }
         
        $this->assign("ptbname",$ptbname);
        
    	$this->_ptbList();
    	$this->display();
    }
    
    public function _ptbList() {
        $where_ands = array();
        $fields = array(
                'start_time' => array(
                        "field" => "pm.update_time", 
                        "operator" => ">" 
                ),
                
                'end_time' => array(
                        "field" => "pm.update_time", 
                        "operator" => "<" 
                ),
                
                'username' => array(
                        "field" => "m.username", 
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
                    
                    if ('start_time' == $param) {
                        $get = strtotime($get);
                    } else if ('end_time' == $param) {
                        $get .= " 23:59:59";
                        $get = strtotime($get);
                    } 
                    
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
                    
                    if ('start_time' == $param) {
                        $get = strtotime($get);
                    } else if ('end_time' == $param) {
                        $get .= " 23:59:59";
                        $get = strtotime($get);
                    }
                    
                    if ($operator == "like") {
                        $get = "%$get%";
                    }                    
                    array_push($where_ands, "$field $operator '$get'");
                }
            }  
        }

        $where = join(" and ", $where_ands); 

        $count = M('ptb_mem')
        ->alias("pm")
        ->join("left join " . C('DB_PREFIX') . "members m ON pm.mem_id = m.id")
        ->where($where)
        ->count();

        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
        $page = $this->page($count, $rows);
        
        $field = "pm.*, m.username username, m.reg_time";
        
        $items =  M('ptb_mem')
        ->alias("pm")
        ->field($field)
        ->join("left join " . C('DB_PREFIX') . "members m ON pm.mem_id = m.id")
        ->where($where)
        ->order("m.id DESC")
        ->limit($page->firstRow . ',' . $page->listRows)
        ->select();
        
        $sumlist = M('ptb_mem')
        ->alias("pm")
        ->field("sum(total) total, sum(remain) remain")
        ->join("left join " . C('DB_PREFIX') . "members m ON pm.mem_id = m.id")
        ->where($where)
        ->find();

        $this->assign("sumlist", $sumlist);
        $this->assign("items", $items);
        $this->assign("formget", $_GET);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->GetCurrentPage());
    }
    
    /**
     * 平台币手充
     */
    public function setPtb(){
    	$ptbname = C('PTBNAME');
    	if(empty($ptbname)){
    		$ptbname = '平台币';
    	}
    	
    	$this->assign("ptbname",$ptbname);
    	$this->display();
    }

    /**
     * 平台币手充
     */
    public function setPtb_verify(){
        $data['username'] = I('username');
        $data['ptb'] = I('newptb',0);
        $data['beizhu'] = I('beizhu');
        $data['amount'] = I('amount');
        $data['create_time'] = time();
        
        if (empty($data['username']) || $data['ptb'] <= 0) {
            $this->error("请填写完整参数.");
            exit();
        }
        
        if ($data['amount'] < 0) {
            $this->error("充值金额错误");
            exit();
        }
        
        if ($data['ptb'] > 10000){
            $this->error("超出单笔最大金额10000");
        }
        
                
        $ptbname = C('PTBNAME');
        if(empty($ptbname)){
            $ptbname = '平台币';
        }
        $this->assign("ptbname",$ptbname);
        
        $this->assign($_POST);
        $this->display();
    }
    
	 /**
     * 平台币手充
     */
    public function setPtb_post(){
		//data_log("top_first_ptb.log",$ptb_str);
		//die;
		$action = I('action');
		if (isset($action) && isset($action) == 'add') {
    		$username = I('post.username','');
    		$data['ptb_cnt'] = I('post.newptb/d',0);
    		$data['remark'] = I('post.beizhu');
    		$data['money'] = I('post.amount/d');
    		$password = I('post.password');
    		$takeclass = I('post.takeclass/d');
    		
    		if (empty($username) || $data['ptb_cnt'] == 0 || empty($takeclass) || empty($password)) {
				$this->error("请填写完整参数.");
				exit();
    		}
    		
    		if ($data['money'] < 0) {
    		    $this->error("充值金额错误");
    		    exit();
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
    		$data['discount'] = $data['money']*10/$data['ptb_cnt'];
    		
    		$user_model = D("Common/Users");
    		if (1 == $takeclass){
    		    $agent_id = $user_model->where("user_login='%s'",$username)->getField('id');
    		    if (empty($agent_id)) {
    		        $this->error("用户名不存在!");
    		        exit;
    		    }
    		    $data['agent_id'] = $agent_id;
    		    $data['admin_id'] = get_current_admin_id();
    		    $pc_model = M('ptb_agentcharge');
    		    $rs = $pc_model->add($data);
    		    if ($rs){
    		        $data['flag'] = 1;
    		        M('ptb_given')->add($data);
    		        
    		        $p_model = M('ptb_agent');
    		        $p_data = $p_model->where(array('agent_id'=>$agent_id))->find();
    		        if ($p_data){
    		            $p_data['sum_money'] = $p_data['sum_money']+$data['money'];
    		            $p_data['remain'] = $p_data['remain']+$data['ptb_cnt'];
    		            $p_data['total'] = $p_data['total']+$data['ptb_cnt'];
    		            $p_data['update_time'] = $data['create_time'];
    		            $prs = $p_model->save($p_data);
    		            if ($prs){
    		                $this->success("充值成功", U('Ptb/setPtb'));
    		                exit;
    		            }    		           
    		        }else{
    		            $p_data['sum_money'] = $data['money'];
    		            $p_data['remain'] = $data['ptb_cnt'];
    		            $p_data['total'] = $data['ptb_cnt'];
    		            $p_data['create_time'] = $data['create_time'];
    		            $p_data['update_time'] = $data['create_time'];
    		            $p_data['agent_id'] = $agent_id;
    		            $prs = $p_model->add($p_data);
    		           
    		            
                        if ($prs){
                            $this->success("发放成功",U('Ptb/setPtb'));
                            exit;
                        }  
    		        }
    		    }
    		}elseif (2 == $takeclass){
    		    //充值玩家
    		    $mem_model = M('members');
    		    $mem_id = $mem_model->where("username='%s'",$username)->getField('id');
    		    if (empty($mem_id)) {
    		        $this->error("用户名不存在!");
    		        exit;
    		    }
    		    
    		    $data['mem_id'] = $mem_id;
    		    $data['admin_id'] =  get_current_admin_id();
    		    
    		    $pc_model = M('ptb_charge');
    		    $rs = $pc_model->add($data);
    		    if ($rs){
    		        $data['flag'] = 2;
    		        M('ptb_given')->add($data);
    		        $p_model = M('ptb_mem');
    		        $p_data = $p_model->where(array('mem_id'=>$mem_id))->find();
    		        if ($p_data){
    		            $p_data['sum_money'] = $p_data['remain']+$data['money'];
    		            $p_data['remain'] = $p_data['remain']+$data['ptb_cnt'];
    		            $p_data['total'] = $p_data['total']+$data['ptb_cnt'];
    		            $p_data['update_time'] = $data['create_time'];
    		            $prs = $p_model->save($p_data);
    		            if ($prs){
							//玩家充值日志
                            //$ptb_str='';
                            // $mem_prs_data_log=M("ptb_mem")->where(array('id'=>$prs))->find();
                            // $mem_data_log=M("members")->M("members")->where(array("id"=>$mem_prs_data_log['mem_id']))->find();
                            // $ptb_str.=date("Y-m-d H:i:s",$mem_prs_data_log['update_time'])."=>";
                            // $ptb_str.="充值金额：".$data['ptb_cnt'];
                            // $ptb_str.="，玩家名：".$mem_data_log['username'];
                            // $ptb_str.="，游戏名：".M("game")->where(array("id"=>$mem_data_log['app_id']))->getField("name");
                            // $ptb_str.="，渠道名：".M("users")->where(array("id"=>$mem_data_log['agent_id']))->getField("user_nicename");
                            // $ptb_str.=";\n";

                            // data_log("top_up_ptb.log",$ptb_str);
    		                $this->success("充值成功",U('Ptb/setPtb'));
    		                exit;
    		            }
    		        }else{
    		            $p_data['sum_money'] = $data['money'];
    		            $p_data['remain'] = $p_data['remain']+$data['ptb_cnt'];
    		            $p_data['total'] = $p_data['total']+$data['ptb_cnt'];
    		            $p_data['create_time'] = $data['create_time'];
    		            $p_data['update_time'] = $data['create_time'];
    		            $p_data['mem_id'] = $mem_id;
    		            $prs = $p_model->add($p_data);
    		            if ($prs){
							 //玩家首充日志
                            // $ptb_str='';
                            // $mem_prs_data_log=M("ptb_mem")->where(array('id'=>$prs))->find();
                            // $mem_data_log=M("members")->M("members")->where(array("id"=>$mem_prs_data_log['mem_id']))->find();
                            // $ptb_str.=date("Y-m-d H:i:s",$mem_prs_data_log['update_time'])."=>";
                            // $ptb_str.="充值金额：".$data['ptb_cnt'];
                            // $ptb_str.="，玩家名：".$mem_data_log['username'];
                            // $ptb_str.="，游戏名：".M("game")->where(array("id"=>$mem_data_log['app_id']))->getField("name");
                            // $ptb_str.="，渠道名：".M("users")->where(array("id"=>$mem_data_log['agent_id']))->getField("user_nicename");
                            // $ptb_str.=";\n";

                            // data_log("top_first_ptb.log",$ptb_str);
							
    		                $this->success("充值成功",U('Ptb/setPtb'));
    		                exit;
    		            }
    		        }
    		    }
    		}
    		$this->error("手充失败！");
		}
		$this->error("参数错误！");
	 }
    
    /**
     * 通过AJAX来获取用户的平台币余额
     */
    public function ajaxGetPtb() {
    	$username = I('get.username', '', 'trim');
    	$takeclass = I('get.takeclass/d');
    	
    	if (2== $takeclass){
    	    //检测该用户是否存在
    	    $model = M('members');
    	    $mem_id = $model->where("username='%s'",$username)->getField('id');
    	    if (empty($mem_id)) {
    	        echo "noexit";
    	        exit;
    	    }
    	    $model = M('ptb_mem');
    	    $ptb = $model->where("mem_id='%s'",$mem_id)->getField('remain');
    	}elseif (1 == $takeclass){
    	    //检测该用户是否存在
    	    $model = M('users');
    	    $agent_id = $model->where("user_login='%s'",$username)->getField('id');
    	    if (empty($agent_id)) {
    	        echo "noexit";
    	        exit;
    	    }
    	    $model = M('ptb_agent');
    	    $ptb = $model->where("agent_id='%s'",$agent_id)->getField('remain');
    	}else{
    	    echo "noexit";
    	    exit;
    	}
    	
    	if ($ptb) {
    		echo $ptb;
    		exit;
    	} else {
    		echo "0";
    		exit;
    	}
    }
    
	 
	 /**
     * 平台币发放记录列表
     */
    public function ptb_cList() {
        $ptbname = C('PTBNAME');
        if(empty($ptbname)){
            $ptbname = '平台币';
        }
        $this->_pay_status();
        $this->assign('ptbname', $ptbname);
        $this->_agents();
        $this->_payway();
    	$this->_ptb_cList();
    	$this->display();
    }
    
    
    public function _pay_status(){
        $cates = array(
                1=>"待支付",
                2=>"成功",
                3=>"失败",
        );
        
        $this->assign('paystatus', $cates);
    }
	/**
	**平台币充值列表
	**/
	public function _ptb_cList(){
        $where_ands = array();
        $fields = array(
                'agentname' => array(
                        "field" => "u.user_login", 
                        "operator" => "="
                ),
                
                'start_time' => array(
                        "field" => "pc.create_time", 
                        "operator" => ">" 
                ),
                
                'end_time' => array(
                        "field" => "pc.create_time", 
                        "operator" => "<" 
                ), 
                'order_id' => array(
                        "field" => "pc.order_id", 
                        "operator" => "=" 
                ), 
                
                'username' => array(
                        "field" => "m.username", 
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
                    
                    if ('start_time' == $param) {
                        $get = strtotime($get);
                    } else if ('end_time' == $param) {
                        $get .= " 23:59:59";
                        $get = strtotime($get);
                    } 
                    
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
                    
                    if ('start_time' == $param) {
                        $get = strtotime($get);
                    } else if ('end_time' == $param) {
                        $get .= " 23:59:59";
                        $get = strtotime($get);
                    }
                    
                    if ($operator == "like") {
                        $get = "%$get%";
                    }                    
                    array_push($where_ands, "$field $operator '$get'");
                }
            }  
        }

        $where = join(" and ", $where_ands); 

        $count = M('ptb_charge')
        ->alias("pc")
        ->join("left join " . C('DB_PREFIX') . "users u ON pc.admin_id = u.id")
        ->join("left join " . C('DB_PREFIX') . "members m ON pc.mem_id = m.id")
        ->where($where)
        ->count();

        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
        $page = $this->page($count, $rows);
        
        $field = "pc.*, u.user_login agentname, m.username username";
        
        $items =  M('ptb_charge')
        ->alias("pc")
        ->field($field)
        ->join("left join " . C('DB_PREFIX') . "users u ON pc.admin_id = u.id")
        ->join("left join " . C('DB_PREFIX') . "members m ON pc.mem_id = m.id")
        ->where($where)
        ->order("pc.id DESC")
        ->limit($page->firstRow . ',' . $page->listRows)
        ->select();

        $this->assign("ptbcharges", $items);
        $this->assign("formget", $_GET);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->GetCurrentPage());
    }
}