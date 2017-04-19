<?php

/**
 * 渠道数据统计
 * 
 * @author
 *
 */
namespace Data\Controller;
use Common\Controller\AdminbaseController;
class AgentController extends AdminbaseController {
    
	protected $game_model,$where,$daymodel;
	
	function _initialize() {
		parent::_initialize();
		$this->game_model = D("Common/Game");
		$this->daymodel  = M('day_agent');
		if (2<$this->role_type) {
			$this->where = "agent_id".$this->agentwhere;
		}else{
            $this->where = "1 ";
        }
	}

	/*
	 * 渠道数据	
	*/
    public function dataindex() {
        $this->_agents();
        $this->_getAgentdata();
        $this->display();
    }

    /*
     * 渠道专员数据
     */
	public function marketindex() {
		$this->_marketList();
		$this->display();
	}
	
	//渠道数据详细
    function _getAgentdata(){
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
        
        $agent_id = I('get.agent_id/d',0);
        
        if (isset($agent_id) && !empty($agent_id)){
            $role_type = $this->_get_role_type($agent_id);

            if (4 == $role_type ) {
               $wherestr = " agent_id=".$agent_id;
            }else if($role_type == 3){
                $userids = $this->_getOwnerAgents($agent_id);
                $wherestr = " agent_id in (".$userids.") ";
            }else{
                $wherestr = " agent_id < 0 ";
            }
            $_GET['agent_id'] = $agent_id;
            array_push($where_ands, $wherestr);
        }

        $where = join(" AND ", $where_ands);

        $count = $this->daymodel
        ->where($where)
        ->count();
        
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $page = $this->page($count, $rows);
        
        $field = "`date`,`agent_id`,sum(`user_cnt`) `user_cnt`,sum(`sum_money`) `sum_money`,
                sum(`pay_user_cnt`) `pay_user_cnt`, sum(`order_cnt`) `order_cnt`,
                sum(`reg_pay_cnt`) `reg_pay_cnt`,sum(`sum_reg_money`) `sum_reg_money`,
                sum(`reg_cnt`) `reg_cnt`";
        
        $items = $this->daymodel
                ->alias('d')
                ->join("LEFT JOIN ".C('DB_PREFIX')."users u ON u.id=agent_id")
                ->field($field)
                ->where($where)
                ->group('date, agent_id')
                ->order("date DESC")
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
        
        $sumitems = $this->daymodel
                    ->field($field)
                    ->where($where)
                    ->select();
        
//         if(!empty($start_time)){
//             $sumwhere .= " AND date>='".$start_time."'";
//             $regwehre .= " AND login_date>='".$start_time."'";
// //             $regwehre .= " AND reg_time>='".strtotime($start_time)."'";
//         }

//         if(!empty($end_time)){
//             $sumwhere .= " AND date<='".$end_time   ."'";
//             $regwehre .= " AND login_date<='".$end_time   ."'";
// //             $regwehre .= " AND reg_time<'".(strtotime($end_time)+86400)."'";
//         }

//         $sumitems[0]['user_cnt'] = M('daypayuser')->where($regwehre)->count('distinct(mem_id)');
// //         $sumitems[0]['reg_cnt'] = M('daypayuser')->where($regwehre." AND login_date=reg_date")->count('distinct(userid)');
// //         $sumitems[0]['reg_cnt'] = M('dayagentgame')->where($regwehre." AND login_date=reg_date")->count('distinct(userid)');

//         //今日数据
//         if (($bflagstart && $bflagend) ){
// //             $field = "count(distinct(p.userid)) paycnt, sum(p.amount) summoney,
// //                       count(distinct (case  when m.reg_time>1463328000 then p.`userid` else NULL end)) regpaycnt,
// //                       sum(case  when m.reg_time>".$todaytime." then p.amount else 0 end) sumregmoney";
            
// //             $todayitem = M('pay')->alias('p')
// //                         ->field($field)
// //                         ->join("LEFT JOIN ".C('DB_PREFIX')."members m ON p.userid=m.id")
// //                         ->where("p.create_time>".$todaytime." AND p.status=1")
// //                         ->find();

// //             $todayitem['date'] = date('Y-m-d');
// //             $todayitem['user_cnt'] = M('login_log')->where("login_time>".$todaytime)->count('distinct(userid)');
// //             $todayitem['reg_cnt'] = M('members')->where("reg_time>".$todaytime)->count('id');
            
// //             $sumitems[0]['summoney'] += $todayitem['summoney'];
// //             $sumitems[0]['paycnt'] += $todayitem['paycnt'];
// //             $sumitems[0]['regpaycnt'] += $todayitem['regpaycnt'];
// //             $sumitems[0]['sumregmoney'] += $todayitem['sumregmoney'];
// //             $sumitems[0]['reg_cnt'] += $todayitem['reg_cnt'];
// //             $sumitems[0]['user_cnt'] += $todayitem['user_cnt'];
//         }
       
        $this->assign("totalpays", $sumitems);
        $this->assign("pays", $items);
//         $this->assign("todaypays", $todayitem);
        $this->assign("formget", $_GET);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->GetCurrentPage());
    }
    
    function _marketList(){
		$cates=array(
	            "0"=>"全部渠道"
	    );
	     
	    $agents = array();
        $aidarr = M('role')->where(array('role_type'=>3, 'status'=>1))->getField('id', true);
        $aidstr = implode(',', $aidarr);

        if ($aidstr){
            $where['user_type'] = array('in', $aidstr);
            $agents = M('users')->where($where)->getField("id,user_login agentname", true);
        }
		
		
	    
        if ($agents){
			
            $agents = $cates+$agents;
        }else{
			return NULL;
		}
	    $this->assign("agents",$agents);
        
		$agentarr = M('users')->where($where)->getField("id", true);
		$agentstr = implode(',', $agentarr);
		$where_ands = array("u.ownerid in ($agentstr)");
		
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
        
        $agent_id = I('get.agent_id/d',0);
        
        if (isset($agent_id) && !empty($agent_id)){
            $_GET['agent_id'] = $agent_id;
			$wherestr = " u.ownerid  = ".$agent_id;
            array_push($where_ands, $wherestr);
        }

        $where = join(" AND ", $where_ands);
		
        $count = $this->daymodel
		->alias('d')
        ->join("LEFT JOIN ".C('DB_PREFIX')."users u ON u.id=d.agent_id")
        ->where($where)
        ->count();
		
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $page = $this->page($count, $rows);
        
        $field = "`date`,u.ownerid agent_id,sum(`user_cnt`) `user_cnt`,sum(`sum_money`) `sum_money`,
                sum(`pay_user_cnt`) `pay_user_cnt`, sum(`order_cnt`) `order_cnt`,
                sum(`reg_pay_cnt`) `reg_pay_cnt`,sum(`sum_reg_money`) `sum_reg_money`,
                sum(`reg_cnt`) `reg_cnt`";
        
        $items = $this->daymodel
                ->alias('d')
                ->join("LEFT JOIN ".C('DB_PREFIX')."users u ON u.id=d.agent_id")
                ->field($field)
                ->where($where)
                ->group('date, u.ownerid')
                ->order("date DESC")
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
        
        $sumitems = $this->daymodel
					->alias('d')
					->join("LEFT JOIN ".C('DB_PREFIX')."users u ON u.id=d.agent_id")
                    ->field($field)
                    ->where($where)
                    ->select();
        
//         if(!empty($start_time)){
//             $sumwhere .= " AND date>='".$start_time."'";
//             $regwehre .= " AND login_date>='".$start_time."'";
// //             $regwehre .= " AND reg_time>='".strtotime($start_time)."'";
//         }

//         if(!empty($end_time)){
//             $sumwhere .= " AND date<='".$end_time   ."'";
//             $regwehre .= " AND login_date<='".$end_time   ."'";
// //             $regwehre .= " AND reg_time<'".(strtotime($end_time)+86400)."'";
//         }

//         $sumitems[0]['user_cnt'] = M('daypayuser')->where($regwehre)->count('distinct(mem_id)');
// //         $sumitems[0]['reg_cnt'] = M('daypayuser')->where($regwehre." AND login_date=reg_date")->count('distinct(userid)');
// //         $sumitems[0]['reg_cnt'] = M('dayagentgame')->where($regwehre." AND login_date=reg_date")->count('distinct(userid)');

//         //今日数据
//         if (($bflagstart && $bflagend) ){
// //             $field = "count(distinct(p.userid)) paycnt, sum(p.amount) summoney,
// //                       count(distinct (case  when m.reg_time>1463328000 then p.`userid` else NULL end)) regpaycnt,
// //                       sum(case  when m.reg_time>".$todaytime." then p.amount else 0 end) sumregmoney";
            
// //             $todayitem = M('pay')->alias('p')
// //                         ->field($field)
// //                         ->join("LEFT JOIN ".C('DB_PREFIX')."members m ON p.userid=m.id")
// //                         ->where("p.create_time>".$todaytime." AND p.status=1")
// //                         ->find();

// //             $todayitem['date'] = date('Y-m-d');
// //             $todayitem['user_cnt'] = M('login_log')->where("login_time>".$todaytime)->count('distinct(userid)');
// //             $todayitem['reg_cnt'] = M('members')->where("reg_time>".$todaytime)->count('id');
            
// //             $sumitems[0]['summoney'] += $todayitem['summoney'];
// //             $sumitems[0]['paycnt'] += $todayitem['paycnt'];
// //             $sumitems[0]['regpaycnt'] += $todayitem['regpaycnt'];
// //             $sumitems[0]['sumregmoney'] += $todayitem['sumregmoney'];
// //             $sumitems[0]['reg_cnt'] += $todayitem['reg_cnt'];
// //             $sumitems[0]['user_cnt'] += $todayitem['user_cnt'];
//         }
       
        $this->assign("totalpays", $sumitems);
        $this->assign("pays", $items);
//         $this->assign("todaypays", $todayitem);
        $this->assign("formget", $_GET);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->GetCurrentPage());
    }
}
