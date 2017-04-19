<?php

/**
 * 渠道页面
 * 
 * @author
 *
 */
namespace Agent\Controller;
use Common\Controller\AdminbaseController;

class AgentController extends AdminbaseController {
	protected $users_model,$role_model,$role_user_model,$game_model,$where;
	
	function _initialize() {
		parent::_initialize();
		$this->users_model = D("Common/Users");
		$this->role_model = D("Common/Role");
		$this->role_user_model = M("RoleUser");
		$this->game_model = D("Common/Game");
		
		if (2<$this->role_type) {
			$this->where = "agentid".$this->agentwhere;
		}else{
            $this->where = "1 ";
        }
	}

    /**
     * 渠道列表
     * return void
     */
    public function index() {
        if (2<$this->role_type){
            $this->_roles(4);
        }else{
            $this->_roles(3);
        }
        
        $this->_aList();
        $this->display();
    }

	//渠道列表
	public function _aList(){
		$roleid = I('roleid/d',0);
		$nickname = I('nickname', '', 'trim');
		$agentname = I('agentname', '', 'trim');

		$where = "r.role_type > 2 ";
		if (3 <= $this->role_type){
		    $where = "AND  u.id ".$this->agentwhere;
		}
		
    	if ($nickname) {
			$where .= " AND u.user_nicename like '%$nickname%'";
			$this->assign("nickname",$nickname);
    	}
    	
    	if ($agentname) {
			$where .= " AND u.user_login like '%$agentname%'";
			$this->assign("agentname",$agentname);
    	}

    	if ($roleid) {
			$where .= " AND r.id = ".$roleid;
			$this->assign("roleid",$roleid);
    	}

		$count=$this->users_model
		->alias('u')
		->join("LEFT JOIN ".C('DB_PREFIX')."role r ON r.id = u.user_type")
		->where($where)
		->count();

		$row = isset($_POST['row'])? $_POST['row']:$this->row;
		$page = $this->page($count, $row);
		$field = "u.id id, r.name rolename, u.user_login, u.user_nicename ";
		$field .= ",u.linkman, u.mobile, u.qq, u.last_login_time, u.user_status ";
		
		//下载
		if ($_REQUEST['downloads'] == 1) {
			$xlsCell  = array(
					array('id','账号序列'),
					array('user_login','账号'),
					array('linkman','联系人'),
					array('last_login_time','最后登录时间'),
					array('user_email','邮箱'),
					array('mobile','电话'),
					array('qq','QQ'),
			);
			$users = $this->users_model
			->alias('u')
			->join("LEFT JOIN ".C('DB_PREFIX')."role r ON r.id = u.user_type")
			->where($where)
			->order("u.id DESC")
			->select();
			$xlsName = "渠道记录表";
			$this->exportExcel($xlsName,$xlsCell,$users);
		}else{
		    $users = $this->users_model
		    ->alias('u')
		    ->field($field)
		    ->join("LEFT JOIN ".C('DB_PREFIX')."role r ON r.id = u.user_type")
		    ->where($where)
		    ->order("u.id DESC")
		    ->limit($page->firstRow . ',' . $page->listRows)
		    ->select();
		}
		
		$this->assign("users",$users);
	    $this->assign("Page", $page->show('Admin'));
	    $this->assign("current_page", $page->GetCurrentPage());
	}
	
	//添加渠道
	function add(){
	    if (3 == $this->role_type){
	        $this->_roles(4,false);
	    }else{
	        $this->_roles(3,false);
	    }
	    $this->display();
	}
	
	/*
	 *添加渠道
	 */
	public function add_post(){
		if(IS_POST){
    	    if(!empty($_POST['role_id'])){
    		    $role_id = I('role_id');
    		    $_POST['user_type'] = $role_id;
    		    $_POST['pay_pwd'] = $_POST['user_pass'];
    		    unset($_POST['role_id']);
    		    if ($this->users_model->create()) {
    		        $result=$this->users_model->add();
    		        if ($result!==false) {
    		            $uid=$result;
    		            $role_user_model=M("RoleUser");
    		            $role_user_model->where(array("user_id"=>$uid))->delete();
		                $role_user_model->add(array("role_id"=>$role_id,"user_id"=>$uid));
    		            $this->success("添加成功！",U('Agent/Agent/index'));
    		        } else {
    		            $this->error("添加失败！");
    		        }
    		    } else {
    		        $this->error($this->users_model->getError());
    		    }
    		}
		}
	}
	
	//编辑
	function edit(){
	    $id= intval(I("get.id"));
	    $user=$this->users_model->where(array("id"=>$id))->find();
	    $adminid = sp_get_current_admin_id();
	    if (2 < $this->role_type ){
	        if($adminid != $user['id'] && $adminid != $user['ownid']){
	            $this->error("无权限");
	        }
	    }
	    if (3 == $this->role_type){
	        $this->_roles(4,false);
	    }else{
	        $this->_roles(3,false);
	    }
	    $this->assign($user);
	    $this->display();
	}
	
	function edit_post(){
	    if (IS_POST) {
	        if(!empty($_POST['role_id'])){
	            if(empty($_POST['user_pass'])){
	                unset($_POST['user_pass']);
	            }
	            $role_id=$_POST['role_id'];
	            $_POST['user_type'] = $role_id;
	            unset($_POST['role_id']);
	            if ($this->users_model->create()) {
	                $result=$this->users_model->save();
	                if ($result!==false) {
	                    $uid=intval($_POST['id']);
	                    $role_user_model=M("RoleUser");
	                    $role_user_model->where(array("user_id"=>$uid))->delete();
                        $role_user_model->add(array("role_id"=>$role_id,"user_id"=>$uid));
	                    $this->success("修改成功！",U('Agent/Agent/index'));
	                } else {
	                    $this->error("修改失败！");
	                }
	            } else {
	                $this->error($this->users_model->getError());
	            }
	        }else{
	            $this->error("请为此用户指定角色！");
	        }
	        	
	    }
	}

	function ban(){
        $id=intval($_GET['id']);
    	if ($id) {
    		$rst = $this->users_model->where(array("id"=>$id))->setField('user_status','3');
    		if ($rst) {
    			$this->success("账号禁用成功！", U("Agent/index"));
    		} else {
    			$this->error('账号禁用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    
    function cancelban(){
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = $this->users_model->where(array("id"=>$id))->setField('user_status','2');
    		if ($rst) {
    			$this->success("账号启用成功！", U("Agent/index"));
    		} else {
    			$this->error('账号启用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }

   
    
    function _getAgentdata(){
        $paymodel = M('dayagentgame');
        $where_ands = array();
        $where_ends = array_push($where_ands, $this->where);
        $bflagstart = true;
        $bflagend = true;
        
        
        if ('今日' == $_GET['date_time']) {
            $_GET['start_time'] = date("Y-m-d");
            $_GET['end_time']   = date("Y-m-d");
        } elseif ('七日' == $_GET['date_time']) {
            $_GET['start_time'] = date("Y-m-d",strtotime("-6 day"));
            $_GET['end_time']   = date("Y-m-d");
        } elseif ('当月' == $_GET['date_time']) {
            $_GET['start_time'] = date("Y-m-01");
            $_GET['end_time']   = date("Y-m-d");
        } elseif ('30天' == $_GET['date_time']) {
            $_GET['start_time'] = date("Y-m-d",strtotime("-29 day"));
            $_GET['end_time']   = date("Y-m-d");
        }
        $todaytime  = mktime(0,0,0,date("m"),date("d"),date("Y"));

        $start_time = trim(I('get.start_time'));
        $end_time = trim(I('get.end_time'));
        
        if (isset($start_time) && !empty($start_time)){
            array_push($where_ands, "`date` >= '".$start_time."'");
            $bflagstart = strtotime($start_time) <= $todaytime? true : false;
        }
        
        if (isset($end_time) && !empty($end_time)){
            array_push($where_ands, "`date` <= '".$end_time."'");
            $bflagend = strtotime($end_time) >= $todaytime? true : false;
        }
        
        $agentid = trim(I('get.agentid'));
        $sumwhere = $this->where;
        $regwehre = $this->where;
        if (isset($agentid) && !empty($agentid)){
            
            $roleid = $this->getRoletype($agentid);
            
            if ($roleid >= 2 && $roleid != 5 ) {
               $wherestr = "agentid=".$agentid;
            }else if($roleid == 5){
                $userids = $this->_getOwnerAgents($agentid);
                $wherestr = " agentid in (".$userids.") ";
            }
            $regwehre .= " AND ".$wherestr;
            array_push($where_ands, $wherestr);
        }

        $where = join(" AND ", $where_ands);

        $count = $paymodel
        ->where($where)
        ->count();

        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $page = $this->page($count, $rows);
        
        $field = "`date`, `agentid`, `user_cnt`, `summoney`, `paycnt`, `regpaycnt`, `sumregmoney`, `reg_cnt`";
        $field = "`date`, `agentid`, sum(`user_cnt`) user_cnt, sum(`summoney`) summoney, sum(`paycnt`) paycnt,
                sum(`regpaycnt`), sum(`sumregmoney`) sumregmoney, sum(`reg_cnt`) reg_cnt";
        $sumfield = "sum(`user_cnt`) `user_cnt`,sum(`summoney`) `summoney`,sum(`paycnt`) `paycnt`,sum(`regpaycnt`) `regpaycnt`,sum(`sumregmoney`) `sumregmoney`,sum(`reg_cnt`) `reg_cnt`";
        $items = $paymodel
        ->field($field)
        ->where($where)
        ->group('date, agentid')
        ->order("date DESC")
        ->limit($page->firstRow . ',' . $page->listRows)
        ->select();
        
        $sumitems = $paymodel
        ->field($sumfield)
        ->where($where)
        ->select();
        
        if(!empty($start_time)){
            $sumwhere .= " AND date>='".$start_time."'";
            $regwehre .= " AND login_date>='".$start_time."'";
//             $regwehre .= " AND reg_time>='".strtotime($start_time)."'";
        }

        if(!empty($end_time)){
            $sumwhere .= " AND date<='".$end_time   ."'";
            $regwehre .= " AND login_date<='".$end_time   ."'";
//             $regwehre .= " AND reg_time<'".(strtotime($end_time)+86400)."'";
        }

        $sumitems[0]['user_cnt'] = M('daypayuser')->where($regwehre)->count('distinct(mem_id)');
//         $sumitems[0]['reg_cnt'] = M('daypayuser')->where($regwehre." AND login_date=reg_date")->count('distinct(mem_id)');
//         $sumitems[0]['reg_cnt'] = M('dayagentgame')->where($regwehre." AND login_date=reg_date")->count('distinct(mem_id)');

        //今日数据
        if (($bflagstart && $bflagend) ){
//             $field = "count(distinct(p.mem_id)) paycnt, sum(p.amount) summoney,
//                       count(distinct (case  when m.reg_time>1463328000 then p.`mem_id` else NULL end)) regpaycnt,
//                       sum(case  when m.reg_time>".$todaytime." then p.amount else 0 end) sumregmoney";
            
//             $todayitem = M('pay')->alias('p')
//                         ->field($field)
//                         ->join("LEFT JOIN ".C('DB_PREFIX')."members m ON p.mem_id=m.id")
//                         ->where("p.create_time>".$todaytime." AND p.status=1")
//                         ->find();

//             $todayitem['date'] = date('Y-m-d');
//             $todayitem['user_cnt'] = M('login_log')->where("login_time>".$todaytime)->count('distinct(mem_id)');
//             $todayitem['reg_cnt'] = M('members')->where("reg_time>".$todaytime)->count('id');
            
//             $sumitems[0]['summoney'] += $todayitem['summoney'];
//             $sumitems[0]['paycnt'] += $todayitem['paycnt'];
//             $sumitems[0]['regpaycnt'] += $todayitem['regpaycnt'];
//             $sumitems[0]['sumregmoney'] += $todayitem['sumregmoney'];
//             $sumitems[0]['reg_cnt'] += $todayitem['reg_cnt'];
//             $sumitems[0]['user_cnt'] += $todayitem['user_cnt'];
        }
       
        $this->assign("totalpays", $sumitems);
        $this->assign("pays", $items);
//         $this->assign("todaypays", $todayitem);
        $this->assign("formget", $_GET);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->GetCurrentPage());
    }

	function _getAgentreten(){
		$model = M('dayagentgame');
        $start = $_POST['start_time'];
        $end = $_POST['end_time'];

        $where = $this->where;
        if (isset($_POST['agentid']) && !empty($_POST['agentid'])){
            $where .= " AND agentid=".$_POST['agentid'];
        }
        
        $bflagstart = true;
        $bflagend = true;
        if (isset($start) && !empty($start)) {
            $where .= " AND to_days(date) >= to_days('{$start}')";
            $bflagstart = strtotime($start) < mktime(0,0,0,date("m"),date("d"),date("Y"))? true : false;
        }  
        
        if (isset($end) && !empty($end)) {
             $where .= " AND to_days(date) <=  to_days('{$end}')";
             $bflagend = strtotime($end) >= mktime(0,0,0,date("m"),date("d"),date("Y"))? true : false;
        }
        $count = 1;
        
        if ($bflagstart){
           $count =  $model
            ->where($where)->count();
        }
        
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $page = $this->page($count, $rows);
        
        if ($bflagstart) {   
			
			$sql = "SELECT `date`,g.name,u.user_nicename,`second_cnt`, `third_cnt`, `fourth_cnt`, `fifth_cnt`, `sixth_cnt`, `seventh_cnt`, `fifteenth_cnt`, `thirtieth_cnt` 
					FROM (SELECT `date`, `agentid`, `appid`, `user_cnt`, `summoney`, `paycnt`, `regpaycnt`, `sumregmoney`, `reg_cnt`, `second_cnt`, `third_cnt`, `fourth_cnt`, `fifth_cnt`, `sixth_cnt`, `seventh_cnt`, `fifteenth_cnt`, 
					`thirtieth_cnt`  FROM " . C('DB_PREFIX') . "dayagentgame
					UNION ALL
					SELECT `date`, `agentid`, `appid`, `user_cnt`, `summoney`, `paycnt`, `regpaycnt`, `sumregmoney`, `reg_cnt`, `second_cnt`, `third_cnt`, `fourth_cnt`, `fifth_cnt`, `sixth_cnt`, `seventh_cnt`, `fifteenth_cnt`, `thirtieth_cnt` FROM " . C('DB_PREFIX') . "tagentgamepayview) a
					LEFT JOIN c_game g ON (a.appid = g.appid)
					LEFT JOIN c_users u ON (a.agentid = u.id)"
					." where ".$where." limit ".$page->firstRow . ',' . $page->listRows;
			$items = $model->query($sql);
        }        
        
        $this->assign("pays", $items);
        $this->assign("start_time", $start);
        $this->assign("end_time", $end);
        $this->assign("agentid",$_POST['agentid']);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->GetCurrentPage());
	}
}
