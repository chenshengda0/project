<?php
namespace Data\Controller;
use Common\Controller\AdminbaseController;
class MemberController extends AdminbaseController{
	protected $game_model,$members_model, $where,$ag_model;
	
	function _initialize() {
		parent::_initialize();
		$this->members_model = M("members");
		$this->game_model = D("Common/Game");
		$this->ag_model = M("agent_game");
	}
	
	function dataindex(){
	    $this->_getuserdata();
	    $this->display();
	}
	
	function loginindex(){
	    $this->display();
	}
	
	
	/* 玩家数据 */
	function _getuserdata(){
	    $mext_model = M('mem_ext');
        $where_ands = array("m.agent_id".$this->agentwhere);
        
        $fields = array(
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

        $count = $mext_model
        ->alias("me")
        ->join("left join " . C('DB_PREFIX') . "members m ON me.mem_id = m.id")
        ->where($where)
        ->count();

        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
        $page = $this->page($count, $rows);
        
        $field = "me.*, m.username, m.reg_time";
        
        $items = $mext_model
        ->alias("me")
        ->field($field)
        ->where($where)
        ->join("left join " . C('DB_PREFIX') . "members m ON me.mem_id = m.id")
        ->order("m.id DESC")
        ->limit($page->firstRow . ',' . $page->listRows)
        ->select();       

        $this->assign("count", $count);
        $this->assign("members", $items);
        $this->assign("formget", $_GET);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->GetCurrentPage());
	}
}