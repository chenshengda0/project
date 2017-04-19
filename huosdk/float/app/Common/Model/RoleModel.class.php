<?php
namespace Common\Model;
use Common\Model\CommonModel;
class RoleModel extends CommonModel{	
    protected $trueTableName = 'l_clientrole';
    protected $dbName = 'db_league_auth';
    
	//自动验证
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('name', 'require', '角色名称不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
			array('typeid', array(2,3,4), '类型值范围不正确！', 1, 'in', CommonModel:: MODEL_BOTH ),
	);
	
	protected $_auto = array(
			array('create_time','time',1,'function'),
			array('update_time','time',3,'function'),
			array('cid','sp_get_current_cid',3,'function'),
	);
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}
}