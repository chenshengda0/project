<?php
namespace Common\Model;
use Common\Model\CommonModel;
class UsersModel extends CommonModel{
    protected $trueTableName = 'l_clientusers';
    protected $dbName = 'db_league_auth';
    
	protected $_validate = array(
		//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
		array('user_login', 'require', '用户名称不能为空！', 1, 'unique', CommonModel:: MODEL_INSERT  ),
		array('user_pass', 'require', '密码不能为空！', 1, 'regex', CommonModel:: MODEL_INSERT ),
		array('user_login', 'require', '用户名称不能为空！', 0, 'unique', CommonModel:: MODEL_UPDATE  ),
		array('user_pass', 'require', '密码不能为空！', 0, 'regex', CommonModel:: MODEL_UPDATE  ),
		array('user_login','','用户名已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证user_login字段是否唯一
		array('user_email','','邮箱帐号已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证user_email字段是否唯一
		array('user_email','email','邮箱格式不正确！',0,'',CommonModel:: MODEL_BOTH ), // 验证user_email字段格式是否正确
	);
	
	protected $_auto = array(
	    array('create_time','mGetDate',CommonModel:: MODEL_INSERT,'callback'),
	    array('parentid','mGetPid',CommonModel:: MODEL_INSERT,'callback'),
	    array('cid','mGetCid',CommonModel:: MODEL_INSERT,'callback'),
	);
	
	//用于获取时间，格式为2012-02-03 12:12:12,注意,方法不能为private
	function mGetDate() {
		return date('Y-m-d H:i:s');
	}
	
	function mGetPid() {
	    return get_current_admin_id();	    
	}
	
	function mGetCid() {
	    return sp_get_current_cid();
	}
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
		
		if(!empty($data['user_pass']) && strlen($data['user_pass'])<25){
			$data['user_pass']=sp_password($data['user_pass']);
			$data['pay_pwd']=sp_password($data['pay_pwd']);
		}
	}
	
}

