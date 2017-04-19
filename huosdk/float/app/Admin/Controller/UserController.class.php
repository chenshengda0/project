<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class UserController extends AdminbaseController{
	protected $member_model;
	
	function _initialize() {
		parent::_initialize();
		$this->member_model = M('members');
	}
	
	//浮点用户信息首页
	public function index(){
		$this->redirect('Float/User/index');
	    $field = "email, mobile, username, nickname";
	    $mem_id = sp_get_current_user();
        $userdata = $this->member_model->field($field)->where(array('id'=>$mem_id))->find();
        
        if(empty($userdata['email'])){
            $strshow = "未设置";
        }else{
            $strshow = "已设置";
        }
        
        if(empty($userdata['mobile'])){
            $strshowphone = "未设置";
        }else{
            $strshowphone = "已设置";
        }

        $this->assign("users", $userdata);
		$this->assign('strshow',$strshow);
		$this->assign('strshowphone',$strshowphone);
		$this->display();
	}
	
	public function uppwd(){
	    $this->display();
	}
	
	/*
	 * 修改密码处理函数
	 */
	public function upPwdPost(){
	    $oldpwd = I('post.oldpwd');
	    $newpwd = I('post.newpwd');
	    $unewpwd = I('post.unewpwd');
	    $action = I('post.action');
	   
	    $state = 0;
		
	    if ($unewpwd != $newpwd) {
	        $this->assign('state', $state);
	        $this->assign('msg', '两次输入密码不一致!');
	        $this->display('pwdresult');
	        exit;
	    }
		
	    if ($oldpwd == $newpwd) {
	        $this->assign('state', $state);
	        $this->assign('msg', '新密码与老密码一致!');
	        $this->display('pwdresult');
	        exit;
	    }

	    if(isset($action) && $action == 'updatepwd'){
	        $data['password'] = pw_auth_code($oldpwd);
	        $mem_id = sp_get_current_user();
	        $userpwd = $this->member_model->where(array('id'=>$mem_id))->getField('password');
	        $msg = "修改密码失败!";
	        if ($data['password'] == $userpwd) {
	            $data['password'] = pw_auth_code($newpwd);
	            $data['update_time'] = time();
                
	            $rs =  $this->member_model->where(array('id'=>$mem_id))->setField($data);
	            if ($rs>0) {
	                $state = 1;
	                $msg = "修改密码成功!";
	            }
	        }else{
	            $state = 0;
	            $msg = "原来码输入错误";
	        }
	        
	        $this->assign('state', $state);
	        $this->assign('msg', $msg);
	        $this->display('pwdresult');
	    }
	}
	
	//检查原密码是否正确
	public function checkPassword(){
	    
	    $oldpwd = I('post.oldpwd');
	    $newpwd = I('post.newpwd');
	    $unewpwd = I('post.unewpwd');
	    $action = I('post.action');
	    //不能为空
	    if (empty($oldpwd)) {
	        echo json_encode(array('success'=>false,'msg'=>'原密码不能为空.'));
	        exit;
	    }
	    
	    //密码不能为空
	    if (empty($newpwd)) {
	        echo json_encode(array('success'=>false,'msg'=>'新密码不能为空.'));
	        exit;
	    }
	    if (strlen($newpwd)>16 || strlen($newpwd)<6) {
	    
	        echo json_encode(array('success'=>false,'msg'=>'密码长度不能少于5位.'));
	        exit;
	    }
	    if (empty($unewpwd)) {
	        echo json_encode(array('success'=>false,'msg'=>'二次密码不能为空.'));
	        exit;
	    }
	    if ($unewpwd != $newpwd) {
	        echo json_encode(array('success'=>false,'msg'=>'两次输入密码不同.'));
	        exit;
	    }
	    $clientkey = C('clientkey');
	    
	    $oldpwd = pw_auth_code($oldpwd);
	    
	    $mem_id = sp_get_current_user();
	    $userpwd = $this->member_model->where(array('id'=>$mem_id))->getField('password');
	    $msg = "修改密码失败!";
	    if ($oldpwd == $userpwd) {
	        echo json_encode(array('success'=>true));
	        exit;
	    }else{
	        echo json_encode(array('success'=>false,'msg'=>'原密码输入错误.'));
	        exit;
	    }
	}
}