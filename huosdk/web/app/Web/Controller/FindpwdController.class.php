<?php

/**
 * 找回密码
 */
namespace Web\Controller;
use Common\Controller\HomebaseController;

class FindpwdController extends HomebaseController {
    
    public function _initialize() {
        parent::_initialize();
    }
    
    public function index(){
    	$show = I('show');
		if($show == 'findpwd'){
			$this ->display('my_findpwd');
			exit();
		}

		$username = I('username','');
		$femail['fasong'] = '0';
		$femail['checkemail'] = 0;

		if($username != ''){
			$userdate = searchuser($username);   //获取用户的信息
		}
		
		$checkemail = 0;//邮箱验证变量
		//发送邮箱界面
		if($show == 'two' && $userdate){
			//检查是否存在邮箱
			if($userdate['email'] == '' || empty($userdate['email'])){
				$femail['checkemail'] = 1;
			}
			$this->assign('femail',$femail);
			$this->assign('userdate',$userdate);
			$this->display('findpwd_two');
			exit();
		}

		//发送邮件
		if(I('action') == 'findpwd'){
			$password = $userdate['password'];
			$username = $userdate['username'];
			$postemail = $userdate['email'];
			$time = time();
			$emailinfo = $this->sendEmailMsg($username,$password,$time,$postemail);
			if(1 == $emailinfo['error']) {
				$this->error("邮件发送失败 <p>邮件错误信息:".$emailinfo['message']);
			}else { 
				$femail['fasong'] = '1';
				$webemail=explode('@',$userdate['email']);
				$webl = strtolower($webemail[1]);
				$femail['url'] = "mail.".$webl;
				
				$this ->assign('femail',$femail);
				$this ->assign('userdate',$userdate);
				$this ->display('findpwd_two');
				exit();
			}    
		}

		//链接到修改密码界面
		$user = I('u');  
		$array = explode(',',base64_decode($user)); 
		if(isset($_GET['u'])){
			$time = $array[2];
			$password = $array[1];
			$userid = checknamepwd($array[0],$password);
			if($userid){
				if(time() - $time > 60*60){ 
					echo "<script>alert('验证邮箱已过有效期,请重新申请');</script>";
					echo "<meta http-equiv=refresh content=0;URL=".U('Web/Findpwd/index').">";	
					exit(); 
				}
				$this ->assign('username',$array[0]);
				$this ->assign('mem_id',$userid);
				$this ->display('findpwd_thr');
				exit();
			}else {
				echo "<script>alert('验证失败,请重新申请或联系客服');</script>";
				echo "<meta http-equiv=refresh content=0;URL=".U('Web/Findpwd/index').">";	
				exit(); 
			}
		}
	
		//修改密码
		if(isset($_POST['action']) && $_POST['action'] == 'uppwd'){
			
			//获取参数
			$oldpwd = $userdate['password'];
			$newpwd = I('password');
			$mem_id = I('mem_id');
			
			$rs = updatepwd($username,$newpwd,$oldpwd,$mem_id);
			
			if($rs > 0){
				//同步退出ucenter by wuyonghong
				//if (UCENTER_ENABLED){
				//	echo uc_user_synlogout();
					//$db -> sql_connect($dbhost,$dbuser,$dbpwd,$dbname,$dbport, false, false);
				//}
				//setcookie('sdkuser',NULL,-86400,'/');
				//setcookie('xsst_id',$userid,mktime()+86400,'/');
				$this ->display('findpwd_four');//修改成功后界面
				exit();
			}else{
				echo "<script>alert('修改失败');history.go(-1);</script>";
				exit();
			} 
		}
		
		$this ->display('findpwd_one');
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
	        $data['username'] = $fusename;
	        $id = M('members')->where($data)->getField('id');
	        if($id > 0){
	            echo json_encode(array('success'=>true));
	            exit;
	        }else{
	            echo json_encode(array('success'=>false,'msg'=>'账号不存在,请重新输入！'));
	            exit;
	        }
	    }
	}
	
	//发送邮件信息函数
    public function sendEmailMsg($username,$userpwd,$time,$postemail){

    	//$cid = sp_get_current_cid();
    	$user = base64_encode($username.",".$userpwd.",".$time.",".$postemail);
    	$subject = "官方邮箱验证";
    	$url = 'http://'.$_SERVER['SERVER_NAME'].U('Web/Findpwd/index',array('u'=>$user));
    	$message = "<div style='width:680px;padding:0 10px;margin:0 auto;'>
				<div style='line-height:1.5;font-size:14px;margin-bottom:25px;color:#4d4d4d;'>
				<strong style='display:block;margin-bottom:15px;'>亲爱的玩家：".$username." 您好！</strong>
				<p>您使用了本站提供的密码找回功能，如果您确认此密码找回功能是你启用的，请点击下面的链接,<br>
				该链接在3个小时内有效，请在有效时间内操作：
				</p>
				</div>
				<div style='margin-bottom:30px;'><strong style='display:block;margin-bottom:20px;font-size:14px;'>
				<a target='_blank' style='color:#f60;' href='".$url."'>找回密码</a></strong>
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