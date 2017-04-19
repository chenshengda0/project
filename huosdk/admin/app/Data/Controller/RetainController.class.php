<?php
namespace Data\Controller;
use Common\Controller\AdminbaseController;
class RetainController extends AdminbaseController{
	protected  $where, $daymodel;
	
	function _initialize() {
		parent::_initialize();
	}
	
	function index(){
	    if (2 < $this->role_type){
	        $this->_getAgentReten();
	        $this->daymodel = M('day_agent');
	        $this->where = "agent_id".$this->agentwhere;
	    }else{
	        $this->daymodel = M('day_pay');
	        $this->where = "1";
	        $this->_getReten();
	    }
	    
	    $this->display();
	}
	
	function game(){
	    if (2 < $this->role_type){
	        $this->daymodel = M('day_agentgame');
	        $this->_getGamereten();
	        $this->where = "agent_id".$this->agentwhere;
	    }else{
	        $this->daymodel = M('day_game');
	        $this->where = "1";
	        $this->_getGame();
	    }
	    $this->_game();
	    $this->display();
	}
	
	function agentgame(){
	    $this->daymodel = M('day_agentgame');
	    if (2 < $this->role_type){
	        $this->where = "agent_id".$this->agentwhere;
	    }else{
	        $this->where = "1 ";
	    }
	    $this->_getAgentgame();
	    $this->_agents();
	    $this->_game();
	    $this->display();
	}
	
	function agent(){
	    $this->_agents();
	    if (2 < $this->role_type){
	        $this->where = "agent_id".$this->agentwhere;
	    }else{
	        $this->where = " 1 " ;
	    }
	    $this->_getAgent();
	    $this->display();
	}
	
	/* 总体留存 */
	function _getReten(){
	    $start = I('start_time');
	    $end = I('end_time');
	    $where = '1';
	    
	    if (isset($start) && !empty($start)) {
	        $where .= " AND date >= '".$start."'";
	    }
	
	    if (isset($end) && !empty($end)) {
            $where .= " AND date <= '".$end."'";
	    }
	    
        $count =  $this->daymodel->where($where)->count();
	
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
	    $page = $this->page($count, $rows);
	    
	    $items = $this->daymodel
	    ->where($where)
	    ->order('id DESC')
	    ->limit($page->firstRow . ',' . $page->listRows)
	    ->select();

	    $this->assign("pays", $items);
	    $this->assign("start_time", $start);
	    $this->assign("end_time", $end);
	    $this->assign("Page", $page->show('Admin'));
	    $this->assign("current_page", $page->GetCurrentPage());
	}
	
	function _getAgentReten(){
	    $this->daymodel = M('day_agent');
	    $start = I('start_time');
	    $end = I('end_time');
	    $where = $this->where;
	    
	    if (isset($start) && !empty($start)) {
	        $where .= " AND date >= '".$start."'";
	    }
	
	    if (isset($end) && !empty($end)) {
            $where .= " AND date <= '".$end."'";
	    }
	    
	    
        $count =  $this->daymodel->where($where)->group('date')->count();
	
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
	    $page = $this->page($count, $rows);
	    
	    $field = "date, sum(reg_cnt) reg_cnt,sum(day2) day2 ,sum(day3) day3,sum(day4) day4";
	    $field .= " ,sum(day5) day5 ,sum(day6) day6,sum(day7) day7 ,sum(day15) day15 ,sum(day30) day30";
	    $items = $this->daymodel
	    ->where($where)
	    ->order('id DESC')
        ->group('date')
	    ->limit($page->firstRow . ',' . $page->listRows)
	    ->select();

	    $this->assign("pays", $items);
	    $this->assign("start_time", $start);
	    $this->assign("end_time", $end);
	    $this->assign("Page", $page->show('Admin'));
	    $this->assign("current_page", $page->GetCurrentPage());
	}
	
	function _getGame(){
	    $start = I('start_time');
	    $end = I('end_time');
	    $app_id = I('app_id/0',0);
	    $where = '1';
	     
	    if (isset($start) && !empty($start)) {
	        $where .= " AND date >= '".$start."'";
	    }
	
	    if (isset($end) && !empty($end)) {
	        $where .= " AND date <= '".$end."'";
	    }
	     
	    if (isset($app_id) && !empty($app_id)) {
	        $where .= " AND app_id=".$app_id;
	    }
	    
	    $count =  $this->daymodel->where($where)->count();
	
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
	    $page = $this->page($count, $rows);
	     
	    $items = $this->daymodel
	    ->where($where)
	    ->order('id DESC')
	    ->limit($page->firstRow . ',' . $page->listRows)
	    ->select();
	
	    $this->assign("pays", $items);
	    $this->assign("app_id", $app_id);
	    $this->assign("start_time", $start);
	    $this->assign("end_time", $end);
	    $this->assign("Page", $page->show('Admin'));
	    $this->assign("current_page", $page->GetCurrentPage());
	}
	
	function _getGamereten(){
	    $start = I('start_time');
	    $end = I('end_time');
	    $app_id = I('app_id/0',0);
	    $where = $this->where;
	     
	    if (isset($start) && !empty($start)) {
	        $where .= " AND date >= '".$start."'";
	    }
	
	    if (isset($end) && !empty($end)) {
	        $where .= " AND date <= '".$end."'";
	    }
	    
	    if (isset($app_id) && !empty($app_id)) {
	        $where .= " AND app_id=".$app_id;
	    }
	     
	    $count =  $this->daymodel->where($where)->group('date,app_id')->count();
	
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
	    $page = $this->page($count, $rows);
	     
	    $field = "date, sum(reg_cnt) reg_cnt,sum(day2) day2 ,sum(day3) day3,sum(day4) day4";
	    $field .= " ,sum(day5) day5 ,sum(day6) day6,sum(day7) day7 ,sum(day15) day15 ,sum(day30) day30";
	    $items = $this->daymodel
	    ->where($where)
	    ->order('id DESC')
	    ->group('date,app_id')
	    ->limit($page->firstRow . ',' . $page->listRows)
	    ->select();
	
	    $this->assign("pays", $items);
	    $this->assign("start_time", $start);
	    $this->assign("end_time", $end);
	    $this->assign("app_id", $app_id);
	    $this->assign("Page", $page->show('Admin'));
	    $this->assign("current_page", $page->GetCurrentPage());
	}
	
	/* 渠道留存详细 */
	function _getAgent(){
	    $this->daymodel = M('day_agent');
	    $start = I('start_time');
	    $end = I('end_time');
	    $agent_id = I('agent_id/d',0);
	    $where = $this->where;
	    
	    if (isset($start) && !empty($start)) {
	        $where .= " AND date >= '".$start."'";
	    }
	
	    if (isset($end) && !empty($end)) {
            $where .= " AND date <= '".$end."'";
	    }
	    
	    if (isset($agent_id) && !empty($agent_id)) {
	        $where .= " AND agent_id=".$agent_id;
	    }
	    
        $count =  $this->daymodel->where($where)->count();
	
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
	    $page = $this->page($count, $rows);
	    
	    $field = "date, reg_cnt, day2,day3,day4,day5,day6,day7,day15,day30";
	    $items = $this->daymodel
	    ->where($where)
	    ->order('id DESC')
	    ->limit($page->firstRow . ',' . $page->listRows)
	    ->select();

	    $this->assign("pays", $items);
	    $this->assign("start_time", $start);
	    $this->assign("end_time", $end);
	    $this->assign("Page", $page->show('Admin'));
	    $this->assign("current_page", $page->GetCurrentPage());
	}
	
	
	function _getAgentgame(){
	    $start = I('start_time');
	    $end = I('end_time');
	    $app_id = I('app_id/0',0);
	    $agent_id = I('agent_id/0',0);
	    $where = $this->where;
	
	    if (isset($start) && !empty($start)) {
	        $where .= " AND date >= '".$start."'";
	    }
	
	    if (isset($end) && !empty($end)) {
	        $where .= " AND date <= '".$end."'";
	    }
	     
	    if (isset($app_id) && !empty($app_id)) {
	        $where .= " AND app_id=".$app_id;
	    }
	     
	    if (isset($agent_id) && !empty($agent_id)) {
	        $where .= " AND agent_id=".$agent_id;
	    }
	     
	    $count =  $this->daymodel->where($where)->count();
	
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
	    $page = $this->page($count, $rows);
	
	    $items = $this->daymodel
	    ->where($where)
	    ->order('id DESC')
	    ->limit($page->firstRow . ',' . $page->listRows)
	    ->select();
	
	    $this->assign("pays", $items);
	    $this->assign("start_time", $start);
	    $this->assign("end_time", $end);
	    $this->assign("app_id", $app_id);
	    $this->assign("agent_id", $agent_id);
	    $this->assign("Page", $page->show('Admin'));
	    $this->assign("current_page", $page->GetCurrentPage());
	}
}