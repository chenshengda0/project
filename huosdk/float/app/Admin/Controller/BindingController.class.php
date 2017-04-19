<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class BindingController extends AdminbaseController{
	protected $member_model;
	
	function _initialize() {
		parent::_initialize();
		$this->member_model = M('members');
	}
	
	//用户邮箱绑定界面
	public function index(){
		$this->redirect('Float/Binding/index');
	    $mem_id = sp_get_current_user();
	    $email = $this->member_model->where(array('id'=>$mem_id))->getField('email');
	    
	    $update = I('post.upemail');
		$username = $_SESSION['username'];
	    //已绑定邮箱
	    if(!empty($email) && $update !="update"){
	        $this->assign('username',$username);
	        $this->assign('email',$email);
	        //进入已绑定邮箱界面
	        $this->display('emailbinding');
	        exit;
	    }
	    
	    //未绑定邮箱
	    $this->display('noemail');
	}
	
	/*
	 * 用户更换邮箱流程
	 * 1、 验证原邮箱
	 * 2、绑定现邮箱
	 * 3、绑定成功
	 */
    public function updatemail(){
	    $this->display('emailcheckold_f');
	}

	//绑定邮箱提交函数
	public function usermail_post(){
	    $postemail = I('post.email');
	    $postpwd = I('post.pwd');
	    $action = I('post.action');
	    $mem_id = sp_get_current_user();
	    
	    //验证邮箱格式是否正确，密码是否为空
	    $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
	    if (!preg_match($pattern, $postemail) || empty($postpwd)){
	        $this->redirect('-1');
	    }
	    
        $postpwd = pw_auth_code($postpwd);
        $userpwd = $this->member_model->where(array('id'=>$mem_id))->getField('password'); 
        if($userpwd == $postpwd){
			$mem_id = sp_auth_code($mem_id,"ENCODE");
			$userpwd= sp_auth_code($userpwd,"ENCODE");
			$time= sp_auth_code(time(),"ENCODE");
            //发送邮件方法
            $emailinfo = $this->sendEmailMsg($mem_id,$userpwd,$time,$postemail);
            if(1 == $emailinfo['error']) {
                $this->error("邮件发送失败 <p>邮件错误信息:".$emailinfo['message']);
            } else {
                $this->assign('msg',"邮件发送成功,有效时间30分钟, 请登陆邮箱操作.");
                $this->display('emailupdate_s');
                exit();
            }
        } else {
            $this->assign('msg',"绑定失败,密码输入错误.");
            $this->display('index');
            exit();
        }
	}
	
	/*
	 * 一个邮箱能绑定多个账号
	 * 
	 */
    public function ajaxEmail(){
	    $email = I('post.email');
        $pwd = I('post.pwd');
        
	    $data['status'] = 0;
	    $data['msg'] = '';
	    
	    //不能为空
	    if (empty($email)) {
	        $data['msg']  = '邮箱不能为空.';
	        $this->ajaxReturn($data);
	    }
	    
	    //不能为空
	    if (empty($pwd)) {
	        $data['msg']  = '密码不能为空.';
	        $this->ajaxReturn($data);
	    }
	    $cid = sp_get_current_cid();
	    
	    $pwd = pw_auth_code($pwd);

	    $mem_id = sp_get_current_user();
	    $field = "password,email";
	    $userdata = $this->member_model->field($field)->where(array('id'=>$mem_id))->find();

	    if ($pwd != $userdata['password']) {
	        $data['msg']  = '密码错误，请重新填写.';
	        $this->ajaxReturn($data);
	    }
	    	    
	    $data['status'] = 1;
	    $this->ajaxReturn($data);
	}
	
	//用户手机绑定界面
	public function mobile(){
		$this->redirect('Float/Binding/index');
	    $mem_id = sp_get_current_user();
	    $mobile = $this->member_model->where(array('id'=>$mem_id))->getField('mobile');
	     
	    $update = I('post.upmobile');
	    //已绑定手机
	    if(!empty($mobile) && $update !="update"){
	        $this->assign('username',$_SESSION['username']);
	        $this->assign('mobile',$mobile);
	        //进入已绑定手机界面
	        $this->display('mobilebinding');
	        exit;
	    }
	    //未绑定手机
	    $this->display('nomobile');
	}
	
	//判断手机号码是否存在
 	public function ajaxMobile(){
	    $mobile = I('post.mobile');
	    $pwd = I('post.pwd');
	    $code = I('post.code');
	    $data['status'] = 0;
	    $data['msg'] = '';
	    
	    //不能为空
	    if (empty($mobile)) {
	        $data['msg']  = '手机号码不能为空.';
	        $this->ajaxReturn($data);
	    }
	    
	    //验证验证码
	   if(empty($code) ||  $code != session('sms_code')){
	       $data['msg']  = '验证码错误.';
	       $this->ajaxReturn($data);
	    } 
	    
	    //不能为空
	    if (empty($pwd)) {
	        $data['msg']  = '密码不能为空.';
	        $this->ajaxReturn($data);
	    }

	    $pwd = pw_auth_code($pwd);	
	    $mem_id = sp_get_current_user();
	    $field = "password,mobile";
	    $userdata = $this->member_model->field($field)->where(array('id'=>$mem_id))->find();
	
	    if ($pwd != $userdata['password']) {
	        $data['msg']  = '密码错误，请重新填写.';
	        $this->ajaxReturn($data);
	    }
	     
	    $data['status'] = 'success';
	    $this->ajaxReturn($data);
	}
	
	/* 用户更换手机流程
	 * 1、验证手机号码
	 * 2、绑定新号码
	 * 3、绑定成功
	 */
	public function updatemobile(){
	    $update = I('post.upmobile');
	    $this->display('nomobile');
	}
	
	//发送手机验证码
	public function sendMsg(){
	    $userphone = I("phone");

	    //发送短信函数
	    $res = alidayuSend($userphone);

	    $result = new \StdClass();
	    if(0 == $res['code']){
	        $result->success = 1;
	        $result->msg = $res['msg'];
	        echo json_encode($result);
	    }else{
	        $result->success = $res['code'];
	        $result->msg = $res['msg'];
	        echo json_encode($result);
	    }
	}

	//绑定手机提交函数
	public function userMobilePost(){
	    $postmobile = I('post.mobile');
	    $postpwd = I('post.pwd');
	    $username = session('user');
	    
	    $pattern = "/^1[\d]{10}$/";
	    if (!preg_match($pattern, $postmobile) || empty($postpwd)){
	        //$this->redirect('-1');
	        echo "<script>javascript:history.back(-1);</script>";
	    }
	     
	    $mem_id = sp_get_current_user();
	    $action = I('post.action');
	     
        $postpwd = pw_auth_code($postpwd);
        $result = $this->member_model->field('password,mobile')->where(array('id'=>$mem_id))->find();
        $userpwd = $result['password'];
        $mobile = $result['mobile'];
        
        //判断手机号码是否修改
        if($mobile == $postmobile){
            $this->assign('msg','手机号没有修改');
            $this->display('mobilesu');
            exit;
        }
        
        //判断用户输入密码是否正确
        if($userpwd == $postpwd){
            $data['id'] = sp_get_current_user();
            $map['mobile'] = $postmobile;
            $res =  $this->member_model->where($data)->setField($map);
            if($res){
                $this->assign('msg','手机绑定成功');
                $this->display('mobilesu');
                exit;
            }else{
                $this->assign('msg','手机绑定失败');
                $this->display('mobilesu');
                exit;
            }
        } else {
            $this->assign('msg',"绑定失败,密码输入错误.");
            $this->display('index');
            exit();
        }
	}  
	
	//发送邮件信息函数
	public function sendEmailMsg($username,$userpwd,$time,$postemail){
	    $user = base64_encode($username.",".$userpwd.",".$time.",".$postemail);
	     
	    $subject = "官方邮箱验证";
	    $url = 'http://'.$_SERVER['SERVER_NAME'].U('Api/Checkemail/emailsafe',array('u'=>$user));
	    $message = "<div style='width:680px;padding:0 10px;margin:0 auto;'>
				<div style='line-height:1.5;font-size:14px;margin-bottom:25px;color:#4d4d4d;'>
				<strong style='display:block;margin-bottom:15px;'>亲爱的玩家：".$username." 您好！</strong>
				<p>您使用了本站提供的密码邮箱绑定功能，如果您确认此密码邮箱绑定功能是你启用的，请点击下面的链接,<br>
				该链接在3个小时内有效，请在有效时间内操作：
				</p>
				</div>
				<div style='margin-bottom:30px;'><strong style='display:block;margin-bottom:20px;font-size:14px;'>
				<a target='_blank' style='color:#f60;' href='".$url."'>确认绑定邮箱</a></strong>
			    <p style='color:#666;'><small style='display:block;font-size:12px;margin-bottom:5px;'>
				如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：
				</small><span style='color:#666;'>".$url."</span></p></div></div>
				<div style='padding:10px 10px 0;border-top:1px solid #ccc;color:#999;margin-bottom:20px;line-height:1.3em;font-size:12px;'>
		    	<p style='margin-bottom:15px;'>此为系统邮件，请勿回复<br  />
		     	请保管好您的邮箱，避免游戏账户被他人盗用</p>
		   	    <p>Copyright 2004-2015 All Right Reserved</p>
		  		</div>";
	    $emailinfo = sp_send_email($postemail, $subject, $message);
	    return $emailinfo;
	}
	
}