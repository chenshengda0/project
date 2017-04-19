<?php
namespace Common\Model;
use Common\Model\CommonModel;
class UsersModel extends CommonModel
{
	
	protected $_validate = array(
		//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
		array('user_login', 'require', '用户名称不能为空！', 1, 'regex', CommonModel::MODEL_INSERT  ),
		array('user_pass', 'require', '密码不能为空！', 1, 'regex', CommonModel::MODEL_INSERT ),
		array('user_login', 'require', '用户名称不能为空！', 0, 'regex', CommonModel::MODEL_UPDATE  ),
		array('user_pass', 'require', '密码不能为空！', 0, 'regex', CommonModel::MODEL_UPDATE  ),
		array('user_login','','用户名已经存在！',0,'unique',CommonModel::MODEL_BOTH ), // 验证user_login字段是否唯一
        array('user_login','checkuser','账号格式不正确！',0,'callback',CommonModel::MODEL_BOTH ), // 验证user_login字段格式是否正确
        array('user_email','','邮箱帐号已经存在！',0,'unique',CommonModel::MODEL_BOTH ), // 验证user_email字段是否唯一
		array('user_email','email','邮箱格式不正确！',0,'',CommonModel::MODEL_BOTH ), // 验证user_email字段格式是否正确
		array('qq','checkqq','QQ格式不正确！',0,'callback',CommonModel::MODEL_BOTH ), // 验证11字段格式是否正确
		array('mobile','checkmobile','手机格式不正确！',0,'callback',CommonModel::MODEL_BOTH ), // 验证mobile字段格式是否正确
		
	);
	
	protected $_auto = array(
	    array('create_time','mGetDate',CommonModel::MODEL_INSERT,'callback'),
	    array('ownerid','mOwnerid',CommonModel::MODEL_INSERT,'callback'),
	    array('user_pass','mPassword',CommonModel::MODEL_BOTH,'callback'),
	    array('pay_pwd','mPaypwd',CommonModel::MODEL_BOTH,'callback'),
	);
	
	//用于获取时间，格式为2012-02-03 12:12:12,注意,方法不能为private
	function mGetDate() {
		return date('Y-m-d H:i:s');
	}
	
	function mOwnerid() {
		return get_current_admin_id();
	}
	
	function mPassword($data) {
		return sp_password($data);
	}
	
	function mPaypwd($data) {
		return pay_password($data);
	}
	
	function checkqq($data){
	    $checkExpressions = "/^[1-9][0-9]{4,}$/";
	    if (false == preg_match($checkExpressions, $data)){
	        return false;
	    }
	    return true;
	}
	
	function checkmobile($data){
	    $checkExpressions = "/^1[34578]\d{9}$/";
	    if (false == preg_match($checkExpressions, $data)){
	        return FALSE;
	    }
	    return true;
	}
	function checkuser($data){
	    $checkExpressions = "/^[a-zA-Z]\w{3,15}$/i";
	    if (false == preg_match($checkExpressions, $data)){
	        return FALSE;
	    }
	    return true;
	}
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
		
// 		if(!empty($data['user_pass']) && strlen($data['user_pass'])<25){
// 			$data['user_pass']=sp_password($data['user_pass']);
// 		}
		
// 		if(!empty($data['pay_pwd']) && strlen($data['pay_pwd'])<25){
// 			$data['pay_pwd']=pay_password($data['pay_pwd']);
// 		}
	}
	
}

