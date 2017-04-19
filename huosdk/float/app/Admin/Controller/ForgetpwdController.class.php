<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class ForgetpwdController extends AdminbaseController{
	protected $member_model;
	
	function _initialize() {
		parent::_initialize();
		$this->member_model = M('members');
	}
	
	//浮点找回密码首页
	public function index(){
		$this->redirect('Float/Forgetpwd/index');
	    $data = sp_get_help();
	    $this->assign('QQ',$data['qq']);
		$this->display('sdkforget_pwd');
	}
	
 	//检查用户名
	public function checkUserName(){
        $type = I('type');
        $fusename = I('username');
	    if(isset($type) && "findpwd" == $type){
	        //不能为空
	        if (empty($fusename)) {
	            echo json_encode(array('success'=>false,'msg'=>'账号不能为空.'));
	            exit;
	        }
	        //查询用户名是否存在
	        $where['username'] = $fusename;
	        $id = $this->member_model->where($where)->getField('id');
	        if($id > 0){
	            echo json_encode(array('success'=>true));
	            exit;
	        }else{
	            echo json_encode(array('success'=>false,'msg'=>'账号不存在,请重新输入！'));
	            exit;
	        }
	    }
	}
	
	//检查账户是否绑定手机
	 public function checkUserPhone(){
	    $username = I('username');
	    $data['username'] = $username;
	    $mobile = $this->member_model->where($data)->getField('mobile');
	    if($mobile){
	        $this->assign('username',$username);
	        $this->assign('smobile',$mobile);
	        $this->display('sdkforget_three');
	    }else{
	    	$msg = "该账号未绑定手机，请联系客服找回密码！";
	    	$this->assign("msg",$msg);
	        $this->display('sdkforget_two');
	    }
	}
	
	public function modelSelect(){
		$username = I('username');
		$contact = M("members") ->field("mobile,email,username")->where(array("username"=>$username))->find();
		//判断邮箱是否绑定
		$emailstr = "邮箱未绑定";
		if($contact['email']){
			$emailstr = "邮箱已绑定";
		}
		
		//判断手机是否绑定
		$mobilestr = "手机未绑定";
		if($contact['mobile']){
			$mobilestr = "手机已绑定";
		}
		
		$this->assign("emailstr",$emailstr);
		$this->assign("mobilestr",$mobilestr);
		$this->assign("contact",$contact);
		$this->display("modelselect");
	}
	
		public function checkUserEmail(){
		$username = I('username');
		$data['username'] = $username;
		$email = $this->member_model->where($data)->getField('email');
		if($email){
			$this->assign('username',$username);
			$this->assign('email',$email);
			$this->display('sendemail');
		}else{
			$msg = "该账号未绑定邮箱，请联系客服找回密码！";
			$this->assign("msg",$msg);
			$this->display('sdkforget_two');
		}
	}
	
	//验证手机并发送短信
	public function sendMsg(){
		$limit_time = 60;
	    $userphone = I("phone");
	    
		if(isset($_SESSION['mobile']) && $_SESSION['mobile']==$userphone && $_SESSION['sms_time']+$limit_time>time()){
			$result->success = 0;
			$result->msg = '已发送验证码';
			echo json_encode($result);
			exit;
		}   
	
        $res = alidayuSend($userphone);
		$result = new \StdClass();		
		if(0 == $res['code']){
			$_SESSION['sms_time'] = time();
			$_SESSION['mobile'] = $userphone;
			
			$result->success = $res['code'];
			$result->msg = $res['msg'];
			echo json_encode($result);
		}else{
		    $result->success = 0;
			$result->msg = $res['msg'];
			echo json_encode($result);
		}
	}
	
	//验证邮箱并发送密码
	public function sendEmail(){
			$postemail = I("email");
			$username = I("username");
			
			//验证邮箱格式是否正确，密码是否为空
			$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
			if (!preg_match($pattern, $postemail)){
				$this->redirect('-1');
			}
			
			$userpwd= rand(1000,9999);  //生成随机的4位验证码
			$_SESSION['sms_code'] = $userpwd;
			$time= sp_auth_code(time(),"ENCODE");

            //发送邮件方法
            $emailinfo = $this->sendEmailMsg($username,$userpwd,$time,$postemail);
            $result = new \StdClass();
		if(0 == $emailinfo['error']){
			$result->success = 1;
			$result->msg = "发送成功";
			echo json_encode($result);
		}else{
			$result->success = 0;
			$result->msg = $mailerror;
			echo json_encode($result);
		}
	}
	
	//发送邮件信息函数
	public function sendEmailMsg($username,$userpwd,$time,$postemail){
		$message = "<div style='width:680px;padding:0 10px;margin:0 auto;'>
				<div style='line-height:1.5;font-size:14px;margin-bottom:25px;color:#4d4d4d;'>
				<strong style='display:block;margin-bottom:15px;'>亲爱的玩家：".$username." 您好！</strong>
				<p>您使用了本站提供的密码重置服务，你的验证码为:".$userpwd.",如果您确认此密码重置功能是你启用的,请尽快登陆游戏重置您的密码<br>
				</p>
				</div>
				<div style='padding:10px 10px 0;border-top:1px solid #ccc;color:#999;margin-bottom:20px;line-height:1.3em;font-size:12px;'>
		    	<p style='margin-bottom:15px;'>此为系统邮件，请勿回复<br  />
		     	请保管好您的密码，避免游戏账户被他人盗用</p>
		   	    <p>Copyright 2004-2015 All Right Reserved</p>
		  		</div>";
		$subject = "邮箱验证码";

		$emailinfo = $this->sp_send_email($postemail, $subject, $message);
		return $emailinfo;
	}
		
	function sp_send_email($address, $subject, $message){
		$email_arr = sp_get_emailinfo();
	
		import("PHPMailer");
		$mail=new \PHPMailer();
		// 设置PHPMailer使用SMTP服务器发送Email
		$mail->IsSMTP();
		$mail->IsHTML(true);
		// 设置邮件的字符编码，若不指定，则为'UTF-8'
		$mail->CharSet='UTF-8';
		// 添加收件人地址，可以多次使用来添加多个收件人
		$mail->AddAddress($address);
		// 设置邮件正文
		$mail->Body = $message;
		// 设置邮件头的From字段。
		$mail->From = $email_arr['address'];
		// 设置发件人名字
		$mail->FromName= $email_arr['sender'];;
		// 设置邮件标题
		$mail->Subject = $subject;
		// 设置SMTP服务器。
		$mail->Host = $email_arr['smtp'];
		// 设置SMTP服务器端口。
		$port = $email_arr['smtp_port'];
		$mail->Port=empty($port)?"25":$port;
		// 设置为"需要验证"
		$mail->SMTPAuth=true;
		// 设置用户名和密码。
		$mail->Username=$email_arr['username'];
		$mail->Password=$email_arr['password'];
		//echo 'b';dump($mail);exit;
		// 发送邮件。
		if(!$mail->Send()) {
			//echo 'a';exit;
			$mailerror=$mail->ErrorInfo;
			return array("error"=>1,"message"=>$mailerror);
		}else{
			return array("error"=>0,"message"=>"success");
		}
	}
	
	
	//比较两次密码是否相同
	public function checkCode(){
	    $pwd = I('pwd');
	    $new = I('newpwd');
	    if(!($pwd == $new)){
	       echo json_encode(array('success'=>false,'msg'=>'两次密码不一致'));
	       exit;
	    }
	}
	
 	//验证手机验证码
 	public function checkMobileCode(){
 	    $smscode = $_SESSION['sms_code'];
	    $code = I('code');
	    $result = new \StdClass();
	    if(!empty($code) && $smscode == $code){
	        $result->success = 1;
	        $result->msg = "验证码正确";
	        echo json_encode($result);
	    }else{
	        $result->success = 0;
	        $result->msg = "验证码错误";
	        echo json_encode($result);
	    } 
	} 
	
	//修改密码
	public function editPassword(){
	    //判断两次密码是否一致
	    $this->checkCode();
	    	    
	    //验证验证码是否正确
 	    $smscode = $_SESSION['sms_code'];
	    $code = I('code');
	    if(!empty($code) && $smscode == $code){
	        $password = I('newpwd');
	        $data['password'] = pw_auth_code($password);
	        $data['update_time'] = time();
	        $Members = M('members');
	        $data['username'] = I('post.username');
	        $rs =  $Members->where("username='%s'",$data['username'])->setField($data);
	        $result = new \StdClass();
	        if ($rs>0) {
	            $url = U('Admin/Forgetpwd/successMsg');
	            $result->success = true;
	            $result->url = $url;
	            echo json_encode($result);
	            exit;
	        }else{
	            $url = U('Admin/Forgetpwd/index');
	            $result->success = false;
	            $result->msg = "修改密码失败,请重新修改";
	            $result->url = $url;
	            echo json_encode($result);
	            exit;
	        }
	    }else{
	        $url = U('Admin/Forgetpwd/index');
	        $result->success = false;
	        $result->msg = "验证码错误";
	        $result->url = $url;
	        echo json_encode($result);
	        exit;
	    }
	}
	
	//修改成功提示页面
	public function successMsg(){
	    $this->assign('msg','修改密码成功');
	    $this->display('sdkpwdsu');
	} 
}