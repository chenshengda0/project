<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class MainController extends AdminbaseController {
	
	protected $users_model,$daypaymodel,$where,$start_time,$userwhere;

	
	function _initialize() {
		parent::_initialize();
		$this->daypaymodel = M('day_pay');
		$this->users_model = D("Common/Users");
		$this->start_time = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$this->where = " 1 ";
		$this->userwhere = " 1 ";
		
		$admin_id = sp_get_current_admin_id();
		/* 3 表示渠道市场 4 表示渠道 */
		if ($this->role_type == 4 ) {
            $this->daypaymodel = M('day_agent');
            $this->where = "agent_id".$this->agentwhere;
			$this->userwhere = "u.id =".$admin_id;
		}else if($this->role_type == 3){
			$userids = $this->_getOwnerAgents();
			$this->where = "agent_id".$this->agentwhere;
			$this->userwhere = "u.id in (".$userids.") ";
			$this->daypaymodel = M('day_agentgame');
		}
		$this->assign('role_type', $this->role_type);
	}
    public function index(){
    	$this->_todayreg();
		$this->_regcnt();
		$this->_todaylogin();
		$this->_todaypayuser();
		$this->_todaypay();
		$this->_yesterdaypay();
		$this->_paymoney();
		$this->_todaygames();
		$this->_gamescnt();
		$this->_todayagent();
		$this->_agentcnt();
		$this->_packagecnt();
		$this->_paylist();
		$this->_userloginlist();
		$this->_userreglist();
		$this->_userpaylist();
    	$this->display();
    }
	
	//今日注册
	function _todayreg(){
        $where = "reg_time>".$this->start_time;
        if (1 < $this->role_type){
            $where .= " AND ".$this->where;
        }
        $todayreg = M('members')->where($where)->count('id');
		$todayreg = empty($todayreg) ? 0 : $todayreg;
		$this->assign("todayreg",$todayreg);
	}

	//历史注册
	function _regcnt(){
        $reg_model = M('agent_ext');
        $regcnt = $reg_model->where($this->where)->SUM('reg_cnt');
		$regcnt = empty($regcnt) ? 0 : $regcnt;
		$this->assign("regcnt",$regcnt);
	}
	
	//今日登陆
	function _todaylogin(){
	    $where = "login_time>".$this->start_time;
	    if (1 < $this->role_type){
	        $where .= " AND ".$this->where;
        }
	    $todaylogin = M('login_log')->where($where)->count('distinct(mem_id)');
	    
		$todaylogin = empty($todaylogin) ? 0 : $todaylogin;
		$this->assign("todaylogin",$todaylogin);
	}
	
	//今日付费用户
	function _todaypayuser(){
        $where = "status =2 AND create_time>".$this->start_time;
        if (1<$this->role_type){
            $where .= " AND ".$this->where;
        }
        $todaypayuser = M('pay')->where($where)->count('distinct(mem_id)');
        
        $todaypayuser = empty($todaypayuser) ? 0 : $todaypayuser;
		$this->assign("todaypayuser",$todaypayuser);
	}
	
	//今日流水
	function _todaypay(){
	    $where = "status =2 AND create_time>".$this->start_time;
		if (1 < $this->role_type){
            $where .= " AND ".$this->where;
        }
		
	    $todaypay = M('pay')->where($where)->sum('amount');
	    
		$todaypay = empty($todaypay) ? 0 : $todaypay;
		$this->assign("todaypay",$todaypay);
	}
	
	//昨日流水
	function _yesterdaypay(){
		$day = date("Y-m-d",strtotime("-1 day"));
	    $yesterpay = $this->daypaymodel
						->where("$this->where and date = '%s'",$day)
						->sum('sum_money');
		$yesterpay = empty($yesterpay) ? 0 : $yesterpay;
		$this->assign("yesterpay",$yesterpay);
	}
	
	//历史流水
	function _paymoney(){
	    $paymoney = $this->daypaymodel->where($this->where)
						->sum('sum_money');
		$this->assign("paymoney",$paymoney);
	}
	
	//今日游戏数
	function _todaygames(){
		$time = $this->start_time;	
        
		if (1 >= $this->role_type){
            $gamemodel = M("game");
            $todaygames = $gamemodel ->where("status = 2 AND is_delete=1 AND create_time >= %d",$time) ->count();
		}else{
		    $almodel = M('agent_game');
		    $where = $this->where. " AND create_time >= ".$time;
		    $gamescnt = $almodel->where($where)->count('distinct(app_id)');
		}
		
		if (empty($todaygames)){
		    $todaygames = 0;
		}
		
		$this->assign("todaygames",$todaygames);
	}

	//总游戏数
	function _gamescnt(){
	    if (2 > $this->role_type){
	        $gamemodel = M("game");
	        $gamescnt = $gamemodel ->where("status=2 AND is_delete=2") ->count();
	    }else{
	        $almodel = M('agent_game');
	        $gamescnt = $almodel->where($this->where)->count('distinct(app_id)');
	    }
	    
		$this->assign("gamescnt",$gamescnt);
	}
	
	//今日渠道数
	function _todayagent(){
		$todayagent = $this->users_model
		    ->alias('u')
			->join("left join ".C('DB_PREFIX')."role r ON r.id = u.user_type")
			->where($this->userwhere." AND r.role_type = 4 AND date(u.create_time) = curdate()")
			->count();
		$this->assign("todayagent",$todayagent);
	}

	//总渠道数
	function _agentcnt(){
		$agentcnt = $this->users_model
		->alias('u')
    	->join("left join ".C('DB_PREFIX')."role r ON r.id = u.user_type")
    	->where($this->userwhere." AND r.role_type = 4")
    	->count();
		$this->assign("agentcnt",$agentcnt);
	}

	//总分包数
	function _packagecnt(){
		$packagemodel = M("agent_game");
		$packagecnt = $packagemodel->where("$this->where")->count();
		$this->assign("packagecnt",$packagecnt);
	}

	//用户流水
	function _paylist(){
		$moth = date("Y-m");
		$list = $this->daypaymodel->field("date,sum_money")->where("$this->where AND  date > '%s'",$moth)->order("date")->select();
		
		$item = array();
		foreach ($list as $key=>$val){
			 $item[$val['date']] = $val['sum_money'];
		}
		
		$paydata = $this->_tabledata($item);
		$this->assign("paydata",$paydata);
	}

	//活跃用户
	function _userloginlist(){
		$moth = date("Y-m");
		$where = $this->where. " AND "." date > '%s'";
		$list = $this->daypaymodel->field("date,user_cnt")->where($where,$moth)->order("date")->select();
		
		$item = array();
		foreach ($list as $key=>$val){
			 $item[$val['date']] = $val['user_cnt'];
		}
		
		$ulogindata = $this->_tabledata($item);
		$this->assign("ulogindata",$ulogindata);
	}

	//新增用户
	function _userreglist(){
		$moth = date("Y-m");
		$where = $this->where. " AND "." date > '%s'";
		$list = $this->daypaymodel->field("date,reg_cnt")->where($where,$moth)->order("date")->select();
		
		$item = array();
		foreach ($list as $key=>$val){
			 $item[$val['date']] = $val['reg_cnt'];
		}
		
		$uregindata = $this->_tabledata($item);
		$this->assign("uregindata",$uregindata);
	}

	//付费用户
	function _userpaylist(){
		$moth = date("Y-m");
		$where = $this->where. " AND "." date > '%s'";
		$list = $this->daypaymodel->field("date,pay_user_cnt")->where($where,$moth)->order("date ASC")->select();
		
		$item = array();
		foreach ($list as $key=>$val){
			 $item[$val['date']] = $val['pay_user_cnt'];
		}
		
		$upaydata = $this->_tabledata($item);
		$this->assign("upaydata",$upaydata);
	}


	//图形分析数据转换
	function _tabledata($item){
		$list = array();

		$j = date("t"); //获取当前月份天数
		$start_time = strtotime(date('Y-m-01'));  //获取本月第一天时间戳
		$number = 1;
		for($i=0;$i<$j;$i++){
			 $day = date('Y-m-d',$start_time+$i*86400);
			
			 if($item[$day] > 0 && !empty($item[$day])){
				$arr = array($number,floatval($item[$day]));
			 }else{
				$arr = array($number,0);
			 }
			 array_push($list,$arr);
			 $number = $number+1;
		}
		return json_encode($list);
	}
}