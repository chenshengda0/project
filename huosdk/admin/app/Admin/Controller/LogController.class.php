<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class LogController extends AdminbaseController{
	function _initialize() {
		parent::_initialize();
	}
	
	public function adminloginindex(){
	    $this->_loginList();
	    $this->display();
	}
	
	function _loginList(){
	    $where_ands = array();
	    $fields = array(	            
	            'userid' => array(
	                    "field" => "u.id",
	                    "operator" => "="
	            ),
	            'username' => array(
	                    "field" => "u.user_login",
	                    "operator" => "="
	            ),
	            'start_time' => array(
	                    "field" => "a.login_time",
	                    "operator" => ">"
	            ),
	            'end_time' => array(
	                    "field" => "a.login_time",
	                    "operator" => "<"
	            )
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
	                }else if ('end_time' == $param){
	                    $get = strtotime($get,'23:59:59');
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
	                }else if ('end_time' == $param){
	                    $get = strtotime($get,'23:59:59');
	                }
	    
	                if ($operator == "like") {
	                    $get = "%$get%";
	                }
	                array_push($where_ands, "$field $operator '$get'");
	            }
	        }
	    }
	    
	    array_push($where_ands);
	    $where = join(" AND ", $where_ands);
	    $count = M('admin_login_log')
	    ->alias('a')
	    ->join("LEFT JOIN ".C('DB_PREFIX')."users u ON u.id=a.user_id" )
	    ->where($where)
	    ->count();

	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
	    $page = $this->page($count, $rows);
	    
	    $field = "a.id, a.user_id, u.user_login, a.login_time, a.ip, a.addr";
	    $items = M('admin_login_log')
	    ->field($field)
	    ->alias('a')
	    ->join("LEFT JOIN ".C('DB_PREFIX')."users u ON u.id=a.user_id" )
	    ->where($where)
		->limit($page->firstRow . ',' . $page->listRows)
	    ->group('a.id DESC')
	    ->select();

	    $this->assign("logs", $items);
	    $this->assign("formget", $_GET);
	    $this->assign("Page", $page->show('Admin'));
	    $this->assign("current_page", $page->GetCurrentPage());
	}
	
	public function actionindex(){
	    $this->_aList();
	    $this->display();
	}
	
	function _aList(){
	    $where_ands = array();
	    $fields = array(	            
	            'userid' => array(
	                    "field" => "aol.user_id",
	                    "operator" => "="
	            ),
	            'username' => array(
	                    "field" => "aol.username",
	                    "operator" => "="
	            ),
	            'start_time' => array(
	                    "field" => "aol.create_time",
	                    "operator" => ">"
	            ),
	            'end_time' => array(
	                    "field" => "aol.create_time",
	                    "operator" => "<"
	            )
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
	                }else if ('end_time' == $param){
	                    $get = strtotime($get,'23:59:59');
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
	                }else if ('end_time' == $param){
	                    $get = strtotime($get,'23:59:59');
	                }
	    
	                if ($operator == "like") {
	                    $get = "%$get%";
	                }
	                array_push($where_ands, "$field $operator '$get'");
	            }
	        }
	    }
	    
	    array_push($where_ands);
	    $where = join(" AND ", $where_ands);
	    $count = M('admin_operate_log')
	    ->alias('aol')
	    ->where($where)
	    ->count();

	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
	    $page = $this->page($count, $rows);
	    
	    $field = "aol.*";
	    $items = M('admin_operate_log')
	    ->field($field)
	    ->alias('aol')
	    ->where($where)
		->limit($page->firstRow . ',' . $page->listRows)
	    ->group('aol.id DESC')
	    ->select();

	    $this->assign("logs", $items);
	    $this->assign("formget", $_GET);
	    $this->assign("Page", $page->show('Admin'));
	    $this->assign("current_page", $page->GetCurrentPage());
	}
	
	
}