<?php
namespace Common\Model;
use Common\Model\CommonModel;
class GameModel extends CommonModel{
    protected $trueTableName = 'l_game';
    protected $dbName = 'db_league_mng';
    
	//自动验证
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('gamename', 'require', '游戏名不能为空！', 1, 'regex', CommonModel::MODEL_BOTH),
			array('appidenty', 'require', '拼音不能为空！', 1, 'regex', CommonModel::MODEL_BOTH),
			array('status', array(0,1,2,3), '值的范围不正确！', 2, 'in'),
	);
	
	protected $_auto = array(
	        array('create_time','time',CommonModel::MODEL_INSERT,'function'),
	        array('update_time','time',CommonModel::MODEL_BOTH,'function'),	    
    	    array('cid','mGetCid',CommonModel::MODEL_INSERT,'callback'),  
	);
	
	function mGetCid() {
	    return sp_get_current_cid();
	}
	
	protected function _before_insert(&$data, $options) {
		parent::_before_insert($data, $options);
		
		//获取游戏名称拼音
		import('Vendor.Pin');
		$pin = new \Pin();
		$data['appidenty'] = $pin -> pinyin($data['gamename']);
		$data['initial'] = $pin -> pinyin($data['gamename'], true);
	}
	
	protected function _after_insert($data,$options) {
	    parent::_after_insert($data, $options);
	    
	    $data['appkey'] = md5($data['id'].md5($data['appidenty']));
	    $this->save($data);
	}	
}




