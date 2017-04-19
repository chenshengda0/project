<?php
class PushAction extends AuthAction{
	
	function _after_index($list){
		$types = $GLOBALS['db']->getAll("SELECT id,name,module FROM ".DB_PREFIX."pushtype ORDER BY sort ASC");
		$this->assign("types",$types);
		
		$module = array();
		foreach ($types as $val){
			$module[$val['id']] = $val['module'];
		}
		foreach ($list as $k => $v){
			$table = $module[$v['typeid']];
			if($table == 'news'){
				$name = $GLOBALS['db']->getOne("SELECT title FROM ".DB_PREFIX.$table." WHERE id = ".$v['itemid']);
			}else{
				$name = $GLOBALS['db']->getOne("SELECT name FROM ".DB_PREFIX.$table." WHERE id = ".$v['itemid']);
			}
			$list[$k]['name'] = $name;
			$list[$k]['module'] = $table;
		}
		return $list;
	}
	
	function ajax_add(){
		if($_POST){
			$ids = trim(new_addslashes($_POST['ids']));
			$type = intval($_POST['type']);
			if($ids && $type > 0){
				$ids = explode(',',$ids);
				foreach ($ids as $v){
					if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."push WHERE itemid = ".$v." AND typeid = ".$type) == 0){
						$push = array(
								"typeid" => $type,
								"itemid" => $v,
								"sort" => 0,
								"create_time" => time()
						);
						$GLOBALS['db']->autoExecute(DB_PREFIX."push",$push,"INSERT");
					}
				}
				showMsgajax("推送成功",1);
			}else{
				showMsgajax("参数错误",-1);
			}
		}else{
			showMsgajax("参数错误",-1);
		}
	}
	
	function _befor_add(){
		$types = $GLOBALS['db']->getAll("SELECT id,name,module FROM ".DB_PREFIX."pushtype ORDER BY sort ASC");
		$this->assign("types",$types);
	}
	function _befor_insert($data){
		$data['create_time'] = time();
		return $data;
	}
	function _befor_edit($detail){
		$types = $GLOBALS['db']->getAll("SELECT id,name,module FROM ".DB_PREFIX."pushtype ORDER BY sort ASC");
		$this->assign("types",$types);
		
		$module = array();
		foreach ($types as $val){
			$module[$val['id']] = $val['module'];
		}
		$table = $module[$detail['typeid']];
		if($table == 'news'){
			$name = $GLOBALS['db']->getOne("SELECT title FROM ".DB_PREFIX.$table." WHERE id = ".$detail['itemid']);
		}else{
			$name = $GLOBALS['db']->getOne("SELECT name FROM ".DB_PREFIX.$table." WHERE id = ".$detail['itemid']);
		}
		$detail['item_name'] = $name;
		return $detail;
	}
	
	function push_sreach(){
		$keyword = trim(new_addslashes($_POST['keyword']));
		$typeid = intval(new_addslashes($_POST['typeid']));
		$itemids = $GLOBALS['db']->getCol("SELECT itemid FROM ".DB_PREFIX."push WHERE typeid = $typeid");
		$module = $GLOBALS['db']->getOne("SELECT module FROM ".DB_PREFIX."pushtype WHERE id = $typeid");
		if($itemids){
			$where = " AND id NOT IN (".join(',',$itemids).") ";
		}
		if($module == "news"){
			$list = $GLOBALS['db']->getAll("SELECT id,title as name FROM ".DB_PREFIX."news n WHERE is_del = 0 $where AND title like '%$keyword%' ORDER BY add_time DESC LIMIT 20");
		}else{
			$list = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX.$module." n WHERE is_del = 0 $where AND name like '%$keyword%' ORDER BY add_time DESC LIMIT 20");
		}
		if($list){
			echo json_encode($list);exit;
		}else{
			echo '';exit;
		}
	}
}