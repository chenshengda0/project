<?php
class AdvAction extends AuthAction{
	
	function _after_index($data){
		foreach ($data as $k => $v){
			$data[$k]['typename'] = $GLOBALS['db']->getOne("SELECT name FROM ".DB_PREFIX."advtype WHERE id = ".$v['type']);
		}
		return $data;
	}
	
	function _befor_add(){
		$types = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."advtype");
		$this->assign("types",$types);
	}
	function _befor_edit($data){
		$types = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."advtype");
		$this->assign("types",$types);
		return $data;
	}
}