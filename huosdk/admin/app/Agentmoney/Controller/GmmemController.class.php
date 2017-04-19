<?php
/**
* GmmemController.class.php UTF-8
* 玩家游戏币
* @date: 2016年6月17日下午1:13:34
* @license 这不是一个自由软件，未经授权不许任何使用和传播。
* @author: wuyonghong <wyh@1tsdk.com>
* @version: 1.0
* 
*/

namespace Agentmoney\Controller;
use Common\Controller\AdminbaseController;

class GmmemController extends GmController {
    protected $gmm_model,$gmc_model, $gmp_model;
    
    function _initialize() {
        parent::_initialize();
    }
    
    //玩家游戏币余额
    public function index(){
        $this->_game();
        $this->_gamemoney();
        $this->_mem_Gm();
        $this->display();
    }
    
    //玩家充值记录
    public function charge(){
        $this->_charge_flag();
        $this->_pay_status();
        $this->_game();
        $this->_gamemoney();
        $this->_mem_charge();
        $this->display();
    }
    
    //玩家消费记录
    public function pay(){
        $this->_charge_flag();
        $this->_pay_status();
        $this->_game();
        $this->_gamemoney();
        $this->_mem_pay();
        $this->display();
    }
    
    function _mem_Gm(){
        $this->gmm_model = M('gm_mem');
        $where_ands = array("m.agent_id".$this->agentwhere);

        $fields = array(
                'app_id' => array(
                        "field" => "gmm.app_id",
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
        
        $count = $this->gmm_model
        ->alias("gmm")
        ->join("right join " . C('DB_PREFIX') . "members m ON gmm.mem_id = m.id")
        ->where($where)
        ->count();
        
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
        $page = $this->page($count, $rows);
        
        $field = "gmm.*, m.username, m.reg_time,g.name gamename, IFNULL(gmm.mem_id, m.id) mem_id";
        
        $items = $this->gmm_model
        ->alias("gmm")
        ->field($field)
        ->join("right join " . C('DB_PREFIX') . "members m ON gmm.mem_id = m.id")
        ->join("left join " . C('DB_PREFIX') . "game g ON gmm.app_id = g.id")
        ->where($where)
        ->order("gmm.mem_id DESC")
        ->limit($page->firstRow . ',' . $page->listRows)
        ->select();
        
        $sumfield = "sum(sum_money) sum_money, sum(total) total, sum(remain) remain";
        $sums = $this->gmm_model
        ->field($sumfield)
        ->alias("gmm")
        ->join("right join " . C('DB_PREFIX') . "members m ON gmm.mem_id = m.id")
        ->where($where)
        ->find();
        
        $this->assign("sumlist", $sums);
        $this->assign("items", $items);
        $this->assign("formget", $_GET);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->GetCurrentPage());
    }
    
    function _mem_charge(){
        $this->gmc_model = M('gm_charge');
        $where_ands = array("m.agent_id".$this->agentwhere);
        array_push($where_ands, "u.id".$this->agentwhere);
        
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
                'username' => array(
                        "field" => "m.username",
                        "operator" => "="
                ),
                'flag' => array(
                        "field" => "gmc.flag",
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
        
        $count = $this->gmc_model
        ->alias("gmc")
        ->join("left join " . C('DB_PREFIX') . "members m ON gmc.mem_id = m.id")
        ->join("left join " . C('DB_PREFIX') . "users u ON gmc.admin_id = u.id")
        ->where($where)
        ->count();
        
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
        $page = $this->page($count, $rows);
        
        $field = "gmc.*, m.username, m.reg_time, u.user_login adminname";
        
        $items = $this->gmc_model
        ->alias("gmc")
        ->field($field)
        ->join("left join " . C('DB_PREFIX') . "members m ON gmc.mem_id = m.id")
        ->join("left join " . C('DB_PREFIX') . "users u ON gmc.admin_id = u.id")
        ->where($where)
        ->order("gmc.id DESC")
        ->limit($page->firstRow . ',' . $page->listRows)
        ->select();
        
        $sumfield = "sum(money) sum_money, sum(gm_cnt) gm_cnt";
        $sums = $this->gmc_model
        ->field($sumfield)
        ->alias("gmc")
        ->join("left join " . C('DB_PREFIX') . "members m ON gmc.mem_id = m.id")
        ->join("left join " . C('DB_PREFIX') . "users u ON gmc.admin_id = u.id")
        ->where($where)
        ->find();
        
        $this->assign("sumlist", $sums);
        $this->assign("items", $items);
        $this->assign("formget", $_GET);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->GetCurrentPage());
    }
    

    function _mem_pay(){
        $this->gmp_model = M('gm_pay');
        $where_ands = array("m.agent_id".$this->agentwhere);
        
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
                'username' => array(
                        "field" => "m.username",
                        "operator" => "="
                ),
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
        
        $count = $this->gmp_model
        ->alias("gmp")
        ->join("left join " . C('DB_PREFIX') . "members m ON gmp.mem_id = m.id")
        ->where($where)
        ->count();
        
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
        $page = $this->page($count, $rows);
        
        $field = "gmp.*, m.username, m.reg_time";
        
        $items = $this->gmp_model
        ->alias("gmp")
        ->field($field)
        ->join("left join " . C('DB_PREFIX') . "members m ON gmp.mem_id = m.id")
        ->where($where)
        ->order("gmp.id DESC")
        ->limit($page->firstRow . ',' . $page->listRows)
        ->select();
        
        $sumfield = "sum(amount) sum_money, sum(gm_cnt) gm_cnt";
        $sums = $this->gmp_model
        ->field($sumfield)
        ->alias("gmp")
        ->join("left join " . C('DB_PREFIX') . "members m ON gmp.mem_id = m.id")
        ->where($where)
        ->find();
        
        $this->assign("sumlist", $sums);
        $this->assign("items", $items);
        $this->assign("formget", $_GET);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->GetCurrentPage());
    }
    
}