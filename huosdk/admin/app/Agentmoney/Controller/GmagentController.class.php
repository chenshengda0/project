<?php

/**
 * 代理商管理
 * 
 * @author
 *
 */
namespace Agentmoney\Controller;
use Common\Controller\AdminbaseController;

class GmagentController extends GmController {
    protected $gma_model,$gmac_model;
    
    function _initialize() {
        parent::_initialize();
    }
    
	//代理渠道余额
    public function index(){
        $this->_game();
        $this->_gamemoney();
        $this->_agent_Gm();
        $this->display();
    }
    
	
	//代理渠道充值记录
	public function charge(){
	    $this->_pay_status();
	    $this->_game();
	    $this->_gamemoney();
	    $this->_agent_charge();
	    $this->display();
	}
	
	function _agent_Gm(){
	    $this->gma_model = M('gm_agent');
	    $where_ands = array("u.id=".get_current_admin_id());
	    $fields = array(
	            'app_id' => array(
	                    "field" => "gma.app_id",
	                    "operator" => "="
	            ),
	            'username' => array(
	                    "field" => "u.user_login",
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
	
	    $count = $this->gma_model
	    ->alias("gma")
	    ->join("left join " . C('DB_PREFIX') . "users u ON gma.agent_id = u.id")
	    ->where($where)
	    ->count();
	
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
	    $page = $this->page($count, $rows);
	
	    $field = "gma.*, u.user_login username, u.create_time, g.name gamename";
	
	    $items = $this->gma_model
	    ->alias("gma")
	    ->field($field)
	    ->join("left join " . C('DB_PREFIX') . "users u ON gma.agent_id = u.id")
		->join("left join " . C('DB_PREFIX') . "game g ON gma.app_id = g.id")
	    ->where($where)
	    ->order("gma.agent_id DESC")
	    ->limit($page->firstRow . ',' . $page->listRows)
	    ->select();
	
	    $sumfield = "sum(sum_money) sum_money, sum(total) total, sum(remain) remain";
	    $sums = $this->gma_model
	    ->field($sumfield)
	    ->alias("gma")
	    ->join("left join " . C('DB_PREFIX') . "users u ON gma.agent_id = u.id")
	    ->where($where)
	    ->find();
	
	    $this->assign("sumlist", $sums);
	    $this->assign("items", $items);
	    $this->assign("formget", $_GET);
	    $this->assign("Page", $page->show('Admin'));
	    $this->assign("current_page", $page->GetCurrentPage());
	}
	
	
	
    
	function _agent_charge(){
        $this->gmac_model = M('gm_agentcharge');
        $where_ands = array("au.id".$this->agentwhere);
        
        $fields = array(
                'start_time' => array(
                        "field" => "gmc.create_time",
                        "operator" => ">"
                ),
                'end_time' => array(
                        "field" => "gmc.create_time",
                        "operator" => "<"
                ),
                'order_id' => array(
                        "field" => "gmc.order_id",
                        "operator" => "="
                ),
                'app_id' => array(
                        "field" => "gmc.app_id",
                        "operator" => "="
                ),
                'agentname' => array(
                        "field" => "au.user_login",
                        "operator" => "="
                ),
                'adminname' => array(
                        "field" => "u.user_login",
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
        
        $count = $this->gmac_model
        ->alias("gmac")
        ->join("left join " . C('DB_PREFIX') . "users au ON gmac.agent_id = au.id")
        ->join("left join " . C('DB_PREFIX') . "users u ON gmac.admin_id = u.id")
        ->where($where)
        ->count();
        
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
        $page = $this->page($count, $rows);
        
        $field = "gmac.*,u.user_login adminname, au.user_login agentname";
        
        $items = $this->gmac_model
        ->alias("gmac")
        ->field($field)
        ->join("left join " . C('DB_PREFIX') . "users au ON gmac.agent_id = au.id")
        ->join("left join " . C('DB_PREFIX') . "users u ON gmac.admin_id = u.id")
        ->where($where)
        ->order("gmac.id DESC")
        ->limit($page->firstRow . ',' . $page->listRows)
        ->select();
        
        $sumfield = "sum(money) sum_money, sum(gm_cnt) gm_cnt";
        $sums = $this->gmac_model
        ->field($sumfield)
        ->alias("gmac")
        ->join("left join " . C('DB_PREFIX') . "users au ON gmac.agent_id = au.id")
        ->join("left join " . C('DB_PREFIX') . "users u ON gmac.admin_id = u.id")
        ->where($where)
        ->find();
        
        $this->assign("sumlist", $sums);
        $this->assign("items", $items);
        $this->assign("formget", $_GET);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->GetCurrentPage());
    }
	
}