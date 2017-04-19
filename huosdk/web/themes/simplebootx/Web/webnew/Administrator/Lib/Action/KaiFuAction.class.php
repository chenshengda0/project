<?php
class KaiFuAction extends AuthAction{
	function _after_index($list){
		foreach ($list as $k => $v){
			$list[$k]['gname'] = $GLOBALS['db']->getOne("SELECT name FROM ".DB_PREFIX."game WHERE id = ".$v['game_id']);
		}
		return $list;
	} 
	
	function _befor_add(){
		$games = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."game WHERE is_del = 0 ORDER BY orderid ASC");
		$this->assign("games",$games);
	}
	function _befor_insert($data){
		if(!$data['open_time']){
			showMsg("开服时间不能为空");
		}
		$data['open_time'] = strtotime($data['open_time']);
		$data['create_time'] = time();
		return $data;
	}
	
	function _befor_update($data){
		if(!$data['open_time']){
			showMsg("开服时间不能为空");
		}
		$data['open_time'] = strtotime($data['open_time']);
		return $data;
	}
	
	function _befor_edit($data){
		$games = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."game WHERE is_del = 0 ORDER BY orderid ASC");
		$this->assign("games",$games);
		return $data;
	}
	
}