<?php
namespace Float\Controller;
use Common\Controller\AdminbaseController;

class ForgetpwdController extends AdminbaseController{
	protected $member_model;
	
	function _initialize() {
		parent::_initialize();
		$this->member_model = M('members');
	}
	
	//浮点找回密码首页,输入找回密码的账号
	public function index(){
	    unset($_SESSION);
		$this->display();
	}
	
 	//检查用户名
	public function verifyusername(){
	    if (IS_AJAX){
	        $username = I('post.username/s');
	        $type = I('post.type');
	        if ('findpwd' == $type){
	            if (empty($username)){
	                $data = array(
	                        'status'=> '-1',
	                        'msg' => '请输入账号'
	                );
	                $this->ajaxReturn($data);
	            }
	            
	            $where['username'] = $username;
	            $id = $this->member_model->where($where)->getField('id');
	            if($id > 0){
	                $data = array(
	                        'status'=> '1',
	                        'msg' => '合法账号',
	                        'sub' => 1
	                );
	                $_SESSION['mem_id'] = $id;
	                $_SESSION['username'] = $username;
	                $this->ajaxReturn($data);
	            }else{
	                $data = array(
	                        'status'=> '-1',
	                        'msg' => '账号不存在,请重新输入！'
	                );
	                $this->ajaxReturn($data);
	            }
	        }
	    }
        
        $data = array(
                'status'=> '-1',
                'msg' => '数据非法'
        );
        $this->ajaxReturn($data);
	}
	
	//检查账户是否绑定手机
    public function bindinfo(){
        $username = I('post.username/s');
        if (empty($username) || $username!=$_SESSION['username']){
            $this->redirect('Forgetpwd/index');
        }
        
        $userdata = sp_get_user_info();
        if (empty($userdata)){
            $this->redirect('Forgetpwd/index');
        }
        
        if (empty($userdata['email']) && empty($userdata['mobile'])){
            $title = "找回密码失败";
            $helpurl = U('Help/index');
            $msg = "<a href=$helpurl  class='blue'>该账号未绑定手机,点此联系客服</a>";
            $this->ac_error($title, $msg);
            exit;
        }
        
        if (!empty($userdata['mobile'])){
            $userdata['mobile'] = preg_replace('/(^.*)\d{4}(\d{4})$/','\\1****\\2',$userdata['mobile']);
        }
        
        if (!empty($userdata['email'])){
            $str = $userdata['email'];
            $email_array = explode("@",$str);
            $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($str, 0, 3); //邮箱前缀
            $count = 0;
            $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count);
            $userdata['email'] = $prevfix . $str;
        }
        
        $this->assign('userdata', $userdata);
        $this->display();
	}
	
	/*
	 * 验证手机
	 */
	public function mobile_verify(){
	    $userdata = sp_get_user_info();
	    if (!empty($userdata['mobile'])){
	        $_SESSION['mobile_old'] = $userdata['mobile'];
	        $userdata['mobile'] = preg_replace('/(^.*)\d{4}(\d{4})$/','\\1****\\2',$userdata['mobile']);
	    }
	
	    $this->assign("title", '验证手机');
	    $this->assign('userdata', $userdata);
	    $this->display();
	}
	
	/* 发送手机验证码 */
	public function verify_mmsend(){
	    $data = sendMsg();
	    $this->ajaxReturn($data);
	}
	
	public function verifycode(){
	    $limit_time = 120;
	    $mobile = I('post.mobile');
	    $code = I('post.code');
	     
	    if (empty($mobile) && isset($_SESSION['mobile_old'])){
	        $mobile = $_SESSION['mobile_old'];
	        $setmobile = '';
	    }else{
	        $setmobile = $mobile;
	    }
	    
	    if(empty($_SESSION['sms_time']) || $_SESSION['sms_time']+$limit_time<time()) {
	        $data = array(
	                'status'=> '-1',
	                'msg' => '验证码已过期'
	        );
	        session('sms_time', null);
	        session('sms_code', null);
	        session('mobile', null);
	        $this->ajaxReturn($data);
	    }
	     
	    //判断手机号码是否有效
	    if(empty($_SESSION['mobile']) || $_SESSION['mobile'] != $mobile){
	        $data = array(
	                'status'=> '-1',
	                'msg' => '手机号错误或未填验证码'
	        );
	        $this->ajaxReturn($data);
	    }
	     
	    //验证验证码是否正确
	    if(empty($_SESSION['sms_code']) || $_SESSION['sms_code'] != $code ){
	        $data = array(
	                'status'=> '-1',
	                'msg' => '验证码错误'
	        );
	        $this->ajaxReturn($data);
	    }else{
	        $data = array(
	                'status'=> '1',
	                'msg' => '验证通过',
	                'sub' => '1'
	        );
	        session('sms_time', null);
	        session('sms_code', null);
	        session('mobile', null);
	        session('mobile_old', null);
	        $_SESSION['verify_mobile'] = 1;
	        $this->ajaxReturn($data);
	    }
	}
	
	
	//更新密码
	public function uppwd(){
	    if (1 !=$_SESSION['verify_mobile'] && 1 !=$_SESSION['verify_email']){
            $title = "找回密码失败";
            $helpurl = U('Help/index');
            $msg = "<a href=$helpurl  class='blue'>该账号未绑定手机,点此联系客服</a>";
            $this->ac_error($title, $msg);
            exit;
	    }
	    $this->assign("title", '更新密码');
	    $this->display();
	}
	
	/*
	 * 修改密码处理函数
	 */
	public function uppwd_post(){
	    if (IS_POST){
	        $action = I('post.action');
	        if ('updatepwd' == $action){
	            $newpwd = I('post.newpwd');
	            $verifypwd = I('post.verifypwd');
	             
	            //密码不能为空
	            if (empty($newpwd)) {
	                $this->error("新密码不能为空.",'',true);
	                exit;
	            }
	             
	            //确认密码不能为空
	            if (empty($newpwd)) {
	                $this->error("新密码不能为空.",'',true);
	                exit;
	            }
	             
	            //用户名必须为数字字母组合, 长度在6-16位之间
	            $checkExpressions = "/^[0-9A-Za-z-`=\\\[\];',.\/~!@#$%^&*()_+|{}:\"<>?]{6,16}$/";
	            if (false == preg_match($checkExpressions, $newpwd)){
	                $this->error("密码必须由6-16位的数字、字母、符号组成",'',true);
	                exit;
	            }
	             
	            //新密码与确认密码不一致
	            if ($newpwd != $verifypwd) {
	                $this->error("确认密码与新密码不一致",'',true);
	                exit;
	            }
	             
	            $data['password'] = pw_auth_code($newpwd);
	            $mem_id = sp_get_current_user();
	            $userpwd = $this->member_model->where(array('id'=>$mem_id))->getField('password');
	            if ($data['password'] != $userpwd) {
	                $data['update_time'] = time();
	                $rs =  $this->member_model->where(array('id'=>$mem_id))->save($data);
	                if (false != $rs) {
	                    $this->success("修改密码成功",U('Forgetpwd/pwd_success'),true);
	                    exit;
	                }
	            }else{
	                $this->success("修改密码成功",U('Forgetpwd/pwd_success'),true);
                    exit;
	            }
	            $this->error("内部错误.",'',true);
	            exit;
	        }
	    }
	    $this->error("参数错误.",'',true);
	    exit;
	}
	
	//操作成功跳转页面
	public function pwd_success(){
	    $title = "找回密码";
	    $msg = "密码修改成功";
	    $this->ac_success($title, $msg);
	}
	
	//操作成功跳转页面
	public function pwd_error(){
        $title = "找回密码失败";
        $helpurl = U('Help/index');
        $msg = "<a href=$helpurl  class='blue'>找回密码失败,点此联系客服</a>";
        $this->ac_error($title, $msg);
	}
	
	/*
	 * 验证邮箱
	 */
	public function email_verify(){
	    $userdata = sp_get_user_info();
	    if (!empty($userdata['email'])){
	        $_SESSION['email_old'] = $userdata['email'];
	        $str = $userdata['email'];
	
	        $email_array = explode("@",$str);
	        $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($str, 0, 3); //邮箱前缀
	        $count = 0;
	        $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count);
	        $userdata['email'] = $prevfix . $str;
	    }
	
	    $this->assign("title", '验证邮箱');
	    $this->assign('userdata', $userdata);
	    $this->display();
	}
	
	/* 发送邮箱验证码 */
	public function verify_emailsend(){
	    $data = sendEmail();
	    $this->ajaxReturn($data);
	}
	
	public function verifyemailcode(){
	    $limit_time = 120;
	    $email = I('post.email');
	    $code = I('post.code');
	
	    if (empty($email) && isset($_SESSION['email_old'])){
	        $email = $_SESSION['email_old'];
	        $setemail = '';
	    }else{
	        $setemail = $email;
	    }
	     
	    if(empty($_SESSION['sms_time']) || $_SESSION['sms_time']+$limit_time<time()) {
	        $data = array(
	                'status'=> '-1',
	                'msg' => '验证码已过期'
	        );
	        session('sms_time', null);
	        session('sms_code', null);
	        session('email', null);
	        $this->ajaxReturn($data);
	    }
	
	    //判断邮箱号码是否有效
	    if(empty($_SESSION['email']) || $_SESSION['email'] != $email){
	        $data = array(
	                'status'=> '-1',
	                'msg' => '邮箱错误或未填验证码'
	        );
	        $this->ajaxReturn($data);
	    }
	
	    //验证验证码是否正确
	    if(empty($_SESSION['sms_code']) || $_SESSION['sms_code'] != $code ){
	        $data = array(
	                'status'=> '-1',
	                'msg' => '验证码错误'
	        );
	        $this->ajaxReturn($data);
	    }else{
	        $data = array(
	                'status'=> '1',
	                'msg' => '验证通过',
	                'sub' => '1'
	        );
	        session('sms_time', null);
	        session('sms_code', null);
	        session('email', null);
	        session('email_old', null);
	        $_SESSION['verify_email'] = 1;
	        $this->ajaxReturn($data);
	    }
	}
}