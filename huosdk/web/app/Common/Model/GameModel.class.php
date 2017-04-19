<?php
namespace Common\Model;
use Common\Model\CommonModel;
class GameModel extends CommonModel{
	//自动验证
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('name', 'require', '游戏名不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
			array('pinyin', 'require', '拼音不能为空！', 1, 'unique', CommonModel:: MODEL_BOTH ),
			array('client_status', array(0,1,2,3), '值的范围不正确！', 2, 'in'),
	);
	
// 	protected $_auto = array (
// 			array('createtime','mDate',1,'callback'), // 对msg字段在新增的时候回调htmlspecialchars方法
// 	);
	
// 	function mDate(){
// 		return date("Y-m-d H:i:s");
// 	}
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}
	
}




