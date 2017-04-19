<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------
/**
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class PublicController extends AdminbaseController {

    function _initialize() {}
    
    //后台登陆界面
    public function login() {
        if (isset($_SESSION['ADMIN_ID'])) { // 已经登录
            $this->admin_login_log(1);
            $this->redirect("Index/index");
           // $this->success(L('LOGIN_SUCCESS'), U("Index/index"));
        } else {
			$this->display(":login");
        }
    }
    
    public function logout(){
        $this->admin_login_log(2);
    	session('ADMIN_ID',null); 
		session(null);
    	$this->redirect("public/login");
    }
    
    public function dologin(){
    	$name = I("post.username");
    	if(empty($name)){
    		$this->error(L('USERNAME_OR_EMAIL_EMPTY'));
    	}
    	$pass = I("post.password");
    	if(empty($pass)){
    		$this->error(L('PASSWORD_REQUIRED'));
    	}
    	$verrify = I("post.verify");
    	if(empty($verrify)){
    		$this->error(L('CAPTCHA_REQUIRED'));
    	}
    	//验证码
    	if(!sp_check_verify_code()){
    		$this->error(L('CAPTCHA_NOT_RIGHT'));
    	}else{
    		$user = D("Common/Users");
    		//if(strpos($name,"@")>0){//邮箱登陆
    		//	$where['user_email']=$name;
    		//}else{
    			$where['user_login']=$name;
    		//}
    		$result = $user->where($where)->find();
    		if(!empty($result)){

    			if($result['user_pass'] == sp_password($pass)){
    				$role_user_model=M("RoleUser");
    				$role_user_join = C('DB_PREFIX').'role as b on a.role_id =b.id';
    				$role_type=$role_user_model->alias("a")->join($role_user_join)->where(array("user_id"=>$result["id"],"status"=>1))->getField("min(role_type)");
    								
    				if($result["id"]!=1 && (empty($role_type) || 2 != $result['user_status'] ) ){
    					$this->error(L('USE_DISABLED'));
    				}
				    $_SESSION['role_type'] = $role_type;
    				//登入成功页面跳转
    				$_SESSION["ADMIN_ID"]=$result["id"];
    				$_SESSION['name']=$result["user_login"];
    				$result['last_login_ip']=get_client_ip();
    				$result['last_login_time']=date("Y-m-d H:i:s");
    				$user->save($result);
    				$this->admin_login_log(0,$result['last_login_ip']);
    				
    				setcookie("admin_username",$name,time()+30*24*3600,"/");
    				$this->success(L('LOGIN_SUCCESS'),U("Index/index"));
    			}else{
    				$this->error(L('PASSWORD_NOT_RIGHT'));
    			}
    		}else{
    			$this->error(L('USERNAME_NOT_EXIST'));
    		}
    	}
    }
}