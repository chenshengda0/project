<?php
namespace Float\Controller;
use Common\Controller\AdminbaseController;

class BindingController extends AdminbaseController{
	protected $member_model;
	
	function _initialize() {
		parent::_initialize();
		$this->member_model = M('members');
	}
	
	//账户安全首页
	public function index(){
        $userdata = sp_get_user_info();
        if (!empty($userdata['mobile'])){
            $_SESSION['mobile_old'] = $userdata['mobile'];
            $userdata['mobile'] = preg_replace('/(^.*)\d{4}(\d{4})$/','\\1****\\2',$userdata['mobile']);
            $_SESSION['setmobile'] = 1;
            
        }else{
            $_SESSION['setmobile'] = 2;
        }
        
        if (!empty($userdata['email'])){
            $str = $userdata['email'];
            $_SESSION['email_old'] =$str;
            $email_array = explode("@",$str);
            $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($str, 0, 3); //邮箱前缀
            $count = 0;
            $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count);
            $userdata['email'] = $prevfix . $str;
            $_SESSION['setemail'] = 1;
        }else{
            $_SESSION['setemail'] = 2;
        }
        
        $this->assign("title", '账户安全');
        $this->assign('userdata', $userdata);    
        $this->display();
	}
	
	/*
	 * 用户更换绑定手机流程
	 * 1、 验证原手机
	 * 2、 绑定现手机
	 * 3、 绑定成功
	 * 
	 * 1、 验证原手机
	 */
	public function mobile_verify(){
	    $userdata = sp_get_user_info();
	    if (!empty($userdata['mobile'])){
	        $userdata['mobile'] = preg_replace('/(^.*)\d{4}(\d{4})$/','\\1****\\2',$userdata['mobile']);
	    }
	    
	    $this->assign("title", '账户安全');
	    $this->assign('userdata', $userdata);
	    $this->display();
	}
	
	/*
	 * 用户更换绑定手机流程
	 * 1、 验证原手机
	 * 2、 绑定现手机
	 * 3、 绑定成功
	 *
	 * 1、 验证原手机
	 */
	public function mobile_set(){
	    if ($_SESSION['setmobile'] != 2){
	        $this->mobile_error();
	        exit;
	    }
	    $_SESSION['setmobile'] = 2;
	    $title = "设置号码";
	    $this->assign('title',$title);
	    $this->display();
	}
	
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
	    }
	    
	    $userdata['id'] = sp_get_current_user();
	    $userdata['mobile'] = $setmobile;
	    $rs = M('members')->save($userdata);
	    if (false != $rs){
	        $data = array(
	                'status'=> '1',
	                'msg' => '修改成功',
	                'sub' => '1'
	        );
	        session('sms_time', null);
	        session('sms_code', null);
	        session('mobile', null);
	        session('mobile_old', null);
	        if (empty($setmobile)){
	            $_SESSION['setmobile'] = 2;
	        }
	        $this->ajaxReturn($data);
	    }else{
	        $data = array(
	                'status'=> '-1',
	                'msg' => '内部服务器错误',
	        );
	        $this->ajaxReturn($data);
	    }
	}
	
	//操作成功跳转页面
	public function mobile_success(){
	    $title = "绑定手机";
	    $msg = "手机绑定成功";
	    $this->assign('title',$title);
	    $this->assign('msg',$msg);
	    $this->display('Binding/success');
	}
	
	//操作成功跳转页面
	public function mobile_error(){
	    $title = "绑定手机";
	    $msg = "手机绑失败";
	    $this->assign('title',$title);
	    $this->assign('msg',$msg);
	    $this->display('Binding/error');
	}

	/*
	 * 用户更换绑定邮箱
	 * 1、 验证原邮箱
	 * 2、 绑定现邮箱
	 * 3、 绑定成功
	 *
	 * 1、 验证原邮箱
	 */
	public function email_verify(){
	    $userdata = sp_get_user_info();
	     
	    if (!empty($userdata['email'])){
	        $str = $userdata['email'];
	         
	        $email_array = explode("@",$str);
	        $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($str, 0, 3); //邮箱前缀
	        $count = 0;
	        $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count);
	        $userdata['email'] = $prevfix . $str;
	    }
	     
	    $this->assign("title", '账户安全');
	    $this->assign('userdata', $userdata);
	    $this->display();
	}
	
	/*
	 * 用户更换绑定邮箱流程
	 * 1、 验证原邮箱
	 * 2、 绑定现邮箱
	 * 3、 绑定成功
	 *
	 * 1、 验证原邮箱
	 */
	public function email_set(){
	    if ($_SESSION['setemail'] != 2){
	        $this->email_error();
	        exit;
	    }
	    $_SESSION['setemail'] = 2;
	    $title = "设置邮箱";
	    $this->assign('title',$title);
	    $this->display();
	}
	
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
	    }
	     
	    $userdata['id'] = sp_get_current_user();
	    $userdata['email'] = $setemail;
	    $rs = M('members')->save($userdata);
	    if (false != $rs){
	        $data = array(
	                'status'=> '1',
	                'msg' => '修改成功',
	                'sub' => '1'
	        );
	        session('sms_time', null);
	        session('sms_code', null);
	        session('email', null);
	        session('email_old', null);
	        if (empty($setemail)){
	            $_SESSION['setemail'] = 2;
	        }
	        $this->ajaxReturn($data);
	    }else{
	        $data = array(
	                'status'=> '-1',
	                'msg' => '内部服务器错误',
	        );
	        $this->ajaxReturn($data);
	    }
	}
	
	//操作成功跳转页面
	public function email_success(){
	    $title = "绑定邮箱";
	    $msg = "邮箱绑定成功";
	    $this->assign('title',$title);
	    $this->assign('msg',$msg);
	    $this->display('Binding/success');
	}
	
	//操作成功跳转页面
	public function email_error(){
	    $title = "绑定邮箱";
	    $msg = "邮箱绑失败";
	    $this->assign('title',$title);
	    $this->assign('msg',$msg);
	    $this->display('Binding/error');
	}
}