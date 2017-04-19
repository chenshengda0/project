<?php

/**
 * 充值统计页面
 * 
 * @author
 *
 */
namespace Data\Controller;
use Common\Controller\AdminbaseController;
class PayController extends AdminbaseController {
    
    protected $daypaymodel,$where,$orderwhere, $roleid,$pay_model;
    
    function _initialize() {
        parent::_initialize();
        if (2 < $this->role_type){
            $this->daypaymodel = M('day_agent');
            $this->where = "agent_id".$this->agentwhere;
        } else {
            $this->daypaymodel = M('day_pay');
            $this->where = '1';
        }
        $this->pay_model = M('pay');
    }
    
    public function index() {
        $this->_game();
        $this->_getpaydata();
        $this->display();
    }
    
    public function _payindex() {
        $this->display();
    }
    
    public function orderindex() {
         $this->_game();
//          $this->_agents();
         $this->_payway();
         $this->_cpstatus();
         $this->_paystatus();
         $this->_orderList();
        $this->display();
    }
    
    public function gameindex() {
        $this->_game();
		$this->_getgamedata();
        $this->display();
    } 
    
    public function getPayname($payid = 1) {
        $pwmodel = M('payway');
        $where['id'] = $payid;
        $result = $pwmodel->getFieldById($payid, 'payname');
        return $result[0]['payname'];
    }
    
    /*
     * 充值记录详细
     */
    function _getpaydata() {
        $paymodel = $this->daypaymodel;
        $where_ands = array($this->where);
        $startflag = true;
        $endflag = true;
        
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
            $startflag = strtotime($start_time) <= $todaytime? true : false;
        }
        
        if (isset($end_time) && !empty($end_time)){
            array_push($where_ands, "`date` <= '".$end_time."'");
            $endflag = strtotime($end_time) >= $todaytime? true : false;
        }
        $where = join(" AND ", $where_ands);

        $count = $this->daypaymodel
        ->where($where)
        ->count();
        
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
        $page = $this->page($count, $rows);
        
        $field = "`date`,sum(`user_cnt`) `user_cnt`,sum(`sum_money`) `sum_money`,
                sum(`pay_user_cnt`) `pay_user_cnt`, sum( `order_cnt`) `order_cnt`,
                sum(`reg_pay_cnt`) `reg_pay_cnt`,sum(`sum_reg_money`) `sum_reg_money`,
                sum(`reg_cnt`) `reg_cnt`";
        
        $items = $this->daypaymodel
        ->field($field)
        ->where($where)
        ->group('date')
        ->order("date DESC")        
        ->limit($page->firstRow . ',' . $page->listRows)
        ->select();
        
        $sumitems = $this->daypaymodel
        ->field($field)
        ->where($where)
        ->select();
        
        $sumwhere = $this->where;
        if(!empty($start_time)){
            $sumwhere .= " AND date>='".$start_time."'";
        }
        
        if(!empty($end_time)){
            $sumwhere .= " AND date<='".$end_time   ."'";
        }
        
        $sumitems[0]['user_cnt'] = M('day_user')->where($sumwhere)->count('distinct(mem_id)');

        //今日数据
        if (($startflag && $endflag) ){
            $field = "count(distinct(p.mem_id)) pay_user_cnt, sum(p.amount) sum_money, count(p.id) order_cnt,
                      count(distinct (case  when m.reg_time>".$todaytime." then p.`mem_id` end)) reg_pay_cnt,
                      sum(case  when m.reg_time>".$todaytime." then p.amount end) sum_reg_money";
            
            $todayitem = M('pay')->alias('p')
                        ->field($field)
                        ->join("LEFT JOIN ".C('DB_PREFIX')."members m ON p.mem_id=m.id")
                        ->where("p.create_time>".$todaytime." AND p.status=2 AND m.agent_id".$this->agentwhere)
                        ->find();

            $todayitem['date'] = date('Y-m-d');
            $todayitem['user_cnt'] = M('login_log')->where("login_time>".$todaytime." AND agent_id".$this->agentwhere)->count('distinct(mem_id)');
            $todayitem['reg_cnt'] = M('members')->where("reg_time>".$todaytime." AND agent_id".$this->agentwhere)->count('id');
            
            $sumitems[0]['sum_money'] += $todayitem['sum_money'];
            $sumitems[0]['pay_user_cnt'] += $todayitem['pay_user_cnt'];
            $sumitems[0]['reg_pay_cnt'] += $todayitem['reg_pay_cnt'];
            $sumitems[0]['sum_reg_money'] += $todayitem['sum_reg_money'];
            $sumitems[0]['reg_cnt'] += $todayitem['reg_cnt'];
            $sumitems[0]['order_cnt'] += $todayitem['order_cnt'];
            $sumitems[0]['user_cnt'] += $todayitem['user_cnt'];
        }
        
        $this->assign("totalpays", $sumitems);
        $this->assign("pays", $items);
        $this->assign("todaypays", $todayitem);
        $this->assign("formget", $_GET);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->Current_page);
    }
    
    function _orderList() {
        $where_ands = array("p.agent_id".$this->agentwhere);
		array_push($where_ands, "p.payway != '0'");
        
        $fields = array(
                'start_time' => array(
                        "field" => "p.create_time", 
                        "operator" => ">"
                ), 
                'end_time' => array(
                        "field" => "p.create_time", 
                        "operator" => "<" 
                ), 
                'orderid' => array(
                        "field" => "p.order_id", 
                        "operator" => "=" 
                ), 
                'gid' => array(
                        "field" => "p.app_id", 
                        "operator" => "=" 
                ), 
                'username' => array(
                        "field" => "m.username", 
                        "operator" => "=" 
                ), 
                'payway' => array(
                        "field" => "p.payway", 
                        "operator" => "=" 
                ), 
                'paystatus' => array(
                        "field" => "p.status", 
                        "operator" => "=" 
                ),
                'cpstatus' => array(
                        "field" => "p.cpstatus", 
                        "operator" => "=" 
                ),
                'agentname' => array(
                        "field" => "u.user_login",
                        "operator" => "="
                ),
                'agentnickname' => array(
                        "field" => "u.user_nicename",
                        "operator" => "="
                )
        );

        if ('七日' == $_POST['submit']) {
            $_POST['start_time'] = date("Y-m-d",strtotime("-6 day"));
            $_POST['end_time'] = date("Y-m-d",time());
        }elseif ('本月' == $_POST['submit']) {    
            $_POST['start_time'] = date("Y-m-01");
            $_POST['end_time'] = date("Y-m-d",time());
        }
        
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
                    } else if ('cpstatus' == $param){
                        $get = intval($get);
                        if (2 == $get){
                            array_push($where_ands, "$field $operator '$get'");
                            array_push($where_ands, "p.status = 2");
                        }else if (1 == $get){
                            array_push($where_ands, "$field $operator '$get'");
                            array_push($where_ands, "p.status = 1");
                        }else{
                            array_push($where_ands, "p.cpstatus != 2");
                            array_push($where_ands, "p.status = 2");
                        }
                        continue;
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
                    }else if ('cpstatus' == $param){
                        $get = intval($get);
                        if (2 == $get){
                            array_push($where_ands, "$field $operator '$get'");
                            array_push($where_ands, "p.status = 2");
                        }else if (1 == $get){
                            array_push($where_ands, "$field $operator '$get'");
                            array_push($where_ands, "p.status = 1");
                        }else{
                            array_push($where_ands, "p.cpstatus != 2");
                            array_push($where_ands, "p.status = 2");
                        }
                        continue;
                    }
                    
                    if ($operator == "like") {
                        $get = "%$get%";
                    }                    
                    array_push($where_ands, "$field $operator '$get'");
                }
            }  
        }

        $where = join(" and ", $where_ands); 

        $count = $this->pay_model
        ->alias("p")
        ->join("left join " . C('DB_PREFIX') . "users u ON p.agent_id = u.id")
        ->join("left join " . C('DB_PREFIX') . "members m ON p.mem_id = m.id")
        ->where($where)
        ->count();

        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
        $page = $this->page($count, $rows);
        
        $field = "p.order_id, p.amount, m.username,p.agent_id, p.payway, u.user_login agentname, u.user_nicename agentnickname, g.name gamename,p.status,p.cpstatus, p.create_time, p.app_id";
        
        $items = $this->pay_model
        ->alias("p")
        ->field($field)
        ->where($where)
        ->join("left join " . C('DB_PREFIX') . "game g ON p.app_id = g.id")
        ->join("left join " . C('DB_PREFIX') . "users u ON p.agent_id = u.id")
        ->join("left join " . C('DB_PREFIX') . "members m ON p.mem_id = m.id")
        ->order("p.id DESC")
        ->limit($page->firstRow . ',' . $page->listRows)
        ->select();       

        $sums = $this->pay_model
        ->alias("p")
        ->where($where)
        ->join("left join " . C('DB_PREFIX') . "game g ON p.app_id = g.id")
        ->join("left join " . C('DB_PREFIX') . "users u ON p.agent_id = u.id")
        ->join("left join " . C('DB_PREFIX') . "members m ON p.mem_id = m.id")
        ->sum('amount'); 

        $this->assign("sums", $sums);
        $this->assign("orders", $items);
        $this->assign("formget", $_GET);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->GetCurrentPage());
    }
    
    function _getgamedata(){
        $paymodel = M('day_agentgame');
        $where_ands = array();
        array_push($where_ands, $this->where);
        $sumwhere = $this->where;
        $regwehre = $this->where;
        $startflag = true;
        $endflag = true;
        
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
            $startflag = strtotime($start_time) <= $todaytime? true : false;
        }
        
        if (isset($end_time) && !empty($end_time)){
            array_push($where_ands, "`date` <= '".$end_time."'");
            $endflag = strtotime($end_time) >= $todaytime? true : false;
        }
        
        if (isset($_GET['gid']) && !empty($_GET['gid'])){
            array_push($where_ands, "`appid` = '".$_GET['gid']."'");
            $sumwhere = "`appid` = '".$_GET['gid']."'";
            $regwehre = "`appid` = '".$_GET['gid']."'";
        }
        
        $where = join(" AND ", $where_ands);

        $count = $paymodel
        ->where($where)
        ->count();
        
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $page = $this->page($count, $rows);
        
        
        $field = "`date`, `app_id`, sum(`user_cnt`) user_cnt, sum(`sum_money`) summoney, sum(`pay_user_cnt`) paycnt, 
                sum(`reg_pay_cnt`), sum(`sum_reg_money`) sumregmoney, sum(`reg_cnt`) reg_cnt";
        $sumfield = "sum(`user_cnt`) `user_cnt`,sum(`sum_money`) `summoney`,sum(`pay_user_cnt`) `paycnt`,sum(`reg_pay_cnt`) `regpaycnt`,sum(`sum_reg_money`) `sumregmoney`,sum(`reg_cnt`) `reg_cnt`";
        $items = $paymodel
        ->field($field)
        ->where($where)
        ->group('date, app_id')
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
        $sumitems[0]['user_cnt'] = M('day_pay_user')->where($regwehre)->count('distinct(mem_id)');
        
        $sumitems[0]['reg_cnt'] = M('day_pay_user')->where($regwehre." AND login_date=reg_date")->count('distinct(mem_id)');

        //今日数据
        if (($startflag && $endflag) ){
//             $field = "count(distinct(p.userid)) paycnt, sum(p.amount) summoney,
//                       count(distinct (case  when m.reg_time>1463328000 then p.`userid` else NULL end)) regpaycnt,
//                       sum(case  when m.reg_time>".$todaytime." then p.amount else 0 end) sumregmoney";
            
//             $todayitem = M('pay')->alias('p')
//                         ->field($field)
//                         ->join("LEFT JOIN ".C('DB_PREFIX')."members m ON p.userid=m.id")
//                         ->where("p.create_time>".$todaytime." AND p.status=1")
//                         ->find();

//             $todayitem['date'] = date('Y-m-d');
//             $todayitem['user_cnt'] = M('logininfo')->where("login_time>".$todaytime)->count('distinct(userid)');
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
	
    function _getgameagentdata(){
        $model = M('dayagentgame');
        $start = $_GET['start_time'];
        $end = $_GET['end_time'];
        
        if ('今日' == $_GET['date_time']) {            
            $count = 1;
            $start = date("Y-m-d");
            $end  = date("Y-m-d");
        } elseif ('七日' == $_GET['date_time']) {
            $count = 7;
            $start = date("Y-m-d",strtotime("-6 day"));
            $end  = date("Y-m-d");
        } elseif ('当月' == $_GET['date_time']) {
            $count = date('d');
            $start = date("Y-m-01");
            $end  = date("Y-m-d");
        } elseif ('30天' == $_GET['date_time']) {
            $count = 30;
            $start = date("Y-m-d",strtotime("-29 day"));
            $end  = date("Y-m-d");
        }
		
		$agents = $this->_getOwnerAgents();
        $where = " agentid in ($agents) ";
        if (isset($_GET['gid']) && !empty($_GET['gid'])){
            $where .= " AND appid=".$_GET['gid'];
        }
        
		$startflag = true;
        $endflag = true;
        if (isset($start) && !empty($start)) {
            $where .= " AND to_days(date) >= to_days('{$start}')";
            $startflag = strtotime($start) < mktime(0,0,0,date("m"),date("d"),date("Y"))? true : false;
        }  
        
        if (isset($end) && !empty($end)) {
             $where .= " AND to_days(date) <=  to_days('{$end}')";
             $endflag = strtotime($end) >= mktime(0,0,0,date("m"),date("d"),date("Y"))? true : false;
        }
        $count = 1;
        
        if ($startflag){
           $count =  $model
            ->where($where)->count();
        }
        
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $page = $this->page($count, $rows);
        
        if ($startflag) {            

			$sumsql = "SELECT  SUM(`user_cnt`) `user_cnt`,SUM(`summoney`) `summoney`,SUM(`paycnt`) `paycnt`,SUM(`regpaycnt`) `regpaycnt`,SUM(`sumregmoney`) `sumregmoney`,SUM(`reg_cnt`) `reg_cnt` 
					FROM (SELECT appid,agentid,date,user_cnt,summoney,paycnt,regpaycnt,sumregmoney,reg_cnt FROM   " . C('DB_PREFIX') . "dayagentgame
					UNION ALL
					SELECT appid,agentid,date,user_cnt,summoney,paycnt,regpaycnt,sumregmoney,reg_cnt  FROM  " . C('DB_PREFIX') . "tagentgamepayview) a"
					." where ".$where." limit ".$page->firstRow . ',' . $page->listRows;
			$sumitems = $model->query($sumsql);
			
			$sql = "SELECT  `date`, `appid`, SUM(`user_cnt`) `user_cnt`, SUM(`summoney`) `summoney`, SUM(`paycnt`) `paycnt`, SUM(`regpaycnt`) `regpaycnt`, SUM(`sumregmoney`) `sumregmoney`, SUM(`reg_cnt`) `reg_cnt` 
					FROM (SELECT appid,agentid, date,user_cnt,summoney,paycnt,regpaycnt,sumregmoney,reg_cnt FROM   " . C('DB_PREFIX') . "dayagentgame
					UNION ALL
					SELECT appid,agentid, date,user_cnt,summoney,paycnt,regpaycnt,sumregmoney,reg_cnt  FROM  " . C('DB_PREFIX') . "tagentgamepayview) a"
					." where ".$where." GROUP BY appid,date ORDER BY date desc limit ".$page->firstRow . ',' . $page->listRows;
			$items = $model->query($sql);
        }        
        
        $this->assign("totalpays", $sumitems);
        $this->assign("pays", $items);
        $this->assign("fromget", $_GET);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->GetCurrentPage());
    }
    
    function repairorder(){
        
        $order_id = I('get.orderid');
        if (empty($order_id)){
            $this->error("参数错误");
        }
        
        $time = time();
        
        // 1 通过订单号查询订单信息        
        $paydata = M('pay')->where(array('order_id'=>$order_id))->find();
		if (empty($paydata)){
			$this->error("参数错误");
		}
		
        $myamount = number_format($paydata['amount'],2,'.','');
        
        //2 验证是否已经充值成功并且回调失败
        if (2 != $paydata['cpstatus'] && 2 == $paydata['status']) {
            //2.1 查询CP回调地址与APPKEY
            $game_data = M('game')->where(array('id'=>$paydata['app_id']))->find();
            $cpurl = $game_data['cpurl'];
            $app_key = $game_data['app_key'];
            if (empty($cpurl)){
                $this->error('无回调地址');
            }
            
            if (empty($app_key)){
                $this->error('appkey错误');
            }
            
            $param['order_id'] = (string)$paydata['order_id'];
            $param['mem_id'] = (string)$paydata['mem_id'];
            $param['app_id'] = (string)$paydata['app_id'];
            $param['money'] = (string)$myamount;
            $param['order_status'] ='2';
            $param['paytime'] = (string)$paydata['create_time'];
            $param['attach'] = (string)$paydata['attach'];
            
            //2.2.2 拼接回调
            $signstr = "order_id=".$paydata['order_id']."&mem_id=".$paydata['mem_id']."&app_id=".$paydata['app_id'];
            $signstr .= "&money=".$myamount."&order_status=2&paytime=".$paydata['create_time']."&attach=".$paydata['attach'];
            $md5str = $signstr."&app_key=".$app_key;

            $sign = md5($md5str);
            $param['sign'] = (string)$sign;

            $cpstatus = 3;
            //2.2.3 通知CP
            $i = 0;
            while (1) {
                $cp_rs = $this->payback($cpurl, $param);
                if ($cp_rs > 0) {
                    $cpstatus = 2;
                    break;
                }else{
                    $cpstatus = 3;
                    $i ++;
                    sleep(2);
                }

                if ($i == 3) {
                    break;
                }
            }
            
            if (2 == $cpstatus){
                $rs = M('pay')->where(array('order_id'=>$order_id))->setField('cpstatus', $cpstatus);
                if ($rs){
                    $this->success("补单成功");
                    exit;
                }
            }
        }
        $this->error("补单失败");
    }
    /**
     * 执行一个 HTTP 请求
     *
     * @param string    $Url    执行请求的Url
     * @param mixed $Params 表单参数
     * @param string    $Method 请求方法 post / get
     * @return array 结果数组
     */
    
	public static function payback($url, $params){
	    $data_string = json_encode($params);	

	    $curl = curl_init();//初始化curl
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);//设置传送的参数

		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		        'Content-Type: application/json',
		        'Content-Length: ' . strlen($data_string))
		);
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//要求结果为字符串且输出到屏幕上
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);//设置等待时间


		$rs = curl_exec($curl);//运行curl
		$rs = strtoupper($rs);
		$result = 0;

		if ( $rs == 'SUCCESS') {
			$result = 1;
		} else {
			$result = 0;
		}
		curl_close($curl);//关闭curl
		
		return $result;
	}
    
    function _payway($type=NULL, $option=true){
        $cates=array(
                ""=>"全部"
        );
        $payways = M('payway')->getField("payname,realname", true);
        if($option){
            $payways=$cates + $payways;
        }
        
        $this->assign("payways",$payways);
    }
    

    
    function _paystatus(){
        $cates=array(
                "0"=>"全部",
                "1"=>"待支付",
                "2"=>"支付成功",                
                "3"=>"支付失败",
        );
        $this->assign("paystatuss",$cates);
    }
    
    function _cpstatus(){
        $cates=array(
                "0"=>"全部",
                "1"=>"待支付",
                "2"=>"回调成功",
                "3"=>"回调失败",
        );
        $this->assign("cpstatuss",$cates);
    }
    
    
}